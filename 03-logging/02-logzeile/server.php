<?php

$logDir = __DIR__ . "/logs";

if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

$logFile = $logDir . "/access.log";

$logLine = date("Y-m-d H:i:s") . " Server gestartet\n";

file_put_contents(
    $logFile,
    $logLine,
    FILE_APPEND
);

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->on("request", function ($request, $response): void {
    $response->end("Logzeile mit Zeitstempel\n");
});

$server->start();