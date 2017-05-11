<?php
require("../../class/connect.php");
require("../class/user.php");
require("../../class/db_sql.php");
$link=db_connect();
$empire=new mysqlquery();

eCheckCloseMods('member');//关闭模块
//关闭
if($public_r[register_ok])
{
	printerror("CloseRegister","history.go(-1)",1);
}
//验证时间段允许操作
eCheckTimeCloseDo('reg');
//验证IP
eCheckAccessDoIp('register');
$tobind=(int)$_REQUEST['tobind'];
$eiusername=$_REQUEST['eiusername'];//LGM 增加[2014年2月16日14:44]
//转向注册
if(!empty($ecms_config['member']['registerurl']))
{
	$purl=$ecms_config['member']['registerurl'];//LGM 增加[2014年2月17日21:32]
	if(strrpos($purl,'?')>0)
		$purl.='&tobind='.$tobind.'&eiusername='.urlencode($eiusername);
	else 
		$purl.='?tobind='.$tobind.'&eiusername='.urlencode($eiusername);
	Header("Location:".$purl);
	exit();
}
//已经登陆不能注册
if(getcvar('mluserid',0,TRUE))//LGM修改,把用户ID加密[2014年3月16日21:43]
{
	printerror("LoginToRegister","history.go(-1)",1);
}
$sql=$empire->query("select groupid,groupname from {$dbtbpre}enewsmembergroup where canreg=1 order by level,groupid");
//导入模板
require(ECMS_PATH.'e/template/member/ChangeRegister.php');
db_close();
$empire=null;
?>