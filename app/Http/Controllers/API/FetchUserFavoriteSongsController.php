<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SongResource;
use App\Models\User;
use App\Repositories\SongRepository;

class FetchUserFavoriteSongsController extends Controller
{
    public function __invoke(SongRepository $repository, User $user)
    {
        return SongResource::collection($repository->getFavorites($user));
    }
}
