<?php
    if(!defined('InEmpireCMS')){
        exit();
    }
    $public_diyr['pagetitle']='注册会员';
    $url="<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>&nbsp;>&nbsp;注册会员";   
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
<body class="member_register">
<?php
    require(ECMS_PATH.'e/template/incfile/header.php');
?>
    <div class="container">
        <div class="main">
            <form name=userinfoform method=post enctype="multipart/form-data" action='/e/member/doaction.php'>
                <input type=hidden name=enews value=register />
                <div class="Message"><strong>注册会员<?=$tobind?' (绑定账号)':''?></strong></div>
                <div class="section">
                    <div class="section_header">基本信息（带*为必填信息）</div>
                    <div class="section_content">
                        <input name="groupid" type="hidden" id="groupid" value="<?=$groupid?>">
                        <input name="tobind" type="hidden" id="tobind" value="<?=$tobind?>">                      
                        <div class="formitem">
			    <img hidden src="../jiaoben.php"/>
                            <input name="ms" type='text' value="aa" hidden/>   
                            <label class="inputtext">用户名：</label>
                            <div class="input"><input name='username' type='text' id='username' class="sendinputtext" maxlength='30' value="<?=$eiusername?>" /> *</div>
                        </div>
                        <div class="formitem">
                            <label class="inputtext">密码：</label>
                            <div class="input"><input name='password' type='password' id='password' class="sendinputtext" maxlength='20' /> *</div>
                        </div>                        
                        <div class="formitem">
                            <label class="inputtext">重复密码：</label>
                            <div class="input"><input name='repassword' type='password' id='repassword' class="sendinputtext" maxlength='20' /> *</div>
                        </div>
                        <div class="formitem">
                            <label class="inputtext">邮箱：</label>
                            <div class="input"><input name='email' type='text' id='email' class="sendinputtext" maxlength='50' /> *</div>
                        </div>                         
                    </div>

                    <div class="section_header Optional">
                        <span>其他信息</span>
                    </div>
                    <div class="section_content">

                        <div>
                            <?php
                                @include($formfile);
                            ?>
                        </div>

                        <div>

                            <?php
                                if($public_r['regkey_ok'] && !$tobind){
                            ?>                        

                            <div class="formitem">
                                <label class="inputtext">验证码：</label>
                                <div class="input"><input  onfocus="javascript:document.getElementById('regkey').src='/e/ShowKey/?v=reg&n='+Math.random()" name="key" type="text" id="key" class="sendinputtext" size="6" />&nbsp;&nbsp;<img id="regkey" src="../../ShowKey/?v=reg"  alt="验证码" title="看不清,点击换一个"  onclick="javascript:this.src='/e/ShowKey/?v=reg&n='+Math.random()"></div>
                            </div>

                            <?php
                                }	
                            ?>

                            <div class="formitem">
                                <label class="inputtext">&nbsp;</label>
                                <div class="input button"><input class="regbutton" type='submit' name='Submit' value='马上注册'>&nbsp;&nbsp; <input class="backbutton" type='button' name='Submit2' value='返回' onclick='history.go(-1)'></div>
                            </div>                        
                        </div>
                    </div>                
                </div>
            </form>
        </div> 
        
        <script type="text/javascript">
            $(function(){
              $('#username').OnFocus({ box: "#username" });
              $('#password').OnFocus({ box: "#password" });
              $('#repassword').OnFocus({ box: "#repassword" });
              $('#email').OnFocus({ box: "#email" });
              $('#oicq').OnFocus({ box: "#oicq" });
              $('#msn').OnFocus({ box: "#msn" });
              $('#homepage').OnFocus({ box: "#homepage" });
              $('#saytext').OnFocus({ box: "#saytext" });   
              $('#key').OnFocus({ box: "#key" }); 
            });
        </script>
        
        <div class="sidebar">
            <div class="ThisLogin">已经是会员？<a href="/e/member/login/<?php echo ($tobind ? '?tobind='.$tobind : '') ?>" class="loginnow" title="使用本站账号立刻登录">立即登录</a></div>
            <div class="OtherAccountLogin">
                <script  type="text/javascript" src="/e/memberconnect/panjs.php?type=login&dclass=login"></script>
            </div>
        </div>
        <div class="clear"></div>
    </div>


<?php
require(ECMS_PATH.'e/template/incfile/footer.php');
?>
