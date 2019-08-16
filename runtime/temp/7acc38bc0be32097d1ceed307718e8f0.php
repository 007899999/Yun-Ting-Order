<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:91:"E:\phpStudy\PHPTutorial\WWW\fastadmin\public/../application/admin\view\goods\recommend.html";i:1561973371;s:80:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\layout\default.html";i:1562751673;s:77:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\meta.html";i:1557482263;s:79:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\script.html";i:1557482263;}*/ ?>
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
                                
    <a type="button" class="btn btn-info" onclick="open_window('添加推荐商品','<?php echo url('/admin/', '', '', true); ?>Goods/addRecommend?goods_id=<?php echo $ids; ?>')">添加</a>
    <hr>
    <table id="table" class="table table-striped table-bordered table-hover table-nowrap">
        <tr>
            <th>图片</th>
            <th>名称</th>
            <th>单价</th>
            <th>销量</th>
            <th>时间</th>
            <th>操作</th>
        </tr>
        <?php if(is_array($goods) || $goods instanceof \think\Collection || $goods instanceof \think\Paginator): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
        <tr>
            <td><image src="<?php echo $v['image']; ?>" style="width: 50px;height: 50px;"></image></td>
            <td><?php echo $v['name']; ?></td>
            <td><?php echo $v['price']; ?></td>
            <td><?php echo $v['sales']; ?></td>
            <td><?php echo date('Y-m-d',$v['createtime']); ?></td>
            <td>
                <a class="btn btn-xs btn-danger" onclick="del_goods('<?php echo $v['id']; ?>')"><?php echo __('Delete'); ?></a>
            </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </table>
<script>
    function open_window(title,url) {
        layer.open({
            type: 2,
            title: title,
            shadeClose: true,
            shade: 0.5,
            // maxmin: true, //开启最大化最小化按钮
            area: ['60%', '60%'],
            content: url
        });
    }

    function del_goods(id) {
        layer.confirm('操作后将不可恢复,请确认进行此操作？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                dataType:"json",
                data:{
                    id:id
                },
                method:"post",
                url:"goods/del_goods",
                success:function (data) {
                    if (data == 1){
                        layer.msg('已删除', {icon: 1});
                        location.reload();
                    }
                }
            });
        }, function(){
            layer.msg('已取消', {icon: 1});
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
