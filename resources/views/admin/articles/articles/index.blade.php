@extends('admin.layouts.owner.ownerlayout')

@section('header_scripts')
    <link href="{{ admin_asset('css/sweetalert2.css') }}" rel="stylesheet">
    <style>
        .btn-preview {
            padding-right: 6px;
            padding-left: 6px;
            position: relative;
            bottom: 1px;
        }

        .panel-upperbody {
            padding: 15px;
            border-bottom: 1px solid #f2f2f2;
        }

        .filter-container {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filter-container select{
            width: 220px;
            max-height: 34px;
            min-height: 34px;
        }

        .filter-container input{
            width: 220px;
            max-height: 34px;
            min-height: 34px;
        }

        .article-preview {
            padding-left: 12px;
            padding-right: 12px;
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .article-preview p{
            display: block;
            margin-block-start: 1em;
            margin-block-end: 1em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            unicode-bidi: isolate;
        }

        .btn-transparent {
            border: none;
            background: transparent;
        }

        .modal-dialog.modal-lg {
            width: 1100px;
        }
    </style>
@endsection

@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{ PREFIX }}"><i class="mdi mdi-home"></i></a> </li>
                        <li>{{ $page_title }}</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="panel panel-custom">
            <div class="panel-heading">
                <div class="pull-right messages-buttons">
                    <a href="{{ route('articles.articles.create') }}" class="btn btn-primary button category-save" onclick="">Tạo mới</a>
                </div>
                <h1>{{ $page_title }}</h1>
            </div>
            <div class="panel-upperbody">
                <form action="{{ route('articles.articles.index') }}" method="get">
                    <div class="filter-container">
                        <span style="">Lọc bài viết:</span>
                        <select name="search_field" class="form-control">
                            <option value="title">Tiêu đề</option>
                            <option value="category_id">Chuyên mục</option>
                            <option value="status">Trạng thái</option>
                        </select>
                        <div class="filter-options">
                            <input type="text" name="filter_value" class="form-control title" placeholder="Nhập tên tiêu đề">
                            <select name="filter_value" class="form-control category_id d-none" disabled>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <select name="filter_value" class="form-control status d-none" disabled>
                                @foreach ($statusList as $index => $status)
                                    <option value="{{ $index }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary button category-save" onclick="">Tìm</button>
                    </div>
                </form>
            </div>
            @if ($articles->count() > 0)
                <div class="panel-body">
                    <table class="custom-table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Tiêu đề</th>
                                <th>Chuyên mục</th>
                                <th>Trạng thái</th>
                                <th style="width: 15%;">Thời gian đăng</th>
                                <th style="width: 15%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($articles as $article)
                                <tr>
                                    <td style="text-align: left; padding-left: 10px; padding-right: 10px;">{{ $article->title }}</td>
                                    <td>{{ $article->category->name }}</td>
                                    @if ($article->status == App\Article::DRAFT_STATUS)
                                        <td>
                                            <span class="label label-danger">
                                                {{ config('constant.article.status')[App\Article::DRAFT_STATUS] }}
                                            </span>
                                        </td>
                                    @elseif ($article->status == App\Article::PUBLISHED_STATUS)
                                        <td>
                                            <span class="label label-success">
                                                {{ config('constant.article.status')[App\Article::PUBLISHED_STATUS] }}
                                            </span>
                                        </td>
                                    @endif
                                    <td>{{ $article->published_at }}</td>
                                    <td style="font-size: 20px;">
                                        <a href="{{ route('articles.articles.detail', $article->id) }}" class=""><i class="fa fa-edit"></i></a>
                                        @component('admin.common.confirm-delete', ['id' => $article->id, 'title' => 'Xác nhận xóa chuyên mục', 'url' => route('articles.articles.delete', $article->id)])
                                            <div>
                                                <p>Bạn có chắc chắn muốn xóa bài viết này?</p>
                                            </div>
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $articles->links() }}
                </div>
            @else
                <div class="panel-body packages" style="text-align: center;">
                    <h3>Danh sách bài viết đang trống</h3>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script>
        $(document).ready(function () {
            $('select[name="search_field"]').on('change', onChangeSearchField);

            setSearchFieldByParams();
        });

        // On change search field
        const onChangeSearchField = () => {
            const searchField = event.target;

            showSelectedFilterOptions(searchField.value);
        }

        // Show selected filter options
        const showSelectedFilterOptions = (type, value = '') => {
            const filterOptions = $('.filter-options [name="filter_value"]');
            const selectedOptions = filterOptions.filter('.' + type);

            filterOptions.attr('disabled', 'disabled');
            filterOptions.addClass('d-none');

            selectedOptions.removeAttr('disabled');
            selectedOptions.removeClass('d-none');
            if (value) {
                selectedOptions.val(value);
            }
        }

        // Set search field by params
        const setSearchFieldByParams = () => {
            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.has('search_field') && urlParams.has('filter_value')) {
                $('[name="search_field"]').val(urlParams.get('search_field'));
                $('[name="search_field"]').change();

                showSelectedFilterOptions(urlParams.get('search_field'), urlParams.get('filter_value'));
            }
        }
    </script>
    <script src="{{ admin_asset('js/sweetalert2.js') }}"></script>
@endsection