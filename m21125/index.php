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
if(!empty($_GET['load'])){
    $page = $_COOKIE['page'];
    $line = 10; //每页显示记录数
    $offset = $page * $line;
    $select = "select id,classid,title,titlepic,smalltext,newstime,author from {$dbtbpre}ecms_article where ";
    $query1=$select." isgood=2 order by newstime desc limit $offset,$line";
    $c_sql1=$empire->query($query1);
    $vi = 0;
    while ($r = $empire->fetch($c_sql1)) {
        $vi1 = $vi % 2;
        $time = time()-$r['newstime'];
        $day = floor($time/(3600*24));
        $hour = floor($time/3600);
        $minute = floor($time/60);
        if($day >= 1){
            $passedtimestr = $day."天前";
        }elseif ($hour >= 1){
            $passedtimestr = $hour."小时前";
        }else{
            $passedtimestr = $minute."分钟前";
        }
        $titleurl = "show.php?classid=" . $r[classid] . "&id=" . $r[id] . "&style=" . $wapstyle . "&cpage=" . $page . "&cid=" . $classid . "&bclassid=" . $bclassid;
        ?><?php if(!empty($r[titlepic])){?>
        <section class="li<?= $vi1 ?>">
            <div class="img-text">
                <img style="float:right;" src='<?=$r[titlepic]?>' />
                <a  style="font-size: 15px" href="<?= $titleurl ?>"><b><?= $r[title] ?></b></a>
                <p style="font-size:13px;"><?=$passedtimestr?>  &nbsp; &nbsp;&nbsp;  <?=$class_r[$r['classid']]['classname']?>  &nbsp;&nbsp;  <?=$r['author']?></p>
            </div>
            </section><?php }else{?>
        <section class="li<?= $vi1 ?>" style="">
            <div class="img-text">
                <a  style="font-size: 15px" href="<?= $titleurl ?>"><b><?= $r[title] ?></b></a>
                <p style="font-size:13px;"><?=$passedtimestr?>  &nbsp; &nbsp;&nbsp;  <?=$class_r[$r['classid']]['classname']?>  &nbsp;&nbsp;  <?=$r['author']?></p>
            </div>
            </section>
        <?php }?>
        <?php
        $vi++;
    }
    die;
}


// SQL语句
$select = "select id,classid,title,titlepic,smalltext,newstime,author from {$dbtbpre}ecms_article where ";
$where = " and isgood=1 order by newstime desc limit 10";
$headlinenum = 5;

$query=$select." firsttitle>=1 and firsttitle<=3 and ispic=1 order by newstime desc limit ".$headlinenum;
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
    <div id="slideBox" class="slideBox">

    <div class="bd">
    <ul>

<?php $i = 1;while($r=$empire->fetch($c_sql))
{
    ?>

    <li>
        <a class="pic" href="show.php?classid=<?=$r['classid']?>&id=<?=$r['id']?>&style=<?=$wapstyle?>&cpage=<?=$page?>&cid=<?=$classid?>&bclassid=<?=$bclassid?>" id="headline"><img src="<?=$r['titlepic']?>" /></a>
        <a class="tit" href="show.php?classid=<?=$r['classid']?>&id=<?=$r['id']?>&style=<?=$wapstyle?>&cpage=<?=$page?>&cid=<?=$classid?>&bclassid=<?=$bclassid?>"><span style="font-size: 14px;"> <?=$r['title']?></span><span style="font-size: 14px;float: right;margin-right: 10px"><?=$i?>/<?=$headlinenum?></span></a>
    </li>
<?php $i++;}
?>
    </ul>
        <ul></ul>
    </div>
    <div class="hd">
        <ul></ul>
    </div>
</div>
    <script type="text/javascript">
        TouchSlide({
            slideCell:"#slideBox",
            titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
            mainCell:".bd ul",
            effect:"leftLoop",
            autoPage:true,//自动分页
            autoPlay:true //自动播放
        });
    </script>
<article id='Wapper' class="index">
	<section class='column'>
		<div  class="m-list" id="content">
		<?php
         $mhtml = '';$vi = 0;
		while($r=$empire->fetch($c_sql1)){
            $titleurl = "show.php?classid={$r['classid']}&id={$r['id']}&style={$wapstyle}&cpage={$page}&cid={$classid}&bclassid={$bclassid}";
            $time = time()-$r['newstime'];
            $day = floor($time/(3600*24));
            $hour = floor($time/3600);
            $minute = floor($time/60);
            if($day >= 1){
                $passedtimestr = $day."天前";
            }elseif ($hour >= 1){
                $passedtimestr = $hour."小时前";
            }else{
                $passedtimestr = $minute."分钟前";
            }
            $vi1 = $vi%2;
            $mhtml .=<<<VX_DEND
<section class="li{$vi1}">
<div class="img-text">
                        <a href="$titleurl"> <img style="float:right;" src='{$r[titlepic]}' /></a>
                    <a style="font-size: 15px" href="{$titleurl}"><b>{$r[title]}<br></b></a>
                    <p style="font-size:13px;">{$passedtimestr}  &nbsp; &nbsp;&nbsp; {$class_r[$r['classid']]['classname']}  &nbsp; &nbsp; {$r['author']}</p>
                    </div>
</section>
VX_DEND;
            $vi++;
                }
            echo $mhtml;
            ?>
            </div>
        <section>
            <div style="height: 35px;background-color: white;text-align: center">
                <span style="font-family: 黑体;color: rgb(168,0,0)" onclick="jiazai1()"><img style="height: 8px" src="hgh/images/search-red_08.png">&nbsp;点击加载更多</span>
                <a href="#top" class="to_top icon" title="返回顶部" style="display: block;"></a>
            </div></section>
        </section>
    <script type="text/javascript">
        function jiazai1() {
            var xmlhttp;
            var page;
            if (document.cookie.length>0)
            {
                c_start=document.cookie.indexOf("page" + "=")
                if (c_start!=-1)
                {
                    c_start=c_start + 5;
                    c_end=document.cookie.indexOf(";",c_start);
                    if (c_end==-1) c_end=document.cookie.length;
                    page = unescape(document.cookie.substring(c_start,c_end));
                    var num = Number(page)+1;
                    document.cookie="page="+num;
                }else {
                    document.cookie="page="+1;
                }
            }
            if (window.XMLHttpRequest)
            {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp=new XMLHttpRequest();
            }
            else
            {// code for IE6, IE5
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange=function()
            {
                if (xmlhttp.readyState==4 && xmlhttp.status==200)
                {
                    document.getElementById("content").innerHTML=document.getElementById("content").innerHTML+xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET","index.php?load=1",true);
            xmlhttp.send();
        }
    </script>

<?php
DoWapFooter();
db_close();
$empire=null;
?>