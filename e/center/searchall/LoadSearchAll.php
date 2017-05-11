<?php
define ( 'EmpireCMSAdmin', '1' );
require ("../../class/connect.php");
require ("../../class/db_sql.php");
require ("../../class/functions.php");
$link = db_connect ();
$empire = new mysqlquery ();

// 验证用户
$lur = is_login ();
$logininid = $lur ['userid'];
$loginin = $lur ['username'];
$loginrnd = $lur ['rnd'];
$loginlevel = $lur ['groupid'];
$loginadminstyleid = $lur ['adminstyleid'];
// 验证权限
CheckLevel ( $logininid, $loginin, $classid, "searchall" );
require ("../../data/dbcache/class.php");
require_once AbsLoadLang('pub/fun.php');
require ('../../class/schallfun.php');
// 编码
if ($ecms_config ['sets'] ['pagechar'] != 'gb2312') {
	include_once (ECMS_PATH . 'e/class/doiconv.php');
	$iconv = new Chinese ();//LGM修改,废弃 $editor 的低级用法 [2014年3月12日21:09]  
	$char = $ecms_config ['sets'] ['pagechar'] == 'big5' ? 'BIG5' : 'UTF8';
	$targetchar = 'GB2312';
}
$lid = $_GET ['lid'];
$start = $_GET ['start'];
$addgethtmlpath = "../";
LoadSearchAll ( $lid, $start, $logininid, $loginin );
db_close ();
$empire = null;
?>