<?php
//error_reporting(E_ALL); ini_set("display_errors", 1);
error_reporting(0);
define('SYSTEM_ROOT', dirname(__FILE__).'/');
define('ROOT', dirname(SYSTEM_ROOT).'/');
date_default_timezone_set('Asia/Shanghai');
$date = date("Y-m-d H:i:s");

session_start();

$scriptpath=str_replace('\\','/',$_SERVER['SCRIPT_NAME']);
$sitepath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$siteurl = ($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$sitepath.'/';

if(is_file(SYSTEM_ROOT.'360safe/360webscan.php')){//360网站卫士
    require_once(SYSTEM_ROOT.'360safe/360webscan.php');
}

include_once(SYSTEM_ROOT."security.php");

require SYSTEM_ROOT.'config.php';

if(!defined('SQLITE') && (!$dbconfig['user']||!$dbconfig['pwd']||!$dbconfig['dbname']))//检测安装
{
header('Content-type:text/html;charset=utf-8');
echo '你还没安装！<a href="install/">点此安装</a>';
exit();
}

try {
    $DB = new PDO("mysql:host={$dbconfig['host']};dbname={$dbconfig['dbname']};port={$dbconfig['port']}",$dbconfig['user'],$dbconfig['pwd']);
}catch(Exception $e){
    exit('链接数据库失败:'.$e->getMessage());
}
$DB->exec("set names utf8");

$rs=$DB->query("select * from pay_config");
while($row=$rs->fetch()){ 
	$conf[$row['k']]=$row['v'];
}
if($conf['wxpay_api']==1){
    if($conf['pay_useallpay']==1){//启动多接口
    $srrow=$DB->query("SELECT * FROM pay_wxconfig WHERE today_money<sum_money and isuse=1 order by rand() limit 1")->fetch();//取出一个支付接口
    if($srrow){
    define('WX_API_APPID',  $srrow['wx_api_appid']);
    define('WX_API_MCHID',  $srrow['wx_api_mchid']);
    define('WX_API_KEY',  $srrow['wx_api_key']);
    define('WX_API_APPSECRET',  $srrow['wx_api_appsecret']);
			//partner seller_email key
            }
		}else{
    define('WX_API_APPID',  $conf['wx_api_appid']);
    define('WX_API_MCHID',  $conf['wx_api_mchid']);
    define('WX_API_KEY',  $conf['wx_api_key']);
    define('WX_API_APPSECRET',  $conf['wx_api_appsecret']);
            }
}

if($conf['qqpay_api']==1){
    if($conf['pay_useallpay']==1){//启动多接口
    $srrow=$DB->query("SELECT * FROM pay_qqconfig WHERE today_money<sum_money and isuse=1 order by rand() limit 1")->fetch();//取出一个支付接口
    if($srrow){
    define('QQ_API_MCH_ID',  $srrow['qq_api_mchid']);
    define('QQ_API_MCH_KEY',  $srrow['qq_api_mchkey']);
			//partner seller_email key
            }
		}else{
    define('QQ_API_MCH_ID',  $conf['qq_api_mchid']);
    define('QQ_API_MCH_KEY',  $conf['qq_api_mchkey']);
            }
}

/*
foreach ($conf as $k => $v){
    //echo $k."---".$v;
      $DB->query("insert into pay_config set `k`='{$k}',`v`='{$v}' on duplicate key update `v`='{$v}'");
}
exit();
*/
if(!$conf['local_domain'])$conf['local_domain']=$_SERVER['HTTP_HOST'];
$password_hash='!@#%!s!0';
require_once(SYSTEM_ROOT."alipay/alipay_core.function.php");
require_once(SYSTEM_ROOT."alipay/alipay_md5.function.php");
include_once(SYSTEM_ROOT."function.php");
include_once(SYSTEM_ROOT."member.php");

?>