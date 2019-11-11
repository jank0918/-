<?php
// +----------------------------------------------------------------------
// | Quotes [其实台下的观众就我一个，其实我也看出你有点不舍]
// +----------------------------------------------------------------------
// | Created ( PhpStorm )
// +----------------------------------------------------------------------
// | Author: Jonathan <2213147257@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019年04月29日
// +----------------------------------------------------------------------

/** 三网费率设置 **/

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
                        费率设置
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="safe_switch.php" class="form-horizontal" role="form" method="post">
                        <h3>三网费率设置</h3>
                        <hr />
                        <div class="form-group">
                            <label class="col-sm-2 control-label">普通会员微信费率:</label>
                            <div class="col-sm-10">
                                <input type="text" name="money_rate_wxpay" value="<?php echo $conf['money_rate_wxpay']; ?>" class="form-control"/>
                                <small>*默认支付分成比例（百分数） 例如：97 = 收取3%的费率</small>
                            </div>
                        </div><br/>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">普通会员QQ费率:</label>
                            <div class="col-sm-10">
                                <input type="text" name="money_rate_qqpay" value="<?php echo $conf['money_rate_qqpay']; ?>" class="form-control"/>
                                <small>*默认支付分成比例（百分数） 例如：97 = 收取3%的费率</small>
                            </div>
                        </div><br/>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">普通会员支付宝费率:</label>
                            <div class="col-sm-10">
                                <input type="text" name="money_rate_alipay" value="<?php echo $conf['money_rate_alipay']; ?>" class="form-control"/>
                                <small>*默认支付分成比例（百分数） 例如：97 = 收取3%的费率</small>
                            </div>
                        </div><br/>
                        <h3>会员费率配置</h3>
                        <hr>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">黄金会员分成比例:</label>
                            <div class="col-sm-10">
                                <input type="text" name="money_rate_vip" value="<?php echo $conf['money_rate_vip']; ?>" class="form-control"/>
                                <small>*默认支付分成比例（百分数） 例如：97 = 收取3%的费率</small>
                            </div>
                        </div><br/>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">钻石会员分成比例:</label>
                            <div class="col-sm-10">
                                <input type="text" name="money_rate_svip" value="<?php echo $conf['money_rate_svip']; ?>" class="form-control"/>
                                <small>*默认支付分成比例（百分数） 例如：97 = 收取3%的费率</small>
                            </div>
                        </div><br/>
                        <h3>结算费率配置</h3>
                        <hr>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">普通会员结算费率:</label>
                            <div class="col-sm-10">
                                <input type="text" name="settle_rate" value="<?php echo $conf['settle_rate']; ?>" class="form-control"/>
                                <small>* 1 = 1%(100:1) </small>
                            </div>
                        </div><br/>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">黄金会员结算费率:</label>
                            <div class="col-sm-10">
                                <input type="text" name="settle_rate_vip" value="<?php echo $conf['settle_rate_vip']; ?>" class="form-control"/>
                                <small>* 1 = 1%(100:1) </small>
                            </div>
                        </div><br/>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">钻石会员结算费率:</label>
                            <div class="col-sm-10">
                                <input type="text" name="settle_rate_svip" value="<?php echo $conf['settle_rate_svip']; ?>" class="form-control"/>
                                <small>* 1 = 1%(100:1) </small>
                            </div>
                        </div><br/>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="保存" class="btn btn-primary form-control"/><br/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
