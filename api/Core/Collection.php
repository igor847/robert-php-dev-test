<?php

namespace Api\Core;

class Collection
{
    protected array $items;

    public function __construct(
        array $items
    ) {
        $this->items = $items;
    }

    public function first(): mixed
    {
        return $this->items[0] ?? null;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function keys(): array
    {
        return array_keys($this->items);
    }

    public function values(): array
    {
        return array_values($this->items);
    }


    public function toArray(): array
    {
        return $this->items;
    }
}
