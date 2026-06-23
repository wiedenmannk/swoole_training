<?php

require_once __DIR__ . '/render.php';

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->on("request", function ($request, $response): void {

    $uri = $request->server['request_uri'];

    switch ($uri) {

        case '/':

            $response->end(
                render('home.php')
            );

            break;

        case '/hello':

            $name = $request->get['name'] ?? 'Gast';

            $response->end(
                render('hello.php', [
                    'name' => $name
                ])
            );

            break;

        default:

            $response->status(404);
            $response->end("Seite nicht gefunden");
    }
});

$server->start();