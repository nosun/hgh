<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
global $app_secret,$cappid,$appk,$my_url;
if(!isset($_SESSION))session_start();
require(dirname(__FILE__).DIRECTORY_SEPARATOR.'class.php');
$o = new SinaOAuth( $appk , $app_secret,NULL,NULL,$my_url );
$_SESSION['state']=md5(uniqid(rand(),TRUE));//state参数用于防止CSRF攻击，成功授权后回调时会原样带回
$ipa=array('scope'=>'all','state'=>$_SESSION['state']);
if($forcelogin) $ipa['forcelogin'] = 1;
$gotologinurl = $o->AuthorizeURL($ipa);
//var_dump($_cookie['phpsessid']);die;
//var_dump($gotologinurl);die;
//var_dump($_SESSION['cappid']);die;
header("Location:$gotologinurl");//直接转跳