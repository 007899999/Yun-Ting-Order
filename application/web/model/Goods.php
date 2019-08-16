<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/11
 * Time: 19:26
 */

namespace app\web\model;

use think\Model;
use traits\model\SoftDelete;

class Goods extends Model{
    use SoftDelete;
    protected $deleteTime = 'deletetime';

}