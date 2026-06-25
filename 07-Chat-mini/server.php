<?php

// DELTA: Namen aller verbundenen Clients
$clients = [];

// WebSocket Server statt HTTP Server
$server = new Swoole\WebSocket\Server("127.0.0.1", 9501);

// weil unser Swoole ohne zlib gebaut wurde.
$server->set([
    "websocket_compression" => false
]);

// Wird aufgerufen, wenn ein Client eine WebSocket-Verbindung öffnet
$server->on("Open", function ($server, $request): void {
    echo "Client {$request->fd} verbunden\n";
});

// Wird aufgerufen, wenn ein Client eine Nachricht sendet
/*
$server->on("Message", function ($server, $frame): void {
    echo "Nachricht von Client {$frame->fd}: {$frame->data}\n";
});
*/

// DELTA: nachricht senden:
/*
$server->on("Message", function ($server, $frame): void {
    echo "Nachricht von Client {$frame->fd}: {$frame->data}\n";

    // DELTA: Antwort ohne Compression-Flag senden
    $server->push(
        $frame->fd,
        "Hallo {$frame->data}"
    );
});
*/

// next Delta
/*
$server->on("Message", function ($server, $frame): void {
    echo "Nachricht von Client {$frame->fd}: {$frame->data}\n";

    // DELTA: Nachricht an alle verbundenen Clients senden
    foreach ($server->connections as $fd) {

        // DELTA: Prüfen, ob die Verbindung noch gültig ist
        if ($server->isEstablished($fd)) {
            $server->push(
                $fd,
                "Client {$frame->fd}: {$frame->data}"
            );
        }
    }
});
*/

$server->on("Message", function ($server, $frame) use (&$clients): void {
    echo "Nachricht von Client {$frame->fd}: {$frame->data}\n";

    // DELTA: JSON lesen
    $data = json_decode($frame->data, true);

    if (!is_array($data)) {
        return;
    }

    // DELTA: Login

    if ($data["type"] === "login") {

        $clients[$frame->fd] = $data["name"];

        $server->push(
            $frame->fd,
            "Willkommen {$data["name"]}"
        );

        return;
    }
    // DELTA: Nachricht an alle verbundenen Clients senden mit JSON
    if ($data["type"] === "message") {

        $name = $clients[$frame->fd] ?? "Unbekannt";

        foreach ($server->connections as $fd) {

            if ($server->isEstablished($fd)) {

                $server->push(
                    $fd,
                    "{$name}: {$data["text"]}"
                );
            }
        }
    }
});


// Wird aufgerufen, wenn ein Client die Verbindung schließt
$server->on("Close", function ($server, $fd): void {
    echo "Client {$fd} getrennt\n";
});

$server->start();