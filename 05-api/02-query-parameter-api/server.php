<?php

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->on("request", function ($request, $response): void {
    $uri = $request->server["request_uri"];

    $response->header("Content-Type", "application/json");

    if ($uri === "/api/status") {
        $response->end(
            json_encode([
                "status" => "ok",
                "message" => "Swoole API läuft"
            ])
        );

        return;
    }

    if ($uri === "/api/hello") {
        $name = $request->get["name"] ?? "Gast";

        $response->end(
            json_encode([
                "message" => "Hallo {$name}"
            ])
        );

        return;
    }

    $response->status(404);

    $response->end(
        json_encode([
            "error" => "Route not found"
        ])
    );
});

$server->start();