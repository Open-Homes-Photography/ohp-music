<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\FetchRandomSongsInKeywordRequest;
use App\Http\Resources\SongResource;
use App\Models\User;
use App\Repositories\SongRepository;
use Illuminate\Contracts\Auth\Authenticatable;

class FetchRandomSongsInKeywordController extends Controller
{
    /** @param User $user */
    public function __invoke(
        FetchRandomSongsInKeywordRequest $request,
        SongRepository $repository,
        Authenticatable $user
    ) {
        return SongResource::collection($repository->getRandomByKeyword($request->genre, $request->limit, $user));
    }
}
