<?php
error_reporting(E_ALL ^ E_NOTICE);      //设置 PHP 的报错级别并返回当前级别
@set_time_limit(10000);     //设置脚本最大执行时间

require("lib/connect.php");
require("lib/db_sql.php");
require("lib/fun.php");
require("lib/functions.php");
if(!$_GET['ecms'])
{}
else
{
$link=db_connect();
$empire=new mysqlquery();
}
if($_GET['ecms']=="success")
{
	echo"<link rel=\"stylesheet\" href=\"../data/images/css.css\" type=\"text/css\"><br><br><br><font color=red><b>恭喜你，数据转换成功</b></font>";
	exit();
}
elseif($_GET['ecms']=="DoTagsInfo")//信息表数据转换(1)
{
        include('do/tags_infosql.php');
        DoTagsInfo($_GET);
        exit();
}
elseif($_GET['ecms']=="DoAuthorInfo")//信息表数据转换(2)
{
        include('do/author_infosql.php');
        DoAuthorInfo($_GET);
        exit();
}
elseif($_GET['ecms']=="DoArticleInfo")//信息表数据转换(3)
{
        include('do/infosql.php');
        DoArticleInfo($_GET);
        exit();
}
elseif($_GET['ecms']=="DoCommentsInfo")//信息表数据转换(4)
{
        include('do/comments_infosql.php');
        DoCommentsInfo($_GET);
        exit();
}
elseif($_GET['ecms']=="DoFileInfo")//信息表数据转换(5)
{
        include('do/file_infosql.php');
        DoFileInfo($_GET);
        exit();
}
elseif($_GET['ecms']=="DoZtInfo")//信息表数据转换(6)
{
        include('do/zt_infosql.php');
        DoZtInfo($_GET);
        exit();
}
elseif($_GET['ecms']=="DoNewstagsInfo")//信息表数据转换(7)
{
        include('do/newstags_infosql.php');
        DoNewstagsInfo($_GET);
        exit();
}
elseif($_GET['ecms']=="DoCopyfromInfo")//信息表数据转换(7)
{
        include('do/copyfrom_infosql.php');
        DoCopyfromInfo($_GET);
        exit();
}
elseif($_GET['ecms']=="DoPostInfo")//信息表数据转换(8)
{
        include('do/post_infosql.php');
        DoPostInfo($_GET);
        exit();
}
elseif($_GET['ecms']=="maintenance")//信息表数据转换(9)
{
        include('do/maintenance.php');
        DoMaintenance($_GET);
        exit();
}
elseif($_GET['ecms']=="CheckReUpdate")//检查升级状况(0)
{
	//CheckReUpdate();
        echo"<link rel=\"stylesheet\" href=\"../data/images/css.css\" type=\"text/css\"><br>第一步：检查升级状态<script>self.location.href='index.php?ecms=ChangeInfoTbData';</script>";
	exit();
}
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>supesite转换ecms程序</title>
</head>
<body>
<h2>
    <?php echo $task; ?>
</h2>
<p>
    <?php echo $status; ?>
</p>
</body>
</html>