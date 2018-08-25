<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        'db' => [
            'host' => "localhost",
            'user' => "root",
            'pass' => "",
            'dbname' => "nan"
        ],

        // Monolog settings
        'logger' => [
            'name' => 'ebloom-log',
            'path' => '../logs/app.log',
            'level' => \Monolog\Logger::DEBUG
        ]
    ],
];
