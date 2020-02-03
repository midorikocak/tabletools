<?php

declare(strict_types=1);

namespace midorikocak\tabletools;

use Exception;

use function array_filter;
use function array_map;
use function array_slice;
use function in_array;
use function strpos;
use function usort;

class ArrayTable implements TableInterface
{
    private array $data = [];
    private array $toReturn = [];

    /**
     * @param array $data Should be a 2x2 associative array.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->toReturn = $data;
    }

    /**
     * @param string $order
     * @return $this
     * @throws Exception
     */
    public function sort(string $key, $order = 'ASC'): self
    {
        usort($this->toReturn, function ($a, $b) use ($order, $key) {
            return ($order === 'ASC' ? 1 : -1) * ($a[$key] <=> $b[$key]);
        });

        return $this;
    }

    public function columns($keys): self
    {
        //array_walk($keys, fn($key) => $this->checkKey($key));

        $this->toReturn = array_map(function (&$item) use ($keys) {
            return self::filterKeys($keys, $item);
        }, $this->toReturn);

        return $this;
    }

    private static function filterKeys($keys, $data): array
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, $keys)) {
                unset($data[$key]);
            }
        }
        return $data;
    }

    public function filter(string $key, $value): self
    {
        $this->toReturn = array_filter($this->data, function ($item) use ($value, $key) {
            return $item[$key] === $value;
        });
        return $this;
    }

    public function search(string $key, $value): self
    {
        $this->toReturn = array_filter($this->data, function ($item) use ($value, $key) {
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
        if (!$limit) {
            $this->toReturn = array_slice($this->toReturn, $offset);
        } else {
            $this->toReturn = array_slice($this->toReturn, $offset, $limit);
        }
        return $this;
    }

    public function run(): array
    {
        return $this->toReturn;
    }
}
