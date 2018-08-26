<?php

use Base\Application;
use Base\Routing\RouteCollection;


if (! function_exists('env'))
{

	/**
	* Gets the value of an environment variable.
	*
	* @param  string  $key
	* @param  mixed   $default
	* @return mixed
	*/
	function env($k, $default = null)
	{
		$v = getenv($k);

		if ($v === false) return $default;

		switch(strtolower($v))
		{
			case 'true':
				return true;
			case 'false':
				return false;
			case 'empty':
				return '';
			case 'null':
				return;
		}

		if (strlen($v) > 1 && preg_match('|^"|',$v) && preg_match('|"$|',$v))
		{
			return substr($v, 1, -1);
		}

		return $v;
	}

}


if (! function_exists('app'))
{

	/**
	* Quick access to get the application instance
	*
	* @return Base\Application
	*/
	function app()
	{
		return Application::getInstance();
	}

}


if (! function_exists('config'))
{

    /**
     * Get a specific config variable from the config instance
     *
     * @param  mixed  $k
     * @param  mixed  $default
     * @return mixed
     */
    function config($k = null, $default = null)
    {
		if (is_array($k))
		{
            return app()->config->set($k);
        }

		if (is_string($k))
		{
        	return app()->config->get($k, $default);
		}

		return app()->config;
    }

}


if (! function_exists('storage_path'))
{

    /**
     * Sets the path for a storage location
     *
     * @param  string  $path
     * @return string
     */
    function storage_path($path = '')
    {
        return config('path.storage').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

}


if (! function_exists('path'))
{

    /**
     * Get a given path in the application
     *
	 * @param  string  $dir
     * @param  string  $path
     * @return string
     */
    function path($dir = 'storage', $path = '')
    {
        return config('path.'.$dir).($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

}


if (! function_exists('ip_address'))
{

    /**
    * Return the users ip address
    *
    */
    function ip_address()
    {
        return app()->request->ipAddress();
    }

}


if (! function_exists('user_agent'))
{

    /**
    * Return the users (user agent)
    *
    */
    function user_agent()
    {
        return app()->request->userAgent();
    }

}


if (! function_exists('view'))
{

    /**
    * Renders a view (this is a quick way to push a new view out)
    *
    * @param  string  $content
    * @param  array   $data
    */
    function view($path = '', $data = [], $shared = true)
    {
        if ($path != '')
        {
            return app()->response->view()->setData($data, $shared)->render($path);
        }

        return app()->response->view();
    }

}


if ( ! function_exists('session'))
{

	/**
     * Quick way to get session details
	 *
	 * @param string $key
     * @param string $default
	 * @return mixed
	 */
	function session($key = '', $default = '')
	{
		if (isset(app()->request->session))
		{
			if ($key != '')
			{
				return app()->request->session->get($key, $default);
			}
			else
			{
				return app()->request->session;
			}
		}

        return false;
	}

}


if ( ! function_exists('redirect'))
{

	/**
     * Redirect the website to another page...
	 *
	 * @param string $uri
	 *
	 */
	function redirect(string $uri = '')
	{
		if ($uri)
		{
			return app()->response->redirect($uri);
		}

        return false;
	}

}


if ( ! function_exists('route'))
{

	/**
     * Return the router or the router collection based on arguments passed
	 *
	 * @param mixed $args
	 * @return \Base\Routing\Router
	 */
	function route(...$args)
	{
        if (!empty($args))
        {
            return app()->router->routes()->add(...$args);
        }

        return app()->router->routes();
	}

}


if ( ! function_exists('url'))
{

	/**
     * Get the current URL instance
	 *
	 * @return \Base\Support\Url
	 */
	function url()
	{
        return app()->request->url;
	}

}
