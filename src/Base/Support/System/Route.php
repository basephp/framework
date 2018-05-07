<?php

namespace Base\Support\System;

class Route extends BaseFacade
{
    protected static function getClass()
    {
        return \Base\Routing\Router::class;
    }
}
