<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\CmsHomepage;

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
