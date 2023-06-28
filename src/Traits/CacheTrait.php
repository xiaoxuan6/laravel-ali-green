<?php
/**
 * This file is part of james.xue/laravel-ali-green.
 *
 * (c) xiaoxuan6 <15227736751@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace James\Laravel\AliGreen\Traits;

trait CacheTrait
{
    /**
     * Remove an item from the cache.
     *
     * @param string $key
     * @return bool
     */
    public static function forget($key)
    {
        return self::$cache->forget(md5($key));
    }

    /**
     * Remove all items from the cache.
     *
     * @return bool
     */
    public static function flush(): bool
    {
        self::$cache->flush();

        return true;
    }
}
