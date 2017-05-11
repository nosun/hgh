<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$qappname=$appr['qappname'];
$sappname=$appr['appname'];
$public_diyr['pagetitle']=$sappname.'绑定登录';
$url="位置:<a href='../../'>首页</a>&nbsp;>&nbsp;绑定登录";
if(empty($thispagetitle)) $thispagetitle = $public_diyr['pagetitle'];
$regurl=$public_r['newsurl'].'e/member/register/?tobind=1';
$loginurl=$public_r['newsurl'].'e/member/login/?tobind=1';
if(empty($eiusername)==FALSE)$regurl.='&eiusername='.urlencode($eiusername); //LGM 增加[2014年2月16日15:20]
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
<body class="tobind">
<?php 
require(ECMS_PATH.'e/template/incfile/header.php');
?>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
  <tr>
      <td height="30" colspan="2" align="center"><font color="#FF0000"><strong><?php echo $eiusername?> 您好！已通过<?php echo $sappname?>登录,您即将完成注册过程!</strong></font></td>
  </tr>
  <tr>
    <td width="50%" valign="top"><form name="bindform" method="post" action="doaction.php">
      <table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr>
          <td height="25"><div align="center"><strong>1、如果您已有账号，可以点击下面登录绑定</strong></div></td>
        </tr>
        <tr>
          <td height="50"><div align="center">
            <input type="button" name="Submit" value="马上登录绑定" onclick="window.open('<?=$loginurl?>','_self');">
            <input name="enews" type="hidden" id="enews" value="BindUser">
          </div></td>
          </tr>
        <tr>
          <td height="25"><div align="center">提示：捆绑成功后，下次以
            <?=$sappname?>
            方式登录即可直接登录到捆绑后的账号。</div></td>
          </tr>
      </table>
        </form>
    </td>
    <td width="50%" valign="top"><form name="bindregform" method="post" action="doaction.php">
      <table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr>
          <td height="25"><div align="center"><strong>2、如果还没有账号，您可以快速注册</strong></div></td>
          </tr>
        <tr>
          <td height="50"><div align="center">
            <input type="button" name="Submit2" value="马上注册绑定" onclick="window.open('<?=$regurl?>','_self');">
            <input name="enews" type="hidden" id="enews" value="BindReg">
            <input name="eiusername" type="hidden" id="eiusername" value="<?=$eiusername?>">
          </div></td>
          </tr>
        <tr>
          <td height="25"><div align="center">提示：捆绑成功后，下次以
            <?=$sappname?>
            方式登录即可直接登录到捆绑后的账号。</div></td>
        </tr>
      </table>
        </form>
    </td>
  </tr>
</table>
<?php
require(ECMS_PATH.'e/template/incfile/footer.php');
?>