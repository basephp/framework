<?php

namespace Base\Routing;

use Base\Http\Request;
use Base\Http\Response;

/**
* The Controller Class
*
*/
class Controller
{

    /**
    * Request Object
    *
    * @see Base\Http\Request
    */
    public $request;


    /**
    * Request Object
    *
    * @see Base\Http\Response
    */
    public $response;


    /**
    * Set the request object
    *
    */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }


    /**
    * Set the response object
    *
    */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }


    /**
    * Execute the method on the controller.
    *
    * @param  string  $method
    * @param  array   $parameters
    */
    public function callMethod($method, $parameters)
    {
        return call_user_func_array([$this, $method], $parameters);
    }


    /**
    * Calls to missing methods on the controller.
    *
    * @param  string  $method
    * @param  array   $parameters
    * @return mixed
    *
    * @throws \Exception
    */
    public function __call($method, $parameters)
    {
        throw new \Exception("Method [{$method}] does not exist on [".get_class($this)."].");
    }

}
