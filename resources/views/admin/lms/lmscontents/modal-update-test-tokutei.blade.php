<style>
    /* Style cho form group */
    #updateQuestionModal .form-group {
        margin-bottom: 20px;
        background-color: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    /* Style cho label */
    #updateQuestionModal .form-group label {
        display: block;
        margin-bottom: 12px;
        font-weight: 600;
        color: #333;
        font-size: 16px;
    }

    #updateQuestionModal .form-group.disabled,
    #updateQuestionModal .form-group.disabled>* {
        cursor: not-allowed;
        user-select: none;
        pointer-events: none;
        opacity: 0.5;
    }

    /* Layout cho row và column */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }

    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 0 10px;
    }

    .col-md-4 {
        width: 33.33% !important;
    }

    /* Custom file input */
    .custom-file-upload {
        position: relative;
        overflow: hidden;
        display: block;
        cursor: pointer;
    }

    .custom-file-upload input[type="file"] {
        position: absolute;
        left: -9999px;
    }

    .file-upload-btn {
        display: inline-flex;
        align-items: center;
        background-color: #4361ee;
        color: white;
        padding: 12px 20px;
        border-radius: 4px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        width: 100%;
        margin-bottom: 10px;
    }

    .file-upload-btn:hover {
        background-color: #3a56d4;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }

    .file-upload-btn:active {
        transform: translateY(0);
    }

    .file-upload-btn svg {
        margin-right: 8px;
    }

    .file-name {
        display: block;
        margin-top: 8px;
        font-size: 14px;
        color: #666;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 6px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        background-color: #f8fafc;
    }

    /* Image preview */
    .image-preview-container {
        border: 2px dashed #e2e8f0;
        border-radius: 8px;
        padding: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 230px;
        background-color: #f8fafc;
        position: relative;
    }

    .image-preview-container.has-image {
        border-style: solid;
        border-color: #4361ee;
    }

    .image-preview-placeholder {
        color: #94a3b8;
        text-align: center;
        font-size: 14px;
    }

    #imagePreview {
        max-width: 100%;
        max-height: 200px;
        border-radius: 4px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        object-fit: contain;
    }

    .clear-image {
        position: absolute;
        top: 8px;
        right: 8px;
        background-color: rgba(255, 255, 255, 0.8);
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #ef4444;
        font-weight: bold;
        transition: all 0.2s;
    }

    .clear-image:hover {
        background-color: #ef4444;
        color: white;
    }

    @media (max-width: 768px) {
        .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 15px;
        }
    }
</style>

