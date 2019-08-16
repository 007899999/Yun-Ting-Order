<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:85:"E:\phpStudy\PHPTutorial\WWW\fastadmin\public/../application/admin\view\goods\add.html";i:1563159936;s:80:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\layout\default.html";i:1562751673;s:77:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\meta.html";i:1557482263;s:79:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\script.html";i:1557482263;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/fastadmin/public/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/fastadmin/public/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/fastadmin/public/assets/js/html5shiv.js"></script>
  <script src="/fastadmin/public/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>
    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !$config['fastadmin']['multiplenav']): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <link href="/fastadmin/public/assets/shuxing/css/sku_style.css" rel="stylesheet">

<script src="/fastadmin/public/assets/shuxing/js/jquery-1.8.3.min.js"></script>
<script src="/fastadmin/public/assets/shuxing/js/customSku.js"></script>
<script src="/fastadmin/public/assets/shuxing/plugins/layer/layer.js"></script>

<div class="form-horizontal" role="form" data-toggle="validator">

    <form id="add-form" class="form-horizontal" role="form" data-toggle="validator">
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2">商品类别:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-category_id" data-rule="required" data-source="cate/selectChild" data-params='{"custom[type]":"goods_category"}' class="form-control selectpage" name="row[category_id]" type="text" value="">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('No'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-no" data-rule="required" class="form-control" name="row[no]" type="text">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Name'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-name" class="form-control" name="row[name]" type="text" data-rule="required">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Description'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <textarea id="c-description" class="form-control" name="row[description]" type="text" data-rule="required" rows="5" cols="30"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Image'); ?>(1:1):</label>
            <div class="col-xs-12 col-sm-8">
                <div class="input-group">
                    <input id="c-image" class="form-control" size="50" name="row[image]" type="text" data-rule="required">
                    <div class="input-group-addon no-border no-padding">
                        <span><button type="button" id="plupload-image" class="btn btn-danger plupload" data-input-id="c-image" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="false" data-preview-id="p-image"><i class="fa fa-upload"></i> <?php echo __('Upload'); ?></button></span>
                    </div>
                    <span class="msg-box n-right" for="c-image"></span>
                </div>
                <ul class="row list-inline plupload-preview" id="p-image"></ul>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Carousel_images'); ?>(1024px * 470px):</label>
            <div class="col-xs-12 col-sm-8">
                <div class="input-group">
                    <input id="c-carousel_images" class="form-control" size="50" name="row[carousel_images]" type="text" data-rule="required">
                    <div class="input-group-addon no-border no-padding">
                        <span><button type="button" id="plupload-carousel_images" class="btn btn-danger plupload" data-input-id="c-carousel_images" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="true" data-preview-id="p-carousel_images"><i class="fa fa-upload"></i> <?php echo __('Upload'); ?></button></span>
                    </div>
                    <span class="msg-box n-right" for="c-carousel_images"></span>
                </div>
                <ul class="row list-inline plupload-preview" id="p-carousel_images"></ul>
            </div>
        </div>
        <div class="form-group" style="display: none">
            <label class="control-label col-xs-12 col-sm-2">排序:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-weigh" class="form-control" name="row[weigh]" type="hidden" value="0">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2">列表价:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-price" data-rule="required" class="form-control" step="0.01" name="row[price]" type="number">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Is_evaluate'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <select  id="c-is_evaluate" class="form-control selectpicker" name="row[is_evaluate]">
                    <?php if(is_array($isEvaluateList) || $isEvaluateList instanceof \think\Collection || $isEvaluateList instanceof \think\Paginator): if( count($isEvaluateList)==0 ) : echo "" ;else: foreach($isEvaluateList as $key=>$vo): ?>
                    <option value="<?php echo $key; ?>" <?php if(in_array(($key), explode(',',""))): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2">是否库存关联:</label>
            <div class="col-xs-12 col-sm-8">
                <select  id="is_stock_relation" onchange="gradeChange()" class="form-control selectpicker" name="row[is_stock_relation]">
                    <option value="-1">不关联</option>
                    <option value="1">关联</option>
                </select>
            </div>
        </div>
        <div class="form-group" style="display: none" id="div_is_relation">
            <label class="control-label col-xs-12 col-sm-2">关联:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="is_relation" data-rule="required" data-source="cate/selectpage" data-params='{"custom[type]":"goods_category"}' class="form-control selectpage" name="row[is_relation]" type="text" value="">
            </div>
        </div>
    </form>
    <div class="form-group" style="display: none" id="sum_stock">
        <label class="control-label col-xs-12 col-sm-2">总库存:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-sum_stock" class="form-control" step="0.01" name="row[sum_stock]" type="number">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2">商品规格:</label>
        <div class="col-xs-12 col-sm-8" id="l_r">
            <!--<div class="demo-title">注：添加商品属性时请先添加一级属性，再添加二级属性，最后勾选！</div>-->
            <?php if($attr_lists != null): if(is_array($attr_lists) || $attr_lists instanceof \think\Collection || $attr_lists instanceof \think\Paginator): $i = 0; $__LIST__ = $attr_lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <div class="clear"></div>
                    <ul class="SKU_TYPE">
                        <li is_required='0' propid='' sku-type-name="">
                            <input type="text" class="cusSkuTypeInput" value="<?php echo $vo['name']; ?>" readonly="readonly" style="padding: 2px 12px;line-height: 24px;"/>
                            <!--一级栏目input框-->
                        </li>
                    </ul>
                    <ul>
                        <?php if(is_array($vo['child']) || $vo['child'] instanceof \think\Collection || $vo['child'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                            <li>
                                <input type="checkbox" class="model_sku_val" propvalid='' value=""/>
                                <input type="text" class="cusSkuValInput emmmmmmmmmmm" value="<?php echo $v['name']; ?>" readonly="readonly" style="padding: 2px 12px;line-height: 24px;"/>
                            </li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        <button class="layui-btn layui-btn-sm cloneSkuVal" style="border: 1px solid rgb(129,148,170);height: 32px;background: rgb(129,148,170);color:#fff">添加自定义属性值</button>
                    </ul>
                    <div class="clear"></div>
                <?php endforeach; endif; else: echo "" ;endif; endif; ?>

            <button class="cloneSku" style="border: 1px solid rgb(53,87,114);height: 32px;background: rgb(53,87,114);color:#fff">添加自定义sku属性</button>

            <!--一级属性+第一个二级属性         sku模板,用于克隆,生成自定义sku-->

            <div id="skuCloneModel" style="display: none;">
                <div class="clear"></div>
                <ul class="SKU_TYPE">
                    <li is_required='0' propid='' sku-type-name="">
                        <button href="javascript:void(0);" class="delCusSkuType" style="border: 1px solid #fff;height: 32px;background: rgb(205,38,34);color:#fff">移除</button>
                        <input type="text" class="cusSkuTypeInput" placeholder="请输入一级属性名称" style="padding: 2px 12px;line-height: 24px;"/>：                                               <!--一级栏目input框-->
                    </li>
                </ul>
                <ul>
                    <li>
                        <input type="checkbox" class="model_sku_val"  propvalid='' value="" />
                        <input type="text" class="cusSkuValInput" placeholder="请输入二级属性名称，然后选中当前" style="padding: 2px 12px;line-height: 24px;"/>
                    </li>
                    <button class="cloneSkuVal" style="border: 1px solid rgb(129,148,170);height: 32px;background: rgb(129,148,170);color:#fff">添加自定义属性值</button>
                </ul>
                <div class="clear"></div>
            </div>
            <!--=第2个二级属性(单个sku值克隆模板)-->
            <!--<ul>-->
                <li style="display: none;" id="onlySkuValCloneModel">
                    <input type="checkbox" class="model_sku_val"  propvalid='' value="" />
                    <input type="text" class="cusSkuValInput" placeholder="请输入二级属性名称，然后选中当前" style="padding: 2px 12px;line-height: 24px;"/>
                    <button href="javascript:void(0);" class="delCusSkuVal" style="border: 1px solid rgb(129,148,170);height: 32px;background: #333;color:#fff">删除</button>
                </li>
            <!--</ul>-->
            <div class="clear"></div>
            <div id="skuTable"></div>
        </div>
    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <!--<button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>-->
            <a onclick="submitResult()" class="btn btn-success btn-embossed"><?php echo __('OK'); ?></a>
        </div>
    </div>
</div>
<script>
    /**
     * 添加
     */
    function showPreviews(soure) {
        var fileObj = soure.files[0]; // js 获取文件对象
        var formFile = new FormData();
        formFile.append("action", "UploadVMKImagePath");
        formFile.append("file", fileObj); //加入文件对象
        var data = formFile;
        $.ajax({
            type:"post",
            url:"<?php echo \think\Url::build('Ajax/upload'); ?>",
            dataType:'json',
            data:data,
            processData:false,
            cache: false,//上传文件无需缓存
            contentType: false, //必须
            success:function(data){
                var urls = data.data.url;
                // console.log(data);
                // console.log(urls);
                // // $(soure).attr('img-url',urls);
                // $("#setting_img").val(urls);
                $(soure).attr('img-url',urls);
            },
            error:function(error){
                console.log(error)
            }
        });
    }

    function gradeChange() {
        var is_stock_relation = $("#is_stock_relation").val();
        if (is_stock_relation == -1){
            $("#sum_stock").attr("style","display:none;");//隐藏div
            $("#div_is_relation").attr("style","display:none;");//隐藏div
        }else{
            $("#sum_stock").attr("style","display:block;");//显示div
            $("#div_is_relation").attr("style","display:block;");//显示div
        }
    }


    function submitResult() {
        var testArray=[];
        $("tr[class*='sku_table_tr']").each(function(){
            var obj={propnames:'',propvalnames:'',proImg:'',price:'',raw1:'',min_stock:'',remindtime:''};
            obj.proImg=$(this).find("input[type='file'][class*='setting_img']").attr('img-url');        //图片
            obj.price=$(this).find("input[type='number'][class*='setting_price']").val();      //价格
            obj.raw1 = $(this).find("input[type='number'][class*='setting_raw1']").val();  //现有库存/原料用量
            obj.min_stock= $(this).find("input[type='number'][class*='setting_min_stock']").val();//最低库存
            obj.remindtime= $(this).find("input[type='number'][class*='setting_remindtime']").val();//超时提醒时间  单位为分钟
            obj.propnames= $(this).attr("propnames");  //一级属性名称
            obj.propvalnames= $(this).attr("propvalnames");  //二级属性名称
            testArray.push(obj);
        });
        if (testArray.length == 0){
            layer.alert("请设置商品规格");
            return;
        }
        $.ajax({
            type:"POST",
            url:"goods/add",
            dataType:"json",
            data:{
                testArray:testArray,
                category_id:$("#c-category_id").val(),
                no:$("#c-no").val(),
                name:$("#c-name").val(),
                description:$("#c-description").val(),
                image:$("#c-image").val(),
                carousel_images:$("#c-carousel_images").val(),
                weigh:$("#c-weigh").val(),
                price:$("#c-price").val(),
                is_evaluate:$("#c-is_evaluate").val(),
                is_stock_relation:$("#is_stock_relation").val(),
                is_relation:$("#is_relation").val(),
                sum_stock:$("#c-sum_stock").val(),
            },
            success:function(data){
                if (data == 1){
                    layer.alert("操作成功", {icon: 6},function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                        parent.location.reload();
                    });
                }
            }
        });
    }
</script>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/fastadmin/public/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/fastadmin/public/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>
