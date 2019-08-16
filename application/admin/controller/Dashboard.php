<?php

namespace app\admin\controller;

use app\admin\model\Memberevents;
use app\common\controller\Backend;
use think\Config;
use think\Db;

/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{

    /**
     * 查看
     */
    public function index()
    {
        //会员特殊日(这个月1号到下个月1号)
        $firstday = date("2000-m-01",time());
        $lastday = strtotime(date("2000-m-d",strtotime("$firstday +1 month")));
        $firstday = strtotime($firstday);
        $map['eventstime'] = ['between',[$firstday,$lastday]];
        $lists = Db::name("member_events")
            ->field("me.*,m.avatar,m.level,m.nickname,m.mobile")
            ->alias("me")
            ->join("member m","m.id = me.member_id")
            ->where($map)
            ->select();
        $order_count = \app\admin\model\Order::count();  //订单量
        $member_count = \app\admin\model\Member::count();//会员数量
        $goods_count = \app\admin\model\Goods::count();  //货品数量
        $income = \app\admin\model\Order::where(['payment_status'=>1])->sum("pay_money");       //除挂账（累计收入）
        $this->assign([
            'type'=>1,
            'order_count'  => $order_count,
            'goods_count'  => $goods_count,
            'income'  => $income,
            'member_count'  => $member_count,
            'lists'  => $lists,
        ]);
        return $this->view->fetch();
    }

    function getData(){
        $type = input("type",1);
        $name = '';
        $order_count = [];
        $time = [];
        switch ($type){
            case 1://日订单趋势
                $current_time = strtotime(date("Y-m-d H:0:0",time()));
                for ($i = 0 ; $i < 24 ; $i ++) {
                    $start_time = $current_time - 60*60;
                    $end_time = $current_time;
                    $map['createtime'] = ['between',[$start_time,$end_time]];
                    $order_count[$i] = \app\admin\model\Order::where($map)->count();
                    $time[$i] = date("Y-m-d H",$start_time)."点";
                    $current_time = $start_time;
                }
                $name = "日订单趋势";
                break;
            case 2://月订单趋势
                $current_time = strtotime(date("Y-m-d",time())) + 60*60*24;
                for ($i = 0 ; $i < 31 ; $i ++) {
                    $start_time = $current_time - 60*60*24;
                    $end_time = $current_time;
                    $map['createtime'] = ['between',[$start_time,$end_time]];
                    $order_count[$i] = \app\admin\model\Order::where($map)->count();
                    $time[$i] = date("Y-m-d",$start_time);
                    $current_time = $start_time;
                }
                $name = "月订单趋势";
                break;
            case 3://会员数量趋势
                $current_time = strtotime(date("Y-m-d",time())) + 60*60*24;
                for ($i = 0 ; $i < 31 ; $i ++) {
                    $start_time = $current_time - 60*60*24;
                    $end_time = $current_time;
                    $map['createtime'] = ['between',[$start_time,$end_time]];
                    $order_count[$i] = \app\admin\model\Member::where($map)->count();
                    $time[$i] = date("Y-m-d",$start_time);
                    $current_time = $start_time;
                }
                $name = "会员数量趋势";
                break;
        }
        $data = [
            'list'=>[
                [
                    'name'=>$name,
                    'data'=>array_reverse($order_count),
                ]
            ],
            'time'=>array_reverse($time),
            'type'=>$type
        ];
        return json($data);
    }


}
