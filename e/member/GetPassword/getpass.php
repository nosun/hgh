<?php
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/q_functions.php");
require("../class/user.php");
require("../class/member_actfun.php");
$link=db_connect();
$empire=new mysqlquery();

eCheckCloseMods('member');//关闭模块
if(!$public_r['opengetpass'])
{
	printerror('CloseGetPassword','',1);
}
$r=CheckGetPassword($_GET,1);
$username=$r['username'];
//导入模板
require(ECMS_PATH.'e/template/member/getpass.php');
db_close();
$empire=null;
?>