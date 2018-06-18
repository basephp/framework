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
	function env($key, $default = null)
	{
		$value = getenv($key);

		if ($value === false) return $default;

		switch(strtolower($value))
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

		if (strlen($value) > 1 && preg_match('|^"|',$value) && preg_match('|"$|',$value))
		{
			return substr($value, 1, -1);
		}

		return $value;
	}

}


//--------------------------------------------------------------------


if (! function_exists('app'))
{

	/**
	* Quick access to get the application instance
	*
	*/
	function app()
	{
		return Application::getInstance();
	}

}


//--------------------------------------------------------------------


if (! function_exists('config'))
{

    /**
     * Get a specific config variable from the config instance
     *
     * @param  string  $option
     * @param  mixed   $default
     * @return mixed
     */
    function config($option = '', $default = null)
    {
		if ($option != '')
		{
        	return app()->config->get($option, $default);
		}

		return app()->config;
    }

}


//--------------------------------------------------------------------


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


//--------------------------------------------------------------------


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


//--------------------------------------------------------------------


if ( ! function_exists('session'))
{

	/**
     * Quick way to get session details
	 *
	 * @param string $key
     * @param string $default
	 * @return mixed
	 */
	function session($key, $default = '')
	{
		if ($key && isset(app()->request->session))
		{
			return app()->request->session->get($key, $default);
		}

        return false;
	}

}


//--------------------------------------------------------------------


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


//--------------------------------------------------------------------


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


//--------------------------------------------------------------------


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
