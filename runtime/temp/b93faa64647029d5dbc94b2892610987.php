<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:87:"E:\phpStudy\PHPTutorial\WWW\fastadmin\public/../application/admin\view\member\edit.html";i:1562809783;s:80:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\layout\default.html";i:1562751673;s:77:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\meta.html";i:1557482263;s:79:"E:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\script.html";i:1557482263;}*/ ?>
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
                                <form id="edit-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Avatar'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-avatar" class="form-control" size="50" name="row[avatar]" type="text" value="<?php echo htmlentities($row['avatar']); ?>">
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-avatar" class="btn btn-danger plupload" data-input-id="c-avatar" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="false" data-preview-id="p-avatar"><i class="fa fa-upload"></i> <?php echo __('Upload'); ?></button></span>
                    <!--<span><button type="button" id="fachoose-avatar" class="btn btn-primary fachoose" data-input-id="c-avatar" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> <?php echo __('Choose'); ?></button></span>-->
                </div>
                <span class="msg-box n-right" for="c-avatar"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-avatar"></ul>
        </div>
    </div>
    <!--<div class="form-group">-->
        <!--<label class="control-label col-xs-12 col-sm-2"><?php echo __('Level'); ?>:</label>-->
        <!--<div class="col-xs-12 col-sm-8">-->
            <!--<input id="c-level" class="form-control" name="row[level]" type="number" value="<?php echo htmlentities($row['level']); ?>">-->
        <!--</div>-->
    <!--</div>-->
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Nickname'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-nickname" class="form-control" name="row[nickname]" type="text" value="<?php echo htmlentities($row['nickname']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Mobile'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-mobile" class="form-control" name="row[mobile]" type="text" value="<?php echo htmlentities($row['mobile']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2">备注:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-member_remark" class="form-control" name="row[member_remark]" type="text" value="<?php echo htmlentities($row['member_remark']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Birthday'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-birthday" class="form-control" data-date-format="MM-DD" data-use-current="true" name="row[birthday]" type="text" value="<?php echo $row['birthday']; ?>">
        </div>
    </div>
    <div id="div">
        <?php if($event_lists != null): if(is_array($event_lists) || $event_lists instanceof \think\Collection || $event_lists instanceof \think\Paginator): $i = 0; $__LIST__ = $event_lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                <div class="form-group specialdays">
                    <label class="control-label col-xs-12 col-sm-2">会员特殊日:</label>
                    <div class="col-xs-12 col-sm-8">
                        <input id="c-remark" class="form-control" name="remark[]" type="text" style="width: 30%" value="<?php echo $v['remark']; ?>">
                        <input id="c-events"  style="width: 30%" class="form-control" data-date-format="MM-DD" data-use-current="true" name="events[]" type="text" value="<?php echo date('m-d',$v['eventstime']); ?>">
                        <a class="btn btn-xs btn-info" onclick="specialdays()">+</a>
                        <a class="btn btn-xs btn-danger delEvents" title="移除"> &nbsp;-&nbsp; </a>
                    </div>
                </div>
            <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <div class="form-group specialdays">
                <label class="control-label col-xs-12 col-sm-2">会员特殊日:</label>
                <div class="col-xs-12 col-sm-8">
                    <a class="btn btn-xs btn-info" onclick="specialdays()" title="添加"> + </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
        </div>
    </div>
</form>
<script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
<script>
    function specialdays() {
        var specialdays = $(" <div class=\"form-group specialdays\">\n" +
            "            <label class=\"control-label col-xs-12 col-sm-2\">会员特殊日:</label>\n" +
            "            <div class=\"col-xs-12 col-sm-8\">\n" +
            "                <input id=\"c-remark\" class=\"form-control\" name=\"remark[]\" type=\"text\" style=\"width: 30%\" placeholder=\"备注\">\n" +
            "                <input id=\"c-events\"  style=\"width: 30%\" class=\"form-control\" data-date-format=\"MM-DD\" data-use-current=\"true\" name=\"events[]\" type=\"text\" value=\"<?php echo date('m-d'); ?>\">\n" +
            "                <a class=\"btn btn-xs btn-info\" onclick=\"specialdays()\" title=\"添加\"> + </a>\n" +
            "                <a class=\"btn btn-xs btn-danger delEvents\" title=\"移除\"> &nbsp;-&nbsp; </a>\n" +
            "            </div>\n" +
            "        </div>");
        $("#div").append(specialdays);
    }

    //  	点击删除
    $("#div").on("click",".delEvents",function(){
        $(this).parent().parent().remove();
    });
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
