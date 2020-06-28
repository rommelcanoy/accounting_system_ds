<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
        Blade::directive('money', function ($amount) {
            return "<?php
                if($amount < 0) {
                    $amount *= -1;
                    echo '-₱' . number_format($amount, 0);
                } else {
                    echo '₱' . number_format($amount, 0);
                }
            ?>";
        });

        Blade::directive('date_convert', function($date) {
            return "<?php date('M-d-Y', strtotime($date)) ?>";
        });
    }
}
