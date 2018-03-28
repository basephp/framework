<?php

namespace Base\Support\Facades;

class Response extends Facade
{
    protected static function getClass()
    {
        return \Base\Http\Response::class;
    }
}
