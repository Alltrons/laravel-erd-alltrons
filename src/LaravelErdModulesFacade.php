<?php

namespace Alltrons\LaravelErdModules;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kevincobain2000\LaravelERD\LaravelERD
 */
class LaravelErdModulesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-erd';
    }
}