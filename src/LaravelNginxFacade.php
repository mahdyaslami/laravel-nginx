<?php

namespace Mahdyaslami\LaravelNginx;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mahdyaslami\LaravelNginx\Skeleton\SkeletonClass
 */
class LaravelNginxFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-nginx';
    }
}
