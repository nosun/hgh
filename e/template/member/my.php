<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']='帐号状态';
$url="<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>&nbsp;>&nbsp;帐号状态";
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
<body class="member_cp my">
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
                <div class="main_message"><strong>帐号状态</strong></div>
                <div class="section_content">
                    <div class="formitem">
                        <label class="inputtext">用户ID：</label>
                        <div class="input">
                            <?=$user[userid]?>
                        </div>
                    </div>   
                    <div class="formitem">
                        <label class="inputtext">用户名：</label>
                        <div class="input">
                            <?=$user[username]?>&nbsp;&nbsp;(<a href="../../space/?userid=<?=$user[userid]?>" target="_blank">我的会员空间</a>) 
                        </div>
                    </div>                    
                    <div class="formitem">
                        <label class="inputtext">注册时间：</label>
                        <div class="input">
                            <?=$registertime?>
                        </div>
                    </div>                     
                    <div class="formitem">
                        <label class="inputtext">会员等级：</label>
                        <div class="input">
                            <?=$level_r[$r[groupid]][groupname]?>
                        </div>
                    </div>                     
                    <div class="formitem">
                        <label class="inputtext">新短消息：</label>
                        <div class="input msg">
                            <?=$havemsg?>
                        </div>
                    </div>                     
                </div>
            </div>
        </div>

        <div class="clear"></div>        
    </div>
    <?php
    require(ECMS_PATH.'e/template/incfile/footer.php');
    ?>