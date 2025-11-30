<?php

namespace App\Http\Controllers\Api\Music;

use App\Http\Controllers\Controller;
use App\Services\SongService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SongSearchController extends Controller
{

    /**
     * @param Request $request
     * @param string $search
     * @return JsonResponse
     */
    public function show(Request $request, string $search): JsonResponse
    {
        $a = new SongService;
        $artists = $a->searchSongByName($search);
        return response()->json([
            'searchTerm' => $search,
            'results' => $artists
        ]);
    }

}
