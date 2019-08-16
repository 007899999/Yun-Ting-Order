<?php

namespace app\admin\controller\finance;

use app\common\controller\Backend;
use think\Db;

/**
 * 营业概况报表
 */
class Overview extends Backend
{

    /**
     * Order模型对象
     * @var \app\admin\model\Order
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Order;
        $this->view->assign("paymentFromList", $this->model->getPaymentFromList());
        $this->view->assign("paymentStatusList", $this->model->getPaymentStatusList());
        $this->view->assign("isHandleList", $this->model->getIsHandleList());
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    /**
     * 营业概况报表
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $map['order_status'] = ['in',[1,2]];
            $total = $this->model
                ->where($where)
                ->where($map)
                ->order($sort, $order)
                ->group('time')
                ->count();
            $list = $this->model
//                ->distinct(true)
                ->field("*,count(id) as order_count,sum(payable_money) as sum_payable_money,sum(pay_money) as sum_pay_money,count(member_id) as member_count")
                ->where($where)
                ->where($map)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->group('time')
                ->select();
            $list = collection($list)->toArray();
            foreach ($list as $k => $v){
                //单均消费
                $list[$k]['order_avg'] = $v['sum_pay_money'] / $v['order_count'];
                $list[$k]['wechat'] = $this->model->field("payment_from,pay_money")->where(['time'=>$v['time'],'payment_from'=>0])->sum("pay_money");
                $list[$k]['ali'] = $this->model->field("payment_from,pay_money")->where(['time'=>$v['time'],'payment_from'=>1])->sum("pay_money");
                $list[$k]['cash'] = $this->model->field("payment_from,pay_money")->where(['time'=>$v['time'],'payment_from'=>2])->sum("pay_money");
                $list[$k]['card'] = $this->model->field("payment_from,pay_money")->where(['time'=>$v['time'],'payment_from'=>3])->sum("pay_money");
                $list[$k]['gua'] = $this->model->field("payment_from,pay_money")->where(['time'=>$v['time'],'payment_from'=>4])->sum("payable_money");

                $list[$k]['timestamps'] = strtotime( $list[$k]['time']);
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    //查看当前时间的所有订单
    function detail(){
        $type = input("type");
        $time = input("timestamp");
        $orders = [];
        switch ($type){
            case 1://营业概况报表  每日
                $time = date("Y-m-d",$time);
                $orders = $this->model->where(['time'=>$time])->paginate(30, false, ['query' => $this->request->get()]);
                break;
            case 2://周期周报表    7天
                $end_time = $time + 60 * 60 * 24 * 7;
                $map['createtime'] = ['between',[$time,$end_time - 1]];
                $orders = $this->model->where($map)->paginate(30, false, ['query' => $this->request->get()]);
                break;
            case 3://月报表  一个月
                $end_time= strtotime("next month", $time) - 1;//指定年月份月末时间戳
                $map['createtime'] = ['between',[$time,$end_time - 1]];
                $orders = $this->model->where($map)->paginate(30, false, ['query' => $this->request->get()]);
                break;
        }
        $lists = $orders->toArray()['data'];
        foreach ($lists as $k => $v){
            $goods = \app\admin\model\Ordergoods::where(['order_id'=>$v['id']])->select();
            foreach ($goods as $k1 => $v1){
                $goods[$k1]['attribute_price_names'] = implode("/",json_decode($v1['attribute_price_names']));
            }
            $lists[$k]['goods'] = $goods;
            $lists[$k]['nickname'] = Db::name("member")->where(['id'=>$v['member_id']])->value("nickname");
        }
        $this->assign([
            'lists'=>$lists,
            'orders'=>$orders
        ]);
        return $this->view->fetch("Balcony/historical_orders");
    }

}
