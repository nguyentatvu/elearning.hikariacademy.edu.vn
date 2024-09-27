@extends('admin.layouts.owner.ownerlayout')

@section('header_scripts')
    <link href="{{ admin_asset('css/sweetalert2.css') }}" rel="stylesheet">
    <style>
        .detail-content {
            border-bottom: 1px solid #f2f2f2;
        }

        .category-edit {
            cursor: pointer;
            display: inline;
            color: #438afe;
        }

        .detail-content {
            padding-bottom: 0px !important;
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
                    <button class="btn btn-primary button btn-danger" style="display: none;" onclick="hideCancleButton()">Hủy</button>
                    <button class="btn btn-primary button category-save" onclick="categorySave()">Tạo mới</button>
                </div>
                <h1>{{ $page_title }}</h1>
            </div>
            <div class="category-info" style="display: none;">
                <form method="post" id="category_form">
                    <div class="detail-content">
                        <input type="text" name="id" hidden disabled>
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="category_name">
                                    Tên chuyên mục
                                    <span class="text-danger">*<span>
                                </label>
                                <input type="text" class="form-control" name="category_name" id="category_name">
                                <p class="text-danger invalid-feedback"></p>
                                <label for="slug">
                                    Slug (Có thể bỏ trống khi tạo mới)
                                </label>
                                <input type="text" class="form-control" name="slug" id="slug">
                                <p class="text-danger invalid-feedback"></p>
                            </div>
                            <div class="form-group col-6">
                                <label for="description">
                                    Mô tả
                                </label>
                                <textarea class="form-control" name="description" id="" rows="6"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @if ($categories->count() > 0)
                <div class="panel-body packages">
                    <table class="custom-table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Tên</th>
                                <th>Slug</th>
                                <th>Mô tả</th>
                                <th style="width: 20%;">Ngày tạo</th>
                                <th style="width: 15%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->slug }}</td>
                                    <td>{{ truncateText($category->description, 20) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($category->created_at)->format('Y-m-d') }}</td>
                                    <td style="font-size: 20px;">
                                        <div class="category-edit" onclick="categoryEdit({{ $category->id }})"><i class="fa fa-edit"></i></div>
                                        @component('admin.common.confirm-delete', ['id' => $category->id, 'title' => 'Xác nhận xóa chuyên mục', 'url' => route('articles.categories.delete', $category->id)])
                                            <div>
                                                <p>Bạn có chắc chắn muốn xóa chuyên mục này?</p>
                                                <p class="text-danger" style="font-size: 14px;">Chú ý: Không thể xóa nếu có nhiều hơn 1 bài viết thuộc chuyên mục này</p>
                                            </div>
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $categories->links() }}
                </div>
            @else
                <div class="panel-body packages" style="text-align: center;">
                    <h3>Danh sách chuyên mục bài viết đang trống</h3>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script>
        $(document).ready(function () {
            let originalCategories = ({!! json_encode($categories->toArray()) !!}).data;
            prepareCategoriesData(originalCategories);
        });

        // Reload page
        const reloadPage = () => {
            location.reload();
        }

        // Prepare categories data for updating
        const prepareCategoriesData = (originalCategories) => {
            categories = originalCategories.reduce((acc, category) => {
                acc[category.id] = category;
                return acc;
            }, {});
        }

        // Category save button event handler
        const categorySave = () => {
            const categorySaveBtn = $('.category-save');
            const categoryContainer = $('.category-info');
            const saveCategoryURL = '{{ route('articles.categories.update_or_create') }}';

            toggleCancleButton(true);

            if (categoryContainer.is(':hidden')) {
                categoryContainer.show();
                categorySaveBtn.addClass('create');
                categorySaveBtn.removeClass('edit');
                categorySaveBtn.text('Lưu chuyên mục mới');

                return;
            }

            // If save button for creating new category
            if (categorySaveBtn.hasClass('create')) {
                const formData = $('#category_form').serialize();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN':'{{ csrf_token() }}'
                    },
                    url: saveCategoryURL,
                    type: 'post',
                    data: formData,
                    success: function(response) {
                        showSucessAlert(
                            'Lưu chuyên mục bài viết thành công!',
                            reloadPage
                        );
                    },
                    error: function(error) {
                        showErrorAlert('Lưu chuyên mục bài viết thất bại!');
                        showValidatedErrors(error?.responseJSON?.errors);

                        // Fill the slug input in case admin did not fill it and slug being duplicated
                        if(error?.responseJSON?.inputs?.slug) {
                            $('input[name="slug"]').val(error.responseJSON.inputs.slug);
                        }
                    }
                });
            }

            // If save button for updating existing category
            if (categorySaveBtn.hasClass('edit')) {
                const formData = $('#category_form').serialize();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN':'{{ csrf_token() }}'
                    },
                    url: saveCategoryURL,
                    type: 'post',
                    data: formData + '&_method=PUT',
                    success: function(response) {
                        showSucessAlert(
                            'Lưu chuyên mục bài viết thành công!',
                            reloadPage
                        );
                    },
                    error: function(error) {
                        showErrorAlert('Lưu chuyên mục bài viết thất bại!');
                        showValidatedErrors(error?.responseJSON?.errors);
                    }
                });
            }
        }

        // Category edit button event handler for every category
        const categoryEdit = (id) => {
            const categorySaveBtn = $('.category-save');
            const categoryContainer = $('.category-info');
            const editForm = $('#category_form');
            const selectedCategory = categories[id];

            toggleCancleButton(true);

            categoryContainer.show();
            categorySaveBtn.addClass('edit');
            categorySaveBtn.removeClass('create');
            categorySaveBtn.text('Cập nhật chuyên mục');

            editForm.find('input[name="category_name"]').val(selectedCategory.name);
            editForm.find('input[name="slug"]').val(selectedCategory.slug);
            editForm.find('textarea[name="description"]').val(selectedCategory.description);
            editForm.find('input[name="id"]').val(selectedCategory.id).removeAttr('disabled');
        }

        // Toggle or show/hide cancle button next to save button
        const toggleCancleButton = (force) => {
            const cancleButton = $('button.btn-danger');

            if (force && typeof force == 'boolean') {
                cancleButton.toggle(force);
                return;
            }

            cancleButton.toggle();
        }

        // Hide cancle button when being clicked
        const hideCancleButton = () => {
            if ($(event.target).is(':visible')) {
                $('#category_form').trigger('reset');
                $('.category-save').text('Tạo mới');
                $('.category-info').hide();
            }

            $('#category_form').find('input[name="id"]').attr('disabled', 'disabled').val('');

            toggleCancleButton(false);
        }

        // Show error text below inputs when submit create/edit form
        const showValidatedErrors = (errors) => {
            $.each(errors, function(key, value) {
                $(`input[name="${key}"]`).addClass('is-invalid');
                $(`input[name="${key}"]`).next('.invalid-feedback').text(value);
            });
        }
    </script>
    <script src="{{ admin_asset('js/sweetalert2.js') }}"></script>
@endsection