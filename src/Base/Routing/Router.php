<?php

namespace Base\Routing;

use Base\Routing\Route;
use Base\Routing\RouteCollection;
use Base\Routing\Middleware;
use Base\Routing\MiddlewareQueue;
use Base\Http\Request;
use Base\Application;


/**
* The Router class
*
*
*/
class Router
{

    /**
    * The route collection class
    *
    * @var Base\Routing\RouteCollection
    */
    protected $routes;


    /**
    * The registered middlewares
    *
    * @var array
    */
    protected $middleware = [];


    /**
    * The registered middlewares
    *
    * @var array
    */
    protected $autoload = [];


    /**
    * Create a route collection on new instance
    *
    */
    public function __construct()
    {
        $this->routes = new RouteCollection();
    }


    /**
    * Access the route collection
    *
    * @return Base\Routing\RouteCollection
    */
    public function routes()
    {
        return $this->routes;
    }


    /**
    * Run the matched route and its actions
    *
    *
    * @return string
    */
    public function run(Application $app)
    {
        ob_start();

        // this will run and begin the middleware loop
        // if the response doesn't get returned, we will need to end route actions
        $middleware = new Middleware();
        $response = $this->callMiddleware($middleware);

        if ($response)
        {
            if (is_callable($app->request->route->getAction('closure')))
            {
                $content = $this->callClosure($app->request->route);
            }
            else
            {
                $content = $this->callController($app, $app->request->route);
            }

            $output = ltrim(ob_get_clean());

            @ob_end_clean();

            // setting the output and the content from route actions
            $app->response->setOutput($output)->setBody($content);

            // now that we have built our output, run the terminate middleware methods.
            // only if they even exist.
            $middleware->terminate($app->request, $app->response);
        }
    }


    /**
    * Run the closure function
    *
    * @return string
    */
    protected function callMiddleware($middleware)
    {
        return $middleware->initialize((new MiddlewareQueue($this->loadMiddlewares(app()))), app()->request, app()->response);
    }


    /**
    * Run the closure function
    *
    * @return string
    */
    protected function callClosure(Route $route)
    {
        return $route->getAction('closure')(...$route->getAction('parameters'));
    }


    /**
    * Run the controller class
    *
    * @return string
    */
    protected function callController(Application $app, Route $route)
    {
        $controllerNamespace = ($this->namespace['controllers']) ?? '\\App\\Controllers';
        $controllerName = $controllerNamespace.'\\'.($route->getAction('controller'));

        $controller = new $controllerName();
        $controller->setRequest($app->request);
        $controller->setResponse($app->response);

        return $controller->callMethod($route->getAction('method'), $route->getAction('parameters'));
    }


    /**
    * Load Middlewares
    *
    *
    * @return bool
    */
    protected function loadMiddlewares(Application $app)
    {
        $middlewareMatch = [];
        $middlewares = $this->getMiddleware();

        // setup the autoload middleware
        foreach(config('router.autoload', []) as $name)
        {
            if (!isset($middlewares[$name])) continue;

            $middlewareMatch[] = $middlewares[$name];
        }

        // setup the route selected middleware
        foreach(($app->request->route->getMiddleware() ?? []) as $middleware)
        {
            if (!isset($middlewares[$middleware])) continue;

            $middlewareMatch[] = $middlewares[$middleware];
        }

        // return a list of ready middlewares
        return $middlewareMatch;
    }


    /**
    * Match the path to a route
    *
    */
    public function match(Request $request)
    {
        if ($request->isConsole())
        {
            $route = $this->routes->match($request->getConsolePath());
        }
        else
        {
            $route = $this->routes->match($request->url->getPath(), $request->method());
        }

        $request->route = $route;

        return $route;
    }


    /**
    * Register defined settings on the router
    *
    */
    public function register(array $elements)
    {
        if (isset($elements['patterns'])) {
            $this->registerPatterns($elements['patterns']);
        }

        if (isset($elements['middleware'])) {
            $this->registerMiddleware($elements['middleware']);
        }

        if (isset($elements['autoload'])) {
            $this->registerAutoload($elements['autoload']);
        }

        if (isset($elements['controllers'])) {
            $this->namespace['controllers'] = $elements['controllers'];
        }
    }


    /**
    * Get the middlewares
    *
    * @return array
    */
    public function getMiddleware()
    {
        return $this->middleware;
    }


    /**
    * Register settings on the router
    *
    */
    protected function registerSettings($config = '', array $values)
    {
        foreach($values as $name=>$value)
        {
            if (is_string($value) && $value != '')
            {
                $this->$config[$name] = $value;
            }
        }

        $this->$config = array_unique($this->$config);
    }


    /**
    * Register a new middleware on the route
    *
    */
    public function registerMiddleware(array $middleware)
    {
        $this->registerSettings('middleware', $middleware);
    }


    /**
    * Register a new pattern for the routes
    *
    */
    public function registerPatterns(array $patterns)
    {
        $this->routes->patterns($patterns);
    }


    /**
    * Register a new pattern for the routes
    *
    */
    public function registerAutoload(array $autoload)
    {
        $this->registerSettings('autoload', $autoload);
    }

}
