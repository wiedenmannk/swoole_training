<?php

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->on("request", function (Swoole\Http\Request $request, Swoole\Http\Response $response): void {
    $uri = $request->server["request_uri"];

    if ($uri === "/api/status") {
        $response->header("Content-Type", "application/json");

        $response->end(
            json_encode([
                "status" => "ok",
                "message" => "Swoole API läuft"
            ])
        );

        return;
    }

    $response->status(404);
    $response->header("Content-Type", "application/json");

    $response->end(
        json_encode([
            "error" => "Route not found"
        ])
    );
});

$server->start();