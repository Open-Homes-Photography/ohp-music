<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\KeywordResource;
use App\Repositories\KeywordRepository;
use Illuminate\Http\Response;

class KeywordController extends Controller
{
    public function __construct(private readonly KeywordRepository $repository)
    {
    }

    public function index()
    {
        return KeywordResource::collection($this->repository->getAll());
    }

    public function show(string $name)
    {
        $genre = $this->repository->getOne($name);
        abort_unless((bool) $genre, Response::HTTP_NOT_FOUND);

        return KeywordResource::make($genre);
    }
}
