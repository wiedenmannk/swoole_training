<?php

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->set([
    "worker_num" => 2,
    "task_worker_num" => 3
]);

$server->on("WorkerStart", function ($server, $workerId): void {
    $type = $server->taskworker ? "Küche / Task Worker" : "Kellner / HTTP Worker";

    echo "{$type} {$workerId} gestartet\n";
});

$server->on("request", function ($request, $response) use ($server): void {
    $workerId = $server->worker_id;

    $order = $request->get["order"] ?? "Pizza";

    $taskId = $server->task($order);

    $response->end(
        "Kellner Worker {$workerId}: Bestellung '{$order}' angenommen. Task {$taskId}\n"
    );
});

$server->on("Task", function ($server, $taskId, $srcWorkerId, $data): string {
    echo "Küche: Bestellung '{$data}' wird gekocht...\n";

    sleep(5);

    echo "Küche: Bestellung '{$data}' ist fertig\n";

    return $data;
});

$server->on("Finish", function ($server, $taskId, $data): void {
    echo "Service: Task {$taskId} beendet. Essen '{$data}' ist fertig.\n";
});

$server->start();