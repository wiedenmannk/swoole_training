<?php

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->set([
    "worker_num" => 4
]);

$server->on("WorkerStart", function ($server, $workerId): void {
    echo "Worker {$workerId} gestartet\n";
});

$server->on("request", function ($request, $response): void {
    $response->end("Hallo Worker\n");
});

$server->start();