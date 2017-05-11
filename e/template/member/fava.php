<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']='收藏夹';
$url="<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>&nbsp;>&nbsp;收藏夹";
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
                        <form name="form1" method="post" action="">
                            <tr> 
                            <td width="50%" height="30" bgcolor="#FFFFFF">&nbsp;&nbsp;选择分类: 
                                <select name="cid" id="select" onchange=window.location='../fava/?cid='+this.options[this.selectedIndex].value>
                                <option value="0">显示全部</option>
                                <?=$select?>
                                </select></td>
                            <td width="50%" bgcolor="#FFFFFF"><div align="right"><a class="button button2" href="FavaClass/">管理分类</a>&nbsp;&nbsp;</div></td>
                            </tr>
                        </form>
                    </table>
                    <br />
                    <table width="100%" border="0" cellpadding="3" cellspacing="1" class="tableborder">
                        <form name=favaform method=post action="../doaction.php" onsubmit="return confirm('确认要操作?');">
                            <input type=hidden value=DelFava_All name=enews>
                            <tr class="header"> 
                            <td width="4%" height="25"><div align="center"></div></td>
                            <td width="57%"><div align="center">标题</div></td>
                            <td width="12%"><div align="center">点击</div></td>
                            <td width="20%"><div align="center">收藏时间</div></td>
                            <td width="7%"><div align="center">选择</div></td>
                            </tr>
                            <?php
                                        while($fr=$empire->fetch($sql))
                                        {
                                                if(empty($class_r[$fr[classid]][tbname]))
                                                {continue;}
                                                $r=$empire->fetch1("select title,isurl,titleurl,onclick,classid,id from {$dbtbpre}ecms_".$class_r[$fr[classid]][tbname]." where id='$fr[id]' limit 1");
                                                //标题链接
                                                $titlelink=sys_ReturnBqTitleLink($r);
                                                if(!$r['id'])
                                                {
                                                        $r['title']="此信息已删除";
                                                        $titlelink="#EmpireCMS";
                                                }
                                        ?>
                            <tr bgcolor="#FFFFFF"> 
                            <td height="34"> <div align="center"><img src="../../data/images/dir.gif" border=0></div></td>
                            <td> <div align="left"><a href="<?=$titlelink?>" target=_blank> 
                                <?=stripSlashes($r[title])?>
                                </a></div></td>
                            <td> <div align="center"> 
                                <?=$r[onclick]?>
                                </div></td>
                            <td> <div align="center"> 
                                <?=$fr[favatime]?>
                                </div></td>
                            <td> <div align="center"> 
                                <input name="favaid[]" type="checkbox" id="favaid[]2" value="<?=$fr[favaid]?>">
                                </div></td>
                            </tr>
                            <?php
                                }
                            ?>
                            <tr bgcolor="#FFFFFF"> 
                            <td height="34" colspan="5"> &nbsp;&nbsp;&nbsp; 
                                <?=$returnpage?>
                                &nbsp;&nbsp; <select name="cid">
                                <option value="0">请选择要转移的目标分类</option>
                                <?=$select?>
                                </select> <input type="submit" name="Submit" value="转移选中" onclick="document.favaform.enews.value='MoveFava_All'"> 
                            &nbsp;&nbsp; <input type="submit" name="Submit" value="删除选中" onclick="document.favaform.enews.value='DelFava_All'"></td>
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