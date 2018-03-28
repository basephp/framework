<?php

namespace Base\Support\Facades;

/**
 * The Facade Class.
 *
 */
abstract class Facade
{

    /**
     * Store all the facade instances here for easy access
     * We want to keep all the instances alive through the app
     *
     */
    protected static $instances;


    /**
     * Get our facade instance. (from the instance variable)
     * If not found, let's create a new instance and store it.
     *
     */
    protected static function getInstance($class)
    {
        if (is_object($class)) {
            return $class;
        }

        if (isset(static::$instances[$class])) {
            return static::$instances[$class];
        }

        return static::$instances[$class] = new $class();
    }


    /**
     * This method will allow the app to call static methods to our instances
     * Keeps our method APIs consistant through the app
     *
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getInstance( static::getClass() );

        if (! $instance) {
            throw new RuntimeException('A facade has not been set.');
        }

        return $instance->$method(...$args);
    }

}
