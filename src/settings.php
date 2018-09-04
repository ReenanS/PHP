<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        'db' => [
            'host' => "den1.mysql6.gear.host",
            'user' => "ebloom",
            'pass' => "By1379HA-e-7",
            'dbname' => "ebloom"
        ],

        // Monolog settings
        'logger' => [
            'name' => 'ebloom-log',
            'path' => '../logs/app.log',
            'level' => \Monolog\Logger::DEBUG
        ]
    ],
];
