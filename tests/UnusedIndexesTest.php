<?php

declare(strict_types=1);

namespace Sapronovps\Tests;

use Sapronovps\PgsqlIndexAnalyzer\PgsqlIndexAnalyzer;
use Sapronovps\Tests\Mock\ConnectionMock;

/**
 * Test Unused Indexes
 */
final class UnusedIndexesTest extends BaseTests
{
    private string $table = 'table1';

    /**
     * @return void
     */
    public function testWhenIndexScan0ByTables(): void
    {
        $connection = new ConnectionMock();
        $data = [
            'table_name' => $this->table,
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

        $pgsqlIndexAnalyzer = new PgsqlIndexAnalyzer($connection);
        $indexDtos = $pgsqlIndexAnalyzer->unusedIndexesByTables([$this->table]);

        $this->assertNotEmpty($indexDtos, 'Method unused indexes by table is broken.');

        $this->checkEqualsIndexDto($indexDtos[0], $data);
    }

    /**
     * @return void
     */
    public function testWhenIndexScanNot0ByTables(): void
    {
        $connection = new ConnectionMock();
        $data = [
            'table_name' => $this->table,
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

        $pgsqlIndexAnalyzer = new PgsqlIndexAnalyzer($connection);
        $indexDtos = $pgsqlIndexAnalyzer->unusedIndexesByTables([$this->table]);

        $this->assertEmpty($indexDtos, 'Method unused indexes by table is broken.');
    }
}