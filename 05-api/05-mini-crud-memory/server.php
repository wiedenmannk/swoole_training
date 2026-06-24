<?php

$users = [];

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->on("request", function ($request, $response) use (&$users): void {
    $uri = $request->server["request_uri"];
    $method = $request->server["request_method"];

    $response->header("Content-Type", "application/json");

    if ($method === "POST" && $uri === "/api/users") {

        $body = $request->rawContent();
        $data = json_decode($body, true);

        if (!is_array($data)) {
            $response->status(400);
            $response->end(json_encode([
                "error" => "Invalid JSON"
            ]));
            return;
        }

        if (!isset($data["name"]) || trim($data["name"]) === "") {
            $response->status(400);
            $response->end(json_encode([
                "error" => "Name is required"
            ]));
            return;
        }

        $users[] = [
            "name" => $data["name"]
        ];

        $response->status(201);

        $response->end(json_encode([
            "status" => "created"
        ]));

        return;
    }

    if ($method === "GET" && $uri === "/api/users") {

        $response->end(
            json_encode($users)
        );

        return;
    }

    $response->status(404);

    $response->end(json_encode([
        "error" => "Route not found"
    ]));
});

$server->start();