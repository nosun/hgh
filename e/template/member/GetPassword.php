<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
    $public_diyr['pagetitle']='取回密码';
    $url="<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>&nbsp;>&nbsp;取回密码";
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
<body class="member_getpassword">
<?php
    require(ECMS_PATH.'e/template/incfile/header.php');
?>    
    <div class="container">
        <div class="main">
            <div class="commonstyle1 getpasswordbox">
                <div class="page_message"><strong>取回密码</strong></div>
                <div class="section_content">
                    <form name="GetPassForm" method="POST" action="../doaction.php">
                        <div class="getpassword">
                            <div id="usernamebox" class="txtfield username">
                                <input class="havewidth" name='username' type='text' id='username' maxlength='38' />
                            </div>
                            <div id="emailbox" class="txtfield email">
                                <input name="email" type="text" id="email" size="38" />
                            </div>
                            <div class="showkey">
                                验证码：<input  name="key" type="text" id="key" size="6"> &nbsp;
                                <img id="getpasswordkey" src="../../ShowKey/?v=getpassword" alt="验证码" title="看不清,点击换一个"  onclick="javascript:this.src='/e/ShowKey/?v=getpassword&n='+Math.random()"/>
                            </div>     
                            <input class="inputSub flatbtn-blu" type="submit" name="button" value="提交" />
                            <input name="enews" type="hidden" id="enews" value="SendPassword" />                        
                        </div>
                    </form>
                </div>                
            </div>
        </div> 
        <div class="clear"></div>
    </div>
<script type="text/javascript">
    $(function(){
      $('#usernamebox input').OnFocus({ box: "#usernamebox" });
      $('#emailbox input').OnFocus({ box: "#emailbox" });
    });
</script>
<?php
    require(ECMS_PATH.'e/template/incfile/footer.php');
?>