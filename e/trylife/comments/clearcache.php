<?php

/*
 * 计划任务名称：清楚评论缓存
 * 文件名：clearcache.php
 * 说  明：将文章模型中标示为期刊的文章数据，每日定时采集到期刊文章模型，并选入指定栏目中；
 * 作  者：nosun;
 * 文件概要：将指定数据从文章模型中查询出来，并逐条写入期刊文章模型，部分数据需要处理，考虑到扩展性，查询栏目和写入栏目作为函数变量存在；
 * 
 */

require('../../class/connect.php'); //引入数据库配置文件和公共函数文件
require('../../class/db_sql.php'); //引入数据库操作文件
require('functions.php'); //引入数据库操作文件

if(!defined('InEmpireCMS'))
{
	exit();
}
$link=db_connect();
$empire=new mysqlquery();

$action	=$_GET['action'];
function del($id){
	global $empire,$dbtbpre;
	$r = $empire->fetch1("select classid from {$dbtbpre}ecms_article_index where id='$id' ");
        $classid= $r[classid];
	$cachepath=comments_cache_path($classid,$id);
	$cachepath=$cachepath.'0.php';
        var_dump($cachepath);
        @unlink($cachepath);
}

if($action=='del'){
$id=$_POST['id'];
if (empty($id)){
    echo "请输入信息id！";
    die;
}
else{
    del($id);
    echo '删除信息id为'.$_POST[id].'的评论缓存成功';
}
}
else{
?>
<!DOCTYPE HTML>
<html lang="zh">
<head>
<meta charset=utf-8" />
<title>删除评论缓存</title>
</head>
<body>
    <h2>删除评论缓存</h2>
	<form method="post" action="?action=del">
	    <div>请输入文章的id：<input name="id" type="text" id="id" /></div>
	    <input type="submit" value="提交" />
	</form>
</body>
</html>
<?php 
}
?>

