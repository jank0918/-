<?php
// +----------------------------------------------------------------------
// | Quotes [其实台下的观众就我一个，其实我也看出你有点不舍]
// +----------------------------------------------------------------------
// | Created ( PhpStorm )
// +----------------------------------------------------------------------
// | Author: Jonathan <2213147257@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019年05月20日
// +----------------------------------------------------------------------

/** 改变订单状态 **/

include("../includes/common.php");
$title='修改订单状态';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
include 'nav.php';
function TypeToZh($type){
    if ($type == 'wxpay'){
        $zh = '微信';
    }elseif ($type == 'qqpay'){
        $zh = 'QQ';
    }elseif ($type == 'alipay'){
        $zh = '支付宝';
    }else{
        $zh = NULL;
    }
    return $zh;
}
?>
<div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
<?php
if (isset($_GET['oid'])){
    $oid = $_GET['oid'];
    $info = $DB->query("SELECT * FROM `pay_order` WHERE `trade_no` = '$oid'")->fetch();
    if ($info['trade_no'] == ''){
        exit("<script>alert('该订单不存在！');window.history.go(-1);</script>");
    }
}else{
    exit("<script>alert('你猜猜是什么问题');window.history.go(-1);</script>");
}
if (isset($_POST['status'])){
    $status = $_POST['status'];
    if ($status == 1){
        $sql = "UPDATE `pay_order` SET `status` = '$status',`endtime`='$date' WHERE `trade_no` = '$oid';UPDATE `pay_user` SET `money` = `money` + {$info['money']} WHERE `id` = {$info['pid']}";
    }else{
        $sql = "UPDATE `pay_order` SET `status` = '$status',`endtime`=NULL WHERE `trade_no` = '$oid';UPDATE `pay_user` SET `money` = `money` - {$info['money']} WHERE `id` = {$info['pid']}";
    }
    if ($DB->query($sql)){
        showmsg('修改成功<br><a href="order.php"><<<返回订单列表</a>',1);
    }else{
        showmsg('修改失败'.$DB->errorCode().'<br><a href="order.php"><<<返回订单列表</a>',4);
    }
}else{
?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    修改订单状态
                </h3>
            </div>
            <div class="panel-body">
                <form action="change_order.php?oid=<?=$oid?>" class="form-horizontal" role="form" method="post">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">订单号</label>
                        <div class="col-sm-10">
                            <input disabled type="text" value="<?=$info['trade_no']?>" class="form-control"/>
                        </div>
                    </div><br/>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">商户号</label>
                        <div class="col-sm-10">
                            <input disabled type="text" value="<?=$info['pid']?>" class="form-control"/>
                        </div>
                    </div><br/>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">商品名称</label>
                        <div class="col-sm-10">
                            <input disabled type="text" value="<?=$info['name']?>" class="form-control"/>
                        </div>
                    </div><br/>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">支付方式</label>
                        <div class="col-sm-10">
                            <input disabled type="text" value="<?=TypeToZh($info['type'])?>" class="form-control"/>
                        </div>
                    </div><br/>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">开始时间</label>
                        <div class="col-sm-10">
                            <input disabled type="text" value="<?=$info['addtime']?>" class="form-control"/>
                        </div>
                    </div><br/>
                    <?php
                    if ($info['status'] == 1){
                        ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">开始时间</label>
                            <div class="col-sm-10">
                                <input disabled type="text" value="<?=$info['endtime']?>" class="form-control"/>
                            </div>
                        </div><br/>
                    <?php
                    }
                    ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">是否开启</label>
                        <div class="col-sm-10">
                            <select  class="form-control" name="status">
                                <?php if ($info['status'] == 1){ ?>
                                    <option value="1">已完成</option>
                                    <option value="0">未完成</option>
                                <?php }else{ ?>
                                    <option value="0">未完成</option>
                                    <option value="1">已完成</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div><br/>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="保存" class="btn btn-primary form-control"/><br/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
<?php } ?>