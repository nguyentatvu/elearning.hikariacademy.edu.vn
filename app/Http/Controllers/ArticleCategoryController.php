<?php

namespace App\Http\Controllers;

use App\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\Datatables\Datatables;

class ArticleCategoryController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Display a listing of article categories
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $active_class = 'articles';
        $page_title = 'Danh sách chuyên mục bài viết';

        $categories = ArticleCategory::orderBy('updated_at', 'desc')->paginate(PAGE_ITEMS_LIMIT);

        return view('admin.articles.categories.index', compact('categories', 'active_class', 'page_title'));
    }

    /**
     * Update or create an article categorie
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function updateOrCreate(Request $request) {
        $validationRules = [
            'category_name' => ['required', 'string', 'max:255'],
        ];
        if ($request->isMethod('post')) {
            if ($request->slug == '') {
                $request->merge(['slug' => str_slug($request->category_name)]);
            }

            $validationRules['slug'] = [Rule::unique('article_categories', 'slug')];
        }
        if ($request->isMethod('put')) {
            $validationRules['slug'] = ['required', 'string', 'max:255', Rule::unique('article_categories', 'slug')->ignore($request->id)];
            $validationRules['id'] = ['required', 'integer', 'exists:article_categories,id'];
        }

        $validator = Validator::make(
            $request->all(),
            $validationRules,
            [],
            [
                'category_name' => "Chuyên mục bài viết",
                'slug' => 'Slug',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'inputs' => $request->all(),
            ], 422);
        }

        $data = [
            'name' => $request->category_name,
            'slug' => $request->slug,
            'description' => $request->description,
            'updated_at' => now(),
        ];
        if ($request->isMethod('post')) {
            $data['created_at'] = now();
        }

        ArticleCategory::updateOrInsert([
            'id' => $request->id ?? null,
        ], $data);
    }

    /**
     * Delete an article category
     * @param  string $category_id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function delete(string $category_id) {
        $category = ArticleCategory::findOrFail($category_id);
        $category->delete();

        flash2('Thông báo', 'Chuyên mục bài viết đã được xóa!', 'success', 'flash_message_2');
        return redirect()->route('articles.categories.index');
    }
}