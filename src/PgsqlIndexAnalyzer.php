<?php

declare(strict_types=1);

namespace Sapronovps\PgsqlIndexAnalyzer;

use Sapronovps\PgsqlIndexAnalyzer\Connection\ConnectionInterface;
use Sapronovps\PgsqlIndexAnalyzer\Dto\IndexDto;
use Sapronovps\PgsqlIndexAnalyzer\Service\IndexService;

/**
 * Pgsql index analyzer.
 */
final class PgsqlIndexAnalyzer implements PgsqlIndexAnalyzerInterface
{
    private IndexService $service;

    public function __construct(ConnectionInterface $connection)
    {
        $this->service = new IndexService($connection);
    }

    /**
     * Get all indexes by tables.
     *
     * @param array<string> $tables
     * @return array<IndexDto>
     */
    public function allIndexesByTables(array $tables): array
    {
        return $this->service->allIndexesByTables($tables);
    }

    /**
     * Get unused indexes by tables.
     *
     * @param array<string> $tables
     * @return array<IndexDto>
     */
    public function unusedIndexesByTables(array $tables): array
    {
        return $this->service->unusedIndexesByTables($tables);
    }

    /**
     * Get overlapping indexes by tables.
     *
     * @param array<string> $tables
     * @return array<IndexDto>
     */
    public function overlappingIndexesByTables(array $tables): array
    {
        return $this->service->overlappingIndexesByTables($tables);
    }

    /**
     * Get indexes contains in other indexes by tables.
     *
     * @param array<string> $tables
     * @return array<IndexDto>
     */
    public function indexesContainsInOtherIndexesByTables(array $tables): array
    {
        return $this->service->indexesContainsInOtherIndexesByTables($tables);
    }
}