<?php
/**
 * Created by PhpStorm.
 * User: james.xue
 * Date: 2020/4/2
 * Time: 11:14
 */

namespace James\Laravel\AliGreen;

use Illuminate\Foundation\Application;

class AliGreenManager
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;


    /**
     * Create a new Redis manager instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Notes:
     * Date: 2020/4/2 11:43
     * @return Contracts\AliGreen
     */
    public function store($scenes = null)
    {
        $scenes = $scenes ?: $this->getDefaultScenes();

        return (new AliGreen())->connection()->setScenes($scenes);
    }

    /**
     * Get the default aliyun scenes.
     *
     * @return string
     */
    protected function getDefaultScenes()
    {
        return $this->app['config']['aliyun.scenes'];
    }

    /**
     * Pass methods onto the AliGreen connection.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return $this->store()->{$method}(...$arguments);
    }
}