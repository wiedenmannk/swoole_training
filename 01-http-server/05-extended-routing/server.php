<?php

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->on("request", function ($request, $response): void {

    $uri = $request->server['request_uri'];

    switch ($uri) {

        case '/':
            $response->end("Home\n");
            break;

        case '/hello':
            $response->end("Hallo Swoole\n");
            break;

        case '/test':
            $response->end("Testseite\n");
            break;

        case '/debug':
            $response->end("Debugseite\n");
            break;

        default:
            $response->status(404);
            $response->end("Seite nicht gefunden\n");
    }
});

$server->start();