<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Stock extends Model
{

    use SoftDelete;

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'goods_attributes_price';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'remindtime_text'
    ];
    

    



    public function getRemindtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['remindtime']) ? $data['remindtime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setRemindtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
