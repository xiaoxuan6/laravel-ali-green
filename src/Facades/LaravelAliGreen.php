<?php
/**
 * Created by PhpStorm.
 * User: james.xue
 * Date: 2020/4/2
 * Time: 11:22
 */

namespace James\Laravel\AliGreen\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelAliGreen extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return "LaravelAliGreen";
    }
}