<?php

namespace Wine\Routing;

use \Wine\Application;
use \Wine\Http\Request;
use \Wine\Http\Middleware;


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
	* The namespaces we will need to look up later
	*
	* @var array
	*/
	protected $namespaces = [];


	/**
	* The router patterns
	*
	* @var array
	*/
	protected $patterns = [];


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
		$this->loadMiddlewares($app);

		ob_start();

		if ($this->callRequestMiddleware($app) === true)
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

			$app->response->setOutput($output)->setBody($content);

			$this->callResponseMiddleware($app);

			$app->response->send();
		}
	}


	/**
	* Run the closure function
	*
	*
	* @return string
	*/
	protected function callClosure()
	{
		$content = $this->matchedRoute['action']['closure'](...$this->matchedRoute['parameters']);

		return $content;
	}


	/**
	* Run the controller class
	*
	*
	* @return string
	*/
	protected function callController(Application $app)
	{
		$controllerNamespace = ($this->namespace['controllers']) ?? '\\App\\Controllers';
		$controllerName      = $controllerNamespace.'\\'.$this->matchedRoute['action']['controller'];

		$controller = new $controllerName();
		$controller->setRequest($app->request);
		$controller->setResponse($app->response);

		$content = $controller->callMethod($this->matchedRoute['action']['method'], $this->matchedRoute['parameters']);

		return $content;
	}


	/**
	* Load Middlewares
	*
	*
	* @return bool
	*/
	protected function loadMiddlewares(Application $app)
	{
		$middlewares = $this->getMiddleware();
		$selectedMiddleware = $this->getMatchedRoute()['middleware'];

		foreach($selectedMiddleware as $name=>$middleware)
		{
			if (!isset($middlewares[$name])) continue;

			$app->addMiddleware($name, new $middlewares[$name]($app->request, $app->response));
		}
	}


	/**
	* Call middleware "before" request
	*
	*
	* @return bool
	*/
	protected function callRequestMiddleware(Application $app)
	{
		$middlewares = $app->getMiddlewares();
		$current     = $this->getMatchedRoute()['middleware'];

		foreach($middlewares as $name=>$middleware)
		{
			if (method_exists($middleware, 'request'))
			{
				$params = ($current[$name]) ?? [];
				if ($middleware->request(...$params) === false) return false;
			}
		}

		return true;
	}


	/**
	* Call middleware "after" response
	*
	*
	* @return void
	*/
	protected function callResponseMiddleware(Application $app)
	{
		$middlewares = $app->getMiddlewares();
		$current     = $this->getMatchedRoute()['middleware'];

		foreach($middlewares as $name=>$middleware)
		{
			if (method_exists($middleware, 'response'))
			{
				$params = ($current[$name]) ?? [];
				$middleware->response(...$params);
			}
		}
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
	public function add($path, ...$params)
	{
		$prefix     = is_null($this->useGroup) ? '' : $this->useGroup . '/';

		$middleware = (isset($params[0]) && is_array($params[0])) ? $this->attachMiddleware($params[0]) : [];
		$middleware = array_merge($this->useMiddleware, $middleware);
=
		$action     = array_pop($params);

		$patterns   = $this->getPatternParameters($path);
		$path       = $this->setPathPatterns($path);

		// set the offical paths
		$officialPath = $this->cleanPath($prefix.$path);

		$this->routes[$officialPath] = [
			'parameters' => $patterns,
			'middleware' => $middleware,
			'action' => $this->setAction($action)
		];
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
	public function match($path = '')
	{
		if ($path == '') return false;

		foreach ($this->routes as $routePath => $routeAction)
		{
			if (preg_match('#^' . $routePath . '$#i', $path, $matches))
			{
				array_shift($matches);

				$routeParameters = ($routeAction['parameters']) ?? [];

				$routeAction['parameters'] = [];

				foreach($matches as $index=>$match)
				{
					$routeParamIndex = ($routeParameters[$index]) ?? $index;
					$routeAction['parameters'][] = $match;
				}

				$this->matchedRoute = $routeAction;

				break;
			}
		}
	}


	/**
	* Get the patterns
	*
	*
	*/
	public function getPatterns()
	{
		return $this->patterns;
	}


	/**
	* Get the middlewares
	*
	*
	*/
	public function getMiddleware()
	{
		return $this->middleware;
	}


	/**
	* Get the middlewares
	*
	*
	*/
	public function getAutoload()
	{
		return $this->autoload;
	}


	/**
	* Get the routes
	*
	*
	*/
	public function getRoutes()
	{
		return $this->routes;
	}


	/**
	* ...
	*
	*
	*/
	public function getMatchedRoute()
	{
		return $this->matchedRoute;
	}


	/**
	* ...
	*
	*
	*/
	public function self()
	{
		return $this;
	}

}
