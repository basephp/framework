<?php

namespace Wine\Support\Facades;

class Route extends Facade
{
    protected static function getClass()
    {
        return \Wine\Routing\Router::class;
    }
}
