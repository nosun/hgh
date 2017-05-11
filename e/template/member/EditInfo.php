<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
        $public_diyr['pagetitle']='修改资料';
        $url="<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>&nbsp;>&nbsp;修改资料";
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
<body class="member_cp modify_info">
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
                    <form name="userinfoform" method="post" enctype="multipart/form-data" action="../doaction.php">
                        <input type="hidden" name="enews" value="EditInfo" />                    
                        <div class="formitem">
                            <label class="inputtext">用户名</label>
                            <div class="input">
                                <?=$user[username]?>
                            </div>
                        </div>

                        <?php
                        @include($formfile);
                        ?>  

                        <div class="fromitem button">
                            <label class="inputtext">&nbsp;</label>
                            <div class="input">
                                <input class="modifybutton" type='submit' name='Submit' value='修改信息' />
                            </div>
                        </div>                    

                    </form>
                    
                    <script type="text/javascript">
                        $(function(){
                          $('#oicq').OnFocus({ box: "#oicq" });
                          $('#msn').OnFocus({ box: "#msn" });
                          $('#homepage').OnFocus({ box: "#homepage" });
                          $('#saytext').OnFocus({ box: "#saytext" });
                        });
                    </script>
                    
                    <div class="formitem">
                        <label class="inputtext">&nbsp;</label>
                        <div class="input">
                            <div align="right">[<a href="EditSafeInfo.php">密码安全修改</a>]&nbsp;&nbsp;</div>
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