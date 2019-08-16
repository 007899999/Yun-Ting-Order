<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Goods extends Model
{

    use SoftDelete;

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'goods';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'status_text',
        'is_evaluate_text'
    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

    
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




}
