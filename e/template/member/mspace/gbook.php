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
$public_diyr['pagetitle']='管理留言';
$url="<a href='../../../'>首页</a>&nbsp;>&nbsp;<a href='../cp/'>会员中心</a>&nbsp;>&nbsp;管理留言";
require(ECMS_PATH.'e/template/incfile/header.php');
?>

    <div class="container">
        <div class="main">
            <?php
                require(ECMS_PATH.'e/template/incfile/maintop.php');
                require(ECMS_PATH.'e/template/incfile/sidebar.php');
            ?> 
            <div class="member_main">
                <div class="main_message"><strong>管理留言</strong></div>
                <div class="section_content gbook">

                    <form name="gbookform" method="post" action="index.php" onsubmit="return confirm('确认要删除?');">
                        <?php
                        while($r=$empire->fetch($sql))
                        {
                            $i++;
                            $bgcolor=" class='tableborder'";
                            if($i%2==0)
                            {
                                $bgcolor=" bgcolor='#ffffff'";
                            }
                            $private='';
                            if($r['isprivate'])
                            {
                                $private='*悄悄话* / ';
                            }
                            $msg='';
                            if($r['uid'])
                            {
                                $msg=" / <a href='../msg/AddMsg/?username=$r[uname]' target='_blank'>消息回复</a>";
                                $r['uname']="<b><a href='../../space/?userid=$r[uid]' target='_blank'>$r[uname]</a></b>";
                            }
                            $gbuname=$private.$r[uname]." / 留言于 ".$r[addtime]." / IP: ".$r[ip].$msg;
                        ?>
                        
                            <div class="gbookitem">
                                <div class="gbookInfoDo">
                                    <span class="left"><input name="gid[]" type="checkbox" id="gid[]" value="<?=$r[gid]?>" />&nbsp;&nbsp;<?=$gbuname?></span>
                                    <span class="right">
                                        [<a href="#ecms" onclick="window.open('ReGbook.php?gid=<?=$r[gid]?>','','width=600,height=380,scrollbars=yes');">回复</a>]
                                        &nbsp;&nbsp;[<a href="index.php?enews=DelMemberGbook&gid=<?=$r[gid]?>" onclick="return confirm('确认要删除?');">删除</a>]
                                    </span>
                                    <div class="clear"></div>
                                </div>
                                <div class="gbookText">
                                    <?=nl2br($r['gbtext'])?>
                                    <?
                                    if($r['retext'])
                                        {
                                    ?>
                                    <div class="gbookRetext">
                                        <img src="../../data/images/regb.gif" width="18" height="18" />
                                        <strong><font color="#FF0000">回复:</font></strong>
                                        <?=nl2br($r['retext'])?>  
                                    </div>
                                    <?
                                        }
                                    ?>
                                </div>
                            </div>
                        
                        <?
                        }
                        ?>
                        <div class="batch">
                            <div class="left">全选：<input type='checkbox' name='chkall' value='on' onClick='CheckAll(this.form)' /></div>
                            <div class="left">
                                <?=$returnpage?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type='submit' name='submit' value='批量删除' />
                                <input name="enews" type="hidden" id="enews" value="DelMemberGbook_All" />                                
                            </div>
                            <div class="clear"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="clear"></div>        
    </div>
<?php
require(ECMS_PATH.'e/template/incfile/footer.php');
?>