@extends('client.shared.lesson-detail')

@section('lesson-detail-styles')
    <link href="{{ asset('css/pages/lesson-detail/handwriting.css') }}" rel="stylesheet">
@endsection

@section('lesson-detail-content')
    @php
        if ($handwriting->type == \App\JapaneseWritingPractice::HIRAGANA) {
            $handwritingDetail = $handwriting->hiraganaWritingPractices;
            $character = '';

            if ($handwritingDetail->isNotEmpty()) {
                $character = $handwritingDetail[0]->character;
            }
        } else {
            $handwritingDetail = $handwriting->kanjiWritingPractices;

            if ($handwritingDetail->isNotEmpty()) {
                $question = str_replace(
                    $handwritingDetail[0]->underlined_word,
                    '<u>' . $handwritingDetail[0]->underlined_word . '</u>',
                    $handwritingDetail[0]->full_word
                );
                $kanji = $handwritingDetail[0]->kanji;
                $totalKanji = mb_strlen($kanji);
            } else {
                $question = '';
                $kanji = '';
                $totalKanji = 0;
            }
        }
        $total = $handwritingDetail->count();
    @endphp
    <div id="handwriting_container" class="handwriting-container">
        <div id="handwriting_lesson" class="d-flex align-items-center handwriting-lesson">
            <span id="handwriting_lesson_title" class="handwriting-title">
                Đề bài:
                @if ($handwriting->type == \App\JapaneseWritingPractice::HIRAGANA)
                    <span id="handwriting_lesson_content" class="handwriting-lesson-content">
                        {{ $character }}</span>
                @else
                    <span id="handwriting_lesson_content" class="handwriting-lesson-content">
                        {!! $question !!}</span>
                @endif
            </span>
        </div>
        <div id="handwriting_wrapper" class="handwriting-wrapper">
            <div id="handwriting_tabs" class="handwriting-tabs">
                @if ($handwriting->type == \App\JapaneseWritingPractice::HIRAGANA)
                    <div class="handwriting-tab">
                        <span class="handwriting-tab-title" data-kanji="{{ $character }}">
                            {{ $character }}
                        </span>
                    </div>
                @else
                    @for ($index = 0; $index < $totalKanji; $index++)
                        <div class="handwriting-tab">
                            <span class="handwriting-tab-title" data-kanji="{{ mb_substr($kanji, $index, 1) }}">
                                Hán tự {{ $index + 1 }}
                            </span>
                        </div>
                    @endfor
                @endif
            </div>
            <div id="guide_and_canvas_container" class="guide-and-canvas-container">
                <div id="left_container" class="left-container">
                    <div class="handwriting-area">
                        <span class="handwriting-label">Luyện viết</span>
                        <div id="handwriting_content" class="handwriting-content">
                            <canvas id="handwriting_canvas" class="handwriting-canvas" width="300"
                                height="300"></canvas>
                        </div>
                        <div class="actions">
                            <div class="handwriting-action" onclick="clearCanvas()">
                                <i class="bi bi-eraser"></i>
                            </div>
                            <div class="handwriting-action" onclick="checkHandwriting()">
                                <span class="handwriting-action-title">Xem kết quả</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="right_container" class="right-container">
                    <div id="handwriting_guide" class="handwriting-guide">
                        <span class="handwriting-label">Kết quả</span>
                        <div id="handwriting_guide_content" class="handwriting-guide-content"></div>
                        <div class="actions">
                            <div id="handwriting_guide_redraw" class="hadnwriting-guide-redraw handwriting-action"
                                data-kanjivg-target="#animate">
                                <i class="bi bi-arrow-clockwise"></i>
                            </div>
                            <div id="handwriting_guide_eye" class="hadnwriting-guide-eye handwriting-action">
                                <i class="bi bi-eye"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="handwriting-tab-template" style="display: none;">
                <div class="handwriting-tab">
                    <span class="handwriting-tab-title"></span>
                </div>
            </div>
        </div>
        <div id="handwriting_arrow" class="handwriting-arrow">
            <i id="arrow_left" class="bi bi-arrow-left arrow disabled"></i>
            <div class="handwriting-page">
                <span id="current_page">
                    @if ($handwritingDetail->isNotEmpty())
                        1
                    @else
                        0
                    @endif
                </span> / {{ $total }}
            </div>
            <i id="arrow_right" class="bi bi-arrow-right arrow"></i>
        </div>
    </div>
@endsection

