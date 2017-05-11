<?php
if(!defined('InEmpireCMS'))
{
        exit();
}

//------ 心情插件卸载 ------

//删除心情记录表
$empire->query("DROP TABLE IF EXISTS `{$dbtbpre}ecmsextend_mood`;");

?>