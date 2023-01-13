<?php

declare(strict_types=1);

namespace Sapronovps\PgsqlIndexAnalyzer;

use Sapronovps\PgsqlIndexAnalyzer\Dto\IndexDto;

interface PgsqlIndexAnalyzerInterface
{
    /**
     * Get all indexes by tables.
     *
     * @param array<string> $tables
     * @return array<IndexDto>
     */
    public function allIndexesByTables(array $tables): array;

    /**
     * Get unused indexes by tables.
     *
     * @param array<string> $tables
     * @return array<IndexDto>
     */
    public function unusedIndexesByTables(array $tables): array;

    /**
     * Get overlapping indexes by tables.
     *
     * @param array<string> $tables
     * @return array<IndexDto>
     */
    public function overlappingIndexesByTables(array $tables): array;

    /**
     * Get indexes contains in other indexes by tables.
     *
     * @param array<string> $tables
     * @return array<IndexDto>
     */
    public function indexesContainsInOtherIndexesByTables(array $tables): array;
}