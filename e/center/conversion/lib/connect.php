<?php
error_reporting(E_ALL ^ E_NOTICE);

require_once 'config.php';


function db_connect(){
	global $config;
	$dblink=do_dbconnect($config['db']['dbserver'],$config['db']['dbport'],$config['db']['dbusername'],$config['db']['dbpassword'],$config['db']['dbname']);
	return $dblink;
}

function do_dbconnect($dbhost,$dbport,$dbusername,$dbpassword,$dbname){
	global $config;
	$dblocalhost=$dbhost;
	//端口
	if($dbport)
	{
		$dblocalhost.=':'.$dbport;
	}
	$dblink=mysql_connect($dblocalhost,$dbusername,$dbpassword);
	if(!$dblink)
	{
		echo"Cann't connect to DB!";
		exit();
	}
	//编码
		$q='';
		if($config['db']['setchar'])
		{
                    $q='character_set_connection='.$config['db']['setchar'].',character_set_results='.$config['db']['setchar'].',character_set_client=binary';
		}
		if($config['db']['dbver']>='5.0')
		{
                    $q.=(empty($q)?'':',').'sql_mode=\'\'';
		}
		if($q)
		{
                    mysql_query('SET '.$q,$dblink);
		}
                mysql_select_db($dbname,$dblink);
                return $dblink;
}

function return_dblink($query){
        $dblink=$GLOBALS['link'];
	return $dblink;
}

//设置编码
function DoSetDbChar($dbchar){
	global $link;
	if($dbchar&&$dbchar!='auto')
	{
		@mysql_query('set character_set_connection='.$dbchar.',character_set_results='.$dbchar.',character_set_client=binary;',$link);
	}
}

function db_close(){
	global $link;
	if($link)
	{
		@mysql_close($link);
	}
}

//字符截取函数
function sub($string,$start=0,$length,$mode=false,$dot='',$rephtml=0){
	global $ecms_config;
	$strlen=strlen($string);
	if($strlen<=$length)
	{
		return $string;
	}

	if($rephtml==0)
	{
		$string = str_replace(array('&nbsp;','&amp;','&quot;','&lt;','&gt;','&#039;'), array(' ','&','"','<','>',"'"), $string);
	}

	$strcut = '';
	if(strtolower($ecms_config['sets']['pagechar']) == 'utf-8') {

		$n = $tn = $noc = 0;
		while($n < $strlen) {

			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t < 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}

			if($noc >= $length) {
				break;
			}

		}
		if($noc > $length) {
			$n -= $tn;
		}

		$strcut = substr($string, 0, $n);

	} else {
		for($i = 0; $i < $length; $i++) {
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}

	if($rephtml==0)
	{
		$strcut = str_replace(array('&','"','<','>',"'"), array('&amp;','&quot;','&lt;','&gt;','&#039;'), $strcut);
	}

	return $strcut.$dot;
}

//截取字数
function esub($string,$length,$dot='',$rephtml=0){
	return sub($string,0,$length,false,$dot,$rephtml);
}
?>