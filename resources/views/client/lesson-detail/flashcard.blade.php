@extends('client.shared.lesson-detail')

@section('lesson-detail-styles')
    <link href="{{ admin_asset('css/flashcard/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pages/lesson-detail/flashcard.css') }}" rel="stylesheet">
@endsection

@section('lesson-detail-content')
    @php
        if (isset($flashcard)) {
            $flashcardDetail = $flashcard->flashcardDetails;
        } else {
            $flashcardDetail = null;
        }

    @endphp
    <div id="flashcard_body" class="flashcard-body">
        @if (isset($flashcardDetail) && $flashcardDetail->count() > 0)
            <div class="flashcard-container">
                <div id="flashcard_wrapper" class="flashcard-wrapper">
                    <div id="flashcard_arrow_left" class="flashcard-arrow">
                        <img src="{{ asset('images/icons/arrow-left.png') }}" alt="flashcard_arrow_left">
                    </div>
                    <div id="flashcard_content" class="flashcard-content">
                        @foreach ($flashcardDetail as $detail)
                            <div id="card{{ $loop->index + 1 }}" class="flashcard" data-card-id="{{ $loop->index + 1 }}">
                                <div id="flashcard_front" class="flashcard-side front">
                                    <div class="flashcard-word-example">
                                        <span class="flashcard-word">{!! change_furigana($detail->m1tuvung, 'echo') !!}</span>
                                        <span class="flashcard-example">{!! change_furigana($detail->m1vidu, 'echo') !!}</span>
                                    </div>
                                    <span class="flashcard-instruction">クリックして反転</span>
                                </div>
                                <div id="flashcard_back" class="flashcard-side back">
                                    <span class="flashcard-reading">{!! change_furigana($detail->m2cachdoc, 'echo') !!}</span>
                                    <span class="flashcard-sino-vietnamese">{!! change_furigana($detail->m2amhanviet, 'echo') !!}</span>
                                    <span class="flashcard-meaning">{!! change_furigana($detail->m2ynghia, 'echo') !!}</span>
                                    <span class="flashcard-example">{!! change_furigana($detail->m2vidu, 'echo') !!}</span>
                                    <span class="flashcard-instruction">Click để lật mặt</span>
                                </div>
                                <div id="audio{{ $loop->index + 1 }}" data-audio ="{{ $detail->mp3 }}" style="display: none">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div id="flashcard_arrow_right" class="flashcard-arrow">
                        <img src="{{ asset('images/icons/arrow-right.png') }}" alt="flashcard_arrow_left">
                    </div>
                </div>
                <div class="flashcard-page">
                    <span id="current_page">1</span> / {{ count($flashcardDetail) }}
                </div>
                <div class="flashcard-mode">
                    <div id="mode_auto" class="mode mode-atuo" onclick="changeMode('mode_auto')">Tự động
                        chuyển</div>
                    <div id="mode_normal" class="mode mode-normal active" onclick="changeMode('mode_normal')">
                        Ngừng</div>
                    <div id="mode_random" class="mode mode-random" onclick="changeMode('mode_random')">Xem ngẫu
                        nhiên
                    </div>
                </div>
                <div class="flashcard-toggle-switch">
                    <input type="checkbox" id="flashcard-toggle" class="flashcard-toggle-checkbox">
                    <label for="flashcard-toggle" class="flashcard-toggle-label"></label>
                    <span>Âm thanh</span>
                </div>
            </div>
        @else
            <div class="d-flex justify-content-center align-items-center w-100" style="height: 40vh;">
                <div class="alert alert-warning fs-4 mt-3" role="alert">
                    <span>Bài học sẽ sớm có thôi, bạn quay lại sau nhé!</span>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('lesson-detail-scripts')
    <script src="{{ admin_asset('js/flashcard/lodash.js') }}"></script>
    <script>
        let isSwitching = false;
        let debounceTimeout = null;
        let interval;
        let flashcardData = @json($flashcardDetail);
        let shuffledOrder = [];

        $(document).ready(async function() {
            handleClickFlashcard();
            switchCard(1);
            handleClickArrowRight();
            handleClickArrowLeft();

            @if($isValidPayment && !$isFinishedContent)
                await earnPointFinishContent('{{ $detailContent->id }}', 0, '');
            @endif
        });

        function handleClickFlashcard() {
            $('.flashcard-content > .flashcard').on('click', function() {
                $(this).toggleClass('flipped');
            });
        }

        /**
         * Handle click arrow right
         */
        function handleClickArrowRight() {
            $('#flashcard_arrow_right').on('click', function() {
                var currentCardId = $(".flashcard.active").data("card-id");

                if (currentCardId == $('.flashcard-content > .flashcard').length) {
                    return;
                }

                $('.page').addClass('disable-links')
                var nextCardId = parseInt(currentCardId) + 1;
                switchCard(nextCardId, "slideInRight", "slideOutLeft");
                $('.page').removeClass('disable-links')
            });
        }

        /**
         * Handle click arrow left
         */
        function handleClickArrowLeft() {
            $('#flashcard_arrow_left').on('click', function() {
                var currentCardId = $(".flashcard.active").data("card-id");
                if (currentCardId == 1) return;
                $('.page').addClass('disable-links')
                var prevCardId = parseInt(currentCardId) - 1;
                switchCard(prevCardId, "slideInLeft", "slideOutRight");
                $('.page').removeClass('disable-links')
            })
        }

        /**
         * Switch card
         */
        function switchCard(cardId, inClass, outClass, option = null) {
            if (isSwitching) {
                return;
            }
            isSwitching = true;

            // effect
            if (!inClass || !outClass) {
                inClass = "slideInRight";
                outClass = "slideOutLeft";
            }

            // auto flip flash card
            let currentCard = $(".flashcard.active");
            if (currentCard.length == 1) {
                currentCard
                    .addClass("animated")
                    .addClass(outClass)
                    .fadeOut(200)
                    .promise()
                    .done(function() {
                        $(".flashcard")
                            .removeClass(inClass)
                            .removeClass(outClass)
                            .removeClass("animated")
                            .removeClass('active');
                        $(".flashcard[data-card-id=" + cardId + "]")
                            .addClass("animated")
                            .addClass(inClass)
                            .addClass("active")
                            .fadeIn(200)
                            .promise()
                            .done(function() {
                                isSwitching = false;
                                clearTimeout(debounceTimeout);
                                debounceTimeout = setTimeout(() => {
                                    addAudio(cardId)
                                }, 1200);
                            });
                    });

            } else {
                $(".flashcard").removeClass("active");
                $(".flashcard[data-card-id=" + cardId + "]")
                    .addClass("animated")
                    .addClass(inClass)
                    .addClass("active");
                isSwitching = false;
            }

            updatePageNumber(cardId);

            if (option === 'auto') {
                setTimeout(function() {
                    $('#card' + cardId).addClass('flipped');
                }, 3500);
            }
        }

        /**
         * Update page number
         */
        function updatePageNumber(cardId) {
            $('#current_page').text(cardId)
        }

        /**
         * Add audio
         */
        function addAudio(cardId) {
            $('#audio' + cardId).empty();
            let url = '/public/uploads/flashcard/' + $('#audio' + cardId).data("audio");

            if ($('#flashcard-toggle').prop("checked")) {
                $('#audio' + cardId).append(
                    '<audio style="display: none;" controls="controls" onloadeddata="var audioPlayer = this; setTimeout(function() { audioPlayer.play(); }, 500)">' +
                    ' <source src="' + url + '" type="audio/mp3" />' +
                    ' </audio>')
            }
        }

        /**
         * Change mode
         */
        function changeMode(mode) {
            $('.mode').removeClass('active');
            $('#' + mode).addClass('active');

            let currentCard = 1;
            clearInterval(interval);
            interval = null;

            if (mode === "mode_auto" || mode === "mode_random") {
                $('.flashcard-arrow').addClass('disabled')

                if (mode === "mode_random") {
                    //shuffle flash card
                    shuffleFlashcards();
                    updatePageNumber(currentCard);
                } else {
                    currentCard = $(".flashcard.active").data("card-id");
                    currentCard = parseInt(currentCard)
                }

                if (!interval) {
                    interval = setInterval(function() {
                        let total = $('.flashcard-content > .flashcard').length;

                        if (currentCard <= total) {
                            switchCard(currentCard, null, null, 'auto');
                            $('#card' + currentCard).removeClass('flipped');
                            currentCard++;
                        } else {
                            currentCard = 1;
                        }
                    }, 7000);
                }
            } else {
                $('.flashcard-arrow').removeClass('disabled')
            }
        }

        /**
         * Shuffle flash card
         */
        function shuffleFlashcards() {
            let randomFlashcards = _.shuffle(flashcardData);
            $('#flashcard_content').empty();

            randomFlashcards.forEach((data, index) => {
                let  active = '';

                if (index === 0) {
                    active = 'active';
                }

                let newIndex = index + 1;
                let random = randomFlashcards[index];
                let flashcardHtml = `
                    <div id="card${newIndex}" class="flashcard animated slideInRight ${active}" data-card-id="${newIndex}">
                        <div id="flashcard_front" class="flashcard-side front">
                            <span class="flashcard-word">${random.m1tuvung || ''}</span>
                            <span class="flashcard-example">${random.m1vidu || ''}</span>
                            <span class="flashcard-instruction">クリックして反転</span>
                        </div>
                        <div id="flashcard_back" class="flashcard-side back">
                            <span class="flashcard-meaning">${random.m2ynghia || ''}</span>
                            <span class="flashcard-reading">${random.m2cachdoc || ''}</span>
                            <span class="flashcard-sino-vietnamese">${random.m2amhanviet || ''}</span>
                            <span class="flashcard-example">${random.m2vidu || ''}</span>
                            <span class="flashcard-instruction">Click để lật mặt</span>
                        </div>
                        <div id="audio${newIndex}" data-audio="${random.mp3 || ''}" style="display: none">
                        </div>
                    </div>
                `;

                $('#flashcard_content').append(flashcardHtml);
            });

            handleClickFlashcard();
        }
    </script>
@endsection
