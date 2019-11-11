<?php
// +----------------------------------------------------------------------
// | Quotes [其实台下的观众就我一个，其实我也看出你有点不舍]
// +----------------------------------------------------------------------
// | Created ( PhpStorm )
// +----------------------------------------------------------------------
// | Author: Jonathan <2213147257@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019年04月04日
// +----------------------------------------------------------------------

/** 公告 **/

header("Content-Type: text/html;charset=utf-8");
$title='后台管理中心';
include("../includes/common.php");
include './head.php';
if ($islogin == 1) {
} else {
    exit("<script language='javascript'>window.location.href='./login.php';</script>");
}
include 'nav.php';
?>
<div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
<?php
if (isset($_POST['delnotice'])){
    $nid = $_POST['delnotice'];
    $sql = "DELETE FROM `pay_notice` WHERE `id` = $nid";
    if ($DB->query($sql)){
        echo "<script>alert('删除成功');window.history.go(-1);</script>";
    }
}
?>
        <?php
        if ($_GET['mod'] == 'list'){
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <a href="notice.php?mod=add" class="btn btn-sm btn-info pull-right">添加公告</a>
                <h3 class="panel-title">公告列表</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>标题</th>
                            <th>内容</th>
                            <th>发布时间</th>
                            <th>类型</th>
                            <th>操作</th>
                        </tr>
                        <?php
                        $re = $DB->query("SELECT * FROM `pay_notice`");
                        while ($row = $re->fetch()){
                        ?>
                        <tr>
                            <td><?=subText($row['title'],5)?></td>
                            <td><?=subText($row['content'],5)?></td>
                            <td><?=$row['time']?></td>
                            <td>
                                <?php
                                if ($row['type'] == 0){
                                    echo '商户公告';
                                }elseif ($row['type'] == 1){
                                    echo '注册页公告';
                                }
                                ?>
                            </td>
                            <td>
                                <form action="notice.php?mod=list" method="post">
                                <a href="notice.php?mod=edit&nid=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">编辑</a>
                                <button onclick="return confirm('你确实要删除此公告吗？');" type="submit" class="btn btn-sm btn-danger" value="<?php echo $row['id']; ?>" name="delnotice">删除</button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                        </thead>
                        <tbody>
                </div>
            </div>
        </div>
        <?php }elseif ($_GET['mod'] == 'add'){
            if (isset($_POST['submit'])){
                $title = $_POST['title'];
                $content = $_POST['content'];
                $time = $_POST['time'];
                $type = $_POST['type'];
                $num = $DB->query("SELECT COUNT(1) FROM `pay_notice`")->fetchColumn();
                $id = $num + 1;
                $sql = "INSERT INTO `pay_notice` (`id`,`title`,`content`,`time`,`type`) VALUES ($id,'$title','$content','$time','$type');";
                if ($DB->query($sql)){
                    showmsg('添加成功！<br/><br/><a href="./notice.php?mod=list">>>返回公告列表</a>',1);
                }else{
                    showmsg('添加失败！'.$DB->errorCode(),4);
                }
            }else{
            ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        公告添加
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="notice.php?mod=add" class="form-horizontal" role="form" method="post">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">标题</label>
                            <div class="col-sm-10">
                                <input type="text" name="title" placeholder="公告标题" class="form-control"/>
                            </div>
                        </div><br/>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">内容</label>
                            <div class="col-sm-10">
                                <textarea name="content"  class="form-control" rows="5" placeholder="公告内容"></textarea>
                            </div>
                        </div><br/>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">时间</label>
                            <div class="col-sm-10">
                                <input type="text" name="time" value="<?=$date?>" class="form-control"/>
                            </div>
                        </div><br/>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">公告类型</label>
                            <div class="col-sm-10">
                                <select  class="form-control" name="type">
                                        <option value="0">商户公告</option>
                                        <option value="1">注册页公告</option>
                                </select>
                            </div>
                        </div><br/>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="添加" class="btn btn-primary form-control"/><br/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php }}elseif ($_GET['mod'] == 'edit'){
            $nid = $_GET['nid'];
            $row = $DB->query("SELECT * FROM `pay_notice` WHERE `id` = {$nid}")->fetch();
            if ($row['id'] == ''){
                exit("<script>alert('猜猜哪里出了错误');window.history.go(-1)</script>");
            }
            if (isset($_POST['submit'])){
                $title = $_POST['title'];
                $content = $_POST['content'];
                $time = $_POST['time'];
                $type = $_POST['type'];
                $sql = "UPDATE `pay_notice` SET `title` = '$title',`content` = '$content',`time` = '$time',`type` = '$type' WHERE `id` = {$nid}";
                if ($DB->query($sql)){
                    showmsg('编辑成功！<br/><br/><a href="./notice.php?mod=list">>>返回公告列表</a>',1);
                }else{
                    showmsg('编辑失败！'.$DB->errorCode(),4);
                }
            }else{
            ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        编辑公告
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="notice.php?mod=edit&nid=<?=$row['id']?>" class="form-horizontal" role="form" method="post">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">标题</label>
                            <div class="col-sm-10">
                                <input type="text" name="title" value="<?=$row['title']?>" class="form-control"/>
                            </div>
                        </div><br/>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">内容</label>
                            <div class="col-sm-10">
                                <textarea name="content" class="form-control" rows="5"><?=$row['content']?></textarea>
                            </div>
                        </div><br/>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">时间</label>
                            <div class="col-sm-10">
                                <input type="text" name="time" value="<?=$row['time']?>" class="form-control"/>
                            </div>
                        </div><br/>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">公告类型</label>
                            <div class="col-sm-10">
                                <select  class="form-control" name="type">
                                    <?php
                                    if ($row['type'] == 0){
                                    ?>
                                        <option value="0">商户公告</option>
                                        <option value="1">注册页公告</option>
                                    <?php }elseif ($row['type'] == 1){ ?>
                                        <option value="1">注册页公告</option>
                                        <option value="0">商户公告</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div><br/>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="编辑" class="btn btn-primary form-control"/><br/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php }} ?>