<div class="modal fade close-modal" id="updateQuestionModal" tabindex="-1" role="dialog" onclick="closeModal(event)" static>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div style="display: flex; justify-content: space-between; width: 100%;">
                    <h5 class="modal-title" id="updateQuestionModalLabel">Cập nhật câu hỏi</h5>
                    <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close" onclick="closeModal(event)">
                        <span aria-hidden="true" class="close-modal">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <form id="updateQuestionForm" method="POST">
                    <input type="hidden" id="deleteImageFlag" name="delete_image" value="0">
                    <div class="form-group">
                        <label for="tokutei_content">Nội dung</label>
                        <textarea class="form-control" id="tokutei_content" name="tokutei_content" rows="3" required></textarea>
                    </div>
                    <div class="row question-options-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tokutei_option_1">Lựa chọn 1</label>
                                <input type="text" class="form-control" id="tokutei_option_1" name="tokutei_options[]" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tokutei_option_2">Lựa chọn 2</label>
                                <input type="text" class="form-control" id="tokutei_option_2" name="tokutei_options[]" required>
                            </div>
                        </div>
                    </div>
                    <div class="row question-options-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tokutei_option_1">Lựa chọn 3</label>
                                <input type="text" class="form-control" id="tokutei_option_3" name="tokutei_options[]" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tokutei_option_2">Lựa chọn 4</label>
                                <input type="text" class="form-control" id="tokutei_option_4" name="tokutei_options[]" required>
                            </div>
                        </div>
                    </div>
                    <div class="row question-options-row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tokutei_answer">Đáp án</label>
                                <input type="text" class="form-control" id="tokutei_answer" name="tokutei_answer" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tokutei_section">Phần thi</label>
                                <select name="tokutei_section" id="tokutei_section" class="form-control">
                                    @foreach ($section_category_list['section_list'] as $section_index => $section_label)
                                        <option value="{{ $section_index }}">{{ $section_label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tokutei_category">Hạng mục</label>
                                <select name="tokutei_category" id="tokutei_category" class="form-control">
                                    @foreach ($section_category_list['category_list'][1] as $category_index => $category_label)
                                        <option value="{{ $category_index }}">{{ $category_label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tokutei_image">Hình ảnh</label>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="custom-file-upload">
                                    <input type="file" id="tokutei_image" name="tokutei_image" accept="image/*"
                                        onchange="previewImage(this)">
                                    <button class="file-upload-btn" type="button"
                                        onclick="document.getElementById('tokutei_image').click()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="17 8 12 3 7 8"></polyline>
                                            <line x1="12" y1="3" x2="12" y2="15"></line>
                                        </svg>
                                        Chọn hình ảnh
                                    </button>
                                    <span class="file-name" id="fileName">Chưa có file nào được chọn</span>
                                    <button type="button" class="btn btn-danger mt-2" id="deleteImageBtn" style="margin-top: 20px;" onclick="toggleDeleteImage()">
                                        <span aria-hidden="true">&times; <span class="delete-image-label">Xóa ảnh đã được lưu</span></span>
                                    </button>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <div class="image-preview-container" id="previewContainer">
                                    <div class="image-preview-placeholder" id="previewPlaceholder">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                        <p>Hình ảnh sẽ hiển thị ở đây</p>
                                    </div>
                                    <img id="imagePreview" src="" alt="Preview" style="display: none;">
                                    <button class="clear-image" id="clearImage" style="display: none;">×</button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" onclick="submitForm()">Cập nhật</button>
                                <button type="button" class="btn btn-default close-modal" data-dismiss="modal" onclick="closeModal(event)">Hủy</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const categoryList =  @json($section_category_list['category_list']);
    let selectedCategoryIndex = -1;

    function openUpdateQuestionModal(event, questionId) {
        event.preventDefault();
        $('#updateQuestionForm')[0].reset();

        const rowData = $(event.target).closest('tr');
        selectedCategoryIndex = rowData.find('span[data-category]').text();

        $('#tokutei_content').val(rowData.find('span[data-content]').text());
        $('#tokutei_answer').val(rowData.find('span[data-answer]').text());
        $('#tokutei_section').val(rowData.find('span[data-section]').text());
        $('#deleteImageBtn').css('display', rowData.find('img').length > 0 ? 'block' : 'none');

        $('#tokutei_section').change();

        const questionOptions = rowData.find('div[data-options]').data('options');
        $('input[name="tokutei_options[]"]').each(function(index) {
            if (questionOptions[index] !== undefined) {
                $(this).val(questionOptions[index]);
            }
        });

        $('#updateQuestionForm').attr('action', '{{url('test-tokutei/updateQuestion')}}/' + questionId);
        $('#updateQuestionModal').modal('show');
    }

    document.addEventListener('DOMContentLoaded', function () {
        $('#tokutei_section').on('change', () => {
            const selectedSectionKey = $('#tokutei_section').val();
            const categorySelectElement = $('#tokutei_category');
            categorySelectElement.empty();

            $.each(categoryList[selectedSectionKey], (key, value) => {
                categorySelectElement.append($('<option>', {
                    value: key,
                    text: value,
                    selected: (key == selectedCategoryIndex)
                }));
            });
        });
    });

    function setCategoryOption(categorySelectElement, categoryList, selectedSectionKey) {
        categorySelectElement.empty();

        $.each(categoryList[selectedSectionKey], (key, value) => {
            categorySelectElement.append($('<option>', {
                value: key,
                text: value
            }));
        });
    }

    function previewImage(input) {
      const previewContainer = document.getElementById('previewContainer');
      const preview = document.getElementById('imagePreview');
      const placeholderElement = document.getElementById('previewPlaceholder');
      const clearButton = document.getElementById('clearImage');
      const fileNameElement = document.getElementById('fileName');

      if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholderElement.style.display = 'none';
            clearButton.style.display = 'flex';
            previewContainer.classList.add('has-image');

            // Hiển thị tên file
            const fileName = input.files[0].name;
            fileNameElement.textContent = fileName.length > 25 ? fileName.substring(0, 22) + '...' : fileName;
        }

        reader.readAsDataURL(input.files[0]);
      }
    }

    // Thêm chức năng xóa ảnh
    document.getElementById('clearImage').addEventListener('click', function() {
        clearImage();
    });

    const clearImage = () => {
        const clearImage = document.getElementById('clearImage');
        const preview = document.getElementById('imagePreview');
        const placeholderElement = document.getElementById('previewPlaceholder');
        const fileInput = document.getElementById('tokutei_image');
        const previewContainer = document.getElementById('previewContainer');
        const fileNameElement = document.getElementById('fileName');

        preview.src = '';
        preview.style.display = 'none';
        placeholderElement.style.display = 'block';
        clearImage.style.display = 'none';
        previewContainer.classList.remove('has-image');
        fileInput.value = '';
        fileNameElement.textContent = 'Chưa có file nào được chọn';
    }

    function submitForm() {
        let form = $('#updateQuestionForm');
        let formData = new FormData(form[0]);

        // Remove any existing error messages
        $('.error-message').remove();

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert('Cập nhật thành công!');
            },
            error: function(xhr) {
                alert('Có lỗi xảy ra khi cập nhật!');
            },
            complete: function() {
                clearImage();
                $('#updateQuestionModal').modal('hide');
                $('.datatable').DataTable().ajax.reload(null, false);
                $('.error-message').remove();
                toggleDeleteImage(false);
            }
        });
    }

    const closeModal = (event) => {
        const element = $(event.target);

        if (!element.hasClass('close-modal')) {
            return;
        }

        $('.error-message').remove();
        clearImage();
        toggleDeleteImage(false);
    }

    const toggleDeleteImage = (forcedStatus) => {
        const deleteImageFlag = document.getElementById('deleteImageFlag');
        let deletesImage = !parseInt(deleteImageFlag.value) ? 1 : 0;

        if (typeof forcedStatus === 'boolean') {
            deletesImage = forcedStatus ? 1 : 0;
        }
        deleteImageFlag.value = deletesImage;

        const deleteImageText = deletesImage ? 'Ảnh đã lưu sẽ bị xóa!' : 'Xóa ảnh đã lưu';
        $('.delete-image-label').text(deleteImageText);

        if (typeof forcedStatus === 'boolean') {
            if (forcedStatus) {
                $('#deleteImageBtn').removeClass('btn-danger');
                $('#deleteImageBtn').addClass('btn-primary');
            } else {
                $('#deleteImageBtn').removeClass('btn-primary');
                $('#deleteImageBtn').addClass('btn-danger');
            }
        } else {
            $('#deleteImageBtn').toggleClass('btn-danger btn-primary');
        }
    }
</script>