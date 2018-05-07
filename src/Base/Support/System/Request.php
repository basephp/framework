<?php

namespace Base\Support\System;

class Request extends BaseFacade
{
    protected static function getClass()
    {
        return \Base\Http\Request::class;
    }
}
