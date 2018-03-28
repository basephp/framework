<?php

namespace Base\Support\Facades;

class View extends Facade
{
    protected static function getClass()
    {
        return \Base\View\View::class;
    }
}
