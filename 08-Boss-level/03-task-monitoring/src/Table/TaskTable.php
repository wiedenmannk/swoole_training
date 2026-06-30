<?php

declare(strict_types=1);

namespace App\Table;

use App\Dto\TaskMember;
use Swoole\Table;

final class TaskTable
{
    private Table $table;

    public function __construct()
    {
        $this->table = new Table(1024);

        $this->table->column("meal", Table::TYPE_STRING, 100);
        $this->table->column("duration", Table::TYPE_INT);
        $this->table->column("status", Table::TYPE_STRING, 20);
        $this->table->column("request_worker", Table::TYPE_INT);
        $this->table->column("task_worker", Table::TYPE_INT);
        $this->table->column("created_at", Table::TYPE_STRING, 30);
        $this->table->column("started_at", Table::TYPE_STRING, 30);
        $this->table->column("finished_at", Table::TYPE_STRING, 30);
        $this->table->column("wait_ms", Table::TYPE_FLOAT);
        $this->table->column("duration_ms", Table::TYPE_FLOAT);

        $this->table->create();
    }

    public function add(TaskMember $taskMember): void
    {
        $this->table->set(
            $taskMember->traceId,
            $taskMember->toTableRow()
        );
    }

    public function update(TaskMember $taskMember): void
    {
        $this->add($taskMember);
    }

    /**
     * @return TaskMember[]
     */
    public function findAll(): array
    {
        return $this->findByStatus(null);
    }

    /**
     * @return TaskMember[]
     */
    public function findByStatus(?string $status): array
    {
        $tasks = [];

        foreach ($this->table as $traceId => $row) {
            /** @var array{
             *     meal: string,
             *     duration: int,
             *     status: string,
             *     request_worker: int,
             *     task_worker: int,
             *     created_at: string,
             *     started_at: string,
             *     finished_at: string,
             *     wait_ms: float,
             *     duration_ms: float
             * } $row
             */

            if ($status !== null && $row["status"] !== $status) {
                continue;
            }

            $tasks[] = new TaskMember(
                traceId: (string) $traceId,
                meal: (string) $row["meal"],
                duration: (int) $row["duration"],
                status: (string) $row["status"],
                requestWorker: (int) $row["request_worker"],
                taskWorker: (int) $row["task_worker"],
                createdAt: (string) $row["created_at"],
                startedAt: (string) $row["started_at"],
                finishedAt: (string) $row["finished_at"],
                waitMs: (float) $row["wait_ms"],
                durationMs: (float) $row["duration_ms"],
            );
        }

        return $tasks;
    }

    public function find(string $traceId): ?TaskMember
    {
        $row = $this->table->get($traceId);

        if ($row === false) {
            return null;
        }

        /** @var array{
         *     meal: string,
         *     duration: int,
         *     status: string,
         *     request_worker: int,
         *     task_worker: int,
         *     created_at: string,
         *     started_at: string,
         *     finished_at: string,
         *     wait_ms: float,
         *     duration_ms: float
         * } $row
         */

        return new TaskMember(
            traceId: $traceId,
            meal: (string) $row["meal"],
            duration: (int) $row["duration"],
            status: (string) $row["status"],
            requestWorker: (int) $row["request_worker"],
            taskWorker: (int) $row["task_worker"],
            createdAt: (string) $row["created_at"],
            startedAt: (string) $row["started_at"],
            finishedAt: (string) $row["finished_at"],
            waitMs: (float) $row["wait_ms"],
            durationMs: (float) $row["duration_ms"],
        );
    }
}