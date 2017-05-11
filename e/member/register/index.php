<?php
require_once("../../class/connect.php");
require_once("../../class/db_sql.php");
require_once("../class/user.php");
require_once("../class/member_registerfun.php");
$link=db_connect();
$empire=new mysqlquery();
eCheckCloseMods('member');//关闭模块
//关闭

if($public_r['register_ok'])
{
	printerror("CloseRegister","history.go(-1)",1);
}
//验证时间段允许操作
eCheckTimeCloseDo('reg');
//验证IP
eCheckAccessDoIp('register');

$tobind=(int)$_GET['tobind'];
$eiusername=$_GET['eiusername'];//默认的用户名 LGM 增加[2014年2月16日14:44],不需要解码
$randompwd=(int)$_REQUEST['randompwd'];//是否为随机产生密码,提交临时用户注册资料
$saytext = '';//个人说明
$scenery = '';//图像地址
$blogurl = '';//个人网址
$location = '';
//填充$addr:
if(!isset($addr))     
    $addr = array();
$ecmsfirstpost = 0;//允许填充

$groupid=(int)$_GET['groupid'];
$groupid=$groupid?$groupid:eReturnMemberDefGroupid();
$addr['groupid'] = $groupid;
$mformr=$empire->fetch1("select canaddf,mustenter from {$dbtbpre}enewsmemberform where fid=$groupid",MYSQL_ASSOC);
$addfs = array();//显示的字段
$mustfs = array();//必填字段
if (!empty($mformr))
{
    $gfields = trim($mformr['canaddf'], " ,\t\r\n");
    if (!empty($gfields))
    {
        $addfs = explode(',', $gfields);
    }
   $gfields = trim($mformr['mustenter'], " ,\t\r\n"); 
    if (!empty($gfields))
    {
        $mustfs = explode(',', $gfields);
    }   
}
if(!isset($_SESSION))    session_start();

if(empty($eiusername) && isset($_SESSION['openname']))
    $eiusername = $_SESSION['openname'];
if (isset($_SESSION['openerinfo']))//openerinfo
{
    if ( in_array('saytext', $addfs) && (!empty($_SESSION['openerinfo']['介绍']) || !empty($_SESSION['openerinfo']['说明'])))
    {
        $saytext = $_SESSION['openerinfo']['说明'];
        if(empty($saytext)) $saytext =  $_SESSION['openerinfo']['介绍'];
        $addr['saytext'] = addslashes($saytext);
    }//$_SESSION['openerinfo']已经被转换
    if (isset($_SESSION['openerinfo']['头像']) && in_array('userpic', $addfs))
    {
        $scenery = $_SESSION['openerinfo']['头像'];
        $addr['userpic'] = addslashes($scenery);
    }
    if (isset($_SESSION['openerinfo']['url']) && in_array('homepage', $addfs))
    {
        $blogurl = $_SESSION['openerinfo']['url'];
        $addr['homepage'] = addslashes($blogurl);
    }
    if (isset($_SESSION['openerinfo']['地区']) && in_array('address', $addfs))
    {
        $location = $_SESSION['openerinfo']['地区'];
        $addr['address'] = addslashes($location);
    }
}
$querya = $_GET;

if($querya==NULL) $querya = array();//LGM修改,加入绑定与建议用户名
if(!empty($eiusername) && $tobind) $querya['eiusername']=$eiusername;
if($randompwd) $querya['randompwd']=$randompwd;
//转向注册

if(!empty($ecms_config['member']['registerurl']))
{  
	$turl=$ecms_config['member']['registerurl'];
        if(count($querya)>0) 
        {
            if(strpos($turl,'?',3)>0) $turl.='&'.http_build_query($querya);
            else $turl.='?'.http_build_query($querya);
        }
        Header('HTTP/1.1 303 See Other');
	Header("Location:".$turl);
	exit();
}
//已经登陆不能注册
if(getcvar('mluserid',0,TRUE))//LGM修改,把用户ID加密[2014年3月16日21:43]
{
	printerror("LoginToRegister","history.go(-1)",1);
}
if(!empty($ecms_config['member']['changeregisterurl'])&&!$_GET['groupid'])
{
	$changeregisterurl=$ecms_config['member']['changeregisterurl'];
        if(count($querya)>0) 
        {
            if(strpos($changeregisterurl,'?',3)>0) $changeregisterurl.='&'.http_build_query($querya);
            else $changeregisterurl.='?'.http_build_query($querya);
        }
	Header("Location:".$changeregisterurl);
	exit();
}

