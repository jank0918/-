<?php
// +----------------------------------------------------------------------
// | Quotes [其实台下的观众就我一个，其实我也看出你有点不舍]
// +----------------------------------------------------------------------
// | Created ( PhpStorm )
// +----------------------------------------------------------------------
// | Author: Jonathan <2213147257@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019年04月09日
// +----------------------------------------------------------------------

/** 订单导出 **/

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
if ($_GET['mod'] == 'index'){
?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        导出订单
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="outorder.php?mod=out" class="form-horizontal" role="form" method="post">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">开始时间</label>
                        <div class="col-sm-10">
                            <input id="time1" type="text" name="time1" placeholder="这里是开始时间" class="form-control"/>
                        </div>
                    </div><br/>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">结束时间</label>
                        <div class="col-sm-10">
                            <input id="time2" type="text" name="time2" placeholder="这里是结束时间" class="form-control"/>
                        </div>
                    </div><br/>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="导出" class="btn btn-primary form-control"/><br/>
                        </div>
                    </div>
                </form>
                </div>
            </div>
<script>
laydate.render({
    elem:'#time1'
});
laydate.render({
    elem:'#time2'
});
</script>
<?php }elseif ($_GET['mod'] == 'out') {
function check_order_status($status){
    if ($status == 0){
        $msg = '未完成';
    }else{
        $msg = '已完成';
    }
    return $msg;
}
    $time1 = daddslashes($_POST['time1'] . ' 00:00:00');
    $time2 = daddslashes($_POST['time2'] . ' 00:00:00');
    if (!empty($time1) && !empty($time2)) {
        $filename = '../excel/order-'.date("Y-m-d").'-'.rand(10000,99999).'.xlsx';
        $download = "<a class='btn btn-primary' href='{$filename}'>下载</a>";
        $ex = new PHPExcel();
        $ex->createSheet();
        $ex->setActiveSheetIndex(0);
        $objSheet = $ex->getActiveSheet();
        $objSheet->setTitle('订单列表');
        $data = $DB->query("SELECT * FROM `pay_order` WHERE `endtime` > '$time1' AND `endtime` < '$time2'");
        $objSheet->setCellValue('A1', "订单号")->setCellValue('B1', "商户号")->setCellValue('C1', "商品名称")->setCellValue('D1', "金额")->setCellValue('E1', "支付方式")->setCellValue('F1', '创建时间')->setCellValue('G1', "完成时间")->setCellValue('H1', '订单状态');

        $l = 2;
        while ($row = $data->fetch()) {
            $objSheet->setCellValue('A' . $l, $row['trade_no'])->setCellValue('B' . $l, $pid)->setCellValue('C' . $l, $row['name'])->setCellValue('D' . $l, $row['money'])->setCellValue('E' . $l, $row['type'])->setCellValue('F' . $l, $row['addtime'])->setCellValue('G' . $l, $row['endtime'])->setCellValue('H' . $l, check_order_status($row['status']));
            $l++;
        }
        $objWriter = PHPExcel_IOFactory::createWriter($ex, 'Excel2007');
        $objWriter->save($filename);
        if (file_exists($filename)) {
            ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        导出订单
                    </h3>
                </div>
                <div class="panel-body">
                    导出成功！一共导出<?=$l-2?>个订单<hr />
                    <?=$download?>
                </div>
            </div>
            <?php
        } else {
            showmsg('导出失败，未知错误');
        }
    } else {
        showmsg('导出失败，请填写开是时间和结束时间！');
    }
}
?>