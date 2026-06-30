<?php

declare(strict_types=1);

namespace App\Handler;

use App\Dto\StaffMember;
use App\Response\JsonResponse;
use App\Table\StaffTable;
use App\Dto\TaskMember;
use App\Handler\TaskHandler;
use App\Table\TaskTable;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

final class RouteHandler
{
    public static function handle(
        Server $server,
        Request $request,
        Response $response,
        StaffTable $staffTable,
        TaskTable $taskTable
    ): void {
        $path = $request->server["request_uri"] ?? "/";

        switch ($path) {

            case "/status":
                self::status($server, $response);
                return;

            case "/workers":
                self::workers($response, $staffTable);
                return;

            case "/cooks":
                self::cooks($response, $staffTable);
                return;

            case "/order":
                self::order($server, $request, $response, $taskTable);
                return;

            case "/tasks":
                self::tasks($response, $taskTable, null);
                return;

            case "/tasks/waiting":
                self::tasks($response, $taskTable, "waiting");
                return;

            case "/tasks/running":
                self::tasks($response, $taskTable, "running");
                return;

            case "/tasks/finished":
                self::tasks($response, $taskTable, "finished");
                return;

            default:
                self::home($response);
                return;
        }
    }
    private static function status(
        Server $server,
        Response $response
    ): void {

        $memory = [
            "usage_mb" => round(memory_get_usage(true) / 1024 / 1024, 2),
            "peak_mb" => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
        ];

        JsonResponse::send($response, [
            "status" => "ok",
            "data" => [
                "server" => "running",
                "worker_id" => $server->worker_id,
                "time" => date("Y-m-d H:i:s"),
                "memory" => $memory,
            ],
        ]);
    }

    private static function workers(
        Response $response,
        StaffTable $staffTable
    ): void {

        $members = [];
        $staffMembers = $staffTable->findByType("waiter");

        /** @var StaffMember $staffMember */
        foreach ($staffMembers as $staffMember) {
            $members[] = $staffMember->toResponseArray();
        }

        JsonResponse::send($response, [
            "status" => "ok",
            "data" => $members,
        ]);
    }

    private static function cooks(
        Response $response,
        StaffTable $staffTable
    ): void {

        $members = [];
        $staffMembers = $staffTable->findByType("cook");

        /** @var StaffMember $staffMember */
        foreach ($staffMembers as $staffMember) {
            $members[] = $staffMember->toResponseArray();
        }

        JsonResponse::send($response, [
            "status" => "ok",
            "data" => $members,
        ]);
    }

    private static function home(Response $response): void
    {
        $response->end(
            "Boss Level 03 - Task Monitoring\n\n" .
            "Routes:\n" .
            "/status\n" .
            "/workers\n" .
            "/cooks\n" .
            "/order?meal=Pizza&duration=5\n" .
            "/tasks\n" .
            "/tasks/waiting\n" .
            "/tasks/running\n" .
            "/tasks/finished\n"
        );
    }
    private static function order(
        Server $server,
        Request $request,
        Response $response,
        TaskTable $taskTable
    ): void {
        $meal = $request->get["meal"] ?? "Pizza";
        $duration = (int) ($request->get["duration"] ?? 5);

        $taskMember = TaskHandler::createWaitingTask(
            meal: $meal,
            duration: $duration,
            requestWorker: $server->worker_id
        );

        $taskTable->add($taskMember);

        $server->task([
            "trace_id" => $taskMember->traceId,
        ]);

        JsonResponse::send($response, [
            "status" => "ok",
            "data" => [
                "message" => "Bestellung angenommen",
                "trace_id" => $taskMember->traceId,
                "meal" => $taskMember->meal,
                "duration" => $taskMember->duration,
                "request_worker" => $taskMember->requestWorker,
            ],
        ]);
    }

    private static function tasks(
        Response $response,
        TaskTable $taskTable,
        ?string $status
    ): void {
        $taskRows = [];

        $tasks = $taskTable->findByStatus($status);

        /** @var TaskMember $task */
        foreach ($tasks as $task) {
            $taskRows[] = $task->toResponseArray();
        }

        JsonResponse::send($response, [
            "status" => "ok",
            "data" => $taskRows,
        ]);
    }
}