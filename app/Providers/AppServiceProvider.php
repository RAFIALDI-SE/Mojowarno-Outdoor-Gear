<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\TermsCondition;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

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
        // Hanya share data jika tabelnya memang sudah ada di database
        if (Schema::hasTable('terms_conditions')) {
            View::share('terms', TermsCondition::latest()->get());
        }
    }
}
