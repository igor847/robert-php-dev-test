<?php

namespace Api\Models;

use Api\Core\Model;

class Versions extends Model
{
    protected $table = 'versions';

    public int $translation_id;
    public string $content;
    public string $created_at;

    public function translations(): array
    {
        return Translation::findBy('id', $this->translation_id)
            ->toArray();
    }
}
