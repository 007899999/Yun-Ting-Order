<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Db;

/**
 * 包厢
 *
 * @icon fa fa-circle-o
 */
class Balcony extends Backend
{
    
    /**
     * Balcony模型对象
     * @var \app\admin\model\Balcony
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Balcony;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = false;
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id','name','password','createtime','is_online','member_id']);
                
            }
            $list = collection($list)->toArray();
            foreach ($list as $k => $v){
                if ($v['is_online'] == 1){
                    $list[$k]['member'] = Db::name("member")->where(['id'=>$v['member_id']])->value("nickname");
                }
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 包厢的历史订单
     */
    function historical_orders(){
        $ordermodel = new \app\admin\model\Order();
        $ids = input("ids");
        $orders = $ordermodel->where(['balcony_id'=>$ids])->paginate(30, false, ['query' => $this->request->get()]);
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
        return $this->view->fetch("");
    }

    /**
     * 会员登录
     */
    function member_login(){
        $id = input("ids");
        $lists = \app\admin\model\Member::all();
        $this->assign([
            'lists'=>$lists,
            'id'=>$id
        ]);
        if ($this->request->isPost()) {
            Db::startTrans();
            try {
                $con['id'] = input("post.id");
                $param['member_id'] = input("post.member_id");
                Db::name("balcony")->where($con)->update($param);
                Db::commit();
                return 1;
            } catch (\Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
        }
        return $this->view->fetch("");
    }

    /**
     * 会员退出
     */
    function member_logout(){
        $balcony_id = input("ids");
        //清空会员登录
        $this->model->save([
            'member_id'  => 0
        ],['id' => $balcony_id]);

        Db::name("order_cart")->where(['balcony_id'=>$balcony_id])->delete(); //清空购物车

        //清空未支付的订单信息
        $order_ids = \app\web\model\Order::where(['order_status'=>0,'payment_status'=>0,'balcony_id'=>$balcony_id])->column("id");
        foreach ($order_ids as $k => $v){
            Db::name("order_goods")->where(['order_id'=>$v])->delete();
            Db::name("order")->where(['id'=>$v])->delete();
        }
        $this->success();
    }


}
