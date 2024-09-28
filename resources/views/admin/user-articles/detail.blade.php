@extends('admin.layouts.sitelayout')
@section('header_scripts')
    <link href="{{ admin_asset('css/file/application.css') }}" rel="stylesheet">
    <link href="{{ admin_asset('css/user-article/list.css') }}" rel="stylesheet">
    <link href="{{ admin_asset('css/user-article/detail.css') }}" rel="stylesheet">
@stop
@section('content')
    <div class="main-container">
        <div class="article-section container">
            <div class="article-content-container">
                <h1 class="font-weight-semibold mb-2">{{ $article->title }}</h1>
                <div class="related-info">
                    <div class="article-type">
                        {{ $article->category->name }}
                    </div>
                    <div class="article-publish">
                        <i class="fa fa-calendar"></i>
                        {{ $article->published_at->format('d/m/Y') }}
                    </div>
                </div>
                <div class="article-img mt-6">
                    <img src="{{ url($article->thumbnail) }}" alt="Ảnh bài viết" class="contain-img">
                </div>
                <div class="article-content mt-6">
                    {!! $article->content !!}
                </div>
                <h2 class="font-weight-semibold mt-8">Bài viết liên quan</h2>
                <div class="related-articles-container">
                    <div class="related-articles">
                        @foreach ($related_articles as $related_article)
                            <div class="related-item">
                                <div class="article-img">
                                    <a href="{{ route('user-articles.detail', $related_article->slug) }}">
                                        <img src="{{ url($related_article->thumbnail) }}" alt="Ảnh bài viết" class="contain-img">
                                    </a>
                                </div>
                                <div class="article-info">
                                    <div class="article-type">
                                        {{ $related_article->category->name }}
                                    </div>
                                    <div class="article-publish">
                                        <i class="fa fa-calendar"></i>
                                        {{ $related_article->published_at->format('d/m/Y') }}
                                    </div>
                                </div>
                                <a href="{{ route('user-articles.detail', $related_article->slug) }}">
                                    <h4 class="article-title line-clamp-2 font-weight-semibold">
                                        {{ $related_article->title }}
                                    </h4>
                                </a>
                                <span class="article-description line-clamp-3">
                                    {{ $related_article->description }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="category-container">
                <h2>Chuyên mục</h2>
                <div class="category-list">
                    @foreach ($categories as $category)
                        <div class="category-item">
                            <a href="{{ route('user-articles.list_by_category', $category->slug) }}" class="{{ $category->slug == $article->category->slug ? 'active' : '' }}">
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