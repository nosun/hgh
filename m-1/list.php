<?php
require("../e/class/connect.php");
require("../e/class/db_sql.php");
require("../e/class/q_functions.php");
require("../e/data/dbcache/class.php");
$link=db_connect();
$empire=new mysqlquery();
define('WapPage','list');
require("wapfun.php");

//栏目ID
$classid=(int)$_GET['classid'];
if(!$classid||!$class_r[$classid]['tbname'])
{
	DoWapShowMsg('您来自的链接不存在','index.php?style=$wapstyle');
}
$pagetitle=$class_r[$classid]['classname'];

$bclassid=(int)$_GET['bclassid'];
$search='';
$add='';
if($class_r[$classid]['islast'])
{
	$add.=" and classid='$classid'";
}
else
{
	$where=ReturnClass($class_r[$classid][sonclass]);
	$add.=" and (".$where.")";
}
$modid=$class_r[$classid][modid];

$search.="&amp;style=$wapstyle&amp;classid=$classid&amp;bclassid=$bclassid";

$page=intval($_GET['page']);
$line=30;//每页显示记录数
$offset=$page*$line;
$query="select ".ReturnSqlListF($modid)." from {$dbtbpre}ecms_article where isgood > 0 ".$add;
$totalnum=intval($_GET['totalnum']);
if($totalnum<1)
{
	$totalquery="select count(*) as total from {$dbtbpre}ecms_article where isgood > 0 ".$add;
	$num=$empire->gettotal($totalquery);//取得总条数
}
else
{
	$num=$totalnum;
}
$search.="&amp;totalnum=$num";
//排序
if(empty($class_r[$classid][reorder]))
{
	$addorder="newstime desc";
}
else
{
	$addorder=$class_r[$classid][reorder];
}
$query.=" order by newstime desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=DoWapListPage($num,$line,$page,$search);
//系统模型
$ret_r=ReturnAddF($modid,2);

if(!defined('InEmpireCMS'))
{
	exit();
}
$add='';
$bclassid=4;
if($pr['wapshowmid'])
{
	$add=" and modid in (".$pr['wapshowmid'].")";
}
DoWapHeader($pagetitle);
?>
<article id='Wapper'>
	<section class='column'>

		<h2><a href="list.php?classid=<?=$classid?>&style=0&bclassid=0"><?=$class_r[$classid][classname]?></a> <div class='jian'></div></h2>	
		<div id="content">			
		
		<?php
		while($r=$empire->fetch($sql))
		{
			$titleurl="show.php?classid=".$r[classid]."&amp;id=".$r[id]."&amp;style=".$wapstyle."&amp;cpage=".$page."&amp;cid=".$classid."&amp;bclassid=".$bclassid;
		?>
		  <section>
			<a href="<?=$titleurl?>">
			  <h3><?=$r[title]?></h3>
			  <div class="img-text">
				<p>作者：<?=$r[author]?><br />
				最后更新：<?=date('Y-m-d h:i',$r[newstime])?></p>
			  </div>
			</a>
		  </section>		
	
	<?php

		}
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