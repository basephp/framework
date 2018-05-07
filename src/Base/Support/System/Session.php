<?php

namespace Base\Support\System;

class Session extends BaseFacade
{
    protected static function getClass()
    {
        return \Base\Session\Session::class;
    }
}
