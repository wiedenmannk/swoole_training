<?php

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->set([
    "worker_num" => 4
]);

$visits = 0;
$lastUri = "-";

$server->on("request", function ($request, $response) use ($server, &$visits, &$lastUri): void {
    $workerId = $server->worker_id;
    $uri = $request->server["request_uri"];

    $previousUri = $lastUri;

    $visits++;
    $lastUri = $uri;

    $response->end(
        "Worker: {$workerId}\n" .
        "Visits auf diesem Worker: {$visits}\n" .
        "Vorherige URI auf diesem Worker: {$previousUri}\n" .
        "Aktuelle URI: {$uri}\n"
    );
});

$server->start();