<?php

namespace Api\Models;

use Api\Core\Model;

class Version extends Model
{
    protected string $table = 'versions';

    public int $translation_id;
    public string $content;
    public string $created_at;

    public function translations(): array
    {
        return Translation::findBy('id', $this->translation_id)
            ->toArray();
    }
}
