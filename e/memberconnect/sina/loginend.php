﻿<?php

require_once('../../class/connect.php');
require_once('../../class/db_sql.php');
require_once('../../class/q_functions.php');
require_once('../../member/class/user.php');

//ini_set('display_errors', true);error_reporting(E_ALL | E_STRICT);
eCheckCloseMods('member');//关闭模块
eCheckCloseMods('mconnect');//关闭模块

$link=db_connect();
$empire=new mysqlquery();
eCheckCloseMemberConnect();//验证开启的接口
if(empty($_SESSION)) session_start();
require_once('../memberconnectfun.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'class.php');

if(isset($_REQUEST['error']))
{
    if(21330 != $_REQUEST['error_code']) printerror2($_REQUEST['error_description'].'(code:'.$_REQUEST['error_code'].')','../../../');
    else  printerror2('您取消了授权,可以尝试其它方法进入','../../../');
}
//------------------ 参数开始 ------------------

$cappid = $_SESSION['cappid'];

$env = EIService::GetENV($cappid);
if(!$env)
{

    $muserid=(int)getcvar('mluserid',0,TRUE);
    if($muserid!=0) {//已经登录了
        header('Location:/');
        exit();
    }

    //$_SESSION丢失
    if(empty($_SESSION)){

        $ptypef = array('apptype'=>'sina');
        $jqv =  EIService::IndexENV($ptypef);
        if(!empty($jqv)){
            header("Location:".$public_r['newsurl']."e/memberconnect/index.php?id={$jqv['id']}&apptype=sina");//直接转跳
            exit();
        }
    }
        printerror2('请选择登录方式','../../../');
}

$apptype = $env['type'];

if(empty($apptype))
{
    $dirnodes = explode(DIRECTORY_SEPARATOR , __FILE__);
    $apptype = $dirnodes[count($dirnodes)-2];
}
if(empty($apptype))$apptype = "sina";
$appk=$env['appkey'];//应用的APPID
$app_secret=$env['appsecret'];//应用的APPKEY
$my_url = $env['callbackurl'];//成功授权后的回调地址
$revokeoauth_url = $env['callbackurl2'];//取消授权时的回调地址
$app_info=$env['info'];//应用的附加数据

//----------------- 取得Token -----------------
$ReturnUrlQz=eReturnDomainSiteUrl();
if(stripos($my_url,$ReturnUrlQz)===FALSE)
	$my_url=$ReturnUrlQz.$my_url;
if(stripos($revokeoauth_url,$ReturnUrlQz)===FALSE)
	$revokeoauth_url=$ReturnUrlQz.$revokeoauth_url;


if(!isset($_SESSION['state']) || ($_REQUEST['state']<>$_SESSION['state']))
{
        printerror2('来自的链接不存在， [session]问题','../../../');
}
$o = new SinaOAuth( $appk , $app_secret );
$token=NULL;
if (isset($_REQUEST['code'])) {
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = $my_url;
	try {
		$pa = array('type'=>'code','keys'=>$keys);
                try{
		$token = $o->AccessToken($pa);
                }
                catch(EiException $e)
                {
                    $pa = array();
                    $pa['msg']= $e->getMessage();
                    $ecode=$e->getCode();
                    if(!!$ecode)
                        $pa['description']='错误号('. $ecode .')';
                    $pa['waittime'] = 15;
                    printerror('ExceptionSuspend','',1,$pa);
                }
		if(isset($token->error))
		{
                        $pa = array();
                        $pa['msg']= $token->error;
                        $pa['description']=$token->error_description;
                        $pa['waittime'] = 15;
                        printerror('ExceptionSuspend','',1,$pa);
		}
		$pa2 = array('access_token'=>$token['access_token']);
		$revokeoauthurl=$o->RevokeoauthURL($pa2);
	} catch (EiException $e) {
		die($e);
	}
}


$tinfo = NULL;
if ($token) {	
	$o->SaveOAuthToCookie($token);        
	$pti=array('access_token'=>$token['access_token']);
	$tinfo=$o->GetOpenIdInfo($pti);//array("openid","appkey","scope","create_at","expire_in","access_token")
	if(isset($tinfo->error))
	{
               $pa = array();
               $pa['msg']= $token->error;
               $pa['description']=$token->error_description;
               printerror('ExceptionSuspend','',1,$pa);
	}
	$_SESSION['e_token'][$cappid] = $o->FormatTokenInfo($tinfo);//格式化保存到SESSION
}
if(empty($tinfo)){
      $pa = array();
      $pa['msg']= '授权失败';
      $pa['description']='您没有许可,或者由于账号原因无法完成授权.';
      printerror('ExceptionSuspend','',1,$pa);
}
 $openid=$o->GetOpenID();
if(!trim($openid)||!trim($apptype))
{
        printerror2('来自的链接不存在，openid和apptype问题','../../../');
}
$_SESSION['openid']=$openid;
$_mcr = MemberConnect_CheckOpenid($cappid, $openid);
if (!$_mcr['id'])//已经绑定,不需要取详细信息
{
    $eiuinfo=$o->GetEiUserInfo($token['access_token'],$tinfo['openid']);
    if($eiuinfo)
    {
      $_SESSION['openname']=$eiuinfo['name'];//screen_name?
      //删除微博信息:
      unset($eiuinfo['status']);
       $traobj= $o->GetTranSrv();
      $sa = new SinaAction($appk,$app_secret,$traobj,$token['access_token'],$token['refresh_token'],$o);
       $_SESSION['openerinfo'] = $sa->GetTranSrv()->ConvertParam($eiuinfo,0, 1);//保存到eiuserinfo中,必须进行转换
    }
}
$_SESSION['openidkey']=MemberConnect_GetBindKey($cappid,$openid);
//处理登录
$ev=0;
if(isset($tinfo['create_at']) && isset($tinfo['expire_in']))
	$ev=intval($tinfo['create_at'])+intval($tinfo['expire_in']);
$scopev=NULL;
if(!empty($tinfo['scope'])) $scopev=$tinfo['scope'];

MemberConnect_DoLogin($cappid,$openid,$token['access_token'],$ev,$scopev,$token['refresh_token']);
db_close();
unset($empire);