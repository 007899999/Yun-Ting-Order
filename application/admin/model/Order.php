<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Order extends Model
{

    use SoftDelete;

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'order';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'payment_from_text',
        'payment_status_text',
        'is_handle_text'
    ];
    

    
    public function getPaymentFromList()
    {
        return ['0' => __('Payment_from 0'), '1' => __('Payment_from 1'), '2' => __('Payment_from 2'), '3' => __('Payment_from 3'), '4' => __('Payment_from 4')];
    }

    public function getPaymentStatusList()
    {
        return ['0' => __('Payment_status 0'), '1' => __('Payment_status 1')];
    }

    public function getIsHandleList()
    {
        return ['0' => __('Is_handle 0'), '1' => __('Is_handle 1')];
    }

    public function getPaymentFromTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['payment_from']) ? $data['payment_from'] : '');
        $list = $this->getPaymentFromList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getPaymentStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['payment_status']) ? $data['payment_status'] : '');
        $list = $this->getPaymentStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsHandleTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['is_handle']) ? $data['is_handle'] : '');
        $list = $this->getIsHandleList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function member(){
        return $this->belongsTo('Member', 'member_id', 'id', [], 'right')->setEagerlyType(0);
    }




}
