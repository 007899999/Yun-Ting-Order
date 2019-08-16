<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/11
 * Time: 17:12
 */

namespace app\web\controller;

use app\web\model\Balcony;
use app\web\model\Order_cart;
use app\web\model\Order_goods;
use think\Db;

class Login extends Base{

    /**
     * 获取所有包厢
     */
    function getAllBalcony(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, DELETE");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Content-Type, X-Requested-With, Cache-Control,Authorization");

        $Balcony = new Balcony();
        $lists = $Balcony->field("id as balcony_id,name")->select();
        return $this->ajaxReturn(200,"获取成功",$lists);
    }

    /**
     * 登录
     * balcony_id   包厢id
     * password     密码
     */
    function login(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, DELETE");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Content-Type, X-Requested-With, Cache-Control,Authorization");

        $Balcony = new Balcony();
        $balcony_id = input("post.balcony_id");
        $password = input("post.password");
        if (empty($balcony_id) || empty($password)){
            return $this->ajaxReturn(400,"密码为空");
        }

        $info = $Balcony->field("name,id,token,createtime")->where(['id'=>$balcony_id,'password'=>$password])->find();
        if (empty($info)){
            return $this->ajaxReturn(501,"密码错误");
        }
        //更新token
        $token = $Balcony->makeToken($info['createtime']);
        $Balcony->save(['token'=>$token],['id' => $balcony_id]);
        $info['token'] = $token;

        $map['id'] = $balcony_id;
        $map['password'] = $password;
        //清空会员登录
        Balcony::where($map)->update(['member_id'=>0,'is_online'=>1]);

        Order_cart::where(['balcony_id'=>$balcony_id])->delete(); //清空购物车

        //清空未支付的订单信息
        $order_ids = \app\web\model\Order::where(['order_status'=>0,'payment_status'=>0,'balcony_id'=>$balcony_id])->column("id");
        foreach ($order_ids as $k => $v){
            Order_goods::where(['order_id'=>$v])->delete();
            \app\web\model\Order::where(['id'=>$v])->delete();
        }

        return $this->ajaxReturn(200,"登录成功",$info);
    }

    /**
     * 退出登录
     */
    function logout(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, DELETE");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Content-Type, X-Requested-With, Cache-Control,Authorization");

        $balcony_id = input("post.balcony_id/d");
        $password = input("post.password");
        if (empty($password)){
            return $this->ajaxReturn(400,"必填参数为空");
        }
        $map['id'] = $balcony_id;
        $map['password'] = $password;
        Db::startTrans();
        try{
            $check = Balcony::where($map)->find();
            if (empty($check)){
                return $this->ajaxReturn(400,"密码错误");
            }
            Db::name("balcony")->where(['id'=>$balcony_id])->update(['is_online'=>0,'member_id'=>0]);
            Db::commit();
            return $this->ajaxReturn(200,"退出成功");
        } catch (\Exception $e) {
            Db::rollback();
            return $this->ajaxReturn(500,"退出失败,已清空信息");
        }
    }

    function test(){
        $array = [
            ['id' => 1, 'pid' => 0, 'name' => '这是主类1'],
            ['id' => 2, 'pid' => 0, 'name' => '这是主类2'],
            ['id' => 3, 'pid' => 1, 'name' => '父级为1子类'],
            ['id' => 4, 'pid' => 2, 'name' => '父级为2子类'],
            ['id' => 5, 'pid' => 3, 'name' => '父级为3子类'],
        ];
        return json($this->tree($array, 1));
    }

    //获取所有下级
    function tree($array, $pid)
    {
        $tree = array();
        foreach ($array as $key => $value) {
            if ($value['pid'] == $pid) {
                $value['child'] = $this->tree($array, $value['id']);
                if (!$value['child']) {
                    unset($value['child']);
                }
                $tree[] = $value;
            }
        }
        return $tree;
    }

    function test2(){
        $array = [
            ['id' => 1, 'pid' => 0, 'name' => '这是主类1'],
            ['id' => 2, 'pid' => 0, 'name' => '这是主类2'],
            ['id' => 3, 'pid' => 1, 'name' => '父级为1子类'],
            ['id' => 4, 'pid' => 2, 'name' => '父级为2子类'],
            ['id' => 5, 'pid' => 3, 'name' => '父级为3子类'],
            ['id' => 6, 'pid' => 5, 'name' => '父级为3子类'],
            ['id' => 7, 'pid' => 6, 'name' => '父级为3子类'],
        ];
        $info = [
            'id' => 7, 'pid' => 6, 'name' => '父级为3子类'
        ];
        return json($this->parent($array, $info));
    }

    //获取所有上级
    function parent($array, $info){
        if ($info['pid'] != 0){
            foreach ($array as $k => $v){
                if ($v['id'] == $info['pid']){
                    if ($v['pid'] != 0){
                        return $this->parent($array,$v);
                    }else{
                        return $v;
                    }
                }
            }
        }else{
            return $info;
        }
    }


}
