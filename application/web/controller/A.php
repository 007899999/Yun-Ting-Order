<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/6
 * Time: 11:12
 */

namespace app\api\controller;

use think\Controller;

class A extends Controller{

    const wx_app_id = "wx68bcfcab6cfd4abb";
    const wx_secret = "1c7b352e1c57dc3b0674d65d881dbb40";

    function sendToUid($type , $openid){
        switch ($type){
            case "":
                $template_id = "";
                $data = [

                ];
                break;
        }
        $access_token = $this->getWxAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;

    }

    //获取微信基础accessToken
    public function getWxAccessToken(){
        $wx_access_token = cache('wx_access_token');
        if(empty($wx_access_token)){
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.self::wx_app_id.'&secret='.self::wx_secret;

            $arr = reqUrl($url);

            if(array_key_exists('access_token',json_decode($arr,true))){
                $wx_access_token = json_decode($arr,true)['access_token'];
                cache('wx_access_token',$wx_access_token,5400);
            }else{
                //未知错误
            }
        }
//        echo $wx_access_token;
        return $wx_access_token;
    }
}