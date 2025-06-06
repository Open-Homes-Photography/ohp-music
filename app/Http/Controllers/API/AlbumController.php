<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumResource;
use App\Models\Album;
use App\Repositories\AlbumRepository;

class AlbumController extends Controller
{
    public function __construct(private readonly AlbumRepository $repository)
    {
    }

    public function index()
    {
        return AlbumResource::collection($this->repository->paginate(request()->user()));
    }

    public function show(Album $album)
    {
        return AlbumResource::make($album);
    }
}
