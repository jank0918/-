<?php
// +----------------------------------------------------------------------
// | Quotes [其实台下的观众就我一个，其实我也看出你有点不舍]
// +----------------------------------------------------------------------
// | Created ( PhpStorm )
// +----------------------------------------------------------------------
// | Author: Jonathan <2213147257@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019年04月05日
// +----------------------------------------------------------------------

/** 商户公告 **/

include("../includes/common.php");
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$title='公告列表';
include './head.php';
?>
<?php

$numrows=$DB->query("SELECT * from pay_notice WHERE `type` = 0")->rowCount();
$pagesize=20;
$pages=intval($numrows/$pagesize);
if ($numrows%$pagesize)
{
    $pages++;
}
if (isset($_GET['page'])){
    $page=intval($_GET['page']);
}
else{
    $page=1;
}
$offset=$pagesize*($page - 1);

$list=$DB->query("SELECT * FROM `pay_notice` WHERE `type` = 0 order by id desc limit $offset,$pagesize")->fetchAll();

?>
    <div id="content" class="app-content" role="main">
        <div class="app-content-body ">

            <div class="bg-light lter b-b wrapper-md hidden-print">
                <h1 class="m-n font-thin h3">公告列表</h1>
            </div>
            <div class="wrapper-md control">
                <div class="panel panel-default">
                    <div class="panel-heading font-bold">
                        公告列表&nbsp;(<?php echo $numrows?>)
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>公告标题</th>
                                <th>公告内容</th>
                                <th>发布时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $f = 0;
                            foreach ($list as $row){
                            ?>
                            <tr>
                                <td><?php echo $row['title'] ?></td>
                                <td><?php echo subText($row['content'],5) ?></td>
                                <td><?php echo $row['time'] ?></td>
                                <td><a id="check_notice<?=$f?>" class="btn btn-sm btn-primary">查看</a></td>
                                <button name="title<?=$f?>" hidden="hidden" value="<?=$row['title']?>"></button>
                                <button name="content<?=$f?>" hidden="hidden" value="<?=$row['content']?>"></button>
                                <button name="time<?=$f?>" hidden="hidden" value="<?=$row['time']?>"></button>
                            </tr>
                            <?php
                            $f++;
                            } ?>
                            </tbody>
                        </table>
                    </div>

                    <footer class="panel-footer">
                        <?php
                        echo'<ul class="pagination">';
                        $first=1;
                        $prev=$page-1;
                        $next=$page+1;
                        $last=$pages;
                        if ($page>1)
                        {
                            echo '<li><a href="notice.php?page='.$first.$link.'">首页</a></li>';
                            echo '<li><a href="notice.php?page='.$prev.$link.'">&laquo;</a></li>';
                        } else {
                            echo '<li class="disabled"><a>首页</a></li>';
                            echo '<li class="disabled"><a>&laquo;</a></li>';
                        }
                        for ($i=1;$i<$page;$i++)
                            echo '<li><a href="notice.php?page='.$i.$link.'">'.$i .'</a></li>';
                        echo '<li class="disabled"><a>'.$page.'</a></li>';
                        if($pages>=10)$pages=10;
                        for ($i=$page+1;$i<=$pages;$i++)
                            echo '<li><a href="notice.php?page='.$i.$link.'">'.$i .'</a></li>';
                        echo '';
                        if ($page<$pages)
                        {
                            echo '<li><a href="notice.php?page='.$next.$link.'">&raquo;</a></li>';
                            echo '<li><a href="notice.php?page='.$last.$link.'">尾页</a></li>';
                        } else {
                            echo '<li class="disabled"><a>&raquo;</a></li>';
                            echo '<li class="disabled"><a>尾页</a></li>';
                        }
                        echo'</ul>';
                        #分页
                        ?>
                    </footer>
                </div>
            </div>
        </div>
    </div>

<?php include 'foot.php';?>

<script>
    <?php
    $f1 = 0;
    foreach ($list as $row){
    ?>
    $("#check_notice<?=$f1?>").click(function(){
        var title = $("button[name='title<?=$f1?>']").val();
        var content = $("button[name='content<?=$f1?>']").val();
        var time = $("button[name='time<?=$f1?>']").val();
        layer.open({
            type:1,
            area:['420px','240px'],
            title:title,
            content:'' +
                '标题：'+title +'<br />' +
                '内容：'+content+'<br />' +
                '发布时间：'+time+
                ''
        });
    });
    <?php
    $f1++;
    } ?>
</script>