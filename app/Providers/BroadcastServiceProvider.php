<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
public function boot()
{
    Broadcast::routes([
        'prefix' => 'tps-smis', // optional, only if your app uses this prefix
        'middleware' => ['auth'], // make sure you're using appropriate middleware
    ]);

    require base_path('routes/channels.php');
}

}
