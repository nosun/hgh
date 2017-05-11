<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']='点卡充值';
$url="<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>&nbsp;>&nbsp;点卡充值";
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
    <script type="text/javascript">
    function GetFen1()
    {
    var ok;
    ok=confirm("确认要充值?");
    if(ok)
    {
    document.GetFen.Submit.disabled=true
    return true;
    }
    else
    {return false;}
    }
</script>   
</head>
<body class="member_cp card">
<?php
require(ECMS_PATH.'e/template/incfile/header.php');
?> 
<table width="60%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name=GetFen method=post action=../doaction.php onsubmit="return GetFen1();">
    <input type=hidden name=enews value=CardGetFen>
    <tr class="header"> 
      <td height="25" colspan="2"><div align="center">点卡冲值</div></td>
    </tr>
    <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF"> 
      <td width="34%" height="25"> <div align="right">冲值的用户名：</div></td>
      <td width="66%" height="25"> <input name="username" type="text" id="username" value="<?=$user[username]?>">
        *</td>
    </tr>
    <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF"> 
      <td height="25"> <div align="right">重复用户名：</div></td>
      <td height="25"> <input name="reusername" type="text" id="reusername" value="<?=$user[username]?>">
        *</td>
    </tr>
    <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF"> 
      <td height="25"> <div align="right">冲值卡号：</div></td>
      <td height="25"> <input name="card_no" type="text" id="card_no">
        *</td>
    </tr>
    <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF"> 
      <td height="25"> <div align="right">冲值卡密码：</div></td>
      <td height="25"> <input name="password" type="password" id="password">
        *</td>
    </tr>
    <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF"> 
      <td height="25"> <div align="center"></div></td>
      <td height="25"> <input type="submit" name="Submit" value="开始冲值"> &nbsp; 
        <input type="reset" name="Submit2" value="重置"> </td>
    </tr>
    <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF"> 
      <td height="25" colspan="2"> <div align="center">说明：带*的为必填项。</div></td>
    </tr>
  </form>
</table>
<br>
<?php
require(ECMS_PATH.'e/template/incfile/footer.php');
?>