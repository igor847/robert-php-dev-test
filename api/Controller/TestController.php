<?php

namespace Api\Controller;

use Api\Models\User;

use function Api\Helpers\jsonResponse;

class TestController
{
    public function test($id)
    {

        echo jsonResponse(['message' => 'Test successful!']);
    }
}
