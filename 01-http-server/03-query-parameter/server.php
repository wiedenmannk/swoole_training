<?php

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->on("request", function ($request, $response): void {

    $name = $request->get['name'] ?? 'Gast';

    $response->end("Hallo {$name}\n");
});

$server->start();