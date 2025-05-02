<?php

namespace Api\Models;

use Api\Core\Model;

class Language extends Model
{
    protected string $table = 'languages';
    protected string $primaryKey = 'code';

    public ?string $code;
    public ?string $title;

    public static function findByCode(
        string $code
    ): Language|null {
        return self::findBy('code', $code)
            ->first();
    }
}
