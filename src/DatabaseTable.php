<?php

declare(strict_types=1);

namespace midorikocak\tabletools;

use midorikocak\nanodb\Database;

class DatabaseTable implements TableInterface
{
    private Database $db;
    private string $name;

    public function __construct(string $name, Database $db)
    {
        $this->db = $db->select($name);
        $this->name = $name;
    }

    public function sort(string $key, $order = 'ASC'): self
    {
        $this->db = $this->db->orderBy($key, $order);
        return $this;
    }

    public function columns($keys): self
    {
        $this->db = $this->db->select($this->name, $keys);
        return $this;
    }

    public function filter(string $key, $value): self
    {
        $this->db->where($key, $value);
        return $this;
    }

    public function search(string $key, $value): self
    {
        // $this->db = $this->db->where($key, $value, 'LIKE');
        return $this;
    }

    public function range(int $offset, ?int $limit = null): self
    {
        if ($offset !== 0) {
            $this->db = $this->db->offset($offset);
        }
        if ($limit) {
            $this->db = $this->db->limit($limit);
        }

        return $this;
    }

    public function paginate(int $page = 0, int $pageSize = 10): self
    {
        $offset = $page * $pageSize;
        $limit = $pageSize;

        return $this->range($offset, $limit);
    }

    public function run(): array
    {
        return $this->db->fetchAll();
    }
}
