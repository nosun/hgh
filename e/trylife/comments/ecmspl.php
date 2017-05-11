<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require_once(AbsLoadLang('pub/fun.php'));
require("../../data/dbcache/class.php");
require("../../data/dbcache/MemberLevel.php");
require("../../member/class/user.php");
require("../../pl/plfun.php");
require("../common/Dev/ecms-rd-common-functions.php");
require("words.php");
require("functions.php");
$link=db_connect();
$empire=new mysqlquery();
$enews=$_POST['enews'];

if(empty($enews))
{
	$enews=$_GET['enews'];
}
                                                
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];
require("hplfun.php");
if($enews=="DelPl_all")
{
	$plid		=	$_POST['plid']		?	$_POST['plid']		:	$_GET['plid'];
	$id			=	$_POST['id']		?	$_POST['id']		:	$_GET['id'];
	$bclassid	=	$_POST['bclassid']	?	$_POST['bclassid']	:	$_GET['bclassid'];
	$classid	=	$_POST['classid']	?	$_POST['classid']	:	$_GET['classid'];
	DelPl_all($plid,$id,$bclassid,$classid,$logininid,$loginin);
}
elseif($enews=="CheckPl_all")	//批量审核评论
{
	$plid		=	$_POST['plid'];
	$id			=	$_POST['id'];
	$bclassid	=	$_POST['bclassid'];
	$classid	=	$_POST['classid'];
	CheckPl_all($plid,$id,$bclassid,$classid,$logininid,$loginin);
}

elseif($enews=='DoGoodPl_all')//批量推荐/取消评论
{
	$plid		=	$_POST['plid'];
	$id			=	$_POST['id'];
	$bclassid	=	$_POST['bclassid'];
	$classid	=	$_POST['classid'];
	$isgood		=	$_POST['isgood'];
	DoGoodPl_all($plid,$id,$bclassid,$classid,$isgood,$logininid,$loginin);
}
elseif($enews=='DoFuncPl_one')//单条推荐/取消评论
{
	$plid		=	$_POST['plid'];
	$id		=       $_POST['id'];
	$bclassid	=	$_POST['bclassid'];
	$classid	=	$_POST['classid'];
	$method		=	$_POST['method'];
	$value		=	$_POST['value'];
        $restb          =       $_POST['restb'];
	DoFuncPl_one($plid,$id,$bclassid,$classid,$restb,$method,$value,$logininid,$loginin);
}
elseif($enews=='adminReplyComment')
{
    echo adminReplyComment($_POST);
}
elseif($enews=='adminEditComment')
{
	echo adminEditComment($_POST);
}
else
{
    echo 'OK';
	printerror("ErrorUrl","history.go(-1)");
}
db_close();
$empire=null;
?>