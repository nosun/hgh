<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']='好友列表';
$url="<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>&nbsp;>&nbsp;好友列表";
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
<body class="member_cp friend">
<?php
require(ECMS_PATH.'e/template/incfile/header.php');
?>        
    <div class="container">
        <?php
            require(ECMS_PATH . 'e/template/incfile/maintop.php');                
        ?>
        <div class="main">
            <?php
                require(ECMS_PATH.'e/template/incfile/sidebar.php');
            ?> 
            <div class="member_main">
                <div class="main_message"><strong><?=$public_diyr['pagetitle']?></strong></div>
                <div class="section_content">
                    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="usertoolbar">
                        <form name="form1" method="post" action="">
                            <tr> 
                                <td width="50%" height="30" bgcolor="#FFFFFF">&nbsp;&nbsp;选择分类: 
                                    <select name="cid" id="select" onchange=window.location='../friend/?cid='+this.options[this.selectedIndex].value>
                                        <option value="0">显示全部</option>
                                        <?=$select?>
                                    </select>
                                </td>
                                <td width="50%" bgcolor="#FFFFFF"><div align="right"><a class="button button1" href="FriendClass/">管理分类</a> <a class="button button2" href="add/?fcid=<?=$cid?>">添加好友</a>&nbsp;&nbsp;</div></td>
                            </tr>
                        </form>
                    </table>
                    <table width="100%" border="0" cellpadding="3" cellspacing="1" class="tableborder">
                    <form name=favaform method=post action="../doaction.php" onsubmit="return confirm('确认要操作?');">
                        <input type=hidden value=hy name=enews>
                        <tr class="header"> 
                            <td width="5%" height="25"><div align="center"></div></td>
                            <td width="30%"><div align="center">用户名</div></td>
                            <td width="45%"><div align="center">备注</div></td>
                            <td width="20%"><div align="center">操作</div></td>
                        </tr>
                        <?php
                            while($r=$empire->fetch($sql)){
                        ?>
                        <tr bgcolor="#FFFFFF"> 
                        <td height="34"> <div align="center"><img src="../../data/images/man.gif" width="16" height="16" border=0></div></td>
                        <td> <div class="fname"><a href="../ShowInfo/?username=<?=$r[fname]?>" target=_blank> 
                            <?=$r[fname]?>
                            </a></div></td>
                        <td> <div class="finput"> 
                            <input name="fsay[]" type="text" id="fsay[]" value="<?=stripSlashes($r[fsay])?>" size="32">
                            </div></td>
                        <td> 
                            <div class="Manage">
                                [<a href="add/?enews=EditFriend&fid=<?=$r[fid]?>&fcid=<?=$cid?>">修改</a>]&nbsp;&nbsp;&nbsp; 
                                [<a href="../doaction.php?enews=DelFriend&fid=<?=$r[fid]?>&fcid=<?=$cid?>" onclick="return confirm('确认要删除?');">删除</a>]
                            </div>
                        </td>
                        </tr>
                        <?php
                            }
                        ?>
                        <tr bgcolor="#FFFFFF"><td height="34" colspan="4"> &nbsp;&nbsp;&nbsp;<?=$returnpage?></td></tr>
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