<?php

namespace Api\Controller;

use Api\Enums\TranslationStatus;
use Api\Helpers\Request;
use Api\Models\Segment;
use Api\Models\Translation;

use function Api\Helpers\dd;
use function Api\Helpers\jsonResponse;

class SegmentsController
{
    public function read()
    {
        $segments = [];
        foreach (Segment::all() as $segment) {
            $segments[] = [
                'id' => $segment->id,
                'content' => $segment->content,
                'translation' => $segment->translation()->content ?? null,
            ];
        }

        echo jsonResponse($segments);
    }

    public function create()
    {
        $request = Request::getBody();

        try {
            $lastId = Segment::create([
                'language' => 'en',
                'content' => $request['segment'],
            ]);

            if ($lastId) {
                Translation::create([
                    'segment_id' => $lastId,
                    'language' => 'uk',
                    'status' => TranslationStatus::PENDING,
                    'content' => $request['translation'],
                ]);
            }
        } catch (\Exception $e) {
            echo jsonResponse([
                'message' => 'Error creating segment!',
                'error' => $e->getMessage(),
            ], 500);
            return;
        }

        $segment = Segment::findByID($lastId);

        echo jsonResponse([
            'id' => $segment->id,
            'content' => $segment->content,
            'translation' => $segment->translation()->content ?? null,
        ]);
    }

    public function update(
        int $id
    ) {
        $request = Request::getBody();

        try {
            $segment = Segment::findByID($id);
            $segment->content = $request['content'];
            $segment->save();

            $translation = $segment->translation();
            $translation->toHistory();
            $translation->content = $request['translation'];
            $translation->save();
        } catch (\Exception $e) {
            echo jsonResponse([
                'message' => 'Error updating segment!',
                'error' => $e->getMessage(),
            ], 500);
            return;
        }

        echo jsonResponse([
            'id' => $segment->id,
            'content' => $segment->content,
            'translation' => $segment->translation()->content ?? null,
        ]);
    }
}
