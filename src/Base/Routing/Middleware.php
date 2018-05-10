<?php

namespace Base\Routing;

class Middleware
{
    /**
     * The current index in the queue.
     *
     * @var int
     */
    protected $index;


    /**
     * The middleware queue being run.
     *
     * @var \Base\Routing\MiddlewareQueue
     */
    protected $middleware;


    /**
     * Store all the Middleware Instances
     *
     */
    protected $middlewareInstances;


    /**
     * The middleware queue being run.
     *
     * @var \Base\Routing\MiddlewareQueue
     */
    protected $response;


    /**
     * initialize and run the middleware queue
     */
    public function initialize($middleware, $request, $response)
    {
        $this->middleware = $middleware;
        $this->response   = $response;
        $this->index = 0;

        return $this->__invoke($request);
    }


    /**
     * Run the terminate methods (if exist)
     */
    public function terminate($request, $response)
    {
        foreach($this->middlewareInstances as $middleware)
        {
            if (method_exists($middleware, 'terminate'))
            {
                $middleware->terminate($request, $response);
            }
        }
    }


    /**
     * Invoke a middleware object and run the "next" middleware
     */
    public function __invoke($request)
    {
        $next = $this->middleware->get($this->index);

        if ($next)
        {
            $this->index++;

            $middleware = new $next();

            if (method_exists($middleware, 'handle'))
            {
                $this->middlewareInstances[] = $middleware;

                return $middleware->handle($request, $this);
            }
            else
            {
                // if the middleware does not have a handle, we will need to end the script.
                // All middlewares must have a handle.
                return false;
            }
        }

        // return the updated response
        return $this->response;
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
        return call_user_func_array([$this->$response, $method], $parameters);
    }
}
