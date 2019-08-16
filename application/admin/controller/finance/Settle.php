<?php

namespace app\admin\controller\finance;

use app\admin\model\Order;
use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 挂账单报表
 */
class Settle extends Backend
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
     * 挂账单报表
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
            $map['payment_from'] = 4;
            $total = $this->model
                ->with(["member"])
                ->where($where)
                ->where($map)
//                ->order($sort, $order)
                ->count();
            $list = $this->model
//                ->fetchSql(true)
                ->with(["member"])
                ->where($where)
                ->where($map)
                ->order($sort, $order)
//                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();
            $money = $this->model
//                ->fetchSql(true)
                ->with(["member"])
                ->where($where)
                ->where($map)
                ->sum("payable_money");
            $result = array("total" => $total, "rows" => $list, "extend" => ['money' => $money ?: 0 ]);

            return json($result);
        }
        return $this->view->fetch();
    }

    function edit($ids = null)
    {
        $id = input("ids");
        $row = Db::name("order")
            ->field("o.*,m.nickname")
            ->alias("o")
            ->join("member m","m.id = o.member_id")
            ->where(['o.id'=>$id])
            ->find();
        $this->assign([
            'row'=>$row
        ]);

        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $map['order_no'] = $params['order_no'];
            $data['pay_money'] = $params['pay_money'];
            if ($params['pay_money'] == 0){
                $this->error("请填写支付金额");
            }
            $data['payment_status'] = $params['payment_status'];
            $update_res = Db::name("order")->where($map)->update($data);
            if ($update_res == 1){
                $this->success();
            }else{
                $this->error(__('No rows were updated'));
            }
        }
        return $this->view->fetch();
    }

}
