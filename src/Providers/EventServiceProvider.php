<?php

namespace Jorgebeserra\Sumup\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

/**
 * Event service provider
 *
 * @author Jorge Beserra <jorgebeserra@gmail.com>
  */
class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        Event::listen('core.configuration.save.after', 'Jorgebeserra\Sumup\Listeners\CoreConfig@openSumupAuthorization');

    }
}
