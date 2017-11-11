<?php

namespace Attogram\SharedMedia\Sandbox;

class NullObject
{
    const VERSION = '1.1.0';

    public function __call($name, $arguments = null)
    {
        return "__call($name," . json_encode($arguments).')';
    }
}
