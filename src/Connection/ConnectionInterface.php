<?php

declare(strict_types=1);

namespace Sapronovps\PgsqlIndexAnalyzer\Connection;

/**
 * Connection interface.
 */
interface ConnectionInterface
{
    /**
     * Open the connection to Postgresql.
     */
    public function connect(): void;

    /**
     * Closes the connection to Postgresql.
     */
    public function disconnect(): void;

    /**
     * Execute SQL and return one row.
     *
     * @param string $sql
     * @return array<mixed>
     */
    public function one(string $sql): array;

    /**
     * Execute SQL and return all rows.
     *
     * @param string $sql
     * @return array<mixed>
     */
    public function all(string $sql): array;
}