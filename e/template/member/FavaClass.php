<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']='收藏夹分类';
$url="<a href=../../../../>首页</a>&nbsp;>&nbsp;<a href=../../cp/>会员中心</a>&nbsp;>&nbsp;<a href=../../fava/>收藏夹</a>&nbsp;>&nbsp;管理分类";
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
    <script>
        function DelFavaClass(cid){
            var ok;
            ok=confirm("确认要删除?");
            if(ok){
            self.location.href='../../doaction.php?enews=DelFavaClass&cid='+cid;
            }
        }
    </script>    
</head>
<body class="member_cp fava">
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
                    <form name="form1" method="post" action="../../doaction.php">
                        <tr> 
                            <td height="34" bgcolor="#FFFFFF">&nbsp;&nbsp;分类名称: 
                                <input name="cname" type="text" id="cname" class="cname" /> <input class="button button1" type="submit" name="Submit" value="增加" /> 
                        <input name="enews" type="hidden" id="enews" value="AddFavaClass"></td>
                        </tr>
                    </form>
                    </table>
                    <br>
                    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
                    <tr class="header"> 
                        <td width="10%" height="38"> <div align="center">ID</div></td>
                        <td width="56%"><div align="center">分类名称</div></td>
                        <td width="34%"><div align="center">操作</div></td>
                    </tr>
                            <?php
                            while($r=$empire->fetch($sql))
                            {
                            ?>
                    <form name=form method=post action=../../doaction.php>
                        <tr bgcolor="#FFFFFF"> 
                        <td height="34"> <div class="cid"> 
                            <?=$r[cid]?>
                            </div></td>
                        <td><div class="finput"> 
                            <input name="enews" type="hidden" id="enews" value="EditFavaClass" />
                            <input name="cid" type="hidden" value="<?=$r[cid]?>" />
                            <input name="cname" type="text" id="cname" value="<?=$r[cname]?>" />
                            </div></td>
                        <td><div align="center"> 
                            <input type="submit" name="Submit2" value="修改" />
                            &nbsp; 
                            <input type="button" name="Submit3" value="删除" onclick="javascript:DelFavaClass(<?=$r[cid]?>);" />
                            </div></td>
                        </tr>
                    </form>
                            <?php
                            }
                            ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="clear"></div>        
    </div>
    <?php
    require(ECMS_PATH.'e/template/incfile/footer.php');
    ?>