<?php

namespace Base\Http\Providers;

use Base\Http\Request;
use Base\Http\Response;
use Base\ServiceProvider;

class HttpServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register('request', new Request());

        // since we've loaded the globals in Request,
        // let's check if we can clear them
        $this->clearGlobals();

        $this->app->register('response', new Response());
    }


    /**
    * Clear "super globals" on production mode
    *
    */
    protected function clearGlobals()
    {
        if (false === config('app.globals',false))
        {
            $_SERVER = [];
            $_ENV = [];
        }
    }
}
