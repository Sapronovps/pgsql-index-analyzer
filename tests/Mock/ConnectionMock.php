<?php

namespace Sapronovps\Tests\Mock;

use Sapronovps\PgsqlIndexAnalyzer\Connection\ConnectionInterface;

class ConnectionMock implements ConnectionInterface
{
    private array $data = [];

    public function connect(): void
    {

    }

    public function disconnect(): void
    {

    }

    public function all(string $sql): array
    {
        return $this->data;
    }

    public function one(string $sql): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }
}