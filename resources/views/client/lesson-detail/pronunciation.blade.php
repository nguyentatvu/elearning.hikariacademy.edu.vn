@extends('client.shared.lesson-detail')

@section('lesson-detail-styles')
    <link href="{{ asset('css/pages/lesson-detail/pronunciation.css') }}" rel="stylesheet">
@endsection

@section('lesson-detail-content')
    @php
        $pronunciationDetails = $pronunciation->pronunciationDetails;
        $total = $pronunciationDetails->count();
    @endphp
    <div class="pronunciation-body">
        <div id="pronunciation_container" class="pronunciation-container">
            <div class="row">
                <div class="col-xl-10 col-lg-12 col-xs-12 d-flex justify-content-center mx-auto">
                    <div id="pronunciation_wrapper" class="pronunciation-wrapper">
                        <div id="pronunciation_text" class="pronunciation-text">
                            <span class="pronunciation-text-title" id="pronunciation_text_title">
                                {{ $pronunciationDetails[0]->text }}
                            </span>
                            <div id="student_speech_result" class="student-speech-result">
                            </div>
                        </div>
                        <div id="pronunciation_process" class="pronunciation-process">
                            @component('client.components.audio-waveform')
                            @endcomponent
                            <div id="pronunciation_result" class="pronunciation-result">
                                <div id="score" class="score">
                                </div>
                                <span id="review" class="review">
                                </span>
                            </div>
                            <div id="result_detail" class="result-detail">
                                <span id="result_detail_text" class="result-detail-text">
                                    Xem đánh giá chi tiết
                                </span>
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
                            </div>
                            <div class="pronunciation-user">
                                <button id="play_record" class="btn btn-primary" onclick="playRecord()" disabled>
                                    <i class="bi bi-person"></i>
                                </button>
                            </div>
                        </div>
                        {{-- <div class="right-container">
                            <span class="pronunciation-result-title">
                                Điểm số và nhận xét
                            </span>
                            <div id="pronunciation_result" class="pronunciation-result">
                                <div id="student_speech_result" class="student-speech-result">
                                </div>
                                <div class="score-and-review">
                                    <div id="score" class="score">
                                    </div>
                                    <span id="review" class="review">
                                    </span>
                                </div>
                                <div class="intonation-chart">
                                    <canvas id="intonationChart"></canvas>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>

            {{-- <div id="pronunciation_arrow" class="pronunciation-arrow">
                <i id="arrow_left" class="bi bi-arrow-left arrow disabled"></i>
                <div class="pronunciation-page">
                    <span id="current_page">1</span> / {{ $total }}
                </div>
                <i id="arrow_right" class="bi bi-arrow-right arrow"></i>
            </div> --}}
        </div>
    </div>
@endsection

@section('lesson-detail-scripts')
    <script src="{{ admin_asset('js/progressbar.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="{{ admin_asset('js/recorder.js') }}"></script>
    <script>
        const pronunciationDetail = @json($pronunciationDetails);
        let resultDetailText = $('#result_detail_text');
        let totalScore;
        let pronunciationComment = @json(config('constant.pronunciation.comment'));
        let sentenceElement = $('#student_speech_result');
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
        let playRecordBtn = $('#play_record');
        let sentence = "";
        let correctIndices = [];
        const pronunciationAssessmentUrl = "{{ env('PRONUNCIATION_ASSESSMENT_URL') }}"

        let resultScoreLevel1 = 0.0
        let resultScoreLevel2 = 0.0
        let resultScoreLevel3 = 0.0
        let userWords = []
        let sampleWords = []
        let userDirections = []
        let sampleDirections = []
        let userAverages = []
        let sampleAverages = []
        const testSrc = "{{ asset($pronunciationDetails[0]->audio) }}";

        $(document).ready(function() {
            audioWaveform.css('display', 'none');
            sentence = pronunciationDetail[0]['text'];
            console.log(sentence)
            //handleArrowLeftEvent();
            //handleArrowRightEvent();
        })

        /**
         * Toggle recording
         */
        async function toggleRecording() {
            // if (mediaRecorder && mediaRecorder.state === 'recording') {
            //     mediaRecorder.stop();
            //     handlePausing();
            // } else {
            //     try {
            //         // const stream = await navigator.mediaDevices.getUserMedia({
            //         //     audio: true
            //         // });
            //         startRecording();
            //         audioWaveformTitle.text('Đang ghi âm');
            //         handleRecording();
            //     } catch (err) {
            //         alert('Không thể truy cập microphone');
            //         console.error('Không thể truy cập microphone:', err);
            //     }
            // }

            if (!isRecording) {
                startRecording();
            } else {
                stopRecording();
            }
        }

        /**
         * Start recording
         */
        function startRecording() {
            // recordedChunks = [];
            // mediaRecorder = new MediaRecorder(stream);
            handleRecording();
            let constraints = {
                audio: true,
                video: false
            }
            navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
                audioContext = new AudioContext();
                gumStream = stream;
                input = audioContext.createMediaStreamSource(stream);
                rec = new Recorder(input, {
                    numChannels: 1
                })
                rec.record();
                isRecording = true;
            }).catch(function(err) {
                alert(err)
            });

            // mediaRecorder.addEventListener('dataavailable', event => {
            //     recordedChunks.push(event.data);
            // });

            // mediaRecorder.addEventListener('stop', () => {
            //     audioBlob = new Blob(recordedChunks, {
            //         type: 'audio/wav'
            //     });
            //     processAudio(audioBlob)
            //     //uploadAudioFromSrc(testSrc);
            //     const audioUrl = URL.createObjectURL(audioBlob);
            //     
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

        function createProgressBar(color, score) {
            let bar = new ProgressBar.Circle('#score', {
                color: color,
                trailColor: '#eee',
                strokeWidth: 10,
                trailWidth: 10,
                duration: 1400,
                text: {
                    autoStyleContainer: false
                },
                step: function(state, circle) {
                    var value = Math.round(circle.value() * 100);
                    if (value === 0) {
                        circle.setText('');
                    } else {
                        circle.setText(value);
                    }
                }
            });

            bar.animate(score / 100);
        }

        function displaySentence(sentence, correctIndices) {
            sentenceElement.css('display', 'block');
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

                return '<span class="text-result ' + spanClass + '" data-incorrect="' + incorrectData + '">' + char + '</span>';
            }).join('');

            sentenceElement.html(highlightedSentence);
            handleTooltipIncorrect();
        }

        function drawIntonationChart() {
            const intonationChart = $('#intonationChart');
            const ctx = intonationChart[0].getContext('2d');
            intonationChart.html('');
            const data = {
                labels: sampleWords,
                datasets: [{
                        label: 'Giáo viên',
                        data: sampleAverages,
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    },
                    {
                        label: 'Bạn',
                        data: userAverages,
                        fill: false,
                        borderColor: 'rgb(40, 100, 10)',
                        tension: 0.1
                    }
                ]
            };

            new Chart(ctx, {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            title: {
                                display: true,
                                text: 'Hz'
                            }
                        },
                    }
                }
            });
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
                    audioWaveform.css('display', 'none');
                    playRecordBtn.prop('disabled', false);
                    recordBtn.prop('disabled', false);
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

        const fillWordsAndAverages = (intonations, l) => {
            let words = Array(l).fill('')
            let averages = Array(l).fill(0)
            for (let i = 0; i < intonations.length; i++) {
                words[i] = intonations[i].word;
                averages[i] = intonations[i].average;
            }
            return {
                words,
                averages
            }
        }

        const calcDirections = (sampleWordsAndAverages, userWordsAndAverages, l) => {
            let _sampleDirections = Array(l).fill('→')

            for (let i = 1; i < l; i++) {
                if (sampleWordsAndAverages.averages[i] > sampleWordsAndAverages.averages[i - 1]) {
                    _sampleDirections[i] = '↗'
                } else if (sampleWordsAndAverages.averages[i] == sampleWordsAndAverages.averages[i - 1]) {
                    _sampleDirections[i] = '→'
                } else {
                    _sampleDirections[i] = '↘'
                }
            }
            sampleDirections = _sampleDirections
            let _userDirections = Array(l).fill('→')
            for (let i = 1; i < l; i++) {
                if (userWordsAndAverages.averages[i] > userWordsAndAverages.averages[i - 1]) {
                    _userDirections[i] = '↗'
                } else if (userWordsAndAverages.averages[i] == userWordsAndAverages.averages[i - 1]) {
                    _userDirections[i] = '→'
                } else {
                    _userDirections[i] = '↘'
                }
            }
            userDirections = _userDirections
        }

        function PearsonCorrelationCoefficient(actual, predict) {
            let a_mean = actual.reduce((a, b) => a + b) / actual.length;
            let a_diff = actual.map(x => x - a_mean)
            let p_mean = predict.reduce((a, b) => a + b) / predict.length;
            let p_diff = predict.map(x => x - p_mean)
            let numerator = 0
            let a_pow = 0
            let p_pow = 0
            for (let i = 0; i < actual.length; i++) {
                numerator += a_diff[i] * p_diff[i]
                a_pow += Math.pow(a_diff[i], 2)
                p_pow += Math.pow(p_diff[i], 2)
            }
            let denominator = Math.sqrt(a_pow) * Math.sqrt(p_pow)
            return (numerator / denominator) * 100.0
        }

        function setupChartLevel3() {
            //drawIntonationChart();
        }

        function calcResult(res) {
            const sampleIntonations = res["sample_intonations"]
            const userIntonations = res["user_intonations"]
            const length = Math.max(sampleIntonations.length, userIntonations.length)
            const sampleWordsAndAverages = fillWordsAndAverages(sampleIntonations, length)
            const userWordsAndAverages = fillWordsAndAverages(userIntonations, length)
            sampleWords = sampleWordsAndAverages.words
            userWords = userWordsAndAverages.words
            sampleAverages = sampleWordsAndAverages.averages
            userAverages = userWordsAndAverages.averages
            // level 1 score
            let scoreLevel1 = 0
            for (let i = 0; i < length; i++) {
                if (sampleWordsAndAverages.words[i] == userWordsAndAverages.words[i]) {
                    scoreLevel1 += 1;
                    correctIndices.push(0);
                } else if (userWordsAndAverages.words[i] === undefined) {
                    correctIndices.push(-1);
                } else if (sampleWordsAndAverages.words[i] !== userWordsAndAverages.words[i]) {
                    correctIndices.push(1);
                }
            }
            displaySentence(sentence, correctIndices);
            resultScoreLevel1 = (scoreLevel1 / length * 100.0)
            // level 2 score
            calcDirections(sampleWordsAndAverages, userWordsAndAverages, length)
            let scoreLevel2 = 0
            for (let i = 0; i < length; i++) {
                if (userDirections[i] == sampleDirections[i]) {
                    scoreLevel2 += 1
                }
            }
            console.log("u: ", userDirections);
            console.log("t: ", sampleDirections);
            // 50% for level 1
            resultScoreLevel2 = (resultScoreLevel1 * 50 / 100) + ((scoreLevel2 / length * 100.0) * 50 / 100)

            // show reuslt
            showResult();
            // level3
            setTimeout(() => {
                setupChartLevel3()
            }, 100)
        }

        function showResult() {
            resultScoreLevel3 = (PearsonCorrelationCoefficient(sampleAverages, userAverages) * 50 / 100) +
                resultScoreLevel2 * 50 / 100
            totalScore = Math.round(resultScoreLevel3);
            handleReview(totalScore);
            console.log(totalScore);
        }

        function createCommentByScore(score) {
            let index = Math.floor(Math.random() * 3);
            let comment = '';
            let color = '';

            if (score >= 90 && score <= 100) {
                comment = pronunciationComment['excellent'][index];
                color = '#4CAF50';
            } else if (score >= 75 && score < 90) {
                comment = pronunciationComment['good'][index];
                color = '#8BC34A';
            } else if (score >= 50 && score < 75) {
                comment = pronunciationComment['average'][index];
                color = '#DAA520';
            } else if (score < 50) {
                comment = pronunciationComment['poor'][index];
                color = '#F44336';
            }

            comment = comment.replace('{score}', score);

            return {
                comment,
                color
            };
        }

        function handleReview(score) {
            let {
                comment,
                color
            } = createCommentByScore(score);
            createProgressBar(color, score);
            console.log(comment, color);
            $('#review').text(comment);
            resultDetailText.css('display', 'block')
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
                delay: { show: 500, hide: 100 },
                trigger: 'hover focus',
                animation: true
            });
        }
    </script>
@endsection
