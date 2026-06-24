<?php

date_default_timezone_set("Europe/Berlin");

$table = new Swoole\Table(1024);

$table->column("count", Swoole\Table::TYPE_INT);

$table->create();

$table->set("visits", [
    "count" => 0
]);

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->set([
    "worker_num" => 4
]);

$server->on("WorkerStart", function ($server, $workerId): void {
    echo "Worker {$workerId} gestartet\n";
});

$server->on("request", function ($request, $response) use ($server, $table): void {
    $workerId = $server->worker_id;

    $currentCount = $table->get("visits", "count");
    $newCount = $currentCount + 1;

    $table->set("visits", [
        "count" => $newCount
    ]);

    $response->end(
        "Worker: {$workerId}\n" .
        "Global Visits: {$newCount}\n"
    );
});

$server->start();