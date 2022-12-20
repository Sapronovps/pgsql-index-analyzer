<?php

declare(strict_types=1);

namespace Sapronovps\Tests;

use PHPUnit\Framework\TestCase;
use Sapronovps\PgsqlIndexAnalyzer\Dto\IndexDto;

class BaseTests extends TestCase
{
    /**
     * @param IndexDto $indexDto
     * @param array    $actual
     * @return void
     */
    protected function checkEqualsIndexDto(IndexDto $indexDto, array $actual): void
    {
        $this->assertEquals($indexDto->getTableName(), $actual['table_name'], 'Table name is not equal.');
        $this->assertEquals($indexDto->getIndexName(), $actual['index_name'], 'Index name is not equal.');
        $this->assertEquals($indexDto->getColumns(), $actual['columns'], 'Columns is not equal.');
        $this->assertEquals($indexDto->getIndexSizePretty(), $actual['index_size_pretty'], 'Index size pretty is not equal.');
        $this->assertEquals($indexDto->getIndexSize(), $actual['index_size'], 'Index size is not equal.');
        $this->assertEquals($indexDto->getIndexRelid(), $actual['index_relid'], 'Index relid is not equal.');
        $this->assertEquals($indexDto->getRelid(), $actual['relid'], 'Relid is not equal.');
        $this->assertEquals($indexDto->getIndexScan(), $actual['index_scan'], 'Index scan is not equal.');
        $this->assertEquals($indexDto->getIndexTupRead(), $actual['index_tup_read'], 'Index tup read is not equal.');
        $this->assertEquals($indexDto->getIndexTupFetch(), $actual['index_tup_fetch'], 'Index tup fetch is not equal.');
    }
}