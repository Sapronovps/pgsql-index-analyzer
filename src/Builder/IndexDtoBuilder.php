<?php

declare(strict_types=1);

namespace Sapronovps\PgsqlIndexAnalyzer\Builder;

use Sapronovps\PgsqlIndexAnalyzer\Dto\IndexDto;

/**
 * IndexDto builder.
 */
final class IndexDtoBuilder
{
    /**
     * Create a IndexDto[] by array $indexes.
     *
     * @param array<string, mixed> $indexes
     * @return array<IndexDto>
     */
    public function createIndexDtos(array $indexes): array
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

    /**
     * Group IndexDto[] by table.
     *
     * @param array<IndexDto> $indexDtos
     * @return array<string, array<IndexDto>>
     */
    public function groupByTable(array $indexDtos): array
    {
        $groupIndexDtosByTable = [];

        foreach ($indexDtos as $indexesDto) {
            $groupIndexDtosByTable[$indexesDto->getTableName()][] = $indexesDto;
        }

        return $groupIndexDtosByTable;
    }
}