<?php 
require_once('../includes/common.php');
$alipay_config['partner'] = $conf['reg_pid'];
$alipay_config['key'] = $DB->query("SELECT `key` FROM `pay_user` WHERE `id`='{$conf['reg_pid']}' limit 1")->fetchColumn();
require_once("./epay_notify.class.php");

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {//验证成功
	//商户订单号
	$out_trade_no = $_GET['out_trade_no'];

	//支付宝交易号
	$trade_no = $_GET['trade_no'];

	//交易状态
	$trade_status = $_GET['trade_status'];

    if ($_GET['trade_status'] == 'TRADE_SUCCESS') {
		//付款完成后，支付宝系统发送该交易状态通知
			//$DB->query("update `pay_user` set `level`=`level`+1 where id={$srow['buyer']}");
		    $sssrow=$DB->query("select * from `pay_order` where `trade_no`='{$trade_no}' and (`buyer`<1000 or `buyer` is null)")->fetch();
      		$levid=substr($sssrow['name'],0,4);//取出pid
      		$levidb=substr($sssrow['name'],0,5);//取出pid
      		$userrow=$DB->query("select * from `pay_user` where `id`='{$levid}'")->fetch();
      		
			//判断会员以及续费金额
			$tttt=time();
  			$addtime=($tttt+2592000).'';//从现在起加30天
  			$addtime2=($tttt+2592000*12).'';
			if($userrow['level_endtime']>$tttt){//取得当前时间,若未过期则执行
				if($userrow['level']==2){//黄金升级钻石所需要的金额
	 				 $Days = round(($userrow['level_endtime']-$tttt)/3600/24);
     				 $ssum=$conf['level_svip']/30*$Days;

      				//$ssum-=$ssum*$Days;//vip与svip的总价减去剩余的可用时间花费，得到余额，进行月费升级
   				     $conf['level_svip']=round($ssum,2);
                  	 $addtime=($tttt+($Days*24*60*60)).'';
 				   }
			}
  //结束/
      		if($userrow['level']==1 && $sssrow['money']==$conf['level_vip_year'] && $levidb==($userrow['id'].'b'))
            {
            				$DB->exec("update `pay_user` set `level`=`level`+1,`level_endtime`='{$addtime2}' where id={$levid}");
      						$DB->exec("update `pay_order` set `buyer`='{$levid}' where `trade_no`='{$trade_no}'");
            }elseif(($userrow['level']==1 && $sssrow['money']==$conf['level_vip']) || ($userrow['level']==2 && $sssrow['money']==$conf['level_svip'])){
              
							$DB->exec("update `pay_user` set `level`=`level`+1,`level_endtime`='{$addtime}' where id={$levid}");
      						$DB->exec("update `pay_order` set `buyer`='{$levid}' where `trade_no`='{$trade_no}'");
            
            }
     //print_r($userrow);
      //echo $levid;
	echo "success";
}
}
else {
    //验证失败
    echo "fail";
}

?>
