<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/12
 * Time: 16:45
 */

namespace app\web\controller;

use think\Db;

class Wechat extends Base{

    public function _initialize(){
        header("Access-Control-Allow-Origin: *");
    }

    const notify_url = 'http://yunting.mumarenkj.com/web/Wechat/wechat_notify';
    const wx_app_id = 'wx68bcfcab6cfd4abb';
    const wx_secret = '1c7b352e1c57dc3b0674d65d881dbb40';
    const wx_key = 'sRY9u9MdftadWKy0G99NMFiCQW5pITBh';
    const mch_id = '1548481501';

    function pay(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, DELETE");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Content-Type, X-Requested-With, Cache-Control,Authorization");
        $order_no = input("post.order_no");
        $payable_money = Db::name("order")->where(['order_no'=>$order_no,'payment_status'=>0,'order_status'=>0])->value("payable_money");
        if (empty($payable_money)){
            return $this->ajaxReturn(400,"订单不存在");
        }
        if ($payable_money == 0){
            return $this->ajaxReturn(405,"金额为0,不需要支付");
        }

        require_once('../vendor/wxpayapi/lib/WxPayNativePay.php');
        //①、获取用户openid
        $tools = new \NativePay();
        //②、统一下单
        $input = new \WxPayUnifiedOrder();

        $input->SetAppid(self::wx_app_id);       //APPID
        $input->SetMch_id(self::mch_id);         //商户号
        $input->SetNonce_str($this->generateNonceStr());//随机字符串，不长于32位
//        $input->SetNotify_url($config['notify_url'],'','html',true);//通知地址
        $input->SetNotify_url(self::notify_url); //通知地址
        $input->SetOut_trade_no($order_no);            //商户订单号
        $input->SetSpbill_create_ip("");         //终端ip
        $input->SetTotal_fee($payable_money*100);//价格
        $input->SetTrade_type("NATIVE");         //交易类型
        $input->SetProduct_id("");               //trade_type=NATIVE时，此参数必传。此参数为二维码中包含的商品ID，商户自行定义。
        //订单信息
        $input->SetAttach("云廷小坐");
        $input->SetBody("云廷小坐");

        $order = \WxPayApi::unifiedOrder($input);
        if ($order['result_code'] == "FAIL"){
            return json(['code'=>500,"msg"=>"获取失败","data"=>$order['err_code_des']]);
        }
        return json(['code'=>200,'msg'=>'获取成功','data'=>$order]);
    }

    /**
     * 回调   更新   订单状态、实付金额、流水号
     */
    function wechat_notify(){
        require_once('../vendor/wxpayapi/lib/WxPayConfig.php');
        $result = file_get_contents('php://input', 'r');

        $result = simplexml_load_string($result, null, LIBXML_NOCDATA);
        $result = json_encode($result);
        $result = json_decode($result, true);
        if ($result['result_code'] === 'SUCCESS' && $result['mch_id'] === \WxPayConfig::MCHID) {
            ksort($result);
            //拼接生成签名的字符串
            $sign_string = '';
            foreach ($result as $key => $value) {
                if ($key !== 'sign') {
                    $sign_string .= $key . '=' . $value . '&';
                }
            }
            $sign_string .= 'key=' . \WxPayConfig::KEY;
            $sign = strtoupper(md5($sign_string));
            if ($sign === $result['sign']) {
                $transaction_id = $result['transaction_id'];
                $order_no = $result['out_trade_no'];
//                $time_end = $result['time_end'];
                $map['order_no'] = $order_no;
                $payment_status = Db::name("order")->where($map)->value("payment_status");
                if ($payment_status == 0){
                    $data['payment_status'] = 1;
                    $data['order_status'] = 1;
                    $data['pay_time'] = time();
                    $data['batch'] = $transaction_id;
                    $data['pay_money'] = (float)$result['total_fee'] * 0.01;
                    Db::name("order")->where($map)->update($data);
                }
                file_put_contents ( ROOT_PATH . 'public' . DS."wechat.txt", date ( "Y-m-d H:i:s" ) . "  " . json_encode($result) . "\r\n", FILE_APPEND );
                $return = "<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>"; //返回成功给微信端 一定要带上不然微信会一直回调8次
                ob_clean();
                echo $return;
                exit;
            }
        }else{
            return "fail";
        }
    }

    //生成随机字符串
    function generateNonceStr($length=16){
        // 密码字符集，可任意添加你需要的字符
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for($i = 0; $i < $length; $i++)
        {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }


}