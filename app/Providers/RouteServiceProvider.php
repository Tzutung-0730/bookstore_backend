<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot()
    {
        // 如果需要設定速率限制等，可以在此呼叫 $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(function () {
                    foreach (glob(base_path('routes/api/*.php')) as $routeFile) {
                        require $routeFile;
                    }
                });

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
