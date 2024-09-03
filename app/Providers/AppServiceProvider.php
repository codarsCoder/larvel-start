<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $settings = [];

        foreach (Setting::where('id',1)->get() as $item) {
            $settings[$item->name] = $item->value;
        }
        \Config::set('settings', $settings);
    }
}
