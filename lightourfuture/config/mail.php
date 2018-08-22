<?php

return [

    
    'driver' => env('MAIL_DRIVER','ses'),

    'host' => env('MAIL_HOST', 'smtp.us-east-1.amazonaws.com'),

    'port' => env('MAIL_PORT', 587),
    
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS','lof@halo.co.jp'),
        'name'    => env('MAIL_FROM_NAME',"TakashiYano"),
    ],

    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    
    'username' => env('MAIL_USERNAME'),

    'password' => env('MAIL_PASSWORD'),
    
    'sendmail' => '/usr/sbin/sendmail -bs',
    
    'markdown' => [
        'theme' => 'default',
        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],
];
