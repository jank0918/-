<?php
require_once('./includes/commons.php');

$srow=$DB->query("SELECT * FROM pay_order WHERE trade_no='{$_POST['out_trade_no']}' limit 1 for update")->fetch();
if($srow['jkid']>0)
{
          	$srrow=$DB->query("SELECT * FROM pay_wxconfig WHERE id={$srow['jkid']}")->fetch();//取出一个支付接口
    		define('WX_API_APPID',  $srrow['wx_api_appid']);
    		define('WX_API_MCHID',  $srrow['wx_api_mchid']);
    		define('WX_API_KEY',  $srrow['wx_api_key']);
    		define('WX_API_APPSECRET',  $srrow['wx_api_appsecret']);
            
}else{
    define('WX_API_APPID',  $conf['wx_api_appid']);
    define('WX_API_MCHID',  $conf['wx_api_mchid']);
    define('WX_API_KEY',  $conf['wx_api_key']);
    define('WX_API_APPSECRET',  $conf['wx_api_appsecret']);
}

require_once SYSTEM_ROOT."wxpay/WxPay.Api.php";
require_once SYSTEM_ROOT."wxpay/WxPay.Notify.php";

//初始化日志
//$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
//$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		//Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		//file_put_contents('log.txt',"call back:" . json_encode($data));
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		global $DB,$date,$conf;
		if($data['return_code']=='SUCCESS'){
			if($data['result_code']=='SUCCESS'){
				$srow=$DB->query("SELECT * FROM pay_order WHERE trade_no='{$data['out_trade_no']}' limit 1 for update")->fetch();

				if($srow['status']==0){
                    if($srow['jkid']!=0)
      					$DB->query("update `pay_wxconfig` set `today_money` =`today_money`+{$srow['money']} where `id`={$srow['jkid']}");//增加接口支付额度
					$DB->query("update `pay_order` set `status` ='1',`endtime` ='$date' where `trade_no`='{$data['out_trade_no']}'");
					processOrder($srow);
					return true;
				}else{
					$msg='该订单已经处理';
					return true;
				}
			}else{
				$msg='['.$data['err_code'].']'.$data['err_code_des'];
				return false;
			}
		}else{
			$msg='['.$data['return_code'].']'.$data['return_msg'];
			return false;
		}
		return true;
	}
}

//Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
