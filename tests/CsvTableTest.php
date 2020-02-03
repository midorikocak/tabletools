<?php

declare(strict_types=1);

namespace midorikocak\tabletools;

use PHPUnit\Framework\TestCase;

use function array_keys;
use function ini_get;
use function ini_set;
use function reset;

class CsvTableTest extends TestCase
{
    private CsvTable $csvTable;

    public function setUp(): void
    {
        if (!ini_get('auto_detect_line_endings')) {
            ini_set('auto_detect_line_endings', '1');
        }
        parent::setUp();

        $this->csvTable = new CsvTable('tests/small-name.csv');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->csvTable);
    }

    public function testColumns(): void
    {
        $result = $this->csvTable->columns(['first_name', 'last_name'])->run();

        $keys = array_keys(reset($result));

        $this->assertEquals(['first_name', 'last_name'], $keys);
    }

    public function testFilter(): void
    {
        $result = $this->csvTable->filter('first_name', 'Deniz')->run();
        $this->assertNotEmpty($result);
    }

    public function testSearch(): void
    {
        $result = $this->csvTable->search('first_name', 'Mahir')->run();
        $this->assertNotEmpty($result);
    }

    public function testSort(): void
    {
        $result = $this->csvTable->sort('birth_year')->run();
        $this->assertNotEmpty($result);
    }

    public function testRange(): void
    {
        $result = $this->csvTable->range(0, 2)->run();
        $this->assertNotEmpty($result);
    }

    public function testPaginate(): void
    {
        $result = $this->csvTable->paginate(2, 5)->run();
        $this->assertNotEmpty($result);
    }
}
