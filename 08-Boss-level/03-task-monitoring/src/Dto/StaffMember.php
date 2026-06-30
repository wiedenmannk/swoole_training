<?php

declare(strict_types=1);
namespace App\Dto;
final readonly class StaffMember
{
    public function __construct(
        public string $key,
        public string $type,
        public int $pid,
        public string $status,
        public string $startedAt,
    ) {
    }

    public function toTableRow(): array
    {
        return [
            "type" => $this->type,
            "pid" => $this->pid,
            "status" => $this->status,
            "started_at" => $this->startedAt,
        ];
    }

    public function toResponseArray(): array
    {
        return [
            "key" => $this->key,
            "type" => $this->type,
            "pid" => $this->pid,
            "status" => $this->status,
            "started_at" => $this->startedAt,
        ];
    }
}