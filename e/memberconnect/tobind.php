<?php
require_once("../class/connect.php");
require_once("../class/db_sql.php");
require_once("../class/q_functions.php");
require_once("../member/class/user.php");
eCheckCloseMods('member');//关闭模块
eCheckCloseMods('mconnect');//关闭模块
$link=db_connect();
$empire=new mysqlquery();
eCheckCloseMemberConnect();//验证开启的接口
if(!isset($_SESSION)) session_start();
require('memberconnectfun.php');
$apptype=$_SESSION['apptype'];
$appid=$_SESSION['cappid'];
$openid=$_SESSION['openid'];
$eiusername=$_SESSION['openname'];//LGM 增加[2014年2月16日13:50] 预定义的用户名
var_dump($eiusername);
if(!trim($apptype)||!trim($openid))
{
	printerror2('来自的链接不存在','../../../');
}
$appr=MemberConnect_CheckAppId($appid);//验证登录方式
MemberConnect_CheckBindKey($appid,$openid);


$regurl=GetEPath().'member/register/?tobind=1&eiusername='.urlencode($eiusername);
$loginurl=GetEPath().'member/login/?tobind=1';
//如果已经登录了:

$curuid = getcvar('mluserid',0,TRUE);
var_dump($curuid);
if($curuid)
{
    if(BindMemberFromEI($curuid))
    {
        printerror('BindEISuccess','/e/memberconnect/ListBind.php',1);
    }
    else
       printerror('BindEIFail','/e/memberconnect/ListBind.php',1); 
}
//判断以前是否已经有会员登录

$oldlogin = GetValueFromCookie('lastlogin',0,FALSE);
$mustnewreg = $_REQUEST['newreg'];
if($mustnewreg=='true')
    $mustnewreg = 1;
else 
    $mustnewreg = intval($mustnewreg);
if($mustnewreg) 
    $oldlogin = NULL;
$HasOldUser = FALSE;

if(!empty($oldlogin))
{//登录
   list($lastlogintime,$lastloginuserid) = explode('@', $oldlogin); 
   $lastloginusername = GetUserName($lastloginuserid);
   if(!empty($lastloginusername))
   {
       $HasOldUser = TRUE;
       $loginurl.='&defname='.urlencode($lastloginusername);          
   }
}
$bindtype = $_REQUEST['bindtype'];
if(!empty($bindtype))
{
    $bindtype = strtolower($bindtype);
    if($bindtype=='register')
    {
        Header('HTTP/1.1 303 See Other'); 
        Header("Location: $regurl"); 
        exit;
    }
    elseif($bindtype=='login')
    {
            Header('HTTP/1.1 303 See Other'); 
            Header("Location: $loginurl");    
            exit;
    }
}

if(!empty($public_r['add_EIQuickReg']))
{
    if($HasOldUser)
    {
            Header('HTTP/1.1 303 See Other'); 
            Header("Location: $loginurl");    
            exit;          
    }
    elseif($HasOldUser===FALSE)//$HasOldUser有等于NULL的情况
    {
        $regurl .= '&randompwd=1';
	//var_dump($regurl);die;
        Header('HTTP/1.1 303 See Other'); 
        Header("Location: $regurl"); 
        exit;
    }
}

//导入模板
require(ECMS_PATH.'e/template/memberconnect/tobind.php');
db_close();
unset($empire);