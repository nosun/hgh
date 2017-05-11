<?php
header('Content-Type:text/html; charset=utf-8');
require("../../class/connect.php");
require("../../class/db_sql.php");
$link=db_connect();
$empire=new mysqlquery();
$id=(int)$_GET['id'];

$r=$empire->fetch1('select diggtop,diggdown from hgh_ecms_article where id='.$id.' limit 1');
$up=$r['diggtop'];
$down=$r['diggdown'];
db_close();
$empire=null;
echo $up."-".$down;
?>