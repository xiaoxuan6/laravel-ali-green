<?php
/**
 * This file is part of PHP CS Fixer.
 *
 * (c) vinhson <15227736751@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace James\Laravel\AliGreen\Tests;

use Illuminate\Foundation\Application;
use James\Laravel\AliGreen\Facades\LaravelAliGreen;
use James\Laravel\AliGreen\LaravelAliGreenServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param  Application  $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            LaravelAliGreenServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('aliyun', [
            'accessKeyId' => '******',
            'accessKeySecret' => '******',
            'scenes' => ['ad', 'porn', 'terrorism', 'qrcode'],
            'region' => 'cn-shanghai',
            'content' => [
                'cnm',
            ],
        ]);
    }

    /**
     * Get package aliases.
     *
     * @param  Application  $app
     * @return array
     */
    protected function getPackageAliases($app): array
    {
        return [
            'LaravelAliGreen' => LaravelAliGreen::class,
        ];
    }
}
