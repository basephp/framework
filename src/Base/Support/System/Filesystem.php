<?php

namespace Base\Support\System;

class Filesystem extends BaseFacade
{
    protected static function getClass()
    {
        return \Base\Filesystem\Filesystem::class;
    }
}
