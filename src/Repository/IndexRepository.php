<?php

namespace Sapronovps\PgsqlIndexAnalyzer\Repository;

use Sapronovps\PgsqlIndexAnalyzer\Connection\ConnectionInterface;
use Sapronovps\PgsqlIndexAnalyzer\Schema\Schema;

/**
 * Index repository.
 */
final class IndexRepository
{
    private Schema $schema;

    public function __construct(private ConnectionInterface $connection)
    {
        $this->schema = new Schema();
    }

    /**
     * Get indexes by tables.
     *
     * @param array<string> $tables
     * @return array<mixed>
     */
    public function indexesByTables(array $tables): array
    {
        $sql = $this->schema->indexesByTables($tables);

        return $this->connection->all($sql);
    }
}