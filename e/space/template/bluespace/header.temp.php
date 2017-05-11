<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
//公告
/*$spacegg='';
if($addur['spacegg'])
{
	$spacegg='<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#96C8F1">
  <tr>
    <td background="template/default/images/bg_title_sider.gif"><b>公告</b></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            '.$addur['spacegg'].'
          </td>
        </tr>
      </table></td>
  </tr>
</table>
<br>';
}*/
//注册时间
if($user_register)
{
	$registertime=date("Y-m-d H:i:s",$ur[$user_registertime]);
}
else
{
	$registertime=$ur[$user_registertime];
}
//导航菜单
$dhmenu='';
$modsql=$empire->query("select mid,qmname from {$dbtbpre}enewsmod where usemod=0 and showmod=0 and qenter<>'' order by myorder,mid");
while($modr=$empire->fetch($modsql))
{
	$dhmenu.="<li><a href=\"list.php?userid=$userid&mid=$modr[mid]\"><span>".$modr[qmname]."</span></a></li>";
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?=$spacename?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="<?=$spacename?>" name="keywords" />
<meta content="<?=$spacename?>" name="description" />
<link href="template/bluespace/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="headTop" class="aGray grayA">
  <div class="logo" title="网站主页"> </div>
  <div id="topBar">
    <ul class="userMenu green">
      <li><a href="<?=$public_r[newsurl]?>" title="网站主页">网站主页</a></li>
      <li><a href="<?=$public_r[newsurl]?>e/member/register/" target="_blank" title="注册">注册</a></li>
      <li><a href="<?=$public_r[newsurl]?>e/member/login/" target="_blank" title="登录">登录</a></li>
      <li class="help"><a href="#" title="帮助中心" target="_blank">帮助</a></li>
    </ul>
    <span>
    <script type="text/javascript">
      var now=(new Date()).getHours();
			if(now>0&&now<=6){
				document.write("午夜好，");
			}else if(now>6&&now<=11){
				document.write("早上好，");
			}else if(now>11&&now<=14){
				document.write("中午好，");
			}else if(now>14&&now<=18){
				document.write("下午好，");
			}else{
				document.write("晚上好，");
			}
			</script>
			<?php 
			if($userid)
			{
			?>
    <i><?=$username?></i>你可以选择到
	
	<?php }else{?>
	<i>游客</i>你可以选择到
	<?php }?>
	
	</span> </div>
</div>

<div id="head" class="wrapper">
  <div id="spaceName" class="whiteA aWhite">
    <h1><?=$spacename?></h1>
    <p></p>
  </div>
  <ul id="navMenu" class="fLeftChild">
    <li class="thisClass"><a href="index.php?userid=<?=$userid?>"><span>首页</span></a></li>
  <?=$dhmenu?>
    <li><a href="UserInfo.php?userid=<?=$userid?>"><span>个人资料</span></a></li>
    <li><a href="gbook.php?userid=<?=$userid?>"><span>留言板</span></a></li>
  </ul>
</div>
<div id="navChild"></div>
<div class="clearfix"></div><div class="wrapper mT10">



  <div class="west">
  <!-- //begin icon -->
<dl class="border">
	<dt class="caption"><strong>个人资料</strong></dt>
	<dd class="body" id="userInfo"> <a class="pic" href="UserInfo.php?userid=<?=$userid?>" title="我的资料"> <img src="<?=$userpic?>"> </a>
		<div class="textCenter dashed pB10">
			<h4 class="mT5 mB10"><?=$username?></h4>
				<ul class="w50 buttonBlue grayA">
            <li class="mB5">
			
			<a href="gbook.php?userid=<?=$userid?>"><span>给我留言</span></a></li>
            <li class="mB5"><a href="../member/msg/AddMsg/?username=<?=$username?>"><span>发送消息</span></a></li>
            <li><a href="../member/friend/add/?fname=<?=$username?>"><span>加为好友</span></a></li>
            <li><a href="../member/cp"><span>控制面板</span></a></li>
				</ul>
				<div class="clear"></div>
			</div>
			<ul class="mT10 mL10 mB10 lh20">
          <li>会员等级：<?=$level_r[$ur[$user_group]]['groupname']?></li>
          <li>注册时间：<?=$registertime?></li>
          <li>空间访问：<?=$addur[viewstats]?>次</li>
			</ul>
	</dd>
</dl>
<!-- //end icon --><!-- //begin dirs -->
<dl class="border mT10">
      <dt class="caption"><strong>日志分类</strong></dt>
      <dd class="body lh20">
        <ul class="list2 mB5">
    		<li><a href="list.php?userid=<?=$userid?>&mid=7">所有文档&gt;&gt;</a></li>
        </ul>
      </dd>
    </dl>
<!-- //end dirs -->
<!-- //spacenews -->
<dl class="border mT10">
  <dt class="caption"><strong>空间公告</strong></dt>
 <dd><?=$addur['spacegg']?></dd>
	  
	 
</dl><!-- //最近访客 -->
<!--<dl class="border mT10">
	<dt class="caption"><strong>最近访客</strong></dt>
	<dd class="body">
		</dd>
</dl>--><!-- //begin search -->
<!--<dl class="border mT10">
  <dt class="caption"><strong>文档搜索</strong></dt>
  <dd class="body lh20 pB10">
  	<form action="../plus/search.php" method="post" target="_blank">
	  <input name="mid" value="aa123" type="hidden">
	  <div class="text">
		<label>
		  <input class="ip" name="keyword" size="15" style="width: 100px;" type="text">
		  <input class="bt" name="submit" value=" Go " type="submit">
		</label>
	  </div>
	  </form>
  </dd>
</dl>-->
<!-- //end search --><!-- //links -->
<!--<dl class="border mT10">
    <dt class="caption"><strong>个人书签</strong></dt>
    <dd class="body lh20">
      <ul class="list2 mB5">
      	<li><a href="http://www.dedecms.com/" target="_blank">织梦内容管理系统</a></li>
      </ul>
    </dd>
</dl>  --></div>
