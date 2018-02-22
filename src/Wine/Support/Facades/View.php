<?php

namespace Wine\Support\Facades;

class View extends Facade
{
    protected static function getClass()
    {
        return \Wine\View\View::class;
    }
}
