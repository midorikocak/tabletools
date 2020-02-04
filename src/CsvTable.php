<?php

declare(strict_types=1);

namespace midorikocak\tabletools;

use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Statement;

use function count;
use function iterator_to_array;
use function strpos;

class CsvTable implements TableInterface
{
    private Reader $csv;
    private Statement $statement;
    private array $columns = [];

    /**
     * @throws Exception
     */
    public function __construct(string $filename)
    {
        $this->csv = Reader::createFromPath($filename, 'r');
        $this->csv->setHeaderOffset(0);
        $this->statement = new Statement();
    }

    /**
     * @param string $order
     * @return $this
     */
    public function sort(string $key, $order = 'ASC'): self
    {
        $this->statement->orderBy(function ($a, $b) use ($order) {
            return ($order === 'ASC' ? 1 : -1) * ($a <=> $b);
        });

        return $this;
    }

    public function columns($keys): self
    {
        $this->columns = $keys;
        return $this;
    }

    public function filter(string $key, $value): self
    {
        $this->statement->where(function ($item) use ($key, $value) {
            return $item[$key] === $value;
        });

        return $this;
    }

    public function search(string $key, $value): self
    {
        $this->statement->where(function ($item) use ($key, $value) {
            return strpos($item[$key], $value) !== false;
        });

        return $this;
    }

    public function paginate(int $page = 0, int $pageSize = 10): self
    {
        $offset = $page * $pageSize;
        $this->range($offset, $pageSize);
        return $this;
    }

    public function range(int $offset, ?int $limit = null): self
    {
        if ($limit !== null) {
            $this->statement->limit($limit);
        }

        if ($limit !== null && $offset !== null) {
            $this->statement->offset($offset);
        }

        return $this;
    }

    public function run(): array
    {
        $records = iterator_to_array($this->statement->process($this->csv)->getRecords());
        if (!empty($this->columns)) {
            $result = [];

            $recordCount = count($records);

            for ($i = 1; $i <= $recordCount; $i++) {
                foreach ($this->columns as $jValue) {
                    $key = $jValue;
                    $result[$i][$key] = $records[$i][$key];
                }
            }

            return $result;
        }
        return $records;
    }
}
