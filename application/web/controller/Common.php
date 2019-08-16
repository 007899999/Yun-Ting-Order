<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/12
 * Time: 11:42
 */

namespace app\web\controller;

use app\web\model\Goods_attributes_price;
use think\Db;

class Common extends Base{
    protected $beforeActionList = [
        'checkLoginSession',
    ];

    /**
     * @param $attr_id
     * @param $num
     * @param $is_relation
     * @param $raw
     * @param $sum_stock
     * @param $is_stock         库存是否变动  -1不变动   1变动
     * @return int
     * @throws \think\Exception
     */
    static function keep_stock($attr_id,$num,$is_relation,$raw,$sum_stock,$is_stock,$goods_id){
        //判断库存
        if ($is_relation == 0){//否则不关联  按照正常库存来走
            if ($raw < $num){
                return -1;
            }
            if ($is_stock == 1){
                Goods_attributes_price::where(['id'=>$attr_id])->setDec("raw",$num);                //减现有库存 raw
                Goods_attributes_price::where(['id'=>$attr_id])->setInc("freezing_stock",$num);
                Db::name("goods")->where(['id'=>$goods_id])->setInc("sales",$num);            //加销量
            }
        }else{ //判断是否为关联  例如茶叶 g、斤
            $attr_stock = $raw * $num;      //总共用量
            if ($sum_stock < $attr_stock){
                return -1;
            }
            if ($is_stock == 1) {
                \app\web\model\Goods::where(['id' => $goods_id])->setDec("sum_stock", $attr_stock);   //减总库存 sum_stock
                Goods_attributes_price::where(['id' => $attr_id])->setInc("freezing_stock", $attr_stock);
                Db::name("goods")->where(['id'=>$goods_id])->setInc("sales",$attr_stock);      //加销量
            }
        }
        return 1;
    }

    //生成订单号
    static function makeOrderNo() {
        return $danhao = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}