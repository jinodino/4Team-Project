<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN', 'sandbox1b99720ac62a4ab48046e97a8c004fa8.mailgun.org'),
        'secret' => env('MAILGUN_SECRET', 'key-9230e8c71a44471f687506976ca937a1'),
    ],

    'ses' => [
        'key' => env('SES_KEY','AKIAIFVXDV5QKDV2NY3Q'),
        'secret' => env('SES_SECRET','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'),
        'region' => env('SES_REGION','us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

];
