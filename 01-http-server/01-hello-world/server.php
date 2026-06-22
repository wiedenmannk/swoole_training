<?php

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->on("request", function ($request, $response): void {
    $response->end("Hello Swoole\n");
});

$server->start();