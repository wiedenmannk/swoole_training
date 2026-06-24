<?php

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->set([
    "worker_num" => 4
]);

$count = 0;

$server->on("request", function ($request, $response) use ($server, &$count): void {
    $workerId = $server->worker_id;

    $count++;

    $response->end(
        "Worker {$workerId} Count: {$count}\n"
    );
});

$server->start();