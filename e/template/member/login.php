<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']='会员登录';
$url="<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>&nbsp;>&nbsp;会员登录";
if(empty($thispagetitle)) $thispagetitle = $public_diyr['pagetitle'];
if(!empty($defname)) $defname=' value="'.  addslashes($defname) . '"'; else $defname='';
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
<body class="member_login">
<?php
require(ECMS_PATH.'e/template/incfile/header.php');
?>
    <div class="container">
        <div class="main">
            <div class="commonstyle1 loginbox">
                <div class="page_message"><strong>会员登录<?=$tobind?' (绑定账号)':''?></strong></div>
                <div class="section_content">

                    <form class="loginform" name="form1" method="post" action="../doaction.php">
                        <input type=hidden name=ecmsfrom value="<?=ehtmlspecialchars($_GET['from'])?>">
                        <input type=hidden name=enews value=login>
                        <input name="tobind" type="hidden" id="tobind" value="<?=$tobind?>">

                        <div class="login">
                            <div id="usernamebox" class="txtfield username">
                                <input class="havewidth" name='username' type='text' id='username' maxlength='30'<?php echo $defname; ?> />&nbsp;&nbsp;
                                                         
                            </div>
                            <div class="txtfield password" id="passwordbox">
                                <input name='password' type='password' id='password' maxlength='30' />
                            </div>  
                            <div class="remember">
                                记住密码：<input name=lifetime type=radio value=0 checked />不保存
                                <input type=radio name=lifetime value=86400 />一天
                                <input type=radio name=lifetime value=2592000 />一个月
                                <input type=radio name=lifetime value=315360000 />永久
                            </div> 
                            <div class="forgetmsg">
                                <a href="../GetPassword/" target="_blank">忘记密码？</a>
                                 <?php
                                    if($public_r['regacttype']==1){
                                ?>
                                &nbsp;&nbsp;<a href="../register/regsend.php" target="_blank">帐号未激活？</a>
                                <?php
                                    }
                                ?>  
                                
                            </div>

                            <?php
                                if($public_r['loginkey_ok']){
                            ?>
                            <div class="formitem">
                                <label class="inputtext">验证码：</label>
                                <div class="input">
                                    <input name="key" type="text" id="key" size="6" />
                                    <img src="../../ShowKey/?v=login"  alt="验证码" title="看不清,点击换一个"  onclick="javascript:this.src='/e/ShowKey/?v=login&n='+Math.random()"/>
                                </div>
                            </div> 
                            <?php
                                }	
                            ?>
                            <input class="inputSub flatbtn-blu" type="submit" name="Submit" value=" 登 录 " />
                  
                        </div>
                    </form>
                </div>                
            </div>
        </div> 
        <div class="sidebar">
            <p>还不是会员？加入会员可以添加其他会员为好友、互相访问及发站内消息，将自己喜欢的文章加入收藏夹，还可以发布自己的文章、推荐好文章给红歌会网站，等等。赶快加入吧，一起寻找志同道合的朋友！</p>
            <div class="regnow">
                <?php if($tobind) { 
                        $tnewregurl = GetEPath().'memberconnect/tobind.php?newreg=1';
                ?>
                <a target='_self' id='notbindlink' href='<?=$tnewregurl ?>' title='注册新的账号并绑定社交账号'><span>不绑定</span></a>                
            <?php }?>
                <input type="button" name="button" value="马上注册" onclick="parent.location.href='../register/<?=$tobind?'?tobind=1':''?>';"></div>
            <?php if(!$tobind) { ?>
     <div>
       <script  type="text/javascript" src="/e/memberconnect/panjs.php?type=login&dclass=login"></script>
    </div>
    <?php } ?>     
        </div>
        <div class="clear"></div>
    </div>

<script type="text/javascript">
    $(function(){
      $('#usernamebox input').OnFocus({ box: "#usernamebox" });
      $('#passwordbox input').OnFocus({ box: "#passwordbox" });
    });
</script>
<?php
require(ECMS_PATH.'e/template/incfile/footer.php');
?>