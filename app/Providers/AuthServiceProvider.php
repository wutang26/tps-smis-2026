<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Programme;
use App\Policies\ProgrammePolicy;
use App\Policies\TimeSheetPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Programme::class => ProgrammePolicy::class,
        TimeSheetPolicy::class => TimeSheetPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('viewAny', [TimeSheetPolicy::class, 'viewAny']);
        Gate::define('view-timesheet', [TimeSheetPolicy::class, 'view']);
        Gate::define('update-timesheet', [TimeSheetPolicy::class, 'update']);

        // You can define additional gates here if needed
        // Gate::define('update-programme', function ($user, $programme) {
        //     return $user->id == $programme->user_id;
        // });
    }
}