<?php

$server = new Swoole\Http\Server("127.0.0.1", 9501);

Swoole\Timer::tick(1000, function (): void {
    echo date("H:i:s") . PHP_EOL;
});

$server->on("request", function ($request, $response): void {
    $response->end("Tick Demo");
});

$server->start();