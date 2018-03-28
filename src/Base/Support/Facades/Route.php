<?php

namespace Base\Support\Facades;

class Route extends Facade
{
    protected static function getClass()
    {
        return \Base\Routing\Router::class;
    }
}
