<?php

declare(strict_types=1);

namespace Sapronovps\PgsqlIndexAnalyzer\Connection;

interface ConnectionInterface
{
    /**
     * Open the connection to Postgresql.
     */
    public function connect();

    /**
     * Closes the connection to Postgresql.
     */
    public function disconnect();

    /**
     * Execute SQL and return one row.
     */
    public function one(string $sql);

    /**
     * Execute SQL and return all rows.
     */
    public function all(string $sql);
}