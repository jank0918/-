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

/** 二次安全验证开关 **/

$title='后台管理中心';
include("../includes/common.php");
include './head.php';
include 'nav.php';
if ($islogin == 1) {
} else {
    exit("<script language='javascript'>window.location.href='./login.php';</script>");
}
?>

<div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
<?php
if(isset($_POST['submit'])) {
    foreach ($_POST as $k => $value) {
        if($k=='pwd')continue;
        $value=daddslashes($value);
        $DB->query("insert into pay_config set `k`='{$k}',`v`='{$value}' on duplicate key update `v`='{$value}'");
    }
    showmsg('保存成功！',1);
    exit();
}
?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    用户二次安全验证配置
                </h3>
            </div>
            <div class="panel-body">
                <form action="safe_switch.php" class="form-horizontal" role="form" method="post">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">是否开启</label>
                        <div class="col-sm-10">
                            <select  class="form-control" name="safe_switch">
                                <?php if ($conf['safe_switch'] == 1){ ?>
                                <option value="1">开启</option>
                                <option value="0">关闭</option>
                                <?php }else{ ?>
                                <option value="0">关闭</option>
                                <option value="1">开启</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div><br/>
                    <?php if ($conf['safe_switch'] == 0){ ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">未开启提示信息:</label>
                        <div class="col-sm-10">
                            <input type="text" name="safe_info" value="<?php echo $conf['safe_info']; ?>" class="form-control"/>
                        </div>
                    </div><br/>
                    <?php } ?>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="保存" class="btn btn-primary form-control"/><br/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
