@extends('client.shared.lesson-detail')

@section('lesson-detail-styles')
    <link href="{{ asset('css/pages/lesson-detail/pronunciation.css') }}" rel="stylesheet">
    <style>
        :root {
            --dot-size: 1.25rem;
            --max-block-size: calc(var(--dot-size) * 5);
            --dot-color: #166bc9;
            --dot-color-transition-1: #66a9db;
            --dot-color-transition-2: #b3d9ef;
            --delay: 0ms;
        }

        .audio-waveform-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        h2 {
            font-size: 1.75rem;
            color: #166bc9;
            text-align: center;
        }

        .loader {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: calc(var(--dot-size) / 2);
            block-size: var(--max-block-size);
        }

        .dot {
            inline-size: var(--dot-size);
            block-size: var(--dot-size);
            border-radius: calc(var(--dot-size) / 2);
            background: var(--dot-color);
            animation: y-grow 2s infinite ease-in-out;
            animation-delay: calc(var(--delay) * 1ms);
        }

        @keyframes y-grow {
            25% {
                block-size: var(--max-block-size);
                background-color: var(--dot-color-transition-1);
            }

            50% {
                block-size: var(--dot-size);
                background-color: var(--dot-color-transition-2);
            }
        }

        .custom-tooltip {
            --bs-tooltip-bg: #ffffff;
            --bs-tooltip-color: #333;
            --bs-tooltip-max-width: 200px;
            --bs-tooltip-padding-x: 1rem;
            --bs-tooltip-padding-y: 0.5rem;
            font-size: 1.25rem;
            font-weight: 300px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            z-index: 999999;
        }

        /* Tooltip arrow styles */
        .custom-tooltip .tooltip-arrow::before {
            border-top-color: #ffffff;
        }

        /* Optional animation for tooltip */
        .custom-tooltip.show {
            opacity: 1;
        }

        @keyframes tooltipFadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

@section('lesson-detail-content')
    @php
        $total = 0;

        if (isset($pronunciation) && $pronunciation->pronunciationDetails) {
            $pronunciationDetails = $pronunciation->pronunciationDetails;
            $total = $pronunciationDetails->count();
        }
    @endphp
    <div class="pronunciation-body">
        @if (isset($pronunciationDetails) && $pronunciationDetails->count() > 0)
            <div id="pronunciation_container" class="pronunciation-container">
                <div id="pronunciation_wrapper" class="pronunciation-wrapper">
                    <div class="pronunciation-practice">
                        <div class="instruction" id="instruction">
                            Di chuyển chuột vào từng ký tự để xem đánh giá nhé!
                        </div>
                        <div class="pronunciation-question-and-result">
                            <div class="pronunciation-text-process" id="pronunciation_text_result">
                                <div id="pronunciation_text" class="pronunciation-text">
                                    <span class="pronunciation-text-title" id="pronunciation_text_title">
                                        {{ $pronunciationDetails[0]->text }}
                                    </span>
                                </div>
                                <div id="pronunciation_process" class="pronunciation-process">
                                    <div id="audio_waveform">
                                        <main class="audio-waveform-container">
                                            <h2 id="audio_waveform_title"></h2>
                                            <div class="loader js-loader" data-delay="200">
                                                <div class="dot"></div>
                                                <div class="dot"></div>
                                                <div class="dot"></div>
                                                <div class="dot"></div>
                                                <div class="dot"></div>
                                            </div>
                                        </main>
                                    </div>
                                </div>
                            </div>
                            <div id="student_speech_result" class="student-speech-result">
                                <div class="result-level d-flex flex-column justify-content-center align-tiems-center">
                                    <div id="resultl_level_1" class="pronunciation-result">
                                        <h3 class="text-center">Kết quả đánh giá độ chính xác phát âm</h3>
                                        <div id="resultl_level_1_assessment"
                                            class="d-flex justify-content-center gap-2 ps-2 pe-2">
                                        </div>
                                    </div>
                                    <div id="result_level_2" class="pronunciation-result">
                                        <h3 class="text-center">Kết quả đánh giá ngữ điệu câu</h3>
                                        <div id="resultl_level_2_assessment"
                                            class="d-flex justify-content-center gap-2 ps-2 pe-2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="pronunciation_audio" class="pronunciation-audio">
                            <div class="pronunciation-bot">
                                <button class="btn btn-primary" id="play_sound_btn">
                                    <i class="bi bi-volume-up"></i>
                                </button>
                            </div>
                            <div class="pronunciation-record">
                                <button id="record" class="btn btn-primary" onclick="toggleRecording()">
                                    <i class="bi bi-mic"></i>
                                </button>
                                <button id="reload" class="reload-btn btn btn-primary">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                            <div class="pronunciation-user">
                                <button id="play_record" class="btn btn-primary" onclick="playRecord()" disabled>
                                    <i class="bi bi-person"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pronunciation_arrow" class="pronunciation-arrow">
                    <i id="arrow_left" class="bi bi-arrow-left arrow disabled"></i>
                    <div class="pronunciation-page">
                        <span id="current_page">1</span> / {{ $total }}
                    </div>
                    <i id="arrow_right" class="bi bi-arrow-right arrow"></i>
                </div>
            </div>
        @else
            <div class="alert alert-warning fs-4 mt-3" role="alert">
                <span>Bài học sẽ sớm có thôi, bạn quay lại sau nhé!</span>
            </div>
        @endif
    </div>
