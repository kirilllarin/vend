<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Topic' => 'App\Policies\TopicPolicy',
        'App\User' => 'App\Policies\UserPolicy',
        'App\Comment' => 'App\Policies\CommentPolicy',
        'App\Project' => 'App\Policies\ProjectPolicy',
        'App\Log' => 'App\Policies\LogPolicy',
        'App\Account' => 'App\Policies\AccountPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
