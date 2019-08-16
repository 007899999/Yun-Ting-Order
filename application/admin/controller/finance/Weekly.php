<?php

namespace app\admin\controller\finance;

use app\common\controller\Backend;

/**
 * 周期周报表
 */
class Weekly extends Backend
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
     * 周期周报表(无分页)
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
            //开始日期写死  2019-7-1 周一
            $start_time = strtotime("2019-06-03");
            $end_time = strtotime(date('Y-m-d',(time()+(8-(date('w',time())==0?7:date('w',time())))*24*3600)));  //获取当前周日
            $week_count = ($end_time - $start_time)/(60*60*24*7);
            $list = [];
            $time = [];
            for ($i = 0 ; $i < $week_count ; $i ++) {
                $time[$i] = $start_time;
                $week_end = $start_time + 60 *60 *24 *7;
                $map['createtime'] = ['between',[$start_time,$week_end - 1]];
                $map['order_status'] = ['in',[1,2]];
                $list[$i] = $this->model
                    ->field("*,count(id) as order_count,sum(payable_money) as sum_payable_money,sum(pay_money) as sum_pay_money,count(member_id) as member_count")
                    ->where($where)
                    ->where($map)
                    ->order($sort, $order)
                    ->find();
                $start_time = $week_end;
            }
            foreach ($list as $k => $v){
                $map2['createtime'] = ['between',[$time[$k],$time[$k] + 60 * 60 * 24 * 7]];

                $list[$k]['time'] = date("Y-m-d",$time[$k]);
                $list[$k]['sum_payable_money'] = $list[$k]['sum_payable_money'] ?: '0';
                $list[$k]['sum_pay_money'] = $list[$k]['sum_pay_money'] ?: '0';
                $list[$k]['order_count'] = $list[$k]['order_count'] ?: '0';
                $list[$k]['member_count'] = $list[$k]['member_count'] ?: '0';
                $list[$k]['time_range'] = date("Y-m-d",$time[$k]). '——' .date("Y-m-d",$time[$k] + 60 * 60 * 24 * 7 - 1);

                if (!empty($v['order_count'])){
                    //单均消费
                    $list[$k]['order_avg'] = $v['sum_pay_money'] / $v['order_count'];
                    $list[$k]['order_avg'] = round($list[$k]['order_avg'],2);
                }
                $list[$k]['wechat'] = $this->model->field("payment_from,pay_money")->where(['payment_from'=>0])->where($map2)->sum("pay_money");
                $list[$k]['ali'] = $this->model->field("payment_from,pay_money")->where(['payment_from'=>1])->where($map2)->sum("pay_money");
                $list[$k]['cash'] = $this->model->field("payment_from,pay_money")->where(['payment_from'=>2])->where($map2)->sum("pay_money");
                $list[$k]['card'] = $this->model->field("payment_from,pay_money")->where(['payment_from'=>3])->where($map2)->sum("pay_money");
                $list[$k]['gua'] = $this->model->field("payment_from,pay_money")->where(['payment_from'=>4])->where($map2)->sum("payable_money");

                $list[$k]['timestamps'] = strtotime( $list[$k]['time']);
            }
            $result = array("total" => $week_count, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

}