if($randompwd)//自动注册
{  
    global $ecms_config;
   $apptype=$_SESSION['apptype'];
    $openid=$_SESSION['openid'];  
    $OriginalName = $eiusername;
    if(empty($eiusername))  $eiusername = $apptype.strval($openid);
    else
    {
        $eiusername = RepPostVar($eiusername);
    }
    if(empty($eiusername))  $eiusername = $apptype.strval($openid);
    $defaultsuffix = array(date('Y'),'1','2','ghg','1949','1976','1893','gcd','mzd','1226','marx','china','red','good','love','sum','wrmfw');
    //会员名检验:
    $pr=$empire->fetch1("select min_userlen,max_userlen,regclosewords from {$dbtbpre}enewspublic limit 1",MYSQL_ASSOC);
    if(!empty($pr))
    {
        $mnlen = strlen($eiusername);
        $maxlen= intval($pr['max_userlen']);
        $minlen= intval($pr['min_userlen']);   
        if($mnlen>$maxlen)
        {
            do
            {
                $eiusername = esub($eiusername,iconv_strlen($eiusername,$ecms_config['sets']['pagechar'])-1);
                $mnlen = strlen($eiusername);
            }
            while($mnlen>$maxlen);            
        }
        elseif($mnlen<$minlen)
        {
            if($minlen-$mnlen < 3)
                $eiusername .= 'hgh';
            else
            {
               $eiusername .= 'szhgh'; 
            }
            $mnlen = strlen($eiusername);
            if($mnlen<$minlen)
            {
               $eiusername .= 'shzy';  
            }
            $mnlen = strlen($eiusername);
        }
    }
	
    //判断会员名是否存在 :
    $isexist = GetUserID($eiusername);
    if($isexist!=0 && $eiusername!=$apptype.strval($openid))
    {
        foreach ($defaultsuffix as $mysuffix)
        {
            $randname = $eiusername.$mysuffix;
            if(strlen($randname)>$maxlen)  $randname = substr($randname,0,$maxlen);
            $isexist = GetUserID($randname);
            if($isexist==0)
            {
                $eiusername = $randname;
                break;
            }
        }
        if($isexist!=0)
        {
            $eiusername2 = $apptype.strval($openid);
            $isexist = GetUserID($eiusername2);
            if($isexist==0) $eiusername = $eiusername2;
        }
    }
    $mysuffix = '';   
    if($isexist!=0)
    {
        foreach ($defaultsuffix as $mysuffix)
        {
            $randname = $eiusername.$mysuffix;
            if(strlen($randname)>$maxlen)  $randname = substr($randname,0,$maxlen);
            $isexist = GetUserID($randname);
            if($isexist==0) break;
        }
    }
    if($isexist!=0)
    {
        while($mysuffix!=0)
        {
           $mysuffix = strval(mt_rand(3,0xFFFF)); 
           $isexist = GetUserID($eiusername.$mysuffix);
        }   
    }
    $eiusername.=$mysuffix;
    $addr['username'] = $eiusername;
    foreach ($mustfs as $m)
    {
        if(!isset($addr[$m]))
        {
            switch ($m)
            {
                case 'mycall':
                    $addr[$m] = '010-00000000';
                    break;
                case 'phone':
                    $addr[$m] = '13000000000';
                    break;   
                case 'zip':
                    $addr[$m] = '000000';
                    break;                  
                case 'company':
                    if(empty($eiusername))  $addr[$m] = '请填写公司名';
                    else  $addr[$m] =$eiusername.'的公司';
                    break;
                case 'email':
                    $parts = parse_url($public_r['newsurl']);
                   $phost = trim($parts['host']);
                   if(stripos($phost,'www.')!==FALSE)
                        $phost = substr($phost,4);   
                   $addr['email'] = $eiusername.'@'.$phost;//设置为在本地的邮箱              
                    break;
                default :
                    $addr[$m] = '0000';
                    break;
            }
        }
    }
    foreach ($addr as $kr=>$ar)
    {
        $_POST[$kr] = $ar;
    }
	
    $_POST['enews'] = 'register';
    $_POST['tobind'] = $tobind;
    $_POST['randompwd'] = $randompwd;
    $_POST['JumpChk'] = TRUE;
    $_POST['groupid'] = $groupid; 
    $_POST['notifychgpwd'] = 'sitemsg';//通知用户更改密码,站内消息[sitemsg],[email] 
    $_POST['chgpwdtitle'] ='请您及时修改您的用户信息及密码!';
    $fjinfo = '';

    if($eiusername != $OriginalName) $fjinfo = '(您在['.$apptype.']的名字'.$OriginalName.'已经被注册,系统自动分配了['.$eiusername.'])';
    $_POST['chgpwdinfo'] =<<<EOF_E
亲爱的[!--username--],感谢您通过{$apptype}注册本站会员,在创建账户时系统给您随机初始化了密码和用户名{$fjinfo},为安全考虑,请您及时修改您的密码,也可以修改用户名与邮箱,点击或者将如下链接复制到浏览器地址栏即可快速修改密码:  
[!--url--]
  本链接不能重复打开!如有疑惑或无法解决的问题,请与我们联系.
   [!--sitename--]  [!--date--]
EOF_E;

    $pathf= ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'member'.DIRECTORY_SEPARATOR.'doaction.php';
//var_dump($pathf);
    include($pathf);
    exit();//中止以后的代码
}

CheckMemberGroupCanReg($groupid);
$formid=GetMemberFormId($groupid);
if(empty($formid))
{
	printerror('ErrorUrl','',1);
}
$formfile='../../data/html/memberform'.$formid.'.php';
//导入模板
$thispagetitle = '注册用户-'.$public_r['sitename'];
require(ECMS_PATH.'e/template/member/register.php');
db_close();
$empire=null;
?>