<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']='选择好友';
$url="<a href=../../../../>首页</a>&nbsp;>&nbsp;<a href=../../cp/>会员中心</a>&nbsp;>&nbsp;<a href=../../msg/>选择好友</a>&nbsp;>&nbsp;选择好友";
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
<link href="../../../data/images/qcss.css" rel="stylesheet" type="text/css">
<script>
function ChangeHy()
{
	var fname=document.changeuser.fname.value;
	if(fname!="")
	{
		opener.document.<?=$fm?>.<?=$f?>.value=fname;
	}
	window.close();
}
</script>
</head>
<body>
<?php
require(ECMS_PATH.'e/template/incfile/header.php');
?>
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="changeuser" method="GET" action="index.php?<?=$addvar?>">
    <tr class="header"> 
      <td height="23">选择用户</td>
    </tr>
    <tr> 
      <td width="82%" height="25" bgcolor="#FFFFFF">分类：
        <select name="cid" id="select" onchange=window.location='index.php?<?=$addvar?>&cid='+this.options[this.selectedIndex].value>
          <option value="0">显示全部</option>
          <?=$select?>
        </select></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">
<select name="fname" size="16" id="fname" style="width:200">
<?=$hyselect?>
        </select></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">
<input type="button" name="Submit" value="确定" onclick="ChangeHy();"></td>
    </tr>
	</form>
  </table>
</body>
</html>