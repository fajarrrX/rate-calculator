<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();

        // Load broadcast channel routes
        $channelsPath = base_path('routes/channels.php');
        if (file_exists($channelsPath)) {
            require_once $channelsPath;
        }
    }
}
