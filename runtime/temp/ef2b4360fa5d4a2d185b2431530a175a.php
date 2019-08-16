<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:95:"E:\phpStudy\PHPTutorial\WWW\fastadmin\public/../application/admin\view\goods\add_recommend.html";i:1561349864;s:80:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\layout\default.html";i:1557482263;s:77:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\meta.html";i:1557482263;s:79:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\script.html";i:1557482263;}*/ ?>
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
                                <div id="add-form" class="form-horizontal" role="form" data-toggle="validator">
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" style="width: 15%;margin: 7px 0">菜&nbsp;&nbsp;&nbsp;品:</label>
        <div class="col-xs-12 col-sm-8" style="width: 40%">
            <input id="c-goods_id" class="form-control" name="row[goods_id]" type="text" value="<?php echo $info['name']; ?>" readonly="readonly">
            <input type="hidden" value="<?php echo $info['id']; ?>" id="goods_id"/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" style="width: 15%;margin: 7px 0">选择推荐:</label>
        <div class="col-xs-12 col-sm-8" style="width: 40%">
            <select id="c-recommend_id" class="form-control" name="row[recommend_id]" type="text">
                <?php if(is_array($goods) || $goods instanceof \think\Collection || $goods instanceof \think\Paginator): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>

    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <a type="submit" class="btn btn-success btn-embossed" onclick="submitResult()"><?php echo __('OK'); ?></a>
        </div>
    </div>
</div>
<script>
    function submitResult() {
        $.ajax({
            type:"POST",
            url:"goods/addRecommend",
            dataType:"json",
            data:{
                recommend_id:$("#c-recommend_id").val(),
                goods_id:$("#goods_id").val(),
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
                if (data == -2){
                    layer.alert("仅能推荐三个商品", {icon: 5});
                }
                if (data == -3){
                    layer.alert("该商品已被推荐", {icon: 5});
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