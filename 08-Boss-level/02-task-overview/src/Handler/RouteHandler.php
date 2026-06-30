<?php

declare(strict_types=1);

namespace App\Handler;

use App\Dto\StaffMember;
use App\Response\JsonResponse;
use App\Table\StaffTable;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

final class RouteHandler
{
    public static function handle(
        Server $server,
        Request $request,
        Response $response,
        StaffTable $staffTable
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
            "Boss Level 02 - Task Overview\n\n" .
            "Routes:\n" .
            "/status\n" .
            "/workers\n" .
            "/cooks\n"
        );
    }
}