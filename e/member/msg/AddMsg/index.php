<?php
require("../../../class/connect.php");
require("../../../class/q_functions.php");
require("../../../class/db_sql.php");
require("../../class/user.php");
$link=db_connect();
$empire=new mysqlquery();

eCheckCloseMods('member');//关闭模块
$user=islogin();
$mid=(int)$_GET['mid'];
$username=RepPostStr($_GET['username']);
$re=$_GET['re'];
if($mid)
{
	$r=$empire->fetch1("select title,msgtext,from_username from {$dbtbpre}enewsqmsg where mid=$mid and to_username='$user[username]' limit 1");
	if(empty($username)&&$r['from_username']!=$user['username'])
	{
		$username=$r['from_username'];
	}
	$title=$r['title'];
	$msgtext=$r['msgtext'];
	//回复
	if($re==1)
	{
		$title="Re:".$title;
		$msgtext="\r\n"."------原文内容------\r\n".$msgtext;
	}
}

//新增修改（红星）
require("../../custom/memberCenter.php");


//导入模板
require(ECMS_PATH.'e/template/member/AddMsg.php');
db_close();
$empire=null;
?>