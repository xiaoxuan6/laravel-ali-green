<?php
/**
 * Created by PhpStorm.
 * User: james.xue
 * Date: 2020/4/2
 * Time: 11:13
 */

namespace James\Laravel\AliGreen;

use Illuminate\Support\ServiceProvider;

class LaravelAliGreenServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/aliyun.php' => config_path('aliyun.php')
            ], 'aliyun');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     *
     * @see \Illuminate\Container\Container
     */
    public function register()
    {
        $this->app->singleton("LaravelAliGreen", function($app){
            return new AliGreenManager($app);
        });

        $this->app->alias(AliGreenManager::class, 'LaravelAliGreen');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ["LaravelAliGreen"];
    }
}