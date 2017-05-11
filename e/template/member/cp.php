<?php
if (!defined('InEmpireCMS')) {
    exit();
}
$public_diyr['pagetitle'] = '会员中心';
$url = "<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>";
if(empty($thispagetitle)) $thispagetitle = $public_diyr['pagetitle'];  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?= $thispagetitle ?></title>
        <link href="/skin/default/css/member.css" rel="stylesheet" type="text/css" />
        <script src="/skin/default/js/jquery-1.8.2.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="/skin/default/js/custom.js"></script>
    </head>
<body class="member_cp">
<?php
 require(ECMS_PATH . 'e/template/incfile/header.php');
?>
        <div class="container">
            <?php
                require(ECMS_PATH . 'e/template/incfile/maintop.php');                
            ?>
            <div class="main">
                <?php
                    require(ECMS_PATH . 'e/template/incfile/sidebar.php');
                ?>  
                <div class="member_main">
                    <div class="main_message"><strong><?=$public_diyr['pagetitle']?></strong></div>
                    <div class="section_content">
                        <div class="formitem">
                            <span class="info"><?= $user[userid] ?></span>
                            <span class="info_name">用户ID：</span>
                        </div>
                        <div class="formitem">
                            <span class="info"><?= $user[username] ?></span>
                            <span class="info_name">用户名：</span>
                        </div>                        
                        <div class="formitem">
                            <span class="info"><?= $registertime ?></span>
                            <span class="info_name">注册时间：</span>
                        </div>
                        <div class="formitem">
                            <span class="info"><?= $level_r[$r[groupid]][groupname] ?></span>
                            <span class="info_name">会员等级：</span>
                        </div>                          
                        <div class="formitem">
                            <span class="info newsmsg"><?= $havemsg ?></span>
                            <span class="info_name">新消息：</span>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div> 

            <div class="clear"></div>

        </div>
<?php
require(ECMS_PATH . 'e/template/incfile/footer.php');
?>