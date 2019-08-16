<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use app\web\controller\Gateway;
use app\web\model\Order_goods;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 订单管理-待支付订单
 *
 * @icon fa fa-circle-o
 */
class Order extends Backend
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
            $map['payment_status'] = 0;
            $map['order_status'] = 0;
//            $map['payment_from'] = ['notin',[0,1,2,3,4]];
            $total = $this->model
                    ->where($where)
                    ->where($map)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->where($where)
                    ->where($map)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id','order_no','payable_money','payment_from','payment_status','balcony_name','createtime','is_handle']);
                
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 订单详情
     */
    function detail(){
        $order_id = input("ids");
        //订单详情
        $info = $this->model->where(['id'=>$order_id])->find();
        //订单商品
        $goods = Order_goods::where(['order_id'=>$order_id])->select();
        foreach ($goods as $k => $v){
            $goods[$k]['attribute_price_names'] = implode("/",json_decode($v['attribute_price_names'],true));
        }
        //商品数量
        $goods_count = count($goods);
        $nickname = Db::name("member")->where(['id'=>$info['member_id']])->value("nickname");
        $info['nickname']=$nickname;
        $this->assign([
            'info'=>$info,
            'goods'=>$goods,
            'goods_count'=>$goods_count
        ]);
        return $this->view->fetch();
    }

    /**
     * 修改金额
     */
    function modify_money($ids = null){
        $id = input("ids");
        $row = $this->model->where(['id'=>$id])->find();
        $this->assign([
            'row'=>$row
        ]);
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch("");
    }

    /**
     * 刷卡支付
     */
    function card($ids = null){
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $params['payment_status'] = 1;
                    $params['order_status'] = 1;
                    $params['payment_from'] = 3;   //支付方式  刷卡
                    $result = $row->allowField(true)->save($params);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 现金支付
     */
    function cash($ids = null){
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $params['payment_status'] = 1;
                    $params['order_status'] = 1;
                    $params['payment_from'] = 2;   //支付方式  现金
                    $result = $row->allowField(true)->save($params);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 挂账
     */
    function settle($ids = null){
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $params['payment_from'] = 4;   //支付方式  挂账
                    $params['order_status'] = 1;
                    $result = $row->allowField(true)->save($params);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 上餐  更改备餐状态  减冻结库存
     */
    function dinner(){
        $order_goods_id = input("id");
        $map['id'] = $order_goods_id;
        $data['status'] = 1;
        $data['is_read'] = 1;
        $data['updatetime'] = time();
        $res = Db::name("order_goods")->where($map)->update($data);
        $info = Db::name("order_goods")->where($map)->find();
        $this->common_stock($info,1);
        $message = [
            'code'=>200,
            'msg'=>'有新菜品上餐了',
        ];
        if ($res == 1){
            Gateway::sendToUid($info['balcony_id'],json_encode($message));
            return 1;
        }else{
            return -1;
        }
    }

    /**
     * 删除订单商品(减最终应支付金额)
     */
    function del_goods(){
        $order_goods_id = input("id");
        // 启动事务
        Db::startTrans();
        try{
            $info = \app\admin\model\Ordergoods::where(['id'=>$order_goods_id])->find();
            $order_id = $info['order_id'];
            $total_price = $info['total_price'];
            $orderInfo = \app\admin\model\Order::where(['id'=>$order_id])->find();
            if ($orderInfo['payable_money'] >= $total_price){ //若最终实付金额大于该商品金额
                Db::name("order")->where(['id'=>$order_id])->setDec("payable_money",$total_price);
                Db::name("order")->where(['id'=>$order_id])->setDec("sum_money",$total_price);
            }else{
                Db::name("order")->where(['id'=>$order_id])->update(["payable_money"=>0]);
                Db::name("order")->where(['id'=>$order_id])->update(["sum_money"=>0]);
            }
            \app\admin\model\Ordergoods::destroy($order_goods_id);

            //减销量  加库存  减冻结库存
            $this->common_stock($info,2);
            //判断所有商品是否删除完成(若无商品 则删除订单)
            $goods_count = \app\admin\model\Ordergoods::where(['order_id'=>$order_id])->count();
            if ($goods_count <= 0){
                \app\admin\model\Order::destroy($order_id);
            }
            // 提交事务
            Db::commit();
            return 1;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $e->getMessage();
        }
    }

    function common_stock($info,$type){
        //减销量  加库存  减冻结库存
        $priceInfo = Db::name("goods_attributes_price")->where(['id'=>$info['attr_id']])->find();
        if (!empty($priceInfo)){
            if ($priceInfo['is_relation'] != 0){//关联  加总库存
                $number = $priceInfo['raw'] * $info['num'];
                if ($type == 2) {
                    Db::name("goods")->where(['id'=>$info['goods_id']])->setInc("sum_stock",$number);
                }
            }else{
                $number = $info['num'];
                if ($type == 2) {
                    Db::name("goods_attributes_price")->where(['id'=>$info['attr_id']])->setInc("raw",$number);
                }
            }
            if ($type == 1){//上餐  只减冻结库存
                Db::name("goods_attributes_price")->where(['id'=>$info['attr_id']])->setDec("freezing_stock",$number);
            }
            if ($type == 2){//删除订单商品
                if ($info['status'] == 0){//若在备餐下删除订单商品  则减掉冻结库存
                    Db::name("goods_attributes_price")->where(['id'=>$info['attr_id']])->setDec("freezing_stock",$number);
                }
                Db::name("goods")->where(['id'=>$info['goods_id']])->setDec("sales",$number);
            }
        }
    }

    function other_pay($ids = null){
        $type = input("type");
        if (!in_array($type,[1,2])){
            $this->error("参数错误");
        }
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $params['payment_status'] = 1;
                    $params['order_status'] = 1;
                    $params['payment_from'] = $params['type'];   //支付方式   1微信   2支付宝
                    unset($params['type']);
                    $result = $row->allowField(true)->save($params);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        $this->view->assign("type", $type);
        return $this->view->fetch();
    }
}
