<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\TokenManager;
use App\Services\UserService;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /** @param User $user */
    public function __construct(
        private readonly Hasher $hash,
        private readonly UserService $userService,
        private readonly TokenManager $tokenManager,
    ) {
    }

    public function show()
    {
        return UserResource::make(request()->user());
    }

    public function update(ProfileUpdateRequest $request)
    {
        static::disableInDemo(Response::HTTP_NO_CONTENT);

        // If the user is not using SSO, we need to verify their current password.
        throw_if(
            !$request->user()->is_sso && !$this->hash->check($request->current_password, $request->user()->password),
            ValidationException::withMessages(['current_password' => 'Invalid current password'])
        );

        $user = $this->userService->updateUser(
            user: $request->user(),
            name: $request->name,
            email: $request->email,
            password: $request->new_password,
            avatar: Str::startsWith($request->avatar, 'data:') ? $request->avatar : null
        );

        $response = UserResource::make($user)->response();

        if ($request->new_password) {
            $response->header(
                'Authorization',
                $this->tokenManager->refreshApiToken($request->bearerToken() ?: '')->plainTextToken
            );
        }

        return $response;
    }
}
