<?php

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->on("Start", function (): void {
    $counter = 0;

    Swoole\Timer::tick(1000, function ($timerId) use (&$counter): void {
        $counter++;

        echo "Tick {$counter}" . PHP_EOL;

        if ($counter >= 5) {
            echo "Timer wird gestoppt" . PHP_EOL;

            Swoole\Timer::clear($timerId);
        }
    });
});

$server->on("request", function ($request, $response): void {
    $response->end("Tick Stop Demo\n");
});

$server->start();