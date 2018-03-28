<?php

namespace Base\Support\Facades;

class URL extends Facade
{
    protected static function getClass()
    {
        return \Base\Routing\Url::class;
    }
}
