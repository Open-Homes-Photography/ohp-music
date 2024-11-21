<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    // public function handle(Request $request, Closure $next) // @phpcs:ignore
    // {
    //     // if ($request->user()?->tokenCan('*')) {
    //     if (true) {
    //         // dd('here');
    //         return $next($request);
    //     }

    //     abort_if($request->ajax() || $request->wantsJson(), Response::HTTP_UNAUTHORIZED);

    //     return redirect()->guest('/');
    // }
}
