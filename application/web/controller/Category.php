<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/11
 * Time: 18:16
 */

namespace app\web\controller;

use app\web\model\Goods_category;

class Category extends Base{
    protected $beforeActionList = [
        'checkLoginSession',
    ];
    /**
     * 获取分类
     */
    function getAllCate(){
        $Goods_category = new Goods_category();
        $lists = $Goods_category->field("id,category_id,eng_name,name,content,image,weigh,s_image")
            ->where("category_id","=",0)
            ->order("weigh asc")
            ->select();
        foreach ($lists as $k => $v){
            $child_lists = $Goods_category->field("id,category_id,eng_name,name,content,image,weigh,s_image")
                ->where("category_id","=",$v['id'])
                ->order("weigh asc")
                ->select();
            $lists[$k]['image'] = $v['image'];
            $lists[$k]['s_image'] = $v['s_image'];
            $lists[$k]['child'] = $child_lists;
        }
        return $this->ajaxReturn(200,"获取成功",$lists);
    }
}