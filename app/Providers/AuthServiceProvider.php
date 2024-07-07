<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Model'leriniz için politikalar burada tanımlanabilir
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
