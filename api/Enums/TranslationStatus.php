<?php

namespace Api\Enums;

enum TranslationStatus: string
{
    case PENDING = 'pending';
    case COMPLETE = 'complete';
}
