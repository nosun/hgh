<?php
if(!defined('InEmpireCMS'))
{
    exit();
}
$public_diyr['pagetitle']='发送消息';
$url="<a href=../../../../>首页</a>&nbsp;>&nbsp;<a href=../../cp/>会员中心</a>&nbsp;>&nbsp;<a href=../../msg/>消息列表</a>&nbsp;>&nbsp;发送消息";
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
        function DelFriendClass(cid){
            var ok;
            ok=confirm("确认要删除?");
            if(ok){
            self.location.href='../../doaction.php?enews=DelFriendClass&doing=1&cid='+cid;
            }
        }
    </script>    
</head>
<body class="member_cp Msg SendMsg">
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
                    <form action="../../doaction.php" method="post" name="sendmsg" id="sendmsg" class="sendmsg" >
                        <div class="formitem">
                            <label class="inputtext">标题</label>
                            <div class="input">
                                <input name="title" type="text" id="title2" class="sendinputtext" value="<?=ehtmlspecialchars(stripSlashes($title))?>" size="100" />&nbsp;*
                            </div>
                        </div>
                        <div class="formitem">
                            <label class="inputtext">接收者</label>
                            <div class="input">
                                <input name="to_username" type="text" id="to_username2" class="sendinputtext" value="<?=$username?>" />&nbsp;&nbsp;<a class="button button1" href="#EmpireCMS" onclick="window.open('../../friend/change/?fm=sendmsg&f=to_username','','width=250,height=360');">选择好友</a>&nbsp;*
                            </div>
                        </div>                        
                        <div class="formitem">
                            <label class="inputtext">内容</label>
                            <div class="input">
                                <textarea class="msgtext sendinputtext" name="msgtext" cols="102" rows="8" id="textarea"><?=ehtmlspecialchars(stripSlashes($msgtext))?></textarea>&nbsp;*
                            </div>
                        </div>    
                        <div class="formitem">
                            <label class="inputtext">&nbsp;</label>
                            <div>
                                <input class="button buttoninput button2" type="submit" name="Submit" value="发送" />
                                <input class="button buttoninput button1" type="reset" name="Submit2" value="重置" />
                                <input name="enews" type="hidden" id="enews" value="AddMsg" />                                
                            </div>
                        </div>                         
                    </form>

                    <script type="text/javascript">
                        $(function(){
                          $('#title2').OnFocus({ box: "#title2" });
                          $('#to_username2').OnFocus({ box: "#to_username2" });
                          $('#textarea').OnFocus({ box: "#textarea" });
                        });
                    </script>
                    
                </div>
            </div>
        </div>
        <div class="clear"></div>        
    </div>
    <?php
    require(ECMS_PATH.'e/template/incfile/footer.php');
    ?>