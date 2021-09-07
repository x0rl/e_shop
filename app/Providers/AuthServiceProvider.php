<?php

namespace App\Providers;

use App\Models\SubCategory;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('addProduct', function($subCategory) {
            return SubCategory::find($subCategory) 
                ? Response::allow()
                : Response::deny('Вы пытаетесь добавить товар в несуществующую категорию');
        });
    }
}
