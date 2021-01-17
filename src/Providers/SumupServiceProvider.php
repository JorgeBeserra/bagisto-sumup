<?php

namespace Jorgebeserra\Sumup\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class SumupServiceProvider
 * @package Jorgebeserra\Sumup\Providers
 */

class SumupServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../Http/routes.php';

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'sumup');

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/paymentmethods.php', 'paymentmethods'
        );
    }
}
