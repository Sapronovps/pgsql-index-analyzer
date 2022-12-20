<?php

declare(strict_types=1);

namespace Sapronovps\PgsqlIndexAnalyzer\Service;

use Sapronovps\PgsqlIndexAnalyzer\Connection\ConnectionInterface;
use Sapronovps\PgsqlIndexAnalyzer\Dto\IndexDto;
use Sapronovps\PgsqlIndexAnalyzer\Exception\TableNotExistException;
use Sapronovps\PgsqlIndexAnalyzer\Repository\IndexRepository;

final class IndexService
{
    private IndexRepository $repository;

    public function __construct(ConnectionInterface $connection)
    {
        $this->repository = new IndexRepository($connection);
    }

    /**
     * @param string $table
     * @return array
     * @throws TableNotExistException
     */
    public function unusedIndexesByTable(string $table): array
    {
        $this->tableExistOrFail($table);

        return $this->unusedIndexesByTables([$table]);
    }

    /**
     * @param array $tables
     * @return array
     */
    public function unusedIndexesByTables(array $tables): array
    {
        $unusedIndexes = [];
        $indexes = $this->repository->indexesByTables($tables);
        $indexesDtos = $this->getIndexDtos($indexes);

        foreach ($indexesDtos as $indexesDto) {
            if ($indexesDto->getIndexScan() === 0) {
                $unusedIndexes[] = $indexesDto;
            }
        }

        return $unusedIndexes;
    }

    /**
     * @param string $table
     * @return array
     * @throws TableNotExistException
     */
    public function overlappingIndexesByTable(string $table): array
    {
        $this->tableExistOrFail($table);

        return $this->overlappingIndexesByTables([$table]);
    }

    /**
     * @param array $tables
     * @return array
     */
    public function overlappingIndexesByTables(array $tables): array
    {
        $overlappingIndexes = [];
        $indexes = $this->repository->indexesByTables($tables);
        $indexesDtos = $this->getIndexDtos($indexes);
        $groupIndexDtosByTable = $this->groupIndexDtosByTable($indexesDtos);

        /**
         * @var IndexDto $index
         * @var IndexDto $index2
         */
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
     * @param string $table
     * @return array
     * @throws TableNotExistException
     */
    public function indexesContainsInOtherIndexesByTable(string $table): array
    {
        $this->tableExistOrFail($table);

        return $this->indexesContainsInOtherIndexesByTables([$table]);
    }

    /**
     * @param array $tables
     * @return array
     */
    public function indexesContainsInOtherIndexesByTables(array $tables): array
    {
        $indexesContainsInOtherIndexes = [];
        $indexes = $this->repository->indexesByTables($tables);
        $indexesDtos = $this->getIndexDtos($indexes);
        $groupIndexDtosByTable = $this->groupIndexDtosByTable($indexesDtos);

        /**
         * @var IndexDto $index
         * @var IndexDto $index2
         */
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

    /**
     * Fail if table is not exist.
     *
     * @param string $table
     * @return void
     * @throws TableNotExistException
     */
    private function tableExistOrFail(string $table): void
    {
        $tableInfo = $this->repository->tableInfo($table);

        if ($tableInfo === false) {
            throw new TableNotExistException("Table with name '$table' not exist.");
        }
    }

    /**
     * @param array $indexes
     * @return IndexDto[]
     */
    private function getIndexDtos(array $indexes): array
    {
        $indexesDtos = [];

        foreach ($indexes as $index) {
            $indexDto = (new IndexDto())
                ->setTableName($index['table_name'])
                ->setIndexName($index['index_name'])
                ->setColumns($index['columns'])
                ->setIndexSizePretty($index['index_size_pretty'])
                ->setIndexSize($index['index_size'])
                ->setIndexRelid($index['index_relid'])
                ->setRelid($index['relid'])
                ->setIndexScan($index['index_scan'])
                ->setIndexTupRead($index['index_tup_read'])
                ->setIndexTupFetch($index['index_tup_fetch']);

            $indexesDtos[] = $indexDto;
        }

        return $indexesDtos;
    }

    private function groupIndexDtosByTable(array $indexDtos): array
    {
        $groupIndexDtosByTable = [];

        foreach ($indexDtos as $indexesDto) {
            $groupIndexDtosByTable[$indexesDto->getTableName()][] = $indexesDto;
        }

        return $groupIndexDtosByTable;
    }
}