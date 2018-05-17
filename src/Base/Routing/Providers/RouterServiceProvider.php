<?php

namespace Base\Routing\Providers;

use Base\Routing\Router;
use Base\ServiceProvider;

class RouterServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->register('router', new Router());

        $this->configRouter();

        $this->addVersionRoute();
        $this->addInfoRoute();

        $this->loadRoutes();
        $this->refreshRouteList();
    }


    /**
    * Get and display the BasePHP Version
    *
    */
    protected function addVersionRoute()
    {
        $this->app->router->routes()->add('GET','_base',function(){
            return $this->app->version();
        });
    }


    /**
    * Get and display the phpinfo()
    *
    */
    protected function addInfoRoute()
    {
        $this->app->router->routes()->add('GET','_php',function(){
            return phpinfo();
        });
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
