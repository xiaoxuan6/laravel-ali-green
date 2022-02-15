<?php
/**
 * This file is part of PHP CS Fixer.
 *
 * (c) vinhson <15227736751@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace James\Laravel\AliGreen\Contracts;

interface AliGreen
{
    /**
     * Get connection instance.
     *
     * @return AliGreen
     */
    public function connection(): AliGreen;
}
