@extends('client.shared.lesson-detail')

@section('lesson-detail-styles')
    <link rel="stylesheet" href="{{ asset('css/plugins/videojs/video-js.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/videojs/index.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/lesson-detail/video-lesson.css') }}">
@endsection

@section('lesson-detail-content')
<div class="video-lesson">
    <video id="my-video" class="video-js vjs-theme-fantasy" controls preload="auto" width="640" height="360"
        data-setup="{}">
        <source src="{{ $video_url }}" type="application/x-mpegURL">
        <p class="vjs-no-js">
            To view this video please enable JavaScript, and consider upgrading to a web browser that
            <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
        </p>
    </video>
    <div class="video-placeholder mb-5" style="display: none;">
        <div class="card text-center">
            <div class="card-body py-5">
                <span class="carbon--video-off-filled error-icon"></span>
                <h5 class="card-title">Không thể phát video</h5>
                <p class="card-text">Đã xảy ra lỗi khi tải video bài học. Vui lòng kiểm tra kết nối internet và thử lại.</p>
                <button class="btn btn-primary btn-reload" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise me-1"></i> Tải lại trang web
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('lesson-detail-scripts')
    <script src="{{ asset('js/plugins/videojs/video.min.js') }}"></script>
    <script src="{{ asset('js/plugins/videojs/videojs-http-source-selector.min.js') }}"></script>
    <script src="{{ asset('js/plugins/videojs/videojs-contrib-quality-levels.min.js') }}"></script>
    <script src="{{ asset('js/plugins/videojs/videojs-http-streaming.min.js') }}"></script>
    <script>
        const getVideoConfig = () => {
            return {
                controls: true,
                preload: 'auto',
                autoplay: true,
                controlBar: {
                    children: [
                        'playToggle',
                        'currentTimeDisplay',
                        'progressControl',
                        'volumePanel',
                        'durationDisplay',
                        'volumeBar',
                        'playbackRateMenuButton',
                        'fullscreenToggle',
                    ],
                    volumePanel: false,
                    durationDisplay: true
                },
                playbackRates: [0.5, 1, 1.5, 2],
                html5: {
                    vhs: {
                        overrideNative: true
                    }
                }
            };
        }

        (function() {
            let initAttempts = 0;
            const MAX_ATTEMPTS = 5;
            let tsSegmentRetries = 0;
            const MAX_TS_RETRIES = 100;

            function initializePlayer() {
                if (initAttempts >= MAX_ATTEMPTS) {
                    console.error('Failed to initialize video player after', MAX_ATTEMPTS, 'attempts');

                    // $('#my-video').hide();
                    // $('.video-placeholder').show();
                    return;
                }
                initAttempts++;

                // Make sure the video element exists before initializing
                const videoElement = document.getElementById('my-video');
                if (!videoElement) {
                    console.warn(`Video element not found, retrying in 100ms (attempt ${initAttempts}/${MAX_ATTEMPTS})`);
                    setTimeout(initializePlayer, 100);
                    return;
                }

                // Dispose existing players
                const existingPlayers = videojs.getPlayers();
                for (const playerId in existingPlayers) {
                    if (existingPlayers[playerId]) {
                        existingPlayers[playerId].dispose();
                    }
                }

                try {
                    // Initialize new player
                    const player = videojs('my-video', getVideoConfig());

                    if (!player) {
                        throw new Error('Failed to initialize video player');
                    }

                    player.httpSourceSelector();

                    // Add error handling
                    player.on('error', function(e) {
                        console.log('Error: ', e);
                        handlePlayerError(player, e);
                        // player.error(4);
                    });

                    player.src({
                        src: '{{ $video_url }}',
                        type: 'application/x-mpegURL'
                    });

                    // Wait for the player to be ready before accessing menus
                    player.ready(function() {
                        try {
                            addSkipButton(player);
                            allowQualitySelect(player);
                            earnPointsOnVideoEnded(player);
                        } catch (error) {
                            console.error('Error in player ready callback:', error);
                        }
                    });
                } catch (error) {
                    console.error(`Error initializing video player (attempt ${initAttempts}/${MAX_ATTEMPTS}):`, error);
                    // Retry after a delay
                    if (initAttempts < MAX_ATTEMPTS) {
                        setTimeout(initializePlayer, 500);
                    }
                }
            }

            function handlePlayerError(player, e) {
                const error = player.error();

                // Handle HLS playlist request error
                if (
                    (error.code === 2 || error.status === 404 || error.message.includes('HLS playlist request error')) ||
                    error.code === 4
                ) {
                    // console.error(`HLS playlist request failed (attempt ${initAttempts}/${MAX_ATTEMPTS}):`, error.message);
                    retryInitialization(player);
                    return;
                }

                // Log other errors
                console.error('Video player error:', error);
            }

            function retryInitialization(player) {
                // Clear the error
                player.error(null);

                // Retry initialization after a delay
                if (initAttempts < MAX_ATTEMPTS) {
                    setTimeout(function() {
                        player.dispose();
                        initializePlayer();
                    }, 500);
                }
            }

            const player = videojs('my-video', getVideoConfig());

            player.httpSourceSelector();
            player.src({
                src: '{{ $video_url }}',
                type: 'application/x-mpegURL'
            });

            // Wait for the player to be ready before accessing menus
            player.ready(function() {
                addSkipButton(player);
                allowQualitySelect(player);
                earnPointsOnVideoEnded(player);
            });
        })();

        const earnPointsOnVideoEnded = (player) => {
            player.on('ended', function() {
                @if($isValidPayment && !$isFinishedContent)
                    const rewardPoints = {{ getRewardPointRule('learning')['video']['completion_points'] }};
                    earnPointFinishContent('{{$detailContent->id}}', rewardPoints, 'video');
                    animateHicoin(rewardPoints);
                    checkFinishContent();
                @endif
                let lastLogin = '{{ \Carbon\Carbon::parse(Auth::user()->last_login_date)->format('Y-m-d') }}';
                let today = '{{ \Carbon\Carbon::today()->format('Y-m-d') }}';

                if (lastLogin != today) {
                    showDailyStreak('{{ $detailContent->id }}');
                }
            })
        };

        const allowQualitySelect = (player) => {
            // Ensure HTTP Source Selector is fully initialized
            player.on('sourceselectioninitialized', function() {
                try {
                    // Get the quality selector button
                    const qualitySelector = player.controlBar.getChild('VideoJsButtonClass');
                    if (qualitySelector && qualitySelector.menu) {
                        const menuItems = qualitySelector.menu.children();

                        // Function to set quality
                        const setQuality = function(label) {
                            const qualityLevels = player.qualityLevels();
                            if (!qualityLevels) return;

                            for (let i = 0; i < qualityLevels.length; i++) {
                                let quality = qualityLevels[i];
                                quality.enabled = (quality.height === parseInt(label) || label === 'auto');
                            }
                        };

                        // Add click handlers to menu items
                        menuItems.forEach(function(item) {
                            item.on('click', function() {
                                setQuality(this.options_.label);
                            });
                        });

                        // Set initial quality to auto
                        setQuality('auto');
                    }
                } catch (error) {
                    console.error('Error setting up quality menu:', error);
                }
            });
        }

        const addSkipButton = (player) => {
            const SkipButton = videojs.extend(videojs.getComponent('Button'), {
                constructor: function() {
                    videojs.getComponent('Button').call(this, player);
                },

                buildCSSClass: function() {
                    return 'vjs-skip-button vjs-control vjs-skip-button';
                },

                handleClick: function() {
                    goToNextLesson();
                },

                createEl: function() {
                    return videojs.dom.createEl('button', {
                        className: this.buildCSSClass(),
                        innerHTML: '<i class="bi bi-skip-end-fill fs-4 position-relative" style="bottom: 2px;" ></i>',
                        title: 'Skip'
                    });
                }
            });

            // Đăng ký component
            videojs.registerComponent('SkipButton', SkipButton);

            // Thêm nút vào control bar
            const controlBar = player.getChild('controlBar');
            const skipButton = controlBar.addChild('SkipButton', {}, controlBar.children().length);
        }

        const goToNextLesson = () => {
            let seriesSlug = "{{ request()->route('slug') }}";
            let seriesComboSlug = "{{ request()->route('combo_slug') }}";
            let contentId = "{{ request()->route('stt') }}";

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: "{{ route('learning-management.next-lesson') }}",
                type: "get",
                data: {
                    series_slug: seriesSlug,
                    series_combo_slug: seriesComboSlug,
                    content_id: contentId
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = response.url;
                    }
                }
            });
        }
    </script>
@endsection
