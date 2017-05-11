<?php

require("../e/class/connect.php");
require("../e/class/db_sql.php");
require("../e/data/dbcache/class.php");

if(!defined('InEmpireCMS')){
    exit();
}

$link=db_connect();
$empire=new mysqlquery();
define('WapPage','index');
require("wapfun.php");
$pagetitle= "首页";
$search='';
$search.="&amp;style=$wapstyle";

$page=intval($_GET['page']);
$line=30;//每页显示记录数
$offset=$page*$line;


// SQL语句
$select = "select id,classid,title,titlepic,smalltext,newstime,author from {$dbtbpre}ecms_article where ";
$where = " and isgood=1 order by newstime desc limit 10";
$query=$select." firsttitle>=1 and firsttitle<=3 and ispic=1 order by newstime desc limit 3";
$query1=$select." isgood=2 order by newstime desc limit 10";

// SQL查询
$c_sql=$empire->query($query);
$c_sql1=$empire->query($query1);


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
		<div  class="m-list" id="content">
		<?php
                $mhtml = '';$vi = 0;
		while($r=$empire->fetch($c_sql))
		{
            $vi1 = $vi%3;
            $mhtml .=<<<VX_DEND
<section class="li{$vi1}">
	<a href="show.php?classid={$r['classid']}&id={$r['id']}&style={$wapstyle}&cpage={$page}&cid={$classid}&bclassid={$bclassid}">
		<h2>{$r['title']}</h2>
		<div class="img-text">
                    <img src='{$r[titlepic]}' />
                    <p>{$r['smalltext']}</p>
		</div>
	</a>
</section>
VX_DEND;
            $vi++;
                }
            echo $mhtml;

            ?>
            </div>
            
                <!-- 最新推荐 -->
                
		<h2><a>最新推荐</a> <div class='jian'></div></h2>
		<div  class="m-list" id="content1">

		<?php
         $mhtml = '';$vi = 0;
		while($r=$empire->fetch($c_sql1)){
            $vi1 = $vi%2;
            $mhtml .=<<<VX_DEND
<section class="li{$vi1}">
	<h3><a href="show.php?classid={$r['classid']}&id={$r['id']}&style={$wapstyle}&cpage={$page}&cid={$classid}&bclassid={$bclassid}">
		{$r['title']}</a></h3>
</section>
VX_DEND;
            $vi++;
                }
            echo $mhtml;
            ?>
            </div>

<?
    foreach ($class_setting as $key=>$value){
?>


    <!-- <?=$class_r[$value]['classname']?> -->

    <h2><a href="list.php?classid=<?=$value?>"><?=$class_r[$value]['classname']?></a><div class='jian'></div></h2>
    <div  class="m-list" id="content2">
<?
    
    if($value==52){
        $query_class = $select.'classid in(66,67,68)'.$where;
    } else {
        $query_class = $select.ReturnClass($class_r[$value][sonclass]).$where;
    }
    $class_sql = $empire->query($query_class);
    $mhtml = '';$vi = 0;
    while($r=$empire->fetch($class_sql)){
        $vi1 = $vi%2;
        $mhtml .=<<<VX_DEND
<section class="li{$vi1}">
	<h3><a href="show.php?classid={$r['classid']}&id={$r['id']}&style={$wapstyle}&cpage={$page}&cid={$classid}&bclassid={$bclassid}">
		{$r['title']}</a></h3>
</section>
VX_DEND;
        $vi++;
    }
    echo $mhtml."</div>";
}
?>

        </section>

<?php
DoWapFooter();
db_close();
$empire=null;
?>