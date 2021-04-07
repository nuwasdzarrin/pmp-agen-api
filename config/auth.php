<?php
return [
    'defaults' => [
        'guard' => 'vendor',
        'passwords' => 'users',
    ],

    'guards' => [
        'admin' => [
            'driver' => 'passport',
            'provider' => 'admin',
        ],
        'vendor' => [
            'driver' => 'passport',
            'provider' => 'vendor',
        ],
        'customer' => [
            'driver' => 'passport',
            'provider' => 'customer',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\User::class
        ],
        'admin' => [
            'driver' => 'eloquent',
            'model' => \App\Models\Admin::class
        ],
        'customer' => [
            'driver' => 'eloquent',
            'model' => \App\Models\Customer::class
        ],
        'vendor' => [
            'driver' => 'eloquent',
            'model' => \App\Models\BranchUser::class
        ]
    ]
];
