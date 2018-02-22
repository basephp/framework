<?php

namespace Wine\Support\Facades;

class Response extends Facade
{
    protected static function getClass()
    {
        return \Wine\Http\Response::class;
    }
}
