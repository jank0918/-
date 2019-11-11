<nav class="navbar navbar-fixed-top navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">导航按钮</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="./">SAF支付管理中心</a>
        </div><!-- /.navbar-header -->
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="<?=checkIfActive(',index')?>">
                    <a href="./"><span class="glyphicon glyphicon-home"></span> 平台首页</a>
                </li>
                <li class="<?=checkIfActive('order,outorder')?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cloud"></span> 订单操作<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="./order.php">订单列表</a></li>
                        <li><a href="./outorder.php?mod=index">导出订单</a><li>
                    </ul>
                </li>
                <li class="<?=checkIfActive('settle,slist')?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cloud"></span> 结算管理<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="./settle.php">结算操作</a></li>
                        <li><a href="./slist.php">结算记录</a><li>
                    </ul>
                </li>
                <li class="<?=checkIfActive('ulist')?>"><a href="./ulist.php"><span class="glyphicon glyphicon-user"></span> 商户管理</a></li>
                <li  class="<?=checkIfActive('set,v_set')?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cloud"></span> 网站配置<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="./other.php?my=admin">管理员账号配置</a><li>
                        <li><a href="./other.php?my=glj">商品拦截配置</a><li>
                        <li><a href="./payset.php">支付接口配置</a><li>
                        <li><a href="./rate_set.php">费率设置</a><li>
                        <li><a href="./paysetall.php">多官方接口配置</a><li>
                        <li><a href="./v_set.php">发件验证配置</a></li>
                        <li><a href="./set.php">网站配置</a></li>
                        <li><a href="./notice.php?mod=list">公告配置</a></li>
                        <li><a href="./safe_switch.php">用户二次验证设置</a></li>
                        <li><a href="./tem_set.php">首页模板设置</a></li>
                        <li><a href="./update.php">在线更新</a></li>
                    </ul>
                </li>
                <li><a href="./login.php?logout"><span class="glyphicon glyphicon-log-out"></span> 退出登陆</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
</nav><!-- /.navbar -->