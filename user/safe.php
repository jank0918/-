<?php
// +----------------------------------------------------------------------
// | Quotes [其实台下的观众就我一个，其实我也看出你有点不舍]
// +----------------------------------------------------------------------
// | Created ( PhpStorm )
// +----------------------------------------------------------------------
// | Author: Jonathan <2213147257@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019年02月20日
// +----------------------------------------------------------------------

/** 二次验证 **/

include '../includes/common.php';
header("Content-Type: text/html;charset=utf-8");
if ($conf['safe_switch'] == 0){
    exit("<script>alert('{$conf['safe_info']}');window.location.href='./';</script>");
}
if (empty($userrow['safecode'])){
    @header('Content-Type: text/html; charset=UTF-8');
    exit("<script language='javascript'>alert('你还没有设置安全码！');window.location.href='userinfo.php';</script>");
}
if ($islogin3 == 1){
    @header('Content-Type: text/html; charset=UTF-8');
    exit("<script language='javascript'>alert('已经验证过啦！');window.location.href='./';</script>");
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8" />
    <title>安全码二次验证 | <?php echo $conf['web_name']?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="https://template.down.swap.wang/ui/angulr_2.0.1/bower_components/bootstrap/dist/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="https://template.down.swap.wang/ui/angulr_2.0.1/bower_components/animate.css/animate.css" type="text/css" />
    <link rel="stylesheet" href="https://template.down.swap.wang/ui/angulr_2.0.1/bower_components/font-awesome/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="https://template.down.swap.wang/ui/angulr_2.0.1/bower_components/simple-line-icons/css/simple-line-icons.css" type="text/css" />
    <link rel="stylesheet" href="https://template.down.swap.wang/ui/angulr_2.0.1/html/css/font.css" type="text/css" />
    <link rel="stylesheet" href="https://template.down.swap.wang/ui/angulr_2.0.1/html/css/app.css" type="text/css" />
    <style>input:-webkit-autofill{-webkit-box-shadow:0 0 0px 1000px white inset;-webkit-text-fill-color:#333;}</style>
</head>
<body>
<div class="app app-header-fixed  ">
    <div class="container w-xxl w-auto-xs" ng-controller="SigninFormController" ng-init="app.settings.container = false;">
        <span class="navbar-brand block m-t"><?php echo $conf['web_name']?></span>
        <div class="m-b-lg">
            <div class="wrapper text-center">
                <strong>请输入你的安全码</strong>
            </div>
            <form name="form" class="form-validation" method="post" action="safe.php">
                <div class="text-danger wrapper text-center" ng-show="authError">
                </div>
                <div class="list-group list-group-sm swaplogin">
                    <div class="list-group-item">
                        <input type="password" name="safecode" placeholder="安全码" class="form-control no-border" required>
                    </div>
                </div>
                <button  type="button" id="tosafecode" class="btn btn-lg btn-primary btn-block" ng-disabled='form.$invalid'>验证</button>
                <div class="line line-dashed"></div>
            </form>
        </div>
        <div class="text-center">
            <p>
                <small class="text-muted"><?php echo $conf['web_name']?><br>&copy; 2016~2019</small>
            </p>
        </div>
    </div>
</div>
<script src="https://template.down.swap.wang/ui/angulr_2.0.1/bower_components/jquery/dist/jquery.min.js"></script>
<script src="../assets/layer/layer.js"></script>
<script>
    $(function () {
        $("#tosafecode").click(function(){
            var safecode=$("input[name='safecode']").val();
            if(safecode==''){layer.alert('请填写安全码！');return false;}
            var load = layer.load(2, {shade:[0.1,'#fff']});
            $.ajax({
                type : "POST",
                url : "ajax2.php?act=tosafecode",
                data : {safecode:safecode},
                dataType : 'json',
                success : function(data) {
                    layer.close(load);
                    if(data.code == 1){
                        layer.alert('验证成功！');
                        window.location.href='./';
                    }else{
                        layer.alert(data.msg);
                    }
                }
            });
        });
    });
</script>
</body>
</html>