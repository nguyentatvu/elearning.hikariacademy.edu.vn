<?php

namespace App\Http\Controllers;

use App\Article;
use App\ArticleCategory;
use Illuminate\Http\Request;

class UserArticleController extends Controller
{
    /**
     * List all articles
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function list(Request $request) {
        $search = $request->search;

        $active_class = 'user-articles';
        $title = 'Danh sách bài viết';
        $featured_article_number = 4;

        $categories = ArticleCategory::all();
        $latest_articles = Article::with('category')
            ->where('status', Article::PUBLISHED_STATUS)
            ->latest('published_at')
            ->take($featured_article_number)->get();
        $articles = Article::with('category')
            ->when(
                !empty($search),
                function ($query) use ($search) {
                    $query->where('title', 'LIKE', '%' . $search . '%');
                }
            )
            ->where('status', Article::PUBLISHED_STATUS)
            ->latest('published_at')
            ->paginate(PAGE_ITEMS_LIMIT);

        return view('admin.user-articles.list', compact('articles', 'latest_articles', 'search', 'categories', 'featured_article_number', 'active_class', 'title'));
    }

    /**
     * List articles by category
     *
     * @param Request $request
     * @param string $category_slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function listByCategory(Request $request, string $category_slug) {
        $active_class = 'user-articles';
        $title = 'Danh sách bài viết';

        $page_category = ArticleCategory::where('slug', $category_slug)->first();
        if (!$page_category) {
            abort(404);
        }

        $categories = ArticleCategory::all();
        $articles = Article::with('category')
            ->where('category_id', $page_category->id)
            ->where('status', Article::PUBLISHED_STATUS)
            ->latest('published_at')
            ->paginate(PAGE_ITEMS_LIMIT);

        return view('admin.user-articles.list-by-category', compact('articles', 'categories', 'page_category', 'active_class', 'title'));
    }

    /**
     * Show article detail
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $article_slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function detail(Request $request, string $article_slug) {
        $active_class = 'user-articles';
        $title = 'Nội dung bài viết';

        $article = Article::with('category')->where('slug', $article_slug)->first();
        if (!$article) {
            abort(404);
        }
        $categories = ArticleCategory::all();
        $related_articles = Article::with('category')
            ->latest('published_at')
            ->where('status', Article::PUBLISHED_STATUS)
            ->where('category_id', $article->category_id)
            ->whereNotIn('id', [$article->id])
            ->take(4)
            ->get();

        return view('admin.user-articles.detail', compact('categories', 'article', 'related_articles', 'active_class', 'title'));
    }
}
