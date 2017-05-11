<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require("../../member/class/user.php");
$link=db_connect();
$empire=new mysqlquery();

//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];
//验证权限
CheckLevel($logininid,$loginin,$classid,"memberconnect");
$enews=$_GET['enews'];
$id=(int)$_GET['id'];
$newapptype = $_GET['apptype'];
$r = array();
if($id){
   $r=$empire->fetch1("select id,appname,appkey,isclose,myorder,qappname,appsay,callbackurl,callbackurl2,tranregexlist,trankeywordslist,apptype,appid from {$dbtbpre}enewsmember_connect_app where id='$id'",MYSQL_ASSOC);
}
else {
   $r=$empire->fetch1("select '' as id,'' as appname,'' as appkey,0 as isclose,0 as myorder,'' as qappname,'' as appsay,'' as callbackurl,'' as callbackurl2,tranregexlist,trankeywordslist,apptype,'' as appid from {$dbtbpre}enewsmember_connect_app where apptype='".mysql_real_escape_string($newapptype)."' order by id DESC limit 1",MYSQL_ASSOC);
}
$url="外部接口 &gt; <a href=MemberConnect.php>管理外部登录接口</a>&nbsp;>&nbsp;配置外部登录接口：<b>".$r['appname']."</b>";
$othermsg = '';
//检查这个应用是否实现:
$apptype = $r['apptype'];
$appfilepath = ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'memberconnect'.DIRECTORY_SEPARATOR.$apptype.DIRECTORY_SEPARATOR.'class.php';
if(!file_exists($appfilepath))
{
     if($empire->query("update {$dbtbpre}enewsmember_connect_app set isclose=1 where id='{$id}'"))
     {
         $r['isclose'] = 1;
         $othermsg = '<font color="red">系统检测这个应用没有在本站实现,自动关闭</font>';
     }
}
db_close();
$empire=null;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>外部登录接口</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="50%">位置： 
      <?=$url?>
    </td>
    <td><div align="right" class="emenubutton"></div></td>
  </tr>
</table>
<form name="setmemberconnectform" method="post" action="MemberConnect.php" enctype="multipart/form-data">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td height="25" colspan="2">配置外部登录接口 
        <input name="enews" type="hidden" id="enews" value="<?=$enews?>">
        <input name="id" type="hidden" id="id" value="<?=$id?>">      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><div align="right">接口类型：</div></td>
      <td height="25"> 
        <?=$r['apptype']?>  
         <input name="apptype" type="hidden" id="apptype" value="<?=$r['apptype']?>">
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><div align="right">接口状态：</div></td>
      <td height="25"><input type="radio" name="isclose" value="0"<?=$r[isclose]==0?' checked':''?>>
        开启 
        <input type="radio" name="isclose" value="1"<?=$r[isclose]==1?' checked':''?>>
        关闭</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="23%" height="25"><div align="right">接口名称：</div></td>
      <td width="77%" height="25"><input name="appname" type="text" id="appname" value="<?=$r[appname]?>" size="35"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25"><div align="right">接口别名：</div></td>
      <td height="25"><input name="qappname" type="text" id="qappname" value="<?=$r[qappname]?>" size="35"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" valign="top"><div align="right">接口描述：</div></td>
      <td height="25"><textarea name="appsay" cols="65" rows="6" id="appsay"><?=ehtmlspecialchars($r[appsay])?></textarea></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><div align="right">appid：</div></td>
      <td height="25"><input name="appid" type="text" id="appid" value="<?=$r[appid]?>" size="35">
        <font color="#666666">(申请的应用ID)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><div align="right">appkey：</div></td>
      <td height="25"><input name="appkey" type="text" id="appkey" value="<?=$r[appkey]?>" size="35">
        <font color="#666666">(申请的应用密钥)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><div align="right">授权回调地址：</div></td>
      <td height="25"><input name="callbackurl" type="text" id="callbackurl" value="<?=$r['callbackurl']?>" size="50">
        <font color="#666666">(必须与新浪应用设置相同)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><div align="right">取消授权回调地址：</div></td>
      <td height="25"><input name="callbackurl2" type="text" id="callbackurl2" value="<?=$r['callbackurl2']?>" size="50">
        <font color="#666666">(必须与新浪应用设置相同)</font></td>
    </tr>  
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><div align="right">转换正则规则表：</div></td>
      <td height="25"><input name="tranregexlist" type="text" id="tranregexlist" value="<?=ehtmlspecialchars($r['tranregexlist']) ?>" size="100">
        <div color="#666666">(网站内容发布到第三方平台时对内容处理时的正则规则组,必须以 ['正则式1'=>'替换值1','正则式2'=>'替换值2']的形式存在)</div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><div align="right">转换关键词表：</div></td>
      <td height="25"><input name="trankeywordslist" type="text" id="trankeywordslist" value="<?=ehtmlspecialchars($r['trankeywordslist'])?>" size="100">
        <div color="#666666">(网站内容发布到第三方平台时要替换的关键词组,必须以 ['关键词1'=>'替换值1','关键词2'=>'替换值2']的形式存在)</div></td>
    </tr>      
      
     <tr bgcolor="#FFFFFF"> 
      <td height="25" valign="top"><div align="right">接口自定义参数：</div></td>
      <td height="25"><textarea name="info" cols="65" rows="6" id="info"><?=ehtmlspecialchars($r['info'])?></textarea></td>
    </tr>
       
    <tr bgcolor="#FFFFFF">
      <td height="25"><div align="right">显示排序：</div></td>
      <td height="25"><input name=myorder type=text id="myorder" value='<?=$r[myorder]?>' size="35">
        <font color="#666666">(值越小显示越前面)</font></td>
    </tr>
    <?php if(!empty($othermsg)){ ?>
    <tr bgcolor="#FFEEEE">
      <td height="25"><div align="right">其它信息：</div></td>
      <td height="25"><?php echo $othermsg; ?></td>
    </tr>    
    <?php } ?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value=" 设 置 "> &nbsp;&nbsp;&nbsp; 
        <input type="reset" name="Submit2" value="重置"></td>
    </tr>
  </table>
</form>
</body>
</html>
