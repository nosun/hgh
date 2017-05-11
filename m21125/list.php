<?php
require("../e/class/connect.php");
require("../e/class/db_sql.php");
require("../e/class/q_functions.php");
require("../e/data/dbcache/class.php");
$link = db_connect();
$empire = new mysqlquery();
define('WapPage', 'list');
require("wapfun.php");
if(!empty($_GET['load'])){
    $classid = $_GET['classid'];
    $modid = $class_r[$classid][modid];
    $add = '';
    $page = $_COOKIE['page'];
    $line = 30; //每页显示记录数
    $offset = $page * $line;
    if ($class_r[$classid]['islast']) {
        $add .= " and classid='$classid'";
    } else {
        $where = ReturnClass($class_r[$classid][sonclass]);
        $add .= " and (" . $where . ")";
    }
    $query = "select " . ReturnSqlListF($modid) . " from {$dbtbpre}ecms_article where isgood > 0 " . $add;
    $query .= " order by newstime desc limit $offset,$line";
    $sql = $empire->query($query);
    $vi = 0;
    while ($r = $empire->fetch($sql)) {
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
                <p style="font-size:13px;"><?=$passedtimestr?> &nbsp; &nbsp;&nbsp;    <?=$class_r[$r['classid']]['classname']?> &nbsp;&nbsp;  <?=$r['author']?></p>
            </div>
            </section><?php }else{?>
        <section class="li<?= $vi1 ?>">
            <div class="img-text">
                <a  style="font-size: 15px" href="<?= $titleurl ?>"><b><?= $r[title] ?></b></a>
                <p style="font-size:13px;"><?=$passedtimestr?> &nbsp; &nbsp;&nbsp;    <?=$class_r[$r['classid']]['classname']?> &nbsp;&nbsp;  <?=$r['author']?></p>
            </div>
            </section><?php }?>
        <?php
        $vi++;

//    $query =
    }
    die;
}

//栏目ID
$classid = (int)$_GET['classid'];
if (!$classid || !$class_r[$classid]['tbname']) {
    DoWapShowMsg('您来自的链接不存在', 'index.php?style=$wapstyle');
}
$pagetitle = $class_r[$classid]['classname'];

$bclassid = (int)$_GET['bclassid'];

$search = '';
$add = '';
if ($class_r[$classid]['islast']) {
    $add .= " and classid='$classid'";
} else {
    $where = ReturnClass($class_r[$classid][sonclass]);
    $add .= " and (" . $where . ")";
}
$modid = $class_r[$classid][modid];

$search .= "&style=$wapstyle&classid=$classid&bclassid=$bclassid";
//$
$page = intval($_GET['page']);
$line = 30; //每页显示记录数
$offset = $page * $line;
$query = "select " . ReturnSqlListF($modid) . " from {$dbtbpre}ecms_article where isgood > 0 " . $add;
$totalnum = intval($_GET['totalnum']);
if ($totalnum < 1) {
    $totalquery = "select count(*) as total from {$dbtbpre}ecms_article where isgood > 0 " . $add;
    $num = $empire->gettotal($totalquery); //取得总条数
} else {
    $num = $totalnum;
}
$search .= "&totalnum=$num";
//排序
if (empty($class_r[$classid][reorder])) {
    $addorder = "newstime desc";
} else {
    $addorder = $class_r[$classid][reorder];
}
$query .= " order by newstime desc limit $offset,$line";
$sql = $empire->query($query);
$returnpage = DoWapListPage($num, $line, $page, $search);
//系统模型
$ret_r = ReturnAddF($modid, 2);

if (!defined('InEmpireCMS')) {
    exit();
}
$add = '';
$bclassid = 4;
if ($pr['wapshowmid']) {
    $add = " and modid in (" . $pr['wapshowmid'] . ")";
}
$pcdirurl = sys_ReturnBqClassname($_GET,9);
DoWapHeader($pagetitle);
?>
<article id='Wapper'>
    <section class='column'>
        </h2>
        <div id="content" class="m-list">
            <?php
            $vi = 0;
            while ($r = $empire->fetch($sql)) {
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
                        <a href="<?= $titleurl ?>"> <img style="float:right;" src='<?=$r[titlepic]?>' /></a>
                    <a  style="font-size: 15px" href="<?= $titleurl ?>"><b><?= $r[title] ?></b></a>
                        <p style="font-size:13px;"><?=$passedtimestr?>    <?=$class_r[$r['classid']]['classname']?>   <?=$r['author']?></p>
                    </div>
                </section><?php }else{?>
                <section class="li<?= $vi1 ?>">
                    <div class="img-text">
                    <a  style="font-size: 15px" href="<?= $titleurl ?>"><b><?= $r[title] ?></b></a>
                        <p style="font-size:13px;"><?=$passedtimestr?>    <?=$class_r[$r['classid']]['classname']?>   <?=$r['author']?></p>
                    </div>
                </section><?php }?>
                <?php
                $vi++;
            }
            ?>
        </div>
        <section><div style="height: 35px;background-color: white;text-align: center">
                <span style="font-family: 黑体;color: rgb(168,0,0)" onclick="jiazai()"><img style="height: 8px" src="hgh/images/search-red_08.png">&nbsp;点击加载更多</span>
                <a href="#top" class="to_top icon" title="返回顶部" style="display: block;"></a>
            </div></section>
    </section>

    <script>
        function GetQueryString(name)
        {
            var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if(r!=null)return  unescape(r[2]); return null;
        }
        function jiazai() {
            var xmlhttp;
            var page;
            var classid = GetQueryString("classid");
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
            xmlhttp.open("GET","list.php?load=1&classid="+classid,true);
            xmlhttp.send();
        }
    </script>
<?php

DoWapFooter();

db_close();
$empire = null;

?>