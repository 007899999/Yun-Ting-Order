<?php

namespace app\admin\controller;

use app\admin\model\Price;
use app\common\controller\Backend;
use app\web\model\Goods_recommend;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 商品管理
 *
 * @icon fa fa-circle-o
 */
class Goods extends Backend
{
    
    /**
     * Goods模型对象
     * @var \app\admin\model\Goods
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Goods;
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("isEvaluateList", $this->model->getIsEvaluateList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = false;
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id','category_id','name','image','createtime','status','price','is_evaluate']);
                
            }
            $list = collection($list)->toArray();
            foreach ($list as $k => $v){
                $list[$k]['category_id'] = Db::name("goods_category")->where(['id'=>$v['category_id']])->value("name");
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    function add(){
        if ($this->request->isPost()) {
            Db::startTrans();
            try{
                $data['category_id'] = input("post.category_id/d");     //分类
                $data['no'] = input("post.no/s");                       //商品编号
                $data['name'] = input("post.name/s");                   //商品名称
                $data['description'] = input("post.description/s");     //商品详情
                $data['image'] = input("post.image/s");                 //顶图
                $carousel_images = input("post.carousel_images/s");
                if (strpos($carousel_images,",")){                              //轮播图
                    $data['carousel_images'] = json_encode(explode(",",input("post.carousel_images/s")));
                }else{
                    $data['carousel_images'] = json_encode([input("post.carousel_images/s")]);
                }
                $data['weigh'] = input("post.weigh");                   //权重
                $data['status'] = 1;                                        //状态:0=下架,1=上架
                $data['price'] = input("post.price/f");                 //列表价
                $data['is_evaluate'] = input("post.is_evaluate",0);       //是否可评价:0=不可评价,1=可评价
                $data['createtime'] = time();       //创建时间
                $is_stock_relation = input("post.is_stock_relation");//-1不关联  1关联
                $is_relation = input("post.is_relation");
                if (empty($is_relation)){
                    $is_relation = 0;               //不关联  按正常库存流程走
                }else{
                    $data['sum_stock'] = input("post.sum_stock");
                }
                $goods_id = Db::name("goods")->insertGetId($data);          //添加商品

                $testArray = input("post.testArray/a");
                if (!empty($testArray)){
                    foreach ($testArray as $k => $v){
                        $p_names = $v['propnames'];
                        $c_names = $v['propvalnames'];
                        if (empty($p_names) || empty($c_names)){
                            $this->error("请填写规格信息");
                        }
                    }
                  $this->addPrice($testArray,$goods_id,$is_relation,$is_stock_relation);
                }
                Db::commit();
                return 1;
            } catch (\Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
        }
        $attr_lists = Db::name("goods_attributes")->where(['pid'=>0])->select();
        foreach ($attr_lists as $k => $v){
            $p_id = $v['id'];
            $attr_lists[$k]['child'] = Db::name("goods_attributes")->where(['pid'=>$p_id])->select();
        }
        $this->assign([
            'attr_lists'=>$attr_lists
        ]);
        return $this->view->fetch();
    }

    function addPrice($testArray,$goods_id,$is_relation,$is_stock_relation){
        $data_price = [];
        foreach ($testArray as $k => $v){
            $p_names = $v['propnames'];
            $c_names = $v['propvalnames'];
            if (strpos($p_names,";")){
                $p_name_arr = explode(";",$p_names);
            }else{
                $p_name_arr = [$p_names];
            }
            if (strpos($c_names,";")){
                $c_names_arr = explode(";",$c_names);
            }else{
                $c_names_arr = [$c_names];
            }
            $p_id = [];
            $c_id = [];
            foreach ($p_name_arr as $k1 => $v1){                            //属性分类父级id

                $p_name = $v1;
                $check_p = Db::name("goods_attributes")->where(['name'=>$p_name,'pid'=>0])->find();
                if (!empty($check_p)){
                    $p_id[$k1] = $check_p['id'];
                }else{
                    $p_id[$k1] = Db::name("goods_attributes")->insertGetId(['pid'=>0,'name'=>$p_name,'createtime'=>time()]);
                }

                $c_name = $c_names_arr[$k1];
                $check_c = Db::name("goods_attributes")->where(['name'=>$c_name,'pid'=>$p_id[$k1]])->find();
                if (!empty($check_c)){
                    $c_id[$k1] = $check_c['id'];
                }else{
                    $c_id[$k1] = Db::name("goods_attributes")->insertGetId(['pid'=>$p_id[$k1],'name'=>$c_name,'createtime'=>time()]);
                }
            }

            $data_price[$k]['goods_id'] = $goods_id;
            $data_price[$k]['attribute_ids'] = json_encode($p_id);
            $data_price[$k]['attribute_names'] = json_encode($p_name_arr);
            $data_price[$k]['attribute_price_ids'] = json_encode($c_id);
            $data_price[$k]['attribute_price_names'] = json_encode($c_names_arr);
            $data_price[$k]['price'] = $v['price'];
            $data_price[$k]['image'] = $v['proImg'];
            $data_price[$k]['remindtime'] = $v['remindtime'];
            $data_price[$k]['raw'] = $v['raw1'];
            $data_price[$k]['min_stock'] = $v['min_stock'];
            if ($is_stock_relation == 1){
                $data_price[$k]['is_relation'] = $is_relation;
            }
            $data_price[$k]['createtime'] = time();
        }
        Db::name("goods_attributes_price")->insertAll($data_price);
    }

    function edit($ids = null){
        if ($this->request->isPost()) {
            Db::startTrans();
            try{
                $goods_id = input("post.id");
                $map['id'] = $goods_id;
                $data_goods['category_id'] = input("post.category_id");
                $data_goods['no'] = input("post.no");
                $data_goods['name'] = input("post.name");
                $data_goods['description'] = input("post.description");
                $data_goods['image'] = input("post.image");
                $carousel_images = input("post.carousel_images/s");
                if (strpos($carousel_images,",")){                              //轮播图
                    $data_goods['carousel_images'] = json_encode(explode(",",input("post.carousel_images/s")));
                }else{
                    $data_goods['carousel_images'] = json_encode([input("post.carousel_images/s")]);
                }
                $data_goods['weigh'] = input("post.weigh");
                $data_goods['price'] = input("post.price");
                $data_goods['is_evaluate'] = input("post.is_evaluate");
                $is_stock_relation = input("post.is_stock_relation");//-1不关联  1关联
                $is_relation = input("post.is_relation");
                if ($is_stock_relation == 1){
                    $data_goods['sum_stock'] = input("post.sum_stock");
                    Db::name("goods_attributes_price")->where(['goods_id'=>$goods_id])->update(['is_relation'=>$is_relation]);
                }else{
                    $data_goods['sum_stock'] = 0;
                    Db::name("goods_attributes_price")->where(['goods_id'=>$goods_id])->update(['is_relation'=>0]);
                }

                \app\admin\model\Goods::where($map)->update($data_goods);
                $testArray = input("post.testArray/a");
                if (!empty($testArray)){  //若重新设置属性的话  原有属性删掉
                    foreach ($testArray as $k => $v){
                        $p_names = $v['propnames'];
                        $c_names = $v['propvalnames'];
                        if (empty($p_names) || empty($c_names)){
                            $this->error("请填写规格信息");
                        }
                    }
                    Db::name("goods_attributes_price")->where(['goods_id'=>$goods_id])->update(['deletetime'=>time()]);
                    $this->addPrice($testArray,$goods_id,$is_relation,$is_stock_relation);
                }
                Db::commit();
                return 1;
            } catch (\Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
        }
        $row = $this->model->get($ids);
        $row['carousel_images'] = implode(",",json_decode($row['carousel_images']));
        //查找是否关联  （茶叶：g）
        $attrs = Price::where(['goods_id'=>$row['id']])->select();
        foreach ($attrs as $k => $v){
            $attrs[$k]['attribute_price_names'] = implode(",",json_decode($v['attribute_price_names'],true));
        }
        $list = \app\admin\model\Cate::where("category_id","<>",0)->select();
        $list2 = \app\admin\model\Cate::where(['category_id'=>0])->select();
        $this->view->assign([
            "row" => $row,
            'attrs'=>$attrs,
            'list'=>$list,
            'list2'=>$list2,
        ]);
        return $this->view->fetch();
    }

    /**
     * 上架
     */
    function start(){
        $ids = input("ids");
        $update_res = Db::name("goods")->where(['id'=>$ids])->update(['status'=>1]);
        if ($update_res == 1){
            $this->success();
        }else{
            $this->error("操作失败");
        }
    }

