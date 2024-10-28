    @extends('admin.layouts.owner.ownerlayout')

    @section('header_scripts')
        <link href="{{ admin_asset('css/sweetalert2.css') }}" rel="stylesheet">

        <style>
            /* Thumbnail Styles */
            .slider-thumbnail img,
            .banner-table img {
                width: 100px;
                height: 100px;
                object-fit: cover;
            }

            /* Preview Image Styles */
            .preview-container {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 20px;
            }

            .preview-image {
                position: relative;
                width: 150px;
                height: 150px;
                border: 1px solid #ddd;
                object-fit: cover;
            }

            .preview-delete {
                position: absolute;
                top: -10px;
                right: -10px;
                background: red;
                color: white;
                border-radius: 50%;
                width: 24px;
                height: 24px;
                display: flex;
                justify-content: center;
                align-items: center;
                cursor: pointer;
            }

            /* Action Buttons */
            .banner-actions {
                display: flex;
                gap: 10px;
            }

            /* Drag handle */
            .drag-handle {
                cursor: move;
                color: #666;
            }

            .preview-image img {
                height: 100%;
                width: 100%;
            }

            .img-fluid {
                height: 100px;
                width: 100px;
                max-height: 100px;
            }

            .image-order {
                position: absolute;
                top: 5px;
                left: 5px;
                background: rgba(0, 0, 0, 0.5);
                color: white;
                padding: 5px;
                border-radius: 5px;
            }

            .overflow-container {
                overflow: auto;
                width: 300px;
            }

            .text-center {
                text-align: center;
            }

            .container-preview-position {
                position: relative;
                display: inline-block;
            }

            .tooltip-preview-image {
                position: absolute;
                top: 100%;
                left: 50%;
                z-index: 10;
                transform: translateX(-50%);
                border: 2px solid #666;
            }

            .tooltip-preview-image img {
                width: 400px;
                max-width: 400px;
                max-height: 300px;
            }

            .preview-info {
                text-align: center;
            }

            .btn-edit-image {
                width: 100%;
            }

            .tooltip-preview-image {
                display: flex;
                gap: 10px;
                overflow-x: scroll;
                max-width: 600px;
                padding: 4px;
                background: #fff;
            }

            .tooltip-image {
                height: 50px;
                border: 1px solid black;
                border-radius: 4px;
            }

            .tooltip-preview-image::-webkit-scrollbar {
                -webkit-appearance: none;
                height: 10px;
            }

            .tooltip-preview-image::-webkit-scrollbar-thumb {
                border-radius: 4px;
                background-color: rgba(0, 0, 0, .5);
            }

            .switch {
                position: relative;
                display: flex;
                justify-content: center;
                width: 50px;
                height: 25px;
            }

            .switch input {
                display: none;
            }

            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                transition: .4s;
                border-radius: 25px;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 21px;
                width: 21px;
                left: 2px;
                bottom: 2px;
                background-color: white;
                transition: .4s;
                border-radius: 50%;
            }

            input:checked+.slider {
                background-color: #337ab7;
            }

            input:checked+.slider:before {
                transform: translateX(24px);
            }

            .status-banner-container {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        </style>
    @endsection

    @section('content')
        <div class="panel panel-custom">
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
            <div class="panel-heading">
                <div class="pull-right messages-buttons">
                    <button class="btn btn-primary button btn-danger" style="display: none;"
                        onclick="hideCancleButton()">Hủy</button>
                    <button class="btn btn-primary button category-save" onclick="openAddModal()">Tạo mới</button>
                </div>
                <h1>{{ $page_title }}</h1>
            </div>

            <div class="container mt-4">
                <!-- Banner Table -->
                <table class="table table-hover table-bordered banner-table" id="bannerTable">
                    <thead class="table-dark">
                        <tr>
                            <th style="min-width: 50px;"></th>
                            <th style="min-width: 50px;">Tên</th>
                            <th style="min-width: 50px;">Vị trí</th>
                            <th style="min-width: 50px;">Trạng thái</th>
                            <th class="text-center" style="min-width: 50px;">Số lượng ảnh</th>
                            <th style="min-width: 50px;">Thông Tin</th>
                            <th style="min-width: 50px;">Hành Động</th>
                        </tr>
                    </thead>

                    <tbody id="bannerTableBody">
                        <!-- Dynamic Data Here -->
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Modal for Adding/Editing Banner -->
        <div class="modal fade" id="bannerModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalTitle">Thêm Banner Mới</h4>

                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <form id="bannerForm" onsubmit="saveBanner(event)">
                        <div class="modal-body">
                            <input type="hidden" id="editingId">

                            <!-- Banner Type Selection -->
                            <div class="form-group select-banner-group">
                                <label id="label_banner_type" for="bannerType" class="control-label">Chọn vị trí</label>
                                <select id="bannerType" class="form-control" onchange="toggleBannerFields();">
                                    <option value="" selected>Chọn vị trí</option>
                                    @foreach ($listBanner as $key => $banner)
                                        <option value="{{ $banner['category'] }}"
                                            data-banner='@json($banner)'>
                                            {{ $banner['title'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Common Fields -->
                            <div class="form-group">
                                <label for="bannerTitle" class="control-label">Vị trí</label>
                                <input type="text" readonly id="bannerTitle" class="form-control disabled"
                                    placeholder="Vị trí của banner">
                            </div>

                            <div class="form-group">
                                <label for="bannerDescription" class="control-label">Mô tả</label>
                                <textarea style="resize: none;" id="bannerDescription" class="form-control" rows="3" placeholder="Mô tả"></textarea>
                            </div>
                            <input type="hidden" id="bannerPosition" name="banner_position" value="">
                            <input type="hidden" id="bannerSize" name="banner_size" value="">
                            <div class="form-group">
                                <label for="bannerUrl" class="control-label">URL đích</label>
                                <input type="url" id="bannerUrl" class="form-control"
                                    placeholder="https://hikariacademy.edu.vn">
                            </div>
                            <label for="regularImage" class="control-label"><strong>Hình Ảnh (Kích thước: <span
                                        class="size-image-text"></span>)</strong></label>
                            <!-- Regular Banner Fields -->
                            <div id="regularFields">
                                <div class="form-group">
                                    <input type="file" id="regularImage" class="form-control" accept="image/*"
                                        onchange="previewRegular(this)">
                                    <div id="regularPreview" class="preview-container"></div>
                                </div>
                            </div>
                            <input type="hidden" name="" id="display_type_edit">
                            <!-- Slider Fields -->
                            <div id="sliderFields" style="display: none;">
                                <div class="form-group">
                                    <input type="file" id="sliderImages" class="form-control" multiple
                                        accept="image/*" onchange="previewSlider(this)">
                                    <div id="sliderPreview" class="preview-container"></div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" onclick="saveBanner()" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @section('footer_scripts')
        <script src="{{ admin_asset('js/sweetalert2.js') }}"></script>

        <script>
            // Centralize common configurations
            const CONFIG = {
                maxFileSize: 2 * 1024 * 1024, // 3MB in bytes
                maxSliderImages: 6,
                supportedTypes: ['image/jpeg', 'image/png', 'image/gif'],
                routes: {
                    update: '{{ route('admin.banners.update', ':id') }}',
                    update_status: '{{ route('admin.banners.update_status', ':id') }}',
                    create: '{{ route('admin.banners.create') }}',
                    show: "{{ route('admin.banners.show', ':id') }}"
                }
            };

            const MODAL_ACTIONS = {
                CREATE: 'create',
                EDIT: 'edit'
            };

            // Centralize DOM elements
            const DOM = {
                sliderPreview: () => $('#sliderPreview'),
                bannerModal: () => $('#bannerModal'),
                bannerType: () => $('#bannerType'),
                editingId: () => $('#editingId'),
                regularFields: () => $('#regularFields'),
                sliderFields: () => $('#sliderFields'),
                bannerForm: {
                    title: () => $('#bannerTitle'),
                    description: () => $('#bannerDescription'),
                    url: () => $('#bannerUrl'),
                    position: () => $('#bannerPosition'),
                    size: () => $('.size-image-text'),
                }
            };

            // Initialize event listeners
            $(document).ready(() => {
                loadBanners();
            });

            /**
             * Load banners from the server.
             */
            function loadBanners() {
                $.ajax({
                    url: '/banner/getList',
                    type: 'GET',
                    success: renderBannerTable,
                    error: handleAjaxError
                });
            }

            /**
             * Validate file upload
             */
            function validateFile(file) {
                console.log(file);
                if (!file) {
                    return {
                        valid: false,
                        message: "Phải có tối thiểu 1 hình."
                    };
                }

                if (!CONFIG.supportedTypes.includes(file.type)) {
                    return {
                        valid: false,
                        message: `File ${file.name} không đúng định dạng. Chỉ chấp nhận JPG, PNG, GIF.`
                    };
                }

                if (file.size > CONFIG.maxFileSize) {
                    return {
                        valid: false,
                        message: `File ${file.name} vượt quá 3MB.`
                    };
                }

                return {
                    valid: true
                };
            }

            /**
             * Render the banner table with banner data.
             */
            function renderBannerTable(banners) {
                let rows;
                if (banners.length === 0) {
                    rows = `
                    <tr>
                        <td colspan="6" class="text-center">Không có banner nào</td>
                    </tr>
                `;
                } else {
                    rows = banners.map(banner => {
                        const imageSrc = banner.display_type === 'multi_image' ?
                            banner.multiple_image :
                            banner.image;
                        return `
                    <tr>
                        <td><span class="drag-handle">⇅</span></td>
                        <td><strong>${banner.title}</strong></td>
                        <td>
                            <div class="container-preview-position">
                                <button class="show-image-button" onclick="showTooltip(this)">Hiển thị hình ảnh</button>
                                <div class="tooltip-preview-image" style="display:none;">
                                    ${Array.isArray(imageSrc)
                                        ? imageSrc.map(src => `<img src="${src}" alt="Hình ảnh Tooltip" class="tooltip-image">`).join('')
                                        : `<img src="${imageSrc}" alt="Hình ảnh Tooltip" class="tooltip-image">`}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="status-banner-container">
                                <label class="switch d-flex justify-content-center">
                                    <input id="checkbox_active_${banner.id}" type="checkbox" ${banner.is_active === 1 ? 'checked' : ''} onchange="updateStatus(${banner.id}, this)">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </td>
                        <td class="text-center">${banner.display_type === 'multi_image' && imageSrc ? imageSrc.length : 1} </td>
                        <td>
                            <div class="overflow-container">
                                <strong><strong>Tiêu đề:</strong> ${banner.title ?? banner.title}</strong><br>
                                <div><strong>Mô tả:</strong> ${banner.description ?? banner.description}</div>
                                ${banner.to_url ? `<strong>Địa chỉ url:</strong> <a href="${banner.to_url}" target="_blank">${banner.to_url}</a>` : ''}
                            </div>
                        </td>
                        <td class="banner-actions">
                            <button class="btn btn-warning" onclick="openEditModal(${banner.id})">Sửa</button>
                            <button class="btn btn-danger" onclick="deleteBanner(${banner.id})">Xóa</button>
                        </td>
                    </tr>
                `;
                    }).join('');
                }

                $('#bannerTableBody').html(rows);
            }

            /**
             * Handle AJAX errors.
             */
            function handleAjaxError(xhr, status, errorThrown) {
                Swal.fire({
                    title: 'Lỗi',
                    text: 'Có lỗi xảy ra: ' + status,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }

            /**
             * Open the modal to edit an existing banner.
             */
            function openEditModal(id) {
                const url = CONFIG.routes.show.replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const banner = response.data;
                            populateBannerForm(banner, MODAL_ACTIONS.EDIT);
                            $('#modalTitle').text('Chỉnh Sửa Banner');
                            $('.select-banner-group').hide();
                            $('#bannerModal').modal('show');
                        } else {
                            console.log('Không tìm thấy banner: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Đã xảy ra lỗi khi tải dữ liệu banner. Vui lòng thử lại.');
                        console.log(xhr.responseText);
                    }
                });
            }

            /**
             * Open the modal to add a new banner.
             */
            function openAddModal() {
                resetBannerForm();
                $('#modalTitle').text('Thêm Banner Mới');
                $('.select-banner-group').show();
                $('#bannerDescription').text('');
                $('#bannerModal').modal('show');
                toggleBannerFields();
            }

            const STATE = {
                currentImages: [], // Store current images when editing
                deletedImages: [], // Track deleted images
                changedImages: {} // Track changed images with their positions
            };

            /**
             * Populate the banner form based on the action type (create/edit).
             */
            function populateBannerForm(banner, action) {
                if (action === MODAL_ACTIONS.EDIT) {
                    $('#editingId').val(banner.id);
                    $('#bannerType').val(banner.display_type).change();
                    $('#bannerTitle').val(banner.title);
                    $('#bannerDescription').val(banner.description);
                    $('#bannerUrl').val(banner.to_url);
                    $('.size-image-text').text(banner.size);
                    $('#display_type_edit').val(banner.display_type);

                    // Reset state when opening modal
                    STATE.currentImages = [];
                    STATE.deletedImages = [];
                    STATE.changedImages = {};

                    if (banner.display_type === 'multi_image') {
                        $('#regularFields').hide();
                        $('#sliderFields').show();

                        // Parse images if it's a string
                        const images = typeof banner.image === 'string' ? JSON.parse(banner.image) : banner.image;
                        // Store initial images
                        if (images) {
                            STATE.currentImages = [...images];
                            populateSliderPreview(images);
                        }
                    } else {
                        $('#regularFields').show();
                        $('#sliderFields').hide();
                        $('#regularImage').hide();

                        if (banner.image) {
                            STATE.currentImages = [banner.image];
                            $('#regularPreview').empty().append(`
                                <div class="preview-image">
                                    <img src="${banner.image}" class="img-fluid">
                                    <div class="preview-info">
                                        <span class="file-size">1</span>
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="editRegular(this)">Chỉnh sửa hình ảnh</button>
                                </div>
                            `);
                        }
                    }
                } else if (action === MODAL_ACTIONS.CREATE) {
                    resetBannerForm();
                }
            }

            /**
             * open upload input
             */
            function editRegular(button) {
                $('#regularImage').click();
            }

            /**
             * Toggle fields in the banner form based on banner type.
             */
            function toggleBannerFields() {
                const bannerType = $('#bannerType');
                let type = bannerType.val();
                let bannerData = $('#bannerType option:selected').data('banner');

                if (bannerData) {
                    DOM.bannerForm.title().val(bannerData.title);
                    DOM.bannerForm.description().val(bannerData.description);
                    DOM.bannerForm.url().val(bannerData.url);
                    DOM.bannerForm.position().val(bannerData.position);
                    DOM.bannerForm.size().text(bannerData.size);
                }

                if (type == 'single_image') {
                    $('#regularFields').show();
                    $('#sliderFields').hide();
                } else if (type == 'multi_image') {
                    $('#regularFields').hide();
                    $('#sliderFields').show();
                } else if (!type) {
                    $('#bannerDescription').text('');
                    $('#bannerPosition').val('');
                }
            }

            /**
             * Reset the banner form fields.
             */
            function resetBannerForm() {
                DOM.bannerForm.title().val('');
                DOM.bannerForm.description().val('');
                DOM.bannerForm.url().val('');
                DOM.bannerForm.position().val('');
                $('#regularPreview').empty();
                $('#regularImage').val('');
                $('#regularImage').show();
                DOM.regularFields().show();
                DOM.sliderFields().hide();
                $('#editingId').val('');
                $('.size-image-text').text('');
                $('#bannerType').val('').change();
            }

            function removePreview(containerId, element) {
                if (element) {
                    $(element).closest('.preview-image').remove();
                } else {
                    $(`#${containerId}`).empty();
                }

                $('#regularImage').show();
            }

            /**
             * Updates the preview container with current images
             * Maintains proper indexing and event handlers
             * @param {Array} images - Array of image sources to display
             */
            function populateSliderPreview(images) {
                const $previewContainer = $('#sliderPreview');
                $previewContainer.empty();

                images.forEach((image, index) => {
                    // Create preview element with proper index and handlers
                    const previewImageHtml = `
                        <div class="preview-image" data-index="${index}">
                            <img src="${image}" class="img-fluid" alt="Image ${index + 1}">
                            <div class="preview-info">
                                <span class="file-size">${index + 1}</span>
                            </div>
                            <span class="preview-delete" onclick="removeSliderImage(${index})">&times;</span>
                            <button type="button" class="btn btn-primary btn-edit-image" onclick="editSliderImage(${index})">Chỉnh sửa hình ảnh</button>
                        </div>
                    `;
                    $previewContainer.append(previewImageHtml);
                });
            }

            /**
             * Handles removal of slider images and updates state accordingly
             * @param {number} index - The index of the image to remove
             */
            function removeSliderImage(index) {
                const removedImage = STATE.currentImages[index];

                // If it's a real URL (not base64), add to deleted images list
                if (!removedImage.startsWith('data:image')) {
                    STATE.deletedImages.push(removedImage);
                }

                // Remove from current images array
                STATE.currentImages.splice(index, 1);

                // Clean up any changed images at this index
                if (STATE.changedImages[index]) {
                    delete STATE.changedImages[index];
                }

                // Refresh preview with updated indices
                populateSliderPreview(STATE.currentImages);
            }

            let updatedImages = {};

            /**
             * Edit slider image and update preview
             */
            function editSliderImage(index) {
                const fileInput = $('<input type="file" accept="image/*" style="display:none" />');
                fileInput.on('change', function() {
                    if (this.files && this.files[0]) {
                        const validation = validateFile(this.files[0]);
                        if (!validation.valid) {
                            Swal.fire('Lỗi', validation.message, 'error');
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            // Update the preview
                            const $previewImage = $(`#sliderPreview .preview-image[data-index="${index}"] img`);
                            $previewImage.attr('src', e.target.result);

                            // Store the changed image
                            STATE.changedImages[index] = e.target.result;
                            // Update current images array
                            STATE.currentImages[index] = e.target.result;
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                });
                fileInput.click();
            }

            /**
             * Remove preview for images.
             */
            function removePreview(containerId) {
                $(`#${containerId}`).empty();
            }

            /**
             * Save the banner to the server.
             */
            function saveBanner(event) {
                const action = $('#editingId').val() ? MODAL_ACTIONS.EDIT : MODAL_ACTIONS.CREATE;
                const id = $('#editingId').val();
                const url = action === MODAL_ACTIONS.EDIT ? CONFIG.routes.update.replace(':id', id) : CONFIG.routes.create;

                const formData = new FormData();
                formData.append('title', DOM.bannerForm.title().val());
                formData.append('description', DOM.bannerForm.description().val());
                formData.append('to_url', DOM.bannerForm.url().val());
                formData.append('position', DOM.bannerForm.position().val());
                if (action == MODAL_ACTIONS.EDIT) {
                    formData.append('display_type', $('#display_type_edit').val());
                } else {
                    formData.append('display_type', $('#bannerType').val());
                }
                formData.append('size', DOM.bannerForm.size().text());

                if ($('#regularPreview').find('img').length) {
                    const image = $('#regularPreview').find('img').attr('src');
                    formData.append('images', image);
                } else if ($('#sliderPreview').find('.preview-image').length) {
                    $('#sliderPreview').find('.preview-image').each(function(index) {
                        const imageSrc = $(this).find('img').attr('src');

                        if (action == MODAL_ACTIONS.EDIT && STATE.currentImages.length > 0) {
                            STATE.currentImages.forEach((image, index) => {
                                formData.append(`images[${index}]`, processImagePath(image));
                            });
                        } else {
                            formData.append(`images[${index}]`, processImagePath(imageSrc));
                        }

                        // Add information about deleted images if needed
                        if (STATE.deletedImages.length > 0) {
                            formData.append('deleted_images', JSON.stringify(STATE.deletedImages));
                        }
                    });
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            title: 'Thành công',
                            text: 'Banner đã được lưu thành công.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('#bannerModal').modal('hide');
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        let response = JSON.parse(xhr.responseText);
                        let errorMessage = '';

                        if (response.errors) {
                            // Loop through errors to create a readable error message
                            for (let field in response.errors) {
                                errorMessage += response.errors[field].join(', ') + '\n';
                            }
                        } else {
                            errorMessage = 'Có lỗi xảy ra.';
                        }

                        Swal.fire({
                            title: 'Lỗi',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }

            /**
             * Preview single image banner.
             */
            function previewRegular(input) {
                const $previewContainer = $('#regularPreview');
                $previewContainer.empty();

                if (input.files && input.files.length > 0) {
                    $.each(input.files, function(index, file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imagePreview = `
                                <div class="preview-image">
                                    <img src="${e.target.result}" class="img-fluid" alt="Image ${index + 1}">
                                    <div class="preview-info">
                                        <span class="file-size">${index + 1}</span>
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="editRegular(this)">Chỉnh sửa hình ảnh</button>
                                </div>
                            `;

                            // Append the image preview to the container
                            $previewContainer.append(imagePreview);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            }

            /**
             * Preview multi image banner.
             */
            function previewSlider(input) {
                const $previewContainer = $('#sliderPreview');
                const existingImages = [];

                $previewContainer.find('.preview-image').each(function() {
                    const imgSrc = $(this).find('img').attr('src');
                    existingImages.push(imgSrc);
                });

                if (input.files && input.files.length > 0) {
                    const totalImages = existingImages.length + input.files.length;
                    if (totalImages > CONFIG.maxSliderImages) {
                        Swal.fire({
                            title: 'Lỗi',
                            text: `Chỉ được phép tải lên tối đa ${CONFIG.maxSliderImages} hình ảnh`,
                            icon: 'error'
                        });
                        return;
                    }

                    $.each(input.files, function(index, file) {
                        // Validate file
                        const validation = validateFile(file);
                        if (!validation.valid) {
                            Swal.fire('Lỗi', validation.message, 'error');
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const currentIndex = existingImages.length + index;
                            const imagePreview = `
                                <div class="preview-image" data-index="${currentIndex}">
                                    <img src="${e.target.result}" class="img-fluid" alt="Image ${currentIndex + 1}">
                                    <div class="preview-info">
                                        <span class="file-size">${currentIndex + 1}</span>
                                    </div>
                                    <span class="preview-delete" onclick="removeSliderImage(${currentIndex})">&times;</span>
                                    <button type="button" class="btn btn-primary btn-edit-image" onclick="editSliderImage(${currentIndex})">Chỉnh sửa hình ảnh</button>
                                </div>
                            `;

                            $previewContainer.append(imagePreview);

                            if (STATE.currentImages) {
                                STATE.currentImages.push(e.target.result);
                            } else {
                                STATE.currentImages = [...existingImages, e.target.result];
                            }
                        };
                        reader.readAsDataURL(file);
                    });
                }
            }

            /**
             * Delete a banner.
             */
            function deleteBanner(id) {
                Swal.fire({
                    title: 'Xác nhận',
                    text: 'Bạn có chắc chắn muốn xóa banner này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('admin.banners.delete', ':id') }}`.replace(':id', id),
                            type: 'DELETE',
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                            },
                            success: function() {
                                Swal.fire('Đã xóa!', 'Banner đã được xóa.', 'success');
                                loadBanners();
                            },
                            error: function() {
                                Swal.fire('Lỗi', 'Có lỗi xảy ra khi xóa banner.', '');
                            }
                        });
                    }
                });
            }

            /**
             * Remove url path.
             */
            function processImagePath(imagePath) {
                // If the image is a data URL (newly uploaded image), return as is
                if (imagePath.startsWith('data:image')) {
                    return imagePath;
                }

                // Remove the base URL if it exists
                return imagePath.replace(/.*?storage\//, '');
            }

            function updateStatus(id, checkbox) {
                $isChecked = checkbox.checked;
                const url = CONFIG.routes.update_status.replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        is_active: $isChecked
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Cập nhật thành công',
                            text: 'Trạng thái banner đã được cập nhật.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Cập nhật  bại',
                            text: 'Trạng thái banner cập nhật thất bại.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    }
                });

            }

            window.showTooltip = (button) => {
                const tooltip = $(button).siblings('.tooltip-preview-image');
                $('.tooltip-preview-image').not(tooltip).hide();
                tooltip.toggle();
            };

            $(document).click((event) => {
                if (!$(event.target).closest('.tooltip-preview-image, [onclick*="showTooltip"]').length) {
                    $('.tooltip-preview-image').hide();
                }
            });
        </script>
    @endsection
