<?php
require('../../class/connect.php');
require("../../class/db_sql.php");
require('../../member/class/user.php');
$link=db_connect();
$empire=new mysqlquery();

eCheckCloseMods('member');//关闭模块
$tobind=(int)$_GET['tobind'];
if ($ecms_config['member']['loginurl'])
{
    if (!empty($_SERVER['QUERY_STRING']))
        Header("Location:" . $ecms_config['member']['loginurl'] . '?' . $_SERVER['QUERY_STRING']);
    else
        Header("Location:" . $ecms_config['member']['loginurl']);
    exit();
}
$defname = $_REQUEST['defname'];
//导入模板
require(ECMS_PATH.'e/template/member/login.php');
db_close();
$empire=null;
?>
