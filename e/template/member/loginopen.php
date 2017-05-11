<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']='会员登录';
$url="<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>&nbsp;>&nbsp;会员登录";
if(empty($thispagetitle)) $thispagetitle = $public_diyr['pagetitle'];
if(!empty($defname)) $defname=' value="'.  addslashes($defname) . '"'; else $defname='';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?=$thispagetitle?></title>
    <link href="/skin/default/css/member.css" rel="stylesheet" type="text/css" />
    <link href="../../data/images/qcss.css" rel="stylesheet" type="text/css">
    <script src="/skin/default/js/jquery-1.8.2.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/skin/default/js/custom.js"></script>
</head>
<body class="member_login">
<?php
require(ECMS_PATH.'e/template/incfile/header.php');
?>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="form1" method="post" action="../doaction.php">
    <input type=hidden name=ecmsfrom value="<?=ehtmlspecialchars($_GET['from'])?>">
    <input type=hidden name=prtype value="<?=ehtmlspecialchars($_GET['prt'])?>">
    <input type=hidden name=enews value=login>
    <tr class="header"> 
      <td height="25" colspan="2"><div align="center">会员登陆</div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="24%" height="25">用户名：</td>
      <td width="76%" height="25"><input name="username" type="text" id="username"<?php echo $defname ?> ></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">密码：</td>
      <td height="25"><input name="password" type="password" id="password"></td>
    </tr>
	 <tr bgcolor="#FFFFFF">
      <td height="25">保存：</td>
      <td height="25"> 
        <select name="lifetime">
          <option value="0">不保存</option>
		  <option value="3600">一小时</option>
		  <option value="86400">一天</option>
		  <option value="2592000">一个月</option>
		  <option value="315360000">永久</option>
        </select>
     </td>
    </tr>
    <?php
	if($public_r['loginkey_ok'])
	{
	?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">验证码：</td>
      <td height="25"><input name="key" type="text" id="key" size="6">
        <img src="../../ShowKey/?v=login"  alt="验证码" title="看不清,点击换一个"  onclick="javascript:this.src='/e/ShowKey/?v=login&n='+Math.random()"></td>
    </tr>
    <?php
	}	
	?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value="登陆"> <input type="button" name="button" value="注册" onclick="window.open('../register/');"></td>
    </tr>
	</form>
  </table>
<?php
require(ECMS_PATH.'e/template/incfile/footer.php');
?>