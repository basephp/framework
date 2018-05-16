<?php

namespace Base\Routing;

use Base\Routing\Route;
use Base\Support\Arr;


/**
* The Route Collection class
*
*
*/
class RouteCollection
{

    /**
    * The route collection storage
    *
    */
    protected $routes;


    /**
    * The route collection storage
    *
    */
    protected $allRoutes;


    /**
    * The route name list
    *
    */
    protected $nameList = [];


    /**
    * The router patterns
    *
    * @var array
    */
    public $patterns = [
        'any' => '.*',
        'num' => '[0-9]+',
        'alphanum' => '[a-zA-Z0-9]+',
        'alpha' => '[a-zA-Z]+'
    ];


    /**
    * Used for groups
    *
    */
    protected $useMiddleware = [];
    protected $usePrefix = [];
    protected $useDomain = [];

    protected $tempRoute;


    /**
    * Get all routes in collection
    *
    */
    public function all()
    {
        return $this->routes;
    }


    /**
     * Get routes from the collection by method.
     *
     * @param  string|null  $method
     * @return array
     */
    public function get($method = null)
    {
        return is_null($method) ? $this->routes : Arr::get($this->routes, $method, []);
    }


    /**
    * Get all the routes by names
    *
    */
    public function getNames()
    {
        return $this->nameList;
    }


    /**
    * Match a route based on a request
    *
    */
    protected function matches($route, $params)
    {
        array_shift($params);

        $tempParameters = [];
        $p = 0;
        foreach($params as $index=>$match)
        {
            $p++;
            $tempParameters[$p] = $match;
        }

        $mparams = $route->getAction()['method_parameters'] ?? [];

        if (!empty($mparams))
        {
            $addParams = [];
            foreach($mparams as $param)
            {
                // get the parameter name (removal of the number symbol)
                $p = ($tempParameters[str_replace('$','',$param)]) ?? $param;
                // if the parameter name is blank
                if ($p === '') continue;
                // set the parameters in the correct order
                $addParams[] = $p;
            }

            $route->setParameters($addParams);
        }
        else
        {
            $route->setParameters($tempParameters);
        }

        return $route;
    }


    /**
    * Match a route based on a request
    *
    */
    public function match($uri, $method = 'GET')
    {
        $domain = app()->request->url->getHost();

        foreach($this->get($method) as $matchUri => $route)
        {
            $withDomain = $domain.'/'.$matchUri;
            $uriWithDomain = $domain.$uri;

            // check if we are domain only routing
            if (preg_match('#^'. $matchUri .'$#i', $uriWithDomain, $params))
            {
                $route = $this->matches($route, $params);

                return $route;
            }

            // check all routing
            if (preg_match('#^'. $matchUri .'$#i', $uri, $params))
            {
                $route = $this->matches($route, $params);

                return $route;
            }
        }

        // add an error 404 route here...
        app()->response->setStatusCode(404);
        return $this->add($uri, config('router.errors', 'Error').'::index');
    }




    /**
    * Refresh the routes list
    *
    */
    public function refreshRouteList()
    {
        $this->nameList = [];

        foreach ($this->allRoutes as $route)
        {
            if ($route->getName())
            {
                $this->nameList[$route->getName()] = $route;
            }

            $routePatterns = $route->getPatterns();

            $routeUrl = $route->getDomain().$route->uri();
            $routeUrl = $this->setPathPatterns($routeUrl, $routePatterns);

            foreach($route->getMethods() as $method)
            {
                $this->routes[$method][$routeUrl] = $route;
            }
        }
    }


    /**
    * Replace the pattern names with REGEX patterns if found.
    *
    * @return array
    */
    protected function setPathPatterns($path, $routePatterns)
    {
        $routePatterns = array_merge($routePatterns, $this->patterns);

        if (!empty($routePatterns))
        {
            foreach($routePatterns as $name=>$pattern)
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
    * Add a new route to the collection
    *
    * @param mixed $args
    * @return Base\Routing\Route
    */
    public function add(...$args)
    {
        if (count($args) > 2) {
            $methods = $args[0];
            $uri = $args[1];
            $action = $args[2] ?? null;
        }
        else {
            $methods = '';
            $uri = $args[0];
            $action = $args[1] ?? null;
        }

        // if the action is not found,
        // we must end the creation of the route.
        if (is_null($action) || !$action) {
            $this->tempRoute = $uri;
            return $this;
        }

        $route = new Route($methods, $uri, $action);
        $route->domain(end($this->useDomain));
        $route->middleware($this->useMiddleware);
        $route->prefix($this->usePrefix);

        return $this->allRoutes[] = $route;
    }



    /**
    * Setting up a group of routes,
    * when we're complete, we need to remove the last element of arrays
    *
    * @param closure $fn
    */
    public function group($fn)
    {
        if (is_callable($fn))
        {
            $fn();

            array_pop($this->usePrefix);
            array_pop($this->useMiddleware);
            array_pop($this->useDomain);
        }
    }


    /**
    * Setting new patterns
    *
    * @param mixed $pattern
    */
    public function patterns($pattern)
    {
        $this->patterns = array_merge((array) $pattern, $this->patterns);
    }


    /**
    * Setting all the middleware for the group
    *
    * @param mixed $args
    * @return Base\Routing\RouteCollection
    */
    public function middleware(...$args)
    {
        $middleware = (array) $args[0];
        $group = $args[1] ?? null;

        $this->useMiddleware = array_merge($middleware, $this->useMiddleware);

        $this->group($group);

        return $this;
    }


    /**
    * Setting all the prefixes for a group
    *
    * @param mixed $args
    * @return Base\Routing\RouteCollection
    */
    public function prefix(...$args)
    {
        $prefix = (array) $args[0];
        $group = $args[1] ?? null;

        $this->usePrefix = array_merge($prefix, $this->usePrefix);

        $this->group($group);

        return $this;
    }


    /**
    * Setting the domain for the group
    *
    * @param mixed $args
    * @return Base\Routing\RouteCollection
    */
    public function domain(...$args)
    {
        $domain = $args[0];
        $group = $args[1] ?? null;

        $this->useDomain[] = $domain;

        $this->group($group);

        return $this;
    }


    /**
    * Get a named route
    *
    * @param string $name
    */
    public function getNamed($name)
    {
        return $this->nameList[$name] ?? false;
    }


    /**
    * Redirect to a named route
    *
    * @param string $name
    * @param array $parameters
    */
    public function path($name, $parameters)
    {
        $uri = $this->getNamed($name)->uri();

        foreach((array)$parameters as $k => $v)
        {
            $uri = preg_replace('/\{'.$k.'\}/', $v, $uri);
        }

        return $uri;
    }


    /**
    * Redirect to a named route
    *
    * @param string $name
    * @param array $parameters
    */
    public function redirect($name, $parameters)
    {
        $uri = $this->path($name, $parameters);

        if ($uri)
        {
            return redirect($uri);
        }

        return false;
    }


    /**
    * Add a route view
    *
    * @param string $path
    * @param array $data
    */
    public function view($path, $data = [])
    {
        return $this->add('GET',$this->tempRoute,function() use ($path, $data){
            return view($path, $data);
        });
    }


    /**
    * Redirect to a named route
    *
    * @param string $name
    * @param array $parameters
    */
    public function to($name, $parameters)
    {
        return $this->redirect($name, $parameters);
    }

}
