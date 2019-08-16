<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:96:"E:\phpStudy\PHPTutorial\WWW\fastadmin\public/../application/admin\view\finance\weekly\index.html";i:1561793895;s:80:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\layout\default.html";i:1557482263;s:77:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\meta.html";i:1557482263;s:79:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\script.html";i:1557482263;}*/ ?>
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
                                <div class="panel panel-default panel-intro">
    <?php echo build_heading(); ?>

    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">
                    <table id="table" class="table table-striped table-bordered table-hover table-nowrap"
                           width="100%">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script id="customformtpl" type="text/html">
    <form action="" class="form-commonsearch">
        <div style="border-radius:2px;margin-bottom:10px;background:#f5f5f5;padding:20px;">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-3" style="min-height:60px;">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2" style="width: 50px;padding: 5px 8px">付款方式</label>
                        <div class="col-xs-12 col-sm-8">
                            <select id="balcony_ids" class="form-control selectpicker" multiple name="balcony_ids" style="height:31px;width: 50%;">
                                <option value="">微信</option>
                                <option value="">支付宝</option>
                                <option value="">银行卡</option>
                                <option value="">现金</option>
                                <option value="">挂账</option>
                            </select>
                            <input type="hidden" class="operate" data-name="balcony_ids"/>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-3" style="min-height:60px;">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2" style="width: 50px;padding: 5px 8px">时间段</label>
                        <div class="col-xs-12 col-sm-8">
                            <input id="c-start_time" class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss" data-use-current="true" name="start_time" type="text">
                             -
                            <input id="c-end_time" class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss" data-use-current="true" name="end_time" type="text">
                            <input type="hidden" class="operate" data-name="start_time"/>
                            <input type="hidden" class="operate" data-name="end_time"/>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3" style="min-height:60px;margin: 50px 0 5px 0">
                    <div class="form-group">
                        <label class="control-label"></label>
                        <div class="row">
                            <div class="col-xs-6">
                                <input type="submit" class="btn btn-success btn-block" value="提交"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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