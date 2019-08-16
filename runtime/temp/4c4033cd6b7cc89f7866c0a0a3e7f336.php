<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:89:"E:\phpStudy\PHPTutorial\WWW\fastadmin\public/../application/admin\view\member\detail.html";i:1562812807;s:80:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\layout\default.html";i:1562751673;s:77:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\meta.html";i:1557482263;s:79:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\script.html";i:1557482263;}*/ ?>
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
        <label class="control-label col-xs-10 col-sm-2">用户昵称:</label>
        <div class="col-xs-12 col-sm-3">
            <span class="form-control" style="border: 1px solid #fff;"><?php echo htmlentities($info['nickname']); ?></span>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-10 col-sm-2">联系方式:</label>
        <div class="col-xs-12 col-sm-2">
            <span class="form-control" style="border: 1px solid #fff;"><?php echo htmlentities($info['mobile']); ?></span>
        </div>

        <label class="control-label col-xs-10 col-sm-2">累计消费金额:</label>
        <div class="col-xs-12 col-sm-2">
            <span class="form-control" style="border: 1px solid #fff;">￥<?php echo $info['money']; ?></span>
        </div>

        <label class="control-label col-xs-10 col-sm-2">会员生日:</label>
        <div class="col-xs-12 col-sm-3">
            <span class="form-control" style="border: 1px solid #fff;"><?php echo $info['birthday']; ?></span>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-10 col-sm-2">注册时间:</label>
        <div class="col-xs-12 col-sm-2">
            <span class="form-control" style="border: 1px solid #fff;"><?php echo date("Y-m-d H:i:s",$info['createtime']); ?></span>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-10 col-sm-2">特殊日子:</label>
        <div class="col-xs-12 col-sm-2">
            <span class="form-control" style="border: 1px solid #fff;">共 <?php echo $event_count; ?> 个</span>
        </div>
    </div>
    <?php if($event_count != 0): ?>
        <div class="form-group">
            <label class="control-label col-xs-10 col-sm-2"></label>
            <div class="col-xs-12 col-sm-10">
                <table id="table" class="table table-striped table-bordered table-hover table-nowrap">
                    <tr>
                        <th>备注</th>
                        <th>日期</th>
                        <th>操作</th>
                    </tr>
                    <?php if(is_array($events) || $events instanceof \think\Collection || $events instanceof \think\Paginator): $i = 0; $__LIST__ = $events;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                    <tr>
                        <td><?php echo $v['remark']; ?></td>
                        <td><?php echo date('m-d',$v['eventstime']); ?></td>
                        <td>
                            <a class="btn btn-xs btn-danger" onclick="del_event('<?php echo $v['id']; ?>')"><?php echo __('Delete'); ?></a>
                        </td>
                    </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
            </div>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <label class="control-label col-xs-10 col-sm-2">历史订单:</label>
        <div class="col-xs-12 col-sm-2">
            <span class="form-control" style="border: 1px solid #fff;">共 <?php echo $order_count; ?> 个</span>
        </div>
    </div>
    <?php if($order_count != 0): ?>
    <div class="form-group">
        <label class="control-label col-xs-10 col-sm-2"></label>
        <div class="col-xs-12 col-sm-10">
            <table id="table" class="table table-striped table-bordered table-hover table-nowrap">
                <tr>
                    <th>包厢名称</th>
                    <th>订单编号</th>
                    <th>支付类型</th>
                    <th>支付状态</th>
                    <th>实际支付金额</th>
                    <th>日期</th>
                    <th>操作</th>
                </tr>
                <?php if(is_array($orderlists) || $orderlists instanceof \think\Collection || $orderlists instanceof \think\Paginator): $i = 0; $__LIST__ = $orderlists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td><?php echo $v['balcony_name']; ?></td>
                    <td><?php echo $v['order_no']; ?></td>
                    <td>
                        <?php if($v['payment_from'] == 0): ?>
                            微信
                        <?php elseif($v['payment_from'] == 1): ?>
                            支付宝
                        <?php elseif($v['payment_from'] == 2): ?>
                            现金
                        <?php elseif($v['payment_from'] == 3): ?>
                            刷卡
                        <?php elseif($v['payment_from'] == 4): ?>
                            挂账
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($v['payment_status'] == 0): ?>
                            未支付
                        <?php elseif($v['payment_status'] == 1): ?>
                            已支付
                        <?php endif; ?>
                    </td>
                    <td><?php echo $v['pay_money']; ?></td>
                    <td><?php echo date("Y-m-d H:i:s",$v['createtime']); ?></td>
                    <td>
                        <a class="btn btn-xs btn-info" onclick="order_detail('<?php echo url('/admin/', '', '', true); ?>order/detail?ids=<?php echo $v['id']; ?>')">查看</a>
                    </td>
                </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </table>
        </div>
    </div>
    <?php endif; ?>
</form>
<script>
    function order_detail(url) {
        layer.open({
            type: 2,
            title: '很多时候，我们想最大化看，比如像这个页面。',
            shadeClose: true,
            shade: false,
            maxmin: true, //开启最大化最小化按钮
            area: ['893px', '600px'],
            content: url
        });
    }

    function del_event(id) {
        layer.confirm('操作后将不可恢复,请确认进行此操作？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                dataType:"json",
                data:{
                    id:id
                },
                method:"post",
                url:"Member/del_event",
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
