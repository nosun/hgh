<?php
if(!defined('InEmpireCMS'))
{
        exit();
}

//------ 心情插件安装 ------

//建立心情记录表
$empire->query(SetCreateTable("CREATE TABLE `{$dbtbpre}ecmsextend_mood` (
  `classid` smallint(6) NOT NULL default '0',
  `id` int(11) NOT NULL default '0',
  `mood1` int(11) NOT NULL default '0',
  `mood2` int(11) NOT NULL default '0',
  `mood3` int(11) NOT NULL default '0',
  `mood4` int(11) NOT NULL default '0',
  `mood5` int(11) NOT NULL default '0',
  `mood6` int(11) NOT NULL default '0',
  `mood7` int(11) NOT NULL default '0',
  `mood8` int(11) NOT NULL default '0',
  `mood9` int(11) NOT NULL default '0',
  `mood10` int(11) NOT NULL default '0',
  `mood11` int(11) NOT NULL default '0',
  `mood12` int(11) NOT NULL default '0',
  KEY `classid` (`classid`,`id`)
) TYPE=MyISAM;",$phome_db_dbchar));

?>