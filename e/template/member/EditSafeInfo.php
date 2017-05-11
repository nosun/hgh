<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']='修改安全信息';
$url="<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>&nbsp;>&nbsp;修改安全信息";
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
<body class="member_cp EditSafeInfo">
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
                    <form name=userinfoform method=post enctype="multipart/form-data" action=../doaction.php>
                        <input type=hidden name=enews value=EditSafeInfo />
                        <input type='hidden' name='userid' value='<?=$_REQUEST["userid"] ?>' />
                        <div class="formitem">
                            <label class="inputtext">用户名</label>
                            <div class="input">
                                <?php
                                 if($UserInitial){
                                ?>
                                <input name='username' type='text' id='username' class="sendinputtext" value='<?=$user["username"]?>' size="38" maxlength='30' />&nbsp;
                                <?php
                                 }
                                 else
                                  echo $user['username'];
                                ?>
                            </div>
                        </div>
                        <?php
                         if(!$UserInitial){
                         ?>
                        <div class="formitem">
                            <label class="inputtext">原密码</label>
                            <div class="input">
                                <input name='oldpassword' type='password' id='oldpassword' class="sendinputtext" size="38" maxlength='20' />&nbsp;(修改密码或邮箱时需要密码验证)
                            </div>
                        </div>      
                         <?php
                         }
                          ?>
                         <div class="formitem">
                            <label class="inputtext">新密码</label>
                            <div class="input">
                                <input name='password' type='password' id='password' class="sendinputtext" size="38" maxlength='20' />&nbsp;(<?php if(!$UserInitial) echo '不想修改请留空';else echo '请尽量设置较复杂的密码!'; ?>)
                            </div>
                        </div>
                        <div class="formitem">
                            <label class="inputtext">确认新密码</label>
                            <div class="input">
                                <input name='repassword' type='password' id='repassword' class="sendinputtext" size="38" maxlength='20' />&nbsp;(<?php if(!$UserInitial) echo '不想修改请留空';else echo '与上个文本框的内容相同!'; ?>)
                            </div>
                        </div>                        
                        <div class="formitem">
                            <label class="inputtext">邮箱</label>
                            <div class="input">
                                <input name='email' type='text' id='email' class="sendinputtext" value='<?=$user[email]?>' size="38" maxlength='50' />&nbsp;<?php if(((int)$public_r['regacttype'])>0) echo '(修改邮箱需要验证)'; ?>
                            </div>
                        </div> 
                        <div class="formitem button">
                            <label class="inputtext">&nbsp;</label>
                            <div class="input">
                                <input class="modifybutton" type='submit' name='Submit' value='修改信息' />
                            </div>
                        </div>  
                     </form>   
                </div>
            </div>
            
            <script type="text/javascript">
                $(function(){
                  $('#oldpassword').OnFocus({ box: "#oldpassword" });
                  $('#password').OnFocus({ box: "#password" });
                  $('#repassword').OnFocus({ box: "#repassword" });
                  $('#email').OnFocus({ box: "#email" });
                });
            </script>
            
        </div>
        <div class="clear"></div>        
    </div>
    <?php
    require(ECMS_PATH.'e/template/incfile/footer.php');
    ?>