<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\User;

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
        View::composer('orders.create', function ($view) {
            $view->with('vehicles', Vehicle::all());
            $view->with('drivers', Driver::all());
            $view->with('approvers', User::whereIn('role', ['approver', 'admin'])->get());
        });
    }
}
