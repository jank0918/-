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

/** sdk.php **/

include("../includes/common.php");
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$title='支付测试';
include './head.php';
?>
<div id="content" class="app-content" role="main">
    <div class="app-content-body ">

        <div class="bg-light lter b-b wrapper-md hidden-print">
            <h1 class="m-n font-thin h3">支付测试</h1>
        </div>
        <div class="wrapper-md control">
            <div class="panel panel-default">
                <div class="panel-heading font-bold">
                    支付测试
                </div>
                <div class="panel-body">
                    <form class="form-horizontal devform" name=alipayment method="post" action="../SDK/epayapi.php" target="_blank">
                        <input type="text" name="partner" value="<?=$userrow['id']?>" hidden>
                        <input type="text" name="key" value="<?=$userrow['key']?>" hidden>
                        <input type="text" name="sign_type" value="<?=strtoupper('MD5')?>" hidden>
                        <input type="text" name="input_charset" value="<?=strtolower('utf-8')?>" hidden>
                        <input type="text" name="transport" value="<?='http'?>" hidden>
                        <input type="text" name="apiurl" value="<?='http://'.$_SERVER['HTTP_HOST'].'/'?>" hidden>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">订单号</label>
                            <div class="col-sm-9">
                                <input name="WIDout_trade_no" class="form-control" type="text" value="<?php echo date("YmdHis").mt_rand(100,999); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品名称</label>
                            <div class="col-sm-9">
                                <input name="WIDsubject" class="form-control" value="测试商品" type="text" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品金额</label>
                            <div class="col-sm-9">
                                <input name="WIDtotal_fee" class="form-control" value="0.01" type="text" required="required">
                            </div>
                        </div>
                            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                <div class="btn-group" role="group">
                                    <button type="radio" name="type" value="alipay" class="btn btn-primary">支付宝</button>
                                </div>
                                <div class="btn-group" role="group">
                                    <button type="radio" name="type" value="qqpay" class="btn btn-success">QQ</button>
                                </div>
                                <div class="btn-group" role="group">
                                    <button type="radio" name="type" value="wxpay" class="btn btn-info">微信</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
        </div>