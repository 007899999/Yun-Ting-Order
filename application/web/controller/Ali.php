<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/12
 * Time: 16:45
 */

namespace app\web\controller;

use think\Controller;
use think\Db;

class Ali extends Base {

    public function _initialize(){
        header("Access-Control-Allow-Origin: *");
    }
    const appid = '2019072966057024';
    const rsaPrivateKey = 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCVhCkNhfuSQgTGWv7//cUP9UIez+ar8zIP5dtNKJwJA/7O2fpxvKexhB9X8nrtnvKWytd2eDEdVfFrTgt/bBSrWG4QSebRJzJNQfQwIOFBKSQUcPU2celZPguc9UadPkYkPhF+AOZ69BWHZlVctXgNmWvOHW98/C8VYf7Kfw3qu67lIQByZR9GM1fModS4muFx0QltBZF1rG1acODwV/CIPZzYCBIbkhVI9dV5q8pzD40yBDKZJGCkWC3Hi6uUJeKlyKv+XW3GRXe4kyo1lCF7pKmwyMiqCk0pVXk6JKnDKeoiPdi1DjOx85aXzcPtit6GUV7AW4xJxWi5XUeEA5UnAgMBAAECggEAUjOB2oSCBiwzsdYPgjBD6n0dqBEHMhwXtvPZePdNqgs/SPxAm81rqMYJ8XFHsmKD3vGQOfrkjctVWnhjC+AhEP8iK+RleTICjsUm+lrpXMjApFhiCFfiQgVBnz6eXw8AwyY4ubzG+mw9dd6GKd0/LsLdqUk3pVCElk31JeFqhlVikndHYuLk8vhQT0RrIRlmParmZKLaC32BTP5vHSKsOCjv3J96wmsHwDBjVzb9PrTxtm/La3mkMdd5VQng/IGi7WSIXDyhDdb4FEWBnXGMJaMZepJAbLGCcpqdE2ofEYMJwaxi8dnaobWbJ6luVofNgH4LpqjGwbKKA+u1tP3UIQKBgQD14vJK5+oTHbnAWbQiFWZbJiqFdu6jrgCC59nes2CwgqNHO62aVEfpcQgdMpRP6ERUSxAq7BtQl7VthOPrkRYcx0dgBndEoJapFinjVnJrHfrci+Cyd4yzexgOZMEsYxAh0Gpp+5tdrvt+dgGCvUzhSy4WjI3s3v8hUmJjMbQ+0QKBgQCbqnyRE7KIE8a4rpV+XMbXp4C3az/I6OP9ahZLPrjmuG1lKq2dahLfNRwOCax+22few/jFIO5+C6ESsFkGy9gjQ5sYw6nJnDGnPhztabpGECOSr8aNGYRc/v6s5xX05e/MFImHcrZzZbvjNHyoS99ZtvA9HuPjBluJGU9qX6DCdwKBgQDE1K08RTXdo5gEYEqQVi0fnge/2xrhL/L813hSi6lE4u6toRoxRnJibrceGFUdOkMobwY8NdEPzMHRL+X7tqLK2sNKHSLwbtTr+fACKjthgBhG4Do44vZg3pK9Qu5YB9zsFJh6yozui5qK+w/uhna6iqIULuOV0FTPadGUsj8lYQKBgH0ojXFEChZ4+B1DWbU4uTNpXQecdrN99Nmq83PaYWY4QNTHhMH9KGFI3V64DnYMyK4GmT0+JiM4DHDvUxUwzn5D/C7kGt6b9BxIx49no7pzfXaSV6zZKGBxMakpP7EhMzW+CXnbFLEhyfh7GgNixZjniq9J5+aAFRbdP8oCS0ovAoGAZjwHFEPgzVUjJzVMWl4ANxr63XanIOQayF5Wr7BWHPBycZ1znEk7w8II+bl/rM5onJEnW8T0OfDU4wUxCDANbBFiAnWZe6njnhLNMudBjC43u4MekYXgCwmf5/nUpyvExfc6psMegcIMVZgVwZF6uolmBXc6qBVzB5W0bh1JZ84=';
    const alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApKILkI2ly6d3Jgtt/Y5Y7DWZj+/oCyfYhfCp9HAh2WMRzJZpvirM5zgHJNF+x41rKEbOXfkeJsqFEeoMTKTXibVaJWZVG1ohE5wUNMJeV4Fs5rKNzPwlmzAHEWDufj+FT9ud+V3hlIYOeB46XptSzCdLZc6skMjzoVwuLuJ+gZlQ0+E7YobV62R2s2Poi6Y7/sen98Svc+KaDAvoG6Y1osHRfdBsB1FykOZSNzTQSeX9thcvxF9fT1sUlDWfpecM7vLe1grZV8+EqEbl9jN16kPK6DEfqMLex+Nem0XfxWx7Ojr2X/4aMvl8Umm3NZL6+BjR3C4Wi8X1KdygfIBeUwIDAQAB';
    const return_url = 'http://yunting.mumarenkj.com/web/Ali/return_url';
    const notify_url = 'http://yunting.mumarenkj.com/web/Ali/notify_url';
    const gatewayUrl = 'https://openapi.alipay.com/gateway.do';
    const charset = 'utf-8';

