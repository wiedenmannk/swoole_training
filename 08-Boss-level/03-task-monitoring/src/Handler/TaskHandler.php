<?php

declare(strict_types=1);

namespace App\Handler;

use App\Dto\TaskMember;

final class TaskHandler
{
    public static function createWaitingTask(
        string $meal,
        int $duration,
        int $requestWorker
    ): TaskMember {
        return new TaskMember(
            traceId: uniqid("task-", true),
            meal: $meal,
            duration: $duration,
            status: "waiting",
            requestWorker: $requestWorker,
            taskWorker: -1,
            createdAt: date("Y-m-d H:i:s"),
            startedAt: "",
            finishedAt: "",
            waitMs: 0,
            durationMs: 0,
        );
    }

    public static function markRunning(
        TaskMember $taskMember,
        int $taskWorker
    ): TaskMember {
        $startedAt = date("Y-m-d H:i:s");

        return new TaskMember(
            traceId: $taskMember->traceId,
            meal: $taskMember->meal,
            duration: $taskMember->duration,
            status: "running",
            requestWorker: $taskMember->requestWorker,
            taskWorker: $taskWorker,
            createdAt: $taskMember->createdAt,
            startedAt: $startedAt,
            finishedAt: "",
            waitMs: 0,
            durationMs: 0,
        );
    }

    public static function markFinished(
        TaskMember $taskMember,
        float $durationMs
    ): TaskMember {
        return new TaskMember(
            traceId: $taskMember->traceId,
            meal: $taskMember->meal,
            duration: $taskMember->duration,
            status: "finished",
            requestWorker: $taskMember->requestWorker,
            taskWorker: $taskMember->taskWorker,
            createdAt: $taskMember->createdAt,
            startedAt: $taskMember->startedAt,
            finishedAt: date("Y-m-d H:i:s"),
            waitMs: $taskMember->waitMs,
            durationMs: $durationMs,
        );
    }
}