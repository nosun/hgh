<?php
/**
 * 用于用Get/Post 获取缩短的网址,返回JSON格式的Array
 */
include_once("../class/connect.php");
require("../class/db_sql.php");
require("../class/q_functions.php");
$link=db_connect();
$empire=new mysqlquery();
include_once(ECMS_PATH . 'e' . DIRECTORY_SEPARATOR . 'memberconnect' . DIRECTORY_SEPARATOR . 'eibase.php' );
header('Content-type: text/json;charset=utf-8');
$url = $_REQUEST['url'];
if(!empty($url)) {
    $p = array('url'=>$url);
   $r = EIService::DoShortUrl($p);   
   if(empty($r)) $r = array('error'=>1,'l'=>$url);
   $put = json_encode($r);
   echo $put;
}
db_close();
unset($empire);
