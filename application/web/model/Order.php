<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/12
 * Time: 14:18
 */

namespace app\web\model;

use think\Model;
use traits\model\SoftDelete;

class Order extends Model{
    use SoftDelete;
    protected $autoWriteTimestamp = true;
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

}