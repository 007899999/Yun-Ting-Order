<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:101:"E:\phpStudy\PHPTutorial\WWW\fastadmin\public/../application/admin\view\Balcony\historical_orders.html";i:1562037965;s:80:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\layout\default.html";i:1557482263;s:77:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\meta.html";i:1557482263;s:79:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\script.html";i:1557482263;}*/ ?>
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
</style>
<table id="table" class="table table-striped table-bordered table-hover table-nowrap">
    <tr>
        <th>商品信息</th>
        <th>订单时间</th>
        <th>订单金额</th>
        <th>应付金额</th>
        <th>实付金额</th>
        <th>会员</th>
        <th>订单状态</th>
        <th>是否支付</th>
        <th>支付类型</th>
        <th>操作</th>
    </tr>
    <?php if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
    <tr>
        <td>
            <?php if($v['goods'] != null): if(is_array($v['goods']) || $v['goods'] instanceof \think\Collection || $v['goods'] instanceof \think\Paginator): $i = 0; $__LIST__ = $v['goods'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <?php echo $vo['name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    规格：<?php echo $vo['attribute_price_names']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    单价：￥<?php echo $vo['price']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    购买数量：<?php echo $vo['num']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <br>
                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
        </td>
        <td><?php echo date('Y-m-d H:i:s',$v['createtime']); ?></td>
        <td>￥<?php echo $v['sum_money']; ?></td>
        <td>￥<?php echo $v['payable_money']; ?></td>
        <td>￥<?php echo $v['pay_money']; ?></td>
        <td><?php if($v['nickname'] == null): ?>-<?php else: ?><?php echo $v['nickname']; endif; ?></td>
        <td><?php if($v['order_status'] == 0): ?>未支付<?php else: ?>已支付<?php endif; ?></td>
        <td><?php if($v['payment_status'] == 0): ?>未支付<?php else: ?>已支付<?php endif; ?></td>
        <td><?php if($v['payment_from'] == 0): ?>
                微信
            <?php elseif($v['payment_from'] == 1): ?>
                支付宝
            <?php elseif($v['payment_from'] == 2): ?>
                现金
            <?php elseif($v['payment_from'] == 3): ?>
                刷卡
            <?php elseif($v['payment_from'] == 4): ?>
                挂账
            <?php else: ?>
                暂无
            <?php endif; ?>
        </td>
        <td>
            <a class="btn btn-xs btn-info" onclick="open_window('<?php echo url('/admin/', '', '', true); ?>Order/detail?ids=<?php echo $v['id']; ?>','查看')">查看</a>
        </td>
    </tr>
    <?php endforeach; endif; else: echo "" ;endif; ?>
</table>
<?php echo $orders->render(); ?>
<script>
    function open_window(url,title) {
        layer.open({
            type: 2,
            title: title,
            maxmin: true, //开启最大化最小化按钮
            area: ['893px', '600px'],
            content: url
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