<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Ordergoods extends Model
{

    use SoftDelete;

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'order_goods';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'status_text',
        'is_evaluate_text',
        'remindtime_text'
    ];
    

    
    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1')];
    }

    public function getIsEvaluateList()
    {
        return ['0' => __('Is_evaluate 0'), '1' => __('Is_evaluate 1')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsEvaluateTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['is_evaluate']) ? $data['is_evaluate'] : '');
        $list = $this->getIsEvaluateList();
        return isset($list[$value]) ? $list[$value] : '';
    }


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
