<?php
/**
 * Created by PhpStorm.
 * User: james.xue
 * Date: 2020/4/2
 * Time: 11:20
 */

namespace James\Laravel\AliGreen\Contracts;

interface AliGreen
{
    /**
     * Get connection instance.
     *
     * @return \James\Laravel\AliGreen\Contracts\AliGreen
     */
    public function connection();
}