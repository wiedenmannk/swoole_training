<?php

declare(strict_types=1);

namespace App\Handler;

use App\Dto\StaffMember;

final class StaffHandler
{
    public static function createFromWorker(
        int $workerId,
        bool $isTaskWorker
    ): StaffMember {
        $type = $isTaskWorker ? "cook" : "waiter";
        $key = $isTaskWorker ? "cook-{$workerId}" : "worker-{$workerId}";

        return new StaffMember(
            key: $key,
            type: $type,
            pid: posix_getpid(),
            status: "idle",
            startedAt: date("Y-m-d H:i:s"),
        );
    }
}