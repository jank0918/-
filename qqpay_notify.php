<?php

//---------------------------------------------------------
//财付通即时到帐支付后台回调示例，商户按照此文档进行开发即可
//---------------------------------------------------------
require_once('./includes/commons.php');
//require_once(SYSTEM_ROOT.'qqpay/qpayNotify.class.php');
@header('Content-Type: text/html; charset=UTF-8');
$srow=$DB->query("SELECT * FROM pay_order WHERE trade_no='{$_POST['out_trade_no']}' limit 1 for update")->fetch();
if($srow['jkid']>0)
{
          	$srrow=$DB->query("SELECT * FROM pay_qqconfig WHERE id={$srow['jkid']}")->fetch();//取出一个支付接口
          	define('QQ_API_MCH_ID',  $srrow['qq_api_mchid']);
    		define('QQ_API_MCH_KEY',  $srrow['qq_api_mchkey']);
            
}else{
    define('QQ_API_MCH_ID',  $conf['qq_api_mchid']);
    define('QQ_API_MCH_KEY',  $conf['qq_api_mchkey']);
            }
require_once(SYSTEM_ROOT.'qqpay/qpayNotify.class.php');
$qpayNotify = new QpayNotify();
$result = $qpayNotify->getParams();
//判断签名
if($qpayNotify->verifySign()) {

//判断签名及结果（即时到帐）
	if($result['trade_state'] == "SUCCESS") {
		//商户订单号
		$out_trade_no = $result['out_trade_no'];
		//QQ钱包订单号
		$transaction_id = $result['transaction_id'];
		//金额,以分为单位
		$total_fee = $result['total_fee'];
		//币种
		$fee_type = $result['fee_type'];

		//------------------------------
		//处理业务开始
		//------------------------------
		$srow=$DB->query("SELECT * FROM pay_order WHERE trade_no='{$out_trade_no}' limit 1")->fetch();
		
		if($srow['status']==0){
            if($srow['jkid']!=0)
      		$DB->query("update `pay_qqconfig` set `today_money` =`today_money`+{$srow['money']} where `id`={$srow['jkid']}");//增加接口支付额度
      	
		$DB->query("update `pay_order` set `status` ='1',`endtime` ='$date' where `trade_no`='$out_trade_no'");
		processOrder($srow);
		}
		//------------------------------
		//处理业务完毕
		//------------------------------
		echo "<xml>
<return_code>SUCCESS</return_code>
</xml>";
	} else {
		echo "<xml>
<return_code>FAIL</return_code>
</xml>";
	}

} else {
	//回调签名错误
	echo "<xml>
<return_code>FAIL</return_code>
<return_msg>签名失败</return_msg>
</xml>";
} 

?>