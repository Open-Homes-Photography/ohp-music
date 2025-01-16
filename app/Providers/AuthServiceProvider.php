<?php

namespace App\Providers;

use App\Models\User;
use App\Services\TokenManager;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();

        Auth::viaRequest(
            'token-via-query-parameter',
            static function (Request $request): ?User {
                $token = $request->get('api_token') ?: $request->get('t');

                return  app(TokenManager::class)->getUserFromPlainTextToken($token ?: '');
            }
        );

        Auth::extend(
            'ohp-session',
            static function (Application $app, string $name, array $config) {
                $provider = config('auth.providers.' . $config['provider']);

                // Only use session if it's present (i.e. HTTP context)
                $session = $app->bound('request') && $app->make('request')->hasSession()
                    ? $app->make('request')->session()
                    : $app->make('session.store'); // Fallback for non-HTTP environments

                return new SessionGuard(
                    $config['ohp_guard_name'],
                    new EloquentUserProvider($app->make(Hasher::class), $provider['model']),
                    $session,
                );
            }
        );

        $this->setPasswordDefaultRules();

        ResetPassword::createUrlUsing(
            static function (User $user, string $token): string {
                $payload = base64_encode($user->getEmailForPasswordReset() . "|$token");

                return url("/#/reset-password/$payload");
            }
        );
    }

    private function setPasswordDefaultRules(): void
    {
        Password::defaults(
            fn (): Password => $this->app->isProduction()
            ? Password::min(10)->letters()->numbers()->symbols()->uncompromised()
            : Password::min(6)
        );
    }
}
