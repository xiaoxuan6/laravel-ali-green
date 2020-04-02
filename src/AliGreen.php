<?php
/**
 * Created by PhpStorm.
 * User: james.xue
 * Date: 2020/4/2
 * Time: 11:25
 */

namespace James\Laravel\AliGreen;

use Illuminate\Contracts\Support\Arrayable;
use James\AliGreen\AliGreen as AliGreenGreens;
use James\AliGreen\Green\VideoAsyncScanRequest;
use James\AliGreen\Green\VideoAsyncScanResultsRequest;
use James\Laravel\AliGreen\Contracts\AliGreen as AliGreenInterface;

class AliGreen extends AliGreenGreens implements AliGreenInterface,Arrayable
{
    /**
     * @var result
     */
    protected $result;

    /**
     * @var scenes
     */
    protected $scenes;

    /**
     * @var instance
     */
    protected static $_instance;

    /**
     * Get connection instance.
     *
     * @return AliGreenInterface
     */
    public function connection()
    {
        if(empty( self::$_instance)){
            $class = get_called_class();
            self::$_instance  = new $class();
        }

        return self::$_instance;
    }

    /**
     * Notes: set scenes
     * Date: 2020/4/2 18:53
     * @param $scenes
     */
    public function setScenes($scenes)
    {
        $this->scenes = $scenes;

        return $this;
    }

    /**
     * Notes: 提交视频异步检测任务
     * Date: 2020/4/2 15:44
     * @param $video
     * @return null|void
     *
     *  视频文件链接支持以下协议：HTTP和HTTPS。
     *  视频文件支持以下格式：.avi、.flv、.mp4、.mpg、.asf、.wmv、.mov、.wma、.rmvb、.rm、.flash、.mid、.ts。
     *  视频大小限制：单个视频大小不超过200MB。如果您有特殊需求（大视频），可以提工单进行调整。
     *  视频流支持以下协议：.rtmp、.hls、.http-flv。
     *  视频流时长限制：单个视频流检测任务最长支持24小时，超过24小时任务自动结束。
     *  视频检测的时间依赖于视频的下载时间。请保证被检测的视频文件所在的存储服务稳定可靠，建议您使用阿里云OSS存储服务存储视频文件。
     *
     */
    public function checkVideo($video)
    {
        if(!$video) {
            return null;
        }

        $video = is_array($video) ? $video : array($video);

        $request = new VideoAsyncScanRequest();
        $request->setMethod("POST");
        $request->setAcceptFormat("JSON");

        $taskArr = [];
        foreach($video as $k => $v){
            $task = 'task'.$k;
            $$task = [
                'dataId' =>  md5(uniqid($task)),
                "url" => $v,
                "interval" => 1,
                "maxFrames" => 200,
            ];
            $taskArr[] = $$task;
        }

        $request->setContent(json_encode([
            "scenes" => $this->scenes,
            "audioScenes" => "antispam",
            "tasks" => $taskArr,
        ]));

        $client = $this->getClient();
        $response = $client->getAcsResponse($request);

        if(200 == $response->code){
            $result = $response->data;

            $this->result = [];
            foreach($result as $value) {
                $this->result[] = [
                    'url' => $value->url,
                    'taskId' => $value->taskId,
                ];
            }

            return $this->toArray();
        }else{
            return response()->json(['code' => '500', 'msg' => '请求失败，请检测参数重试！']);
        }
    }

    /**
     * Notes: 查询视频异步检测结果
     * Date: 2020/4/2 16:15
     * @param array $taskIds
     * @return array|\Illuminate\Http\JsonResponse|string
     *
     * 客户端定时轮询查询异步检测结果。建议您将查询间隔设置为30秒，最长不能超出一个小时，否则结果将会丢失。
     */
    public function checkResult(array $taskIds)
    {
        $request = new VideoAsyncScanResultsRequest();
        $request->setMethod("POST");
        $request->setAcceptFormat("JSON");
        $request->setContent(json_encode($taskIds));

        $client = $this->getClient();
        $response = $client->getAcsResponse($request);

        if($response->code == 200) {

            $this->result = ['code' => 200] ;
            foreach ($response->data as $value) {
                if(280 == $value->code) {
                    $this->result = [
                        'code' => 280,
                        'msg' => "任务正在执行中，建议您等待一段时间（例如5s）后再查询结果。",
                    ];

                    return $this->toArray();
                }

                foreach ($value->results as $item) {
                    $this->result[] = [
                        'url' => $value->url,
                        'label' => $item->label,
                        'rate' => $item->rate,
                        'scene' => $item->scene,
                        'suggestion' => $item->suggestion,
                    ];
                }
            }
            return $this->toArray();

        } else {
            return response()->json(['code' => '500', 'msg' => '请求失败，请检测参数重试！']);
        }
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->result;
    }
}