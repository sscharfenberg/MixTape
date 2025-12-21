<?php

namespace App\Http\Controllers\Api\Widget;

use App\Http\Controllers\Controller;
use App\Models\GlobalProperties;
use App\Models\Playlist;
use App\Models\PlaylistEntry;
use App\Models\Song;
use App\Models\Track;
use App\Services\FormatService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Finder\Glob;

class GlobalWidgetController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $f = new FormatService;
        $trackSize = Track::sum('size');
        $trackFiles = Track::count();
        $trackDuration = Track::sum('duration');
        $songSize = Song::sum('size');
        $songFiles = Song::count();
        $songDuration = Song::sum('duration');
        $totalSize = $songSize + $trackSize;
        $totalFiles = $songFiles + $trackFiles;
        $totalDuration = $songDuration + $trackDuration;
        $playlists = Playlist::count();
        $playlistEntries = PlaylistEntry::count();
        $playlistsSize = PlaylistEntry::sum('size');
        $playlistsDuration = PlaylistEntry::sum('duration');
        $fullUpdate = GlobalProperties::where('key', 'refresh.full')->first();

        return response()->json(
            [
                'music' => [
                    'size' => (int)$songSize,
                    'files' => (int)$songFiles,
                    'duration' => $songDuration
                ],
                'audiobooks' => [
                    'size' => (int)$trackSize,
                    'files' => (int)$trackFiles,
                    'duration' => $trackDuration
                ],
                'total' => [
                    'size' => (int)$totalSize,
                    'files' => (int)$totalFiles,
                    'duration' => $totalDuration
                ],
                'playlists' => [
                    'count' => (int)$playlists,
                    'entries' => (int)$playlistEntries,
                    'size' => (int)$playlistsSize,
                    'duration' => $playlistsDuration
                ],
                'last_full_update' => Carbon::parse("$fullUpdate->updated_at Europe/Berlin")
                    ->format('Y-m-d\TH:i:sP')
            ]
        );

    }
}
