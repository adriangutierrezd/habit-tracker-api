<?php

namespace App\Providers;

use App\Models\Habit;
use App\Models\HabitRecord;
use App\Policies\V1\HabitPolicy;
use App\Policies\V1\HabitRecordPolicy;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(Habit::class, HabitPolicy::class);
        Gate::policy(HabitRecord::class, HabitRecordPolicy::class);
    }
}
