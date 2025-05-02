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
        $old = Language::all();

        $model = new Language();
        $model->title = 'Polish';
        $model->code = 'pl';
        $model->save();

        $new1 = Language::all();

        if ($model = Language::findByCode('pl')) {
            $model->title = 'Polski';
            $model->save();
        }

        echo jsonResponse([
            'message' => [
                'old' => $old,
                'new1' => $new1,
                'model' => $model,

            ]
        ]);
    }

    public function testId($id)
    {
        echo jsonResponse(['message' => 'Test ' . $id . ' successful!']);
    }
}
