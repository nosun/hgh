<?php
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/q_functions.php");
require("../class/user.php");
require("../../data/dbcache/MemberLevel.php");
$link=db_connect();
$empire=new mysqlquery();

eCheckCloseMods('member');//关闭模块
//是否登陆
$user=islogin();
$r=ReturnUserInfo($user[userid]);
$userdate=0;
//时间
if($r[userdate])
{
	$userdate=$r[userdate]-time();
	if($userdate<=0)
	{
		$userdate=0;
	}
	else
	{
		$userdate=round($userdate/(24*3600));
	}
}

//新增修改（红星）
require("../custom/memberCenter.php");

//注册时间
$registertime=eReturnMemberRegtime($r['registertime'],"Y-m-d H:i:s");
//导入模板
require(ECMS_PATH.'e/template/member/my.php');
db_close();
$empire=null;
?>