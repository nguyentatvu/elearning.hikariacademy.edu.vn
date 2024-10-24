<?php

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
    ]
];