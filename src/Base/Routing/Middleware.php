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
     * The middleware queue to run
     *
     * @var \Base\Routing\MiddlewareQueue
     */
    protected $middleware;


    /**
     * Store all the Middleware Instances
     * These instances have already run through @handle()
     */
    protected $middlewareInstances = [];


    /**
     * The response object (saved for reference)
     *
     * @var \Base\Http\Response
     */
    protected $response;


    /**
     * initialize and run the middleware queue
     *
     * @see \Base\Routing\Router->run()
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
     * This runs "after" the controller actions
     *
     * @see \Base\Routing\Router->run()
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
     * Invoke a middleware object and run the "next()" middleware callable.
     * These must "return" the response, otherwise we fail.
     */
    public function __invoke($request)
    {
        $next = $this->middleware->get($this->index);

        if ($next)
        {
            $name = ($next['n']) ?? '';
            $mParams = explode(',',($next['p']) ?? '');

            // check if the middleware class actually exist,
            // otherwise we will need to close out this.
            if (!class_exists($name)) return false;

            // increment the middelware we instantiate
            $this->index++;

            // begin our middleware instance
            $middleware = new $name();

            // load our middelware handle instance for the request
            if (method_exists($middleware, 'handle'))
            {
                $this->middlewareInstances[] = $middleware;

                return $middleware->handle($request, $this, ...$mParams);
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
    * Calls all methods on the response object
    *
    * @param  string  $method
    * @param  array   $parameters
    * @return mixed
    */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->response, $method], $parameters);
    }
}
