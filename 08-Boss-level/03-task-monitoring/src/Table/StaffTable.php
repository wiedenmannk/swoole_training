<?php

declare(strict_types=1);

namespace App\Table;

use App\Dto\StaffMember;
use Swoole\Table;

final class StaffTable
{
    private Table $table;

    public function __construct()
    {
        $this->table = new Table(64);

        $this->table->column("type", Table::TYPE_STRING, 20);
        $this->table->column("pid", Table::TYPE_INT);
        $this->table->column("status", Table::TYPE_STRING, 20);
        $this->table->column("started_at", Table::TYPE_STRING, 30);

        $this->table->create();
    }

    public function add(StaffMember $staffMember): void
    {
        $this->table->set(
            $staffMember->key,
            $staffMember->toTableRow()
        );
    }

    /**
     * @return StaffMember[]
     */
    public function findAll(): array
    {
        return $this->findByType(null);
    }

    /**
     * @return StaffMember[]
     */
    public function findByType(?string $type): array
    {
        $members = [];

        foreach ($this->table as $key => $row) {
            /** @var array{
             *     type: string,
             *     pid: int,
             *     status: string,
             *     started_at: string
             * } $row
             */

            if ($type !== null && $row["type"] !== $type) {
                continue;
            }

            $members[] = new StaffMember(
                key: (string) $key,
                type: (string) $row["type"],
                pid: (int) $row["pid"],
                status: (string) $row["status"],
                startedAt: (string) $row["started_at"],
            );
        }

        return $members;
    }
}