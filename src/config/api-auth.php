<?php

return [
    'salutation'  => 'Kind regards, Laravel API Auth',
    'options'     => [
        'timezones' => [
            'enabled' => false,
        ],
    ],

    /**
     * The default expiration time for tokens in minutes.
     */
    'expirations' => [
        'default'             => 60,     // 1 hour
        'email_verifications' => 60,     // 1 hour
        'email_changes'       => 525600, // 1 year
        'password_resets'     => 525600, // 1 year
        'password_changes'    => 525600, // 1 year
    ],
];
