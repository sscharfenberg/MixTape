<?php

namespace App\Http\Controllers\Api\Audiobooks;

use App\Http\Controllers\Controller;
use App\Services\AudiobookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AudiobooksController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $a = new AudiobookService;
        $audiobooks = $a->getAllAudiobooks();
        if (count($audiobooks) > 0) {
            return response()->json($audiobooks, 200);
        } else {
            return response()
                ->json(['message' => 'Fehler beim laden der AudiobookBooks. app:update durchgeführt?'], 422);
        }
    }

}
