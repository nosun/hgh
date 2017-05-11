<?php
if(!defined('InEmpireCMS'))
{
        exit();
}
include_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'memberconnect'.DIRECTORY_SEPARATOR.'eibase.php' );
//------ 新浪交互插件卸载 ------

//删除新浪交互应用记录
$dirnodes = explode(DIRECTORY_SEPARATOR , __FILE__);
$apptype = $dirnodes[count($dirnodes)-3];
if(empty($apptype))$apptype = "sina";
$empire->query("delete from {$dbtbpre}enewsmember_connect where apptype='{$apptype}';");
$suc=$empire->query("delete from {$dbtbpre}enewsmember_connect_app where apptype='{$apptype}';");
$num=$empire->num("select id from {$dbtbpre}enewsmember_connect_app");
if($num==0)//如果清空了，就删除自定义变量
{
	$empire->query("delete FROM `{$dbtbpre}enewspubvar` where myvar='EITypes'");
}
if($suc)
{
	EIService::GenerateApps(TRUE);
}
GetConfig();

?>