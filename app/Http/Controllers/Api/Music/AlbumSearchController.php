<?php

namespace App\Http\Controllers\Api\Music;

use App\Http\Controllers\Controller;
use App\Services\AlbumService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlbumSearchController extends Controller
{

    /**
     * @param Request $request
     * @param string $search
     * @return JsonResponse
     */
    public function show(Request $request, string $search): JsonResponse
    {
        $a = new AlbumService;
        $artists = $a->searchAlbumByName($search);
        return response()->json([
            'searchTerm' => $search,
            'results' => $artists
        ]);
    }

}
