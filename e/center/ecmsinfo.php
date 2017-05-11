<?php
define('EmpireCMSAdmin','1');
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
require_once(AbsLoadLang('pub/fun.php'));
require("../class/delpath.php");
require("../class/copypath.php");
require("../class/t_functions.php");
require("../data/dbcache/class.php");
require("../data/dbcache/MemberLevel.php");
$link=db_connect();
$empire=new mysqlquery();
$enews=$_POST['enews'];
if(empty($enews))
{
	$enews=$_GET['enews'];
}
//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];
$incftp=0;
if($public_r['phpmode'])
{
	include("../class/ftp.php");
	$incftp=1;
}
//防采集
if($public_r['opennotcj'])
{
	@include("../data/dbcache/notcj.php");
}
//会员
require("../member/class/user.php");
require("../class/hinfofun.php");
if($enews=="AddNews")//增加信息
{
	$navtheid=(int)$_POST['filepass'];
	AddNews($_POST,$logininid,$loginin);
}
elseif($enews=="EditNews")//修改信息
{
	$navtheid=(int)$_POST['id'];
	EditNews($_POST,$logininid,$loginin);
}
elseif($enews=="EditInfoSimple")//修改信息(快速)
{
	$navtheid=(int)$_POST['id'];
	EditInfoSimple($_POST,$logininid,$loginin);
}
elseif($enews=="DelNews")//删除信息
{
	$id=$_GET['id'];
	$classid=$_GET['classid'];
	$bclassid=$_GET['bclassid'];
	DelNews($id,$classid,$logininid,$loginin);
}
elseif($enews=="DelNews_all")//批量删除信息
{
	$id=$_POST['id'];
	$classid=$_POST['classid'];
	$bclassid=$_POST['bclassid'];
	$ecms=$_POST['ecmscheck']?2:0;
	DelNews_all($id,$classid,$logininid,$loginin,$ecms);
}
elseif($enews=="EditMoreInfoTime")//批量修改信息时间
{
	EditMoreInfoTime($_POST,$logininid,$loginin);
}
elseif($enews=="DelInfoDoc_all")//删除归档
{
	$id=$_POST['id'];
	$classid=$_POST['classid'];
	$bclassid=$_POST['bclassid'];
	DelNews_all($id,$classid,$logininid,$loginin,1);
}
elseif($enews=='AddInfoToReHtml')//刷新页面
{
	AddInfoToReHtml($_GET['classid'],$_GET['dore']);
}
elseif($enews=="TopNews_all")//信息置顶
{
	$bclassid=$_POST['bclassid'];
	$classid=$_POST['classid'];
	$id=$_POST['id'];
	$istop=$_POST['istop'];
	TopNews_all($classid,$id,$istop,$logininid,$loginin);
}
elseif($enews=="CheckNews_all")//审核信息
{
	$bclassid=$_POST['bclassid'];
	$classid=$_POST['classid'];
	$id=$_POST['id'];
	CheckNews_all($classid,$id,$logininid,$loginin);
}
elseif($enews=="NoCheckNews_all")//取消审核信息
{
	$bclassid=$_POST['bclassid'];
	$classid=$_POST['classid'];
	$id=$_POST['id'];
	NoCheckNews_all($classid,$id,$logininid,$loginin);
}
elseif($enews=="MoveNews_all")//移动信息
{
	$bclassid=$_POST['bclassid'];
	$classid=$_POST['classid'];
	$id=$_POST['id'];
	$to_classid=$_POST['to_classid'];
	MoveNews_all($classid,$id,$to_classid,$logininid,$loginin);
}
elseif($enews=="CopyNews_all")//复制信息
{
	$bclassid=$_POST['bclassid'];
	$classid=$_POST['classid'];
	$id=$_POST['id'];
	$to_classid=$_POST['to_classid'];
	CopyNews_all($classid,$id,$to_classid,$logininid,$loginin);
}
elseif($enews=="MoveClassNews")//批量移动信息
{
	$add=$_POST['add'];
	MoveClassNews($add,$logininid,$loginin);
}
elseif($enews=="GoodInfo_all")//批量推荐/头条信息
{
	$classid=$_POST['classid'];
	$id=$_POST['id'];
	$doing=$_POST['doing'];
	$isgood=empty($doing)?$_POST['isgood']:$_POST['firsttitle'];
	GoodInfo_all($classid,$id,$isgood,$doing,$logininid,$loginin);
}
elseif($enews=="SetAllCheckInfo")//本栏目信息全部审核
{
	$classid=$_GET['classid'];
	$bclassid=$_GET['bclassid'];
	SetAllCheckInfo($bclassid,$classid,$logininid,$loginin);
}
elseif($enews=="DoWfInfo")//签发信息
{
	DoWfInfo($_POST,$logininid,$loginin);
}
elseif($enews=="DelInfoData")//删除信息页面
{
	$start=$_GET['start'];
	$classid=$_GET['classid'];
	$from=$_GET['from'];
	$retype=$_GET['retype'];
	$startday=$_GET['startday'];
	$endday=$_GET['endday'];
	$startid=$_GET['startid'];
	$endid=$_GET['endid'];
	$tbname=$_GET['tbname'];
	DelInfoData($start,$classid,$from,$retype,$startday,$endday,$startid,$endid,$tbname,$_GET,$logininid,$loginin);
}
elseif($enews=="InfoToDoc")//归档信息
{
	if($_GET['ecmsdoc']==1)//栏目
	{
		InfoToDoc_class($_GET,$logininid,$loginin);
	}
	elseif($_GET['ecmsdoc']==2)//条件
	{
		InfoToDoc($_GET,$logininid,$loginin);
	}
	else//信息
	{
		InfoToDoc_info($_POST,$logininid,$loginin);
	}
}
elseif($enews=="DoInfoAndSendNotice")//处理信息并通知
{
	$doing=(int)$_POST['doing'];
	$adddatar=$_POST;
	if($doing==1)//删除
	{
		$enews='DelNews';
		DelNews($adddatar['id'],$adddatar['classid'],$logininid,$loginin);
	}
	elseif($doing==2)//审核通过
	{
		$enews='CheckNews_all';
		$doid[0]=$adddatar['id'];
		CheckNews_all($adddatar['classid'],$doid,$logininid,$loginin);
	}
	elseif($doing==3)//取消审核
	{
		$enews='NoCheckNews_all';
		$doid[0]=$adddatar['id'];
		NoCheckNews_all($adddatar['classid'],$doid,$logininid,$loginin);
	}
	elseif($doing==4)//转移
	{
		$enews='MoveNews_all';
		$doid[0]=$adddatar['id'];
		MoveNews_all($adddatar['classid'],$doid,$adddatar['to_classid'],$logininid,$loginin);
	}
}
else
{
	printerror("ErrorUrl","history.go(-1)");
}
db_close();
$empire=null;
?>