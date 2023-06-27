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

use Illuminate\Contracts\Support\Arrayable;
use James\AliGreen\AliGreen as AliGreenGreens;
use James\Laravel\AliGreen\Contracts\AliGreen as AliGreenInterface;
use James\AliGreen\Green\{VideoAsyncScanRequest, VideoAsyncScanResultsRequest};

class AliGreen extends AliGreenGreens implements AliGreenInterface, Arrayable
{
    protected array $result;

    protected array|string $scenes;

    /**
     * @var AliGreen
     */
    protected static AliGreen $_instance;

    /**
     * @var array
     */
    protected static array $response = [
        'code' => 500,
        'msg' => '不合法的参数！',
    ];

    public const ERROR_CODE = [
        280 => '任务正在执行中，建议您等待一段时间（例如5s）后再查询结果。',
        400 => '请求有误，通常由于请求参数不正确导致，请仔细检查请求参数。',
        401 => '请求失败，通常是由于使用了不安全的图片、视频、语音链接地址。',
        403 => '请求访问失败，通常由于您的图片、视频、语音链接无法访问导致，请确认公网是否可访问，并且无防盗链策略。',
        404 => '待检测内容未找到，通常是由于您的图片、视频、语音内容无法下载导致，请确认内容可通过公网访问到。',
        480 => '下载失败，请确认待检测内容的大小、分辨率（如果有）在API的限制范围内。',
        500 => '一般是服务端临时出错。建议重试，若持续返回该错误码，请通过工单联系我们。',
        580 => '数据库操作失败。建议重试，若持续返回该错误码，请通过工单联系我们。',
        581 => '超时。建议重试，若持续返回该错误码，请通过工单联系我们。',
        585 => '缓存出错。建议重试，若持续返回该错误码，请通过工单联系我们。',
        586 => '算法出错。请通过工单联系我们。',
        587 => '中间件出错。请通过工单联系我们。',
        588 => '请求频率超出配额。默认配额：图片检测50张/秒，视频检测20路/秒，语音检测20路/秒，文本检测100条/秒。如果需要调整配额，请通过工单联系我们。',
        589 => '待检测内容过大，请确保检测的内容在API的限制范围内。建议重试，若持续返回该错误码，请通过工单联系我们。',
        590 => '待检测内容格式错误，请确保检测的内容在API的限制范围内。',
        591 => '连接池满。请通过工单联系我们。',
        592 => '下载超时，下载时间限制为3s，请确保检测的内容大小在API的限制范围内。',
        594 => '任务过期，如taskId过期。',
        595 => '截帧失败，请通过工单联系我们。',
        596 => '账号未授权、账号欠费、账号未开通、账号被禁等原因，具体可以参考返回的msg。',
    ];

    /**
     * Get connection instance.
     *
     * @return AliGreenInterface
     */
    public function connection(): AliGreenInterface
    {
        if (empty(self::$_instance)) {
            $class = get_called_class();
            self::$_instance = new $class();
        }

        return self::$_instance;
    }

    /**
     * Notes: set scenes
     * Date: 2020/4/2 18:53
     *
     * @param array $scenes
     * @return AliGreen
     */
    public function setScenes(array $scenes): AliGreen
    {
        $this->scenes = $scenes;

        config(['aliyun.scenes' => $scenes]);

        return $this;
    }

    /**
     * Notes: 提交视频异步检测任务
     * Date: 2020/4/2 15:44
     *
     * @param $video
     * @return array
     *
     *  视频文件链接支持以下协议：HTTP和HTTPS。
     *  视频文件支持以下格式：.avi、.flv、.mp4、.mpg、.asf、.wmv、.mov、.wma、.rmvb、.rm、.flash、.mid、.ts。
     *  视频大小限制：单个视频大小不超过200MB。如果您有特殊需求（大视频），可以提工单进行调整。
     *  视频流支持以下协议：.rtmp、.hls、.http-flv。
     *  视频流时长限制：单个视频流检测任务最长支持24小时，超过24小时任务自动结束。
     *  视频检测的时间依赖于视频的下载时间。请保证被检测的视频文件所在的存储服务稳定可靠，建议您使用阿里云OSS存储服务存储视频文件。
     */
    public function checkVideo($video): array
    {
        if (! $video) {
            return self::$response;
        }

        $video = is_array($video) ? $video : [$video];

        $request = new VideoAsyncScanRequest();
        $request->setMethod('POST');
        $request->setAcceptFormat('JSON');

        $taskArr = [];
        foreach ($video as $k => $v) {
            $task = 'task' . $k;
            $$task = [
                'dataId' => md5(uniqid($task)),
                'url' => $v,
                'interval' => 1,
                'maxFrames' => 200,
            ];
            $taskArr[] = $$task;
        }

        $request->setContent(json_encode([
            'scenes' => $this->scenes,
            'audioScenes' => 'antispam',
            'tasks' => $taskArr,
        ]));

        $client = $this->getClient();
        $response = $client->getAcsResponse($request);

        if ($response->code == 200) {
            $result = $response->data;

            $this->result = ['code' => 200];
            foreach ($result as $value) {
                $this->result['msg'][] = [
                    'url' => $value->url,
                    'taskId' => $value->taskId,
                ];
            }

            return $this->toArray();
        }

        return (array) $response;
    }

    /**
     * Notes: 查询视频异步检测结果
     * Date: 2020/4/2 16:15
     *
     * @param array|string $taskIds
     * @return array 客户端定时轮询查询异步检测结果。建议您将查询间隔设置为30秒，最长不能超出一个小时，否则结果将会丢失。
     *
     * 客户端定时轮询查询异步检测结果。建议您将查询间隔设置为30秒，最长不能超出一个小时，否则结果将会丢失。
     */
    public function checkResult(array|string $taskIds): array
    {
        if (! $taskIds) {
            return self::$response;
        }

        $taskIds = is_array($taskIds) ? $taskIds : [$taskIds];

        $request = new VideoAsyncScanResultsRequest();
        $request->setMethod('POST');
        $request->setAcceptFormat('JSON');
        $request->setContent(json_encode($taskIds, JSON_UNESCAPED_UNICODE));

        $client = $this->getClient();
        $response = $client->getAcsResponse($request);

        if ($response->code == 200) {
            $this->result = ['code' => 200];
            foreach ($response->data as $value) {
                if (array_key_exists($value->code, self::ERROR_CODE)) {
                    $this->result = [
                        'code' => $value->code,
                        'msg' => self::ERROR_CODE[$value->code],
                    ];

                    return $this->toArray();
                }

                foreach ($value->results as $item) {
                    $this->result['msg'][] = [
                        'url' => $value->url,
                        'label' => $item->label,
                        'rate' => $item->rate,
                        'scene' => $item->scene,
                        'suggestion' => $item->suggestion,
                    ];
                }
            }

            return $this->toArray();
        }

        return (string) $response;
    }

    /**
     * Get the result as an array.
     */
    public function toArray(): array
    {
        return $this->result;
    }
}
