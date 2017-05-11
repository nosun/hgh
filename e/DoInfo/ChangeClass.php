<?php
require("../class/connect.php");
require("../class/db_sql.php");
require("../data/dbcache/class.php");
require("../member/class/user.php");
require("../class/q_functions.php");
require_once(AbsLoadLang('pub/fun.php'));
$link=db_connect();
$empire=new mysqlquery();
//关闭投稿
if($public_r['addnews_ok'])
{
	printerror("CloseQAdd","",1);
}
//验证本时间允许操作
eCheckTimeCloseDo('info');
//验证IP
eCheckAccessDoIp('postinfo');
$mid=(int)$_GET['mid'];
if(empty($mid))
{
	printerror("ErrorUrl","",1);
}
$mr=$empire->fetch1("select mid,qenter,qmname,tbname from {$dbtbpre}enewsmod where mid='$mid'");
if(!$mr['mid']||!$mr['qenter']||InfoIsInTable($mr['tbname']))
{
	printerror("ErrorUrl","",1);
}
$muserid=(int)getcvar('mluserid',0,TRUE);//LGM修改,把用户ID加密[2014年3月16日21:43]
$musername=RepPostVar(getcvar('mlusername'));
if(empty($musername))
{
	$musername="游客";
}
$classjs=$public_r['newsurl']."d/js/js/addinfo".$mid.".js";

//新增修改（红星）
require("../member/custom/memberCenter.php");

//导入模板
require(ECMS_PATH.'e/template/DoInfo/ChangeClass.php');
db_close();
$empire=null;
?>