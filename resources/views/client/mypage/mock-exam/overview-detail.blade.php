@extends('client.shared.mypage')

@section('mypage-styles')
    <link rel="stylesheet" href="{{ asset('css/custom/mock-exam/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/my-page/mock-exam.css') }}">
@endsection

@section('mypage-content')
    <div class="card mb-10">
        <div class="card-header">
            <h3 class="card-title">
                <?php change_furigana_text ($item->title); ?>
            </h3>
        </div>
        <div class="card-body">
            @if(!$content_record)
            <div class="row">
                @php $image_path = IMAGE_PATH_UPLOAD_SERIES . 'n' . $item->category_id . '.png'; @endphp
                <div class="col-md-4">
                    <img src="{{ $image_path }}" class="img-responsive center-block" alt="Bộ đề thi" width="100%">
                </div>
                <div class="col-md-8 ">
                    @include('client.mypage.mock-exam.overview-detail-item', ['series' => $item, 'content' => $content_record])
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@section('mypage-scripts')
    <script>

    </script>
@endsection