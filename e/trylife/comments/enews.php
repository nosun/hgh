<?php
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../data/dbcache/class.php");
require("../../class/functions.php");
require("../../class/userfun.php");
//require("../../class/q_functions.php");
require("../../data/dbcache/MemberLevel.php");
require("../../member/class/user.php");
require("../../pl/plfun.php");
require("../common/Dev/ecms-rd-common-functions.php");
require("functions.php");
require("words.php");
require_once(AbsLoadLang('pub/fun.php'));
$link=db_connect();
$empire=new mysqlquery();
//
$enews=$_POST['enews'];
if(empty($enews)){
    $enews=$_GET['enews'];
}
if($enews=='get_comments')
{
	$classid=(int)$_GET['classid'];
	$id=(int)$_GET['id'];
	$page=(int)$_GET['page'];
	echo get_comments($classid,$id,$page);
}
elseif($enews=="ajax_AddPl")//增加评论
{

	$_POST		=	$_GET;
	//$_POST=ecms_utf82gbk($_POST);//trylife 2011-09-01
	$username	=	$_POST['username'];
	$password	=	$_POST['password'];
	$saytext	=	$_POST['saytext'];
	$id		=       (int)$_POST['comment_id'];
	$classid	=	(int)$_POST['comment_classid'];
	$repid		=	(int)$_POST['repid'];
	$nomember	=	(int)$_POST['nomember'];
	$key		=	$_POST['key'];
	echo ajax_AddPl($username,$password,$nomember,$key,$saytext,$id,$classid,$repid,$_POST);
}
elseif($enews=='refresh_pltemp_option')
{
	$lur=is_login();
	$logininid=$lur['userid'];
	$loginin=$lur['username'];
	$loginrnd=$lur['rnd'];
	$loginlevel=$lur['groupid'];
	$loginadminstyleid=$lur['adminstyleid'];
	echo refresh_pltemp_option();
}
elseif($enews=='update_comments_set')
{
	$lur=is_login();
	$logininid=$lur['userid'];
	$loginin=$lur['username'];
	$loginrnd=$lur['rnd'];
	$loginlevel=$lur['groupid'];
	$loginadminstyleid=$lur['adminstyleid'];
	echo update_comments_set();
}
elseif($enews=='add_comment')
{
	
}

db_close();
$empire=null;
?>