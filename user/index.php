<?php
include("../includes/common.php");
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$title='用户中心';
include './head.php';
?>
<script src="https://template.down.swap.wang/ui/angulr_2.0.1/bower_components/jquery/dist/jquery.min.js"></script>

<script src="../assets/layer/layer.js"></script>
<?php
if(isset($_GET['act'])){
              $notify_url   = $siteurl . 'notify_getvip.php';
            $return_url   = $siteurl . 'index.php?act=getvipok';
            $trade_no     = date("YmdHis") . rand(11111, 99999);
            $out_trade_no = date("YmdHis") . rand(111, 999);
            $domain       = getdomain($notify_url);


   			 $level=$userrow['level'];
  if($_GET['act']=='getvip'){
//升级会员
  if($level==1){
  	//升级到黄金会员
    if($conf['level_vip']>0)
    {
      if($userrow['money']<$conf['level_vip'])
      {
      	//余额不足，跳转在线支付
            if (!$DB->query("insert into `pay_order` (`trade_no`,`out_trade_no`,`notify_url`,`return_url`,`type`,`pid`,`addtime`,`name`,`money`,`domain`,`ip`,`status`) values ('" . $trade_no . "','" . $out_trade_no . "','" . $notify_url . "','" . $return_url . "','no','" . $conf['reg_pid'] . "','" . $date . "','{$pid}升级黄金会员','" . $conf['level_vip'] . "','" . $domain . "','" . $clientip . "','0')")) {
                exit("<script language='javascript'>alert('余额不足，在线支付订单创建失败！');window.location.href='./index.php';</script>");
            }
			exit("<script language='javascript'>
			
            layer.open({
					  type: 1,
					  title: '支付确认页面',
					  skin: 'layui-layer-rim',
					  content: '<li class=\"list-group-item\"><b>所需支付金额：</b>{$conf['level_vip']}元</li><li class=\"list-group-item text-center\"><a href=\"../submit2.php?type=alipay&trade_no={$trade_no}\" class=\"btn btn-default\"><img src=\"../assets/icon/alipay.ico\" class=\"logo\">支付宝</a>&nbsp;<a href=\"../submit2.php?type=wxpay&trade_no={$trade_no}\" class=\"btn btn-default\"><img src=\"../assets/icon/wechat.ico\" class=\"logo\">微信支付</a>&nbsp;<a href=\"../submit2.php?type=qqpay&trade_no={$trade_no}\" class=\"btn btn-default\"><img src=\"../assets/icon/qqpay.ico\" class=\"logo\">QQ钱包</a>&nbsp;<li class=\"list-group-item\">提示：支付完成后请到主页查看到账信息</li>'});

            </script>
            ");
      }else
    	if($DB->exec("update `pay_user` set `money`=`money`-{$conf['level_vip']},`level`=2,`level_endtime`='{$addtime}' where `id`={$pid}"))
        {
        	//扣款成功
            if (!$DB->query("insert into `pay_order` (`trade_no`,`out_trade_no`,`notify_url`,`return_url`,`type`,`pid`,`addtime`,`name`,`money`,`domain`,`ip`,`status`,`buyer`) values ('" . $trade_no . "','" . $out_trade_no . "','" . $notify_url . "','" . $return_url . "','余额支付','" . $conf['reg_pid'] . "','" . $date . "','{$pid}升级黄金会员','" . $conf['level_vip'] . "','" . $domain . "','" . $clientip . "','1','{$pid}')")) {
			  $DB->exec("update `pay_user` set `money`={$userrow['money']},`level`=1 where `id`={$pid}");
              exit("<script language='javascript'>alert('充值失败！请重试！');window.location.href='./index.php';</script>");
            }
          	exit("<script language='javascript'>alert('升级成功！您已成功升级到黄金会员！');window.location.href='./index.php';</script>");
        }else{
        	exit("<script language='javascript'>alert('无法升级！扣款失败！');window.location.href='./index.php';</script>");
        }
          
    }else{
    	exit("<script language='javascript'>alert('无法升级！原因有：站长未初始化价格');window.location.href='./index.php';</script>");
    }
  }elseif($level==2){
        if($conf['level_svip']>0)
    {
       if($userrow['money']<$conf['level_svip'])
      {
      	//余额不足，跳转在线支付
            if (!$DB->query("insert into `pay_order` (`trade_no`,`out_trade_no`,`notify_url`,`return_url`,`type`,`pid`,`addtime`,`name`,`money`,`domain`,`ip`,`status`) values ('" . $trade_no . "','" . $out_trade_no . "','" . $notify_url . "','" . $return_url . "','no','" . $conf['reg_pid'] . "','" . $date . "','{$pid}升级钻石会员','" . $conf['level_svip'] . "','" . $domain . "','" . $clientip . "','0')")) {
                exit("<script language='javascript'>alert('余额不足，在线支付订单创建失败！');window.location.href='./index.php';</script>");
            }
			exit("<script language='javascript'>
			
            layer.open({
					  type: 1,
					  title: '支付确认页面',
					  skin: 'layui-layer-rim',
					  content: '<li class=\"list-group-item\"><b>所需支付金额：</b>{$conf['level_svip']}元</li><li class=\"list-group-item text-center\"><a href=\"../submit2.php?type=alipay&trade_no={$trade_no}\" class=\"btn btn-default\"><img src=\"../assets/icon/alipay.ico\" class=\"logo\">支付宝</a>&nbsp;<a href=\"../submit2.php?type=wxpay&trade_no={$trade_no}\" class=\"btn btn-default\"><img src=\"../assets/icon/wechat.ico\" class=\"logo\">微信支付</a>&nbsp;<a href=\"../submit2.php?type=qqpay&trade_no={$trade_no}\" class=\"btn btn-default\"><img src=\"../assets/icon/qqpay.ico\" class=\"logo\">QQ钱包</a>&nbsp;<li class=\"list-group-item\">提示：支付完成后请到主页查看到账信息</li>'});

            </script>
            ");
      }else
    	if($DB->exec("update `pay_user` set `money`=`money`-{$conf['level_svip']},`level`=3,`level_endtime`='{$addtime}' where `id`={$pid}"))
        {
        	//扣款成功
          	 if (!$DB->query("insert into `pay_order` (`trade_no`,`out_trade_no`,`notify_url`,`return_url`,`type`,`pid`,`addtime`,`name`,`money`,`domain`,`ip`,`status`,`buyer`) values ('" . $trade_no . "','" . $out_trade_no . "','" . $notify_url . "','" . $return_url . "','余额支付','" . $conf['reg_pid'] . "','" . $date . "','{$pid}升级钻石会员','" . $conf['level_vip'] . "','" . $domain . "','" . $clientip . "','1','{$pid}')")) {
			  $DB->exec("update `pay_user` set `money`={$userrow['money']},`level`=2,`level_endtime`='0' where `id`={$pid}");
              exit("<script language='javascript'>alert('充值失败！请重试！');window.location.href='./index.php';</script>");
            }
          	exit("<script language='javascript'>alert('升级成功！您已成功升级到钻石会员！');window.location.href='./index.php';</script>");
        }else{
        	exit("<script language='javascript'>alert('无法升级！扣款失败！');window.location.href='./index.php';</script>");
        }
          
    }else{
    	exit("<script language='javascript'>alert('无法升级！站长未初始化价格');window.location.href='./index.php';</script>");
    }
  }else{
  	exit("<script language='javascript'>alert('无法升级！级别已满');window.location.href='./index.php';</script>");
  }
  }elseif($_GET['act']=='getvipyear'){
  	  if($level==1){
  	//升级到黄金会员
    if($conf['level_vip_year']>0)
    {
      if($userrow['money']<$conf['level_vip_year'])
      {
      	//余额不足，跳转在线支付
            if (!$DB->query("insert into `pay_order` (`trade_no`,`out_trade_no`,`notify_url`,`return_url`,`type`,`pid`,`addtime`,`name`,`money`,`domain`,`ip`,`status`) values ('" . $trade_no . "','" . $out_trade_no . "','" . $notify_url . "','" . $return_url . "','no','" . $conf['reg_pid'] . "','" . $date . "','{$pid}b套餐升级包年黄金会员','" . $conf['level_vip_year'] . "','" . $domain . "','" . $clientip . "','0')")) {
                exit("<script language='javascript'>alert('余额不足，在线支付订单创建失败！');window.location.href='./index.php';</script>");
            }
			exit("<script language='javascript'>
			
            layer.open({
					  type: 1,
					  title: '支付确认页面',
					  skin: 'layui-layer-rim',
					  content: '<li class=\"list-group-item\"><b>所需支付金额：</b>{$conf['level_vip_year']}元</li><li class=\"list-group-item text-center\"><a href=\"../submit2.php?type=alipay&trade_no={$trade_no}\" class=\"btn btn-default\"><img src=\"../assets/icon/alipay.ico\" class=\"logo\">支付宝</a>&nbsp;<a href=\"../submit2.php?type=wxpay&trade_no={$trade_no}\" class=\"btn btn-default\"><img src=\"../assets/icon/wechat.ico\" class=\"logo\">微信支付</a>&nbsp;<a href=\"../submit2.php?type=qqpay&trade_no={$trade_no}\" class=\"btn btn-default\"><img src=\"../assets/icon/qqpay.ico\" class=\"logo\">QQ钱包</a>&nbsp;<li class=\"list-group-item\">提示：支付完成后请到主页查看到账信息</li>'});

            </script>
            ");
      }else
    	if($DB->exec("update `pay_user` set `money`=`money`-{$conf['level_vip_year']},`level`=2,`level_endtime`='{$addtime2}' where `id`={$pid}"))
        {
        	//扣款成功
            if (!$DB->query("insert into `pay_order` (`trade_no`,`out_trade_no`,`notify_url`,`return_url`,`type`,`pid`,`addtime`,`name`,`money`,`domain`,`ip`,`status`,`buyer`) values ('" . $trade_no . "','" . $out_trade_no . "','" . $notify_url . "','" . $return_url . "','余额支付','" . $conf['reg_pid'] . "','" . $date . "','{$pid}b套餐升级包年黄金会员','" . $conf['level_vip'] . "','" . $domain . "','" . $clientip . "','1','{$pid}')")) {
			  $DB->exec("update `pay_user` set `money`={$userrow['money']},`level`=1 where `id`={$pid}");
              exit("<script language='javascript'>alert('充值失败！请重试！');window.location.href='./index.php';</script>");
            }
          	exit("<script language='javascript'>alert('升级成功！您已成功升级到包年黄金会员！');window.location.href='./index.php';</script>");
        }else{
        	exit("<script language='javascript'>alert('无法升级！扣款失败！');window.location.href='./index.php';</script>");
        }
          
    }else{
    	exit("<script language='javascript'>alert('无法升级！原因有：站长未初始化价格');window.location.href='./index.php';</script>");
    }
  }
}
}

$orders=$DB->query("SELECT count(*) from pay_order WHERE pid={$pid}")->fetchColumn();
$yoursendtime=date('Y-m-d H:i:s',$userrow['level_endtime']);
$lastday=date("Y-m-d",strtotime("-1 day")).' 00:00:00';
$today=date("Y-m-d").' 00:00:00';
$order_today=$DB->query("SELECT sum(money) from pay_order where pid={$pid} and status=1 and endtime>='$today'")->fetchColumn();

$order_lastday=$DB->query("SELECT sum(money) from pay_order where pid={$pid} and status=1 and endtime>='$lastday' and endtime<'$today'")->fetchColumn();

$rs=$DB->query("SELECT * from pay_settle where pid={$pid} and status=1");
$settle_money=0;
$max_settle=0;
$chart='';
$i=0;
while($row = $rs->fetch())
{
	$settle_money+=$row['money'];
	if($row['money']>$max_settle)$max_settle=$row['money'];
	if($i<9)$chart.='['.$i.','.$row['money'].'],';
	$i++;
}
$chart=substr($chart,0,-1);
if($conf['verifytype']==1 && empty($userrow['phone'])){
    $alertinfo='你还没有绑定密保手机，请&nbsp;<a href="userinfo.php" class="btn btn-sm btn-info">尽快绑定</a>';
}elseif(empty($userrow['email'])){
    $alertinfo='你还没有绑定密保邮箱，请&nbsp;<a href="userinfo.php" class="btn btn-sm btn-info">尽快绑定</a>';
}elseif ($conf['safe_switch'] == 1){
    if (empty($userrow['safecode'])){
        $alertinfo='你还没有设置安全码，请&nbsp;<a href="userinfo.php" class="btn btn-sm btn-info">尽快绑定</a>';
    }
}

?>
 <div id="content" class="app-content" role="main">
    <div class="app-content-body ">
		<?php if(($conf['verifytype']==1 && empty($userrow['phone'])) || ($conf['verifytype']==0 && empty($userrow['email'])) || ($conf['safe_switch'] == 1) && empty($userrow['safecode'])){?>
		<div class="modal inmodal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span>
						</button>
						<h4 class="modal-title">提示信息</h4>
					</div>
					<div class="modal-body">
						<?php echo $alertinfo?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
					</div>
				</div>
			</div>
		</div>      
        <?php }else{?> <?php }?>

<div class="bg-light lter b-b wrapper-md hidden-print">
  <h1 class="m-n font-thin h3">用户中心</h1>
  <small class="text-muted">欢迎使用<?php 
    echo $conf['web_name'];
        if($userrow['level']==2)
      echo "--您是尊贵的<font color='red'>黄金会员</font>--享有".(100-$conf['money_rate_vip'])."%的低费率【到期时间：{$yoursendtime}】<a href='?act=getvip' onclick=\"return confirm('确定要花{$conf['level_svip']}元进行升级吗？')\"> 点我升级到钻石会员</a>";
    else if($userrow['level']==3)
      echo "--您是尊贵的<font color='red'>钻石会员</font>--享有".(100-$conf['money_rate_svip'])."%的低费率【到期时间：{$yoursendtime}】";
    else
      echo "--您是普通会员--享有微信费率：".(100-$conf['money_rate_wxpay'])."%，QQ费率：".(100-$conf['money_rate_qqpay'])."%，支付宝费率：".(100-$conf['money_rate_alipay'])."%<a href='?act=getvip' onclick=\"return confirm('确定要花{$conf['level_vip']}元进行包月升级吗？')\"> 点我开通包月黄金会员</a><a href='?act=getvipyear' onclick=\"return confirm('确定要花{$conf['level_vip_year']}元进行包年升级吗？')\"> 点我开通包年黄金会员</a>";
    ?></small>
</div>
<div class="col-md-12">      
<div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <em class="fa fa-bell-o fa-fw"></em>最新公告
                            </h4>
                        </div>
                        <div class="panel-body">
                            <div class="list-group">
							<center><iframe width="280" scrolling="no" height="25" frameborder="0" allowtransparency="true" src="http://i.tianqi.com/index.php?c=code&amp;id=34&amp;icon=1&amp;num=3"></iframe></center>
                                <?php
                                $f = 0;
                                $re = $DB->query("SELECT * FROM `pay_notice` WHERE `type` = 0 order by id desc limit 5");
                                $nrow = $re->fetchAll();
                                foreach ($nrow as $notice){
                                ?>
                                <a id="notice<?=$f?>" class="list-group-item"><span class="pull-right"> </span><button name="ntct<?=$f?>" type="button"hidden="hidden" value="<?=$notice['title']?>"></button><button name="ntcc<?=$f?>" type="button" hidden="hidden" value="<?=$notice['content']?>"></button><em class="fa fa-fw fa-volume-up mr"></em><?=$notice['content']?></a>
                                <?php
                                $f++;
                                } ?>
							</div>	
                        </div>
                    </div>
  </div>
<div class="wrapper-md control">
<!-- stats -->
      <div class="row">
        <div class="col-md-5">
          <div class="row row-sm text-center">
            <div class="col-xs-6">
              <div class="panel padder-v item">
                <div class="h1 text-info font-thin h1"><?php echo $orders?>个</div>
                <span class="text-muted text-xs">订单总数</span>
                <div class="top text-right w-full">
                  <i class="fa fa-caret-down text-warning m-r-sm"></i>
                </div>
              </div>
            </div>
            <div class="col-xs-6">
              <div class="block panel padder-v bg-primary item">
                <span class="text-white font-thin h1 block">￥<?php echo $settle_money?></span>
                <span class="text-muted text-xs">已结算余额</span>
                <span class="bottom text-right w-full">
                  <i class="fa fa-caret-down text-muted m-r-sm"></i>
                </span>
              </div>
            </div>
            <div class="col-xs-6">
              <div class="block panel padder-v bg-info item">
                <span class="text-white font-thin h1 block">￥<?php echo $order_today?></span>
                <span class="text-muted text-xs">今日收入</span>
                <span class="top">
                  <i class="fa fa-caret-up text-warning m-l-sm m-r-sm"></i>
                </span>
              </div>
            </div>
            <div class="col-xs-6">
              <div class="panel padder-v item">
                <div class="font-thin h1">￥<?php echo $order_lastday?></div>
                <span class="text-muted text-xs">昨日收入</span>
                <div class="bottom">
                  <i class="fa fa-caret-up text-warning m-l-sm m-r-sm"></i>
                </div>
              </div>
            </div>
            <div class="col-xs-12 m-b-md">
              <div class="r bg-light dker item hbox no-border">
                <div class="col w-xs v-middle hidden-md">
                  <div ng-init="d3_3=[60,40]" ui-jq="sparkline" ui-options="[60,40], {type:'pie', height:40, sliceColors:['#fad733','#fff']}" class="sparkline inline"></div>
                </div>
                <div class="col dk padder-v r-r">
                  <div class="text-primary-dk font-thin h1"><span>￥<?php echo $userrow['money']?></span></div>
                  <span class="text-muted text-xs">商户当前余额</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <div class="panel wrapper">
            <label class="i-switch bg-warning pull-right" ng-init="showSpline=true">
              <input type="checkbox" ng-model="showSpline">
              <i></i>
            </label>
            <h4 class="font-thin m-t-none m-b text-muted">结算统计表</h4>
            <div ui-jq="plot" ui-refresh="showSpline" ui-options="
              [
                { data: [ <?php echo $chart?> ], label:'结算金额', points: { show: true, radius: 1}, splines: { show: true, tension: 0.4, lineWidth: 1, fill: 0.8 } }
              ], 
              {
                colors: ['#23b7e5', '#7266ba'],
                series: { shadowSize: 3 },
                xaxis:{ font: { color: '#a1a7ac' } },
                yaxis:{ font: { color: '#a1a7ac' }, max:<?php echo ($max_settle+10)?> },
                grid: { hoverable: true, clickable: true, borderWidth: 0, color: '#dce5ec' },
                tooltip: true,
                tooltipOpts: { content: '结算金额￥%y',  defaultTheme: false, shifts: { x: 10, y: -25 } }
              }
            " style="height:246px" >
            </div>
          </div>
        </div>
      </div>
      <!-- / stats -->
	<div class="panel panel-default">
		<div class="panel-heading font-bold">
			基本资料
		</div>
		<div class="panel-body">
			<form class="form-horizontal devform">
				<div class="form-group">
					<label class="col-sm-2 control-label">商户ID</label>
					<div class="col-sm-9">
						<input class="form-control" type="text" value="<?php echo $pid?>" disabled>
					</div>
				</div>
				<div class="line line-dashed b-b line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-2 control-label">商户密钥</label>
					<div class="col-sm-9">
						<input class="form-control" type="text" value="<?php echo $userrow['key']?>" disabled>
					</div>
				</div>
				<div class="line line-dashed b-b line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-2 control-label">账号绑定</label>
					<div class="col-sm-9">
					<?php if($conf['quicklogin']==1 && empty($userrow['alipay_uid'])){?>
						<a href="oauth.php?bind=true" class="btn btn-primary btn-sm" target="_blank">绑定支付宝账号 一键登录到本站</a>
					<?php }else if($conf['quicklogin']==1 && !empty($userrow['alipay_uid'])){?>
						已绑定支付宝UID:<?php echo $userrow['alipay_uid']?>&nbsp;<a href="oauth.php?unbind=true" class="btn btn-danger btn-xs" onclick="return confirm('解绑后将无法通过支付宝一键登录，是否确定解绑？');">解绑账号</a>
					<?php }else if($conf['quicklogin']==2 && empty($userrow['qq_uid'])){?>
						<a href="connect.php?bind=true" class="btn btn-primary btn-sm" target="_blank">绑定QQ 一键登录到本站</a>
					<?php }else{?>
						已绑定QQ互联Openid:<?php echo $userrow['qq_uid']?>&nbsp;<a href="connect.php?unbind=true" class="btn btn-danger btn-xs" onclick="return confirm('解绑后将无法通过支付宝一键登录，是否确定解绑？');">解绑账号</a>
					<?php }?>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
    </div>
  </div>

<?php include 'foot.php';?>
<script>
$(document).ready(function(){
	<?php if(isset($alertinfo)){
	    ?>
    $('#myModal').modal('show');
    <?php }?>
    $("#notice0").click(function(){
        var title = $("button[name='ntct0']").val();
        var content = $("button[name='ntcc0']").val();
        layer.alert(content,{
            title:title
        });
    });
    $("#notice1").click(function(){
        var title = $("button[name='ntct1']").val();
        var content = $("button[name='ntcc1']").val();
        layer.alert(content,{
            title:title
        });
    });
    $("#notice2").click(function(){
        var title = $("button[name='ntct2']").val();
        var content = $("button[name='ntcc2']").val();
        layer.alert(content,{
            title:title
        });
    });
    $("#notice3").click(function(){
        var title = $("button[name='ntct3']").val();
        var content = $("button[name='ntcc3']").val();
        layer.alert(content,{
            title:title
        });
    });
    $("#notice4").click(function(){
        var title = $("button[name='ntct4']").val();
        var content = $("button[name='ntcc4']").val();
        layer.alert(content,{
            title:title
        });
    });

});
</script>