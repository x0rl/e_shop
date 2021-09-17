<?php

namespace App\Providers;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('e_shop.layouts.header', function ($view) {
            if (! Auth::check()) {
                return;
            }
            if (! $count = session('messageCount')) {
                $count = Message::getMessageCount();
                session(['messageCount' => $count]);
            }
            $view->with('messageCount', $count);
        });
    }
}
