<?php
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/q_functions.php");
require("../class/user.php");
require("../../data/dbcache/MemberLevel.php");
$link=db_connect();
$empire=new mysqlquery();

eCheckCloseMods('member');//关闭模块
//是否登陆
$user=islogin();
$r=ReturnUserInfo($user[userid]);
$addr=$empire->fetch1("select * from {$dbtbpre}enewsmemberadd where userid='".$user[userid]."' limit 1",MYSQL_ASSOC);
//头像
$userpic=$addr['userpic']?$addr['userpic']:$public_r['newsurl'].'e/data/images/nouserpic.jpg';
//简介
$saytext=$addr['saytext']?$addr['saytext']:"暂无简介";
//收藏文章数量
$fava_r=$empire->query("select id from {$dbtbpre}enewsfava where userid='".$user[userid]."'");
$favacounts = mysql_num_rows($fava_r);

//发布文章数量
$article_r=$empire->query("select id from {$dbtbpre}ecms_article where userid='".$user[userid]."'");
$articlecounts = mysql_num_rows($article_r);
//有效期
$userdate=0;
if($r[userdate])
{
	$userdate=$r[userdate]-time();
	if($userdate<=0)
	{
		$userdate=0;
	}
	else
	{
		$userdate=round($userdate/(24*3600));
	}
}
//是否有短消息
$havemsg="<span style='background:url(/e/data/images/nomsg.jpg) no-repeat scroll 0 0 transparent;'><a href='".$public_r['newsurl']."e/member/msg' target=_blank>站内消息</a></span>";
if($user[havemsg]){
    $havemsg="<span style='background:url(/e/data/images/havemsg.jpg) no-repeat scroll 0 0 transparent;'><a href='".$public_r['newsurl']."e/member/msg' target=_blank>站内消息</a></span>";
}
//是否有留言
$gbook_r=$empire->query("select gid from {$dbtbpre}enewsmembergbook where userid='".$user[userid]."'");
$gbookcounts = mysql_num_rows($gbook_r);
$havegbook="<span style='background:url(/e/data/images/nogbook.jpg) no-repeat scroll 0 0 transparent;'><a href='".$public_r['newsurl']."e/member/mspace/gbook.php' target=_blank>空间留言</a></span>";
if($user[havemsg]){
    $havegbook="<span style='background:url(/e/data/images/havegbook.jpg) no-repeat scroll 0 0 transparent;'><a href='".$public_r['newsurl']."e/member/mspace/gbook.php' target=_blank>空间留言</a></span>";
}
//注册时间
$registertime=eReturnMemberRegtime($r['registertime'],"Y-m-d H:i:s");

//好友列表
$friend_result = $empire->query("select fid,fname,userid from {$dbtbpre}enewshy where userid='".$user[userid]."' order by fid ASC limit 9",MYSQL_ASSOC);

while($friend_r=$empire->fetch($friend_result)){
    $friendtemp_r = $empire->fetch1("select userid from {$dbtbpre}enewsmember where username='".$friend_r['fname']."'");
    $friendinfo_r = $empire->fetch1("select userpic from {$dbtbpre}enewsmemberadd where userid='".$friendtemp_r['userid']."'");
    $userpic = $friendinfo_r['userpic'];
    if(empty($userpic)){
        $userpic = '/e/data/images/nouserpic.jpg';
    }
    $friendinfo .= '<li><div><img src="'.$userpic.'" /></div><div class="username"><a href="/e/member/ShowInfo/?username='.$friend_r['fname'].'" target="_blank">'.$friend_r['fname'].'</a></div></li>';
}
if(empty($friendinfo)){
    $friendinfo = '<li>暂无好友</li>';
}
//导入模板
require(ECMS_PATH.'e/template/member/cp.php'); 
db_close();
$empire=null;
?>