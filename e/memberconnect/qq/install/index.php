<?php
@set_time_limit(10000);
define('EmpireCMSAdmin','1');
//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);//E_ALL ^ E_NOTICE

require('../../../class/connect.php');
require('../../../class/db_sql.php');
require('../../../class/functions.php');
$link=db_connect();
$empire=new mysqlquery();

//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];
$ph1 = NULL;
if(!empty($_SERVER["HTTP_REFERER"])){
    $ph1 = strtolower(pathinfo(parse_url($_SERVER["HTTP_REFERER"],PHP_URL_PATH),PATHINFO_DIRNAME));
}
$allowreferer = array(
   strtolower('/e/'.  getcvar('pathn', 1, TRUE).'/adminstyle/'.$loginadminstyleid),
   strtolower( '/e/'.  getcvar('pathn', 1, TRUE).'/member'),
   strtolower( '/e/memberconnect/qq/install')
);
if(!defined('InEmpireCMS') || empty($ph1) || in_array($ph1,$allowreferer)===FALSE)
{
        echo '敬告:必须在后台框架内运行';
        exit();
}

//验证权限
CheckLevel($logininid,$loginin,$classid,"memberconnect");
//修改表的结构enewsmember_connect_app,enewsmember_connect：
$sqlv=$empire->query("show columns from {$ecms_config['db']['dbname']}.{$dbtbpre}enewsmember_connect_app");
$hascallbackurlfeild=FALSE;
while($r=$empire->fetch($sqlv))
{
	if($r['Field']=='callbackurl')
	{
		$hascallbackurlfeild=TRUE;
		break;
	}
}
if(!$hascallbackurlfeild)//增加新的字段
{
	$empire->query("ALTER TABLE `{$dbtbpre}enewsmember_connect_app` 
ADD COLUMN `callbackurl`  varchar(512) NULL AFTER `appsay`,
ADD COLUMN `callbackurl2`  varchar(512) NULL AFTER `callbackurl`,
ADD COLUMN `info`  varchar(1024) NULL AFTER `callbackurl2`,
ADD COLUMN `tranregexlist`  varchar(2048) NULL COMMENT '转换正则规则表' AFTER `info`,
ADD COLUMN `trankeywordslist`  varchar(2048) NULL COMMENT '转换关键词表' AFTER `tranregexlist`,
DROP INDEX `apptype` ,
ADD INDEX `apptype` (`apptype`) USING BTREE ,
ADD UNIQUE INDEX `appname` (`appname`) USING BTREE;");
	
	$empire->query("ALTER TABLE `{$dbtbpre}enewsmember_connect`
ADD COLUMN `token`  varchar(128) CHARACTER SET ascii COLLATE ascii_general_ci NULL AFTER `lasttime`,
ADD COLUMN `expired`  datetime NULL COMMENT 'access_token' AFTER `token`,
ADD COLUMN `scope`  varchar(128) NULL AFTER `expired`,
ADD COLUMN `bindname`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '第三方交互平台用户名' AFTER `scope`,
ADD COLUMN `rtoken`  varchar(128) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL COMMENT 'refresh_token' AFTER `bindname`,
ADD COLUMN `aid`  int NOT NULL COMMENT '应用的ID' AFTER `rtoken`,
DROP INDEX `openid` ,
ADD INDEX `openid` (`openid`, `token`) USING BTREE,
ADD INDEX `aid` (`aid`) USING BTREE;");
	
}
//修改表的结构结束---------------------------------------------
if($_GET['ecms']=="install")
{
	$doinstall=$_GET['doinstall'];
	if($doinstall=='install')//安装操作
	{
		include('install.php');
		$word='已安装完毕!';
	}
	elseif($doinstall=='uninstall')//卸载操作
	{
		include('uninstall.php');
		$word='已卸载完毕!';
	}
	echo"QQ互联交互插件 $word";
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>帝国CMS－QQ互联交互插件安装/卸载程序</title>
<link href="/e/<?=getcvar('pathn',1,TRUE)?>/adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<style>
a:link     { COLOR: #000000; TEXT-DECORATION: none }
a:visited   { COLOR: #000000 ; TEXT-DECORATION: none }
a:active   { COLOR: #000000 ; TEXT-DECORATION: underline }
a:hover    { COLOR: #000000 ; TEXT-DECORATION:underline }
.home_top { border-top:2px solid #4798ED; }
.home_path { background:#4798ED; padding-right:10px; color:#F0F0F0; font-size: 11px; }
td, th, caption { font-family:  "宋体"; font-size: 14px; color:#000000;  LINE-HEIGHT: 165%; }
.hrLine{MARGIN: 0px 0px; BORDER-BOTTOM: #807d76 1px dotted;}
</style>
<script type="text/javascript">
function CheckUpdate(obj){
	if(confirm('确认操作?'))
	{
		obj.updatebutton.disabled=true;
		return true;
	}
	return false;
}
</script>
</head>
<body>
<form method="GET" action="index.php" name="formupdate" onsubmit="return CheckUpdate(document.formupdate);">
  <br>
  <br>
  <br>
  <table width="500" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#4FB4DE">
    <tr> 
      <td height="30" colspan="2"> <div align="center"><strong><font color="#FFFFFF">帝国CMS－QQ互联交互插件安装/卸载程序</font></strong></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="141" height="36"> 
        <div align="right">选择操作：</div></td>
      <td width="344"> <input name="doinstall" type="radio" value="install" checked>
        安装 
        <input type="radio" name="doinstall" value="uninstall">
        卸载</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="30"> 
        <div align="center"></div></td>
      <td> <input type=submit name=updatebutton value="提交操作"> <input name="ecms" type="hidden" id="ecms" value="install"> 
      </td>
    </tr>
  </table>
  </form>
  </body>
  </html>
<?php
db_close();
$empire=null;
?>