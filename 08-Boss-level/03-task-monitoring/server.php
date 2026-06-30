<?php

declare(strict_types=1);

date_default_timezone_set("Europe/Berlin");

use App\Handler\RouteHandler;
use App\Handler\StaffHandler;
use App\Table\StaffTable;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

require_once __DIR__ . "/src/Dto/StaffMember.php";
require_once __DIR__ . "/src/Handler/StaffHandler.php";
require_once __DIR__ . "/src/Table/StaffTable.php";
require_once __DIR__ . "/src/Response/JsonResponse.php";
require_once __DIR__ . "/src/Handler/RouteHandler.php";

$staffTable = new StaffTable();

$server = new Server("127.0.0.1", 9501);

$server->set([
    "worker_num" => 2
]);

$server->on("WorkerStart", function (Server $server, int $workerId) use ($staffTable): void {
    $staffMember = StaffHandler::createFromWorker(
        workerId: $workerId,
        isTaskWorker: $server->taskworker
    );

    $staffTable->add($staffMember);

    echo "{$staffMember->type} {$workerId} gestartet. PID={$staffMember->pid}" . PHP_EOL;
});

$server->on("Request", function (Request $request, Response $response) use ($server, $staffTable): void {
    RouteHandler::handle(
        server: $server,
        request: $request,
        response: $response,
        staffTable: $staffTable
    );
});

$server->start();