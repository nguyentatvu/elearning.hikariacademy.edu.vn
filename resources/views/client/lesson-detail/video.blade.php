@extends('client.shared.lesson-detail')

@section('lesson-detail-styles')
    <link rel="stylesheet" href="{{ asset('css/plugins/videojs/video-js.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/videojs/index.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/lesson-detail/video-lesson.css') }}">
@endsection

@section('lesson-detail-content')
    <video id="my-video" class="video-js vjs-theme-fantasy" controls preload="auto" width="640" height="360"
        data-setup="{}">
        <source src="{{ $video_url }}" type="application/x-mpegURL">
        <p class="vjs-no-js">
            To view this video please enable JavaScript, and consider upgrading to a web browser that
            <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
        </p>
    </video>
    {{-- @include('client.components.streak') --}}
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
            const existingPlayers = videojs.getPlayers();
            for (const playerId in existingPlayers) {
                if (existingPlayers[playerId]) {
                    existingPlayers[playerId].dispose();
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
                @if ($isValidPayment && !$isFinishedContent)
                    animateHicoin(1);
                    earnPointFinishContent('{{ $detailContent->id }}', 1, 'video');
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