@section('lesson-detail-scripts')
    <script src="{{ asset('js/client/handwriting/kanji-animate.js') }}"></script>
    <script src="{{ asset('js/client/handwriting/handwriting-canvas.js') }}"></script>
    <script>
        let total = {{ $total }};
        const handwritingDetails = @json($handwritingDetail);
        const arrowLeft = $('#arrow_left');
        const arrowRight = $('#arrow_right');
        const handwritingLessonContent = $('#handwriting_lesson_content');
        const handwritingTabs = $('#handwriting_tabs');
        let currentIndex = 0;
        let currentPage = $currentPage = $('#current_page');
        let handwritingGuideRedraw = $('#handwriting_guide_redraw');
        let handwritingGuideEye = $('#handwriting_guide_eye');
        let fileKanjiSvg = "{{ asset('images/kanji') }}";
        let handwritingGuideContent = $('#handwriting_guide_content');
        let handwritingCanvasContent = $('#handwriting_canvas');
        let handwritingContent = $('#handwriting_content');
        let handwritingContentDiv;
        let handwritingCanvas;
        let kanji;
        let resultSVG;
        let handwritingGuideVisibilityObject = {};
        let handwritingGuideContentObject = {};
        let resultKanjiObject = {};
        let canvasData = {};
        new KanjivgAnimate('.hadnwriting-guide-redraw')

        $(document).ready(function() {
            handleArrowLeftEvent();
            handleArrowRightEvent();
            handleClickHandwritingTab();
            hideAndShowHandwritingGuide();
            createCanvasByTab();
            showFirstTab();
        });

        /**
         * Update Handwriting
         */
        function updateHandwriting(index) {
            const data = handwritingDetails[index];
            updateHandwritingLessonContent(data);
            updateHandwritingTabs(data);
            handleClickHandwritingTab();
            createCanvasByTab();
            showFirstTab();
            restoreCurrentCanvasData(index + 1);

            arrowLeft.toggleClass('disabled', index === 0);
            arrowRight.toggleClass('disabled', index === total - 1);
            $currentPage.text(index + 1);
        }

        /**
         * Update Handwriting Lesson Content
         */
        function updateHandwritingLessonContent(data) {
            if ({{ $handwriting->type }} == {{ \App\JapaneseWritingPractice::KANJI }}) {
                const question = data.full_word.replace(data.underlined_word, `<u>${data.underlined_word}</u>`);
                handwritingLessonContent.html(question);
            } else {
                handwritingLessonContent.text(data.character);
            }
        }

        /**
         * Update Handwriting Tabs
         */
        function updateHandwritingTabs(data) {
            handwritingTabs.empty();

            if ({{ $handwriting->type }} == {{ \App\JapaneseWritingPractice::KANJI }}) {
                const kanjiChars = data.kanji.split('');
                for (let i = 0; i < data.kanji.length; i++) {
                    const kanjiChar = kanjiChars[i];

                    let $tabClone = $('.handwriting-tab-template').first().clone();
                    $tabClone.find('.handwriting-tab-title')
                        .attr('data-kanji', kanjiChar)
                        .text(`Hán tự ${i + 1}`);
                    handwritingTabs.append($tabClone.children());
                }
            } else {
                let $tabClone = $('.handwriting-tab-template').first().clone();
                $tabClone.find('.handwriting-tab-title')
                    .attr('data-kanji', data.character)
                    .text(data.character);
                handwritingTabs.append($tabClone.children());
            }
        }

        /**
         * Handle Arrow Event
         */
        function handleArrowLeftEvent() {
            $('#arrow_left').on('click', function() {
                if (currentIndex > 0) {
                    saveCurrentCanvasData(currentIndex + 1);
                    currentIndex--;
                    updateHandwriting(currentIndex);
                }
            });
        }

        /**
         * Handle Arrow Event
         */
        function handleArrowRightEvent() {
            $('#arrow_right').on('click', function() {
                if (currentIndex < total - 1) {
                    saveCurrentCanvasData(currentIndex + 1);
                    currentIndex++;
                    updateHandwriting(currentIndex);
                }
            });
        }

        /**
         * Save Current Canvas Data
         */
        function saveCurrentCanvasData(currentIndex) {
            $('#handwriting_content canvas').each(function() {
                let canvas = $(this)[0];
                let canvasId = this.id;

                if (!canvasData[currentIndex]) {
                    canvasData[currentIndex] = {};
                }

                canvasData[currentIndex][canvasId] = canvas.toDataURL('image/png');
            });
        }

        /**
         * Restore Current Canvas Data
         */
        function restoreCurrentCanvasData(currentIndex) {
            $('#handwriting_content canvas').each(function() {
                let canvas = $(this)[0];
                let canvasId = this.id;
                let context = canvas.getContext('2d');
                let image = new Image();

                if (canvasData[currentIndex] && canvasData[currentIndex][canvasId]) {
                    image.src = canvasData[currentIndex][canvasId];

                    image.onload = function() {
                        context.clearRect(0, 0, canvas.width, canvas.height);
                        context.drawImage(image, 0, 0);
                    };
                }

                let kanjiIndex = canvasId.split('_').pop();

                if (resultKanjiObject[kanjiIndex]) {
                    resultKanji = $(resultKanjiObject[kanjiIndex]);
                    $(this).after(resultKanji)
                    showHandwritingTabTitle(kanjiIndex);
                }
            });
        }

        /**
         * Show First Tab
         */
        function showFirstTab() {
            let firstTab = $('#handwriting_tabs .handwriting-tab').first();
            firstTab.addClass('active');
            handleAfterClickingHandwritingTab(firstTab);
        }

        /**
         * Get Result SVG
         */
        function getResultSvg() {
            let code = kanji.codePointAt(0).toString(16);
            code = code.padStart(5, '0');
            let svg = getKanjiWithoutNumber(code);
        }

        /**
         * Handle click handwriting tab
         */
        function handleClickHandwritingTab() {
            $('.handwriting-tab').click(function() {
                $('.handwriting-tab').removeClass('active');
                $(this).addClass('active');
                handleAfterClickingHandwritingTab(this);
            });
        }

        /**
         * Handle After Clicking Handwriting Tab
         */
        function handleAfterClickingHandwritingTab(handwritingTab) {
            kanji = $(handwritingTab).find('.handwriting-tab-title').data('kanji').trim();
            getResultSvg();
            getCanvasByTab(kanji);
            showCanvasByTab(kanji);
            handleHandwritingGuideContent(kanji);
        }

        /**
         * Handle Handwriting Guide Content
         */
        function handleHandwritingGuideContent(kanji) {
            if (!handwritingGuideVisibilityObject[kanji]) {
                handwritingGuideContent.html('');
                disableHandwritingGuideEye();
                disableHandwritingGuideRedraw();
            } else {
                showHandwritingGuideContent(kanji);
                activeHandwritingGuideEye();
                activeHandwritingGuideRedraw();
            }
        }

        /**
         * Active Handwriting Guide Eye
         */
        function activeHandwritingGuideEye() {
            handwritingGuideEye.css("pointer-events", "auto");
            handwritingGuideEye.css("opacity", "1");
        }

        /**
         * Disable Handwriting Guide Eye
         */
        function disableHandwritingGuideEye() {
            handwritingGuideEye.css("pointer-events", "none");
            handwritingGuideEye.css("opacity", "0.5");
        }

        /**
         * Active Handwriting Guide Redraw
         */
        function activeHandwritingGuideRedraw() {
            handwritingGuideRedraw.css("pointer-events", "auto");
            handwritingGuideRedraw.css("opacity", "1");
        }

        /**
         * Disable Handwriting Guide Redraw
         */
        function disableHandwritingGuideRedraw() {
            handwritingGuideRedraw.css("pointer-events", "none");
            handwritingGuideRedraw.css("opacity", "0.5");
        }

        /**
         * Show Canvas By Tab
         */
        function showCanvasByTab(kanji) {
            $('#handwriting_content .canvas-wrapper').hide();
            $('#canvas_wrapper_' + kanji).show();
        }

        /**
         * Get Canvas By Tab
         */
        function getCanvasByTab(kanji) {
            handwritingContentDiv = $('#canvas_wrapper_' + kanji);
            handwritingCanvasContent = $('#handwriting_canvas_' + kanji);
            let canvas = handwritingCanvasContent[0];
            handwritingCanvas = new handwriting.Canvas(canvas);
        }

        /**
         * Create Canvas By Tab
         */
        function createCanvasByTab() {
            let currentCanvas = $('#handwriting_content canvas');
            let width = currentCanvas.attr('width');
            let height = currentCanvas.attr('height');
            let tabs = $('#handwriting_tabs .handwriting-tab');
            let tabCount = tabs.length;

            handwritingContent.empty();

            tabs.each(function(index) {
                let kanji = $(this).find('.handwriting-tab-title').data('kanji').trim();
                let newCanvasId = 'handwriting_canvas_' + kanji;

                if (!handwritingGuideVisibilityObject[kanji]) {
                    handwritingGuideVisibilityObject[kanji] = false;
                }

                if (!handwritingGuideContentObject[kanji]) {
                    handwritingGuideContentObject[kanji] = false;
                }

                let canvasWrapper = $('<div>', {
                    class: 'canvas-wrapper',
                    id: 'canvas_wrapper_' + kanji
                });

                let newCanvas = $('<canvas></canvas>', {
                    id: newCanvasId
                }).attr({
                    width: width,
                    height: height
                });

                canvasWrapper.append(newCanvas);
                handwritingContent.append(canvasWrapper);
            });
        }

        /**
         * Show Handwriting Guide Content
         */
        function showHandwritingGuideContent(kanji) {
            handwritingGuideVisibilityObject[kanji] = true;
            handwritingGuideContent.html('');

            getKanji(kanji)
        }

        /**
         * Get Kanji SVG
         */
        function getKanji(kanji) {
            if (handwritingGuideContentObject[kanji]) {
                handwritingGuideContent.append(handwritingGuideContentObject[kanji]);
            } else {
                let code = kanji.codePointAt(0).toString(16);
                code = code.padStart(5, '0');

                $.get(`${fileKanjiSvg}/${code}.svg`, function(data) {
                        const svgMatch = $(data).find('svg');

                        if (svgMatch.length) {
                            let svgContent = $('<div>').append(svgMatch.clone()).html();
                            svgContentWithId = svgContent.replace('<svg', '<svg id="animate"');
                            handwritingGuideContent.append(svgContentWithId);
                        }

                        let paths = handwritingGuideContent.find('path');
                        getColorForPath(paths, true);
                        handwritingGuideContentObject[kanji] = handwritingGuideContent.html();
                    })
                    .fail(function(error) {
                        console.error('Lỗi tải SVG:', error);
                    });
            }
        }

        /**
         * Get Kanji Without Number
         */
        function getKanjiWithoutNumber(code) {
            $.get(`${fileKanjiSvg}/${code}.svg`, function(data) {
                    const svgMatch = $(data).find('svg');

                    if (svgMatch.length) {
                        let svgContent = $('<div>').append(svgMatch.clone()).html();
                        resultSVG = cleanupSVGContent(svgContent);
                    }
                })
                .fail(function(error) {
                    console.error('Lỗi tải SVG:', error);
                });
        }

        /**
         * Get Color for Path
         */
        function getColorForPath(paths, isRandomColor, color = "#F0F0F0") {
            $(paths).each(function() {
                let c = color;

                if (isRandomColor) {
                    c = getRandomColor();
                }

                $(this).attr('stroke', c);
                $(this).attr('stroke-width', '3');
            });
        }

        /**
         * Get Random Color
         */
        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        /**
         * Clear Canvas
         */
        function clearCanvas() {
            handwritingCanvas.erase();
        }

        /**
         * Cleanup SVG Content
         */
        function cleanupSVGContent(svgContent) {
            // Create a temporary DOM element to parse the SVG content
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = svgContent;

            // Get the SVG element
            const svgElement = tempDiv.querySelector('svg');

            if (svgElement) {
                // Remove the last <g> element (which contains stroke numbers)
                const gElements = svgElement.querySelectorAll('g');
                if (gElements.length > 0) {
                    gElements[gElements.length - 1].remove();
                }

                // Remove all script tags
                const scriptElements = svgElement.querySelectorAll('script');
                scriptElements.forEach(script => script.remove());

                // Get the cleaned up SVG content
                const cleanedSVGContent = svgElement.outerHTML;

                return cleanedSVGContent;
            } else {
                console.error('No SVG element found in the provided content');
                return svgContent;
            }
        }

        /**
         * Check Handwriting
         */
        function checkHandwriting() {
            showResult(resultSVG);
            showHandwritingGuideContent(kanji);
            showHandwritingTabTitle(kanji)
        }

        /**
         * Show Handwriting Tab Title
         */
        function showHandwritingTabTitle(kanji) {
            let $element = $('.handwriting-tab-title[data-kanji="' + kanji + '"]');

            if ($element.length > 0) {
                $element.text(kanji);
            }
        }

        /**
         * Show Result
         */
        function showResult(resultSVG) {
            handwritingContentDiv.find('div').remove();
            const svg = $(resultSVG);
            let paths = svg.find('path');

            getColorForPath(paths, false);
            resultKanjiObject[kanji] = svg;
            handwritingContentDiv.append(svg);
            activeHandwritingGuideEye();
            activeHandwritingGuideRedraw();
        }

        /**
         * Hide And Show Handwriting Guide
         */
        function hideAndShowHandwritingGuide() {
            $('.hadnwriting-guide-eye').on('click', function() {
                handwritingGuideContent.children().toggle();
            });
        }
    </script>
@endsection
