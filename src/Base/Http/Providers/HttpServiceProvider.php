<?php

namespace Base\Http\Providers;

use Base\Http\Request;
use Base\Http\Response;
use Base\ServiceProvider;

class HttpServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->register('request', new Request());

        $this->app->register('response', new Response());
    }
}
