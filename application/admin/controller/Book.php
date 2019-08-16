<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 包厢预约
 *
 * @icon fa fa-circle-o
 */
class Book extends Backend
{

    /**
     * Book模型对象
     * @var \app\admin\model\Book
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Book;

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
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {

//            //先删除所有包厢的过期预约
//            $lists = $this->model->where('end_time',"<=",time())->select();
//            foreach ($lists as $k => $v){
//                $this->model->destroy($v['id']);
//            }

            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->with(['balcony'])
                ->order($sort, $order)
                ->count();

            $list = $this->model
//                    ->fetchSql(true)
                ->with(['balcony'])
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            foreach ($list as $row) {
                $row->visible(['start_time','end_time','nickname','mobile','id']);
                $row->visible(['balcony']);
                $row->getRelation('balcony')->visible(['id','name']);
            }
            $list = collection($list)->toArray();
            $filter = $this->request->get("filter");

            $filter = json_decode($filter,true);
            if (isset($filter['start_time']))
                $filter['start_time'] = explode(" - ", $filter['start_time']);
            if (isset($filter['end']))
                $filter['end'] = explode(" - ", $filter['end']);

            foreach ($list as $k => $v){
                if (empty($v['start_time'])){
                    $list[$k]['status'] = "1"; //可预约
                }else{
                    if (time() >= $v['end_time']){
                        $list[$k]['status'] = "3"; //预约过期
                    }else{
                        $list[$k]['status'] = "2";//已预约
                    }
                }
                if (isset($filter['start_time'][0]))
                    $list[$k]['start'] = strtotime($filter['start_time'][0]);

                if (isset($filter['end_time'][0]))
                    $list[$k]['end'] = strtotime($filter['end_time'][0]);
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    //预约
    function add(){
        $id = input("id");
        $start = input("start");
        $end = input("end");
        $info = \app\admin\model\Balcony::where(['id'=>$id])->find();
        $this->assign([
            'info'=>$info,
            'start'=>$start,
            'end'=>$end
        ]);
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
                    $info = \app\admin\model\Member::where(['id'=>$params['member_id']])->find();
                    $balcony_info = \app\admin\model\Balcony::get($params['balcony_id']);
                    $params['start_time'] = strtotime($params['start_time']);
                    $params['end_time'] = strtotime($params['end_time']);
                    $params['createtime'] = time();
                    $params['nickname'] = $info['nickname'];
                    $params['mobile'] = $info['mobile'];
                    $params['balcony_name'] = $balcony_info['name'];
                    unset($params['name']);
                    //判断是否有重复预约时间的包厢
//                    $map1['start_time'] = ['<',$params['start_time']];
//                    $map1['end_time'] = ['>',$params['end_time']];
                    $map1['balcony_id'] = $params['balcony_id'];
                    $check = $this->model
                        ->where($map1)
                        ->where(function ($query) use ($params){
                            $query->where('start_time', 'between',[$params['start_time'],$params['end_time']])->whereor('end_time', 'between',[$params['start_time'],$params['end_time']]);
                        })
                        ->find();
                    if (!empty($check)){
                        $this->error("该时间段与该包厢的其他预约时间冲突!");
                    }else{
                        $result = $this->model->allowField(true)->save($params);
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
     * 取消预约
     */
    function cancel(){
        $id = input("id");
        $this->model->destroy($id,true);
        $this->success();
    }
}
