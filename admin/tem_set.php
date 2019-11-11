<?php
// +----------------------------------------------------------------------
// | Quotes [其实台下的观众就我一个，其实我也看出你有点不舍]
// +----------------------------------------------------------------------
// | Created ( PhpStorm )
// +----------------------------------------------------------------------
// | Author: Jonathan <2213147257@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019年02月16日
// +----------------------------------------------------------------------

/** 首页模板设置 **/

header("Content-Type: text/html;charset=utf-8");
$title='后台管理中心';
include("../includes/common.php");
include './head.php';
if ($islogin == 1) {
} else {
    exit("<script language='javascript'>window.location.href='./login.php';</script>");
}
include 'nav.php';
?>
<div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
<?php
if(isset($_POST['template'])) {
    foreach ($_POST as $k => $value) {
        if($k=='pwd')continue;
        $value=daddslashes($value);
        $DB->query("insert into pay_config set `k`='{$k}',`v`='{$value}' on duplicate key update `v`='{$value}'");
    }
    showmsg('修改成功！',1);
    exit();
}
$templates = getTemplates();
$template = getTemplate();

?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">首页模板设置</h3>
            </div>
            <div class="panel-body">
                <form action="tem_set.php" method="post" class="form-horizontal" role="form">
                    <div class="panel panel-default">
                        <div class="panel-body ">
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="thumbnail">
                                        <img src="<?php echo '../template/'.$conf['template'].'/demo.jpg' ?>"
                                             alt="模板演示图">
                                        <div class="caption">
                                            <?php
                                            $json = file_get_contents( '../template/'.$conf['template'].'/package.json');
                                            $info = json_decode($json,true);
                                            ?>
                                            <h3><?=$info['name']?></h3>
                                            <p><?=$info['sketch']?></p>

                                        </div>
                                    </div>

                                </div>
                                <div style="margin-top: 20px;color:black">
                                    <span style="color:red;font-weight: bold">模板作者：<?=$info['author']?></span><br>
                                    <span style="color:blue">更新时间：<?=$info['update_time']?></span><br>
                                    <a style="color:pink" href="<?=$info['website']?>">作者官网：<?=$info['website']?></a><br>
                                    <span  style="color:gold">模板介绍：<br><?= str_replace("·", "<br>", $info['dependencies'])?></span><br>
                                </div>
                            </div>
                            <div class="row">
                                <?php for ($i = 0; $i < $templates; $i++){
                                    $json2 = file_get_contents( '../template/'.$template[$i].'/package.json');
                                    $info2 = json_decode($json2,true);
                                    if (!$info2){
                                        continue;
                                    }
                                    ?>
                                    <div class="thumbnail col-lg-6 col-md-6">
                                        <img src="<?php echo '../template/'.$template[$i].'/demo.jpg'; ?> " alt="模板演示图">
                                        <div class="caption">
                                            <h3><?=$info2['name']?></h3>
                                            <p><?=$info2['sketch']?></p>
                                            <p>
                                                <?php
                                                if($conf['template'] == $template[$i]){
                                                    ?>
                                                    <a  class="btn btn-primary" role="button" style="width: 100%;background-color: gray;border: 0px;">
                                                        当前使用
                                                    </a>
                                                    <?php
                                                }else{
                                                    ?>
                                                    <button name="template" value="<?php echo $template[$i] ?>" class="btn btn-primary" role="button" style="width: 100%;background-color: #436EEE" name="submit" )">
                                                                                                                                                                                                                   使用该模板
                                                    </button>
                                                    <?php
                                                }
                                                ?>


                                            </p>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer">
                <span class="glyphicon glyphicon-info-sign"></span>
                模板放进template目录这里自动加载，不是傻瓜式的模板切换。<br />&nbsp;&nbsp;&nbsp;&nbsp;注意：如果非模板类的文件不会自动加载！
            </div>
        </div>
    </div>
</div>