<?php

namespace Api\Controller;

use function Api\Helpers\dd;
use function Api\Helpers\jsonResponse;

class TranslationController
{
    public function getUnits()
    {


        echo jsonResponse([
            'message' => [

            ]
        ]);
    }
}
