<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']='消息列表';
$url="<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>&nbsp;>&nbsp;消息列表&nbsp;&nbsp;(<a href='AddMsg/?enews=AddMsg'>发送消息</a>)";
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
<body class="member_cp Msg  msglist">
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
                    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="msglisttool">
                        <tr>
                        <td width="50%" height="30" bgcolor="#FFFFFF">&nbsp;</td>
                        <td width="50%" bgcolor="#FFFFFF"><div align="right"><a class="button button2" href="AddMsg/?enews=AddMsg">发送消息</a>&nbsp;&nbsp;</div></td>
                        </tr>
                    </table>
                    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
                    <form name="listmsg" method="post" action="../doaction.php" onsubmit="return confirm('确认要删除?');">
                        <tr class="header"> 
                        <td width="4%" height="38"> <div align="center"></div></td>
                        <td width="45%"><div align="center">标题</div></td>
                        <td width="18%"><div align="center">发送者</div></td>
                        <td width="23%"><div align="center">发送时间</div></td>
                        <td width="10%"><div align="center">操作</div></td>
                        </tr>
                        <?php
                                    while($r=$empire->fetch($sql))
                                    {
                                            $img="haveread";
                                            if(!$r[haveread])
                                            {$img="nohaveread";}
                                            //后台管理员
                                            if($r['isadmin'])
                                            {
                                                    $from_username="<a title='后台管理员'><b>".$r[from_username]."</b></a>";
                                            }
                                            else
                                            {
                                                    $from_username="<a href='../ShowInfo/?userid=".$r[from_userid]."' target='_blank'>".$r[from_username]."</a>";
                                            }
                                            //系统信息
                                            if($r['issys'])
                                            {
                                                    $from_username="<b>系统消息</b>";
                                                    $r[title]="<b>".$r[title]."</b>";
                                            }
                                    ?>
                        <tr bgcolor="#FFFFFF"> 
                        <td height="34"> <div align="center"> 
                            <input name="mid[]" type="checkbox" id="mid[]2" value="<?=$r[mid]?>">
                            </div></td>
                            <td class="color1"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr> 
                                <td width="9%"><div align="center"><img src="../../data/images/<?=$img?>.gif" border=0></div></td>
                                <td width="91%"><a href="ViewMsg/?mid=<?=$r[mid]?>"> 
                                <?=stripSlashes($r[title])?>
                                </a></td>
                            </tr>
                            </table></td>
                        <td class="color1"><div align="center"> 
                            <?=$from_username?>
                            </div></td>
                        <td class="color1"><div align="center"> 
                            <?=$r[msgtime]?>
                            </div></td>
                            <td> <div class="color2" align="center">&nbsp;[<a href="../doaction.php?enews=DelMsg&mid=<?=$r[mid]?>" onclick="return confirm('确认要删除?');">删除</a>]</div></td>
                        </tr>
                        <?php
                                    }
                                    ?>
                        <tr bgcolor="#FFFFFF"> 
                        <td><div align="center"> 
                            <input type=checkbox name=chkall value=on onclick=CheckAll(this.form)>
                            </div></td>
                        <td colspan="4"><input class="button button1" type="submit" name="Submit2" value="删除选中" /> 
                            <input name="enews" type="hidden" value="DelMsg_all">              </td>
                        </tr>
                        <tr bgcolor="#FFFFFF"> 
                        <td><div align="center"></div></td>
                        <td colspan="4"> 
                            <?=$returnpage?>              </td>
                        </tr>
                        <tr bgcolor="#FFFFFF"> 
                        <td height="34" colspan="5"><div align="center">说明：<img src="../../data/images/nohaveread.gif" width="14" height="11"> 
                            代表未阅读消息，<img src="../../data/images/haveread.gif" /> 
                            代表已阅读消息.</div></td>
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