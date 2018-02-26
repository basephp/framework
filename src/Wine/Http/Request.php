<?php

namespace Wine\Http;

use \Wine\Support\Facades\URL;

/**
* The Request class
*
*
* Current Collections:
*
* ->server
* ->get
* ->post
* ->cookies
* ->files
*
*/
class Request extends Server
{

    /*
     * Any additional (non-essential requests)
     *
     */
    protected $additional = [];


	/**
	* Once Request has been loaded up, be sure to set the URL
	*
	*/
	public function __construct()
	{
		// let's set the global variables
		parent::__construct($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

		// now let's set the URL
		URL::setUrl($this->server->get('REQUEST_URI'));
	}


	/**
	* Get the GET or POST variable data
	*
	* @return mixed
	*/
	public function input($name, $default = '')
	{
		return ($this->fetch(['GET','POST'],$name)) ?? $default;
	}


	/**
	* Get cookie data
	*
	* @return mixed
	*/
	public function cookie($name, $default = '')
	{
		return $this->cookie->get($name, $default);
	}


	/**
	* get the request method (GET/POST)
	*
	* @return string
	*/
	public function method()
	{
		return $this->server->get('REQUEST_METHOD', 'GET');
	}


	/**
	* Check whether this request is a type of method
	*
	* @return bool
	*/
	public function isMethod($method = '')
	{
		if ($this->method() === $method)
		{
			return true;
		}

		return false;
	}


	/**
	* Check whether this request is AJAX
	*
	* @return bool
	*/
	public function isAjax()
	{
		return (strtolower($this->server->get('HTTP_X_REQUESTED_WITH','')) === 'xmlhttprequest');
	}


	/**
	* Check whether this request was made from the console (CLI)
	*
	* @return bool;
	*/
	public function isConsole()
	{
		return (PHP_SAPI === 'cli');
	}


	/**
	* Get the user's agent from the server headers
	*
	* @return string
	*/
	public function userAgent()
	{
		return $this->server->get('HTTP_USER_AGENT', '');
	}


	/**
	* Get valid IP address of user (if found)
	*
	* @source https://gist.github.com/cballou/2201933
	* @return string
	*/
	public function ipAddress()
	{
		$ip_keys = ['REMOTE_ADDR', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED'];
		foreach ($ip_keys as $key)
		{
			if ($this->server->has($key) === true)
			{
				foreach (explode(',', $this->server->get($key)) as $ip)
				{
					// trim for safety measures
					$ip = trim($ip);

					// attempt to validate IP
					if (valid_ip($ip))
					{
						return $ip;
					}
				}
			}
		}

		return '0.0.0.0';
	}


	/**
	* Fetch the given Super Global Requests
	*
	* @return string|null
	*/
	public function fetch(array $methods = ['GET','POST'], $name = '')
	{
		foreach($methods as $method)
		{
			switch($method)
			{
				case 'GET' :
					return $this->get->get($name, null);
				case 'POST' :
					return $this->post->get($name, null);
				default :
					return null;
			}
		}

		return null;
	}


	/**
	* Return current instance of url.
	*
	*/
	public function url()
	{
		return URL::self();
	}


	/**
	* Return current instance of self.
	*
	*/
	public function self()
	{
		return $this;
	}


    /**
	* Set the custom variable
	*
	*/
	public function __set($name, $value)
	{
		$this->additional[$name] = $value;
	}


    /**
    * Get a custom variable
    *
    * @param  string  $key
    * @return mixed
    */
    public function __get($key)
    {
        return $this->additional[$key] ?? null;
    }

}
