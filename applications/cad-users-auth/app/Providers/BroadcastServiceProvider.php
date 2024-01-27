<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any applications services.
     */
    public function boot(): void
    {
        Broadcast::routes();

        require base_path('routes/channels.php-v8.2');
    }
}
