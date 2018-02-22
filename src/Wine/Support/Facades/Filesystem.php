<?php

namespace Wine\Support\Facades;

class Filesystem extends Facade
{
    protected static function getClass()
    {
        return \Wine\Filesystem\Filesystem::class;
    }
}
