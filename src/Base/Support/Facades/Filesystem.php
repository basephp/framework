<?php

namespace Base\Support\Facades;

class Filesystem extends Facade
{
    protected static function getClass()
    {
        return \Base\Filesystem\Filesystem::class;
    }
}
