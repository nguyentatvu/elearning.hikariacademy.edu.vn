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
                        overrideNative: true,
                        enableLowInitialPlaylist: false,
                        useDevicePixelRatio: false,
                        maxBitrate: 0,
                        enableAdaptiveBitrate: false
                    }
                }
            };
        }

        (function() {
            let initAttempts = 0;
            const MAX_ATTEMPTS = 5;
            let tsSegmentRetries = 0;
            const MAX_TS_RETRIES = 100;

            // Function to disable adaptive bitrate completely
            function disableABR(player) {
                if (player.tech_ && player.tech_.vhs) {
                    const vhs = player.tech_.vhs;
                    if (vhs.playlists && vhs.playlists.master) {
                        // Disable ABR functionality
                        vhs.masterPlaylistController_.useQualityLevels_ = false;
                        vhs.masterPlaylistController_.fastQualityChange_ = false;

                        // Force manual selection only
                        vhs.masterPlaylistController_.mediaSource.autoUpdateEnd = false;

                        // Disable automatic quality selection
                        const qualityLevels = player.qualityLevels();
                        console.log(qualityLevels);
                        for (let i = 0; i < qualityLevels.length; i++) {
                            qualityLevels[i].enabled = false;
                        }
                    }
                }
            }

            const player = videojs('my-video', getVideoConfig());

            // Disable ABR after source selector is initialized
            disableABR(player);
            player.src({
                src: '{{ $video_url }}',
                type: 'application/x-mpegURL'
            });

            // Wait for the player to be ready before accessing menus
            player.ready(function() {
                player.httpSourceSelector();

                player.qualityLevels().on('addqualitylevel', function(event) {
                    var qualityLevel = event.qualityLevel;
                    qualityLevel.enabled = false;
                });

                addSkipButton(player);
                allowQualitySelect(player);
                earnPointsOnVideoEnded(player);
                addUpperSeekbarForMobile(player);
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
                @if (Auth::check())
                    let logged = '{{ Auth::user()->has_logged_in }}';

                    if (logged == false) {
                        showDailyStreak('{{ $detailContent->id }}');
                    }
                @endif
            })
        };

        const allowQualitySelect = (player) => {
            // Ensure HTTP Source Selector is fully initialized
            player.on('sourceselectioninitialized', function() {
                console.log('tset');
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

        const addUpperSeekbarForMobile = (player) => {
            const controlBar = player.getChild('ControlBar');
            const progressControl = controlBar.getChild('ProgressControl');

            const clonedProgress = new ProgressControl(player, {});
            controlBar.addChild(clonedProgress, {}, 0);
        }
    </script>
@endsection
