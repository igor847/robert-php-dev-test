<?php

namespace App\Core;

class Collection
{
    protected array $items;

    public function __construct(
        array $items
    ) {
        $this->items = $items;
    }

    public function all(): array
    {
        return $this->items;
    }

    public function first(): mixed
    {
        return $this->items[0] ?? null;
    }

    public function count(): int
    {
        return count($this->items);
    }

}
