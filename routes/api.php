<?php

use Illuminate\Support\Facades\Route;


Route::prefix('api')->group(function () {

    /**
     * Stats Controllers
     */
    // Global Stats Controller
    Route::get('/stats/global',
        [\App\Http\Controllers\Api\Stats\GlobalStatsController::class, "show"]
    );
    // Song Stats Controller
    Route::get('/stats/songs',
        [\App\Http\Controllers\Api\Stats\SongStatsController::class, "show"]
    );
    // Genre Stats Controller
    Route::get('/stats/genres',
        [\App\Http\Controllers\Api\Stats\GenreStatsController::class, "show"]
    );
    // Album Stats Controller
    Route::get('/stats/albums',
        [\App\Http\Controllers\Api\Stats\AlbumStatsController::class, "show"]
    );

    /**
     * Music Controllers
     */
    // Songs Controller
    Route::get('/music/songs',
        [\App\Http\Controllers\Api\Music\SongsController::class, "show"]
    );
    // Song Controller
    Route::get('/music/songs/{path}',
        [\App\Http\Controllers\Api\Music\SongController::class, "show"]
    );

    // Genres Controller
    Route::get('/music/genres',
        [\App\Http\Controllers\Api\Music\GenresController::class, "show"]
    );
    // Genre Controller
    Route::get('/music/genres/{name}',
        [\App\Http\Controllers\Api\Music\GenreController::class, "show"]
    );

    // Albums Controller
    Route::get('/music/albums',
        [\App\Http\Controllers\Api\Music\AlbumsController::class, "show"]
    );
    // Album Controller
    Route::get('/music/albums/{id}',
        [\App\Http\Controllers\Api\Music\AlbumController::class, "show"]
    );
    Route::get('/music/albums/{id}/download',
        [\App\Http\Controllers\Api\Music\AlbumController::class, "download"]
    );

});


