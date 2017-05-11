<?php
require_once('../class/connect.php');
require_once('../class/db_sql.php');
require_once('../class/q_functions.php');
require_once('../member/class/user.php');

eCheckCloseMods('member');//关闭模块
eCheckCloseMods('mconnect');//关闭模块
$link = db_connect();
$empire = new mysqlquery();
eCheckCloseMemberConnect();//验证开启的接口
if (empty($_SESSION)) session_start();
require_once('memberconnectfun.php');
require_once('eibase.php');
//接口
$cappid = intval($_GET['id']);
$forcelogin = intval($_GET['forcelogin']);//是否强制弹出登录对话框
$env = EIService::GetENV($cappid);

if (!$env) {
    printerror2('请选择登录方式', '../../../');
}
$apptype = $env['type'];
$_SESSION['cappid'] = $cappid;      //保存到SESSION
$_SESSION['apptype'] = $apptype;
$appk = $env['appkey'];     //应用的APPID
$app_secret = $env['appsecret'];        //应用的APPKEY
$my_url = $env['callbackurl'];      //成功授权后的回调地址
$revokeoauth_url = $env['callbackurl2'];        //取消授权时的回调地址
$app_info = $env['info'];       //应用的附加数据
$ReturnUrlQz = eReturnDomainSiteUrl();


if (stripos($my_url, $ReturnUrlQz) === FALSE)
    $my_url = $ReturnUrlQz . $my_url;
if (stripos($revokeoauth_url, $ReturnUrlQz) === FALSE)
    $revokeoauth_url = $ReturnUrlQz . $revokeoauth_url;


//取得回调网址,保存为ECOOKIE['returnurl']
$returnurl = $_REQUEST['returnurl'];//第一优先级,在参数中指定

if (empty($returnurl)) $returnurl = GetValueFromCookie('returnurl', 0, TRUE);       //加密的,第二优先级,在COOKIE中存储
if (empty($returnurl)) $returnurl = $_SERVER['HTTP_REFERER'];//如果都没有,则默认为上个页面
if (!empty($returnurl)) {
    if (stripos($returnurl, GetEPath() . 'member/login/') !== FALSE || stripos($returnurl, GetEPath() . 'member/register/') !== FALSE || stripos($returnurl, GetEPath() . 'memberconnect/') !== FALSE)//如果在登录页或者注册页则转跳到会员信息页面
    {
        $returnurl = GetEPath() . 'member/cp/';
    }
} else
    $returnurl = GetEPath() . 'member/cp/';         //如果为空,会导致完成登录后出错


$logincookie = 0;
esetcookie('returnurl', $returnurl, 0, 0, TRUE, NULL, 0);//10分钟time()+600,加密的
$file = $apptype . DIRECTORY_SEPARATOR . 'to_login.php';

@include($file);
db_close();
$empire = null;
?>