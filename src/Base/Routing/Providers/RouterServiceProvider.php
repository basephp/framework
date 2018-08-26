<?php

namespace Base\Routing\Providers;

use Base\Routing\Router;
use Base\ServiceProvider;

class RouterServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->register('router', new Router($this->app));

        $this->configRouter();
        $this->addDevRoutes();

        $this->loadRoutes();
        $this->refreshRouteList();
    }


    /**
    * Add these routes when in development mode
    * include the phpinfo() and basephp version output when in dev mode.
    */
    protected function addDevRoutes()
    {
        if ($this->app->config->get('app.environment','development') !== 'production')
        {
            $this->addVersionRoute();
            $this->addInfoRoute();
        }
    }



    /**
    * Get and display the BasePHP Version
    *
    */
    protected function addVersionRoute()
    {
        $this->app->router->routes()->add('GET','_basephp',function(){
            return [
                'version' => $this->app->version()
            ];
        });
    }


    /**
    * Get and display the phpinfo()
    *
    */
    protected function addInfoRoute()
    {
        $this->app->router->routes()->add('GET','_phpinfo',function(){
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
