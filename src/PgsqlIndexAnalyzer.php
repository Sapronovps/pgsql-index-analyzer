<?php

declare(strict_types=1);

namespace Sapronovps\PgsqlIndexAnalyzer;

use Sapronovps\PgsqlIndexAnalyzer\Connection\ConnectionInterface;
use Sapronovps\PgsqlIndexAnalyzer\Service\IndexService;

final class PgsqlIndexAnalyzer
{
    private IndexService $service;

    public function __construct(ConnectionInterface $connection)
    {
        $this->service = new IndexService($connection);
    }

    /**
     * @param string $table
     * @return array
     * @throws Exception\TableNotExistException
     */
    public function unusedIndexesByTable(string $table): array
    {
        return $this->service->unusedIndexesByTable($table);
    }

    /**
     * @param array $tables
     * @return array
     */
    public function unusedIndexesByTables(array $tables): array
    {
        return $this->service->unusedIndexesByTables($tables);
    }

    /**
     * @param string $table
     * @return array
     * @throws Exception\TableNotExistException
     */
    public function overlappingIndexesByTable(string $table): array
    {
        return $this->service->overlappingIndexesByTable($table);
    }

    /**
     * @param array $tables
     * @return array
     */
    public function overlappingIndexesByTables(array $tables): array
    {
        return $this->service->overlappingIndexesByTables($tables);
    }

    /**
     * @param string $table
     * @return array
     * @throws Exception\TableNotExistException
     */
    public function indexesContainsInOtherIndexesByTable(string $table): array
    {
        return $this->service->indexesContainsInOtherIndexesByTable($table);
    }

    /**
     * @param array $tables
     * @return array
     */
    public function indexesContainsInOtherIndexesByTables(array $tables): array
    {
        return $this->service->indexesContainsInOtherIndexesByTables($tables);
    }
}