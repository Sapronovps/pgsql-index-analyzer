<?php

declare(strict_types=1);

namespace Sapronovps\Tests;

use Sapronovps\PgsqlIndexAnalyzer\Dto\IndexDto;
use Sapronovps\PgsqlIndexAnalyzer\PgsqlIndexAnalyzer;
use Sapronovps\Tests\Mock\ConnectionMock;

class IndexesContainsInOtherIndexesTest extends BaseTests
{
    public function testWhenHasIndexesInOtherIndexesByTable(): void
    {
        $table = 'table1';
        $connection = new ConnectionMock();
        $data1 = [
            'table_name' => $table,
            'index_name' => 'ix_table1_field1',
            'columns' => 'field1',
            'index_size_pretty' => '6304 MB',
            'index_size' => 6609903616,
            'index_relid' => 47267,
            'relid' => 42414,
            'index_scan' => 0,
            'index_tup_read' => 0,
            'index_tup_fetch' => 0,
        ];
        $data2 = [
            'table_name' => $table,
            'index_name' => 'ix_table1_field1_field2',
            'columns' => 'field2, field1',
            'index_size_pretty' => '6304 MB',
            'index_size' => 6609903616,
            'index_relid' => 47268,
            'relid' => 42414,
            'index_scan' => 0,
            'index_tup_read' => 0,
            'index_tup_fetch' => 0,
        ];

        $connection->setData([$data1, $data2]);
        $pgsqlIndexAnalyzer = new PgsqlIndexAnalyzer($connection);
        /** @var IndexDto[] $indexDtos */
        $indexDtos = $pgsqlIndexAnalyzer->overlappingIndexesByTable($table);

        $this->assertNotEmpty($indexDtos, 'Method unused indexes by table is broken.');

        $this->checkEqualsIndexDto($indexDtos[0], $data1);
    }
}