<?php

namespace app\admin\controller\finance;

use app\common\controller\Backend;

/**
 * 今日营业概况报表
 */
class Overviewtoday extends Backend
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
     * 今日营业概况报表(按照支付类型分类)
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
                ->group("payment_from")
                ->count();
            $list = $this->model
                ->field("*,count(id) as order_count,sum(payable_money) as sum_payable_money,sum(pay_money) as sum_pay_money,count(member_id) as member_count")
                ->where($where)
                ->where($map)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->group("payment_from")
                ->select();
            $list = collection($list)->toArray();
            foreach ($list as $k => $v){
                //单均消费
                $list[$k]['order_avg'] = $v['sum_pay_money'] / $v['order_count'];
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }


}
