<?php
if(!defined('InEmpireCMS'))
{
	exit();
}

$public_diyr['pagetitle']='管理信息';
$url="<a href=../../>首页</a>&nbsp;>&nbsp;<a href=../member/cp/>会员中心</a>&nbsp;>&nbsp;管理信息";
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
<body class="member_cp">
<?php
require(ECMS_PATH.'e/template/incfile/header.php');
?>  
      <p>&nbsp;</p>
      <table width="80%" border="0" align="center" class="tableborder">
        <tr class="header">
          <td height="25"><div align="center">欢迎来到信息管理中心</div></td>
        </tr>
        <tr>
          <td height="50" bgcolor="#FFFFFF"> 
          <div align="center">选择左边您要增加或管理的信息。</div></td>
        </tr>
      </table>
<?php
require(ECMS_PATH.'e/template/incfile/footer.php');
?>