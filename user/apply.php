<?php
include("../includes/common.php");
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$title='申请提现';
include './head.php';
?>
<?php

if($conf['settle_open']==0)exit('未开启申请提现');

$today=date("Y-m-d").' 00:00:00';
$notday=date("Y-m-d H:i:s");
$rs=$DB->query("SELECT * from pay_order where pid={$pid} and status=1 and endtime>='$today'");
$order_today=0;
while($row = $rs->fetch())
{
	$order_today+=$row['money'];
}
if($userrow['level']==1)
  $rat=$conf['money_rate'];
if($userrow['level']==2)
  $rat=$conf['money_rate_vip'];
if($userrow['level']==3)
  $rat=$conf['money_rate_svip'];
$enable_money=round($userrow['money']-$order_today*$rat/100,2);
$emoney=$enable_money*$rat/100;
$rmoney=$enable_money*(1-$rat/100);
if(isset($_GET['act']) && $_GET['act']=='do'){
	if($_POST['submit']=='申请提现'){
		if($userrow['apply']==1){
			exit("<script language='javascript'>alert('你今天已经申请过提现，请勿重复申请！');history.go(-1);</script>");
		}
		if($enable_money<$conf['settle_money']){
			exit("<script language='javascript'>alert('可提现余额不足！');history.go(-1);</script>");
		}
		if($userrow['type']==2){
			exit("<script language='javascript'>alert('您的商户出现异常，无法提现');history.go(-1);</script>");
		}
		$sqs=$DB->exec("update `pay_user` set `apply` ='1' where `id`='$pid'");//限制一日一次提现
        if($userrow['level']>=3 && $userrow['settle_id']==1){//钻石会员级别进行快速提现
          	$ins=$DB->exec("INSERT INTO `pay_settle`(`pid`, `batch`, `type`, `username`, `account`, `money`, `fee`, `status`, `transfer_status`,`time`) VALUES ({$pid},'T0_tixian',1,'{$userrow['username']}','{$userrow['account']}',{$emoney},{$rmoney},0,0,'{$notday}')");
          	if($ins){
              	$retid=$DB->query("select max(id) from `pay_settle` where pid={$pid}")->fetch();
              	//exit("<script language='javascript'>alert('{$retid[0]}');history.go(-1);</script>");
              	$datta=array(
                	'id'=>$retid[0],
                  	'pvkey'=>Alipay_PrivateKey
                );
              	//$_SESSION['privatekey']=Alipay_PrivateKey;
              	//echo "<script>window.location.href='./transfer_do_T0.php?id={$retid[0]}';</script>";
              	if(!$DB->exec("update `pay_user` set `money` =0 where `id`='$pid'"))
                  exit("<script language='javascript'>alert('提现失败！返回信息：用户信息设置失败！');history.go(-1);</script>");
              	
              	$uri='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
              	//print_r(dirname($uri)."/transfer_do_T0.php");
            	$mysub= spost(dirname($uri)."/transfer_do_T0.php",$datta);//提现信息提交
              	$ret=json_decode($mysub,true);
              	if($ret['code']==0 && $ret['msg']=='success'){
                  $DB->exec("update `pay_settle` set `status` =1 where `id`='{$retid[0]}'");
                  $DB->exec("update `pay_user` set `apply` ='0' where `id`='$pid'");//钻石会员无限次提现
                  exit("<script language='javascript'>alert('提现成功！返回信息：{$ret['result']}');history.go(-1);</script>");
                }
              	else{
                  $DB->exec("update `pay_user` set `money` ={$userrow['money']} where `id`='$pid'");
                  $DB->exec("update `pay_user` set `apply` ='0' where `id`='$pid'");//钻石会员无限次提现
                  exit("<script language='javascript'>alert('提现失败！错误信息：{$ret['msg']}{$ret['result']}');history.go(-1);</script>");
                }
            }else{
              	$DB->exec("update `pay_user` set `apply` ='0' where `id`='$pid'");//钻石会员无限次提现
            	exit("<script language='javascript'>alert('尊敬的钻石会员，本次T0快速提现失败！请联系管理员！');history.go(-1);</script>");
            }
			//exit("<script language='javascript'>alert('您不是尊贵的钻石vip会员，无法享受T+0快速提现，请找管理员升级！');history.go(-1);</script>");
		}
		exit("<script language='javascript'>alert('申请提现成功！T0结算仅限于支付宝结算方式！');history.go(-1);</script>");
	}
}


function spost($uri,$data){
	$ch = curl_init ();
// print_r($ch);
curl_setopt ( $ch, CURLOPT_URL, $uri);
curl_setopt ( $ch, CURLOPT_POST, 1);
curl_setopt ( $ch, CURLOPT_HEADER,0);
//curl_setopt ( $ch, CURLOPT_COOKIE,);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证证书
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt ( $ch, CURLOPT_POSTFIELDS,$data);
//print_r( $uri);
$return = curl_exec ($ch);
//print_r( curl_error($ch));
curl_close ($ch);
return $return;
}

?>
 <div id="content" class="app-content" role="main">
    <div class="app-content-body ">

<div class="bg-light lter b-b wrapper-md hidden-print">
  <h1 class="m-n font-thin h3">申请提现</h1>
</div>
<div class="wrapper-md control">
<?php if(isset($msg)){?>
<div class="alert alert-info">
	<?php echo $msg?>
</div>
<?php }?>
	<div class="panel panel-default">
		<div class="panel-heading font-bold">
			申请提现
		</div>
		<div class="panel-body">
			<form class="form-horizontal devform" action="./apply.php?act=do" method="post">
				<div class="form-group">
					<label class="col-sm-2 control-label">支付宝账号</label>
					<div class="col-sm-9">
						<input class="form-control" type="text" value="<?php echo $userrow['account']?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">支付宝姓名</label>
					<div class="col-sm-9">
						<input class="form-control" type="text" value="<?php echo $userrow['username']?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">当前余额</label>
					<div class="col-sm-9">
						<input class="form-control" type="text" value="<?php echo $userrow['money']?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">可提现余额</label>
					<div class="col-sm-9">
						<input class="form-control" type="text" name="tmoney" value="<?php echo $enable_money?>" disabled>
					</div>
				</div>
				<div class="form-group">
				  <div class="col-sm-offset-2 col-sm-4"><input type="submit" name="submit" value="申请提现" class="btn btn-primary form-control"/><br/>
				 </div>
				<div class="line line-dashed b-b line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-2"></label>
					<div class="col-sm-6">
					<h4><span class="glyphicon glyphicon-info-sign"></span>注意事项</h4>
						当前最低提现金额为<b><?php echo $conf['settle_money']?></b>元<br/>
						申请提现后，你的款项将在T+1工作日内下发到指定账户内，钻石会员提现秒到账(支付宝结算)
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
    </div>
  </div>

<?php include 'foot.php';?>