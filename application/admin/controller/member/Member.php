<?php

namespace app\admin\controller\member;

use app\admin\model\Memberevents;
use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 会员管理
 *
 * @icon fa fa-circle-o
 */
class Member extends Backend
{
    
    /**
     * Member模型对象
     * @var \app\admin\model\Member
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Member;

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
                $row->visible(['id','avatar','level','nickname','mobile','birthday','createtime']);
                
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 获取所有会员
     */
    function getAll(){
        $list = $this->model->select();
        foreach ($list as $k => $v){
            $list[$k]['name'] = $v['nickname'];
        }
        $total = $this->model->count();
        return json(['list' => $list, 'total' => $total]);
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    //先判断手机号是否存在  以免重复添加
                    $check = $this->model->where(['mobile'=>$params['mobile']])->find();
                    if (!empty($check)){
                        $this->error("该手机号已存在");
                    }
//                    if (!empty($params['birthday'])){
//                        $params['birthdaytime'] = strtotime($params['birthday']);
//                    }
                    $result = $this->model->allowField(true)->save($params);
                    $member_id = $this->model->id;
                    $member_no = str_pad(0,9,0).$member_id;
                    $this->model->where(['id'=>$member_id])->update(['member_no'=>$member_no]);

                    $remark = input("remark/a");
                    $events = input("events/a");
                    $data_events = [];
                    if (isset($remark) && !empty($remark)){
                        foreach ($remark as $k => $v){
                            $data_events[$k]['member_id'] = $member_id;
                            $data_events[$k]['remark'] = $remark[$k];
                            $data_events[$k]['events'] = "2000-".$events[$k];
                            $data_events[$k]['eventstime'] = strtotime($data_events[$k]['events']);
                            $data_events[$k]['createtime'] = time();
                            $data_events[$k]['type'] = 2;
                        }
                        Db::name("member_events")->insertAll($data_events);
                    }

                    //添加生日进特殊表
                    if (!empty($params['birthday'])){
                        $events_bir['member_id'] = $member_id;
                        $events_bir['remark'] = "生日";
                        $events_bir['events'] = "2000-".$params['birthday'];
                        $events_bir['eventstime'] = strtotime($events_bir['events']);
                        $events_bir['createtime'] = time();
                        $events_bir['type'] = 1;
                        Db::name("member_events")->insertGetId($events_bir);
                    }
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
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
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
        $member_events = new Memberevents();
        $event_lists = $member_events->where(['member_id'=>$ids,'type'=>2])->select();
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
//                    if (!empty($params['birthday'])){
//                        $params['birthdaytime'] = strtotime($params['birthday']);
//                    }
                    $result = $row->allowField(true)->save($params);
                    //先删除原本的会员特殊日
                    Db::name("member_events")->where(['member_id'=>$ids])->delete();

                    $remark = input("remark/a");
                    $events = input("events/a");
                    $data_events = [];
                    if (!empty($remark)){
                        foreach ($remark as $k => $v){
                            $data_events[$k]['member_id'] = $ids;
                            $data_events[$k]['remark'] = $remark[$k];
                            $data_events[$k]['events'] = "2000-".$events[$k];
                            $data_events[$k]['eventstime'] = strtotime($data_events[$k]['events']);
                            $data_events[$k]['createtime'] = time();
                            $data_events[$k]['type'] = 2;
                        }
                    }
                    Db::name("member_events")->insertAll($data_events);
                    //添加生日进特殊表
                    if (!empty($params['birthday'])){
                        $events_bir['member_id'] = $ids;
                        $events_bir['remark'] = "生日";
                        $events_bir['events'] = "2000-".$params['birthday'];
                        $events_bir['eventstime'] = strtotime($events_bir['events']);
                        $events_bir['createtime'] = time();
                        $events_bir['type'] = 1;
                        Db::name("member_events")->insertGetId($events_bir);
                    }
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
        $this->view->assign("event_lists", $event_lists);
        return $this->view->fetch();
    }

    /**
     * 详情
     */
    function detail(){
        $member_id = input("ids");
        $info = $this->model->get($member_id);//基本信息
        $Memberevents = new Memberevents();
        $events  = $Memberevents->where(['member_id'=>$member_id])->select();        //特殊日子列表
        $event_count = count($events);

        //历史订单
        $order = new \app\admin\model\Order();
        $orderlists = $order->where(['member_id'=>$member_id])->select();
        $sum_money = array_column($orderlists,"sum_money");
        $info['money'] = array_sum($sum_money);
        $order_count = count($orderlists);
        $this->assign([
            'info'=>$info,
            'events'=>$events,
            'event_count'=>$event_count,
            'order_count'=>$order_count,
            'orderlists'=>$orderlists
        ]);
        return $this->view->fetch();
    }

    /**
     * 删除特殊日子
     */
    function del_event(){
        $id = input("id");
        $del = Memberevents::where('id','=',$id)->delete(true);
        if ($del){
            return 1;
        }else{
            return -1;
        }
    }

}
