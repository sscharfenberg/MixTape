<?php

namespace App\Http\Controllers\Api\Music;

use App\Models\Genre;
use App\Http\Controllers\Controller;
use App\Services\GenreService;
use App\Services\SongService;
use App\Services\UrlSafeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenreController extends Controller
{

    /**
     * @param Request $request
     * @param string $name
     * @return JsonResponse
     */
    public function show(Request $request, string $name): JsonResponse
    {
        $g = new GenreService;
        $genre = $g->getGenreByName($name);
        if (count($genre) > 0) {
            return response()->json($genre);
        } else {
            return response()
                ->json(['message' => 'Es existiert kein Genre mit diesem Namen.'], 422);
        }
    }
}
