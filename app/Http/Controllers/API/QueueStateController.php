<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\UpdateQueueStateRequest;
use App\Http\Resources\QueueStateResource;
use App\Models\User;
use App\Services\QueueService;

class QueueStateController extends Controller
{
    /** @param User $user */
    public function __construct(private readonly QueueService $queueService)
    {
    }

    public function show()
    {
        return QueueStateResource::make($this->queueService->getQueueState(request()->user()));
    }

    public function update(UpdateQueueStateRequest $request)
    {
        $this->queueService->updateQueueState($request->user(), $request->songs);

        return response()->noContent();
    }
}
