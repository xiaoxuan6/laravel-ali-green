<?php
/**
 * This file is part of PHP CS Fixer.
 *
 * (c) vinhson <15227736751@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace James\Laravel\AliGreen\Facades;

use Illuminate\Support\Facades\Facade;
use James\Laravel\AliGreen\AliGreen;
use James\Laravel\AliGreen\AliGreenManager;
use RuntimeException;

/**
 * @method static AliGreen checkText(array|string $text)
 * @method static AliGreen checkImg(array|string $img)
 * @method static AliGreen checkVideo(array|string $video)
 * @method static AliGreen checkResult(array|string $taskIds)
 * @method static AliGreenManager store(array $scenes = [])
 */
class LaravelAliGreen extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws RuntimeException
     */
    protected static function getFacadeAccessor(): string
    {
        return 'LaravelAliGreen';
    }
}
