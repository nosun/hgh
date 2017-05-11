<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']='增加收藏夹';
$url="<a href=../../../../>首页</a>&nbsp;>&nbsp;<a href=../../cp/>会员中心</a>&nbsp;>&nbsp;<a href=../../fava/>收藏夹</a>&nbsp;>&nbsp;增加收藏夹";
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
<body class="member_cp Addfava">
<?php
    require(ECMS_PATH.'e/template/incfile/header.php');
?>
<br>
        <table width="90%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
          <form name="form1" method="POST" action="../../doaction.php">
            <tr class="header"> 
              <td height="25"><div align="center"> 
                  <input name="enews" type="hidden" id="enews" value="AddFava">
                  增加收藏夹 
                  <input name="from" type="hidden" id="from2" value="<?=$from?>">
                  <input name="classid" type="hidden" id="classid2" value="<?=$classid?>">
                  <input name="id" type="hidden" id="id2" value="<?=$id?>">
                  [<a href="../FavaClass/" target="_blank">增加收藏分类</a>] </div></td>
            </tr>
            <tr> 
              <td height="25" bgcolor="#FFFFFF"><div align="center">收藏页面：<a href='<?=$titleurl?>' target=_blank><?=stripSlashes($r[title])?></a></div></td>
            </tr>
            <tr> 
              <td height="25" bgcolor="#FFFFFF"><div align="center">选择收藏分类: 
                  <select name="cid" id="select">
                    <option value="0">不设置</option>
                    <?=$select?>
                  </select>
                </div></td>
            </tr>
            <tr> 
              <td height="25" bgcolor="#FFFFFF"><div align="center"> 
                  <input type="submit" name="Submit" value="收藏">
                  &nbsp;&nbsp; 
                  <input type="button" name="Submit2" value="返回" onclick="javascript:history.go(-1)">
                </div></td>
            </tr>
          </form>
        </table>
        <br>
<?php
require(ECMS_PATH.'e/template/incfile/footer.php');
?>