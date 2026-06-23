<?php

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->set([
    "worker_num" => 4
]);

$server->on("WorkerStart", function ($server, $workerId): void {
    echo "Worker {$workerId} gestartet\n";
});

$server->on("request", function ($request, $response) use ($server): void {
    $workerId = $server->worker_id;
    $uri = $request->server["request_uri"];

    $response->end("Worker {$workerId} hat {$uri} bearbeitet\n");
});

$server->start();