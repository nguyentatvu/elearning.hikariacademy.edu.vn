@extends('admin.layouts.sitelayout')
@section('header_scripts')
    <link href="{{ admin_asset('css/file/application.css') }}" rel="stylesheet">
    <link href="{{ admin_asset('css/user-article/list.css') }}" rel="stylesheet">
@stop
@section('content')
    <div class="main-container">
        <div class="article-section container">
            <div class="article-list-container">
                <h2>Danh sách bài viết theo chuyên mục: <strong class="primary-color">“{{ $page_category->name }}”</strong></h2>
                <div class="article-list">
                    @foreach ($articles as $article)
                        <div class="article-item">
                            <div class="article-img">
                                <a href="{{ route('user-articles.detail', $article->slug) }}">
                                    <img src="{{ url($article->thumbnail) }}" alt="Ảnh bài viết" class="contain-img">
                                </a>
                            </div>
                            <div class="article-info">
                                <div class="article-type">
                                    {{ $article->category->name }}
                                </div>
                                <div class="article-publish">
                                    <i class="fa fa-calendar"></i>
                                    {{ $article->published_at->format('d/m/Y') }}
                                </div>
                            </div>
                            <a href="{{ route('user-articles.detail', $article->slug) }}">
                                <h4 class="article-title line-clamp-2">
                                    {{ $article->title }}
                                </h4>
                            </a>
                            <span class="article-description line-clamp-3">
                                {{ $article->description }}
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="article-pagination">
                    {{ $articles->links() }}
                </div>
            </div>
            <div class="category-container">
                <h2>Chuyên mục</h2>
                <div class="category-list">
                    @foreach ($categories as $category)
                        <div class="category-item">
                            <a href="{{ route('user-articles.list_by_category', $category->slug) }}" class="{{ $category->slug == $page_category->slug ? 'active' : '' }}">
                                <span>{{ $category->name }}</span>
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop