<?php

declare(strict_types=1);

use LeanMapper\Result;
use LeanMapper\Row;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

$row = new Row(Result::createInstance(new \Dibi\Row(['id' => 1, 'name' => true]), 'test', $connection, $mapper), 1);

Assert::true(isset($row->name));

Assert::false(isset($row->test));
