<?php
/**
 * This file is part of PHP CS Fixer.
 *
 * (c) vinhson <15227736751@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace James\Laravel\AliGreen;

use Illuminate\Foundation\Application;

class AliGreenManager
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * Create a new Redis manager instance.
     *
     * @param Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Notes:
     * Date: 2020/4/2 11:43
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
        return $this->store()->{$method}(...$arguments);
    }
}
