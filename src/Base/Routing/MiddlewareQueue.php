<?php

namespace Base\Routing;


class MiddlewareQueue
{
    /**
     * The queue of middlewares.
     *
     * @var array
     */
    protected $queue = [];


    /**
     * Constructor
     *
     * The middleware assigned here should NOT have custom array keys.
     *
     * @param array $middleware The list of middleware.
     */
    public function __construct(array $middleware = [])
    {
        $this->queue = $middleware;
    }


    /**
     * Get the middleware at the provided index.
     *
     * @param int $index The middleware index to fetch.
     * @return callable|null Either the callable middleware or null
     *   if the index is undefined.
     */
    public function get($index)
    {
        if (isset($this->queue[$index]))
        {
            return $this->queue[$index];
        }

        return null;
    }


    /**
     * Add a new middleware to the end of the queue.
     *
     * @param string|array $middleware The middleware(s) to append.
     * @return $this
     */
    public function add($middleware)
    {
        if (is_array($middleware))
        {
            $this->queue = array_merge($this->queue, $middleware);

            return $this;
        }

        $this->queue[] = $middleware;

        return $this;
    }


    /**
     * Get all the middleware in the queue
     *
     * @return array
     */
    public function all()
    {
        return $this->queue;
    }


    /**
     * Clear the all middlewares in the queue
     *
     */
    public function clear()
    {
        $this->queue = [];
    }

}
