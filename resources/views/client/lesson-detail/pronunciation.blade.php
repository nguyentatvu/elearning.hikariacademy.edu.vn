@extends('client.shared.lesson-detail')

@section('lesson-detail-styles')
    <link href="{{ asset('css/pages/lesson-detail/pronunciation.css') }}" rel="stylesheet">
    <style>
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
                                @component('client.components.audio-waveform')
                                @endcomponent
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
        let resultDetailText = $('#result_detail_text');
        let totalScore;
        let pronunciationComment = @json(config('constant.pronunciation.comment'));
        let studentSpeechResult = $('#student_speech_result');
        let pronunciationTextResult = $('#pronunciation_text_result');
        let pronunciationTextTitle = $('#pronunciation_text_title');
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
        let audioWaveform = $('#audio_waveform');
        let isRecording = false;
        let audioWaveformTitle = $('#audio_waveform_title');
        let recordBtn = $('#record');
        let reloadBtn = $('#reload');
        let playRecordBtn = $('#play_record');
        let playSoundBtn = $('#play_sound_btn');
        let sampleAudio;
        let instruction = $('#instruction');


        $(document).ready(function() {
            audioWaveform.css('display', 'none');
            handleArrowLeftEvent();
            handleArrowRightEvent();
            updatePageNumber(1);

            if (/Mobi|Android/i.test(navigator.userAgent)) {
                instruction.text = "Nhấn vào từng ký tự để xem đánh giá";
            }

            reloadBtn.on('click', function() {
                closeResultBlock();
            });

            playSoundBtn.on('click', function() {
                playSound(sampleAudio);
            });
        })

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
            audioWaveform.css('display', 'block');
            playRecordBtn.prop('disabled', true);
            audioWaveformTitle.text('Đang ghi âm');
            $('#record i').removeClass('bi-mic').addClass('bi-pause-circle');
        }

        /**
         * Handle pause
         */
        function handlePausing() {
            handleAssessment()
        }

        function handleAssessment() {
            audioWaveformTitle.text('Đang xử lí, xin vui lòng chờ 1 chút !');
            $('#record i').removeClass('bi-pause-circle').addClass('bi-mic');
            recordBtn.prop('disabled', true);

            //displayResult([]);
        }

        function displayResult(data) {
            handleResult(data);
            openResultBlock();
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

            $.each(assessmentResult, function(i, assessmentItem) {
                const difference = differences.find(diff => diff.index === i);
                const userText = userResult[i]?.text || '';

                let span = $('<span></span>')
                    .addClass('char-assessment')
                    .text(assessmentItem.word);

                if (difference) {
                    span.addClass('incorrect')
                        .attr('data-bs-toggle', 'tooltip')
                        .attr('data-bs-placement', 'top')
                        .attr('data-bs-custom-class', 'custom-tooltip')
                        .attr('data-bs-html', 'true')
                        .attr('data-bs-title',
                            `Bạn đã phát âm sai thành: <span class='text-danger'>${userText}</span>`);
                } else {
                    span.addClass('correct')
                        .attr('data-bs-toggle', 'tooltip')
                        .attr('data-bs-placement', 'top')
                        .attr('data-bs-custom-class', 'custom-tooltip')
                        .attr('data-bs-html', 'true')
                        .attr('data-bs-title', `Tuyệt vời! Bạn phát âm rất chuẩn!`);
                }

                $('#resultl_level_1_assessment').append(span);
            });

            $('[data-bs-toggle="tooltip"]').tooltip();
        }

        function createTooltipForResultLevel2(assessmentResult) {
            $('#resultl_level_2_assessment').empty();

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

                // if (pearsonrValue > 0) {
                //     if (Math.abs(speechTimeDifference) <= thresholdSpeechTime) {
                //         span.addClass('correct');
                //         span.removeClass('intonation-error');
                //         tooltipText = "Bạn làm rất tốt! Cao độ và độ dài âm đều khớp, cứ tiếp tục phát huy nhé!";
                //     } else if (speechTimeDifference < -1 * thresholdSpeechTime) {
                //         tooltipText = "Cao độ của bạn chuẩn rồi! Thử kéo dài âm thêm chút nữa cho hoàn hảo nhé!";
                //     } else if (speechTimeDifference > thresholdSpeechTime) {
                //         tooltipText =
                //             "Bạn phát âm rất đúng cao độ! Thử nói ngắn lại một chút để độ dài âm khớp hơn nhé!";
                //     }
                // } else if (pearsonrValue === 0) {
                //     let feedbackText = evaluatePitch(pitchValue, thresholdPitch);
                //     if (Math.abs(speechTimeDifference) <= thresholdSpeechTime) {
                //         tooltipText = feedbackText +
                //             "nhưng độ dài chuẩn rồi! Hãy tập trung điều chỉnh cao độ nhé!";
                //     } else if (speechTimeDifference < -1 * thresholdSpeechTime) {
                //         tooltipText = feedbackText +
                //             "và độ dài âm hơi ngắn. Hãy kéo dài âm thêm chút và điều chỉnh cao độ nhé!";
                //     } else if (speechTimeDifference > thresholdSpeechTime) {
                //         tooltipText = feedbackText + "và độ dài âm hơi dài. Bạn cần điều chỉnh lại cao độ và thử nói ngắn hơn một chút để khớp hơn nhé!";
                //     }
                // } else {
                //     let feedbackText = evaluatePitch(pitchValue, thresholdPitch);
                //     if (Math.abs(speechTimeDifference) <= thresholdSpeechTime) {
                //         tooltipText =
                //             "Cao độ hơi lệch, nhưng độ dài âm rất ổn! Hãy tập trung luyện thêm về cao độ nhé!";
                //     } else if (speechTimeDifference < -1 * thresholdSpeechTime) {
                //         tooltipText =
                //             "Cao độ chưa khớp lắm. Bạn cũng có thể kéo dài âm thêm chút để độ dài đạt chuẩn nhé!";
                //     } else if (speechTimeDifference > thresholdSpeechTime) {
                //         tooltipText =
                //             "Bạn cần điều chỉnh lại cao độ một chút. Độ dài âm thì gần chuẩn rồi, chỉ cần luyện thêm cao độ thôi!";
                //     }
                // }

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
            studentSpeechResult.css('display', 'block');
            pronunciationTextResult.css('display', 'none');
            recordBtn.css('display', 'none');
            reloadBtn.css('display', 'block');
            recordBtn.prop('disabled', false);
            audioWaveform.css('display', 'none');
            playRecordBtn.prop('disabled', false);
        }

        function closeResultBlock() {
            instruction.css('display', 'none');
            studentSpeechResult.css('display', 'none');
            pronunciationTextResult.css('display', 'flex');
            recordBtn.css('display', 'block');
            reloadBtn.css('display', 'none');
            audioBlob = null;
            playRecordBtn.prop('disabled', true);
        }

        /**
         * Update page number
         */
        function updatePageNumber(number) {
            $('#current_page').text(number)
            updatePronunciationAssessmentLesson(number - 1)
            arrowLeft.toggleClass('disabled', number === 1);
            arrowRight.toggleClass('disabled', number === total);
        }

        function updatePronunciationAssessmentLesson(index) {
            pronunciationDetailId = pronunciationDetail[index].id;
            pronunciationTextTitle.text(pronunciationDetail[index].text);
            audioPath = pronunciationDetail[index].audio
            sampleAudio = `{{ asset('${audioPath}') }}`;
        }

        /**
         * Handle Arrow Event
         */
        function handleArrowLeftEvent() {
            arrowLeft.on('click', function() {
                if (currentIndex > 1) {
                    currentIndex--;
                    closeResultBlock();
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
                    currentIndex++;
                    closeResultBlock();
                    updatePageNumber(currentIndex);
                }
            });
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
                    displayResult(response);
                },
                error: function(xhr, error) {
                    audioWaveform.css('display', 'none');
                    Swal.fire("Lỗi",
                        "Hệ thống không thể nhận diện rõ ràng giọng nói của bạn. Vui lòng nói rõ hơn hoặc kiểm tra thiết bị ghi âm của bạn.",
                        "error");
                    recordBtn.prop('disabled', false);
                },
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

        function handleTooltipIncorrect() {
            $('.text-result.incorrect').each(function() {
                let incorrectWord = $(this).data('incorrect');;
                let correctWord = $(this).text();

                $(this).attr('data-bs-toggle', 'tooltip')
                    .attr('data-bs-placement', 'bottom')
                    .attr('title', `Bạn đã phát âm thành ${incorrectWord}, thử lại ${correctWord} xem sao!`);
            });

            $('.text-result.missing-word').each(function() {
                $(this).attr('data-bs-toggle', 'tooltip')
                    .attr('data-bs-placement', 'bottom')
                    .attr('title', 'Có vẻ như bạn bỏ sót một vài từ, hãy thử lại và đảm bảo phát âm đủ nhé!');
            });

            $('[data-bs-toggle="tooltip"]').tooltip({
                delay: {
                    show: 500,
                    hide: 100
                },
                trigger: 'hover focus',
                animation: true
            });
        }
    </script>
@endsection
