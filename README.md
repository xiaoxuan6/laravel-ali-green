# laravel-ali-green

[![Latest Stable Version](https://poser.pugx.org/james.xue/laravel-ali-green/v/stable.svg)](https://packagist.org/packages/james.xue/laravel-ali-green)
[![Total Downloads](https://poser.pugx.org/james.xue/laravel-ali-green/downloads.svg)](https://packagist.org/packages/james.xue/laravel-ali-green)
[![Latest Unstable Version](https://poser.pugx.org/james.xue/laravel-ali-green/v/unstable.svg)](https://packagist.org/packages/james.xue/laravel-ali-green)
[![License](https://poser.pugx.org/james.xue/laravel-ali-green/license.svg)](https://packagist.org/packages/james.xue/laravel-ali-green)

## Install

```shell
composer require "james.xue/laravel-ali-green"

php artisan vendor:publish --tag=aliyun-green
```

## 增加 `文本` 查询缓存功能

将 `disable` 设置为 `true`, 每次 `本文` 检测的结果都会写入 `redis`, 重复检测直接从 `redis` 获取结果。

默认不安装 `redis` 扩展，如果使用该功能需要自行安装 `predis/predis`

```php
'cache' => [
    'disable' => env('ALI_CACHE_DISABLE', true),

    'tag_name' => env('ALI_CACHE_TAG_NAME', 'ali_green'),

    'redis' => [
        'url' => env('ALI_REDIS_URL',''),
        'host' => env('ALI_REDIS_HOST', '127.0.0.1'),
        'password' => env('ALI_REDIS_PASSWORD', null),
        'port' => env('ALI_REDIS_PORT', '6379'),
        'database' => env('ALI_REDIS_CACHE_DB', 1),
    ],
]
```

## Usage

以下方法均支持 `字符串` `数组`格式

```php
use James\Laravel\AliGreen\Facades\LaravelAliGreen;

// 文本
LaravelAliGreen::checkText('cnm');

// 图片
LaravelAliGreen::checkImg('http://nos.netease.com/yidun/2-0-0-4f903f968e6849d3930ef0f50af74fc2.jpg');

// 视频（异步）视频同步检测接口只支持通过上传视频截帧图片的方式进行检测，目前本扩展包不支持同步
LaravelAliGreen::checkVideo(['http://vfx.mtime.cn/Video/2019/03/21/mp4/190321153853126488.mp4','http://vfx.mtime.cn/Video/2019/03/19/mp4/190319222227698228.mp4']);

// 查询视频异步检测结果 taskId
LaravelAliGreen::checkResult(['vi4bzThu6JXD347OqceSNiqp-1sjE7S','vi6Apksz3BbCg56RtbnAUpzm-1sjE7S']); 
```

## License

MIT
