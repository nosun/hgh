<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']='增加信息';
$url="<a href='../../'>首页</a>&nbsp;>&nbsp;<a href='../member/cp/'>会员中心</a>&nbsp;>&nbsp;<a href='ListInfo.php?mid=".$mid."'>管理信息</a>&nbsp;>&nbsp;增加信息&nbsp;(".$mr[qmname].")";
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
    <script>
        function CheckChangeClass()
        {
                if(document.changeclass.classid.value==0||document.changeclass.classid.value=='')
                {
                        alert("请选择栏目");
                        return false;
                }
                return true;
        }
    </script>
</head>
<body class="member_cp qlistinfo">
<?php
require(ECMS_PATH.'e/template/incfile/header.php');
?>

    <div class="container">
        <div class="main">
            <?php
                
                require(ECMS_PATH.'e/template/incfile/maintop.php');
                require(ECMS_PATH.'e/template/incfile/sidebar.php');
                
            ?> 
            <div class="member_main">
                <div class="main_message"><strong>投稿</strong></div>
                <div class="section_content">
                    <table width="100%" border="0" align="center">
                        <tr> 
                        <td>你好，<b><?=$musername?></b></td>
                        </tr>
                    </table>
                    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
                        <form action="AddInfo.php" method="get" name="changeclass" id="changeclass" onsubmit="return CheckChangeClass();">
                        <tr class="header"> 
                            <td height="23"><strong>请选择要增加信息的栏目 
                                <input name="mid" type="hidden" id="mid" value="<?=$mid?>">
                            <input name="enews" type="hidden" id="enews" value="MAddInfo">
                            </strong></td>
                        </tr>
                        <tr bgcolor="#FFFFFF"> 
                            <td height="32"> <select name=classid size="22" style="width:100%">
                                <script src="<?=$classjs?>"></script>
                            </select> </td>
                        </tr>
                        <tr bgcolor="#FFFFFF"> 
                            <td><input type="submit" name="Submit" value="添加信息"> <font color="#666666">(请选择终极栏目[蓝色条])</font></td>
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