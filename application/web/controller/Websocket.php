<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/13
 * Time: 10:58
 */

namespace app\web\controller;

use app\web\model\Order_goods;
use think\Request;

class Websocket extends  Base {

    function __construct(Request $request = null)
    {
        parent::__construct($request);
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, DELETE");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Content-Type, X-Requested-With, Cache-Control,Authorization");
    }

    /** 判断客户端id & 包厢id是否绑定连接
     * balcony_id  包厢id
     */
    function is_connect(){
        $balcony_id = input("post.balcony_id/d");
        if (empty($balcony_id)){
            return $this->ajaxReturn(400,"参数为空");
        }
        $check = Gateway::isUidOnline($balcony_id);
        if ($check == 0){
            return $this->ajaxReturn(200,"该包厢目前未绑定,请发起连接绑定!");
        }else{
            $client_ids = Gateway::getClientIdByUid($balcony_id);
            return $this->ajaxReturn(201,"已有连接",$client_ids);
        }
    }

    /**
     * 包厢id & 客户端id 绑定
     * balcony_id      包厢id
     * client_id       客户端id
     */
    function bindClient(){
        $balcony_id = input("post.balcony_id");
        $client_id = input("post.client_id/s");
        if (empty($balcony_id) || empty($client_id)){
            return $this->ajaxReturn(400,"参数为空");
        }
        Gateway::bindUid($client_id,$balcony_id);
        return $this->ajaxReturn(801,"绑定成功");
    }

    /**
     * 查询菜品是否上菜信息
     */
    function getGoodsInfo(){
        $balcony_id = input("balcony_id/d");
        $client_id = input("client_id/s");
        if (empty($balcony_id) || empty($client_id)){
            return $this->ajaxReturn(400,"参数为空");
        }
        $map['balcony_id'] = $balcony_id;
        $map['status'] = 1;
        $map['is_read'] = 1;
        $lists = Order_goods::where(['balcony_id'=>$balcony_id,'status'=>1,'is_read'=>1])->select();
        foreach ($lists as $k => $v){
            Order_goods::where(['balcony_id'=>$balcony_id,'id'=>$v['id']])->update(['is_read'=>2]);
        }
        $data = [
            'code'=>200,
            'msg'=>'获取成功',
            'lists'=>$lists
        ];
        return json($data);
    }

}