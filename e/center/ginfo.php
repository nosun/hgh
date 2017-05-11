<?php
define('EmpireCMSAdmin','1');
die;
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
$link=db_connect();
$empire=new mysqlquery();
//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];
db_close();
$empire=null;
@header('Content-Type: text/html; charset=gb2312');
@include('../class/EmpireCMS_version.php');
$pd="?product=empirecms&usechar=".$ecms_config['sets']['pagechar']."&doupdate=".EmpireCMS_UPDATE."&ver=".EmpireCMS_VERSION."&lasttime=".EmpireCMS_LASTTIME."&domain=".$_SERVER['HTTP_HOST']."&ip=".$_SERVER['REMOTE_ADDR'];

?>
