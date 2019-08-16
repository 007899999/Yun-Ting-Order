<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/12
 * Time: 10:36
 */

namespace app\web\controller;

use app\web\model\Balcony;
use app\web\model\Goods_attributes_price;
use app\web\model\Goods_evaluation;
use app\web\model\Order_cart;
use app\web\model\Order_goods;
use think\Db;
use think\Request;

class Order extends Base{
    private $order;
    function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->order = new \app\web\model\Order();
    }

    protected $beforeActionList = [
        'checkLoginSession',
    ];

    /**
     * 点单加入购物车
     * 判断库存  冻结库存  减少总库存   加入购物车
     * balcony_id   包厢id
     * attr_id      属性id
     * num          数量  默认1
     */
    function addCart(){
        $balcony_id = input("post.balcony_id/d");
        $attr_id = input("post.attr_id/d");
        $num = input("post.num/d",1);
        if(empty($attr_id)){
            return $this->ajaxReturn(400,"参数为空");
        }
        $info = Goods_attributes_price::get($attr_id);
        if (empty($info)){
            return $this->ajaxReturn(400,"属性不存在");
        }
        Db::startTrans();
        try{
            $sum_stock = \app\web\model\Goods::where(['id'=>$info['goods_id']])->value("sum_stock");
            $check = Common::keep_stock($attr_id,$num,$info['is_relation'],$info['raw'],$sum_stock,-1,$info['goods_id']);  //判断库存
            if ($check == -1){
                return $this->ajaxReturn(501,"库存不足");
            }
            //判断购物车是否存在  包厢id   属性id
            $check = Order_cart::where(['balcony_id'=>$balcony_id,'goods_id'=>$info['goods_id'],'attr_id'=>$attr_id])->find();
            if (empty($check)){
                $data_cart['balcony_id'] = $balcony_id;
                $data_cart['goods_id'] = $info['goods_id'];
                $data_cart['attr_id'] = $attr_id;
                $data_cart['num'] = $num;
                $data_cart['attribute_price_ids'] = $info['attribute_price_ids'];
                $data_cart['attribute_price_names'] = $info['attribute_price_names'];
                $data_cart['price'] = $info['price'];
                $data_cart['name'] = Db::name("goods")->where(['id'=>$info['goods_id']])->value("name");
                $data_cart['image'] = $info['image'];
                $Order_cart =  new Order_cart;
                $Order_cart->save($data_cart);
            }else{
                Order_cart::where(['id'=>$check['id']])->setInc("num",$num);
            }
            Db::commit();
            return $this->ajaxReturn(200,"点单成功");
        } catch (\Exception $e) {
            Db::rollback();
            return $this->ajaxReturn(500,$e->getMessage());
        }
    }

    /**
     * 删除购物车
     * cart_id  购物车id
     */
    function delCart(){
        $balcony_id = input('post.balcony_id/d');
        $cart_id = input("post.cart_id/d");
        if (empty($cart_id)){
            return $this->ajaxReturn(400,"参数为空");
        }
        $res = Order_cart::where(['id'=>$cart_id,'balcony_id'=>$balcony_id])->delete();
        if (!$res){
            return $this->ajaxReturn(501,"删除失败");
        }
        return $this->ajaxReturn(200,"删除成功!");
    }

    /**
     * 我的点单列表（购物车） &  已生成订单列表（暂未支付）
     */
    function myCart(){
        $balcony_id = input('post.balcony_id/d');
        $lists = Order_cart::where(['balcony_id'=>$balcony_id])->select();
        $total_price = [];
        foreach ($lists as $k => $v){
            $lists[$k]['attribute_price_names'] = implode("/",json_decode($v['attribute_price_names']));
            $price = $v['price'];
            $num = $v['num'];
            $total_price[$k] = $price * $num;
        }
        $count = count($lists);
        $money = array_sum($total_price);

        $map['balcony_id'] = $balcony_id;  //包厢id
        $map['payment_status'] = 0;        //支付状态 未支付
        $map['order_status'] = 0;          //订单状态 未支付
        $orderInfo = $this->order->field("id as order_id,order_no,payable_money")->where($map)->find();
        $con['order_id'] = $orderInfo['order_id'];
        $goodsLists = Order_goods::field("goods_id,name,attr_id,num,attribute_price_names,price,status,image")
            ->where($con)
            ->select();
        foreach ($goodsLists as $k2 => $v2){
            $goodsLists[$k2]['attribute_price_names'] = implode("/",json_decode($v2['attribute_price_names']));
        }
        $orderInfo['goods'] = $goodsLists;
        $data = [
            'cart_lists'=>$lists,
            'cart_money'=>$money,
            'cart_goods'=>$count,
            'orderInfo'=>$orderInfo,
        ];
        return $this->ajaxReturn(200,'获取成功',$data);
    }

    /**
     * 购物车结算  减库存 加销量
     * info    数组【id num】
     */
    function settle(){
        $balcony_id = input("post.balcony_id/d");
        $info = input("post.info/a");
        if (empty($info)){
            return $this->ajaxReturn(400,"参数为空");
        }

        Db::startTrans();
        try{
            $cart_id = [];
            foreach ($info as $k => $v){
                if (empty($v['id']) || empty($v['num'])){
                    return $this->ajaxReturn(400,"参数为空");
                }
                $cart_id[$k] = $v['id'];
            }
            $cartInfo = Order_cart::all($cart_id);
            if (empty($cartInfo)){
                return $this->ajaxReturn(400,"菜单的菜品为空!");
            }
            $total_price = [];
            $data_goods = [];
            foreach ($cartInfo as $k1 => $v1){
                $attrInfo = Goods_attributes_price::get($v1['attr_id']);
                $sum_stock = \app\web\model\Goods::where(['id'=>$attrInfo['goods_id']])->value("sum_stock");
                //判断库存
                $check = Common::keep_stock($attrInfo['id'],$info[$k1]['num'],$attrInfo['is_relation'],$attrInfo[ 'raw'],$sum_stock,1,$attrInfo['goods_id']);
                if ($check == -1){
                    return $this->ajaxReturn(501,"库存不足");
                }
                $total_price[$k1] = $v1['price']  * $info[$k1]['num'];
                $data_goods[$k1]['goods_id'] = $v1['goods_id'];
                $data_goods[$k1]['name'] = $v1['name'];
                $data_goods[$k1]['attr_id'] = $v1['attr_id'];
                $data_goods[$k1]['num'] = $info[$k1]['num'];
                $data_goods[$k1]['attribute_price_ids'] = $v1['attribute_price_ids'];
                $data_goods[$k1]['attribute_price_names'] = $v1['attribute_price_names'];
                $data_goods[$k1]['price'] = $v1['price'];
                $data_goods[$k1]['total_price'] = $total_price[$k1];
                $data_goods[$k1]['status'] = 0;
                $data_goods[$k1]['image'] = $v1['image'];
                $data_goods[$k1]['balcony_id'] = $balcony_id;
                $data_goods[$k1]['is_evaluate'] = Db::name("goods")->where(['id'=>$v1['goods_id']])->value("is_evaluate");
                $data_goods[$k1]['remindtime'] = Db::name("goods_attributes_price")->where(['id'=>$v1['attr_id']])->value("remindtime");
            }
            $sum_money = array_sum($total_price);
            //先判断是否有未支付的订单
            $map['balcony_id'] = $balcony_id;
            $map['payment_status'] = 0;
            $map['order_status'] = 0;
            $checkOrder = $this->order->where($map)->find();

            $member_id = Db::name("balcony")->where(['id'=>$balcony_id])->value("member_id");

            if (empty($checkOrder)){
                $order_no = Common::makeOrderNo();
                $data_order['order_no'] = $order_no;            //订单编号
                $data_order['sum_money'] = $sum_money;          //商品总价
                $data_order['payable_money'] = $sum_money;      //最终应支付金额
                $data_order['balcony_id'] = $balcony_id;        //包厢id
                $data_order['balcony_name'] = Db::name("balcony")->where(['id'=>$balcony_id])->value("name");//包厢名称
                $data_order['payment_status'] = 0;
                $data_order['order_status'] = 0;
                $data_order['is_invoice'] = 0;
                $data_order['is_handle'] = 0;
                $data_order['time'] = date("Y-m-d",time());
                $data_order['member_id'] = $member_id;
                $this->order->save($data_order);
                $order_id = $this->order->id;
                $balcony_name =  $data_order['balcony_name'];
                $message = [
                    'code'=>200,
                    'msg'=>$balcony_name.'有新的订单需要处理!',
                    'data'=>'order_no'
                ];
                Gateway::sendToUid("admin",json_encode($message));
            }else{
                $order_id = $checkOrder['id'];
                $order_no = $checkOrder['order_no'];
                $this->order->where(['id'=>$order_id])->setInc("sum_money",$sum_money);
                $this->order->where(['id'=>$order_id])->setInc("payable_money",$sum_money);
                Db::name("order")->where(['id'=>$order_id])->update(['member_id'=>$member_id]);
                $balcony_name = $checkOrder['balcony_name'];
                $message = [
                    'code'=>200,
                    'msg'=>$balcony_name.'增加了部分餐品!',
                    'data'=>'order_no'
                ];
                Gateway::sendToUid("admin",json_encode($message));
            }
            $Order_goods = new Order_goods();
            foreach ($data_goods as $k => $v){
                $data_goods[$k]['order_id'] = $order_id;
                $data_goods[$k]['order_no'] = $order_no;
            }
            $Order_goods->saveAll($data_goods);

            //清空购物车
            Order_cart::where("id","in",$cart_id)->delete();

            $payable_money = $this->order->where(['id'=>$order_id])->value("payable_money");
            $data = [
                'order_no'=>$order_no.'',
                'money'=>$payable_money
            ];

            Db::commit();
            return $this->ajaxReturn(200,"下单成功",$data);
        } catch (\Exception $e) {
            Db::rollback();
            return $this->ajaxReturn(500,$e->getMessage());
        }
    }

    /**
     * 查询订单是否支付完成(微信)
     * order_no     订单号
     */
    function is_pay(){
        $balcony_id = input("post.balcony_id/s");
        $order_no = input("post.order_no/s");
        if (empty($order_no)){
            return $this->ajaxReturn(400,"参数为空");
        }
        $map['balcony_id'] = $balcony_id;  //包厢id
        $map['payment_status'] = 1;
        $map['order_status'] = 1;
        $orderInfo = $this->order->where($map)->find();
        if (empty($orderInfo)){
            return $this->ajaxReturn(500,"暂未支付");
        }
        return $this->ajaxReturn(200,"支付成功",$order_no);
    }

    /**
     * 用餐评价商品列表
     * balcony_id       包厢id
     * order_no         订单号
     */
    function evaluationLists(){
        $balcony_id = input("post.balcony_id/s");
        $order_no = input("post.order_no/s").'';
        if (empty($order_no)){
            return $this->ajaxReturn(400,"参数为空");
        }
        $lists = Order_goods::field("id as order_goods_id,order_id,order_no,goods_id,name,num,image")
            ->where(['balcony_id'=>$balcony_id,'order_no'=>$order_no,'is_evaluate'=>1])
            ->select();
        return $this->ajaxReturn(200,"获取成功",$lists);
    }

    /**
     * 提交商品评价
     * eva_arr   数组【order_goods_id、score =>评分 1很差 2一般 3满意】
     */
    function addEvaluation(){
        $eva_arr = input("post.eva_arr/a");
        if (empty($eva_arr)){
            return $this->ajaxReturn(400,"参数为空");
        }
        Db::startTrans();
        try{
            $info = [];
            $data = [];
            foreach ($eva_arr as $k => $v){
                if (empty($v['order_goods_id']) || empty($v['score'])){
                    return $this->ajaxReturn(401,"参数为空");
                }
                $info = Order_goods::get($v['order_goods_id']);
                $data[$k]['goods_id'] = $info['goods_id'];
                $data[$k]['order_id'] = $info['order_id'];
                $data[$k]['order_goods_id'] = $info['id'];
                $data[$k]['score'] = $v['score'];
                $data[$k]['attr_id'] = $info['attr_id'];
                $data[$k]['attribute_price_ids'] = $info['attribute_price_ids'];
                $data[$k]['attribute_price_names'] = $info['attribute_price_names'];
                $data[$k]['num'] = $info['num'];
                $data[$k]['image'] = $info['image'];
                $data[$k]['balcony_id'] = $info['balcony_id'];
            }
            //判断该订单是否评价过
            $order_status = $this->order->where("id","=",$info['order_id'])->value("order_status");
            if ($order_status == 2){
                return $this->ajaxReturn(501,"该订单已评价!");
            }

            $Goods_evaluation = new Goods_evaluation();
            $Goods_evaluation->saveAll($data);

            $this->order->where("id","=",$info['order_id'])->update(['order_status'=>2]);

            Db::commit();
            return $this->ajaxReturn(200,"提交评价成功");
        } catch (\Exception $e) {
            Db::rollback();
            return $this->ajaxReturn(500,$e->getMessage());
        }
    }

    /**
     * 开票
     * order_no     订单编号
     */
    function drawInvoice(){
        $order_no = input("post.order_no");
        if (empty($order_no)){
            return $this->ajaxReturn(400,"参数为空");
        }
        $info = $this->order->field("payment_status,is_invoice,balcony_name")->where(['order_no'=>$order_no])->find();
        if (empty($info)){
            return $this->ajaxReturn(501,"订单不存在!");
        }
        if ($info['payment_status'] == 0){
            return $this->ajaxReturn(502,"该订单尚未支付!");
        }
        if ($info['is_invoice'] == 1){
            return $this->ajaxReturn(503,"该订单已申请开发票!");
        }
        $update_res = $this->order->where(['order_no'=>$order_no])->update(['is_invoice'=>1]);
        if ($update_res == 1){
            $message = [
                'code'=>200,
                'msg'=>$info['balcony_name'].'需要开票!',
                'data'=>'order_no'
            ];
            Gateway::sendToUid("admin",json_encode($message));
            return $this->ajaxReturn(200,"申请成功,请去收银台!");
        }else{
            return $this->ajaxReturn(504,"异常!");
        }
    }

    /**
     * 购物车数量
     */
    function cart_count(){
        $balcony_id = input('post.balcony_id/d');
        $count = Order_cart::where(['balcony_id'=>$balcony_id])->sum("num");
        return $this->ajaxReturn(200,'获取成功',$count);
    }

    function is_call(){
        $balcony_id = input('post.balcony_id/d');
        $balcony_name = Balcony::where(['id'=>$balcony_id])->value("name");
        $message = [
            'code'=>200,
            'msg'=>$balcony_name.'正在呼叫客服!',
            'data'=>'order_no'
        ];
        Gateway::sendToUid("admin",json_encode($message));
        return $this->ajaxReturn(200,'呼叫成功');
    }

    /**
     * 现金/刷卡
     */
    function otherPayment(){
        $balcony_id = input('post.balcony_id/d');
        $balcony_name = Balcony::where(['id'=>$balcony_id])->value("name");
        $message = [
            'code'=>200,
            'msg'=>$balcony_name.'申请现金/刷卡支付!',
            'data'=>'order_no'
        ];
        Gateway::sendToUid("admin",json_encode($message));
        return $this->ajaxReturn(200,'已通知前台,请稍候!');
    }

    /**
     * 查看订单是否支付
     */
    function showOrder(){
        $order_no = input("post.order_no");
        if (empty($order_no)){
            return $this->ajaxReturn(400,"订单号为空");
        }
        $check = \app\web\model\Order::where(['order_no'=>$order_no,'payment_status'=>1])->find();
        if (empty($check)){
            return $this->ajaxReturn(500,"订单未支付!");
        }
        return $this->ajaxReturn(200,"支付成功");
    }

}