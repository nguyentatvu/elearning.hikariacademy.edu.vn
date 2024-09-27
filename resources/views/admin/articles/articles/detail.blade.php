@extends('admin.layouts.owner.ownerlayout')

@section('header_scripts')
    <link href="{{ admin_asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('public/js/plugins/tinymce/tinymce.min.js') }}"></script>
    <style>
        .file-upload {
            font-size: 16px;
            margin-top: 0.25rem;
            width: 100%;
            border-radius: 0.375rem;
            border: 1px solid #438afe;
            background-color: rgb(255 255 255);
            color: rgb(156 163 175);
        }

        .file-upload::file-selector-button{
            background-color: #438afe;
            border: none;
            color: white;
            height: 30px;
        }

        .thumbnail-container {
            margin-top: 20px;
            max-height: 300px;
        }

        .preview-thumbnail, .showing-image {
            object-fit: contain;
            object-position: center;
            width: 100%;
            height: 100%;
        }

        .preview-thumbnail {
            max-height: 300px;
        }

        .content {
            width: 100%;
        }

        div.tox-tinymce {
            width: 100%;
        }

        .image-select {
            margin-top: 0.25rem;
        }

        .modal-dialog.select-image-dialog {
            width: 800px;
        }

        .modal-body {
            border-bottom: 1px solid #f2f2f2;
        }

        .image-upload-label {
            margin-top: 20px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            font-size: 26px;
            color: #686e7d;
            border: 1px solid #686e7d;
            border-radius: 5px;
            padding: 4px;
            cursor: pointer;
        }

        .image-list-text {
            font-size: 20px;
            color: #686e7d;
        }

        .image-list-container {
            margin-top: 16px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            width: 100%;
            gap: 10px;
        }

        .image-item {
            position: relative;
            height: 180px;
            border-radius: 4px;
            box-shadow: rgba(0, 0, 0, 0.18) 0px 2px 4px;
            cursor: pointer;
        }

        .image-item:hover {
            box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset;
        }

        .image-control-container {
            position: absolute;
            background-color: rgba(0, 0, 0, 0.5);
            top: 0;
            width: 100%;
            height: 60px;
        }

        .image-item:hover .image-control-container {
            display: block !important;
        }

        .image-control {
            display: flex;
            align-items: center;
            width: 100%;
            height: 100%;
            justify-content: space-around;
        }

        .article-image-wrapper {
            position: relative;
            height: 180px;
            border-radius: 4px;
            cursor: pointer;
            width: 300px;
        }

        .article-image-container {
            display: flex;
            flex-flow: column;
            gap: 10px;
            padding: 10px;
            box-shadow: rgba(0, 0, 0, 0.18) 0px 2px 4px;
            border-radius: 4px;
        }

        .article-image-control {
            display: flex;
            width: 100%;
            gap: 20px;
            justify-content: center;
        }

        p.empty-text {
            text-align: center;
            margin-top: 10px;
            font-size: 20px;
        }

        div.tox.tox-tinymce {
            margin: auto;
            width: 685px;
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
                    <button class="btn btn-primary button category-save" onclick="updateArticle()">Cập nhật</button>
                </div>
                <h1>{{ $page_title }}</h1>
            </div>
            <div class="panel-body">
                <form id="article_form" method="post">
                    <div class="detail-content">
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="title">
                                    Tiêu đề
                                    <span class="text-danger">*<span>
                                </label>
                                <input type="text" class="form-control" name="title" value="{{ $article->title }}">
                            </div>
                            <div class="form-group col-6">
                                <label for="description">
                                    Mô tả
                                </label>
                                <input type="text" class="form-control" name="description" value="{{ $article->description }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="category_id">
                                    Chuyên đề
                                    <span class="text-danger">*<span>
                                </label>
                                <select name="category_id" class="form-control">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $article->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label for="status">
                                    Trạng thái
                                    <span class="text-danger">*<span>
                                </label>
                                <select name="status" class="form-control">
                                    @foreach ($statusList as $index => $status)
                                        <option value="{{ $index }}" {{ $article->status == $index ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="thumbnail">
                                    Tải ảnh thumbnail
                                    <span class="text-danger">*<span>
                                </label>
                                <input type="file" class="file-upload" name="thumbnail" accept="image/*">
                                <div class="thumbnail-container">
                                    <img src="{{ $article->thumbnail }}" alt="preview image" class="preview-thumbnail">
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label for="title">
                                    Quản lý danh sách ảnh được upload
                                </label>
                                <br>
                                <button class="btn btn-primary image-select" data-toggle="modal" data-target="#image_select_modal" onclick="event.preventDefault();">
                                    <i class="fa fa-file-image-o"></i>
                                    Xem ảnh
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="content">
                                    Nội dung bài viết
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea class="content" id="article_editor" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Image Select Modal -->
    <div class="modal fade" id="image_select_modal" tabindex="-1" aria-labelledby="image_modal_label">
        <div class="modal-dialog select-image-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="image_modal_label"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span class="image-list-text">Danh sách ảnh được tải lên trong bài viết</span>
                    @if (count($article->images) > 0)
                        <div class="image-list-container">
                            @foreach ($article->images as $imageIndex => $image)
                                <div class="image-item">
                                    <img src="{{ $image }}" alt="article image" class="showing-image">
                                    <div class="image-control-container d-none">
                                        <div class="image-control">
                                            @component('admin.common.confirm-delete', ['id' => 'article_image_' . $imageIndex])
                                                @slot('delete_button')
                                                    <button class="btn btn-danger" data-toggle="modal" data-target="#confirm_delete_modal{{ 'article_image_' . $imageIndex }}">
                                                        <i class="fa fa-times"></i>
                                                        <span>Xóa ảnh</span>
                                                    </button>
                                                @endslot
                                                <div>
                                                    <p>Bạn có chắc chắn muốn ảnh này?</p>
                                                    <p class="text-danger" style="font-size: 14px;">Chú ý: Chỉ xóa nếu hình ảnh không còn được xuất hiện trong bài viết nữa!</p>
                                                </div>
                                                @slot('confirm_button')
                                                    <button type="submit" class="btn btn-danger btn-delete" style="display:inline-block;"
                                                        onclick="removeSavedArticleImage('{{ $image }}', '{{ $article->id }}')">
                                                        Xác nhận
                                                    </button>
                                                @endslot
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="empty-text">(TRỐNG)</p>
                    @endif
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- End Image Select Modal -->
@endsection

@section('footer_scripts')
    <script src="{{ admin_asset('js/sweetalert2.js') }}"></script>
    <script src="/public/js/plugins/tinymce/tinymce.min.js"></script>
    <script>
        let blobList = {};
        let removedBlobList = {};

        $(document).ready(function () {
            $('.file-upload').on('change', showPreviewThumbnail);
            $('.article-image-upload').on('change', showPreviewArticleImage);

            setupTinymce();
            setupRemovingErrorValidation();
            setupModalAction();
        });

        // Setup modals action
        const setupModalAction = () => {
            $('[id^="confirm_delete_modal"]').on('shown.bs.modal', function () {
                $(this).closest('.image-control-container').removeClass('d-none');
            });

            $('[id^="confirm_delete_modal"]').on('hidden.bs.modal', function () {
                $(this).closest('.image-control-container').addClass('d-none');
            });

            $('.modal.fade').on('hidden.bs.modal', function () {
                if ($('.modal-backdrop.fade.in').length > 0 && $('.modal:visible').length == 0) {
                    $('.modal').modal('hide');
                }
            });

        }

        // Show preview thumbnail following file upload data
        const showPreviewThumbnail = (e) => {
            const file = e.target.files[0];
            const reader = new FileReader();
            const previewImage = $('.preview-thumbnail');

            if (!file) {
                previewImage.attr('src', '{{ $article->thumbnail }}');
                return;
            }

            reader.onload = function(e) {
                previewImage.attr('src', e.target.result).removeClass('d-none');
            }
            reader.readAsDataURL(file);
        }

        // Show preview article image following file upload data
        const showPreviewArticleImage = (e) => {
            const file = e.target.files[0];
            const reader = new FileReader();
            const previewImageContainer = $('.article-image-container');
            const previewImage = previewImageContainer.find('img.showing-image');

            if (!file) {
                previewImage.attr('src', '');
                previewImageContainer.addClass('d-none');
                return;
            }

            reader.onload = function(e) {
                previewImage.attr('src', e.target.result);
                previewImageContainer.removeClass('d-none');
            }
            reader.readAsDataURL(file);
        }

        // Remove preview article image
        const removePreviewArticleImage = () => {
            const previewImageContainer = $('.article-image-container');
            const previewImage = previewImageContainer.find('img.showing-image');
            previewImage.attr('src', '');
            previewImageContainer.addClass('d-none');
        }

        // Convert base64 to blob url
        const base64ToBlobURL = async (base64) => {
            const base64Response = await fetch(base64);
            const blob = await base64Response.blob();

            return URL.createObjectURL(blob);
        }

        //Remove saved article image item
        const removeSavedArticleImage = (imageId, articleId) => {

            const imageItem = $(event.target).closest('.image-item');
            const confirmDeleteModal = $(event.target).closest('.modal.fade.in');

            confirmDeleteModal.modal('hide');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                },
                url: '{{ route('articles.articles.delete_image') }}',
                type: 'post',
                data: {
                    'image_id': imageId,
                    'article_id': articleId,
                },
                success: (response) => {
                    showSucessAlert(
                        'Xóa bài viết thành công!', null, 1000
                    );

                    setTimeout(() => {
                        imageItem.remove();

                        if ($('.image-item').length === 0) {
                            $('.image-list-container').replaceWith(`
                                <p class="empty-text">(TRỐNG)</p>
                            `);
                        }
                    }, 300);
                },
                error: (error) => {
                    if (error.status == 404) {
                        showErrorAlert(
                            error.responseText,
                            () => {
                                window.location.href = '{{ route('articles.articles.index') }}'
                            },
                            1000
                        );
                        return;
                    }

                    showErrorAlert('Xóa hình ảnh thất bại!', null, 1000);
                }
            });
        }

        // Save article
        const updateArticle = () => {
            const form = $('#article_form')[0];
            const articleData = new FormData(form);

            articleData.append('content', getArticleContent());
            articleData.append('_method', 'PUT');

            Object.values(blobList).forEach((blob) => {
                articleData.append('added_article_images[]', blob);
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                },
                url: '{{ route('articles.articles.update', $article->id ) }}',
                type: 'post',
                data: articleData,
                processData: false,
                contentType: false,
                success: (response) => {
                    window.location.reload();
                },
                error: (response) => {
                    if (response.status == 404) {
                        window.location.href = response.responseJSON.redirect;
                        return;
                    }

                    showErrorAlert('Lưu bài viết thất bại!', null, 1000);
                    showErrorValidation(response?.responseJSON?.errors);
                }
            });
        }

        // Get article content
        const getArticleContent = () => {
            let articleContent = tinymce.get("article_editor").getContent();
            const parser = new DOMParser();
            const tinyDoc = parser.parseFromString(articleContent, 'text/html');

            tinyDoc.querySelectorAll('img[src^="data:image"]').forEach(function(img) {
                img.src = img.title;
                img.removeAttribute('title');
            });

            return tinyDoc.body.innerHTML;
        }

        // Show failed validation messages on fields
        const showErrorValidation = (errors) => {
            if (!errors) {
                return;
            }

            $('span.invalid-feedback').remove();
            Object.keys(errors).forEach((errorField) => {
                if (errorField == 'content' || errorField.startsWith('added_article_images')) {
                    // Make different action when article edit is empty
                    // Or image type is not suppported
                    $('label[for="content"]').after(`
                        <span class="invalid-feedback text-danger">
                            ${errors[errorField][0]}
                        </span>
                    `);
                    $('.tox-tinymce').addClass('is-invalid');

                    return;
                }

                const field = $(`[name="${errorField}"]`);
                field.addClass('is-invalid');
                field.after(`
                    <span class="invalid-feedback text-danger">
                        ${errors[errorField][0]}
                    </span>
                `);
            });
        }

        //Remove error message when focusing error field
        const setupRemovingErrorValidation = () => {
            $('#article_form').find('[name]').on('click', function () {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });
        }

        // Check existed images in editor
        const checkExistedImagesInEditor = (tinyDOM) => {
            // If an image disappears after a node change event
            // => Its image file will be removed in blobList and saved in removedBlobList
            Object.keys(blobList).forEach((blobKey) => {
                const findingImage = tinyDOM.querySelector(`[title="${blobKey}"]`);
                if (!findingImage) {
                    removedBlobList[blobKey] = blobList[blobKey];
                    delete blobList[blobKey];
                }
            });

            // If an image appears after a node change event
            // => Its image file will be saved in blobList and removed in removedBlobList
            Object.keys(removedBlobList).forEach((blobKey) => {
                const findingImage = tinyDOM.querySelector(`[title="${blobKey}"]`);
                if (findingImage) {
                    blobList[blobKey] = removedBlobList[blobKey];
                    delete removedBlobList[blobKey];
                }
            });
        }

        // Set up tinymce editor
        const setupTinymce = () => {
            const savedContent = {!! json_encode($article->content) !!};
            tinymce.init({
                selector: 'textarea#article_editor',
                license_key: 'gpl',
                language: 'vi',
                height: 700,
                toolbar_mode: 'wrap',
                image_title: true,
                automatic_uploads: true,
                file_picker_types: 'image',
                file_picker_callback: (cb, value, meta) => {
                    const input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');

                    input.addEventListener('change', (e) => {
                        const file = e.target.files[0];

                        const reader = new FileReader();
                        reader.addEventListener('load', () => {
                            const id = 'article_image_' + (new Date()).getTime();
                            const blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                            const base64 = reader.result.split(',')[1];
                            const blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);

                            // Save blob items
                            const fileExt = file.name.split('.').pop();
                            blobList[id] = new File(
                                [file],
                                `${id}.${fileExt}`,
                                {
                                    type: file.type,
                                    lastModified: file.lastModified,
                                }
                            );

                            cb(blobInfo.blobUri(), { title: id });
                        });
                        reader.readAsDataURL(file);
                    });

                    input.click();
                },
                plugins:[
                    'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                    'searchreplace', 'wordcount', 'visualblocks', 'code', 'insertdatetime', 'media',
                    'table', 'emoticons', 'codesample', 'quickbars',
                ],
                toolbar: 'undo redo | styles fontsizeinput | bold italic underline | forecolor backcolor emoticons | align bullist numlist |' +
                'outdent indent | link image table | preview',
                menubar: 'favs file edit view insert format tools table',
                quickbars_selection_toolbar: 'bold italic | forecolor backcolor | quicklink h2 h3 blockquote | table',
                quickbars_insert_toolbar: '',
                contextmenu: 'link image editimage table configurepermanentpen',
                content_style: 'a { text-decoration: none; } body { font-size: 14px; font-family: \'Nunito\', sans-serif;}',
                file_picker_types: 'image',
                setup: function(editor) {
                    editor.on('NodeChange', function(e) {
                        checkExistedImagesInEditor(editor.dom.doc);
                    });

                    editor.on('focus', function (e) {
                        $('.tox-tinymce').removeClass('is-invalid');
                        $('.tox-tinymce').siblings('span.invalid-feedback').remove();
                    });
                },
            });

            setTimeout(() => {
                tinymce.activeEditor.setContent(savedContent);
            }, 500);
        }
    </script>
@endsection