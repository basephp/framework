<?php

namespace Wine\Support\Facades;

class Request extends Facade
{
    protected static function getClass()
    {
        return \Wine\Http\Request::class;
    }
}
