<?php

declare(strict_types=1);

date_default_timezone_set("Europe/Berlin");

use App\Handler\RouteHandler;
use App\Handler\StaffHandler;
use App\Table\StaffTable;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;
use App\Table\TaskTable;
use App\Handler\TaskHandler;
use App\Dto\TaskMember;

require_once __DIR__ . "/src/Dto/StaffMember.php";
require_once __DIR__ . "/src/Handler/StaffHandler.php";
require_once __DIR__ . "/src/Table/StaffTable.php";
require_once __DIR__ . "/src/Response/JsonResponse.php";
require_once __DIR__ . "/src/Handler/RouteHandler.php";
require_once __DIR__ . "/src/Dto/TaskMember.php";
require_once __DIR__ . "/src/Table/TaskTable.php";
require_once __DIR__ . "/src/Handler/TaskHandler.php";

$staffTable = new StaffTable();
$taskTable = new TaskTable();

$server = new Server("127.0.0.1", 9501);

$server->set([
    "worker_num" => 3,
    "task_worker_num" => 2,
]);

$server->on("WorkerStart", function (Server $server, int $workerId) use ($staffTable): void {
    $staffMember = StaffHandler::createFromWorker(
        workerId: $workerId,
        isTaskWorker: $server->taskworker
    );

    $staffTable->add($staffMember);

    echo "{$staffMember->type} {$workerId} gestartet. PID={$staffMember->pid}" . PHP_EOL;
});

$server->on("Request", function (Request $request, Response $response) use ($server, $staffTable, $taskTable): void {
    RouteHandler::handle(
        server: $server,
        request: $request,
        response: $response,
        staffTable: $staffTable,
        taskTable: $taskTable
    );
});

$server->on("Task", function (Server $server, int $taskId, int $srcWorkerId, mixed $data) use ($taskTable): void {
    $traceId = $data["trace_id"];

    $taskMember = $taskTable->find($traceId);

    if ($taskMember === null) {
        echo "Task nicht gefunden: {$traceId}" . PHP_EOL;
        return;
    }

    $runningTask = TaskHandler::markRunning(
        taskMember: $taskMember,
        taskWorker: $server->worker_id
    );

    $taskTable->update($runningTask);

    echo "Koch {$server->worker_id} startet {$runningTask->meal}" . PHP_EOL;

    sleep($runningTask->duration);

    $finishedTask = TaskHandler::markFinished(
        taskMember: $runningTask,
        durationMs: $runningTask->duration * 1000
    );

    $taskTable->update($finishedTask);

    echo "Koch {$server->worker_id} beendet {$finishedTask->meal}" . PHP_EOL;
});

$server->on("Finish", function (Server $server, int $taskId, mixed $data): void {
    echo "Finish task_id={$taskId}" . PHP_EOL;
});

$server->start();