<?php
define('EmpireCMSAdmin','1');
require('../../class/connect.php'); //引入数据库配置文件和公共函数文件
require('../../class/db_sql.php'); //引入数据库操作文件
$link=db_connect(); //连接MYSQL
$empire=new mysqlquery(); //声明数据库操作类
$editor=1; //声明目录层次

/* get login user info
$ecmsdodbdata       = getcvar('ecmsdodbdata',1);
$eloginlic          = getcvar('eloginlic',1);         //用户许可证书名称
$loginadminstyleid  = getcvar('loginadminstyleid',1); //风格ID
$loginecmsckpass    = getcvar('loginecmsckpass',1);   //密码加密
$loginlevel         = getcvar('loginlevel',1);        //组ID
$loginrnd           = getcvar('loginrnd',1);          //认证码加密
$loginuserid        = getcvar('loginuserid',1);       //用户ID
$loginusername      = getcvar('loginusername',1);     //用户名
$logintime          = getcvar('logintime',1);         //登陆时间UNIX时间戳
*/

$loginlevel  = getcvar('loginlevel',1);

if($loginlevel != 1){
    exit;
}

$message  = '';
$url      = '';

if($_POST){
    $url = $_POST['url'];
    if($url){
        $message = del($url);
    }
}

function del($url){
    // check if the file is html
    if(! endWith($url,'.html') && ! endWith($url,'htm')){
        return '只能删除 html 或者 htm 结尾的文件';
    }

    // get path
    $rootPath = $_SERVER['DOCUMENT_ROOT'];
    $_arr = explode('com',$url);
    $file = $rootPath.$_arr[1];

    // check if the file exist;

    if(! file_exists($file))
    {
        return "文件不存在";
    }

    if(false == unlink($file)){
        return "很抱歉，权限不够，请联系管理员";
    }

    return '文件已经被成功删除';
}

function endWith($haystack, $needle) {

    $length = strlen($needle);
    if($length == 0)
    {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}


require('template/index.temp.php'); //导入模板文件

db_close(); //关闭MYSQL链接
$empire=null; //注消操作类变量

?>
