<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']='在线充值';
$url="<a href='../../../'>首页</a>&nbsp;>&nbsp;<a href='../cp/'>会员中心</a>&nbsp;>&nbsp;在线充值";
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
<body class="member_cp buygroup">
<?php
require(ECMS_PATH.'e/template/incfile/header.php');
?> 
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="payform" method="post" action="../../payapi/BuyGroupPay.php">
    <tr class="header"> 
      <td height="25">请选择要购买的充值类型：</td>
    </tr>
    <?
  while($r=$empire->fetch($sql))
  {
	  if($r[buygroupid]&&$level_r[$r[buygroupid]][level]>$level_r[$user[groupid]][level])
	  {
		  continue;
	  }
  ?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><table width="100%" border="0" cellspacing="1" cellpadding="3">
          <tr> 
            <td width="1%"> <input type="radio" name="id" value="<?=$r[id]?>"> 
            </td>
            <td width="97%"> 
              <?=$r[gmoney]?>
              元 （ 
              <?=$r[gname]?>
              ）</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td><font color="#666666">
              <?=nl2br($r[gsay])?>
              </font></td>
          </tr>
        </table></td>
    </tr>
    <?
  }
  ?>
    <tr bgcolor="#FFFFFF">
      <td height="25">支付平台：
        <SELECT name="payid" style="WIDTH: 120px">
          <?=$pays?>
        </SELECT></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><input type="submit" name="Submit" value="马上充值">
        &nbsp;&nbsp; <input type="button" name="Submit2" value="返回" onclick="self.location.href='../../../';"> 
      </td>
    </tr>
  </form>
</table>
<?php
require(ECMS_PATH.'e/template/incfile/footer.php');
?>