<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/12
 * Time: 17:20
 */

namespace app\web\model;

use think\Model;
use traits\model\SoftDelete;

class Goods_evaluation extends Model{
    use SoftDelete;

    protected $autoWriteTimestamp = true;
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

}