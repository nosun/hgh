<?php
require("../../../class/connect.php");
require("../../../class/q_functions.php");
require("../../../class/db_sql.php");
require("../../class/user.php");
require('../../class/friendfun.php');
$link=db_connect();
$empire=new mysqlquery();

eCheckCloseMods('member');//关闭模块
$user=islogin();
$enews=$_GET['enews'];
if(empty($enews))
{
	$enews="AddFriend";
}
$word="添加好友";
$fcid=(int)$_GET['fcid'];
if($fcid)
{
	$r['cid']=$fcid;
}
$fname=$_GET['fname'];
//修改
if($enews=="EditFriend")
{
	$fid=(int)$_GET['fid'];
	if(empty($fid))
	{
		printerror("ErrorUrl","",1);
	}
	$r=$empire->fetch1("select fid,fname,cid,fsay from {$dbtbpre}enewshy where fid=$fid and userid='$user[userid]'");
	if(empty($r['fid']))
	{
		printerror("ErrorUrl","",1);
	}
	$fname=$r['fname'];
	$word="修改好友";
}
$select=ReturnFriendclass($user['userid'],$r['cid']);

//新增修改（红星）
require("../../custom/memberCenter.php");

//导入模板
require(ECMS_PATH.'e/template/member/AddFriend.php');
db_close();
$empire=null;
?>