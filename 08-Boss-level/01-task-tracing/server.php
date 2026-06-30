<?php

declare(strict_types=1);

date_default_timezone_set("Europe/Berlin");

$baseDir = __DIR__;
$logDir = $baseDir . "/logs";

if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

$applicationLog = $logDir . "/application.log";
$swooleLog = $logDir . "/swoole.log";

file_put_contents($applicationLog, "");
file_put_contents($swooleLog, "");

function nowMs(): string
{
    return date("H:i:s") . "." . substr((string) ((int) ((microtime(true) * 1000) % 1000) + 1000), 1);
}

function logApplication(string $file, string $message): void
{
    file_put_contents(
        $file,
        nowMs() . " " . $message . PHP_EOL,
        FILE_APPEND
    );
}

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->set([
    "worker_num" => 2,
    "task_worker_num" => 1,
    "log_file" => $swooleLog
]);

$server->on("Start", function () use ($applicationLog): void {
    logApplication($applicationLog, "[SERVER] Restaurant öffnet");
});

$server->on("WorkerStart", function (Swoole\Server $server, int $workerId) use ($applicationLog): void {
    if ($server->taskworker) {
        logApplication($applicationLog, "[TASK-WORKER {$workerId}] Koch kommt zur Arbeit");
        return;
    }

    logApplication($applicationLog, "[WORKER {$workerId}] Kellner kommt zur Arbeit");
});

$server->on("request", function ($request, $response) use ($server, $applicationLog): void {
    $uri = $request->server["request_uri"];
    $workerId = $server->worker_id;

    if ($uri === "/status") {
        $response->header("Content-Type", "application/json");
        $response->end(json_encode([
            "status" => "ok",
            "worker_id" => $workerId,
            "memory_usage" => memory_get_usage(true),
            "memory_peak" => memory_get_peak_usage(true),
            "time" => date("Y-m-d H:i:s")
        ]));

        return;
    }

    if ($uri === "/error") {
        logApplication($applicationLog, "[WORKER {$workerId}] Fehlerroute wurde aufgerufen");
        undefined_function();
    }

    $taskTraceId = uniqid("task-", true);
    $createdAt = microtime(true);
    $order = $request->get["order"] ?? "Pizza";

    logApplication(
        $applicationLog,
        "[{$taskTraceId}] [WORKER {$workerId}] Request angenommen order={$order}"
    );

    $swooleTaskId = $server->task([
        "trace_id" => $taskTraceId,
        "order" => $order,
        "request_worker" => $workerId,
        "created_at" => $createdAt
    ]);

    logApplication(
        $applicationLog,
        "[{$taskTraceId}] [WORKER {$workerId}] Task an Küche übergeben swoole_task_id={$swooleTaskId}"
    );

    $response->end(
        "Bestellung angenommen: {$order}\nTrace: {$taskTraceId}\n"
    );
});

$server->on("Task", function (Swoole\Server $server, int $taskId, int $srcWorkerId, mixed $data) use ($applicationLog): array {
    $taskWorkerId = $server->worker_id;
    $startedAt = microtime(true);

    $waitMs = round(($startedAt - $data["created_at"]) * 1000, 2);

    logApplication(
        $applicationLog,
        "[{$data["trace_id"]}] [TASK-WORKER {$taskWorkerId}] Task gestartet wait={$waitMs}ms order={$data["order"]}"
    );

    sleep(2);

    $finishedAt = microtime(true);
    $durationMs = round(($finishedAt - $startedAt) * 1000, 2);

    logApplication(
        $applicationLog,
        "[{$data["trace_id"]}] [TASK-WORKER {$taskWorkerId}] Task beendet duration={$durationMs}ms order={$data["order"]}"
    );

    return [
        "trace_id" => $data["trace_id"],
        "order" => $data["order"],
        "request_worker" => $data["request_worker"],
        "task_worker" => $taskWorkerId,
        "wait_ms" => $waitMs,
        "duration_ms" => $durationMs
    ];
});

$server->on("Finish", function (Swoole\Server $server, int $taskId, mixed $data) use ($applicationLog): void {
    logApplication(
        $applicationLog,
        "[{$data["trace_id"]}] [FINISH] Essen fertig order={$data["order"]} request_worker={$data["request_worker"]} task_worker={$data["task_worker"]} wait={$data["wait_ms"]}ms duration={$data["duration_ms"]}ms"
    );
});

$server->on("WorkerError", function (Swoole\Server $server, int $workerId, int $workerPid, int $exitCode, int $signal) use ($applicationLog): void {
    logApplication(
        $applicationLog,
        "[WORKER-ERROR] worker={$workerId} pid={$workerPid} exitCode={$exitCode} signal={$signal}"
    );
});

$server->on("WorkerStop", function (Swoole\Server $server, int $workerId) use ($applicationLog): void {
    if ($server->taskworker) {
        logApplication($applicationLog, "[TASK-WORKER {$workerId}] Koch geht nach Hause");
        return;
    }

    logApplication($applicationLog, "[WORKER {$workerId}] Kellner geht nach Hause");
});

$server->on("Shutdown", function () use ($applicationLog): void {
    logApplication($applicationLog, "[SERVER] Restaurant schließt");
});

$server->start();