<?php
include("../includes/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

$id = isset($_POST['id'])?intval($_POST['id']):exit('{"code":-1,"msg":"ID不能为空"}');

$row=$DB->query("SELECT * FROM pay_settle WHERE id='{$id}' limit 1")->fetch();

if(!$row)exit('{"code":-1,"msg":"记录不存在"}');

if($row['type']!=3)exit('{"code":-1,"msg":"该记录不是QQ结算"}');

if($row['transfer_status']==1)exit('{"code":0,"ret":2,"result":"QQ订单号:'.$row['transfer_result'].' 支付时间:'.$row['transfer_date'].'"}');

if (!is_numeric($row['account']) || strlen($row['account'])<6 || strlen($row['account'])>10) {
	$a = array();
	$a['code']=0;
    $a['ret']=0;
    $a['msg']='fail';
    $a['result']='QQ号格式错误';
    exit(json_encode($a));
}

require_once (SYSTEM_ROOT.'qqpay/qpayMchAPI.class.php');

$out_biz_no = date("Ymd").'000'.$id;

//入参
$params = array();
$params["input_charset"] = 'UTF-8';
$params["uin"] = $row['account'];
$params["out_trade_no"] = $out_biz_no;
$params["fee_type"] = "CNY";
$params["total_fee"] = $row['money']*100;
$params["memo"] = $conf['wxtransfer_desc']; //付款备注
$params["check_name"] = 'false'; //校验用户姓名，"FORCE_CHECK"校验实名
$params["re_user_name"] = ''; //收款用户真实姓名
$params["check_real_name"] = "0"; //校验用户是否实名
$params["op_user_id"] = QpayMchConf::OP_USERID;
$params["op_user_passwd"] = md5(QpayMchConf::OP_USERPWD);
$params["spbill_create_ip"] = $clientip;

//api调用
$qpayApi = new QpayMchAPI('https://api.qpay.qq.com/cgi-bin/epay/qpay_epay_b2c.cgi', true, 10);
$ret = $qpayApi->reqQpay($params);
$result = QpayMchUtil::xmlToArray($ret);

if ($result['return_code']=='SUCCESS' && $result['result_code']=='SUCCESS') {
	$data['code']=0;
	$data['ret']=1;
	$data['msg']='success';
	$data['result']='QQ订单号:'.$result["transaction_id"].' 交易时间:'.date('Y-m-d H:i:s',time());
	$DB->exec("update `pay_settle` set `transfer_status`='1',`transfer_result`='".$result["transaction_id"]."',`transfer_date`='".date('Y-m-d H:i:s',time())."' where `id`='$id'");
}elseif ($result['err_code']=='TRANSFER_FEE_LIMIT_ERROR' || $result['err_code']=='TRANSFER_FAIL' || $result['err_code']=='NOTENOUGH' || $result['err_code']=='APPID_OR_OPENID_ERR' || $result['err_code']=='TOTAL_FEE_OUT_OF_LIMIT' || $result['err_code']=='REALNAME_CHECK_ERROR' || $result['err_code']=='RE_USER_NAME_CHECK_ERROR') {
	$data['code']=0;
	$data['ret']=0;
	$data['msg']='fail';
	$data['result']='转账失败 ['.$result["err_code"].']'.$result["err_code_des"];
	$DB->exec("update `pay_settle` set `transfer_status`='2',`transfer_result`='".$data['result']."' where `id`='$id'");
}elseif(isset($result['result_code'])){
	$data['code']=-1;
	$data['result']='转账失败 ['.$result["err_code"].']'.$result["err_code_des"];
}else{
	$data['code']=-1;
	$data['result']='未知错误 '.$result["return_msg"];
}

echo json_encode($d);
