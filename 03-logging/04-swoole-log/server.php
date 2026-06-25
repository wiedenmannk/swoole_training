<?php

date_default_timezone_set("Europe/Berlin");

$logDir = __DIR__ . "/logs";

if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

$applicationLogFile = $logDir . "/application.log";
$swooleLogFile = $logDir . "/swoole.log";

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->set([
    "log_file" => $swooleLogFile
]);

$server->on("request", function ($request, $response) use ($applicationLogFile): void {
    $uri = $request->server["request_uri"];
    $method = $request->server["request_method"];

    file_put_contents(
        $applicationLogFile,
        date("Y-m-d H:i:s") . " {$method} {$uri}" . PHP_EOL,
        FILE_APPEND
    );
    // hier ist bewusst ein Fehler drin
    if ($uri === "/error") {
        undefined_function();
    }

    $response->end("Logging Demo\n");
});

$server->start();