<?php

namespace App\Providers;

use App\Events\BuyProduct;
use App\Events\NewDiscount;
use App\Events\SendedToAmoCRM;
use App\Listeners\SendDiscountNotification;
use App\Listeners\SendNotification;
use App\Listeners\SendToAmoCRM;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        BuyProduct::class => [
            SendToAmoCRM::class,
        ],
        NewDiscount::class => [
            SendDiscountNotification::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
