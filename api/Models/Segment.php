<?php

namespace Api\Models;

use Api\Core\Model;
use Api\Models\Language;

class Segment extends Model
{
    protected $table = 'segments';

    public string $language;
    public string $content;
    public string $created_at;

    public function language(): Language
    {
        return Language::findByCode($this->language);
    }
}
