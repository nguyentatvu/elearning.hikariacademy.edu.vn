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

        #image_select_modal .modal-footer {
            display: flex;
            flex-flow: column;
            align-items: center;
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
            display: none;
            position: absolute;
            background-color: rgba(0, 0, 0, 0.5);
            top: 0;
            width: 100%;
            height: 60px;
        }

        .image-item:hover .image-control-container {
            display: block;
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

        .tox-promotion {
            width: 0 !important;
            visibility: hidden;
        }

        .tox-statusbar__branding {
            display: none;
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
                    <button class="btn btn-primary button category-save" onclick="saveArticle()">Lưu</button>
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
                                <input type="text" class="form-control" name="title">
                            </div>
                            <div class="form-group col-6">
                                <label for="description">
                                    Mô tả
                                    <span class="text-danger">*<span>
                                </label>
                                <input type="text" class="form-control" name="description">
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
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                                        <option value="{{ $index }}">{{ $status }}</option>
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
                                    <img src="" alt="preview image" class="preview-thumbnail d-none">
                                </div>
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

            trySetupTinyMCE();
            setupRemovingErrorValidation();
        });

        const trySetupTinyMCE = (retryCount = 0, maxRetries = 5) => {
            try {
                if (tinymce.get('article_editor')) {
                    tinymce.get('article_editor').remove();
                }

                setupTinymce();
            }
            catch(error) {
                console.error('TinyMCE setup failed:', error);
                if (retryCount < maxRetries) {
                    setTimeout(() => {
                        trySetupTinyMCE(retryCount + 1);
                    }, 200);
                }
            }
        }

        // Show preview thumbnail following file upload data
        const showPreviewThumbnail = (e) => {
            const file = e.target.files[0];
            const reader = new FileReader();
            const previewImage = $('.preview-thumbnail');

            if (!file) {
                previewImage.attr('src', '').addClass('d-none');
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

        // Save article
        const saveArticle = () => {
            const form = $('#article_form')[0];
            const articleData = new FormData(form);

            articleData.append('content', getArticleContent());

            Object.values(blobList).forEach((blob) => {
                articleData.append('article_images[]', blob);
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                },
                url: '{{ route('articles.articles.store') }}',
                type: 'post',
                data: articleData,
                processData: false,
                contentType: false,
                success: (response) => {
                    window.location.href = response.redirect;
                },
                error: (response) => {
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
                if (errorField == 'content' || errorField.startsWith('article_images')) {
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
                extended_valid_elements: 'figure[class|id],figcaption[class|id]',
                plugins:[
                    'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                    'searchreplace', 'wordcount', 'visualblocks', 'code', 'insertdatetime', 'media',
                    'table', 'emoticons', 'codesample', 'quickbars'
                ],
                toolbar: 'undo redo | styles fontsizeinput | bold italic underline | forecolor backcolor emoticons | align bullist numlist |' +
                'outdent indent | link image table | preview',
                menubar: 'favs file edit view insert format tools table',
                quickbars_selection_toolbar: 'bold italic | forecolor backcolor | quicklink h2 h3 blockquote | table',
                quickbars_insert_toolbar: '',
                contextmenu: 'link image editimage table configurepermanentpen',
                content_style: `
                    a {
                        text-decoration: none;
                    }
                    body {
                        font-size: 14px;
                        font-family: 'Nunito', sans-serif;
                    }
                    figure {
                        margin: 0;
                        display: inline-block;
                        text-align: center;
                    }
                    figure img {
                        max-width: 665px;
                    }
                    figcaption {
                        font-size: 13px;
                        color: #666;
                        margin-top: 5px;
                    }
                `,
                file_picker_types: 'image',
                setup: function(editor) {
                    editor.on('OpenWindow', function(e) {
                        const saveButton = document.querySelector('button[data-mce-name="Save"]');
                        if (saveButton) {
                            saveButton.addEventListener('click', function(e) {
                                setTimeout(() => {
                                    let img = window.currentEditedImg
                                    const title = img.getAttribute('title');

                                    if (title) {
                                        const parentFigure = img.closest('figure');

                                        if (!parentFigure) {
                                            const figure = editor.dom.create('figure');
                                            const figcaption = editor.dom.create('figcaption', {
                                                contenteditable: 'true' // Cho phép chỉnh sửa figcaption
                                            }, title);

                                            img.parentNode.replaceChild(figure, img);
                                            figure.appendChild(img);
                                            figure.appendChild(figcaption);
                                        } else {
                                            let figcaption = parentFigure.querySelector('figcaption');
                                            if (!figcaption) {
                                                figcaption = editor.dom.create('figcaption', {
                                                    contenteditable: 'true'
                                                }, title);
                                                parentFigure.appendChild(figcaption);
                                            } else {
                                                figcaption.textContent = title;
                                            }
                                        }
                                    }
                                }, 100);

                                setTimeout(function() {
                                    editor.getBody().querySelectorAll('img').forEach(function(img) {
                                        img.style.maxWidth = '100%';
                                        img.style.height = 'auto';
                                    });
                                }, 100);
                            });
                        }
                    });

                    editor.on('NodeChange', function(e) {
                        checkExistedImagesInEditor(editor.dom.doc);
                    });

                    editor.on('ObjectSelected', function(e) {
                        if (e.target.nodeName === 'IMG') {
                            const img = e.target;
                            window.currentEditedImg = img;
                            const figure = img.closest('figure');

                            if (!img.getAttribute('title') && figure) {
                                const figcaption = figure.querySelector('figcaption');
                                if (figcaption) {
                                    figcaption.remove();
                                }
                                if (!figure.querySelector('figcaption')) {
                                    figure.parentNode.replaceChild(img, figure);
                                }
                            }
                        }
                    });

                    editor.on('paste', function(e) {
                        setTimeout(function() {
                            editor.getBody().querySelectorAll('img[title]').forEach(function(img) {
                                const title = img.getAttribute('title');
                                if (title && !img.closest('figure')) {
                                    const figure = editor.dom.create('figure');
                                    const figcaption = editor.dom.create('figcaption', {}, title);

                                    img.parentNode.replaceChild(figure, img);
                                    figure.appendChild(img);
                                    figure.appendChild(figcaption);
                                }
                            });
                        }, 120);

                        setTimeout(function() {
                            editor.getBody().querySelectorAll('img').forEach(function(img) {
                                img.style.maxWidth = '100%';
                                img.style.height = 'auto';
                            });
                        }, 100);
                    });

                    editor.on('focus', function (e) {
                        $('.tox-tinymce').removeClass('is-invalid');
                        $('.tox-tinymce').siblings('span.invalid-feedback').remove();
                    });

                    editor.on('keydown', function(e) {
                        const element = editor.selection.getNode();
                        if (element.nodeName === 'FIGCAPTION') {
                            e.preventDefault();
                            return false;
                        }
                    });

                    // Ngăn chặn paste vào figcaption
                    editor.on('paste', function(e) {
                        const element = editor.selection.getNode();
                        if (element.nodeName === 'FIGCAPTION') {
                            e.preventDefault();
                            return false;
                        }
                    });

                    // Xử lý khi click vào figcaption
                    editor.on('click', function(e) {
                        if (e.target.nodeName === 'FIGCAPTION') {
                            e.preventDefault();
                            e.stopPropagation();

                            editor.getBody().blur();

                            return false;
                        }
                    });
                },
            });
        }
    </script>
@endsection