<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:91:"E:\phpStudy\PHPTutorial\WWW\fastadmin\public/../application/admin\view\cate\recyclebin.html";i:1560564810;s:80:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\layout\default.html";i:1557482263;s:77:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\meta.html";i:1557482263;s:79:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\script.html";i:1557482263;}*/ ?>
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
                    <div id="toolbar" class="toolbar">
                        <?php echo build_toolbar('refresh'); ?>
                        <a class="btn btn-info btn-multi btn-disabled disabled <?php echo $auth->check('cate/restore')?'':'hide'; ?>" href="javascript:;" data-url="cate/restore" data-action="restore"><i class="fa fa-rotate-left"></i> <?php echo __('Restore'); ?></a>
                        <a class="btn btn-danger btn-multi btn-disabled disabled <?php echo $auth->check('cate/destroy')?'':'hide'; ?>" href="javascript:;" data-url="cate/destroy" data-action="destroy"><i class="fa fa-times"></i> <?php echo __('Destroy'); ?></a>
                        <a class="btn btn-success btn-restoreall <?php echo $auth->check('cate/restore')?'':'hide'; ?>" href="javascript:;" data-url="cate/restore" title="<?php echo __('Restore all'); ?>"><i class="fa fa-rotate-left"></i> <?php echo __('Restore all'); ?></a>
                        <a class="btn btn-danger btn-destroyall <?php echo $auth->check('cate/destroy')?'':'hide'; ?>" href="javascript:;" data-url="cate/destroy" title="<?php echo __('Destroy all'); ?>"><i class="fa fa-times"></i> <?php echo __('Destroy all'); ?></a>
                    </div>
                    <table id="table" class="table table-striped table-bordered table-hover"
                           data-operate-restore="<?php echo $auth->check('cate/restore'); ?>"
                           data-operate-destroy="<?php echo $auth->check('cate/destroy'); ?>"
                           width="100%">
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/fastadmin/public/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/fastadmin/public/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>