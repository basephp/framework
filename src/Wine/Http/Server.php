<?php

namespace Wine\Http;

use \Wine\Support\Collection;

/**
* The server class
*
*/
class Server
{

	/**
	* Parameter $_SERVER
	*/
	protected $server;


	/**
	* Parameter $_GET
	*/
	protected $get;


	/**
	* Parameter $_POST
	*/
	protected $post;


	/**
	* Parameter $_COOKIE
	*/
	protected $cookie;


	/**
	* Parameter $_FILES
	*/
	protected $files;


	/**
	* Set up the server variables
	*
	*/
	public function __construct($server = [], $get = [], $post = [], $cookie = [], $files = [])
	{
		$this->server = new Collection($server);
		$this->get = new Collection($get);
		$this->post = new Collection($post);
		$this->cookie = new Collection($cookie);
		$this->files = new Collection($cookie);
	}

}
