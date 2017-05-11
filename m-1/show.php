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
	<p><br/>
		<center><h1><?=DoWapClearHtml($r[title])?></h1><br/>
	<b>作者:</b><?=DoWapRepF($r[author],'author',$ret_r)?><br/>
	<b>日期:</b><?=date("Y-m-d H:i:s",$r[newstime])?><br/></center></p>
		<hr />	
	</article>	
	<div class="textcontent">
	<?=DoWapRepNewstext($r[newstext])?>
	</div>
	<p><br/>
	<section class='column'>
		<section><a href="<?=$listurl?>"><h2>返回列表</h2></a> </section><section><a href="index.php?style=<?=$wapstyle?>"><h2>网站首页</h2></a></p></section>
	</section>
</article>
<?php
DoWapFooter();
db_close();
$empire=null;
?>