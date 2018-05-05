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
    public $server;


    /**
    * Server Variable $_GET
    */
    public $get;


    /**
    * Server Variable $_POST
    */
    public $post;


    /**
    * Server Variable $_COOKIE
    */
    public $cookie;


    /**
    * Server Variable $_FILES
    */
    public $files;


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

}
