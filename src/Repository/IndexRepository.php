<?php

namespace Sapronovps\PgsqlIndexAnalyzer\Repository;

use Sapronovps\PgsqlIndexAnalyzer\Connection\ConnectionInterface;
use Sapronovps\PgsqlIndexAnalyzer\Schema\Schema;

final class IndexRepository
{
    private Schema $schema;

    public function __construct(private ConnectionInterface $connection)
    {
        $this->schema = new Schema();
    }

    public function tableInfo(string $table)
    {
        $sql = $this->schema->tableInfo($table);

        return $this->connection->one($sql);
    }

    public function unusedIndexesByTable(array $tables): array
    {
        $sql = $this->schema->indexesByTables($tables);

        return $this->connection->all($sql);
    }

    public function indexesByTables(array $tables): array
    {
        $sql = $this->schema->indexesByTables($tables);

        return $this->connection->all($sql);
    }
}