<?php

namespace App\Providers;

use App\Events\ApplicationFormSubmitted;
use App\Listeners\UpdateApplicationStatus;
use App\Models\HomepageNotice;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
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
        //Event::listen(ApplicationFormSubmitted::class, UpdateApplicationStatus::class);

        // Share active notice with all views using the app layout
        View::composer('layouts.app', function ($view) {
            $view->with('homepageNotice', HomepageNotice::getActiveNotice());
        });
    }
}
