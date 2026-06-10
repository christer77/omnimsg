<?php

namespace OmniMsg\Facades;

use Illuminate\Support\Facades\Facade;

class OmniMsg extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'omnimsg';
    }
}
