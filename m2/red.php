<?php
require("../e/class/connect.php");
require("../e/class/db_sql.php");
require("../e/data/dbcache/class.php");
$link=db_connect();
$empire=new mysqlquery();
define('WapPage','index');
require("wapfun.php");



$search='';
$search.="&amp;style=$wapstyle";

$page=intval($_GET['page']);
$line=30;//每页显示记录数
$offset=$page*$line;
$query="select id,classid,title,titlepic,ftitle,newstime,author from {$dbtbpre}ecms_article where classid in(66,67,68) ";
$totalnum=intval($_GET['totalnum']);
if($totalnum<1)
{
	$totalquery="select count(*) as total from {$dbtbpre}ecms_article where classid in(66,67,68) and isgood=1 ";
	$num=$empire->gettotal($totalquery);//取得总条数
}
else
{
	$num=$totalnum;
}
$search.="&amp;totalnum=$num";
//排序

$query.=" order by newstime desc limit $offset,$line";
$c_sql=$empire->query($query);
$returnpage=DoWapListPage($num,$line,$page,$search);


if(!defined('InEmpireCMS'))
{
	exit();
}
$add='';
$bclassid=52;
if($pr['wapshowmid'])
{
	$add=" and modid in (".$pr['wapshowmid'].")";
}
$pcdirurl ='http://www.szhgh.com/Article/red-china/';
DoWapHeader($pagetitle);
?>
<article id='Wapper'>
	<section class='column'>
		<h2><a>红色中国</a> <div class='jian'></div></h2>
		<div id="content">

			<?php
			$mhtml = '';
			while($r=$empire->fetch($c_sql))
			{
				$mhtml .=<<<VX_DEND
<section>
	<a href="show.php?classid={$r['classid']}&id={$r['id']}&style={$wapstyle}&cpage={$page}&cid={$classid}&bclassid={$bclassid}">
		<h3>{$r['title']}</h3>
	</a>
</section>
VX_DEND;
			}
			echo $mhtml;
			if($returnpage)
			{
				echo $returnpage;
			}
			?>
		</div>
	</section>


<?php
DoWapFooter();

db_close();
$empire=null;
?>