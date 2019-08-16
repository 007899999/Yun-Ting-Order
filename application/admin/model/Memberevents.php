<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Memberevents extends Model
{

    use SoftDelete;

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'member_events';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [

    ];

    public function member()
    {
        return $this->belongsTo('Member', 'member_id', 'id', [], 'left')->setEagerlyType(0);
    }
    

    







}
