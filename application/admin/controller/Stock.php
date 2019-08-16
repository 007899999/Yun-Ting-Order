<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 属性价格管理
 *
 * @icon fa fa-circle-o
 */
class Stock extends Backend
{
    
    /**
     * Stock模型对象
     * @var \app\admin\model\Stock
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Stock;

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
                $row->visible(['id','goods_id','attribute_price_names','price','image','remindtime','raw','min_stock','createtime','is_relation']);
                
            }
            $list = collection($list)->toArray();
            foreach ($list as $k => $v){
                $list[$k]['attribute_price_names'] = json_decode($v['attribute_price_names']);
                $list[$k]['name'] = \app\admin\model\Goods::where(['id'=>$v['goods_id']])->value("name");
                $list[$k]['status'] = "充足";

                //状态(超过最低库存为待补充)
                if ($v['is_relation'] == 0){   //是否为关联  例如茶叶  0不关联   其它数字为关联分类id
                    if ($v['raw'] <= $v['min_stock']){
                        $list[$k]['status'] = "待补充";
                    }
                    if ($v['raw'] <= 0){
                        $list[$k]['status'] = "清仓";
                    }
                }else{
                    $sum_stock = Db::name("goods")->where(['id'=>$v['goods_id']])->value("sum_stock");
                    if ($v['raw'] >= $sum_stock){
                        $list[$k]['status'] = "待补充";
                    }
                    if ($sum_stock == 0){
                        $list[$k]['status'] = "清仓";
                    }
                }

            }
            foreach ($list as $k => $v){
                $list[$k]['category_id'] = Db::name("goods_category")->where(['id'=>$v['is_relation']])->value("name");
                $list[$k]['sum_stock'] = Db::name("goods")->where(['id'=>$v['goods_id']])->value("sum_stock");
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    function edit($ids = null){
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
        $row['attribute_price_names'] = implode("/",json_decode($row['attribute_price_names']));
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    //补货
    function replenishment($ids = null){
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
        $row['attribute_price_names'] = implode("/",json_decode($row['attribute_price_names']));
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }
}
