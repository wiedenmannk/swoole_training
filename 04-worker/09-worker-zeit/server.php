<?php

date_default_timezone_set("Europe/Berlin");

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->set([
    "worker_num" => 2,
    "task_worker_num" => 1
]);

$requestCounts = [];

$server->on("Start", function (): void {
    echo "Restaurant öffnet\n";
});

$server->on("WorkerStart", function (Swoole\Server $server, int $workerId) use (&$requestCounts): void {
    if ($server->taskworker) {
        echo "Koch {$workerId} kommt zur Arbeit\n";
        return;
    }

    $requestCounts[$workerId] = 0;

    echo "Kellner {$workerId} kommt zur Arbeit\n";
});

$server->on("request", function ($request, $response) use ($server, &$requestCounts): void {
    $workerId = $server->worker_id;

    $requestCounts[$workerId]++;

    echo "Kellner {$workerId} nimmt Bestellung {$requestCounts[$workerId]} an\n";

    $server->task("Pizza");

    $response->end(
        "Kellner {$workerId}: Bestellung angenommen\n"
    );
});

$server->on("Task", function ($server, int $taskId, int $srcWorkerId, mixed $data): string {
    echo "Koch kocht {$data}\n";

    sleep(2);

    return $data;
});

$server->on("Finish", function ($server, int $taskId, mixed $data): void {
    echo "Küche meldet: {$data} ist fertig\n";
});

$server->on("WorkerStop", function (Swoole\Server $server, int $workerId) use (&$requestCounts): void {
    if ($server->taskworker) {
        echo "Koch {$workerId} geht nach Hause\n";
        return;
    }

    $count = $requestCounts[$workerId] ?? 0;

    echo "Kellner {$workerId} geht nach Hause. Bestellungen heute: {$count}\n";
});

$server->on("Shutdown", function (): void {
    echo "Restaurant schließt\n";
});

$server->start();