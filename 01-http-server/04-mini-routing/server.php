<?php

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->on("request", function ($request, $response): void {

    $uri = $request->server['request_uri'];

    if ($uri === '/') {
        $response->end("Home\n");
        return;
    }

    if ($uri === '/hello') {
        $response->end("Hallo Swoole\n");
        return;
    }

    if ($uri === '/test') {
        $response->end("Testseite\n");
        return;
    }

    $response->status(404);
    $response->end("Seite nicht gefunden\n");
});

$server->start();