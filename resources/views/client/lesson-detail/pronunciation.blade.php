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
        $pronunciationDetails = $pronunciation->pronunciationDetails;
        $total = $pronunciationDetails->count();
    @endphp
    <div class="pronunciation-body">
        <div id="pronunciation_container" class="pronunciation-container">
            <div id="pronunciation_wrapper" class="pronunciation-wrapper">
                <div class="pronunciation-practice">
                    <div class="instruction" id="instruction">
                        Di chuột vào từng ký tự để xem đánh giá
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
                                    <h3 class="text-center">Kết quả đánh giá phát âm từng từ</h3>
                                    <div class="d-flex justify-content-center gap-2 ps-2 pe-2">
                                        <span class="char-assessment correct" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Correct: 東">東</span>
                                        <span class="char-assessment correct" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Correct: 京">京</span>
                                        <span class="char-assessment correct" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Correct: は">は</span>
                                        <span class="char-assessment incorrect" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                            data-bs-html="true"
                                            data-bs-title="は<span class='text-danger'>れ</span> (hare) - Nắng<br><small class='text-danger'>Phát âm sai: はれ → はり</small>">晴</span>
                                        <span class="char-assessment correct" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Correct: れ">れ</span>
                                        <span class="char-assessment correct" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Correct: で">で</span>

                                        <span class="char-assessment correct" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Correct: た">た</span>
                                    </div>
                                </div>
                                <div id="result_level_2" class="pronunciation-result">
                                    <h3 class="text-center">Kết quả đánh giá ngữ điệu câu</h3>
                                    <div class="d-flex justify-content-center gap-2 ps-2 pe-2">
                                        <span class="char-assessment correct" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Correct: 東">東</span>
                                        <span class="char-assessment intonation-error" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Correct: 京">京</span>
                                        <span class="char-assessment correct" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Correct: は">は</span>
                                        <span class="char-assessment intonation-error" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Incorrect: 晴">晴</span>
                                        <span class="char-assessment correct" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Correct: れ">れ</span>
                                        <span class="char-assessment correct" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Correct: で">で</span>
                                        <span class="char-assessment correct" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Correct: し">し</span>
                                        <span class="char-assessment correct" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Correct: た">た</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="pronunciation_audio" class="pronunciation-audio">
                        <div class="pronunciation-bot">
                            <button class="btn btn-primary"
                                onclick="playSound('{{ asset($pronunciationDetails[0]->audio) }}')">
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
    <script src="{{ admin_asset('js/progressbar.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="{{ admin_asset('js/recorder.js') }}"></script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('.char-assessment[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        const pronunciationDetail = @json($pronunciationDetails);
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
        let instruction = $('#instruction');
        let sentence = "";
        let correctIndices = [];
        const pronunciationAssessmentUrl = "{{ env('PRONUNCIATION_ASSESSMENT_URL') }}"

        const testSrc = "{{ asset($pronunciationDetails[0]->audio) }}";

        $(document).ready(function() {
            audioWaveform.css('display', 'none');
            sentence = pronunciationDetail[0]['text'];
            console.log(sentence)
            handleArrowLeftEvent();
            handleArrowRightEvent();

            if (/Mobi|Android/i.test(navigator.userAgent)) {
                instruction.text = "Nhấn vào từng ký tự để xem đánh giá";
            }

            reloadBtn.on('click', function() {
                closeResultBlock();
            });
        })

        /**
         * Toggle recording
         */
        async function toggleRecording() {
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
                console.log("stop record: ", mediaRecorder)
                handlePausing();
            } else {
                try {
                    // const stream = await navigator.mediaDevices.getUserMedia({
                    //     audio: true
                    // });
                    startRecording();
                } catch (err) {
                    alert('Không thể truy cập microphone');
                    console.error('Không thể truy cập microphone:', err);
                }
            }

            // if (!isRecording) {
            //     startRecording();
            // } else {
            //     stopRecording();
            // }
        }

        /**
         * Start recording
         */
        function startRecording() {
            // mediaRecorder = new MediaRecorder(stream);
            recordedChunks = [];
            console.log("start record")
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

                mediaRecorder.addEventListener('dataavailable', event => {
                    recordedChunks.push(event.data);
                });

                mediaRecorder.addEventListener('stop', () => {
                    audioBlob = new Blob(recordedChunks, {
                        type: 'audio/wav'
                    });
                    //processAudio(audioBlob)
                    //uploadAudioFromSrc(testSrc);
                    const audioUrl = URL.createObjectURL(audioBlob);

                });

                mediaRecorder.start();
            }).catch(function(err) {
                alert(err)
            });

            // mediaRecorder.addEventListener('dataavailable', event => {
            //     recordedChunks.push(event.data);
            // });

            // mediaRecorder.start();
        }

        function stopRecording() {
            rec.stop();
            gumStream.getAudioTracks()[0].stop();
            isRecording = false;
            handleAssessment();
            // rec.exportWAV(processBlob);
            uploadAudioFromSrc(testSrc);
            //processAudio(audioBlob);
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

            // audioWaveform.css('display', 'none');
            // playRecordBtn.prop('disabled', false);
            // recordBtn.prop('disabled', false);
        }

        function handleAssessment() {
            audioWaveformTitle.text('Đang xử lí, xin vui lòng chờ 1 chút !');
            $('#record i').removeClass('bi-pause-circle').addClass('bi-mic');
            recordBtn.prop('disabled', true);


            displayResult();
        }

        function displayResult() {
            openResultBlock();
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
            arrowLeft.toggleClass('disabled', number === 1);
            arrowRight.toggleClass('disabled', number === total);
        }

        /**
         * Handle Arrow Event
         */
        function handleArrowLeftEvent() {
            arrowLeft.on('click', function() {
                if (currentIndex > 1) {
                    currentIndex--;
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
                    updatePageNumber(currentIndex);
                }
            });
        }

        function displaySentence(sentence, correctIndices) {
            studentSpeechResult.css('display', 'block');
            pronunciationTextTitle.css('display', 'none');
            let spanClass;
            let incorrectData = '';
            let highlightedSentence = $.map(sentence.split(''), function(char, index) {
                if (char === ' ') return ' ';
                if (correctIndices[index] === -1) {
                    spanClass = 'missing-word';
                } else if (correctIndices[index] === 0) {
                    spanClass = 'correct';
                } else if (correctIndices[index] === 1) {
                    spanClass = 'incorrect';
                    //  incorrectData = userWords.word[index];
                }

                return '<span class="text-result ' + spanClass + '" data-incorrect="' + incorrectData + '">' +
                    char + '</span>';
            }).join('');

            studentSpeechResult.html(highlightedSentence);
            handleTooltipIncorrect();
        }

        const processBlob = (blob) => {
            let formData = new FormData();
            let filename = new Date().toISOString() + ".wav";
            formData.append("audio_file", blob, filename);
            formData.append("pronunciation_detail_id", 1)
            formData.forEach((value, key) => {
                console.log(key + ':', value);
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: "{{ route('pronunciation.assess') }}",
                type: 'post',
                dataType: 'json',
                contentType: false,
                processData: false,
                data: formData,
                success: function(response) {
                    console.log(response);
                    calcResult(response);
                },
                error: function(response) {
                    console.log(response)
                },
                complete: function() {
                    const audioUrl = URL.createObjectURL(blob);
                    audioBlob = blob;
                    displayResult();
                }
            });
        }

        function uploadAudioFromSrc(audioSrc) {
            fetch(audioSrc)
                .then(response => response.blob())
                .then(blob => {
                    let formData = new FormData();
                    let filename = new Date().toISOString() + ".wav";
                    formData.append("audio_file", blob, filename);
                    formData.append("pronunciation_detail_id", 1)
                    console.log(22);
                    formData.forEach((value, key) => {
                        console.log(key + ':', value);
                    });
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        url: "{{ route('pronunciation.assess') }}",
                        type: 'post',
                        dataType: "json",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            console.log(response);
                            audioWaveform.css('display', 'none');
                            playRecordBtn.prop('disabled', false);
                            recordBtn.prop('disabled', false);
                            calcResult(response);
                        },
                        error: function(response) {
                            console.log("Error uploading file");
                        }
                    });
                })
                .catch(error => console.error('Error fetching audio:', error));
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
