<?php

namespace Wine\Support\Facades;

class URL extends Facade
{
    protected static function getClass()
    {
        return \Wine\Routing\Url::class;
    }
}
