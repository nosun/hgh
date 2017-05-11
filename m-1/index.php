<?php
require("../e/class/connect.php");
require("../e/class/db_sql.php");
require("../e/data/dbcache/class.php");
$link=db_connect();
$empire=new mysqlquery();
define('WapPage','index');
require("wapfun.php");
$pagetitle=$public_r['sitename'];
$search='';
$search.="&amp;style=$wapstyle";

$page=intval($_GET['page']);
$line=30;//每页显示记录数
$offset=$page*$line;


$query="select id,classid,title,titlepic,smalltext,newstime,author from {$dbtbpre}ecms_article where firsttitle>=1 and firsttitle<=3 and ispic=1 order by newstime desc limit 3";
$query1="select id,classid,title,titlepic,smalltext,newstime,author from {$dbtbpre}ecms_article where isgood=2 order by newstime desc limit 10";
$query2="select id,classid,title,titlepic,smalltext,newstime,author from {$dbtbpre}ecms_article where classid in(59,60,61,62,63,64,65) and isgood=1 order by newstime desc limit 10";
$query3="select id,classid,title,titlepic,smalltext,newstime,author from {$dbtbpre}ecms_article where classid in(66,67,68) and isgood=1 order by newstime desc limit 10";
$query4="select id,classid,title,titlepic,smalltext,newstime,author from {$dbtbpre}ecms_article where classid in(37,38,39,40,41,42,43,44,45,46) and isgood=1 order by newstime desc limit 10";
$query5="select id,classid,title,titlepic,smalltext,newstime,author from {$dbtbpre}ecms_article where classid in(48,49,50,51) and isgood=1 order by newstime desc limit 10";

$c_sql=$empire->query($query);
$c_sql1=$empire->query($query1);
$c_sql2=$empire->query($query2);
$c_sql3=$empire->query($query3);
$c_sql4=$empire->query($query4);
$c_sql5=$empire->query($query5);
if(!defined('InEmpireCMS'))
{
	exit();
}
$add='';
$bclassid = 4;
if($pr['wapshowmid'])
{
    $add=" and modid in (".$pr['wapshowmid'].")";
}
DoWapHeader($pagetitle);
?>
<article id='Wapper' class="index">
	<section class='column'>
            
                <!-- 今日头条 -->
            
		<h2><a>今日头条</a> <div class='jian'></div></h2>
		<div id="content">

		<?php
                $mhtml = '';
		while($r=$empire->fetch($c_sql))
		{
            $mhtml .=<<<VX_DEND
<section>
	<a href="show.php?classid={$r['classid']}&id={$r['id']}&style={$wapstyle}&cpage={$page}&cid={$classid}&bclassid={$bclassid}">
		<h2>{$r['title']}</h2>
		<div class="img-text">
                    <img src='{$r[titlepic]}' width="110" />
                    <p>{$r['smalltext']}</p>
		</div>
	</a>
</section>
VX_DEND;
                }
            echo $mhtml;

            ?>
            </div>
            
                <!-- 最新推荐 -->
                
		<h2><a>最新推荐</a> <div class='jian'></div></h2>
		<div id="content">

		<?php
                $mhtml = '';
		while($r=$empire->fetch($c_sql1))
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
            ?>
            </div>
                
                
                <!-- 毛泽东 -->
                
		<h2><a href="mzd.php">毛泽东</a> <div class='jian'></div></h2>
		<div id="content">

		<?php
                $mhtml = '';
		while($r=$empire->fetch($c_sql2))
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
            ?>
            </div>
                
                
                
                <!-- 红色中国 -->
                
		<h2><a href="red.php">红色中国</a> <div class='jian'></div></h2>
		<div id="content">

		<?php
                $mhtml = '';
		while($r=$empire->fetch($c_sql3))
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
            ?>
            </div>
                
                
                <!-- 资讯中心 -->
                
		<h2><a href="new.php">资讯中心</a> <div class='jian'></div></h2>
		<div id="content">

		<?php
                $mhtml = '';
		while($r=$empire->fetch($c_sql4))
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
            ?>
            </div>
                
                
                <!-- 纵论天下 -->
                
		<h2><a href="lun.php">纵论天下</a> <div class='jian'></div></h2>
		<div id="content">

		<?php
                $mhtml = '';
		while($r=$empire->fetch($c_sql5))
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
            ?>
            </div>
                
                
                
        </section>

<?php
DoWapFooter();
db_close();
$empire=null;
?>