<?php

$count = 0;

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->on("request", function ($request, $response) use (&$count): void {

    $count++;

    $response->end(
        "Count: {$count}\n"
    );
});

$server->start();