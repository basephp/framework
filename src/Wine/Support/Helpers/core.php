<?php

use \Wine\Support\Facades\View;
use \Wine\Application;


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


if (! function_exists('view'))
{
	/**
	* Renders a view (this is a quick way to push a new view out)
	*
	* @param  string  $content
	* @param  array   $data
	*/
	function view($path, $data = [], $shared = true)
	{
		return View::setData($data, $shared)->render($path);
	}
}


//--------------------------------------------------------------------
