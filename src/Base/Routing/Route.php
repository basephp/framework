<?php

namespace Base\Routing;


/**
* The Route class
*
*
*/
class Route
{

    /**
    * The HTTP Verb
    * Request Methods
    *
    * @var array
    */
    protected $methods = ['GET'];


    /**
    * The route URI path
    *
    * @var string
    */
    protected $uri = '';


    /**
    * THe route actions
    * Controller or Closure
    *
    * @var array
    */
    protected $action = [];


    /**
    * The route domain specific
    *
    * @var string
    */
    protected $domain;


    /**
    * The route name
    *
    * @var string
    */
    protected $name = '';


    /**
    * The route middleware list
    *
    * @var array
    */
    protected $middleware = [];


    /**
    * The route prefix
    *
    * @var string
    */
    protected $prefix = '';


    /**
    * Custom parameters only for this route instance
    *
    * @var array
    */
    protected $patterns = [];


    /**
    * Creating a new route requires Methods, URI and Action
    *
    */
    public function __construct($methods, $uri, $action)
    {
        $this->uri = trim($uri, '/');

        $this->setMethods($methods);
        $this->setAction($action);
    }


    /**
    * Set the route HTTP Methods
    *
    */
    public function setMethods($methods)
    {
        // use the default methods, if does not exist.
        if (!$methods) return $this;

        $this->methods = (array) $methods;

        return $this;
    }


    /**
    * Set the route parameters
    *
    */
    public function setParameters($params)
    {
        $this->action['parameters'] = $params;

        return $this;
    }


    /**
    * Set the parameter patterns
    *
    */
    public function where($patterns)
    {
        $this->patterns = (array) $patterns;

        return $this;
    }


    /**
    * Get the parameter patterns
    *
    */
    public function getPatterns()
    {
        return $this->patterns;
    }


    /**
    * Set the parameters
    *
    */
    public function getParameters()
    {
        return $this->action['parameters'] ?? [];
    }


    /**
    * Get the action
    *
    * @param string $key
    * @return mixed $action
    */
    public function getAction($key = '')
    {
        if ($key != '')
        {
            return $this->action[$key] ?? false;
        }

        return $this->action;
    }


    /**
    * Set the route action
    *
    * @param mixed $action
    * @return $this
    */
    public function setAction($action = null)
    {
        if (is_callable($action))
        {
            $this->action['closure'] = $action;
        }
        else
        {
            if (!is_null($action))
            {
                $action = explode('/',$action);
                $controllerMethod = explode('::',$action[0]);

                $this->action['controller'] = $controllerMethod[0];
                $this->action['method'] = $controllerMethod[1] ?? 'index';

                unset($action[0]);

                $this->action['method_parameters'] = $action;
            }
        }

        $this->action['parameters'] = $this->getPatternParameters($this->uri);

        return $this;
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
    * Set the middlewares
    *
    */
    public function middleware($middleware)
    {
        $this->middleware = array_merge((array) $middleware,$this->middleware);

        return $this;
    }


    /**
    * Set the prefix of the route
    *
    */
    public function prefix($prefix = '')
    {
        // if the prefix is an array, lets just flatten that out
        $prefix = ($prefix) ? implode('/',array_reverse( (array) $prefix)) : '';

        // remove the trailing slash
        $prefix = trim($prefix, '/');
        $this->prefix = trim($this->prefix, '/');

        $this->prefix = ($this->prefix) ? $this->prefix.'/'.$prefix : $prefix;

        return $this;
    }


    /**
    * Set the domain of the route
    *
    */
    public function domain($domain)
    {
        $this->domain = rtrim($domain, '/');
        $this->domain = str_replace(['http://', 'https://'], '', $this->domain);

        return $this;
    }


    /**
    * Set the name of the route
    *
    */
    public function name($name)
    {
        $this->name = $name;

        return $this;
    }


    /**
    * Get the name of the route
    *
    */
    public function getName()
    {
        return $this->name;
    }


    /**
    * Get the domain
    *
    */
    public function getDomain()
    {
        return $this->domain;
    }


    /**
    * Get the methods
    *
    */
    public function getMethods()
    {
        return $this->methods;
    }


    /**
    * Get the middlewares
    *
    */
    public function getMiddleware()
    {
        return $this->middleware;
    }


    /**
    * Get the full uri of the route
    *
    */
    public function uri()
    {
        $prefix = trim($this->prefix, '/');
        $uri = trim($this->uri, '/');

        return '/'.(($prefix) ? $prefix.'/' : '').$uri;
    }


    /**
    * Add a route view
    *
    * @param string $path
    * @param array $data
    */
    public function view($path, $data = [])
    {
        $this->setAction(function() use ($path, $data){
            return view($path, $data);
        });
    }

}
