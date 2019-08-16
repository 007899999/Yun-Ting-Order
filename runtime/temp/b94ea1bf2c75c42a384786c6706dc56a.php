<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:88:"E:\phpStudy\PHPTutorial\WWW\fastadmin\public/../application/admin\view\order\detail.html";i:1562038717;s:80:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\layout\default.html";i:1562751673;s:77:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\meta.html";i:1557482263;s:79:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\script.html";i:1557482263;}*/ ?>
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
                                <style>
    table th{
        text-align: center !important;
        vertical-align: middle !important;
    }
    table td{
        text-align: center !important;
        vertical-align: middle !important;
    }
    .form-horizontal .control-label {
        margin-right:1px ;
        width: 10%;
    }

    span.form-control{
        padding: 6px 0;
    }
</style>
<form id="edit-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">
    <div class="form-group">
        <label class="control-label col-xs-10 col-sm-2">包厢名称:</label>
        <div class="col-xs-12 col-sm-3">
            <span class="form-control" style="border: 1px solid #fff;"><?php echo htmlentities($info['balcony_name']); ?></span>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-10 col-sm-2">订单编号:</label>
        <div class="col-xs-12 col-sm-2">
            <span class="form-control" style="border: 1px solid #fff;"><?php echo htmlentities($info['order_no']); ?></span>
        </div>

        <label class="control-label col-xs-10 col-sm-2">支付方式:</label>
        <div class="col-xs-12 col-sm-2">
            <span class="form-control" style="border: 1px solid #fff;">
                <?php if($info['payment_from'] == 0): ?>
                    微信
                <?php elseif($info['payment_from'] == 1): ?>
                    支付宝
                <?php elseif($info['payment_from'] == 2): ?>
                    现金
               <?php elseif($info['payment_from'] == 3): ?>
                    刷卡
               <?php elseif($info['payment_from'] == 4): ?>
                    挂账
                <?php else: ?>
                    -
                <?php endif; ?>
            </span>
        </div>

        <label class="control-label col-xs-10 col-sm-2">订单时间:</label>
        <div class="col-xs-12 col-sm-3">
            <span class="form-control" style="border: 1px solid #fff;"><?php echo date("Y-m-d H:i:s",$info['createtime']); ?></span>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-10 col-sm-2">商品总价:</label>
        <div class="col-xs-12 col-sm-2">
            <span class="form-control" style="border: 1px solid #fff;">￥<?php echo htmlentities($info['sum_money']); ?></span>
        </div>

        <label class="control-label col-xs-10 col-sm-2">应付金额:</label>
        <div class="col-xs-12 col-sm-2">
            <span class="form-control" style="border: 1px solid #fff;">￥<?php echo htmlentities($info['payable_money']); ?></span>
        </div>

        <label class="control-label col-xs-10 col-sm-2">实付金额:</label>
        <div class="col-xs-12 col-sm-3">
            <span class="form-control" style="border: 1px solid #fff;">￥<?php echo $info['pay_money']; ?></span>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-10 col-sm-2">会员:</label>
        <div class="col-xs-12 col-sm-2">
            <span class="form-control" style="border: 1px solid #fff;"><?php if($info['nickname'] == null): ?>-<?php else: ?><?php echo $info['nickname']; endif; ?></span>
        </div>

        <label class="control-label col-xs-10 col-sm-2">是否开票:</label>
        <div class="col-xs-12 col-sm-2">
            <span class="form-control" style="border: 1px solid #fff;"><?php if($info['is_invoice'] == 0): ?>暂不开票<?php else: ?>开票<?php endif; ?></span>
        </div>

        <label class="control-label col-xs-10 col-sm-2">备注:</label>
        <div class="col-xs-12 col-sm-3">
            <span class="form-control" style="border: 1px solid #fff;"><?php echo htmlentities($info['remark']); ?></span>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-10 col-sm-2">共<?php echo $goods_count; ?>道菜</label>
        <div class="col-xs-12 col-sm-10">
            <table id="table" class="table table-striped table-bordered table-hover table-nowrap">
                <tr>
                    <th>图片</th>
                    <th>菜品名称</th>
                    <th>菜品信息</th>
                    <th>单价</th>
                    <th>数量</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                <?php if(is_array($goods) || $goods instanceof \think\Collection || $goods instanceof \think\Paginator): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td><image src="<?php echo $v['image']; ?>" style="width: 50px;height: 50px;"></image></td>
                    <td><?php echo $v['name']; ?></td>
                    <td><?php echo $v['attribute_price_names']; ?></td>
                    <td><?php echo $v['price']; ?></td>
                    <td><?php echo $v['num']; ?></td>
                    <td><?php if($v['status'] == 0): ?>备餐中<?php elseif($v['status'] == 1): ?>已上餐<?php endif; ?></td>
                    <td>
                        <?php if($v['status'] == 0): ?>
                            <a class="btn btn-xs btn-info" onclick="dinner('<?php echo $v['id']; ?>')">上餐</a>
                        <?php endif; ?>
                        <a class="btn btn-xs btn-danger" onclick="del_goods('<?php echo $v['id']; ?>')"><?php echo __('Delete'); ?></a>
                    </td>
                </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </table>
        </div>
    </div>
</form>
<script>
    function dinner(id) {
        layer.confirm('确认已经上餐？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                dataType:"json",
                data:{
                    id:id
                },
                method:"post",
                url:"order/dinner",
                success:function (data) {
                    if (data == 1){
                        layer.msg('已上餐', {icon: 1});
                        location.reload();
                    }
                }
            });
        }, function(){
            layer.msg('已取消', {icon: 1});
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
                url:"order/del_goods",
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
