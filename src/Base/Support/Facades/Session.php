<?php

namespace Base\Support\Facades;

class Session extends Facade
{
    protected static function getClass()
    {
        return \Base\Session\Session::class;
    }
}
