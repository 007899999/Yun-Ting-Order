<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/11
 * Time: 17:29
 */

namespace app\web\model;

use think\Model;
use traits\model\SoftDelete;

class  Goods_category extends Model{
    use SoftDelete;
    protected $deleteTime = 'deletetime';


}