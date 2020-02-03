<?php

declare(strict_types=1);

namespace midorikocak\tabletools;

use League\Csv\Reader;
use PHPUnit\Framework\TestCase;

use function array_keys;
use function ini_get;
use function ini_set;
use function reset;

class TableTest extends TestCase
{
    private ArrayTable $arrayTable;

    public function setUp(): void
    {
        if (!ini_get('auto_detect_line_endings')) {
            ini_set('auto_detect_line_endings', '1');
        }
        parent::setUp();

        $data = $this->getArrayFromCsv('tests/small-name.csv');
        $this->arrayTable = new ArrayTable($data);
    }

    public function getArrayFromCsv(string $filename): array
    {
        $csv = Reader::createFromPath($filename, 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();

        $toReturn = [];

        foreach ($records as $record) {
            $toReturn[] = $record;
        }

        return $toReturn;
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->arrayTable);
    }

    public function testColumns(): void
    {
        $result = $this->arrayTable->columns(['first_name', 'last_name'])->run();

        $keys = array_keys(reset($result));

        $this->assertEquals(['first_name', 'last_name'], $keys);
    }

    public function testFilter(): void
    {
        $result = $this->arrayTable->filter('first_name', 'Deniz')->run();
        $this->assertNotEmpty($result);
    }

    public function testSearch(): void
    {
        $result = $this->arrayTable->search('first_name', 'Mahir')->run();
        $this->assertNotEmpty($result);
    }

    public function testSort(): void
    {
        $result = $this->arrayTable->sort('birth_year')->run();
        $this->assertNotEmpty($result);
    }

    public function testRange(): void
    {
        $result = $this->arrayTable->range(0, 2)->run();
        $this->assertNotEmpty($result);
    }

    public function testPaginate(): void
    {
        $result = $this->arrayTable->paginate(2, 5)->run();
        $this->assertNotEmpty($result);
    }
}
