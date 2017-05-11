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
    <script>
    function CheckAll(form)
    {
    for (var i=0;i<form.elements.length;i++)
        {
        var e = form.elements[i];
        if (e.name != 'chkall')
        e.checked = form.chkall.checked;
        }
    }
    </script>
</head>

<body class="member_cp qlistinfo">

<?php
$public_diyr['pagetitle']='管理反馈';
$url="<a href='../../../'>首页</a>&nbsp;>&nbsp;<a href='../cp/'>会员中心</a>&nbsp;>&nbsp;管理反馈";
require(ECMS_PATH.'e/template/incfile/header.php');
?>
    
    <div class="container">
        <div class="main">
            <?php
                require(ECMS_PATH.'e/template/incfile/maintop.php');
                require(ECMS_PATH.'e/template/incfile/sidebar.php');
            ?> 
            <div class="member_main">
                <div class="main_message"><strong>管理反馈</strong></div>
                <div class="section_content">
                    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
                        <form name="feedbackform" method="post" action="index.php" onsubmit="return confirm('确认要删除?');">
                        <tr class="header"> 
                        <td width="6%" height="25"><div align="center">全选：<input type='checkbox' name='chkall' value='on' onClick='CheckAll(this.form)' /></div></td>
                        <td width="58%"><div align="center">标题(点击查看)</div></td>
                        <td width="25%"><div align="center">提交时间</div></td>
                                <td width="11%"><div align="center">删除</div></td>
                        </tr>
                        <?php
                            while($r=$empire->fetch($sql))
                            {
                                if($r['uid'])
                                {
                                    $r['uname']="<a href='../../space/?userid=$r[uid]' target='_blank'>$r[uname]</a>";
                                }
                                else
                                {
                                    $r['uname']='游客';
                                }
                        ?>
                        <tr bgcolor="#FFFFFF"> 
                                <td height="25"><div align="center"> 
                                <input name="fid[]" type="checkbox" value="<?=$r[fid]?>">
                                </div></td>
                                <td height="25"><div align="left">
                                <a href="#ecms" onclick="window.open('ShowFeedback.php?fid=<?=$r[fid]?>','','width=650,height=600,scrollbars=yes,top=70,left=100');"><?=$r[title]?></a>&nbsp;(<?=$r['uname']?>)
                                </div></td>
                                <td height="25"><div align="center"> 
                                <?=$r[addtime]?>
                                </div></td>
                                <td height="25"><div align="center">
                                <a href="index.php?enews=DelMemberFeedback&fid=<?=$r[fid]?>" onclick="return confirm('确认要删除?');">删除</a>
                                </div></td>
                        </tr>
                        <?
                        }
                        ?>
                        <tr bgcolor="#FFFFFF"> 
                                <td height="25" colspan="4"> 
                                <?=$returnpage?>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="submit" name="Submit" value="批量删除">
                                <input name="enews" type="hidden" id="enews" value="DelMemberFeedback_All"></td>
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