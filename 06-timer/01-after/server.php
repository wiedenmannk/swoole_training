<?php

$server = new Swoole\Http\Server("127.0.0.1", 9501);

Swoole\Timer::after(3000, function (): void {
    echo "Bestellung ist fertig" . PHP_EOL;
});

$server->on("request", function ($request, $response): void {
    $response->end("Bestellung angenommen\n");
});

$server->start();