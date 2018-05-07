<?php

namespace Base\Support\System;

class Response extends BaseFacade
{
    protected static function getClass()
    {
        return \Base\Http\Response::class;
    }
}
