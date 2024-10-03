@extends('client.shared.lesson-detail')

@section('lesson-detail-styles')
    <link href="{{ asset('css/pages/lesson-detail/handwriting.css') }}" rel="stylesheet">
@endsection

@section('lesson-detail-content')
    <div id="handwriting_container">
        <div id="handwriting_lesson" class="d-flex align-items-center">
            <span id="handwriting_lesson_title" class="handwriting-title">
                Đề bài:
                <span id="handwriting_lesson_content">観光地</span>
            </span>
        </div>
        <div id="handwriting_wrapper">
            <div id="handwriting_tabs">
                <div class="handwriting-tab">
                    <span class="handwriting-tab-title">
                        観
                    </span>
                </div>
                <div class="handwriting-tab">
                    <span class="handwriting-tab-title">
                        光
                    </span>
                </div>
                <div class="handwriting-tab">
                    <span class="handwriting-tab-title">
                        地
                    </span>
                </div>
            </div>
            <div id="guide_and_canvas_container">
                <div id="left_container">
                    <div id="handwriting_guide">
                        <div id="handwriting_guide_content">
                        </div>
                        <div class="actions">
                            <div class="hadnwriting-guide-redraw handwriting-action" data-kanjivg-target="#animate">
                                <i class="bi bi-arrow-clockwise"></i>
                            </div>
                            <div class="hadnwriting-guide-eye handwriting-action">
                                <i class="bi bi-eye"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="right_container">
                    <div id="handwriting_area">
                        <div id="handwriting_content">
                            <canvas id="handwriting_canvas" width="300" height="300"></canvas>
                        </div>
                        <div class="actions">
                            <div class="handwriting-action" onclick="clearCanvas()">
                                <i class="bi bi-eraser"></i>
                            </div>
                            <div class="handwriting-action" onclick="checkHandwriting()">
                                <span class="handwriting-action-title">Kiểm tra</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('lesson-detail-scripts')
    <script src="{{ asset('js/client/handwriting/kanji-animate.js') }}"></script>
    <script src="{{ asset('js/client/handwriting/handwriting-canvas.js') }}"></script>
    <script>
        const fileKanjiSvg = "{{ asset('images/kanji') }}";
        const handwritingGuideContent = $('#handwriting_guide_content');
        const handwritingCanvasContent = $('#handwriting_canvas');
        const canvas = handwritingCanvasContent[0];
        const ctx = canvas.getContext('2d');
        const handwritingCanvas = new handwriting.Canvas(document.getElementById("handwriting_canvas"));
        let kanji;
        let result;
        new KanjivgAnimate('.hadnwriting-guide-redraw')

        $(document).ready(function() {
            handleClickHandwritingTab();
            hideAndShowHandwritingGuide();
        });

        function setup() {
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

                kanji = $(this).find('.handwriting-tab-title').text().trim();
                showHandwritingGuideContent(kanji);
                setup();
            });
        }

        /**
         * Show Handwriting Guide Content
         */
        function showHandwritingGuideContent(kanji) {
            handwritingGuideContent.html('');
            let code = kanji.codePointAt(0).toString(16);
            code = code.padStart(5, '0');

            getKanji(code);
        }

        /**
         * Get Kanji SVG
         */
        function getKanji(code) {
            $.get(`${fileKanjiSvg}/${code}.svg`, function(data) {
                    const svgMatch = $(data).find('svg');

                    if (svgMatch.length) {
                        let svgContent = $('<div>').append(svgMatch.clone()).html();
                        svgContentWithId = svgContent.replace('<svg', '<svg id="animate"');

                        handwritingGuideContent.append(svgContentWithId);
                    }

                    let paths = handwritingGuideContent.find('path');
                    getColorForPath(paths, true);
                })
                .fail(function(error) {
                    console.error('Lỗi tải SVG:', error);
                });
        }

        function getKanjiWithoutNumber(code) {
            $.get(`${fileKanjiSvg}/${code}.svg`, function(data) {
                    const svgMatch = $(data).find('svg');

                    if (svgMatch.length) {
                        let svgContent = $('<div>').append(svgMatch.clone()).html();
                        let cleanedSVG = cleanupSVGContent(svgContent);

                        loadSVGToCanvas(cleanedSVG);
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
            showSVGOnCanvas();
        }

        function loadSVGToCanvas(svgString) {
            const handwritingContent = document.getElementById('handwriting_content');
            const contentWidth = handwritingContent.clientWidth;
            const contentHeight = handwritingContent.clientHeight;

            canvas.width = contentWidth;
            canvas.height = contentHeight;

            const cleanedSVG = svgString
                .replace(/<path([^>]*)>/g, (match, group) => {
                    if (!/stroke="/.test(group)) {
                        return `<path${group} stroke="#F0F0F0">`;
                    }
                    return match;
                });

            result = new Image();
            const svgBlob = new Blob([cleanedSVG], {
                type: 'image/svg+xml;charset=utf-8'
            });
            const url = URL.createObjectURL(svgBlob);

            result.onload = function() {
                URL.revokeObjectURL(url);
            };

            result.src = url;
        }

        function showSVGOnCanvas() {
            if (result) {
                ctx.drawImage(result, 0, 0, canvas.width, canvas.height);
            }
        }

        function hideAndShowHandwritingGuide() {
            $('.hadnwriting-guide-eye').on('click', function() {
                $('#handwriting_guide_content').children().toggle();
            });
        }
    </script>
@endsection
