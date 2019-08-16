<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/11
 * Time: 19:26
 */

namespace app\web\controller;

use app\web\model\Goods_attributes_price;
use app\web\model\Goods_category;
use app\web\model\Goods_recommend;
use think\Db;
use think\Request;

class Goods extends Base{
    private $Goods;
    function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Goods = new \app\web\model\Goods();
    }

    protected $beforeActionList = [
        'checkLoginSession',
    ];

    /**
     * 获取商品列表  (6-18去掉分页)
     * category_id   分类id
     *  page         页码
     *  num          每页显示数量
     */
    function getAllGoods(){
//        $page = input("post.page",1);
//        $num = input("post.num",4);
        $category_id = input("post.category_id");
        if (empty($category_id)){
            return $this->ajaxReturn(400,"参数为空");
        }
        $info = Goods_category::get($category_id);
        $map['category_id'] = $category_id;
        $map['status'] = 1;
        $lists = $this->Goods
            ->field("id as goods_id,name,image,price")
            ->where($map)
            ->order("weigh asc")
//            ->page($page,$num)
            ->select();
//        $count = $this->Goods->where(["category_id"=>$category_id,"status"=>1])->count();
        $cate_lists = Goods_category::field("id,name")->where(['category_id'=>$info['category_id']])->select();
        $data = [
            'info'=>$info,
            'lists'=>$lists,
//            'count'=>$count,
            'cate_lists'=>$cate_lists
        ];
        return $this->ajaxReturn(200,"获取成功",$data);
    }

    /**
     * 商品详情
     * goods_id  商品id
     */
    function getGoodsInfo(){
        $Goods_recommend = new Goods_recommend();
        $goods_id = input("post.goods_id");
        if (empty($goods_id)){
            return $this->ajaxReturn(400,"参数为空");
        }
        $map['id'] = $goods_id;
        $map['status'] = 1;
        $info = $this->Goods->field("id as goods_id,category_id,name,description,image,carousel_images,price")->where($map)->find();
        $info['carousel_images'] = json_decode($info['carousel_images']);
        //获取上下一个上架中的同类型商品
        $con['category_id'] = $info['category_id'];
        $con['status'] = 1;
        $last_id = $this->Goods->where($con)->where("id","<",$info['goods_id'])->order("id desc")->value("id as last_id");
        $next_id = $this->Goods->where($con)->where("id",">",$info['goods_id'])->value("id as next_id");
        if (empty($last_id)){ //若上一个商品为空   则查找最后一个商品替补
            $last_id = $this->Goods->where($con)->order("id desc")->value("id as last_id");
        }
        if (empty($next_id)){ //若下一个商品为空   则查找第一个商品替补
            $next_id = $this->Goods->where($con)->order("id asc")->value("id as next_id");
        }

        //获取推荐搭配
        $recommendLists = $Goods_recommend
            ->field("g.id as goods_id,g.name,g.image,g.price")
            ->alias("gr")
            ->join("goods g","g.id = gr.recommend_id")
            ->where(['gr.goods_id'=>$goods_id,'g.status'=>"1"])
            ->select();
        $data = [
            'info'=>$info,
            'last_id'=>$last_id ? $last_id : $goods_id,
            'next_id'=>$next_id ? $next_id : $goods_id,
            'recommendLists'=>$recommendLists
        ];
        return $this->ajaxReturn(200,"获取成功",$data);
    }

    /**
     * 商品规格
     * goods_id  商品id
     */
    function getAttr(){
        $goods_id = input("post.goods_id");
        if (empty($goods_id)){
            return $this->ajaxReturn(400,"参数为空");
        }

        $map['id'] = $goods_id;
        $map['status'] = 1;
        $info = $this->Goods->field("id as goods_id,name,image,price")->where($map)->find();
        if (empty($info)){
            return $this->ajaxReturn(400,"商品已下架,请刷新重试");
        }

        $matchList = Goods_attributes_price::where(['goods_id'=>$goods_id])
            ->field("id as attr_id,attribute_ids,attribute_names,attribute_price_ids,attribute_price_names,price,image,raw")
            ->select();
        $a = [];
        foreach ($matchList as $k => $v){
            $attribute_price_idss = json_decode($v['attribute_price_ids'],true);
            $a[$k] = implode(",",$attribute_price_idss);
        }
        $b = implode(",",$a);
        $c = explode(",",$b);
        $d = array_unique($c);
        $attrList = [];
        foreach ($matchList as $k => $v){
            $attribute_price_ids = json_decode($v['attribute_price_ids'],true);
            $attribute_price_names = json_decode($v['attribute_price_names'],true);
            $attribute_ids = json_decode($v['attribute_ids'],true);
            $attribute_names = json_decode($v['attribute_names'],true);

            foreach($attribute_ids as $key=>$val){
                $child = Db::name("goods_attributes")->field("id,name")->where(['pid'=>$val])->where("id","in",$d)->select();
                $attrList[$key]['pid']=$attribute_ids[$key];
                $attrList[$key]['pname']=$attribute_names[$key];
                $attrList[$key]['child']=$child;
            }
            $matchList[$k]['attribute_price_ids'] = implode(",",$attribute_price_ids);
            $matchList[$k]['attribute_price_names'] = implode(",",$attribute_price_names);
            unset($matchList[$k]['attribute_ids']);
            unset($matchList[$k]['attribute_names']);
        }

        $data = [
            'info'=>$info,
            'attrList'=>$attrList,
            'matchList'=>$matchList
        ];
        return $this->ajaxReturn(200,"获取成功",$data);
    }

    function test(){
        $data = [
            'https://ss0.bdstatic.com/70cFvHSh_Q1YnxGkpoWK1HF6hhy/it/u=2108160895,1789720967&fm=27&gp=0.jpg',
            'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=39158597,3582179444&fm=27&gp=0.jpg',
            'https://ss0.bdstatic.com/70cFvHSh_Q1YnxGkpoWK1HF6hhy/it/u=3821064618,3580788310&fm=27&gp=0.jpg',
        ];
        dump(json_encode($data));
    }
}