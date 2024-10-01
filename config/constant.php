<?php

return [
    'article' => [
        'status' => [
            '1' => "Bản nháp",
            '2' => "Bài đăng",
        ],
        'uploadPath' => 'uploads/articles/',
    ],
    'login' => [
        'streak' => [
            '3' => 5,
            '8' => 10,
            '15' => 15,
        ]
    ],
    'series' => [
        'upload_path' => 'uploads/lms/combo/',
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
            'title' => [0, 8]
        ],
        'topic_icons' => [
            0 => 'vocab.png',
            1 => 'grammar.png',
            2 => 'exercise.png',
            3 => 'jap-character.svg'
        ],
        'chapter_icons' => [
            5 => 'score.png',
            9 => 'video-streaming.png',
            10 => 'flashcard.png',
            0 => 'lesson.png'
        ]
    ],
    'redeemed_coin' => [
        'vnd_convert_rate' => 1000
    ],
    'chatbot' => [
        'endpoint' => [
            'chat_messages' => 'chat-messages',
            'delete_conversation' => 'conversations',
        ]
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
    ]
];