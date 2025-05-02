<?php

namespace Api\Models;

use Api\Core\Model;
use Api\Models\Segment;
use Api\Models\Language;
use Api\Enums\TranslationStatus;

class Translation extends Model
{
    protected string $table = 'translations';

    public int $id;
    public int $segment_id;
    public string $language;
    public TranslationStatus $status;
    public string $content;
    public string $created_at;
    public ?string $updated_at;

    public function segment(): Segment
    {
        return Segment::findByID($this->segment_id);
    }

    public function language(): Language
    {
        return Language::findByCode($this->language);
    }

    public function toHistory(): string|int
    {
        return Version::create([
            'translation_id' => $this->id,
            'language' => $this->language,
            'content' => $this->content,
        ]);
    }
}
