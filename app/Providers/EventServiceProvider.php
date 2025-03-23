<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // 例如：
        // 'App\Events\SomeEvent' => [
        //     'App\Listeners\SomeEventListener',
        // ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        // 如有需要，可在此註冊事件觀察者
    }
}
