<?php

namespace App\Http\Controllers\Api\Widget;

use App\Http\Controllers\Controller;
use App\Services\GenreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenreWidgetController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $g = new GenreService;
        $genres = $g->getGenresByDuration();
        if (count($genres) > 0) {
            return response()->json(
                array_slice(
                    $genres, 0, config('collection.stats.genres.num_top')
                )
            );
        } else {
            return response()
                ->json(['message' => 'Fehler beim Laden der Kachel Genres.'], 422);
        }
    }
}
