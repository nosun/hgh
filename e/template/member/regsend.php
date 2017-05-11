<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']='发送帐号激活邮件';
$url="<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>&nbsp;>&nbsp;重发帐号激活邮件";
if(!isset($eiusername))$eiusername=$_GET['eiusername'];if(empty($eiusername))$eiusername='';//LGM 增加[2014年2月16日14:44],不需要解码
if(empty($thispagetitle)) $thispagetitle = $public_diyr['pagetitle'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?=$thispagetitle?></title>
    <link href="/skin/default/css/member.css" rel="stylesheet" type="text/css" />
    <script src="/skin/default/js/jquery-1.8.2.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/skin/default/js/custom.js"></script>
</head>
<body class="member_RegSend">
<?php
require(ECMS_PATH.'e/template/incfile/header.php');
?>
<br>
<table width="600" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="RegSendForm" method="POST" action="../doaction.php">
    <tr class="header"> 
      <td height="25" colspan="2"><div align="center">重发帐号激活邮件</div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="23%" height="25">用户名(*)</td>
      <td width="77%"><input name="username" type="text" id="username" size="38" value="<?php echo $eiusername;?>"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">密码(*)</td>
      <td><input name="password" type="password" id="password" size="38"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">邮箱(*)</td>
      <td><input name="email" type="text" id="email" size="38"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">新接收邮箱</td>
      <td><input name="newemail" type="text" id="newemail" size="38">
        <font color="#666666">(要改变接收邮箱可填写)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">验证码</td>
      <td><input name="key" type="text" id="key" size="6"> <img src="../../ShowKey/?v=regsend"  alt="验证码" title="看不清,点击换一个"  onclick="javascript:this.src='/e/ShowKey/?v=regsend&n='+Math.random();"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp; </td>
      <td> <input type="submit" name="button" value="提交"> 
        <input name="enews" type="hidden" id="enews" value="RegSend"></td>
    </tr>
  </form>
</table>
<br>
<?php
require(ECMS_PATH.'e/template/incfile/footer.php');
?>