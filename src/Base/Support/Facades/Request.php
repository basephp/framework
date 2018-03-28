<?php

namespace Base\Support\Facades;

class Request extends Facade
{
    protected static function getClass()
    {
        return \Base\Http\Request::class;
    }
}
