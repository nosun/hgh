<?php

define("QQXROOT",dirname(__FILE__).DIRECTORY_SEPARATOR);
define("QQXCLASS_PATH",QQXROOT."class".DIRECTORY_SEPARATOR);

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'eibase.php');

require_once(QQXCLASS_PATH.'QQTranSrv.class.php');
require_once(QQXCLASS_PATH.'QQOauth.class.php');
require_once(QQXCLASS_PATH.'QQAction.class.php');
