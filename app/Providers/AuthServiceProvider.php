<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Passport::tokensExpireIn(now()->addHours(48)); // 48 saat);
        Passport::refreshTokensExpireIn(now()->addHours(54));
        Passport::personalAccessTokensExpireIn(now()->addHours(60));
    }
}
