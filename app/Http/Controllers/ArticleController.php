<?php

namespace App\Http\Controllers;

use App\Article;
use App\ArticleCategory;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!checkRole(getUserGrade(2))) {
                prepareBlockUserMessage();
                return redirect('/');
            }
            return $next($request);
        });
    }

    /**
     * Page showing all articles
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request) {
        $active_class = 'articles';
        $page_title = 'Danh sách các bài viết';

        $articles = Article::with('category')
            ->when(($request->search_field && $request->filter_value), function($query) use($request) {
                if ($request->search_field == 'title') {
                    $query->where('title', 'LIKE', '%' . $request->filter_value . '%');
                    return;
                }

                $query->where($request->search_field, $request->filter_value);
            })
            ->orderBy('updated_at', 'desc')->paginate(PAGE_ITEMS_LIMIT);
        $statusList = config('constant.article.status');
        $categories = ArticleCategory::select('id', 'name')->get();

        return view('admin.articles.articles.index', compact('articles', 'statusList', 'categories', 'active_class', 'page_title'));
    }

    /**
     * Create new article
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create() {
        if(!Auth::check()) {
            return redirect('/login');
        }

        $active_class = 'articles';
        $page_title = 'Tạo bài viết';

        $articles = Article::with('category')
            ->orderBy('updated_at', 'desc')
            ->paginate(PAGE_ITEMS_LIMIT);
        $statusList = config('constant.article.status');
        $categories = ArticleCategory::select('id', 'name')->get();

        return view('admin.articles.articles.create', compact('articles', 'categories', 'statusList', 'active_class', 'page_title'));
    }

    /**
     * Store a new article
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function store(Request $request) {
        $validationRules = [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:article_categories,id'],
            'status' => ['required', 'integer', Rule::in(array_keys(config('constant.article.status')))],
            'thumbnail' => ['bail', 'required', 'file', 'mimes:jpeg,png,jpg,webp',],
            'content' => ['bail', 'required', 'string'],
            'description' => ['string', 'max:255'],
            'article_images' => ['bail', 'array'],
            'article_images.*' => ['bail', 'file', 'mimes:jpeg,png,jpg,webp',],
        ];
        $attributes = [
            'title' => 'Tiêu đề bài viết',
            'category_id' => 'Chuyên mục bài viết',
            'status' => 'Trạng thái bài viết',
            'thumbnail' => 'Thumbnail bài viết',
            'content' => 'Nội dung bài viết',
            'article_images' => 'Hình ảnh của bài viết',
            'article_images.*' => 'Hình ảnh của bài viết',
        ];

        $validator = Validator::make(
            $request->all(),
            $validationRules,
            [],
            $attributes
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $articleImagePath = config('constant.article.upload_path');
        $imageService = new ImageService($articleImagePath);
        $imagePathList = [];

        // Save article images
        if ($request->article_images) {
            foreach ($request->article_images as $image) {
                $imagePath = $imageService->save($image);

                $imageId = preg_replace('/\.[^.]+$/', '', $image->getClientOriginalName());
                $imagePathList[$imageId] = "/public/" . $imagePath;
            }
        }
        //Save article thumbnail
        $thumbnailFileName = 'article_thumbnail_' . time() . '.' . $request->thumbnail->guessClientExtension();
        $thumbnailPath = '/public/' . $imageService->save($request->thumbnail, $thumbnailFileName);

        $insertData = [
            'title' => $request->title,
            'category_id' => $request->category_id,
            'author_id' => Auth::user()->id,
            'slug' => str_slug($request->title) . '-' . mt_rand(100000, 999999),
            'content' => $this->processArticleCotent($request->content, $imagePathList),
            'description' => $request->description,
            'status' => $request->status,
            'thumbnail' => $thumbnailPath,
            'images' => empty($imagePathList) ? $imagePathList : collect($imagePathList)->values()->all(),
        ];
        if ($request->status == Article::PUBLISHED_STATUS) {
            $insertData['published_at'] = now();
        }
        $newArticle = Article::create($insertData);

        flash2('Thông báo', 'Bài viết được tạo thành công!', 'success');
        return response()->json([
            'success' => true,
            'redirect' => route('articles.articles.detail', $newArticle->id),
        ]);
    }

    /**
     * Show article detail
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $articleId
     */
    public function detail(Request $request, string $articleId) {
        $active_class = 'articles';
        $page_title = 'Cập nhật bài viết';

        try {
            $article = Article::with('category')->findOrFail($articleId);
        }
        catch(ModelNotFoundException $e) {
            flash2('Thông báo', 'Không tìm thấy bài viết!', 'error');
            return redirect()->route('articles.articles.index');
        }

        $statusList = config('constant.article.status');
        $categories = ArticleCategory::select('id', 'name')->get();

        return view('admin.articles.articles.detail', compact('article', 'categories', 'statusList', 'active_class', 'page_title'));
    }

    /**
     * Delete article image
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function deleteArticleImage(Request $request) {
        $articleId = $request->input('article_id');
        $imageId = $request->input('image_id');

        if (empty($articleId) || empty($imageId)) {
            return response('Missing data!', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $article = Article::findOrFail($articleId);
        }
        catch(ModelNotFoundException $e) {
            return response('Không tìm thấy bài viết, vui lòng liên hệ những admin khác!', Response::HTTP_NOT_FOUND);
        }
        $imagePathList = array_diff($article->images, [$imageId]);
        $article->images = $imagePathList;
        $article->save();

        app(ImageService::class)->remove(str_replace('/public', public_path(), $imageId));
    }

    /**
     * Update article
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $articleId
     */
    public function update(Request $request, string $articleId) {
        try {
            $article = Article::findOrFail($articleId);
        }
        catch (ModelNotFoundException $e) {
            flash2('Thông báo', 'Bài viết có thể đã bị xóa trước đó, vui lòng liên hệ những admin khác!', 'error');
            return response()->json([
                'success' => false,
                'redirect' => route('articles.articles.index'),
            ], 404);
        }

        $validationRules = [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:article_categories,id'],
            'status' => ['required', 'integer', Rule::in(array_keys(config('constant.article.status')))],
            'thumbnail' => ['bail', 'file', 'mimes:jpeg,png,jpg,webp',],
            'content' => ['bail', 'required', 'string'],
            'description' => ['string', 'max:255'],
            'added_article_images' => ['bail', 'array'],
            'added_article_images.*' => ['bail', 'file', 'mimes:jpeg,png,jpg,webp',],
        ];
        $attributes = [
            'title' => 'Tiêu đề bài viết',
            'category_id' => 'Chuyên mục bài viết',
            'status' => 'Trạng thái bài viết',
            'thumbnail' => 'Thumbnail bài viết',
            'content' => 'Nội dung bài viết',
            'added_article_images' => 'Hình ảnh của bài viết',
            'added_article_images.*' => 'Hình ảnh của bài viết',
        ];

        $validator = Validator::make(
            $request->all(),
            $validationRules,
            [],
            $attributes
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $articleImagePath = config('constant.article.upload_path');
        $imageService = new ImageService($articleImagePath);
        $imagePathList = [];

        // Save article images
        if ($request->added_article_images) {
            foreach ($request->added_article_images as $image) {
                $imagePath = $imageService->save($image);

                $imageId = preg_replace('/\.[^.]+$/', '', $image->getClientOriginalName());
                $imagePathList[$imageId] = "/public/" . $imagePath;
            }
        }

        // Save new thumbnail and remove old one
        if ($request->thumbnail) {
            $thumbnailFileName = 'article_thumbnail_' . time() . '.' . $request->thumbnail->guessClientExtension();
            $thumbnailPath = '/public/' . $imageService->save($request->thumbnail, $thumbnailFileName);
            $imageService->remove(str_replace('/public', public_path(), $article->thumbnail));

            $article->thumbnail = $thumbnailPath;
        }

        $article->images = \array_merge($article->images, empty($request->added_article_images) ? [] : collect($imagePathList)->values()->all());
        $article->title = $request->title;
        $article->category_id = $request->category_id;
        $article->status = $request->status;
        $article->content = $this->processArticleCotent($request->content, $imagePathList);
        $article->description = $request->description;
        if ($request->status == Article::PUBLISHED_STATUS && empty($article->published_at)) {
            $article->published_at = now();
        }
        $article->save();

        flash2('Thông báo', 'Bài viết được cập nhật thành công!', 'success');
    }

    /**
     * Delete article
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $articleId
     *
     * @return mixed
     */
    public function delete(Request $request, string $articleId) {
        try {
            $article = Article::findOrFail($articleId);
        }
        catch (ModelNotFoundException $e) {
            flash2('Thông báo', 'Bài viết có thể đã bị xóa trước đó, vui lòng liên hệ những admin khác!', 'error');
            return back();
        }

        $imageService = app(ImageService::class);

        foreach ($article->images as $image) {
            $imageService->remove(str_replace('/public', public_path(), $image));
        }
        $imageService->remove(str_replace('/public', public_path(), $article->thumbnail));

        $article->delete();

        flash2('Thông báo', 'Bài viết được xóa thành công!', 'success');
        return back();
    }

    /**
     * Process article content
     *
     * @param string $rawContent
     * @param array $imagePathList
     * @return string
     */
    private function processArticleCotent(string $rawContent, array $imagePathList) {
        foreach ($imagePathList as $imageId => $imagePath) {
            $rawContent = str_replace($imageId, $imagePath, $rawContent);
        }

        return preg_replace('/\r\n/', '', $rawContent);
    }
}
