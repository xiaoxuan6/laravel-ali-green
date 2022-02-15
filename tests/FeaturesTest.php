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

use James\Laravel\AliGreen\Facades\LaravelAliGreen;

class FeaturesTest extends TestCase
{
    public function taskIds(): array
    {
        return [
            ['vi6VeukskdQA37eSlYkqvfBh-1vLNvZ']
        ];
    }

    public function test_check_normal_text()
    {
        $result = LaravelAliGreen::checkText('你好');

        $this->assertIsArray($result);
        $this->assertEquals(200, $result['code'], $result['msg']['describe']);
    }

    public function test_check_text()
    {
        $result = LaravelAliGreen::checkText('傻逼');

        $this->assertIsArray($result);
        $this->assertEquals(-100, $result['code'], $result['msg']['describe']);


        $result = LaravelAliGreen::checkText('cnm');

        $this->assertIsArray($result);
        $this->assertEquals(-100, $result['code'], $result['msg']['describe']);
    }

    public function test_check_img()
    {
        $result = LaravelAliGreen::checkImg('http://nos.netease.com/yidun/2-0-0-4f903f968e6849d3930ef0f50af74fc2.jpg');

        $this->assertIsArray($result);
        $this->assertEquals(-100, $result['code'], $result['msg']['describe']);
    }

    public function test_check_video()
    {
        // 该场景支持的场景有：porn（色情）、terrorism（暴恐涉政视频）、logo（带有logo的视频）、ad（包含广告的视频）
        config(['aliyun.scenes' => ['porn', 'terrorism']]);

        $result = LaravelAliGreen::checkVideo('http://vfx.mtime.cn/Video/2019/03/19/mp4/190319222227698228.mp4');

        $this->assertIsArray($result);
        $this->assertEquals(200, $result['code'], is_array($result['msg']) ? '' : $result['msg']);

        return current($result['msg'])['taskId'];
    }

    /**
     * @dataProvider taskIds
     */
    public function test_check_result($taskId)
    {
        $result = LaravelAliGreen::checkResult($taskId);

        $this->assertIsArray($result);
        $this->assertEquals(200, $result['code'], $result['msg']);
    }

    /* 数组形式 */
    public function test_check_array_text()
    {
        $result = LaravelAliGreen::checkText(['傻逼']);

        $this->assertIsArray($result);
        $this->assertEquals(-100, $result['code'], $result['msg']['describe']);

        $result = LaravelAliGreen::checkText(['cnm']);

        $this->assertIsArray($result);
        $this->assertEquals(-100, $result['code'], $result['msg']['describe']);
    }

    public function test_check_array_img()
    {
        $result = LaravelAliGreen::checkImg(['http://nos.netease.com/yidun/2-0-0-4f903f968e6849d3930ef0f50af74fc2.jpg', 'http://img14.360buyimg.com/n8/jfs/t1/171001/3/27661/137322/61bada34E7fc1abbd/93795acd9b991d02.jpg']);

        $this->assertIsArray($result);
        $this->assertEquals(-100, $result['code'], $result['msg']['describe']);
    }
}
