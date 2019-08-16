<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:91:"E:\phpStudy\PHPTutorial\WWW\fastadmin\public/../application/admin\view\dashboard\index.html";i:1561432875;s:80:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\layout\default.html";i:1562751673;s:77:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\meta.html";i:1557482263;s:79:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\script.html";i:1557482263;}*/ ?>
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
                                <style type="text/css">
    .sm-st {
        background: #fff;
        padding: 20px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        margin-bottom: 20px;
        -webkit-box-shadow: 0 1px 0px rgba(0, 0, 0, 0.05);
        box-shadow: 0 1px 0px rgba(0, 0, 0, 0.05);
    }

    .sm-st-icon {
        width: 60px;
        height: 60px;
        display: inline-block;
        line-height: 60px;
        text-align: center;
        font-size: 30px;
        background: #eee;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        float: left;
        margin-right: 10px;
        color: #fff;
    }

    .sm-st-info {
        font-size: 12px;
        padding-top: 2px;
    }

    .sm-st-info span {
        display: block;
        font-size: 24px;
        font-weight: 600;
    }

    .sm-st .green {
        background: #86ba41 !important;
    }


    .stats .stat-icon {
        color: #28bb9c;
        display: inline-block;
        font-size: 26px;
        text-align: center;
        vertical-align: middle;
        width: 50px;
        float: left;
    }

    .stat .value {
        font-size: 20px;
        line-height: 24px;
        overflow: hidden;
        text-overflow: ellipsis;
        font-weight: 500;
    }

    .stat .name {
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .stat.lg .value {
        font-size: 26px;
        line-height: 28px;
    }

    .stat.lg .name {
        font-size: 16px;
    }

    .stat-col .progress {
        height: 2px;
    }

    .stat-col .progress-bar {
        line-height: 2px;
        height: 2px;
    }

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
<?php if(preg_match('/\/admin\/|admin\.php|admin_d75KABNWt\.php/i', url())): ?>
<div class="alert alert-danger-light">
    <?php echo __('Security tips'); ?>
</div>
<?php endif; ?>
<div class="panel panel-default panel-intro">
    <!--会员特殊日开始-->
    <?php if($lists != null): ?>
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#birthday" data-toggle="tab">会员特殊日</a></li>
            </ul>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade active in" id="birthday">
                    <table id="table" class="table table-striped table-bordered table-hover table-nowrap">
                        <tr>
                            <th>会员id</th>
                            <th>会员昵称</th>
                            <th>会员手机号</th>
                            <th>会员特殊日</th>
                            <th>备注</th>
                        </tr>
                        <?php if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                            <tr>
                                <td><?php echo $v['member_id']; ?></td>
                                <td><?php echo $v['nickname']; ?></td>
                                <td><?php echo $v['mobile']; ?></td>
                                <td><?php echo date('m-d',$v['eventstime']); ?></td>
                                <td><?php echo $v['remark']; ?></td>
                            </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <!--会员特殊日结束-->

    <!--数据分析开始-->
    <div class="panel-heading">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#data_n" data-toggle="tab">数据分析</a></li>
        </ul>
    </div>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane fade active in" id="data_n">
                <div class="row" style="margin-top:15px;">
        <div class="col-lg-12">
        </div>
        <div class="col-xs-6 col-md-3">
            <div class="panel bg-blue">
                <div class="panel-body">
                    <div class="panel-title">
                        <h5>订单量</h5>
                    </div>
                    <div class="panel-content">
                        <h1 class="no-margins"><?php echo $order_count; ?></h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-md-3">
            <div class="panel bg-aqua-gradient">
                <div class="panel-body">
                    <div class="ibox-title">
                        <h5>货品</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?php echo $goods_count; ?></h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-md-3">
            <div class="panel bg-purple-gradient">
                <div class="panel-body">
                    <div class="ibox-title">
                        <h5>累计收入</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?php echo $income; ?></h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-md-3">
            <div class="panel bg-green-gradient">
                <div class="panel-body">
                    <div class="ibox-title">
                        <h5>会员数量</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?php echo $member_count; ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
            </div>
        </div>
    </div>
    <!--数据分析结束-->

    <!--统计图开始-->
    <div class="panel-heading">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#one" data-toggle="tab">日订单趋势</a></li>
            <li><a href="#two" data-toggle="tab">月订单趋势</a></li>
            <li><a href="#three" data-toggle="tab">会员数量趋势</a></li>
        </ul>
    </div>
    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="echart1" style="height:350px;width:100%;"></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="two">
                <div class="row">
                    <div class="col-xs-12">
                        <div id="echart2" style="height:350px;width:100%;"></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="three">
                <div class="row">
                    <div class="col-xs-12">
                        <div id="echart3" style="height:350px;width:100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--统计图结束-->
</div>
<script src="/fastadmin/public/assets/shuxing/js/jquery-1.8.3.min.js"></script>
<script src="https://code.highcharts.com.cn/highcharts/highcharts.js"></script>
<script src="https://code.highcharts.com.cn/highcharts/modules/exporting.js"></script>
<script src="https://code.highcharts.com.cn/highcharts/modules/series-label.js"></script>
<script src="https://code.highcharts.com.cn/highcharts/modules/oldie.js"></script>
<script src="https://code.highcharts.com.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
<script>

    //日订单趋势
    $.ajax({
        type:"POST",
        url:"Dashboard/getData",
        dataType:"json",
        data:{
            type:1
        },
        success:function(data){
            var chart1 = Highcharts.chart('echart1', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: '订单趋势'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: data.time
                },
                yAxis: {
                    title: {
                        text: '订单量'
                    }
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            // 开启数据标签
                            enabled: true
                        },
                        // 关闭鼠标跟踪，对应的提示框、点击事件会失效
                        enableMouseTracking: false
                    }
                },
                series: data.list
            });
        }
    });

    //月订单趋势
    $.ajax({
        type:"POST",
        url:"Dashboard/getData",
        dataType:"json",
        data:{
            type:2
        },
        success:function(data){
            var chart2 = Highcharts.chart('echart2', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: '订单趋势'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: data.time
                },
                yAxis: {
                    title: {
                        text: '订单量'
                    }
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            // 开启数据标签
                            enabled: true
                        },
                        // 关闭鼠标跟踪，对应的提示框、点击事件会失效
                        enableMouseTracking: false
                    }
                },
                series: data.list
            });
        }
    });

    //会员数量趋势
    $.ajax({
        type:"POST",
        url:"Dashboard/getData",
        dataType:"json",
        data:{
            type:3
        },
        success:function(data){
            var chart3 = Highcharts.chart('echart3', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: '订单趋势'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: data.time
                },
                yAxis: {
                    title: {
                        text: '订单量'
                    }
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            // 开启数据标签
                            enabled: true
                        },
                        // 关闭鼠标跟踪，对应的提示框、点击事件会失效
                        enableMouseTracking: false
                    }
                },
                series: data.list
            });
        }
    });

</script>

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
