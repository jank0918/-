<?php
/**
 * 商户列表
**/
include("../includes/common.php");
$title='商户列表';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
include 'nav.php';
?>
 
  <div class="container" style="padding-top:70px;">
    <div class="col-md-12 center-block" style="float: none;">
<?php

$my=isset($_GET['my'])?$_GET['my']:null;
if($my=='edituse'){
$id=$_GET['id'];
$api=$_GET['api'];
$rows=$DB->query("select * from pay_{$api}config where id={$id} limit 1")->fetch();
if(!$rows)
	showmsg('当前记录不存在！',3);


if($rows['isuse']==0)
  $sql="UPDATE `pay_{$api}config` SET `isuse`=1 WHERE `id`={$id}";
else
  $sql="UPDATE `pay_{$api}config` SET `isuse`=0 WHERE `id`={$id}";

$DB->exec($sql);

}
if($my=='cleanall'){
$api=$_GET['api'];

$sql="UPDATE `pay_{$api}config` SET `today_money`=0 WHERE isuse=1";

$DB->exec($sql);
}
if($my=='allpay'){

if($conf['pay_useallpay']==1){
  $conf['pay_useallpay']=0;
  $sql="UPDATE `pay_config` SET `v`='0' WHERE `k`='pay_useallpay'";}
else{
  $conf['pay_useallpay']=1;
  $sql="UPDATE `pay_config` SET `v`='1' WHERE `k`='pay_useallpay'";
}
$DB->exec($sql);


}
if($my=='add')
{
echo '<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">添加接口</h3></div>';
echo '<div class="panel-body">';
echo '<form action="./paysetall.php?my=add_submit" method="POST">
<div class="form-group">
<label>支付方式:</label><br>
<select  class="form-control" name="pay_api" onchange="api_qh(this.value)">';
echo '<option value="1" selected >支付宝官方</option>';
echo'<option value="2"  >微信官方</option>';
echo'<option value="3"  >QQ官方</option>';
echo'</select>
</div>

';
?>
        <!--对接支付宝官方信息-->
        <div id="ali_gf_info" style="" >
	<div class="form-group">
	  <label class="col-sm-3 control-label">合作身份者id:</label>
	  <div class="col-sm-9">
              <input type="text" name="ali_api_partner" value="" class="form-control"/>
              <small> * 合作身份者id，以2088开头的16位纯数字</small>
          </div>
	</div><br/>
        <div class="form-group">
	  <label class="col-sm-3 control-label">收款支付宝账号:</label>
	  <div class="col-sm-9">
              <input type="text" name="ali_api_seller_email" value="" class="form-control"/>
              <small> * 收款支付宝账号</small>
          </div>
	</div><br/>
         <div class="form-group">
	  <label class="col-sm-3 control-label">安全检验码:</label>
	  <div class="col-sm-9">
              <input type="text" name="ali_api_key" value="" class="form-control"/>
              <small> * 安全检验码，以数字和字母组成的32位字符</small>
          </div>
	</div><br/>
          <div class="form-group">
	  <label class="col-sm-3 control-label">可用额度:</label>
	  <div class="col-sm-9">
              <input type="text" name="ali_sum_money" value="" class="form-control"/>
              <small> * 设置可使用的额度</small>
          </div>
	</div><br/>
        </div>   <!-- END 对接支付宝官方信息-->
        <!--对接微信官方信息-->
        <div id="wx_gf_info" style="display: none;" >
	<div class="form-group">
	  <label class="col-sm-3 control-label">APPID:</label>
	  <div class="col-sm-9">
              <input type="text" name="wx_api_appid" value="" class="form-control"/>
              <small> * 绑定支付的APPID（必须配置，开户邮件中可查看）</small>
          </div>
	</div><br/>
        <div class="form-group">
	  <label class="col-sm-3 control-label">MCHID:</label>
	  <div class="col-sm-9">
              <input type="text" name="wx_api_mchid" value="" class="form-control"/>
              <small> * 商户号（必须配置，开户邮件中可查看）</small>
          </div>
	</div><br/>
         <div class="form-group">
	  <label class="col-sm-3 control-label">商户支付密钥:</label>
	  <div class="col-sm-9">
              <input type="text" name="wx_api_key" value="" class="form-control"/>
              <small> * 商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置） <br>设置地址：https://pay.weixin.qq.com/index.php/account/api_cert</small>
          </div>
	</div><br/>
     
          <div class="form-group">
	  <label class="col-sm-3 control-label">APPSECRET:</label>
	  <div class="col-sm-9">
              <input type="text" name="wx_api_appsecret" value="" class="form-control"/>
              <small> * 公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）<br>获取地址：https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=2005451881&lang=zh_CN</small>
          </div>
	</div><br/>
          <div class="form-group">
	  <label class="col-sm-3 control-label">可用额度:</label>
	  <div class="col-sm-9">
              <input type="text" name="wz_sum_money" value="" class="form-control"/>
              <small> * 设置可使用的额度</small>
          </div>
	</div><br/>
        </div>      
        <!-- END 对接微信官方信息-->
        <!--对接QQ官方信息-->
        <div id="qq_gf_info" style="display: none;" >
	<div class="form-group">
	  <label class="col-sm-3 control-label">MCH_ID:</label>
	  <div class="col-sm-9">
              <input type="text" name="qq_api_mchid" value="" class="form-control"/>
              <small> * QQ钱包商户号</small>
          </div>
	</div><br/>
        <div class="form-group">
	  <label class="col-sm-3 control-label">MCH_KEY:</label>
	  <div class="col-sm-9">
              <input type="text" name="qq_api_mchkey" value="" class="form-control"/>
              <small> * QQ钱包商户平台(http://qpay.qq.com/)获取</small>
          </div>
	</div><br/>
          <div class="form-group">
	  <label class="col-sm-3 control-label">可用额度:</label>
	  <div class="col-sm-9">
              <input type="text" name="qq_sum_money" value="" class="form-control"/>
              <small> * 设置可使用的额度</small>
          </div>
	</div><br/>
        </div>      
        <!-- END 对接QQ官方信息-->
      <?php
echo '<input type="submit" class="btn btn-primary btn-block"
value="确定添加"></form>';
echo '<br/><a href="./paysetall.php">>>返回接口列表</a>';
echo '</div></div>';
}
elseif($my=='edit')
{
$id=$_GET['id'];
$api=$_GET['api'];
$row=$DB->query("select * from pay_{$api}config where id='$id' limit 1")->fetch();
echo '<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">修改接口信息</h3></div>';
echo '<div class="panel-body">';
echo '<form action="./paysetall.php?my=edit_submit&api='.$api.'&id='.$id.'" method="POST">';
  if($api=='ali'){
  ?>
          <!--对接支付宝官方信息-->
    <div id="ali_gf_info" style="" >
	<div class="form-group">
	  <label class="col-sm-3 control-label">合作身份者id:</label>
	  <div class="col-sm-9">
              <input type="text" name="ali_api_partner" value="<?php echo $row['ali_api_partner'] ?>" class="form-control"/>
              <small> * 合作身份者id，以2088开头的16位纯数字</small>
          </div>
	</div><br/>
        <div class="form-group">
	  <label class="col-sm-3 control-label">收款支付宝账号:</label>
	  <div class="col-sm-9">
              <input type="text" name="ali_api_seller_email" value="<?php echo $row['ali_api_seller_email'] ?>" class="form-control"/>
              <small> * 收款支付宝账号</small>
          </div>
	</div><br/>
         <div class="form-group">
	  <label class="col-sm-3 control-label">安全检验码:</label>
	  <div class="col-sm-9">
              <input type="text" name="ali_api_key" value="<?php echo $row['ali_api_key'] ?>" class="form-control"/>
              <small> * 安全检验码，以数字和字母组成的32位字符</small>
          </div>
	</div>
          <div class="form-group">
	  <label class="col-sm-3 control-label">可用额度:</label>
	  <div class="col-sm-9">
              <input type="text" name="sum_money" value="<?php echo $row['sum_money'] ?>" class="form-control"/>
              <small> * 设置可使用的额度</small>
          </div>
	</div><br/>
        </div>   <!-- END 对接支付宝官方信息-->
      <?php }else if ($api=='wx'){ ?>
        <!--对接微信官方信息-->
        <div id="wx_gf_info" >
	<div class="form-group">
	  <label class="col-sm-3 control-label">APPID:</label>
	  <div class="col-sm-9">
              <input type="text" name="wx_api_appid" value="<?php echo $row['wx_api_appid'] ?>" class="form-control"/>
              <small> * 绑定支付的APPID（必须配置，开户邮件中可查看）</small>
          </div>
	</div><br/>
        <div class="form-group">
	  <label class="col-sm-3 control-label">MCHID:</label>
	  <div class="col-sm-9">
              <input type="text" name="wx_api_mchid" value="<?php echo $row['wx_api_mchid'] ?>" class="form-control"/>
              <small> * 商户号（必须配置，开户邮件中可查看）</small>
          </div>
	</div><br/>
         <div class="form-group">
	  <label class="col-sm-3 control-label">商户支付密钥:</label>
	  <div class="col-sm-9">
              <input type="text" name="wx_api_key" value="<?php echo $row['wx_api_key'] ?>" class="form-control"/>
              <small> * 商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置） <br>设置地址：https://pay.weixin.qq.com/index.php/account/api_cert</small>
          </div>
	</div><br/>
     
          <div class="form-group">
	  <label class="col-sm-3 control-label">APPSECRET:</label>
	  <div class="col-sm-9">
              <input type="text" name="wx_api_appsecret" value="<?php echo $row['wx_api_appsecret'] ?>" class="form-control"/>
              <small> * 公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）<br>获取地址：https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=2005451881&lang=zh_CN</small>
          </div>
	</div>
          <div class="form-group">
	  <label class="col-sm-3 control-label">可用额度:</label>
	  <div class="col-sm-9">
              <input type="text" name="sum_money" value="<?php echo $row['sum_money'] ?>" class="form-control"/>
              <small> * 设置可使用的额度</small>
          </div>
	</div><br/>
        </div>      
        <!-- END 对接微信官方信息-->
      <?php }else if ($api=='qq'){ ?>
        <!--对接QQ官方信息-->
        <div id="qq_gf_info" >
	<div class="form-group">
	  <label class="col-sm-3 control-label">MCH_ID:</label>
	  <div class="col-sm-9">
              <input type="text" name="qq_api_mchid" value="<?php echo $row['qq_api_mchid'] ?>" class="form-control"/>
              <small> * QQ钱包商户号</small>
          </div>
	</div><br/>
        <div class="form-group">
	  <label class="col-sm-3 control-label">MCH_KEY:</label>
	  <div class="col-sm-9">
              <input type="text" name="qq_api_mchkey" value="<?php echo $row['qq_api_mchkey'] ?>" class="form-control"/>
              <small> * QQ钱包商户平台(http://qpay.qq.com/)获取</small>
          </div>
	</div>
          <div class="form-group">
	  <label class="col-sm-3 control-label">可用额度:</label>
	  <div class="col-sm-9">
              <input type="text" name="sum_money" value="<?php echo $row['sum_money'] ?>" class="form-control"/>
              <small> * 设置可使用的额度</small>
          </div>
	</div><br/>
        </div>      
        <!-- END 对接QQ官方信息-->
      <?php
  }
echo '
<input type="submit" class="btn btn-primary btn-block" value="确定修改"></form>
';
echo '<br/><a href="./paysetall.php">>>返回接口列表</a>';
echo '</div></div>
<script>
var items = $("select[default]");
for (i = 0; i < items.length; i++) {
	$(items[i]).val($(items[i]).attr("default")||0);
}
</script>';
}
elseif($my=='add_submit')
{
$daat=array();
foreach ($_POST as $k=>$v){
	$daat[$k]=$v;
}
if($daat['pay_api']==1)
  $sql="INSERT INTO `pay_aliconfig`(`ali_api_partner`, `ali_api_seller_email`, `ali_api_key`,`sum_money`) VALUES ('{$daat['ali_api_partner']}','{$daat['ali_api_seller_email']}','{$daat['ali_api_key']}','{$daat['ali_sum_money']}')";
elseif($daat['pay_api']==2)
  $sql="INSERT INTO `pay_wxconfig`(`wx_api_appid`, `wx_api_mchid`, `wx_api_key`, `wx_api_appsecret`,`sum_money`) VALUES ('{$daat['wx_api_appid']}','{$daat['wx_api_mchid']}','{$daat['wx_api_key']}','{$daat['wx_api_appsecret']}','{$daat['wx_sum_money']}')";
elseif($daat['pay_api']==3)
  $sql="INSERT INTO `pay_qqconfig`(`qq_api_mchid`, `qq_api_mchkey`,`sum_money`) VALUES ('{$daat['qq_api_mchid']}','{$daat['qq_api_mchkey']}','{$daat['qq_sum_money']}')";

$sds=$DB->exec($sql);
if($sds){
	showmsg('添加接口成功！<br/><br/><a href="./paysetall.php">>>返回接口列表</a>',1);
}else
	showmsg('添加接口失败！<br/>错误信息：'.$DB->errorCode(),4);

}
elseif($my=='edit_submit')
{
$id=$_GET['id'];
$api=$_GET['api'];
$rows=$DB->query("select * from pay_{$api}config where id={$id} limit 1")->fetch();
if(!$rows)
	showmsg('当前记录不存在！',3);


$daat=array();
foreach ($_POST as $k=>$v){
	$daat[$k]=$v;
}
if($api=='ali')
  $sql="UPDATE `pay_aliconfig` SET `ali_api_partner`='{$daat['ali_api_partner']}',`ali_api_seller_email`='{$daat['ali_api_seller_email']}',`ali_api_key`='{$daat['ali_api_key']}',`sum_money`={$daat['sum_money']} WHERE `id`={$id}";
elseif($api=='wx')
  $sql="UPDATE `pay_wxconfig` SET `wx_api_appid`='{$daat['wx_api_appid']}',`wx_api_mchid`='{$daat['wx_api_mchid']}',`wx_api_key`='{$daat['wx_api_key']}',`wx_api_appsecret`='{$daat['wx_api_appsecret']}',`sum_money`={$daat['sum_money']} WHERE `id`={$id}";
elseif($api=='qq')
  $sql="UPDATE `pay_qqconfig` SET `qq_api_mchid`='{$daat['qq_api_mchid']}',`qq_api_mchkey`='{$daat['qq_api_mchkey']}',`sum_money`={$daat['sum_money']} WHERE `id`={$id}";


if($DB->exec($sql))
	showmsg('修改商户信息成功！<br/><br/><a href="./paysetall.php">>>返回商户列表</a>',1);
else
	showmsg('修改商户信息失败！'.$sql.$DB->errorCode(),4);

}
elseif($my=='delete')
{
$id=$_GET['id'];
$api=$_GET['api'];
$rows=$DB->query("select * from pay_{$api}config where id='$id' limit 1")->fetch();
if(!$rows)
	showmsg('当前记录不存在！',3);

$sql="DELETE FROM pay_{$api}config WHERE id='$id'";
if($DB->exec($sql))
	showmsg('删除商户成功！<br/><br/><a href="./paysetall.php">>>返回商户列表</a>',1);
else
	showmsg('删除商户失败！'.$DB->errorCode(),4);
}
else
{
    $choseapi = $_GET['api'];
    switch ($choseapi){
        case 'ali':
            echo '<form action="paysetall.php" method="GET" class="form-inline"><input type="hidden" name="my" value="search">
  <div class="form-group">
    <label>搜索</label>
	<select name="api"  class="form-control"><option value="ali">支付宝接口</option><option value="wx">微信接口</option><option value="qq">qq接口</option></select>
  </div>

  <button type="submit" class="btn btn-primary">切换</button>&nbsp;<a href="./paysetall.php?my=add" class="btn btn-success">添加接口</a>&nbsp;';
        break;
        case 'wx':
            echo '<form action="paysetall.php" method="GET" class="form-inline"><input type="hidden" name="my" value="search">
  <div class="form-group">
    <label>搜索</label>
	<select name="api" class="form-control"><option value="wx">微信接口</option><option value="ali">支付宝接口</option><option value="qq">qq接口</option></select>
  </div>

  <button type="submit" class="btn btn-primary">切换</button>&nbsp;<a href="./paysetall.php?my=add" class="btn btn-success">添加接口</a>&nbsp;';
        break;
        case 'qq':
            echo '<form action="paysetall.php" method="GET" class="form-inline"><input type="hidden" name="my" value="search">
  <div class="form-group">
  
    <label>搜索</label>
	<select name="api" class="form-control"><option value="qq">qq接口</option><option value="ali">支付宝接口</option><option value="wx">微信接口</option></select>
  </div>

  <button type="submit" class="btn btn-primary">切换</button>&nbsp;<a href="./paysetall.php?my=add" class="btn btn-success">添加接口</a>&nbsp;';
        break;
    }
  if($conf['pay_useallpay']==1)
    echo '<a href="./paysetall.php?my=allpay" class="btn btn-success">多支付通道已开启</a>';
  else
    echo '<a href="./paysetall.php?my=allpay" class="btn btn-danger">多支付通道已关闭</a>';
echo '&nbsp;<a href="./paysetall.php?my=cleanall&api=ali" class="btn btn-danger">清空支付宝额度</a>&nbsp;<a href="./paysetall.php?my=cleanall&api=wx" class="btn btn-danger">清空微信额度</a>&nbsp;<a href="./paysetall.php?my=cleanall&api=qq" class="btn btn-danger">清空QQ额度</a></form>';

if($my=='search') {
	$numrows=$DB->query("SELECT * from pay_{$_GET['api']}config WHERE 1")->rowCount();
	$con='包含 '.$_GET['value'].' 的共有 <b>'.$numrows.'</b> 个接口';
}else{
	$numrows=$DB->query("SELECT * from pay_aliconfig WHERE 1")->rowCount();
	$sql=" 1";
	$con='共有 <b>'.$numrows.'</b> 个接口';
}
echo $con;
if(!isset($_GET['api'])){
  $_GET['api']="ali";
  alicode();
}else{
	if($_GET['api']=="ali")
      alicode();
    else if($_GET['api']=="wx")
      wxcode();
    else if($_GET['api']=="qq")
      qqcode();
}
?>
<?php

?>
<?php

}
?>
<script>
function api_qh(val){
    var ali  = $("#ali_gf_info");
    var wx =  $("#wx_gf_info");
    var qq =  $("#qq_gf_info");
    if(val == 1){
       $(ali).show()
       $(wx).hide();
       $(qq).hide();
    }
    if(val == 2){
       $(ali).hide()
       $(wx).show();
       $(qq).hide();
    }
    if(val == 3){
       $(ali).hide()
       $(wx).hide();
       $(qq).show();
    }
}
    
</script>
    </div>
  </div>
<?php

function alicode(){
	   echo'   <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>Id</th><th>合作者身份id</th><th>收款支付宝账号</th><th>安全检验码</th><th>已用额度</th><th>操作</th></tr></thead>
          <tbody>';
  global $DB;
$rs=$DB->query("SELECT * FROM pay_aliconfig WHERE 1 order by id");

while($res = $rs->fetch())
{
echo '<tr><td><b>'.$res['id'].'</b></td><td>'.$res['ali_api_partner'].'</td><td>'.$res['ali_api_seller_email'].'</td><td>'.$res['ali_api_key'].'</td><td>'.$res['today_money'].'/'.$res['sum_money'].'</td><td>';
if($res['isuse']==0)
	echo '<a href="./paysetall.php?my=edituse&api='.$_GET['api'].'&id='.$res['id'].'" class="btn btn-xs btn-danger">已关闭</a>';
else
  echo '<a href="./paysetall.php?my=edituse&api='.$_GET['api'].'&id='.$res['id'].'" class="btn btn-xs btn-success">已开启</a>';
echo '<a href="./paysetall.php?my=edit&api='.$_GET['api'].'&id='.$res['id'].'" class="btn btn-xs btn-info">编辑</a><a href="./paysetall.php?my=delete&api='.$_GET['api'].'&id='.$res['id'].'" class="btn btn-xs btn-danger" onclick="return confirm(\'你确实要删除此接口吗？\');">删除</a></td></tr>';
}

 echo'         </tbody>
        </table>
      </div>';
}
function wxcode(){
    global $DB;
	   echo'   <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>Id</th><th>APPid</th><th>商户号</th><th>商户支付密钥</th><th>公众账号APPSECRET</th><th>已用额度</th><th>操作</th></tr></thead>
          <tbody>';
$rs=$DB->query("SELECT * FROM pay_wxconfig WHERE 1 order by id");
while($res = $rs->fetch())
{
echo '<tr><td><b>'.$res['id'].'</b></td><td>'.$res['wx_api_appid'].'</td><td>'.$res['wx_api_mchid'].'</td><td>'.$res['wx_api_key'].'</td><td>'.$res['wx_api_appsecret'].'</td><td>'.$res['today_money'].'/'.$res['sum_money'].'</td><td>';
if($res['isuse']==0)
	echo '<a href="./paysetall.php?my=edituse&api='.$_GET['api'].'&id='.$res['id'].'" class="btn btn-xs btn-danger">已关闭</a>';
else
  echo '<a href="./paysetall.php?my=edituse&api='.$_GET['api'].'&id='.$res['id'].'" class="btn btn-xs btn-success">已开启</a>';
echo '<a href="./paysetall.php?my=edit&api='.$_GET['api'].'&id='.$res['id'].'" class="btn btn-xs btn-info">编辑</a><a href="./paysetall.php?my=delete&api='.$_GET['api'].'&id='.$res['id'].'" class="btn btn-xs btn-danger" onclick="return confirm(\'你确实要删除此接口吗？\');">删除</a></td></tr>';
}

 echo'         </tbody>
        </table>
      </div>';
}
function qqcode(){
    global $DB;
	   echo'   <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>Id</th><th>QQ钱包商户号</th><th>商户KEY</th><th>已用额度</th><th>操作</th></tr></thead>
          <tbody>';
$rs=$DB->query("SELECT * FROM pay_qqconfig WHERE 1 order by id");
while($res = $rs->fetch())
{
echo '<tr><td><b>'.$res['id'].'</b></td><td>'.$res['qq_api_mchid'].'</td><td>'.$res['qq_api_mchkey'].'</td><td>'.$res['today_money'].'/'.$res['sum_money'].'</td><td>';
if($res['isuse']==0)
	echo '<a href="./paysetall.php?my=edituse&api='.$_GET['api'].'&id='.$res['id'].'" class="btn btn-xs btn-danger">已关闭</a>';
else
  echo '<a href="./paysetall.php?my=edituse&api='.$_GET['api'].'&id='.$res['id'].'" class="btn btn-xs btn-success">已开启</a>';
echo '<a href="./paysetall.php?my=edit&api='.$_GET['api'].'&id='.$res['id'].'" class="btn btn-xs btn-info">编辑</a><a href="./paysetall.php?my=delete&api='.$_GET['api'].'&id='.$res['id'].'" class="btn btn-xs btn-danger" onclick="return confirm(\'你确实要删除此接口吗？\');">删除</a></td></tr>';
}

 echo'         </tbody>
        </table>
      </div>';
}
?>