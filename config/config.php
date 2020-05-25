<?php

return [
    'name' => 'Core',
    'verify' => [
        'debug' => env('VERIFY_DEBUG', false),
        'code' => env('VERIFY_CODE', 123456),
    ]
];