    /**
     * 下架
     */
    function stop(){
        $ids = input("ids");
        $update_res = Db::name("goods")->where(['id'=>$ids])->update(['status'=>0]);
        if ($update_res == 1){
            $this->success();
        }else{
            $this->error("操作失败");
        }
    }

    //修改属性值
    function editAttr(){
        $id = input("id");
        $map['id'] = $id;
        $data['price'] = input("post.price/f");
        $data['remindtime'] = input("post.remindtime");
        $data['raw'] = input("post.raw");
        $data['min_stock'] = input("post.min_stock");
        $data['image'] = input("post.new_shuxing");
        $data['updatetime'] = time();
        $update_res = Price::where($map)->update($data);
        if ($update_res == 1){
            return 1;
        }else{
            return -1;
        }
    }

    //搭配商品列表
    function recommend($ids = null){
        $goods = Db::name("goods_recommend")
            ->field("gr.*,g.name,g.image,g.price,g.sales")
            ->alias("gr")
            ->join("goods g","g.id = gr.recommend_id")
            ->where(['goods_id'=>$ids])
            ->select();
        $this->assign([
            'goods'=>$goods,
            'ids'=>$ids
        ]);
        return $this->view->fetch();
    }

    //添加推荐商品
    function addRecommend(){
        $goods_id = input("goods_id");
        if ($this->request->isPost()) {
            Db::startTrans();
            try {
                $params = input("post.");
                //判断数量 仅三个
                $count = Db::name("goods_recommend")->where(['goods_id'=>$params['goods_id']])->count();
                if ($count>=3){
                    return -2;
                }

                //判断是否存在
                $check = Db::name("goods_recommend")->where($params)->find();
                if (!empty($check)){
                    return -3;
                }

                $params['createtime'] = time();
                Db::name("goods_recommend")->insertGetId($params);
                Db::commit();
                return 1;
            } catch (ValidateException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
        }
        $info = \app\admin\model\Goods::where(['id'=>$goods_id])->find();
        $map['status'] = 1;
        $map['id'] = ['<>',$goods_id];
        $goods = \app\admin\model\Goods::where($map)->select();
        //判断是否推荐
        foreach ($goods as $k => $v){
            $check = Db::name("goods_recommend")->where(['goods_id'=>$info['id'],'recommend_id'=>$v['id']])->find();
            if (!empty($check)){
                unset($goods[$k]);
            }
        }
        $this->assign([
            'info'=>$info,
            'goods'=>$goods,
        ]);
        return $this->view->fetch();
    }

    //删除搭配商品
    function del_goods(){
        $id = input("post.id");
        $del_res = Goods_recommend::where(['id'=>$id])->delete();
        if ($del_res){
            return 1;
        }else{
            return -1;
        }
    }
}
