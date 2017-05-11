<?php
require("../../class/connect.php");
if(!defined('InEmpireCMS'))
{
	exit();
}
eCheckCloseMods('member');//关闭模块
$myuserid=(int)getcvar('mluserid',0,TRUE);//LGM修改,把用户ID加密[2014年3月16日21:43]
$mhavelogin=0;
$id=(int)$_GET['id'];
$classid=(int)$_GET['classid'];
if($myuserid)
{
	include("../../class/db_sql.php");
        include("../../class/t_functions.php");
	include("../../member/class/user.php");
	include("../../data/dbcache/MemberLevel.php");
	$link=db_connect();
	$empire=new mysqlquery();
	$mhavelogin=1;
	//数据
	$myusername=RepPostVar(getcvar('mlusername'));
	$myrnd=RepPostVar(getcvar('mlrnd'));
	$r=$empire->fetch1("select ".eReturnSelectMemberF('userid,username,groupid,userfen,money,userdate,havemsg,checked')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$myuserid' and ".egetmf('rnd')."='$myrnd' limit 1");
	if(empty($r[userid])||$r[checked]==0)
	{
		EmptyEcmsCookie();
		$mhavelogin=0;
	}
	//会员等级
	if(empty($r[groupid]))
	{$groupid=eReturnMemberDefGroupid();}
	else
	{$groupid=$r[groupid];}
	$groupname=$level_r[$groupid]['groupname'];
	//点数
	$userfen=$r[userfen];
	//余额
	$money=$r[money];
	//天数
	$userdate=0;
        //头像
        $showpic_r=$empire->fetch1("select userpic from {$dbtbpre}enewsmemberadd where userid=".(int)$r[userid]." limit 1");
        if(empty($showpic_r[userpic])){
            $userpic=$public_r[newsurl]."e/data/images/nouserpic.jpg";             
        } else {
            $userpic=$showpic_r[userpic];
        }

	if($r[userdate])
	{
		$userdate=$r[userdate]-time();
		if($userdate<=0)
		{$userdate=0;}
		else
		{$userdate=round($userdate/(24*3600));}
	}
	//是否有短消息
	$havemsg="";
	if($r[havemsg])
	{
            include_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'com_functions.php');
            $msgcount=GetSiteMsgCount($myusername,FALSE);
            if($msgcount<2) $msgcount='';
            else $msgcount='('.$msgcount.')';
            $havemsg="<a href='".$public_r[newsurl]."e/member/msg/' target=_blank><font color=red>您有新消息{$msgcount}</font></a>";
	}
	//$myusername=$r[username];

        //头像
        $showpic_r=$empire->fetch1("select userpic from {$dbtbpre}enewsmemberadd where userid=".(int)$r[userid]." limit 1");
        if(empty($showpic_r[userpic])){
            $userpic=$public_r[newsurl]."e/data/images/nouserpic.jpg";             
        } else {
            $userpic=sys_ResizeImg($showpic_r[userpic],64,64,1,'');
        }

	db_close();
	$empire=null;
}
if($mhavelogin==1)
{
?>
document.write("<a href=\"http://www.szhgh.com/e/member/cp/\" target=\"_blank\"><?=$myusername?></a><?=$havemsg?>&nbsp;<a href=\"http://www.szhgh.com/e/member/msg/\" target=_blank>短信息</a><a href=\"http://www.szhgh.com/e/member/doaction.php?enews=exit&ecmsfrom=9\" onclick=\"return confirm(\'确认要退出?\');\">退出</a>");
<?
}
else
{
?>
document.write("<a href=\"#loginmodal\" title=\"点此登录\" class=\"flatbtn loginbutton\" id=\"modaltrigger\">登录</a> <span class=\"toploginex\"><script type=\"text/javascript\">document.write(\'<script  type=\"text/javascript\" src=\"http://www.szhgh.com/e/memberconnect/panjs.php?type=login&dclass=login&t=\'+Math.random()+\'\"><\'+\'/script>\');</script></span>");
<?
}
?>