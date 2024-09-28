@extends('admin.layouts.sitelayout')
@section('header_scripts')
    <link href="{{ admin_asset('css/file/application.css') }}" rel="stylesheet">
    <link href="{{ admin_asset('css/user-article/list.css') }}" rel="stylesheet">
@stop
@section('content')
    <div class="main-container">
        <div class="search-featured-section">
            <div class="search-section-container">
                <div class="search-section container">
                    <span class="search-text">Tìm kiếm bài viết</span>
                    <div class="search-box">
                        <i class="fa fa-search"></i>
                        <form action="{{route('user-articles.list')}}" method="get">
                            <input type="text" class="search-input" placeholder="Nhập từ khoá..." name="search" value="{{$search}}">
                        </form>
                    </div>
                </div>
            </div>
            @if (is_null($search))
                <div class="featured-section container">
                    @php
                        $latest_article = $latest_articles->first();
                    @endphp
                    <div class="latest-article">
                        <a href="{{ route('user-articles.detail', $latest_article->slug) }}">
                            <h2 class="article-title line-clamp-2">
                                {{ $latest_article->title }}
                            </h2>
                        </a>
                        <span class="article-description line-clamp-3">
                            {{ $latest_article->description }}
                        </span>
                        <div class="article-info">
                            <div class="article-type">
                                {{ $latest_article->category->name }}
                            </div>
                            <div class="article-publish">
                                <i class="fa fa-calendar"></i>
                                {{ $latest_article->published_at->format('d/m/Y') }}
                            </div>
                        </div>
                        <a href="{{ route('user-articles.detail', $latest_article->slug) }}">
                            <div class="article-img rounded-lg">
                                <img src="{{ url($latest_article->thumbnail) }}" alt="Ảnh bài viết" class="contain-img">
                            </div>
                        </a>
                    </div>
                    <div class="featured-list">
                        @foreach ($latest_articles->slice(1) as $article)
                            <div class="featured-item">
                                <div class="featured-info">
                                    <a href="{{ route('user-articles.detail', $article->slug) }}">
                                        <h4 class="article-title line-clamp-2">
                                            {{ $article->title }}
                                        </h4>
                                    </a>
                                    <span class="article-description line-clamp-2">
                                        {{ $article->description }}
                                    </span>
                                    <div class="featured-publish-type">
                                        <div class="article-type">
                                            {{ $article->category->name }}
                                        </div>
                                        <div class="article-publish">
                                            <i class="fa fa-calendar"></i>
                                            {{ $article->published_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="featured-img">
                                    <a href="{{ route('user-articles.detail', $article->slug) }}">
                                        <img src="{{ url($article->thumbnail) }}" alt="Ảnh bài viết" class="contain-img">
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        <div class="article-section container">
            <div class="article-list-container">
                @if(is_null($search))
                    <h2>Bài viết gần đây</h2>
                @else
                    <h2>Danh sách bài viết theo từ khoá: <strong class="primary-color">“{{ $search }}”</strong></h2>
                @endif
                <div class="article-list">
                    @foreach ($articles as $article_index => $article)
                        @php
                            if ($articles->currentPage() == 1 && $article_index < $featured_article_number && is_null($search)) continue;
                        @endphp
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
                            <a href="{{ route('user-articles.list_by_category', $category->slug) }}">
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