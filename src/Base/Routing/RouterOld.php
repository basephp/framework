<?php

namespace Base\Routing;

use Base\Application;
use Base\Http\Request;
use Base\Routing\Middleware;
use Base\Routing\MiddlewareQueue;


/**
* The router class
*
*
*/
class Router
{
    /**
    * The current group
    *
    * @var string
    */
    protected $useGroup = null;


    /**
    * The current middleware
    *
    * @var string
    */
    protected $useMiddleware = [];


    /**
    * The router patterns
    *
    * @var array
    */
    protected $patterns = [
        'any' => '.*',
        'num' => '[0-9]+',
        'alphanum' => '[a-zA-Z0-9]+',
        'alpha' => '[a-zA-Z]+'
    ];


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
    * The registered routes
    *
    * @var array
    */
    protected $routes = [];


    /**
    * The matched route
    *
    * @var array
    */
    protected $matchedRoute = [];


    /**
    * .....
    *
    *
    */
    public function attachMiddleware(array $middlewares = [])
    {
        $newMiddlewares = [];

        foreach($middlewares as $middleware)
        {
            $mDetails = explode(':',$middleware);
            $newMiddlewares[$mDetails[0]] = explode(',',(($mDetails[1]) ?? ''));
        }

        return $newMiddlewares;
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
            if (isset($this->matchedRoute['action']['closure']) && is_callable($this->matchedRoute['action']['closure']))
            {
                $content = $this->callClosure();
            }
            else
            {
                $content = $this->callController($app);
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
    protected function callClosure()
    {
        return $this->matchedRoute['action']['closure'](...$this->matchedRoute['parameters']);
    }


    /**
    * Run the controller class
    *
    * @return string
    */
    protected function callController(Application $app)
    {
        $controllerNamespace = ($this->namespace['controllers']) ?? '\\App\\Controllers';
        $controllerName = $controllerNamespace.'\\'.($this->matchedRoute['action']['controller']);

        $controller = new $controllerName();
        $controller->setRequest($app->request);
        $controller->setResponse($app->response);

        return $controller->callMethod($this->matchedRoute['action']['method'], $this->matchedRoute['parameters']);
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
        foreach(($this->getMatchedRoute()['middleware'] ?? []) as $name=>$middleware)
        {
            if (!isset($middlewares[$name])) continue;

            $middlewareMatch[] = $middlewares[$name];
        }

        // return a list of ready middlewares
        return $middlewareMatch;
    }


    /**
    * Clean the path
    *
    * @return string
    */
    protected function cleanPath($path)
    {
        $path = str_replace('//','/',$path);

        if ($path[0]!='/') $path = '/'.$path;

        return $path;
    }


    /**
    * set the action of the route Closure|Controller@Method
    *
    * @return array
    */
    protected function setAction($action)
    {
        if (is_callable($action))
        {
            return [
                'closure' => $action
            ];
        }

        $action = explode('::',$action);

        return [
            'controller' => $action[0],
            'method' => ($action[1]) ?? 'index'
        ];
    }


    /**
    * Get the pattern parameters found in paths.
    *
    * @return array
    */
    protected function getPatternParameters($path)
    {
        $params = [];

        if ($path!='')
        {
            preg_match_all('/\{(.*?)\}/', $path, $matches);

            if (isset($matches[1]))
            {
                foreach($matches[1] as $match)
                {
                    $params[] = $match;
                }
            }
        }

        return $params;
    }


    /**
    * Replace the pattern names with REGEX patterns if found.
    *
    * @return array
    */
    protected function setPathPatterns($path)
    {
        if (!empty($this->patterns))
        {
            foreach($this->patterns as $name=>$pattern)
            {
                // double check we have our "(" group ")"
                if (!preg_match('|^\(.*?\)$|',$pattern)) $pattern = '('.$pattern.')';

                // inject the patterns on our paths
                $path = preg_replace('/\{'.$name.'\}/', $pattern, $path);
            }
        }

        return $path;
    }


    /**
    * Register a new route with the router.
    *
    *
    */
    public function addRoute($httpVerb = ['GET'], $path, ...$params)
    {
        $prefix     = is_null($this->useGroup) ? '' : $this->useGroup . '/';

        $middleware = (isset($params[0]) && is_array($params[0])) ? $this->attachMiddleware($params[0]) : [];
        $middleware = array_merge($this->useMiddleware, $middleware);

        $action     = array_pop($params);

        $patterns   = $this->getPatternParameters($path);
        $path       = $this->setPathPatterns($path);

        // set the offical paths
        $officialPath = $this->cleanPath($prefix.$path);

        $this->routes[$officialPath] = [
            'http' => $httpVerb,
            'parameters' => $patterns,
            'middleware' => $middleware,
            'action' => $this->setAction($action)
        ];
    }


    /**
    * Add a "GET/POST" route
    *
    *
    */
    public function add($path, ...$params)
    {
        $this->addRoute(['GET','POST'],$path,...$params);

        return $this;
    }


    /**
    * Add a "GET" route
    *
    *
    */
    public function get($path, ...$params)
    {
        $this->addRoute(['GET'],$path,...$params);

        return $this;
    }


    /**
    * Add a "PUT" route
    *
    *
    */
    public function put($path, ...$params)
    {
        $this->addRoute(['PUT'],$path,...$params);
    }


    /**
    * Add a "PATCH" route
    *
    *
    */
    public function patch($path, ...$params)
    {
        $this->addRoute(['PATCH'],$path,...$params);
    }


    /**
    * Add a "DELETE" route
    *
    *
    */
    public function delete($path, ...$params)
    {
        $this->addRoute(['DELETE'],$path,...$params);
    }


    /**
    * Add a "OPTIONS" route
    *
    *
    */
    public function options($path, ...$params)
    {
        $this->addRoute(['OPTIONS'],$path,...$params);
    }


    /**
    * create the group
    *
    */
    public function group(...$params)
    {
        $oldGroup = $this->useGroup;
        $oldMiddleware = $this->useMiddleware;

        // path is always first
        $path = (isset($params[0]) && !is_array($params[0]) && !is_callable($params[0])) ? $params[0] : '';
        if ($path != '') {
            $path = $this->setPathPatterns($path);
            $this->useGroup = ltrim($oldGroup . '/' . $path, '/');
        }

        // middleware is always the first or second (never the third)
        $this->useMiddleware  = (isset($params[0]) && is_array($params[0])) ? $params[0] : [];
        $this->useMiddleware  = (isset($params[1]) && is_array($params[1])) ? $params[1] : $this->useMiddleware;
        $this->useMiddleware  = $this->attachMiddleware($this->useMiddleware);

        if (!empty($this->useMiddleware) && !empty($oldMiddleware)) {
            $this->useMiddleware = array_merge($oldMiddleware, $this->useMiddleware);
        }

        if (empty($this->useMiddleware) && !empty($oldMiddleware)) {
            $this->useMiddleware = $oldMiddleware;
        }

        // callback is always last (even though it can be the only)
        $callback = array_pop($params);

        if (is_callable($callback)) {
            $callback($this);
        }

        $this->useMiddleware = $oldMiddleware;
        $this->useGroup = $oldGroup;
    }


    /**
    * Register a new middleware on the route
    *
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
    * Register a new middleware on the route
    *
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
    *
    */
    public function registerMiddleware(array $middleware)
    {
        $this->registerSettings('middleware', $middleware);
    }


    /**
    * Register a new pattern for the routes
    *
    *
    */
    public function registerPatterns(array $patterns)
    {
        $this->registerSettings('patterns', $patterns);
    }


    /**
    * Register a new pattern for the routes
    *
    *
    */
    public function registerAutoload(array $autoload)
    {
        $this->registerSettings('autoload', $autoload);
    }


    /**
    * Match the path to a route
    *
    */
    public function match($path = '', $httpVerb = 'GET')
    {
        if ($path == '') return false;

        foreach ($this->routes as $routePath => $routeAction)
        {
            if (preg_match('#^' . $routePath . '$#i', $path, $matches))
            {
                array_shift($matches);

                $routeVerbs      = ($routeAction['http']) ?? ['GET'];
                $routeParameters = ($routeAction['parameters']) ?? [];

                if (!in_array($httpVerb, $routeVerbs)) continue;

                $routeAction['parameters'] = [];

                $tempParameters = [];
                $p = 0;
                foreach($matches as $index=>$match)
                {
                    $p++;
                    $routeParamIndex = ($routeParameters[$index]) ?? $index;
                    $tempParameters[$p] = $match;
                }

                $methodParams = explode('/',$routeAction['action']['method']);

                $routeAction['action']['method'] = $methodParams[0];

                unset($methodParams[0]);

                if (!empty($methodParams))
                {
                    foreach($methodParams as $param)
                    {
                        $p = ($tempParameters[str_replace('$','',$param)]) ?? $param;
                        if ($p === '') continue;

                        $routeAction['parameters'][] = $p;
                    }
                }
                else
                {
                    $routeAction['parameters'] = $tempParameters;
                }

                $this->matchedRoute = $routeAction;

                break;
            }
        }

        if (empty($this->matchedRoute))
        {
            $this->matchedRoute = [
                'parameters' => [],
                'action' => [
                    'method' => 'index',
                    'controller' => config('router.errors', 'Error')
                ]
            ];

            // built in 404 status when route can not be found.
            app()->response->setStatusCode(404);
        }
    }


    /**
    * Get the patterns
    *
    * @return array
    */
    public function getPatterns()
    {
        return $this->patterns;
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
    * Get the autoload middlewares
    *
    * @return array
    */
    public function getAutoload()
    {
        return $this->autoload;
    }


    /**
    * Get the routes
    *
    * @return array
    */
    public function getRoutes()
    {
        return $this->routes;
    }


    /**
    * Get the select route we have matched by the URI path
    *
    * @return array
    */
    public function getMatchedRoute()
    {
        return $this->matchedRoute;
    }


    /**
    * Return the instance self.
    *
    */
    public function self()
    {
        return $this;
    }

}
