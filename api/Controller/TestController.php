<?php

namespace Api\Controller;

use Api\Models\Language;
use Api\Models\User;

use function Api\Helpers\dd;
use function Api\Helpers\jsonResponse;

class TestController
{
    public function test()
    {
        echo jsonResponse([
            'message' => Language::findByCode('en')
        ]);
    }

    public function testId($id)
    {
        echo jsonResponse(['message' => 'Test ' . $id . ' successful!']);
    }
}
