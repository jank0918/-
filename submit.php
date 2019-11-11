<?php

//decode by http://www.wuaishare.cn/
if ($_SERVER['HTTP_USER_AGENT'] == 'Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 6.1)') {
	exit;
}
if (isset($_GET['pid'])) {
	$queryArr = $_GET;
	$is_defend = true;
} elseif (isset($_POST['pid'])) {
	$queryArr = $_POST;
} else {
	@header('Content-Type: text/html; charset=UTF-8');
	exit('你还未配置支付接口商户！');
}
require './includes/common.php';
@header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>正在为您跳转到支付页面，请稍候...</title>
    <style type="text/css">
        body {margin:0;padding:0;}
        p {position:absolute;
            left:50%;top:50%;
            width:330px;height:30px;
            margin:-35px 0 0 -160px;
            padding:20px;font:bold 14px/30px "宋体", Arial;
            background:#f9fafc url(../images/loading.gif) no-repeat 20px 26px;
            text-indent:22px;border:1px solid #c5d0dc;}
        #waiting {font-family:Arial;}
    </style>
<script>
function open_without_referrer(link){
document.body.appendChild(document.createElement('iframe')).src='javascript:"<script>top.location.replace(\''+link+'\')<\/script>"';
}
</script>
</head>
<body>
<?php 
$prestr = createLinkstring(argSort(paraFilter($queryArr)));
$pid = intval($queryArr['pid']);
if (empty($pid)) {
	sysmsg('PID不存在');
}
$userrow = $DB->query("SELECT * FROM pay_user WHERE id='{$pid}' limit 1")->fetch();
if (!md5Verify($prestr, $queryArr['sign'], $userrow['key'])) {
	sysmsg('签名校验失败，请返回重试！');
}
if ($userrow['active'] == 0) {
	sysmsg('商户已封禁，无法支付！');
}
$type = daddslashes($queryArr['type']);
$out_trade_no = daddslashes($queryArr['out_trade_no']);
$notify_url = strip_tags(daddslashes($queryArr['notify_url']));
$return_url = strip_tags(daddslashes($queryArr['return_url']));
$name = strip_tags(daddslashes($queryArr['name']));
$money = daddslashes($queryArr['money']);
$sitename = urlencode(base64_encode(daddslashes($queryArr['sitename'])));
if (empty($out_trade_no)) {
	sysmsg('订单号(out_trade_no)不能为空');
}
if (empty($notify_url)) {
	sysmsg('通知地址(notify_url)不能为空');
}
if (empty($return_url)) {
	sysmsg('回调地址(return_url)不能为空');
}
if (empty($name)) {
	sysmsg('商品名称(name)不能为空');
}
if (empty($money)) {
	sysmsg('金额(money)不能为空');
}
if ($money <= 0 || !is_numeric($money)) {
	sysmsg('金额不合法');
}
if($money>1000)sysmsg('超出最大限额');
if (!preg_match('/^[a-zA-Z0-9.\_\-|]+$/', $out_trade_no)) {
	sysmsg('订单号(out_trade_no)格式不正确');
}
$ljarr = explode("、", $conf['goods_lj']);
foreach ($ljarr as $k => $v) {
	if (strexists($name, $v)) {
		sysmsg($conf['goods_ljtis']);
		exit;
	}
}
$trade_no = date("YmdHis") . rand(11111, 99999);
$domain = getdomain($notify_url);
if (!$DB->query("insert into `pay_order` (`trade_no`,`out_trade_no`,`notify_url`,`return_url`,`type`,`pid`,`addtime`,`name`,`money`,`domain`,`ip`,`status`) values ('" . $trade_no . "','" . $out_trade_no . "','" . $notify_url . "','" . $return_url . "','" . $type . "','" . $pid . "','" . $date . "','" . $name . "','" . $money . "','" . $domain . "','" . $clientip . "','0')")) {
	exit('创建订单失败，请返回重试！');
}
if ($type == 'alipay') {
	if ($conf['alipay_api'] == 4) {
		exit($conf['ali_close_info']);
	} elseif ($conf['alipay_api'] == 3) {
		echo "<script>window.location.href='./msubmit.php?trade_no={$trade_no}&type={$type}&name={$name}&money={$money}&sitename={$sitename}';</script>";
	} elseif ($conf['alipay_api'] == 2) {
		echo "<script>window.location.href='./epay.php?trade_no={$trade_no}&type={$type}&name={$name}&money={$money}&sitename={$sitename}';</script>";
	} elseif ($conf['alipay_api'] == 1) {
		// echo "<script>window.location.href='./alipay.php?trade_no={$trade_no}&sitename={$sitename}';</script>";
		// exit;
		require_once SYSTEM_ROOT . "alipay/alipay.config.php";
		require_once SYSTEM_ROOT . "alipay/alipay_submit.class.php";
      	if($conf['pay_useallpay']==1){//启动多接口
          	$srrow=$DB->query("SELECT * FROM pay_aliconfig WHERE today_money<sum_money and isuse=1 order by rand() limit 1")->fetch();//取出一个支付接口
          	if($srrow){
          	$alipay_config['partner']=$srrow['ali_api_partner'];
          	$alipay_config['seller_email']=$srrow['ali_api_seller_email'];
            $alipay_config['key']=$srrow['ali_api_key'];
            $DB->exec("update  `pay_order` set `jkid`={$srrow['id']} WHERE trade_no='{$trade_no}'");//设置接口id
			//partner seller_email key
            }
		}
		if (checkmobile() == true) {
			$alipay_service = "alipay.wap.create.direct.pay.by.user";
		} else {
			$alipay_service = "create_direct_pay_by_user";
		}
		$name = 'onlinepay-' . time();
		$parameter = array(
			"service" => $alipay_service,
			"partner" => trim($alipay_config['partner']), 
			"seller_id" => trim($alipay_config['partner']), 
			"payment_type" => "1", 
			"notify_url" => 'http://' . $conf['local_domain'] . '/alipay_notify.php', 
			"return_url" => 'http://' . $_SERVER['HTTP_HOST'] . '/alipay_return.php', 
			"out_trade_no" => $trade_no, 
			"subject" => $name, 
			"total_fee" => $money, 
			"_input_charset" => strtolower('utf-8')
		);
		if (checkmobile() == true) {
			$parameter['app_pay'] = "Y";
		}
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter, "get", "正在跳转");
		echo $html_text;
	} elseif ($conf['alipay_api'] == 5) {
		echo "<script>window.location.href='./alipay.php?trade_no={$trade_no}&sitename={$sitename}';</script>";
		exit;
	} else {
		exit("本站还未配置有效的接口！");
	}
} elseif ($type == 'wxpay') {
    if ($conf['wxpay_api'] == 6){
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            echo "<script>window.location.href='./eshanghujspay.php?trade_no={$trade_no}&d=1';</script>";
        } elseif (checkmobile() == true) {
            echo "<script>window.location.href='./eshanghuapppay.php?trade_no={$trade_no}&sitename={$sitename}';</script>";
        } else {
            echo "<script>window.location.href='./eshanghu.php?trade_no={$trade_no}&sitename={$sitename}';</script>";
        }
    }
	if ($conf['wxpay_api'] == 4) {
		exit($conf['wx_close_info']);
	} elseif ($conf['wxpay_api'] == 3) {
		echo "<script>window.location.href='./msubmit.php?trade_no={$trade_no}&type={$type}&name={$name}&money={$money}&sitename={$sitename}';</script>";
	} elseif ($conf['wxpay_api'] == 2) {
		echo "<script>window.location.href='./epay.php?trade_no={$trade_no}&type={$type}&name={$name}&money={$money}&sitename={$sitename}';</script>";
	} elseif ($conf['wxpay_api'] == 1) {
          if($conf['pay_useallpay']==1){//启动多接口
            $DB->exec("update  `pay_order` set `jkid`={$srrow['id']} WHERE trade_no='{$trade_no}'");//设置接口id
            }
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
			echo "<script>window.location.href='./wxjspay.php?trade_no={$trade_no}&d=1';</script>";
		} elseif (checkmobile() == true) {
			echo "<script>window.location.href='./wxwappay.php?trade_no={$trade_no}&sitename={$sitename}';</script>";
		} else {
			echo "<script>window.location.href='./wxpay.php?trade_no={$trade_no}&sitename={$sitename}';</script>";
		}
	}
} elseif ($type == 'qqpay' || $type == 'tenpay') {
	$type = 'qqpay';
	if ($conf['qqpay_api'] == 4) {
		exit($conf['qq_close_info']);
	} elseif ($conf['qqpay_api'] == 3) {
		echo "<script>window.location.href='./msubmit.php?trade_no={$trade_no}&type={$type}&name={$name}&money={$money}&sitename={$sitename}';</script>";
	} elseif ($conf['qqpay_api'] == 2) {
		echo "<script>window.location.href='./epay.php?trade_no={$trade_no}&type={$type}&name={$name}&money={$money}&sitename={$sitename}';</script>";
	} elseif ($conf['qqpay_api'] == 1) {
                	if($conf['pay_useallpay']==1){//启动多接口
            $DB->exec("update  `pay_order` set `jkid`={$srrow['id']} WHERE trade_no='{$trade_no}'");//设置接口id
            }
		echo "<script>window.location.href='./qqpay.php?trade_no={$trade_no}&sitename={$sitename}';</script>";
	}
} else {
	echo "<script>window.location.href='./default.php?trade_no={$trade_no}&sitename={$sitename}';</script>";
}
?>
<p>正在为您跳转到支付页面，请稍候...</p>
</body>
</html>