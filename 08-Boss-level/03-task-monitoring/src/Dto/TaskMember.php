<?php

declare(strict_types=1);

namespace App\Dto;

final readonly class TaskMember
{
    public function __construct(
        public string $traceId,
        public string $meal,
        public int $duration,
        public string $status,
        public int $requestWorker,
        public int $taskWorker,
        public string $createdAt,
        public string $startedAt,
        public string $finishedAt,
        public float $waitMs,
        public float $durationMs,
    ) {
    }

    public function toTableRow(): array
    {
        return [
            "meal" => $this->meal,
            "duration" => $this->duration,
            "status" => $this->status,
            "request_worker" => $this->requestWorker,
            "task_worker" => $this->taskWorker,
            "created_at" => $this->createdAt,
            "started_at" => $this->startedAt,
            "finished_at" => $this->finishedAt,
            "wait_ms" => $this->waitMs,
            "duration_ms" => $this->durationMs,
        ];
    }

    public function toResponseArray(): array
    {
        return [
            "trace_id" => $this->traceId,
            "meal" => $this->meal,
            "duration" => $this->duration,
            "status" => $this->status,
            "request_worker" => $this->requestWorker,
            "task_worker" => $this->taskWorker,
            "created_at" => $this->createdAt,
            "started_at" => $this->startedAt,
            "finished_at" => $this->finishedAt,
            "wait_ms" => $this->waitMs,
            "duration_ms" => $this->durationMs,
        ];
    }
}