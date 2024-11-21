<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SongResource;
use App\Models\Album;
use App\Models\User;
use App\Repositories\SongRepository;

class AlbumSongController extends Controller
{
    /** @param User $user */
    public function __construct(
        private readonly SongRepository $songRepository,
    ) {
    }

    public function index(Album $album)
    {
        return SongResource::collection($this->songRepository->getByAlbum($album, request()->user()));
    }
}
