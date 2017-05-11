<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']='查看消息';
$url="<a href=../../../../>首页</a>&nbsp;>&nbsp;<a href=../../cp/>会员中心</a>&nbsp;>&nbsp;<a href=../../msg/>消息列表</a>&nbsp;>&nbsp;查看消息";
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
</head>
<body class="member_cp Msg ReadMsg">
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
                    <form name="form1" method="post" action="../../doaction.php">
                        <h3><?=stripSlashes($r[title])?></h3>
                        <div class="MsgInfo">
                            <span class="MsgFrom"><a href="../../ShowInfo/?userid=<?=$r[from_userid]?>"><?=$r[from_username]?></a></span>
                            <span class="MsgTime"><?=$r[msgtime]?></span>
                        </div>
                        <div class="MsgContent">
                            <?=nl2br(stripSlashes($r[msgtext]))?>
                        </div>
                        <div class="MsgManege">
                            <a href="#ecms" onclick="javascript:history.go(-1);">返回</a> 
                            <a href="../AddMsg/?enews=AddMsg&re=1&mid=<?=$mid?>">回复</a> 
                            <a href="../AddMsg/?enews=AddMsg&mid=<?=$mid?>">转发</a> 
                            <a href="../../doaction.php?enews=DelMsg&mid=<?=$mid?>" onclick="return confirm('确认要删除?');">删除</a>
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