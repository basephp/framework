<?php

namespace Base\Routing;

/**
* Parent Class to all Middleware
*
*/
class Middleware
{

    /**
    * Request Object
    *
    * @var string
    */
    protected $request;


    /**
    * Response Object
    *
    * @var string
    */
    protected $response;


    /**
    * Once Request has been loaded up, be sure to set the URL
    *
    */
    public function __construct($request, $response)
    {
        $this->request = $request;

        $this->response = $response;
    }

}
