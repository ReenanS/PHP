<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);


// TODO: Add middleware user authentication

// validar que o user com type admin de fato é um administrador!

$app->add(function (Request $request, Response $response, callable $next) {
    // Use the PSR 7 $response object
    
    // [add firebase auth here]

    $response = $next($request, $response);
    return $response
        ->withHeader('Content-Type', 'application/vnd.api+json')
        ->withHeader('Access-Control-Allow-Origin', '*');
});
