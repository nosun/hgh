<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?=$thispagetitle?></title>
    <link href="/skin/default/css/member.css" rel="stylesheet" type="text/css" />

</head>

<body class="member_cp qlistinfo">

<?php
$public_diyr['pagetitle']='设置空间';
$url="<a href='../../../'>首页</a>&nbsp;>&nbsp;<a href='../cp/'>会员中心</a>&nbsp;>&nbsp;设置空间";
require(ECMS_PATH.'e/template/incfile/header.php');
?>
        
    <div class="container">
        <div class="main">
            <?php
                require(ECMS_PATH.'e/template/incfile/maintop.php');
                require(ECMS_PATH.'e/template/incfile/sidebar.php');
            ?> 
            <div class="member_main">
                <div class="main_message"><strong>设置空间</strong></div>
                <div class="section_content">
                    <table width="100%" border="0" cellspacing="1" cellpadding="3" class="tableborder">
                        <form name="setspace" method="post" action="index.php">
                        <tr bgcolor="#FFFFFF"> 
                            <td width="17%" height="25">&nbsp;&nbsp;空间名称：</td>
                            <td width="83%">&nbsp;&nbsp;<input name="spacename" type="text" id="spacename" value="<?=$addr[spacename]?>"></td>
                        </tr>
                        <tr bgcolor="#FFFFFF"> 
                            <td>&nbsp;&nbsp;空间公告：</td>
                            <td>&nbsp;&nbsp;<textarea name="spacegg" cols="60" rows="6" id="spacegg"><?=$addr[spacegg]?></textarea></td>
                        </tr>
                        <tr bgcolor="#FFFFFF"> 
                            <td height="25">&nbsp;&nbsp;</td>
                            <td>&nbsp;&nbsp;<input type="submit" name="Submit" value="提交">
                            <input type="reset" name="Submit2" value="重置">
                            <input name="enews" type="hidden" id="enews" value="DoSetSpace"></td>
                        </tr>
                            </form>
                        </table>
                </div>
            </div>
        </div>
        <div class="clear"></div>        
    </div>
<?php
require(ECMS_PATH.'e/template/incfile/footer.php');
?>