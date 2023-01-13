# Postgresql index analyzer

## Introduction
<b>PgsqlIndexAnalyzer</b> - it's a simple PHP library, which help support indexes in your postgresql database.
<hr>
You can use it to find the following indexes:
<ul>
<li>All indexes by tables</li>
<li>Unused indexes by tables</li>
<li>Overlapping indexes by tables</li>
<li>Indexes Contains In Other Indexes ByTables</li>
</ul>

<hr>

# Table of contents
-----
1. [Installing](#installing)
2. [Methods](#methods)
3. [Usage](#usage)
4. [Tests](#tests)
-----

# Installing
-----
```sh
composer require sapronovps/pgsqlindexanalyzer --dev
```

# Methods
-----

Library contains only four methods:

<ul>
<b><b></b>allIndexesByTables</b> - method return all indexes by tables.</li>
<b><b></b>unusedIndexesByTables</b> - method return unused indexes by tables. Unused indexes are determined by the parameter <b>IndexScan = 0</b> </li>
<b><b></b>overlappingIndexesByTables</b> - method return overlapping indexes by tables.</li>
<b><b></b>indexesContainsInOtherIndexesByTables</b> - method return indexes contains in other indexes by tables.</li>
</ul>

# Usage
-----

First you need to create an instance of the library and estimate the configuration.

```php
<?php

use Sapronovps\PgsqlIndexAnalyzer\Connection\Connection;
use Sapronovps\PgsqlIndexAnalyzer\Option\Options;
use Sapronovps\PgsqlIndexAnalyzer\PgsqlIndexAnalyzer;

// Create options as array OR with Options class.
 
$options = [
'host' => 'localhost',
'dbName' => 'postgresql',
'user' => 'postgresql',
'password' => 'secretPassword',
];

// OR 
$options = new Options();
$options->setHost('localhost')
        ->setDbName('postgresql')
        ->setUser('postgresql')
        ->setPassword('secretPassword');
        
$connection = new Connection($options);

$pgsqlIndexAnalyzer = new PgsqlIndexAnalyzer($connection);

$tables = [
'table1',
'table2',
'table3',
];
```

<b>Get all indexes by tables:</b>
```php
$tables = [
'table1',
'table2',
'table3',
];

$allIndexes = $pgsqlIndexAnalyzer->allIndexesByTables($tables);
```

<b>Get unused indexes by tables:</b>
<br>
Unused indexes - it's indexes when parameter "IndexScan" === 0;

```php
$tables = [
'table1',
'table2',
'table3',
];

$unusedIndexesByTables = $pgsqlIndexAnalyzer->unusedIndexesByTables($tables);
```

<b>Get overlapping indexes by tables:</b>
<br>
Overlapping indexes - in postgresql, indexes are read from left to right, so there are indexes that are redundant 
and already contained in any existing index in strict left-to-right order. Usually such indexes can be deleted.

```php
$tables = [
'table1',
'table2',
'table3',
];

$overlappingIndexesByTables = $pgsqlIndexAnalyzer->overlappingIndexesByTables($tables);
```

<b>Get indexes contains in other indexes by tables:</b>
<br>
Indexes contains in other indexes - this method is very similar to <b>overlappingIndexesByTables</b>, but this method
looks for redundant indexes without taking into account reading from left to right. Attention: such indexes can be deleted only
after a detailed analysis.

```php
$tables = [
'table1',
'table2',
'table3',
];

$indexesContainsInOtherIndexesByTables = $pgsqlIndexAnalyzer->indexesContainsInOtherIndexesByTables($tables);
```

# Tests
-----

This library is covered by unit test and phpstan.