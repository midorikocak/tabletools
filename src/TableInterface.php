<?php

declare(strict_types=1);

namespace midorikocak\tabletools;

interface TableInterface
{
    public function sort(string $key, $order = 'ASC'): self;

    public function columns($keys): self;

    public function filter(string $key, $value): self;

    public function search(string $key, $value): self;

    public function range(int $offset, ?int $limit = null): self;

    public function paginate(int $page = 0, int $pageSize = 10): self;

    public function run(): array;
}
