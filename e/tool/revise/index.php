<?php

exit();
/* 
 * 修订CMS,包括数据库结构与文件
 * LGM增加
 * 
 */
@set_time_limit(10000);
define('EmpireCMSAdmin','1');
define('UPDATEVERSION','7.0.1.0');//要更新到的版本号
ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);//E_ALL ^ E_NOTICE

require_once('../../class/connect.php');
require_once('../../class/db_sql.php');
require_once('../../class/functions.php');
require_once('../../class/userfun.php');
$link=db_connect();
$empire=new mysqlquery();
//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];
$msg = array();
$opresult = FALSE;
$otherhtmlcode ='';
if (function_exists('MenuClassToShow'))
{
    //显示导航
    function MenuClassToShow()
    {
        global $empire, $dbtbpre;
        $ret ='';
        //常用菜单
        $showfastmenu = $empire->gettotal("select count(*) as total from {$dbtbpre}enewsmenuclass where classtype=1 limit 1");
        if ($showfastmenu)
        {
            $ret.=  "<script type='text/javascript'>if(parent.document.getElementById('dofastmenu')==null||parent.document.getElementById('dofastmenu')=='undefined'){}else{parent.document.getElementById('dofastmenu').style.display='';}</script>";
        }
        $showextmenu = $empire->gettotal("select count(*) as total from {$dbtbpre}enewsmenuclass where classtype=3 limit 1");
        if ($showextmenu)
        {
            $ret.=  "<script type='text/javascript'>if(parent.document.getElementById('doextmenu')==null||parent.document.getElementById('doextmenu')=='undefined'){}else{parent.document.getElementById('doextmenu').style.display='';}</script>";
        }
        return $ret;
    }

}


/**
 * 更新CMS
 * @global mysqlquery $empire
 * @global array $lur
 * @global string $currentver
 * @global string $adminpath
 * @global type $logininid
 * @global type $loginin
 * @global int $classid
 * @param array $msg
 * @return boolean
 */
