<?php

namespace Api\Models;

use Api\Core\Model;
use Api\Models\Language;

class Segment extends Model
{
    protected string $table = 'segments';

    public int $id;
    public string $language;
    public string $content;
    public string $created_at;

    public function language(): Language
    {
        return Language::findByCode($this->language);
    }

    public function translation(): ?Translation
    {
        return Translation::findBy('segment_id', $this->{$this->getPrimaryKey()})->first();
    }

    public static function findByID(
        int $id
    ): Segment {
        return parent::findByID($id);
    }
}
