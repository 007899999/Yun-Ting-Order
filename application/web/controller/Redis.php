<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/31
 * Time: 17:53
 */

namespace app\web\controller;

use think\Cache;
use think\Controller;

class Redis extends Controller{
    function test(){
        $config = [
            'host'      => '127.0.0.1',

            'port'      => "6379",

            'password'  => '',

            'select'    => 0,

            'timeout'    => 0,

            'expire'    => 0,

            'persistent' => false,

            'prefix'    => '',

        ];

        $Redis=new \think\cache\driver\Redis($config);

        $Redis->set("test","test");
        $Redis->set("1111","2222");
        Cache::set();
        echo  $Redis->get("test");
        echo $Redis->get("1111");
    }
}