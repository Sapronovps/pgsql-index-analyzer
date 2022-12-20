<?php

declare(strict_types=1);

namespace Sapronovps\PgsqlIndexAnalyzer\Dto;

final class IndexDto
{
    private string $tableName = '';

    private string $indexName = '';

    private string $columns = '';

    private string $indexSizePretty = '';

    private int $indexSize = 0;

    private int $indexRelid = 0;

    private int $relid = 0;

    private int $indexScan = 0;

    private int $indexTupRead = 0;

    private int $indexTupFetch = 0;

    public function setTableName(string $tableName): self
    {
        $this->tableName = $tableName;

        return $this;
    }

    public function setIndexName(string $indexName): self
    {
        $this->indexName = $indexName;

        return $this;
    }

    public function setColumns(string $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function setIndexSizePretty(string $indexSizePretty): self
    {
        $this->indexSizePretty = $indexSizePretty;

        return $this;
    }

    public function setIndexSize(int $indexSize): self
    {
        $this->indexSize = $indexSize;

        return $this;
    }

    public function setIndexRelid(int $indexRelid): self
    {
        $this->indexRelid = $indexRelid;

        return $this;
    }

    public function setRelid(int $relid): self
    {
        $this->relid = $relid;

        return $this;
    }

    public function setIndexScan(int $indexScan): self
    {
        $this->indexScan = $indexScan;

        return $this;
    }

    public function setIndexTupRead(int $indexTupRead): self
    {
        $this->indexTupRead = $indexTupRead;

        return $this;
    }

    public function setIndexTupFetch(int $indexTupFetch): self
    {
        $this->indexTupFetch = $indexTupFetch;

        return $this;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getIndexName(): string
    {
        return $this->indexName;
    }

    public function getColumns(): string
    {
        return $this->columns;
    }

    public function getIndexSizePretty(): string
    {
        return $this->indexSizePretty;
    }

    public function getIndexSize(): int
    {
        return $this->indexSize;
    }

    public function getIndexRelid(): int
    {
        return $this->indexRelid;
    }

    public function getRelid(): int
    {
        return $this->relid;
    }

    public function getIndexScan(): int
    {
        return $this->indexScan;
    }

    public function getIndexTupRead(): int
    {
        return $this->indexTupRead;
    }

    public function getIndexTupFetch(): int
    {
        return $this->indexTupFetch;
    }
}