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

class  Balcony extends Model{
    use SoftDelete;
    protected $deleteTime = 'deletetime';

    function makeToken($user_create_time){
        $token = md5(time().($user_create_time));
        return $token;
    }
}