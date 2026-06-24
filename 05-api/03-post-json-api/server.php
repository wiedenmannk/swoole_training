<?php

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->on("request", function ($request, $response): void {
    $uri = $request->server["request_uri"];
    $method = $request->server["request_method"];

    $response->header("Content-Type", "application/json");

    if ($method === "GET" && $uri === "/api/status") {
        $response->end(
            json_encode([
                "status" => "ok",
                "message" => "Swoole API läuft"
            ])
        );

        return;
    }

    if ($method === "GET" && $uri === "/api/hello") {
        $name = $request->get["name"] ?? "Gast";

        $response->end(
            json_encode([
                "message" => "Hallo {$name}"
            ])
        );

        return;
    }

    if ($method === "POST" && $uri === "/api/echo") {
        $body = $request->rawContent();

        $data = json_decode($body, true);

        $response->end(
            json_encode([
                "received" => $data
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