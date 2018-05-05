<?php

namespace Base\Http;

use \Base\Support\Collection;

/**
* \Base\Http\Server
*
* This class is called as a parent of the Http\Request
* @see \Base\Http\Request
*
*/
class Server
{

    /**
    * Server Variable $_SERVER
    */
    protected $server;


    /**
    * Server Variable $_GET
    */
    protected $get;


    /**
    * Server Variable $_POST
    */
    protected $post;


    /**
    * Server Variable $_COOKIE
    */
    protected $cookie;


    /**
    * Server Variable $_FILES
    */
    protected $files;


    /**
    * Set up the server variables (and place them into their collections)
    * @see \Base\Http\Request
    */
    public function __construct($server = [], $get = [], $post = [], $cookie = [], $files = [])
    {
        $this->server = new Collection($server);
        $this->get = new Collection($get);
        $this->post = new Collection($post);
        $this->cookie = new Collection($cookie);
        $this->files = new Collection($files);
    }


    /**
    * Access inaccessible properties
    * We use this beacuse we don't want direct property access
    */
    public function __get($name)
    {
        return $this->{$name};
    }

}
