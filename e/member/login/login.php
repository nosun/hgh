<?php
require('../../class/connect.php');
require("../../class/db_sql.php");
require('../../member/class/user.php');
$link=db_connect();
$empire=new mysqlquery();

eCheckCloseMods('member');//关闭模块
if($ecms_config['member']['loginurl'])
{
	echo"<script>window.close();</script>";
	//Header("Location:".$ecms_config['member']['loginurl']);
	exit();
}
//导入模板
require(ECMS_PATH.'e/template/member/loginopen.php');
db_close();
$empire=null;
?>