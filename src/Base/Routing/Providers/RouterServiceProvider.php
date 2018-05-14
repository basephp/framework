<?php

namespace Base\Routing\Providers;

use Base\Routing\Router;
use Base\ServiceProvider;

class RouterServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->register('router', new Router());

        $this->configRouter();
        $this->loadRoutes();
        $this->refreshRouteList();
    }


    /**
    * Load up our router configs
    *
    */
    protected function configRouter()
    {
        $this->app->router->register( $this->app->config->get('router', []) );
    }


    /**
    * Load the application routes
    *
    */
    protected function loadRoutes()
    {
        if ($files = $this->app->getConfigFiles('path.routes'))
        {
            foreach ($files as $key => $filename)
            {
                require $this->app->config->get('path.routes').'/'.($filename);
            }
        }
    }


    /**
    * Refresh our route list
    *
    */
    protected function refreshRouteList()
    {
        $this->app->router->routes()->refreshRouteList();
    }

}
