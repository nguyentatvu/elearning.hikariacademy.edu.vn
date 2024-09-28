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