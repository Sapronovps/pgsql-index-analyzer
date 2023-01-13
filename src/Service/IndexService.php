<?php

declare(strict_types=1);

namespace Sapronovps\PgsqlIndexAnalyzer\Service;

use Sapronovps\PgsqlIndexAnalyzer\Builder\IndexDtoBuilder;
use Sapronovps\PgsqlIndexAnalyzer\Connection\ConnectionInterface;
use Sapronovps\PgsqlIndexAnalyzer\Dto\IndexDto;
use Sapronovps\PgsqlIndexAnalyzer\Repository\IndexRepository;

/**
 * Index service.
 */
final class IndexService
{
    private IndexRepository $repository;

    private IndexDtoBuilder $builder;

    public function __construct(ConnectionInterface $connection)
    {
        $this->repository = new IndexRepository($connection);
        $this->builder = new IndexDtoBuilder();
    }

    /**
     * Get all indexes by tables.
     *
     * @param array<string> $tables
     * @return array<IndexDto>
     */
    public function allIndexesByTables(array $tables): array
    {
        $indexes = $this->repository->indexesByTables($tables);

        return $this->builder->createIndexDtos($indexes);
    }

    /**
     * Get unused indexes by tables.
     *
     * @param array<string> $tables
     * @return array<IndexDto>
     */
    public function unusedIndexesByTables(array $tables): array
    {
        $unusedIndexes = [];
        $indexes = $this->repository->indexesByTables($tables);
        $indexesDtos = $this->builder->createIndexDtos($indexes);

        foreach ($indexesDtos as $indexesDto) {
            if ($indexesDto->getIndexScan() === 0) {
                $unusedIndexes[] = $indexesDto;
            }
        }

        return $unusedIndexes;
    }

    /**
     * @param array<string> $tables
     * @return array<IndexDto>
     */
    public function overlappingIndexesByTables(array $tables): array
    {
        $overlappingIndexes = [];
        $indexes = $this->repository->indexesByTables($tables);
        $indexesDtos = $this->builder->createIndexDtos($indexes);
        $groupIndexDtosByTable = $this->builder->groupByTable($indexesDtos);

        foreach ($groupIndexDtosByTable as $indexes) {
            foreach ($indexes as $index) {
                $columns = explode(', ', $index->getColumns());
                $count = count($columns);

                foreach ($indexes as $index2) {
                    if ($index->getIndexName() === $index2->getIndexName()) {
                        continue;
                    }
                    $columns2 = explode(', ', $index2->getColumns());

                    $isOverlappingIndex = true;
                    for ($i = 0; $i < $count; $i++) {
                        if (!isset($columns2[$i]) || $columns[$i] !== $columns2[$i]) {
                            $isOverlappingIndex = false;
                            break;
                        }
                    }

                    if ($isOverlappingIndex) {
                        $overlappingIndexes[] = $index;
                    }
                }
            }
        }

        return $overlappingIndexes;
    }

    /**
     * @param array<string> $tables
     * @return array<IndexDto>
     */
    public function indexesContainsInOtherIndexesByTables(array $tables): array
    {
        $indexesContainsInOtherIndexes = [];
        $indexes = $this->repository->indexesByTables($tables);
        $indexesDtos = $this->builder->createIndexDtos($indexes);
        $groupIndexDtosByTable = $this->builder->groupByTable($indexesDtos);

        foreach ($groupIndexDtosByTable as $indexes) {
            foreach ($indexes as $index) {
                $columns = explode(', ', $index->getColumns());

                foreach ($indexes as $index2) {
                    if ($index->getIndexName() === $index2->getIndexName()) {
                        continue;
                    }
                    $columns2 = explode(', ', $index2->getColumns());

                    $isFind = false;
                    foreach ($columns as $column) {
                        $isFind = false;
                        foreach ($columns2 as $column2) {
                            if ($column === $column2) {
                                $isFind = true;
                                break;
                            }
                        }
                        if ($isFind === false) {
                            break;
                        }
                    }

                    if ($isFind === true) {
                        $indexesContainsInOtherIndexes[] = $index;
                    }
                }
            }
        }

        return $indexesContainsInOtherIndexes;
    }
}