@endsection

@section('lesson-detail-scripts')
    <script src="{{ admin_asset('js/recorder.js') }}"></script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('.char-assessment[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        const pronunciationDetail = @json($pronunciationDetails);
        let pronunciationDetailId;
        let totalScore;
        let pronunciationComment = @json(config('constant.pronunciation.comment'));
        let rec;
        let gumStream;
        let total = {{ $total }};
        let currentIndex = 1;
        let mediaRecorder;
        let recordedChunks = [];
        let audioBlob;
        let arrowLeft = $('#arrow_left');
        let arrowRight = $('#arrow_right');
        let isPlayingSound = false;
        let isRecording = false;
        let playSoundBtn = $('#play_sound_btn');
        let sampleAudio;
        let instruction = $('#instruction');
        let pronunciationData = {};
        let currentPage = 1;

        $(document).ready(function() {
            initializeAduioWaveform();
            $('#audio_waveform').css('display', 'none');
            handleArrowLeftEvent();
            handleArrowRightEvent();
            updatePageNumber(1);

            if (/Mobi|Android/i.test(navigator.userAgent)) {
                instruction.text = "Nhấn vào từng ký tự để xem đánh giá";
            }

            $('#reload').on('click', function() {
                pronunciationData[currentPage].result_block = false;
                closeResultBlock();
            });

            playSoundBtn.on('click', function() {
                playSound(sampleAudio);
            });
        })

        function initializeAduioWaveform() {
            const loader = document.querySelector(".loader");
            const delay = +loader.dataset.delay || 200;
            const dots = loader.querySelectorAll(".loader .dot");
            dots.forEach((dot, index) => {
                dot.style = `--delay: ${delay * index}`;
            });
        }

        /**
         * Toggle recording
         */
        async function toggleRecording() {
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
                stopRecording();
            } else {
                try {
                    startRecording();
                } catch (err) {
                    alert('Không thể truy cập microphone');
                }
            }
        }

        /**
         * Start recording
         */
        function startRecording() {
            handleRecording();
            let constraints = {
                audio: true,
                video: false
            }

            navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
                mediaRecorder = new MediaRecorder(stream);
                audioContext = new AudioContext();
                gumStream = stream;
                input = audioContext.createMediaStreamSource(stream);
                rec = new Recorder(input, {
                    numChannels: 1
                })
                rec.record();
                isRecording = true;

                recordedChunks = [];
                mediaRecorder.addEventListener('dataavailable', event => {
                    recordedChunks.push(event.data);
                });

                mediaRecorder.addEventListener('stop', () => {
                    audioBlob = new Blob(recordedChunks, {
                        type: 'audio/wav'
                    });
                });

                mediaRecorder.start();

                setTimeout(() => {
                    if (isRecording) {
                        stopRecording();
                    }
                }, 10000);
            }).catch(function(err) {
                alert(err)
            });
        }

        function stopRecording() {
            rec.stop();
            gumStream.getAudioTracks()[0].stop();
            isRecording = false;
            handleAssessment();
            rec.exportWAV((userBlob) => {
                uploadAudioAndSample(userBlob, sampleAudio);
            });
        }

        /**
         * Play sound
         */
        function playSound(audioSrc) {
            if (!isPlayingSound) {
                isPlayingSound = true;
                let audio = new Audio(audioSrc);

                audio.addEventListener('ended', () => {
                    isPlayingSound = false;
                });

                audio.play();
            }
        }

        /**
         * Play record
         */
        function playRecord() {
            if (audioBlob) {
                let audioURL = URL.createObjectURL(audioBlob);
                playSound(audioURL);
            } else {
                alert("Chưa có đoạn ghi âm nào để phát lại!");
            }
        }

        /**
         * Handle recording
         */
        function handleRecording() {
            arrowLeft.addClass('disabled');
            arrowRight.addClass('disabled');
            $('#audio_waveform').css('display', 'block');
            $('#play_record').prop('disabled', true);
            $('#audio_waveform_title').text('Đang ghi âm');
            $('#record i').removeClass('bi-mic').addClass('bi-pause-circle');
        }

        /**
         * Handle pause
         */
        function handlePausing() {
            handleAssessment()
        }

        function handleAssessment() {
            $('#audio_waveform_title').text('Đang xử lí, xin vui lòng chờ 1 chút !');
            $('#record i').removeClass('bi-pause-circle').addClass('bi-mic');
            $('#record').prop('disabled', true);
        }

        function displayResult(data) {
            handleResult(data);
            pronunciationData[currentPage].result_block = true;
            openResultBlock();

            if (!(currentPage === total)) {
                arrowRight.removeClass('disabled');
            }
            if (!(currentPage === 1)) {
                arrowLeft.removeClass('disabled');
            }
        }

        function handleResult(data) {
            let userResult = data['user_result'];
            let assessmentResult = data['assessment_results'];

            handleWrongWords(userResult, assessmentResult);
        }

        function handleWrongWords(userResult, assessmentResult) {
            const maxLength = Math.max(userResult.length, assessmentResult.length);
            let differences = [];

            for (let i = 0; i < maxLength; i++) {
                if (!userResult[i] || !assessmentResult[i]) {
                    differences.push({
                        index: i,
                        userText: userResult[i] ? userResult[i].text : null,
                        assessmentWord: assessmentResult[i] ? assessmentResult[i].word : null
                    });
                    continue;
                }

                if (userResult[i].text !== assessmentResult[i].word) {
                    differences.push({
                        index: i,
                        userText: userResult[i].text,
                        assessmentWord: assessmentResult[i].word
                    });
                }
            }

            //console.log(differences);
            createTooltipForResultLevel1(userResult, assessmentResult, differences);
            createTooltipForResultLevel2(assessmentResult);
        }

        function createTooltipForResultLevel1(userResult, assessmentResult, differences) {
            $('#resultl_level_1_assessment').empty();
            let titleLength = $('#pronunciation_text_title').text().length;

            $.each(assessmentResult, function(i, assessmentItem) {
                const difference = differences.find(diff => diff.index === i);
                const userText = userResult[i]?.text || '';

                let span = $('<span></span>')
                    .addClass('char-assessment')
                    .text(assessmentItem.word);

                span.attr('data-bs-toggle', 'tooltip')
                    .attr('data-bs-placement', 'top')
                    .attr('data-bs-custom-class', 'custom-tooltip')
                    .attr('data-bs-html', 'true');

                if (i < titleLength) {
                    if (userText.trim() === '') {
                        span.addClass('incorrect')
                            .attr('data-bs-title',
                                `Hãy phát âm to và rõ hơn để hệ thống nhận diện và đánh giá bạn nhé!`);
                    } else if (difference) {
                        span.addClass('incorrect')
                            .attr('data-bs-title',
                                `Bạn đã phát âm sai thành: <span class='text-danger'>${userText}</span>`);
                    } else {
                        span.addClass('correct')
                            .attr('data-bs-title',
                                `Tuyệt vời! Bạn phát âm rất chuẩn!`);
                    }
                } else {
                    span.addClass('incorrect')
                        .attr('data-bs-title',
                            `Dường như bạn đã phát âm thừa từ này. Hãy thử lại và phát âm đúng theo mẫu nhé!`);
                }

                $('#resultl_level_1_assessment').append(span);
            });

            $('[data-bs-toggle="tooltip"]').tooltip();
        }

        function createTooltipForResultLevel2(assessmentResult) {
            $('#resultl_level_2_assessment').empty();
            let titleLength = $('#pronunciation_text_title').text().length;

            $.each(assessmentResult, function(i, assessmentItem) {
                let tooltipText = '';

                let span = $('<span></span>')
                    .addClass('char-assessment intonation-error')
                    .text(assessmentItem.word);

                const thresholdSpeechTime = 0.2;
                const thresholdPitch = 5;
                const speechTimeDifference = assessmentItem.speech_time_difference;
                const pitchValue = assessmentItem.pitch_value;
                const pearsonrValue = assessmentItem.pearsonr_value;

                if (i < titleLength) {
                    if (pearsonrValue > 0.5) {
                        if (Math.abs(speechTimeDifference) <= thresholdSpeechTime) {
                            span.addClass('correct');
                            span.removeClass('intonation-error');
                            tooltipText = "Bạn làm rất tốt! Cao độ và độ dài âm đều khớp, cứ tiếp tục phát huy nhé!";
                        } else if (speechTimeDifference < -1 * thresholdSpeechTime) {
                            tooltipText = "Cao độ của bạn chuẩn rồi! Thử kéo dài âm thêm chút nữa cho hoàn hảo nhé!";
                        } else if (speechTimeDifference > thresholdSpeechTime) {
                            tooltipText =
                                "Bạn phát âm rất đúng cao độ! Thử nói ngắn lại một chút để độ dài âm khớp hơn nhé!";
                        }
                    } else if (pearsonrValue > 0 && pearsonrValue <= 0.5) {
                        if (Math.abs(speechTimeDifference) <= thresholdSpeechTime) {
                            span.addClass('correct');
                            span.removeClass('intonation-error');
                            tooltipText =
                                "Cao độ của bạn khá tốt! Độ dài âm cũng tương đối khớp, tiếp tục phát huy nhé!";
                        } else if (speechTimeDifference < -1 * thresholdSpeechTime) {
                            tooltipText = "Cao độ của bạn khá tốt! Thử kéo dài âm thêm chút nữa cho hoàn hảo nhé!";
                        } else if (speechTimeDifference > thresholdSpeechTime) {
                            tooltipText =
                                "Cao độ của bạn khá tốt! Thử nói ngắn lại một chút để độ dài âm khớp hơn nhé!";
                        }
                    } else if (pearsonrValue === 0) {
                        if (Math.abs(speechTimeDifference) <= thresholdSpeechTime) {
                            tooltipText = "Bạn thử điều chỉnh ngữ điệu để cải thiện nhé!";
                        } else if (speechTimeDifference < -1 * thresholdSpeechTime) {
                            tooltipText =
                                "Bạn cần điều chỉnh ngữ điệu và thử kéo dài âm thêm một chút nữa cho hoàn hảo nhé!";
                        } else if (speechTimeDifference > thresholdSpeechTime) {
                            tooltipText =
                                "Bạn cần điều chỉnh ngữ điệu và thử nói ngắn hơn một chút nữa cho chính xác nhé!";
                        }
                    } else {
                        let feedbackText = evaluatePitch(pitchValue, thresholdPitch);
                        if (Math.abs(speechTimeDifference) <= thresholdSpeechTime) {
                            tooltipText = feedbackText +
                                "nhưng độ dài âm rất ổn! Hãy tập trung luyện thêm về cao độ nhé!";
                        } else if (speechTimeDifference < -1 * thresholdSpeechTime) {
                            tooltipText = feedbackText +
                                "Độ dài âm hơi ngắn! Thử kéo dài âm thêm chút và điều chỉnh cao độ nữa cho hoàn hảo nhé!";
                        } else if (speechTimeDifference > thresholdSpeechTime) {
                            tooltipText = feedbackText +
                                "Độ dài âm hơi dài! Thử nói ngắn lại một chút và điều chỉnh cao độ nữa cho khớp hơn nhé!";
                        }
                    }
                } else {
                    span.addClass('incorrect');
                    span.removeClass('intonation-error');
                    tooltipText = `Dường như bạn đã phát âm thừa từ này. Hãy thử lại và phát âm đúng theo mẫu nhé!`;
                }

                if (tooltipText) {
                    span.attr('data-bs-toggle', 'tooltip')
                        .attr('data-bs-placement', 'top')
                        .attr('data-bs-custom-class', 'custom-tooltip')
                        .attr('data-bs-title', tooltipText);
                }

                $('#resultl_level_2_assessment').append(span);
            });

            $('[data-bs-toggle="tooltip"]').tooltip();
        }

        function evaluatePitch(pitchValue, thresholdPitch) {
            let feedbackText = "";

            if (Math.abs(pitchValue) > thresholdPitch) {
                if (pitchValue > thresholdPitch) {
                    feedbackText = "Cao độ của bạn hơi cao, ";
                } else {
                    feedbackText = "Cao độ của bạn hơi thấp, ";
                }
            }

            return feedbackText;
        }

        function openResultBlock() {
            instruction.css('display', 'block');
            $('#student_speech_result').css('display', 'block');
            $('#pronunciation_text_result').css('display', 'none');
            $('#record').css('display', 'none');
            $('#reload').css('display', 'block');
            $('#record').prop('disabled', false);
            $('#audio_waveform').css('display', 'none');
            $('#play_record').prop('disabled', false);
        }

        function closeResultBlock() {
            instruction.css('display', 'none');
            $('#student_speech_result').css('display', 'none');
            $('#pronunciation_text_result').css('display', 'flex');
            $('#record').css('display', 'block');
            $('#reload').css('display', 'none');
            audioBlob = null;
            $('#play_record').prop('disabled', true);
        }

        /**
         * Update page number
         */
        function updatePageNumber(number) {
            $('#current_page').text(number)
            currentPage = number;
            restorePronunciationData(number);
            updatePronunciationAssessmentLesson(number - 1)
            arrowLeft.toggleClass('disabled', number === 1);
            arrowRight.toggleClass('disabled', number === total);
        }

        function updatePronunciationAssessmentLesson(index) {
            pronunciationDetailId = pronunciationDetail[index].id;
            $('#pronunciation_text_title').text(pronunciationDetail[index].text);
            audioPath = pronunciationDetail[index].audio
            sampleAudio = `{{ asset('${audioPath}') }}`;
        }

        /**
         * Handle Arrow Event
         */
        function handleArrowLeftEvent() {
            arrowLeft.on('click', function() {
                if (currentIndex > 1) {
                    storePronunciationData(currentIndex);
                    currentIndex--;
                    checkPronunciationData(currentIndex);
                    updatePageNumber(currentIndex);
                }
            });
        }

        /**
         * Handle Arrow Event
         */
        function handleArrowRightEvent() {
            arrowRight.on('click', function() {
                if (currentIndex < total) {
                    storePronunciationData(currentIndex);
                    currentIndex++;
                    checkPronunciationData(currentIndex);
                    updatePageNumber(currentIndex);
                }
            });
        }

        function checkPronunciationData(page) {
            if (pronunciationData[page] && pronunciationData[page].result_block) {
                openResultBlock();
            } else {
                closeResultBlock();
            }
        }

        function storePronunciationData(page) {
            pronunciationData[page].data = $('.pronunciation-question-and-result').html();
        }

        function restorePronunciationData(page) {
            if (!pronunciationData[page]) {
                pronunciationData[page] = {};
            }

            if (pronunciationData[page].data) {
                audioBlob = pronunciationData[page].user_audio;
                $('.pronunciation-question-and-result').html('');
                $('.pronunciation-question-and-result').html(pronunciationData[page].data);
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        }

        const processBlob = (userBlob, sampleBlob, sampleName) => {
            let formData = new FormData();
            let filename = new Date().toISOString() + ".wav";
            formData.append("user_file", userBlob, filename);
            formData.append("sample_file", sampleBlob, sampleName);
            formData.append("pronunciation_detail_id", pronunciationDetailId)

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: "{{ route('lms.pronunciation_assessment.assess') }}",
                type: 'post',
                dataType: 'json',
                contentType: false,
                processData: false,
                data: formData,
                success: function(response) {
                    pronunciationData[currentPage].user_audio = audioBlob;
                    displayResult(response);
                },
                error: function(xhr, error) {
                    $('#audio_waveform').css('display', 'none');
                    Swal.fire("Lỗi",
                        "Hệ thống không thể nhận diện rõ ràng giọng nói của bạn. Vui lòng nói rõ hơn hoặc kiểm tra thiết bị ghi âm của bạn.",
                        "error");
                    $('#record').prop('disabled', false);
                },
                complete: function() {
                    if (currentPage !== total) {
                        arrowRight.removeClass('disabled');
                    }
                    if (currentPage !== 1) {
                        arrowLeft.removeClass('disabled');
                    }
                }
            });
        }

        function uploadAudioAndSample(userBlob, sampleAudioPath) {
            fetch(sampleAudioPath)
                .then(response => response.blob())
                .then(sampleBlob => {
                    let sampleName = sampleAudioPath.split('/').pop();
                    processBlob(userBlob, sampleBlob, sampleName);
                })
                .catch(error => {
                    console.error('Error checking audio files:', error);
                });
        }
    </script>
@endsection
