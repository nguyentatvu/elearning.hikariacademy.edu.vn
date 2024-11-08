<?php

$pronunciationUrl = env('PRONUNCIATION_ASSESSMENT_URL');

return [
    'article' => [
        'status' => [
            '1' => "Bản nháp",
            '2' => "Bài đăng",
        ],
        'upload_path' => 'uploads/articles/',
    ],
    'login' => [
        'streak' => [
            '1' => 1,
            '3' => 5,
            '8' => 10,
            '15' => 15,
        ]
    ],
    'series_combo' => [
        'image_url' => 'public/uploads/lms/combo/',
        'upload_path' => 'uploads/lms/combo/',
        'month_duration_map' => [
            "0" => 3,
            "1" => 6,
            "2" => 12
        ]
    ],
    'series' => [
        'upload_path' => 'uploads/lms/series/',
        'image_url' => 'public/uploads/lms/series/',
        'time' => [
            0 => 3,
            1 => 6,
            2 => 12,
        ],
        'type_map' => [
            'video' => [1, 2, 6, 9],
            'exercise' => [3, 4, 7],
            'audit' => [5],
            'flashcard' => [10],
            'title' => [0, 8],
            'handwriting' => [11],
            'pronunciation-assessment' => [12],
        ],
        'topic_icons' => [
            0 => 'vocab.png',
            1 => 'grammar.png',
            2 => 'exercise.png',
            3 => 'jap-character.svg',
            4 => 'jap-character.svg',
            5 => 'jap-character.svg',
            6 => 'jap-character.svg',
            7 => 'jap-character.svg',
            8 => 'jap-character.svg',
            9 => 'jap-character.svg',
            10 => 'jap-character.svg',
        ],
        'chapter_icons' => [
            5 => 'score.png',
            9 => 'video-streaming.png',
            10 => 'flashcard.png',
            0 => 'lesson.png',
            1 => 'lesson.png',
            2 => 'lesson.png',
            3 => 'lesson.png',
            4 => 'lesson.png',
            6 => 'lesson.png',
            7 => 'lesson.png',
            8 => 'lesson.png',
        ],
        'routes' => [
            'video' => 'learning-management.lesson.show',
            'exercise' => 'learning-management.lesson.exercise',
            'audit' => 'learning-management.lesson.audit',
            'flashcard' => 'learning-management.lesson.flashcard',
            'handwriting' => 'learning-management.lesson.handwriting',
            'pronunciation-assessment' => 'learning-management.lesson.pronunciation-assessment'
        ],
    ],
    'redeemed_coin' => [
        'vnd_convert_rate' => 1000
    ],
    'payment' => [
        'status' => [
            '0' => 'Chờ thanh toán',
            '1' => 'Thành công',
            '2' => 'Thất bại'
        ],
        'min_valid_time' => 15
    ],
    'coin_recharge_package' => [
        'status' => [
            '1' => 'Hiển thị',
            '0' => 'Ẩn',
        ]
    ],
    'flash_card' => [
        'audio_url' => 'public/uploads/flashcard/',
    ],
    'pronunciation' => [
        'endpoint' => [
            'upload' => $pronunciationUrl . '/speech-to-text/',
            'assessment' => $pronunciationUrl . '/assessment/'
        ],
        'comment' => [
            'excellent' => [
                0 => 'Tuyệt vời! Bạn đã đạt {score} điểm! Phát âm của bạn rất tự nhiên, gần như hoàn hảo. Hãy tiếp tục duy trì phong độ này nhé!',
                1 => 'Rất tốt! Với {score} điểm, phát âm của bạn rất chuẩn. Hãy duy trì sự tự tin này, bạn đã làm rất tuyệt!',
                2 => 'Xuất sắc! {score} điểm cho thấy bạn phát âm rất giống người bản xứ. Hãy luyện thêm để càng hoàn thiện hơn!'
            ],
            'good' => [
                0 => 'Chúc mừng! Bạn đã đạt {score} điểm! Phát âm của bạn khá tốt, chỉ cần luyện thêm một chút nữa để đạt độ tự nhiên hoàn hảo.',
                1 => 'Điểm số của bạn là {score}, phát âm của bạn ổn rồi, chỉ còn một số âm cần cải thiện để phát âm tự nhiên hơn.',
                2 => 'Bạn làm rất tốt với {score} điểm! Phát âm của bạn gần chuẩn rồi, hãy chú ý thêm một vài âm để đạt kết quả cao hơn nhé!'
            ],
            'average' => [
                0 => 'Bạn đã đạt {score} điểm! Phát âm của bạn khá ổn, nhưng có một số âm cần điều chỉnh để giọng tự nhiên hơn. Hãy tiếp tục luyện tập!',
                1 => 'Kết quả {score} điểm cho thấy bạn đang tiến bộ! Phát âm của bạn đang cải thiện, chỉ cần luyện thêm chút nữa là sẽ tự nhiên hơn.',
                2 => 'Bạn làm khá tốt với {score} điểm, nhưng vẫn còn vài âm chưa chuẩn. Luyện thêm để cùng nhau cải thiện phát âm của bạn nhé!'
            ],
            'poor' => [
                0 => 'Bạn đã đạt {score} điểm! Phát âm của bạn cần cải thiện nhiều, nhưng đây chỉ là bước khởi đầu. Cứ luyện tập đều đặn là sẽ thấy kết quả rõ rệt!',
                1 => 'Cố lên! Mặc dù điểm của bạn là {score}, phát âm cần thêm luyện tập, chỉ cần kiên trì là bạn sẽ thấy sự thay đổi lớn!',
                2 => 'Kết quả chưa cao ({score} điểm), nhưng đừng nản lòng! Phát âm cần nhiều luyện tập, và bạn sẽ thấy mình tiến bộ nhanh chóng nếu tiếp tục cố gắng'
            ]
        ]
    ],
    'banner' => [
        0 => [
            'position' => 'login_banner',
            'category' => 'single_image',
            'title' => 'Banner Đăng Nhập',
            'description' => 'Banner này xuất hiện trên trang đăng nhập để nhắc nhở người dùng đăng nhập.',
            'size' => '1920x1080',
            'image_url' => 'https://example.com/path/to/login_banner.jpg',
        ],
        1 => [
            'position' => 'register_banner',
            'category' => 'single_image',
            'title' => 'Banner Đăng Ký',
            'description' => 'Banner này hiển thị trên trang đăng ký để khuyến khích người dùng mới đăng ký.',
            'size' => '1920x1080',
            'image_url' => 'https://example.com/path/to/register_banner.jpg',
        ],
        2 => [
            'position' => 'home_slider_banner',
            'category' => 'multi_image',
            'title' => 'Slider Trang Chủ',
            'description' => 'Một slider chứa nhiều hình ảnh được giới thiệu nằm ở phía trên trang chủ.',
            'size' => '1920x600',
            'image_url' => 'https://example.com/path/to/home_slider_banner.jpg',
        ],
        // 3 => [
        //     'position' => 'home_banner_mini_1',
        //     'category' => 'single_image',
        //     'title' => 'Banner Mini Trang Chủ 1',
        //     'description' => 'Banner mini xuất hiện trên trang chủ, thiết kế để khuyến mãi nhanh.',
        //     'size' => '600x300',
        //     'image_url' => 'https://example.com/path/to/home_banner_mini_1.jpg',
        // ],
        // 4 => [
        //     'position' => 'home_banner_mini_2',
        //     'category' => 'single_image',
        //     'title' => 'Banner Mini Trang Chủ 2',
        //     'description' => 'Một banner mini khác cho các khuyến mãi bổ sung trên trang chủ.',
        //     'size' => '600x300',
        //     'image_url' => 'https://example.com/path/to/home_banner_mini_2.jpg',
        // ],
        5 => [
            'position' => 'home_banner_1',
            'category' => 'single_image',
            'title' => 'Banner Trang Chủ 1',
            'description' => 'Một banner lớn có thể giới thiệu nhiều hình ảnh trên trang chủ.',
            'size' => '1920x500',
            'image_url' => 'https://example.com/path/to/home_banner_1.jpg',
        ],
        6 => [
            'position' => 'home_banner_2',
            'category' => 'single_image',
            'title' => 'Banner Trang Chủ 2',
            'description' => 'Một banner lớn khác cho các khuyến mãi hình ảnh bổ sung trên trang chủ.',
            'size' => '1920x500',
            'image_url' => 'https://example.com/path/to/home_banner_2.jpg',
        ],
        9 => [
            'position' => 'contact_logo',
            'category' => 'single_image',
            'title' => 'Banner trang Liên Hệ',
            'description' => 'Một banner logo hiển thị trên trang liên hệ cho mục đích thương hiệu.',
            'size' => '300x100',
            'image_url' => 'https://example.com/path/to/contact_logo.jpg',
        ],
        // 10 => [
        //     'position' => 'course_logo',
        //     'category' => 'single_image',
        //     'title' => 'Banner Khóa Học',
        //     'description' => 'Banner này được sử dụng trên các trang liên quan đến khóa học để nhận diện và thương hiệu.',
        //     'size' => '300x100',
        //     'image_url' => 'https://example.com/path/to/course_logo.jpg',
        // ],
    ]
];