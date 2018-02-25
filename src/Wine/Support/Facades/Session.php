<?php

namespace Wine\Support\Facades;

class Session extends Facade
{
    protected static function getClass()
    {
        return \Wine\Session\Session::class;
    }
}