    function ali_pay(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, DELETE");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Content-Type, X-Requested-With, Cache-Control,Authorization");
        $order_no = input("order_no");
        $payable_money = Db::name("order")->where(['order_no'=>$order_no,'payment_status'=>0,'order_status'=>0])->value("payable_money");
        if (empty($payable_money)){
            return $this->ajaxReturn(400,"订单不存在");
        }
        if ($payable_money == 0){
            return $this->ajaxReturn(405,"金额为0,不需要支付");
        }
        require_once('../vendor/alipayapi/AopSdk.php');
        require_once('../vendor/alipayapi/aop/AopClient.php');
        //构造参数
        $aop = new \AopClient();

        $aop->gatewayUrl = self::gatewayUrl;
        $aop->appId = self::appid;
        $aop->rsaPrivateKey = self::rsaPrivateKey;
        $aop->alipayrsaPublicKey = self::alipayrsaPublicKey;
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset= self::charset;
        $aop->format='json';

        $request = new \AlipayTradePagePayRequest();
        $request->setReturnUrl(self::return_url);
        $request->setNotifyUrl(self::notify_url);
        $detail['product_code'] = 'FAST_INSTANT_TRADE_PAY';     //销售产品码，与支付宝签约的产品码名称。 注：目前仅支持FAST_INSTANT_TRADE_PAY
        $detail['out_trade_no'] = $order_no;                    //订单号
        $detail['subject'] = '云廷小坐';
//        $detail['total_amount'] = (float)$payable_money;                         //订单总金额，单位为元，精确到小数点后两位，取值范围[0.01,100000000]
        $detail['total_amount'] = 0.01;                         //订单总金额，单位为元，精确到小数点后两位，取值范围[0.01,100000000]
        $detail['body'] = '';
        $order_detail = json_encode($detail);
        $request->setBizContent($order_detail);
        $result = $aop->pageExecute ($request,'post');
        echo $result;
    }

    function return_url(){
        $this->redirect("http://yunting.mumarenkj.com/success");
    }

    //支付回调 小订单号
    function notify_url(){
        $post = input("");
        if ($post['trade_status'] == "TRADE_SUCCESS"){
            $order_no = str_replace("order_no","",$post['out_trade_no']);
            $orderInfo = Db::name("order")->where(['order_no'=>$order_no])->find();
            if (!empty($orderInfo)){
                if ($orderInfo['payment_status'] == 0){
                    $data['pay_time'] = strtotime($post['gmt_payment']);
                    $data['pay_money'] = (float)$post['receipt_amount'];
                    $data['batch'] = $post['trade_no'];
                    $data['payment_status'] = 1;
                    $data['payment_from'] = 1;
                    if ($orderInfo['order_status'] == 0){
                        $data['order_status'] = 1;
                    }
                    $update_res = Db::name("order")->where(['order_no'=>$order_no])->update($data);
                    if ($update_res == 1){
                        file_put_contents ( ROOT_PATH . 'public' . DS."ali.txt", date ( "Y-m-d H:i:s" ) . "  " . json_encode($post) . "\r\n", FILE_APPEND );
                        return "success";
                    }
                }
            }
        }else{
            return "fail";
        }
    }
}