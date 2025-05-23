<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Определение роли администратора
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });

        // Определение роли работника производства
        Gate::define('production-worker', function ($user) {
            return $user->isProductionWorker();
        });

        // Определение роли менеджера склада
        Gate::define('warehouse-manager', function ($user) {
            return $user->isWarehouseManager();
        });
    }
}