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
	<div class="textcontent">
<!--    <div class="article_content">-->
		<?=DoWapRepNewstext($r[newstext])?>
	</div>
	<center><a class="show_all" href="javascript:void(0)" style="font-size: 16px"><i class="icon"></i>查看全部</a></center>
<!--    <a class="show_all" href="javascript:void(0)"><i class="icon"></i><div style="text-align: center;height: 50px;padding-top: 8px;text-decoration-color: red" id="quanwenjiazai"><h3 style="color: rgb(168,0,0)" onclick="quanwen()"><img style="height: 10px" src="hgh/images/search-red_08.png"> 余下全文</h3> </div></a>-->
<!--	<div style="text-align: center;height: 50px;padding-top: 8px;text-decoration-color: red" id="quanwenjiazai"><h3 style="color: rgb(168,0,0)" onclick="quanwen()"><img style="height: 10px" src="hgh/images/search-red_08.png"> 余下全文</h3> </div>-->
		<a href="#top" class="to_top icon" title="返回顶部" style="display: block;"></a>
	<div class="article_other">
		<div class="share">
			<h2 style="text-align: center">+分享到</h2>
			<div>
				<!-- JiaThis Button BEGIN -->
				<div class="jiathis_style_32x32">
					<a class="jiathis_button_tsina"></a>
					<a class="jiathis_button_qzone"></a>
					<a class="jiathis_button_tqq"></a>
					<a href="http://www.jiathis.com/share?uid=1868532" class="jiathis jiathis_txt jiathis_separator jtico jtico_jiathis" target="_blank"></a>
<!--					<a class="jiathis_counter_style"></a>-->
				</div>
				<script type="text/javascript" >
					var jiathis_config={
						summary:"",
						shortUrl:true,
						hideMore:true
					}
				</script>
				<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js?uid=1868532" charset="utf-8"></script>
				<!-- JiaThis Button END -->
			</div>
		</div>
    </div>
    <script>function quanwen() {
		document.getElementById("zhengwen").style.maxHeight = "none";
		document.getElementById("quanwenjiazai").hidden="hidden";
	}</script>
	<center><div><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<!-- 红歌会网手机板内容页底部 -->
			<ins class="adsbygoogle"
				 style="display:inline-block;width:320px;height:100px"
				 data-ad-client="ca-pub-6654006650981234"
				 data-ad-slot="3087636308"></ins>
			<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
			</script>
		</div></center>
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