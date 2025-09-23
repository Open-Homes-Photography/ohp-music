<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\KeywordFetchSongRequest;
use App\Http\Resources\SongResource;
use App\Models\User;
use App\Repositories\SongRepository;
use App\Values\Keyword;
use Illuminate\Contracts\Auth\Authenticatable;

class KeywordSongController extends Controller
{
    /**
     * @param User $user
     */
    public function __invoke(
        string $keyword,
        SongRepository $repository,
        Authenticatable $user,
        KeywordFetchSongRequest $request
    ) {
        return SongResource::collection(
            $repository->getByKeyword(
                $keyword === Keyword::NO_KEYWORDS ? '' : $keyword,
                $request->sort ? explode(',', $request->sort) : ['songs.title'],
                $request->order ?: 'asc',
                $user
            )
        );
    }
}
