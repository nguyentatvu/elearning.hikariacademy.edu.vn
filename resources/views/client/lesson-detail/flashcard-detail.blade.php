<link href="{{ asset('css/pages/lesson-detail/flashcard-detail.css') }}" rel="stylesheet">

<div class="flashcard-detail-container card">
    <h5 class="mb-0">Danh sách từ vựng</h5>
    <div class="card-body p-0 mt-1">
        <div class="flashcard-detail-wrapper">
            @foreach ($flashcardDetail as $detail)
                <div class="flashcard-detail">
                    <div class="flashcard-term">{!! change_furigana($detail->m1tuvung, 'echo') !!}</div>
                    <div class="flashcard-meaning-block">
                        <div class="flashcard-meaning">{!! change_furigana($detail->m2ynghia, 'echo') !!}</div>
                        <div class="flashcard-reading">{!! change_furigana($detail->m2cachdoc, 'echo') !!}</div>
                        <div class="flashcard-sino-vietnamese">{!! change_furigana($detail->m2amhanviet, 'echo') !!}</div>
                    </div>
                    <div class="flashcard-audio" onclick="playAudio('{{'/public/uploads/flashcard/' . $detail->mp3}}')">
                        <i class="bi bi-volume-up"></i>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    /**
     * play audio
     */
    function playAudio(url) {
        let audio = new Audio(url);
        audio.play();
    }
</script>