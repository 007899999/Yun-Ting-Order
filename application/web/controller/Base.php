<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/11
 * Time: 17:13
 */

namespace app\web\controller;

use think\Controller;

class Base extends Controller{
    public function ajaxReturn($code,$msg,$data = []){
        $return['code'] = $code;
        $return['msg'] = $msg;
        $return['data'] = $data;
        return json($return);
    }

    //检查登录的前置方法
    public function checkLoginSession(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, DELETE");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Content-Type, X-Requested-With, Cache-Control,Authorization");

        //检查token
        $balcony_id = input('post.balcony_id/d');
        if(empty($balcony_id)){
            echo json_encode(['code'=>403,'msg'=>'请传包厢id']);
            exit();
        }
    }
}