<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']=$word;
$url="<a href=../../../../>首页</a>&nbsp;>&nbsp;<a href=../../cp/>会员中心</a>&nbsp;>&nbsp;<a href=../../friend/?cid=".$fcid.">好友列表</a>&nbsp;>&nbsp;".$word;
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
<body class="member_cp friend AddFriends">
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
                <div class="main_message"><strong><?=$word?></strong></div>
                <div class="section_content">
                    <form name="form1" method="post" action="../../doaction.php">
                        <div class="formitem">
                            <label class="inputtext">用户名</label>
                            <div class="input">
                                <input name="fname" type="text" id="fname" class="sendinputtext" value="<?=$fname?>" />&nbsp;*
                            </div>
                        </div>                    
                        <div class="formitem">
                            <label class="inputtext">所属分类</label>
                            <div class="input">
                                <select name="cid">
                                    <option value="0">不设置</option>
                                    <?=$select?>
                                </select>
                                [<a href="../FriendClass/" target="_blank">管理分类</a>]                                
                            </div>
                        </div>   
                        <div class="formitem">
                            <label class="inputtext">备注</label>
                            <div class="input">
                                <input name="fsay" type="text" id="fname3" class="sendinputtext" value="<?=stripSlashes($r[fsay])?>" size="38" />
                            </div>
                        </div>                    
                        <div class="formitem">
                            <div class="input">
                                <input class="button buttoninput button2" type="submit" name="Submit" value="提交" />
                                <input class="button buttoninput button1" type="reset" name="Submit2" value="重置" />
                                <input name="enews" type="hidden" id="enews" value="<?=$enews?>" />
                                <input name="fid" type="hidden" id="fid" value="<?=$fid?>" />
                                <input name="fcid" type="hidden" id="fcid" value="<?=$fcid?>" />
                                <input name="oldfname" type="hidden" id="oldfname" value="<?=$r[fname]?>" />                                
                            </div>
                        </div>                     
                    </form>
                </div>
                
                <script type="text/javascript">
                    $(function(){
                      $('#fname').OnFocus({ box: "#fname" });
                      $('#fname3').OnFocus({ box: "#fname3" });
                    });
                </script>
                
            </div>
        </div>
        <div class="clear"></div>        
    </div>
    <?php
    require(ECMS_PATH.'e/template/incfile/footer.php');
    ?>