<?php

require_once __DIR__ . '/render.php';

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->on("request", function ($request, $response): void {
    $uri = $request->server['request_uri'];

    switch ($uri) {
        case '/':
            $response->end(render('home.php'));
            break;

        case '/hello':
            $response->end(render('hello.php'));
            break;

        default:
            $response->status(404);
            $response->end("Seite nicht gefunden\n");
    }
});

$server->start();