<?php
require('../class/connect.php');
require('../class/db_sql.php');
require('../member/class/user.php');
require('../data/dbcache/MemberLevel.php');
eCheckCloseMods('member');//关闭模块
eCheckCloseMods('mconnect');//关闭模块
$link=db_connect();
$empire=new mysqlquery();
eCheckCloseMemberConnect();//验证开启的接口
$enews=$_POST['enews'];
if(empty($enews))
{
	$enews=$_GET['enews'];
}
include('memberconnectfun.php');

if(strtolower($enews)=='delbind')//解除绑定
{
	$id=$_GET['id'];
	MemberConnect_DelBind($id);
}
else
{printerror("ErrorUrl","history.go(-1)",1);}
db_close();
$empire=null;
?>