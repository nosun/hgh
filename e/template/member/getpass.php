<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']='取回密码';
$url="<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>&nbsp;>&nbsp;取回密码";
if(empty($thispagetitle)) $thispagetitle = $public_diyr['pagetitle'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=$thispagetitle?></title>
    <link href="/skin/default/css/member.css" rel="stylesheet" type="text/css" />
    <script src="/skin/default/js/jquery-1.8.2.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/skin/default/js/custom.js"></script>
</head>
<body class="member_cp getpass">
<?php
require(ECMS_PATH.'e/template/incfile/header.php');
?> 
<table width="500" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="GetPassForm" method="POST" action="../doaction.php">
    <tr class="header"> 
      <td height="25" colspan="2"><div align="center">重设密码</div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="23%" height="25">用户名</td>
      <td width="77%"><?=$username?></td> 
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">新密码</td>
      <td><input name="newpassword" type="password" id="newpassword" size="38"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">重复新密码</td>
      <td><input name="renewpassword" type="password" id="renewpassword" size="38"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp; </td>
      <td> <input type="submit" name="button" value="修改"> 
        <input name="enews" type="hidden" id="enews" value="DoGetPassword">
        <input name="id" type="hidden" id="id" value="<?=$r[id]?>">
        <input name="tt" type="hidden" id="tt" value="<?=$r[tt]?>">
        <input name="cc" type="hidden" id="cc" value="<?=$r[cc]?>"></td>
    </tr>
  </form>
</table>
<br>
<?php
require(ECMS_PATH.'e/template/incfile/footer.php');
?>