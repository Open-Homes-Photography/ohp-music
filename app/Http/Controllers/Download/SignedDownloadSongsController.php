<?php

namespace App\Http\Controllers\Download;

use App\Http\Controllers\Controller;
use App\Models\Song;
use App\Services\DownloadService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

class SignedDownloadSongsController extends Controller
{
    public function __invoke(Request $request, DownloadService $service, string $songId)
    {
        $song = Song::find($songId);

        $downloadablePath = $service->getDownloadablePath(collect([$song]));

        abort_unless((bool) $downloadablePath, Response::HTTP_BAD_REQUEST, 'Song or episode cannot be downloaded.');

        return response()->download($downloadablePath, $this->getFilename($song));
    }

    protected function getFilename(Song $song)
    {
        $title = $song->title ? mb_strtolower($song->title) : 'track';

        $songPathInfo = pathinfo($song->path);

        $extension = $songPathInfo['extension'] ? '.' . $songPathInfo['extension'] : '';

        return "$title$extension";
    }

    public function generateUrl(Request $request, string $songId)
    {
        $url = URL::temporarySignedRoute(
            'signed-song-download',
            now()->addDays(90),
            ['songId' => $songId],
        );

        return [
            'url' => $url,
        ];
    }
}
