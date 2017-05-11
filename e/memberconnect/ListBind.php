<?php
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/q_functions.php");
require("../member/class/user.php");
$link=db_connect();
$empire=new mysqlquery();
eCheckCloseMods('member');//关闭模块
eCheckCloseMods('mconnect');//关闭模块
//是否登陆
$user=islogin();
$r=ReturnUserInfo($user['userid']);
//新增修改（红星）
require(ECMS_PATH."e/member/custom/memberCenter.php");
$query="select * from {$dbtbpre}enewsmember_connect_app where isclose=0 order by myorder,id";
$sql=$empire->query($query);
//导入模板
require(ECMS_PATH.'e/template/memberconnect/ListBind.php');
db_close();
unset($empire);
?>