<?php
require("../e/class/connect.php");
require("../e/class/db_sql.php");
require("../e/class/q_functions.php");
require("../e/data/dbcache/class.php");
$link=db_connect();
$empire=new mysqlquery();
define('WapPage','show');
require("wapfun.php");
$classid=(int)$_GET['classid'];
$id=(int)$_GET['id'];
if(!$classid||!$class_r[$classid]['tbname']||!$id)
{
	DoWapShowMsg('您来自的链接不存在','index.php?style=$wapstyle');
}
$cpage=(int)$_GET['cpage'];
$cid=(int)$_GET['cid'];
$bclassid=(int)$_GET['bclassid'];
if(empty($cid))
{
	$cid=$classid;
}
$listurl="list.php?style=".$wapstyle."&amp;page=".$cpage."&amp;classid=".$cid."&amp;bclassid=".$bclassid;
$r=$empire->fetch1("select * from {$dbtbpre}ecms_".$class_r[$classid]['tbname']." where id='$id'");
if(!$r['id']||$classid!=$r[classid])
{
	DoWapShowMsg('您来自的链接不存在',$listurl);
}
if($r['groupid']||$class_r[$classid]['cgtoinfo'])
{
	DoWapShowMsg('此信息不能查看',$listurl);
}
//系统模型
$modid=$class_r[$classid][modid];
//副表
if($emod_r[$modid]['tbdataf']&&$emod_r[$modid]['tbdataf']<>',')
{
	$selectdataf=substr($emod_r[$modid]['tbdataf'],1,-1);
	$finfor=$empire->fetch1("select newstext from {$dbtbpre}ecms_".$class_r[$classid]['tbname']."_data_".$r[stb]." where id='$r[id]'");
	$r=array_merge($r,$finfor);
}
$ret_r=ReturnAddF($modid,1);

$pagetitle=DoWapClearHtml($r['title']);
//存文本内容
$savetxtf=$emod_r[$modid]['savetxtf'];
if($savetxtf&&$r[$savetxtf])
{
	$r[$savetxtf]=GetTxtFieldText($r[$savetxtf]);
}
//分页字段
$pagef=$emod_r[$modid]['pagef'];
if($pagef&&$r[$pagef])
{
	//替换掉分页符
	$r[$pagef]=str_replace('[!--empirenews.page--]','',$r[$pagef]);
	$r[$pagef]=str_replace('[/!--empirenews.page--]','',$r[$pagef]);
}

if(!defined('InEmpireCMS'))
{
	exit();
}
DoWapHeader($pagetitle);
?>
	<article id='Wapper'>
		<h1><?=DoWapClearHtml($r[title])?></h1>
		<div class="u-proinfo">
			<span><b>作者:</b> <?=DoWapRepF($r[author],'author',$ret_r)?></span>
			<span><b>日期:</b> <?=date("Y-m-d H:i:s",$r[newstime])?></span>
		</div>
		<hr />
		<div class="top"></div>
	</article>
<!--	<div class="textcontent" id="zhengwen" style=" max-height: 700px;overflow: hidden;">-->
	<div class="textcontent" id="zhengwen">
<!--    <div class="article_content">-->
		<?=DoWapRepNewstext($r[newstext])?>
	</div>
	<center><a class="show_all" href="javascript:void(0)" style="font-size: 16px;color: rgb(168,0,0);padding-bottom: 10px"><i class="icon"></i>查看全部</a></center>
<!--    <a class="show_all" href="javascript:void(0)"><i class="icon"></i><div style="text-align: center;height: 50px;padding-top: 8px;text-decoration-color: red" id="quanwenjiazai"><h3 style="color: rgb(168,0,0)" onclick="quanwen()"><img style="height: 10px" src="hgh/images/search-red_08.png"> 余下全文</h3> </div></a>-->
<!--	<div style="text-align: center;height: 50px;padding-top: 8px;text-decoration-color: red" id="quanwenjiazai"><h3 style="color: rgb(168,0,0)" onclick="quanwen()"><img style="height: 10px" src="hgh/images/search-red_08.png"> 余下全文</h3> </div>-->
		<a href="#top" class="to_top icon" title="返回顶部" style="display: block;"></a>
<?php
$data['keyboard']= $r['keyboard'];
$data['id']=(int)$_GET['id']; ?>
	<div class="article_other" style="">
		<div class="share" style="width:100%;margin: 0px;padding-top: 15px">
			<div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a><a href="#" class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"></a><a href="#" class="bds_douban" data-cmd="douban" title="分享到豆瓣网"></a></div>
			<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"32"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
		</div>
		<div class="related_article" style="border-bottom: 3px solid rgb(230,230,230)">
			<h2 style="color: rgb(102,102,102);background-color: white;padding-left: 0px;;border-bottom:0px;font-size: 16px">相关文章</h2>
			<?=DoNews($data,1)?>
		</div>
		<div class="recommend_new" style="margin-bottom: 5px">
			<h2 style="color: rgb(102,102,102);padding-left: 0px;border-bottom:0px;font-size: 16px">最新推荐</h2>
			<div style="padding-right: 0%"><?=DoNews($data,2)?></div>
		</div>
		<div class="hot_twodays" style="">
			<h2 style="color: rgb(102,102,102);padding-left: 0px;border-bottom:0px;font-size: 16px;padding-top:15px;border-top: 4px solid rgb(230,230,230);">两日热点</h2>
			<?=DoNews($data,3)?>
		</div>
    </div>
    <script>function quanwen() {
		document.getElementById("zhengwen").style.maxHeight = "none";
		document.getElementById("quanwenjiazai").hidden="hidden";
	}</script>
<!--	<center><div><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>-->
			<!-- 红歌会网手机板内容页底部 -->
<!--			<ins class="adsbygoogle"-->
<!--				 style="display:inline-block;width:320px;height:100px"-->
<!--				 data-ad-client="ca-pub-6654006650981234"-->
<!--				 data-ad-slot="3087636308"></ins>-->
<!--			<script>-->
<!--				(adsbygoogle = window.adsbygoogle || []).push({});-->
<!--			</script>-->
<!--		</div></center>-->
	<section class='column'>
		<section><a href="<?=$listurl?>"><h2>返回列表</h2></a> </section>
		<!--WAP版-->
		<div id="SOHUCS" sid="<?php echo $_GET['classid'].$_GET['id']; ?> " ></div>
		<script id="changyan_mobile_js" charset="utf-8" type="text/javascript" src="http://changyan.sohu.com/upload/mobile/wap-js/changyan_mobile.js?client_id=cysG16cIx&conf=prod_eb841ff6ba533cd3f322c05e9252c602">
		</script>
	</section>
	</article>
<?php
DoWapFooter();
db_close();
$empire=null;
?>