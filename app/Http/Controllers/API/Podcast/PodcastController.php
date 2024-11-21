<?php

namespace App\Http\Controllers\API\Podcast;

use App\Exceptions\UserAlreadySubscribedToPodcast;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Podcast\PodcastStoreRequest;
use App\Http\Resources\PodcastResource;
use App\Http\Resources\PodcastResourceCollection;
use App\Models\Podcast;
use App\Models\User;
use App\Repositories\PodcastRepository;
use App\Services\PodcastService;
use Illuminate\Http\Response;

class PodcastController extends Controller
{
    /** @param User $user */
    public function __construct(
        private readonly PodcastService $podcastService,
        private readonly PodcastRepository $podcastRepository,
    ) {
    }

    public function index()
    {
        return PodcastResourceCollection::make($this->podcastRepository->getAllByUser(request()->user()));
    }

    public function store(PodcastStoreRequest $request)
    {
        self::disableInDemo();

        try {
            return PodcastResource::make($this->podcastService->addPodcast($request->url, $request->user()));
        } catch (UserAlreadySubscribedToPodcast) {
            abort(Response::HTTP_CONFLICT, 'You have already subscribed to this podcast.');
        }
    }

    public function show(Podcast $podcast)
    {
        $this->authorize('view', $podcast);

        return PodcastResource::make($podcast);
    }
}
