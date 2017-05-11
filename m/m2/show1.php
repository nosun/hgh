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
	DoWapShowMsg('您来自的链接不存�?,'index.php?style=$wapstyle');
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
	DoWapShowMsg('您来自的链接不存�?,$listurl);
}
if($r['groupid']||$class_r[$classid]['cgtoinfo'])
{
	DoWapShowMsg('此信息不能查�?,$listurl);
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
//存文本内�?
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
        <span><b>作�?</b> <?=DoWapRepF($r[author],'author',$ret_r)?></span>
        <span><b>日期:</b> <?=date("Y-m-d H:i:s",$r[newstime])?></span>
    </div>
    <hr />
	</article>	
	<div class="textcontent">
		<?=DoWapRepNewstext($r[newstext])?>
	</div>
	<br/>
       <div><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- 红歌会网手机板内容页底部 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:320px;height:100px"
     data-ad-client="ca-pub-6654006650981234"
     data-ad-slot="3087636308"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>
	<section class='column'>
		<section><a href="<?=$listurl?>"><h2>返回列表</h2></a> </section>
        <section><h2 class="u-tools"><a href="index.php?style=<?=$wapstyle?>" id="vhomelink">网站首页</a><a class="pclink2" id="j-2pc-2" href="#">电脑�?/a></h2></section>
	</section>
</article>
<?php
DoWapFooter();
db_close();
$empire=null;
?>
