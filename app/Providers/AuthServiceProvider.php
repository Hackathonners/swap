<?php

namespace App\Providers;

use App\Judite\Models\Exchange;
use App\Policies\ExchangePolicy;
use App\Judite\Models\Enrollment;
use App\Policies\EnrollmentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Enrollment::class => EnrollmentPolicy::class,
        Exchange::class => ExchangePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
