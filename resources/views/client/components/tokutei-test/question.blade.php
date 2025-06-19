<style>
   .kg-study.kg-study-cuttom {
        background-color: white !important;
   }
</style>
@php
    $headerBlock = '
        <div class="kg-study kg-study-cuttom">
            <div id="data-exercise-hid">
                <div class="col-12">
                    <div class="ct-lesson ct-lesson17">';
    $footerBlock = '</div></div></div></div>';
@endphp

{!! $headerBlock !!}

<div class="wp-block-type d-flex justify-content-between position-relative">
    <div class="block-type block-type-cuttom
        {{ isset($record->correct) ? ($record->correct == 1 ? 'correct-status' : 'incorrect-status') . ' ' : '' }}
    ">
        {{-- Question number --}}
        <div class="title-block-type" style="color: #fff;">
            <a>Câu số {{ $record->question_order }}</a>
        </div>

        {{-- Question content --}}
        <div class="text-question-les" style="text-align: left; margin-bottom: 0;">
            <p class="text-primary noto-san-jp-font"><strong>{!! $record->content !!}</strong></p>
        </div>

        {{-- Question options --}}
        <div class="d-flex content-image-question noto-san-jp-font" style="justify-content: space-between; align-items: center;">
            <div class="list-select-les align-self-start-md" style="">
                @foreach ($record->options as $key_option => $option)
                    @php
                        $question_anwser_class = $record->answer == $key_option
                        ? 'correct-answer'
                        : (
                            isset($record->check) && $record->check == $key_option && !$record->correct
                            ? 'incorrect-answer'
                            : ''
                        )
                    @endphp
                    <div class="item-check-select
                        {{ isset($acc_score) ? $question_anwser_class : '' }}
                        ">
                        <div class="form-check">
                            <input
                                type="radio" hidden name="quest_{{ $record->id }}"
                                id="answers_{{ $record->id }}_{{ $key_option }}"
                                class="form-check-input" value="{{ (int) $key_option }}"
                                {{ isset($record->check) && $record->check == (int) $key_option ? 'checked' : '' }}
                            >
                            <label
                                for="answers_{{ $record->id }}_{{ $key_option }}"
                                class="form-kana text-type"
                            >
                                <span class="fa-stack icon-input icon-incorrect">
                                    <i class="bi bi-x-square"></i>
                                </span>
                                <span class="icon-input icon-no-checked">
                                    <i class="bi bi-square"></i>
                                </span>
                                <span class="icon-input icon-checked">
                                    <i class="bi bi-check-square"></i>
                                </span>
                                <span class="icon-input icon-correct">
                                    <i class="bi bi-check-square"></i>
                                </span>
                                <span class="text-label">
                                    <p>{!! $option !!}</p>
                                </span>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Question image --}}
            @if($record->image_url)
                <div style="width: 300px; height: 200px; flex-shrink: 0;">
                    <img src="{{ $record->image_url }}" alt="" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
            @endif
        </div>
    </div>
</div>

{!! $footerBlock !!}