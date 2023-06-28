<?php
/**
 * This file is part of james.xue/laravel-ali-green.
 *
 * (c) xiaoxuan6 <15227736751@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace James\Laravel\AliGreen;

use RuntimeException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Application;
use James\Laravel\AliGreen\Traits\CacheTrait;

class AliGreenManager
{
    use CacheTrait;

    /**
     * @var \Illuminate\Cache\TaggedCache
     */
    public static $cache;

    /**
     * Create a new Redis manager instance.
     *
     * @return void
     */
    public function __construct(protected Application $app)
    {
        $aliRedis = $this->app['config']['aliyun.cache.redis'];
        $this->app['config']->set('database.redis.cache', $aliRedis);

        self::$cache = Cache::store('redis')->tags($this->app['config']['aliyun.cache.tag_name']);
    }

    /**
     * @param array $scenes
     * @return Contracts\AliGreen
     */
    public function store(array $scenes = []): Contracts\AliGreen
    {
        $scenes = $scenes ?: $this->getDefaultScenes();

        return (new AliGreen())->connection()->setScenes($scenes);
    }

    /**
     * Get the default aliyun scenes.
     *
     * @return array
     */
    protected function getDefaultScenes(): array
    {
        return $this->app['config']['aliyun.scenes'];
    }

    /**
     * Pass methods onto the AliGreen connection.
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        $fn = fn () => $this->store()->{$method}(...$arguments);

        if($method == 'checkText' and $this->app['config']['aliyun.cache.disable']) {
            $result = [];
            $param = current($arguments);
            if(! $param) {
                throw new RuntimeException('invalid params');
            }

            $params = is_array($param) ? $param : [$param];
            foreach ($params as $key) {
                $md5Key = md5($key);
                if(self::$cache->has($md5Key)) {
                    $result[] = json_decode(self::$cache->get($md5Key), true);
                } else {
                    $response = $fn();
                    self::$cache->put($md5Key, json_encode($response, JSON_UNESCAPED_UNICODE));
                }
            }

            return $result;
        }

        return $fn();
    }
}
