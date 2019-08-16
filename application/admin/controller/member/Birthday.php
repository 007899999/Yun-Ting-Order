<?php

namespace app\admin\controller\member;

use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 生日列表
 *
 * @icon fa fa-circle-o
 */
class Birthday extends Backend
{
    
    /**
     * Member模型对象
     * @var \app\admin\model\Member
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Memberevents;

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
            $map['type'] = 1;
            //会员生日(上个月1号到下个月1号)
            $firstday = date("2000-m-01",time());
//            $firstday2 = strtotime(date("2000-m-d",strtotime("$firstday -1 month")));
            $lastday = strtotime(date("2000-m-d",strtotime("$firstday +1 month")));
            $firstday = strtotime($firstday);
            $map['eventstime'] = ['between',[$firstday,$lastday]];
            $total = $this->model
//                ->fetchSql(true)
                    ->with(['member'])
                    ->where($where)
                    ->where($map)
//                    ->order($sort, $order)
                    ->count();
            $list = $this->model
                    ->with(['member'])
                    ->where($where)
                    ->where($map)
//                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id','eventstime','remark','type']);
//                $row->visible(['start_time','end_time','nickname','mobile','id']);
                $row->visible(['member']);
                $row->getRelation('member')->visible(['nickname','avatar','mobile']);
            }
            $list = collection($list)->toArray();
            foreach ($list as $k => $v){
                $list[$k]['eventstime'] = date("m-d",$v['eventstime']);
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

}
