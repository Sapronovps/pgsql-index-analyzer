<?php

declare(strict_types=1);

namespace Sapronovps\PgsqlIndexAnalyzer\Schema;

final class Schema
{
    public function tableInfo(string $table): string
    {
        return <<<SQL
            SELECT * FROM pg_tables WHERE tablename = '$table';
            SQL;
    }

    public function indexesByTables(array $tables): string
    {
        array_walk($tables, fn(&$x) => $x = "'$x'");
        $tables = implode(', ', $tables);

        return <<<SQL
SELECT table_name,
       index_name,
       string_agg(column_name, ', ')                      AS columns,
       pg_size_pretty(pg_relation_size(index_name::text)) AS index_size_pretty,
       pg_relation_size(index_name::text)                 AS index_size,
       index_relid,
       relid,
       index_scan,
       index_tup_read,
       index_tup_fetch
FROM (SELECT t.relname         AS table_name,
             i.relname         AS index_name,
             a.attname         AS column_name,
             sui.indexrelid    AS index_relid,
             sui.relid         AS relid,
             sui.idx_scan      AS index_scan,
             sui.idx_tup_read  AS index_tup_read,
             sui.idx_tup_fetch AS index_tup_fetch,
             (SELECT i
              FROM (SELECT *,
                           row_number()
                           OVER () i
                    FROM unnest(indkey) WITH ORDINALITY AS a(v)) a
              WHERE v = attnum)
      FROM pg_class t,
           pg_class i,
           pg_index ix,
           pg_attribute a,
           pg_stat_user_indexes sui
      WHERE t.oid = ix.indrelid
        AND i.oid = ix.indexrelid
        AND a.attrelid = t.oid
        AND a.attnum = ANY (ix.indkey)
        AND t.relkind = 'r'
        AND sui.indexrelid = ix.indexrelid
        AND t.relname IN ({$tables})
      ORDER BY table_name, index_name, i) raw
GROUP BY table_name, index_name, index_relid, relid, index_scan, index_tup_read, index_tup_fetch
ORDER BY table_name, index_size desc;
SQL;
    }
}