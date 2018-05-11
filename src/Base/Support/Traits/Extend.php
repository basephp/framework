<?php

namespace Base\Support\Traits;

use Closure;

trait Extend
{
    /**
     * The registered string macros.
     *
     * @var array
     */
    protected static $extensions = [];


    /**
     * Register a custom macro.
     *
     * @param  string $name
     * @param  object|callable  $macro
     *
     * @return void
     */
    public static function extend($name, $extension)
    {
        static::$extensions[$name] = $extension;
    }


    /**
     * Checks if macro is registered.
     *
     * @param  string  $name
     * @return bool
     */
    public static function hasExtension($name)
    {
        return isset(static::$extensions[$name]);
    }


    /**
     * Dynamically handle calls to the class.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     *
     * @throws \Exception
     */
    public static function __callStatic($method, $parameters)
    {
        if (! static::hasExtension($method)) {
            throw new Exception("Method {$method} does not exist.");
        }

        if (static::$extensions[$method] instanceof Closure) {
            return call_user_func_array(Closure::bind(static::$extensions[$method], null, static::class), $parameters);
        }

        return call_user_func_array(static::$extensions[$method], $parameters);
    }


    /**
     * Dynamically handle calls to the class.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     *
     * @throws \Exception
     */
    public function __call($method, $parameters)
    {
        if (! static::hasExtension($method)) {
            throw new Exception("Method {$method} does not exist.");
        }

        $extension = static::$extensions[$method];

        if ($extension instanceof Closure) {
            return call_user_func_array($extension->bindTo($this, static::class), $parameters);
        }

        return call_user_func_array($extension, $parameters);
    }
}
