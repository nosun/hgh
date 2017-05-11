<?php
require("../../class/connect.php");
require("../../class/q_functions.php");
require("../../class/db_sql.php");
require("../class/user.php");
$link=db_connect();
$empire=new mysqlquery();

eCheckCloseMods('member');//关闭模块
$user=islogin();
$r=ReturnUserInfo($user['userid']);
$UserInitial= FALSE;        
if(!empty($r) && !empty($_REQUEST['oldpwd']))
{
    if($r['kpassword']==$_REQUEST['oldpwd'])
    {
        $UserInitial= TRUE; 
        esetcookie('UserInitial', strval($user['userid']).'@'.time(), 0, 0, TRUE);      
    }
    else
    {
        //弹出对话框
        printerror('{:E,20:}您已经初始化了账号,不能再一次操作.', '/e/member/cp/',1);
    }
}
//新增修改（红星）
require("../custom/memberCenter.php");

//导入模板
require(ECMS_PATH.'e/template/member/EditSafeInfo.php');
db_close();
$empire=null;
?>