<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=defined('empirecms')?$public_diyr[pagetitle]:'用户控制面板'?> - Powered by EmpireCMS</title>
<meta name="keywords" content="<?=defined('empirecms')?$public_diyr[pagetitle]:'用户控制面板'?>" />
<meta name="description" content="<?=defined('empirecms')?$public_diyr[pagetitle]:'用户控制面板'?>" />
<link href="http://www.szhgh.com/skin/default/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://www.szhgh.com/skin/default/js/tabs.js"></script>
</head>
<body class="listpage">
<div class="header">
    <div class="toolbar">
        <div class="login">
            <script>
                document.write('<script src="http://www.szhgh.com/e/member/login/loginjs.php?t='+Math.random()+'"><'+'/script>');
            </script>
        </div>
        <div class="search"><script src="http://www.szhgh.com/d/js/js/search_news1.js" type="text/javascript"></script></div>
        <div class="top_menu"><a onclick="window.external.addFavorite(location.href,document.title)" href="#ecms">加入收藏</a> | <a onclick="this.style.behavior='url(#default#homepage)';this.setHomePage('http://www.szhgh.com/')" href="#ecms">设为首页</a> | <a href="http://www.szhgh.com/e/member/cp/">会员中心</a> | <a href="http://www.szhgh.com/e/DoInfo/">我要投稿</a> | <a href="http://www.szhgh.com/e/web/?type=rss2&classid=0" target="_blank">RSS<img src="http://www.szhgh.com/skin/default/images/rss.gif" border="0" hspace="2" /></a></div>  
    </div>
    <div class="redlogo">
        <div class="logo"><a href="http://www.szhgh.com/" title="红歌会网首页"><img src="http://www.szhgh.com/skin/default/images/logo.jpg" /></a></div>
        <div class="siteintro_left"></div>
        <div class="siteintro_right"></div>
    </div>
    <div class="menu">
        <span class="firstsub"><a href="http://www.szhgh.com/">首页</a></span><span class="sub"><a href="http://www.szhgh.com/html/mao.html">毛泽东</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=6">红色中国</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=7">唱读讲传</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=31">时政纵横</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=9">网友杂谈</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=10">聚焦转基因</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=11">人民健康</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=12">历史视野</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=32">工农之声</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=14">人民文艺</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=15">读书交流</a></span><span class="sub"><a href="http://www.szhgh.com/html/rank.php">排行榜</a></span><span class="sub"><a href="http://www.szhgh.com/bbs/forum.php">红歌会论坛</a></span><span class="sub"><a href="http://www.szhgh.com/bbs/home.php">红歌会圈子</a></span>
    </div>
</div> 
<table width="100%" border="0" cellspacing="10" cellpadding="0">
<tr valign="top">
<td class="list_content"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="position">
<tr>
<td>现在的位置：<?=$url?>
</td>
</tr>
</table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="box">
        <tr> 
          <td width="300" valign="top"> 
		  <?php
		  $lguserid=intval(getcvar('mluserid'));//登陆用户ID
		  $lgusername=RepPostVar(getcvar('mlusername'));//登陆用户
		  $lggroupid=intval(getcvar('mlgroupid'));//会员组ID
		  if($lggroupid)	//登陆会员显示菜单
		  {
		  ?>
            <table width="100%" border="0" cellpadding="3" cellspacing="1" class="tableborder">
              <tr class="header"> 
                <td height="20" bgcolor="#FFFFFF"> <div align="center"><strong><a href="http://www.szhgh.com/e/member/cp/">功能菜单</a></strong></div></td>
              </tr>
              <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="http://www.szhgh.com/e/member/EditInfo/">修改资料</a></div></td>
              </tr>
              <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="http://www.szhgh.com/e/member/my/">帐号状态</a></div></td>
              </tr>
              <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="http://www.szhgh.com/e/member/msg/">站内信息</a></div></td>
              </tr>
              <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="http://www.szhgh.com/e/member/mspace/SetSpace.php">空间设置</a></div></td>
              </tr>
              <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="http://www.szhgh.com/e/DoInfo/">管理信息</a></div></td>
              </tr>
              <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="http://www.szhgh.com/e/member/fava/">收藏夹</a></div></td>
              </tr>
              <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="http://www.szhgh.com/e/payapi/">在线支付</a></div></td>
              </tr>
              <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="http://www.szhgh.com/e/member/friend/">我的好友</a></div></td>
              </tr>
              <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="http://www.szhgh.com/e/member/buybak/">消费记录</a></div></td>
              </tr>
              <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="http://www.szhgh.com/e/member/buygroup/">在线充值</a></div></td>
              </tr>
              <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="http://www.szhgh.com/e/member/card/">点卡充值</a></div></td>
              </tr>
              <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="#ecms" onclick="window.open('http://www.szhgh.com/e/ShopSys/buycar/','','width=680,height=500,scrollbars=yes,resizable=yes');">我的购物车</a></div></td>
              </tr>
              <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="http://www.szhgh.com/e/ShopSys/ListDd/">我的订单</a></div></td>
              </tr>
			  <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="http://www.szhgh.com/e/member/login/">重新登陆</a></div></td>
              </tr>
              <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="http://www.szhgh.com/e/member/doaction.php?enews=exit" onclick="return confirm('确认要退出?');">退出登陆</a></div></td>
              </tr>
            </table>
			<?php
			}
			else	//游客显示菜单
			{
			?>  
            <table width="100%" border="0" cellpadding="3" cellspacing="1" class="tableborder">
              <tr class="header"> 
                <td height="20" bgcolor="#FFFFFF"> <div align="center"><strong><a href="http://www.szhgh.com/e/member/cp/">功能菜单</a></strong></div></td>
              </tr>
              <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="http://www.szhgh.com/e/member/login/">会员登陆</a></div></td>
              </tr>
              <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="http://www.szhgh.com/e/member/register/">注册帐号</a></div></td>
              </tr>
              <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="http://www.szhgh.com/e/DoInfo/">发布投稿</a></div></td>
              </tr>
              <tr> 
                <td height="25" bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#EBF3FC'"><div align="center"><a href="#ecms" onclick="window.open('http://www.szhgh.com/e/ShopSys/buycar/','','width=680,height=500,scrollbars=yes,resizable=yes');">我的购物车</a></div></td>
              </tr>
            </table>
			<?php
			}
			?>
			</td>
          <td width="85%" valign="top">