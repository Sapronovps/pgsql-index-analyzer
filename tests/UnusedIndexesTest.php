<?php

declare(strict_types=1);

namespace Sapronovps\Tests;

use Sapronovps\PgsqlIndexAnalyzer\Dto\IndexDto;
use Sapronovps\PgsqlIndexAnalyzer\Exception\TableNotExistException;
use Sapronovps\PgsqlIndexAnalyzer\PgsqlIndexAnalyzer;
use Sapronovps\Tests\Mock\ConnectionMock;

final class UnusedIndexesTest extends BaseTests
{
    /**
     * @return void
     * @throws TableNotExistException
     */
    public function testWhenIndexScan0ByTable(): void
    {
        $table = 'table1';
        $connection = new ConnectionMock();
        $data =  [
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
        $connection->setData([$data]);

        $pgsqlIndexAnalyzer = new PgsqlIndexAnalyzer($connection);/** @var IndexDto[] $indexDtos */
        $indexDtos = $pgsqlIndexAnalyzer->unusedIndexesByTable($table);

        $this->assertNotEmpty($indexDtos, 'Method unused indexes by table is broken.');

        $this->checkEqualsIndexDto($indexDtos[0], $data);

    }

    /**
     * @return void
     * @throws TableNotExistException
     */
    public function testWhenIndexScanNot0ByTable(): void
    {
        $table = 'table1';
        $connection = new ConnectionMock();
        $data = [
            'table_name' => $table,
            'index_name' => 'ix_table1_field1',
            'columns' => 'field1',
            'index_size_pretty' => '6304 MB',
            'index_size' => 6609903616,
            'index_relid' => 47267,
            'relid' => 42414,
            'index_scan' => 2341,
            'index_tup_read' => 0,
            'index_tup_fetch' => 0,
        ];
        $connection->setData([$data]);

        $pgsqlIndexAnalyzer = new PgsqlIndexAnalyzer($connection);/** @var IndexDto[] $indexDtos */
        $indexDtos = $pgsqlIndexAnalyzer->unusedIndexesByTable($table);

        $this->assertEmpty($indexDtos, 'Method unused indexes by table is broken.');
    }

    /**
     * @return void
     */
    public function testUnusedIndexesByTables(): void
    {
        $connection = new ConnectionMock();
        $data1 = [
            'table_name' => 'table1',
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
            'table_name' => 'table2',
            'index_name' => 'ix_table2_field1',
            'columns' => 'field1',
            'index_size_pretty' => '5868 MB',
            'index_size' => 6153527296,
            'index_relid' => 47266,
            'relid' => 42414,
            'index_scan' => 0,
            'index_tup_read' => 0,
            'index_tup_fetch' => 0,
        ];
        $data3 = [
            'table_name' => 'table3',
            'index_name' => 'ix_table3_field1',
            'columns' => 'field1',
            'index_size_pretty' => '5869 MB',
            'index_size' => 6154526720,
            'index_relid' => 46039,
            'relid' => 42414,
            'index_scan' => 86348,
            'index_tup_read' => 0,
            'index_tup_fetch' => 0,
        ];
        $connection->setData([$data1, $data2, $data3]);

        $tables = ['table1', 'table2'];
        $pgsqlIndexAnalyzer = new PgsqlIndexAnalyzer($connection);
        /** @var IndexDto[] $indexDtos */
        $indexDtos = $pgsqlIndexAnalyzer->unusedIndexesByTables($tables);

        $this->assertNotEmpty($indexDtos, 'Method unused indexes by table is broken.');
        $this->assertCount(2, $indexDtos, 'Must be 2 index dtos.');

        $this->checkEqualsIndexDto($indexDtos[0], $data1);
        $this->checkEqualsIndexDto($indexDtos[1], $data2);
    }
}