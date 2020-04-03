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

About `xiaoxuan6/laravel-ali-green` specific configuration and use, refer to: [xiaoxuan6/laravel-ali-green](https://github.com/xiaoxuan6/laravel-ali-green)

## License

MIT
