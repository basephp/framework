<?php

namespace Base\Http;

use \Base\Support\Url;

/**
* \Base\Http\Request
*
* Server Collections:
* @see \Base\Http\Server
* ------------------------------------------
*   $server
*   $get
*   $post
*   $cookies
*   $files
*
*
*/
class Request extends Server
{

    /**
    * Inject the URL Class
    *
    * @see \Base\Routing\Url
    */
    protected $url;


    /**
    * Once Request has been loaded up, be sure to set the URL
    *
    */
    public function __construct()
    {
        // let's set the global variables
        parent::__construct($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

        // now let's set the URL
        $this->url = new Url();
        $this->url->setHost($this->server->get('HTTP_HOST',config('app.domain','')));
        $this->url->setSecure($this->server->get('HTTPS',false));
        $this->url->setUri($this->server->get('REQUEST_URI','/'));
    }


    /**
    * Get the GET or POST or console ARGV variable data
    *
    * @return mixed
    */
    public function input($name, $default = null)
    {
        return type_cast(($this->fetch(['GET','POST','ARGV'],$name,$default)) ?? $default);
    }


    /**
    * get only GET variable
    *
    * @return mixed
    */
    public function get($name, $default = null)
    {
        return type_cast(($this->fetch(['GET'],$name,$default)) ?? $default);
    }


    /**
    * get only POST variable
    *
    * @return mixed
    */
    public function post($name, $default = null)
    {
        return type_cast(($this->fetch(['POST'],$name,$default)) ?? $default);
    }


    /**
    * get only FILE data
    *
    * @return mixed
    */
    public function file($name)
    {
        return ($this->fetch(['FILE'],$name,false)) ?? false;
    }


    /**
    * Get cookie data
    *
    * @return mixed
    */
    public function cookie($name, $default = null)
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
    public function fetch(array $methods = ['GET','POST'], $name = '', $default = null)
    {
        if (!is_array($methods)) $methods = [$methods];

        foreach($methods as $method)
        {
            switch($method)
            {
                case 'GET' :
                    return $this->get->get($name, $default);
                break;
                case 'POST':
                    return $this->post->get($name, $default);
                break;
                case 'FILE':
                    return $this->files->get($name);
                break;
                case 'ARGV':
                    if ($args = $this->server->get('argv', []))
                    {
                        $options = [];

                        for ($i = 1; $i < count($args); $i ++ )
                		{
                            if (!isset($args[$i])) continue;

                            $param = explode('=',$args[$i]);

                            $options[filter_var($param[0], FILTER_SANITIZE_STRING)] = filter_var(($param[1] ?? null), FILTER_SANITIZE_STRING);
                        }

                        return $options[$name] ?? null;
                    }
                break;
                default :
                    return null;
            }
        }
    }


    /**
    * getConsolePath
    *
    */
    public function getConsolePath()
    {
        if ($this->isConsole())
        {
            $arg = $this->server->get('argv', []);

            $path = $arg[1] ?? '/';

            return '/'.trim($path,'/');
        }

        return false;
    }

}