function UpdateCMS(array &$msg)
{
    global $empire,$lur,$currentver,$adminpath,$logininid,$loginin,$classid,$loginadminstyleid,$ecms_config,$dbtbpre;
$ph1 = NULL;
if(!empty($_SERVER["HTTP_REFERER"])){
    $ph1 = strtolower(pathinfo(parse_url($_SERVER["HTTP_REFERER"],PHP_URL_PATH),PATHINFO_DIRNAME));
}
$allowreferer = array(
   strtolower('/e/'.  getcvar('pathn', 1, TRUE).'/adminstyle/'.$loginadminstyleid)
);
if(!defined('InEmpireCMS') || empty($ph1) || in_array($ph1,$allowreferer)===FALSE)
{
        if(stripos($_SERVER["HTTP_REFERER"],$_SERVER["REQUEST_URI"])===FALSE)
        {
            echo '敬告:必须在后台框架内运行<br/>请转到:后台 > 扩展 > 网站更新 > 更新CMS';
            exit(); 
        }
}    
  
    //验证权限
    CheckLevel($logininid,$loginin,$classid,"updatecms");
    $opresult = FALSE;
    if(version_compare($currentver,UPDATEVERSION)>=0)
    {
        $opresult = TRUE;
        printerror ('已经是最高版本了!','history.go(-1)',0);
    }
    if (version_compare($currentver, '7.0', '=='))
    {
    //检查常量:
    $cvar = 'AUTOPAGESIZE';
    if(!defined($cvar))
    {
        $msg[]="常量[{$cvar}]没有定义,请先更新文件:".ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'connect.php';
    }
    $cvar = 'PV_EINAME';    
    if(!defined($cvar))
    {
        $msg[]="常量[{$cvar}]没有定义,请先更新文件:".ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'connect.php';
    }
    //检查函数是否存在 :
    $fun = 'GetEPath';
    if(!function_exists($fun))
    {
         $msg[]="重要函数[{$fun}()]不存在,请先更新文件:".ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'connect.php';
    }
    $fun = 'fetch1';
    if(call_user_func_array(array($empire,$fun),array('select now() as time;',MYSQL_ASSOC))===FALSE)
    {
         $msg[]="请更新重要函数[{$fun}()],在文件:".ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'db_sql.php';
    }
    if (count($msg) == 0)
    {
        //修改表的结构enewsmember_connect_app,enewsmember_connect：
        $sqlcmd = "show columns from {$ecms_config['db']['dbname']}.{$dbtbpre}enewsmember_connect_app where Field = 'callbackurl'";
        $sqlv = $empire->fetch1($sqlcmd,MYSQL_ASSOC);
        $hascallbackurlfeild = TRUE;
        if(empty($sqlv['Field']))
        {
            $hascallbackurlfeild = FALSE;
            $msg[] = "{$dbtbpre}enewsmember_connect_app,{$dbtbpre}enewsmember_connect需要更新表结构";
        }
        if (!$hascallbackurlfeild)//增加新的字段
        {
            if($empire->query("ALTER TABLE `{$dbtbpre}enewsmember_connect_app` 
ADD COLUMN `callbackurl`  varchar(512) NULL AFTER `appsay`,
ADD COLUMN `callbackurl2`  varchar(512) NULL AFTER `callbackurl`,
ADD COLUMN `info`  varchar(1024) NULL AFTER `callbackurl2`,
ADD COLUMN `tranregexlist`  varchar(2048) NULL COMMENT '转换正则规则表' AFTER `info`,
ADD COLUMN `trankeywordslist`  varchar(2048) NULL COMMENT '转换关键词表' AFTER `tranregexlist`,
DROP INDEX `apptype` ,
ADD INDEX `apptype` (`apptype`) USING BTREE ,
ADD UNIQUE INDEX `appname` (`appname`) USING BTREE;"))       
            {
                $mi = "{$dbtbpre}enewsmember_connect_app表结构已经更改";
                $msg[] = $mi;
                insert_dolog($mi); //操作日志
            }
            if($empire->query("ALTER TABLE `{$dbtbpre}enewsmember_connect`
ADD COLUMN `token`  varchar(128) CHARACTER SET ascii COLLATE ascii_general_ci NULL AFTER `lasttime`,
ADD COLUMN `expired`  datetime NULL COMMENT 'access_token' AFTER `token`,
ADD COLUMN `scope`  varchar(128) NULL AFTER `expired`,
ADD COLUMN `bindname`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '第三方交互平台用户名' AFTER `scope`,
ADD COLUMN `rtoken`  varchar(128) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL COMMENT 'refresh_token' AFTER `bindname`,
ADD COLUMN `aid`  int NOT NULL COMMENT '应用的ID' AFTER `rtoken`,
DROP INDEX `openid` ,
ADD INDEX `openid` (`openid`, `token`) USING BTREE"))  
            {
                $mi = "{$dbtbpre}enewsmember_connect表结构已经更改";
                $msg[] =$mi ;
                insert_dolog($mi); //操作日志
            }
        }
        clearstatcache();
//检查文件是否存在:
        $reqfiles = array(
            ECMS_PATH . 'e' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Array2XML.php',
            ECMS_PATH . 'e' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'MsgCore.php',
            ECMS_PATH . 'e' . DIRECTORY_SEPARATOR . $adminpath . DIRECTORY_SEPARATOR . 'Interface' . DIRECTORY_SEPARATOR . 'NewsDistribute.php',
        );
//文件是否可写
        $allowritefs = array(
            ECMS_PATH . 'e' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'base64keyiv.php',
            ECMS_PATH . 'e' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'apprwcode.php',
        );
        foreach ($reqfiles as $f)
        {
            if (!file_exists($f))
                $msg[] = "请上传文件:{$f}";
        }
        foreach ($allowritefs as $f)
        {
            if (file_exists($f))
            {
                if (!is_writable($f))
                    $msg[] = "请将文件的权限设为可写:{$f}";
            }
            else
            {
                $dir = dirname($f);
                if (!is_writable($dir))
                    $msg[] = "请上传文件并将权限设为可写:{$f}";
            }
        }
//扩展变量检查:
        $xvar = 'DEFAULTENCRYPTPWD';
        if (!ExistsPubVar($xvar))
        {
            $pas = array('myvar' => $xvar, 'varvalue' => strrev(md5(microtime(), TRUE)), 'varname' => '可逆加密默认密码', 'varsay' => '一般用于Cookie加密', 'tocache' => 1);
            if (AddToPubVar($pas))
            {
                $msg[] = "增加扩展变量:{$xvar}";
                insert_dolog($msg[count($msg)-1]); //操作日志
            }
        }
        $xvar = 'EncryptCHKNUM';
        if (!ExistsPubVar($xvar))
        {
            $pas = array('myvar' => $xvar, 'varvalue' => strval(crc32(md5(microtime()))), 'varname' => '可逆加密校验计算码', 'varsay' => '计算密文校验和后异或的数值', 'tocache' => 1);
            if (AddToPubVar($pas))
            {
                $msg[] = "增加扩展变量:{$xvar}";
                insert_dolog($msg[count($msg)-1]); //操作日志
            }
        }
        $xvar = 'EIQuickReg';
        if (!ExistsPubVar($xvar))
        {
            $pas = array('myvar' => $xvar, 'varvalue' => '1', 'varname' => '第三方交互快速注册', 'varsay' => '用第三方交互平台账号登录时,快速在本站注册会员,可能跳过原用户登录过程', 'tocache' => 1);
            if (AddToPubVar($pas))
            {
                $msg[] = "增加扩展变量:{$xvar}";
                insert_dolog($msg[count($msg)-1]); //操作日志
            }
        }
        $opresult = TRUE;
    }
}
if($opresult)
{
    //更新版本号
    $sqlcmd= "UPDATE `{$dbtbpre}enewspublic` SET `softversion`='".UPDATEVERSION."' limit 1";
    if($empire->query($sqlcmd))
    {
        $msg[] = "更新版本号到:".UPDATEVERSION;
        insert_dolog($msg[count($msg)-1]); //操作日志
    }
}
return $opresult;
}
//检查函数是否存在 :
$fun = 'AddToPubVar';
if(!function_exists($fun))
{
         $msg[]="重要函数[{$fun}()]不存在,请先更新文件:".ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'connect.php';
}
$fun = 'ExistsPubVar';
if(!function_exists($fun))
{
         $msg[]="重要函数[{$fun}()]不存在,请先更新文件:".ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'connect.php';
}
$fun = 'AddAdminMenu';
if(!function_exists($fun))
{
         $msg[]="重要函数[{$fun}()]不存在,请先更新文件:".ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'userfun.php';
}
$fun = 'AddAdminMenuClass';
if(!function_exists($fun))
{
         $msg[]="重要函数[{$fun}()]不存在,请先更新文件:".ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'userfun.php';
}
if(count($msg)==0)
{
//判断是否已经在组权限表中有doupdatecms字段:{$dbtbpre}enewsgroup
$er = $empire->fetch1("show columns from {$ecms_config['db']['dbname']}.{$dbtbpre}enewsgroup where Field='doupdatecms'",MYSQL_ASSOC);
if(empty($er['Field']))
{
    //新增加doupdatecms字段:
    $sqlcmd="ALTER TABLE `{$dbtbpre}enewsgroup`ADD COLUMN `doupdatecms`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '允许更新CMS' AFTER `doprecode`;";
    if(!$empire->query($sqlcmd))
    {
        $msg[] = "此用户无法修改表[{$dbtbpre}enewsgroup]的结构!";
    }
    else
    {
         $msg[] = "已经成功修改表[{$dbtbpre}enewsgroup],请先添加[更新CMS]权限后继续.用户管理 > 管理用户组 > 修改用户组";
         insert_dolog("已经成功修改表[{$dbtbpre}enewsgroup]");
    }
}
//增加扩展菜单分类          
 //CheckLevel($userid,$username,$classid,"menu");//验证权限
 $menuclassid1 = AddAdminMenuClass('第三方交互', 2, $otherhtmlcode, $msg,$lur);
if($menuclassid1)
{                    
        //增加菜单项:
        $menuid1=AddAdminMenu($menuclassid1, '新浪交互安装与卸载', '/e/memberconnect/sina/install/index.php', 0, $msg,$lur);
}
else
{
   $msg[] = "扩展菜单[第三方交互]分类添加失败!"; 
}

 $menuclassid = AddAdminMenuClass('网站更新', 3, $otherhtmlcode, $msg,$lur);
if($menuclassid)
{                    
        //增加菜单项:
        $menuid=AddAdminMenu($menuclassid, '更新CMS', '/e/tool/revise/index.php', 0, $msg,$lur);
}
else
{
   $msg[] = "扩展菜单[网站更新]分类添加失败!"; 
}
}

if(count($msg)==0)
{
    if(!defined('InEmpireCMS') || empty($_SERVER["HTTP_REFERER"]) || stripos($_SERVER["HTTP_REFERER"],'/e/'.  getcvar('pathn', 1, TRUE).'/adminstyle/'.$loginadminstyleid.'/left.php')===FALSE)
    {
        if(stripos($_SERVER["HTTP_REFERER"],$_SERVER["REQUEST_URI"])===FALSE)
        {
            echo '敬告:必须在后台框架内运行<br/>请转到:后台 > 扩展 > 网站更新 > 更新CMS';
            exit(); 
        }
    }
}

//TODO -----------------------------------
$r=$empire->fetch1("select softversion from {$dbtbpre}enewspublic limit 1");//取当前版本号
if(empty($r)) exit();
$currentver =  explode(',',$r['softversion']);
if(count($currentver)>0) $currentver = $currentver[0];
$adminpath=getcvar('pathn',1,TRUE);


if($_POST['ecms']=="updatecms")
{
    if(UpdateCMS($msg))
    {
        $msg[] = '更新CMS的操作成功!';
        insert_dolog($msg[count($msg)-1]); //操作日志
    }
    else
    {
       $msg[]  = '更新CMS的操作失败!'; 
       insert_dolog($msg[count($msg)-1]); //操作日志
    }
     
     
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>更新CMS</title>
<link href="/e/<?=getcvar('pathn',1,TRUE)?>/adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
function CheckUpdate(obj){
	if(confirm('确认操作?'))
	{
		obj.updatebutton.disabled=true;
		return true;
	}
	return false;
}
</script>
</head>
<body>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td>位置： 扩展&nbsp;&gt;&nbsp;网站更新 &gt; <a href="/e/tool/revise/index.php">更新CMS</a>
      <div align="right"> </div></td>
  </tr>
</table>    
    <form method="POST" action="index.php" name="formupdate" onsubmit="return CheckUpdate(document.formupdate);">
        <div class='titleinfo'>
        <div> 当前版本号:<?php echo $currentver ?>  </div>
        <div> 将升级到:<?php echo strval(UPDATEVERSION) ?>  </div>
        </div>
        <div class='msglist'>
            <?php
              if(!empty($msg))
              {
                  echo '<span class="msgtitle">消息列表:</span><br/><ul>';
                  foreach ($msg as $m)
                  {
                      echo '<li>'.$m.'</li>';
                  }
                  echo '</ul>' ;
              }
            ?>
        </div>
        <div>
            <input type='submit' name='updatebutton' value="开始更新" /> 
            <input name="ecms" type="hidden" id="ecms" value="updatecms" /> 
        </div>
    </form> 
</body>
    <?php
    if(!empty($otherhtmlcode)) echo $otherhtmlcode;
    ?>
</html>
<?php
//关闭数据库连接
db_close();
$empire=null;
?>
