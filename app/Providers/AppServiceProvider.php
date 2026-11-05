<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\CmsHomepage;
use Laravel\Passport\Passport;

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
        // OAuth untuk connector MCP Claude: halaman izin + masa berlaku token
        // (Claude memperbarui access token otomatis memakai refresh token).
        Passport::authorizationView('mcp.authorize');
        Passport::tokensExpireIn(now()->addHours(8));
        Passport::refreshTokensExpireIn(now()->addDays(30));

        View::composer('*', function ($view) {
            try {
                $cmsHomepage = CmsHomepage::first();
                $view->with('cmsHomepage', $cmsHomepage);
            } catch (\Throwable $e) {
                $view->with('cmsHomepage', null);
            }
        });
    }
}
