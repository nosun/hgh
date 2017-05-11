<?php
if(!defined('InEmpireCMS'))
{
        exit();
}
//------ QQ互联交互插件安装 ------
$dirnodes = explode(DIRECTORY_SEPARATOR , __FILE__);
$apptype = $dirnodes[count($dirnodes)-3];
if(empty($apptype))$apptype = "qq";
$appnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsmember_connect_app where apptype='{$apptype}'");
if(!$appnum)
{
	//增加QQ互联交互应用记录
	$empire->query("REPLACE INTO `{$dbtbpre}enewsmember_connect_app`(`id`,`apptype`,`appname`,`appid`,`appkey`,`isclose`,`myorder`,`qappname`,`appsay`,`callbackurl`,`callbackurl2`,`tranregexlist`) VALUES (NULL,'{$apptype}','QQ互联','12345678','123456789',0,0,'QQ','QQ互联','请填写授权回调网址','请填写取消授权回调网址','\'/\&nbsp;/\'=>\' \',\'/\<br\s*\/\>/i\'=>\' \',\'/\[~e\.([\w\x{4e00}-\x{9fa5}]+?)~\]/u\'=>\'[\1]\'');");
	GetConfig();
}
else 
{
	printerror2('已经安装过','',1);
}
?>