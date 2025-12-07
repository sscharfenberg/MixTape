<?php

namespace App\Http\Controllers\Api\Audiobooks;

use App\Http\Controllers\Controller;
use App\Services\AudiobookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AudiobookSearchController extends Controller
{

    /**
     * @param Request $request
     * @param string $search
     * @return JsonResponse
     */
    public function show(Request $request, string $search): JsonResponse
    {
        $a = new AudiobookService();
        $books = $a->searchAudiobookByName($search);
        return response()->json([
            'searchTerm' => $search,
            'results' => $books
        ]);
    }

}
