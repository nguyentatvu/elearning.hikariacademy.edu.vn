@php
    $id = isset($id) ? $id : 0;
    $title = isset($title) ? $title : 'Xác nhận xóa';
@endphp
@if (isset($delete_button))
    {{ $delete_button }}
@else
    <button type="button" class="transparent" data-toggle="modal" data-target="#confirm_delete_modal{{ $id }}">
        <i class="fa fa-trash text-danger"></i>
    </button>
@endif
<!-- Modal -->
<div class="modal fade" id="confirm_delete_modal{{ $id }}" tabindex="-1" aria-labelledby="confirmDeleteLabel">
    <div class="modal-dialog" style="border: solid 1px #dee2e6;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteLabel">{{ $title }}</h5>
                <button type="button" class="close" onclick="$(event.target).closest('.modal.fade').modal('hide');">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$(event.target).closest('.modal.fade').modal('hide');">
                    Đóng
                </button>
                @if(isset($confirm_button))
                    {{ $confirm_button }}
                @else
                    {!! Form::open(['url' => $url, 'method' => 'DELETE', 'id' => 'delete-form-'.$id, 'style' => 'display:inline-block']) !!}
                        <button type="submit" class="btn btn-danger btn-delete" style="display: block;">Xác nhận</button>
                    {!! Form::close() !!}
                @endif
            </div>
        </div>
    </div>
</div>