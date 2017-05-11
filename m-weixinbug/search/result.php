<?php

require("../../e/class/connect.php");
require("../../e/class/db_sql.php");
require("../../e/data/dbcache/class.php");
require("../../e/class/q_functions.php");
require_once(AbsLoadLang('pub/fun.php'));

eCheckCloseMods('search'); //关闭模块
$link = db_connect();
$empire = new mysqlquery();

define('WapPage','result');
require("../wapfun.php");

$action = (string)$_GET['act'];
$searchid = (int) $_GET['searchid'];

//操作类型

//if($action == 'search'){
//
//
//} elseif($action == 'result'){
//
//}






//搜索结果

//if (empty($searchid)) {
//    printerror("SearchNotRecord", $getfrom, 1);
//}

if (empty($searchid)) {

} else {

    $search_r = $empire->fetch1("select searchid,keyboard,result_num,orderby,myorder,tbname,tempid,andsql,trueclassid from {$dbtbpre}enewssearch where searchid='$searchid'");
    if (empty($search_r['searchid']) || InfoIsInTable($search_r[tbname])) {
//        printerror("SearchNotRecord", $getfrom, 1);
        $temp = '没有搜索到相关内容';
    } else {

        $page = (int) $_GET['page'];
        $page = RepPIntvar($page);
        $start = 0;
        $page_line = $public_r['search_pagenum']; //每页显示链接数
        $line = $public_r['search_num']; //每页显示记录数
        $offset = $page * $line; //总偏移量
        $search = "&searchid=" . $searchid;
        $myorder = $search_r[orderby];

        if (empty($search_r[myorder])) {
            $myorder.=" desc";
        }
        $add = stripSlashes($search_r['andsql']);
        $num = $search_r[result_num];
        $query = "select * from {$dbtbpre}ecms_" . $search_r[tbname] . ($add ? ' where ' . substr($add, 5) : '');
        $query.=" order by " . $myorder . " limit $offset,$line";
        $sql = $empire->query($query);
    }

}

if($search_r['keyboard']){
    $kr = explode(',',$search_r['keyboard']);
    $count = count($kr);
    for($i=0;$i<$count;$i++){
        $key .= $i==0 ? $kr[$i] : ' ' . $kr[$i];
    }
}


// SEO优化
$title = $key ? "$key - 搜索结果" : "搜索结果";
$keywords = $search_r['keyboard'] ? "$search_r[keyboard],乌有之乡,网刊,毛泽东思想,乌有,手机乌有,手机版,毛泽东思想,毛主义" : "乌有之乡,网刊,毛泽东思想,乌有,手机乌有,手机版,毛泽东思想,毛主义";
$description = "乌有之乡是当前中国最具影响力的时政、思想和学术综合类网站之一。坚持爱国主义和社会主义，秉持学术为国家利益和人民利益服务的原则。坚信只有毛泽东思想才能指引中华民族解决当前问题、化解内外危机，为全人类作出贡献！文章涵盖国内外重大事件、历史与现实重要热点话题。集中反映了当前国内外有良知、有责任、有远见的知识分子的思想动态。乌有之乡网站深受广大人民群众及爱国人士的拥护和支持，并受到世界进步人士、正义力量的关注和支持。自创办以来，访问量不断攀升，是国内思想类网站中的佼佼者，也是国际学术界了解中国社会思潮的重要窗口";


//自定义时间处理
function usr_Date($newstime){
    $differe = time() - $newstime;
    switch ($differe) {
        case $differe < 3600:
            return '<em class="s-red">' . floor($differe / 60) . '分钟前</em>';
        case $differe < 86400:
            return '<em class="s-red">' . floor($differe / 3600) . '小时前</em>';
        case $differe > 86400:
            return date("Y-m-d", $newstime);
    }
}


//引入模板
//DoWapHead($title,$keywords,$description,1);
//DoWapHeader($title);

?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <head lang="en">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>搜索</title>
        <link rel="stylesheet" type="text/css" href="../hgh/css/base.css" />
        <link rel="stylesheet" type="text/css" href="../hgh/css/szhgh-m-style.css" />
        <script src="../hgh/js/jquery-1.9.1.min.js" type="text/javascript"></script>
        <script src="../hgh/js/szhgh-m-js.js" type="text/javascript"></script>
        <script src="http://www.szhgh.com/skin/default/js/jquery.cookie.js" type="text/javascript"></script>
    </head>
    <body>
    <a name="top"></a>
    <header>
        <div class="head2"><a href="../index.php"><img src="../hgh/images/arrow-left_03.png" style="height: 20px;float: left;margin-left: 10px;margin-top: 15px"></a>
            <span class="header-msg" style="color: white;font-family:"Times New Roman"">搜索结果</span>
        </div>
    </header>
    <form class="search_box" action="index.php" method="post" name="searchform" id="searchform">
        <input type="text" name="keyboard" class="search_input"  id="keyboard" placeholder="输入关键字" value="<?=$key?>" size="42"/>
        <input type="hidden" name="show" value="title,smalltext,author" />
        <input type="hidden" name="tempid" value="1" />
        <input type="submit" name="Submit22"  class="search_btn icon" value="" />
    </form>
    </body>
<!--<form class="search_box" action="index.php" method="post" name="searchform" id="searchform">-->
<!--    <input class="search_input" name="keyboard" type="text" placeholder="输入关键字" id="keyboard" value="--><?//=$key?><!--" size="42" />-->
<!--    <a href="javascript:void(0)" class="clear_btn"><i class="icon"></i></a>-->
<!--    <input type="hidden" name="show" value="title,smalltext,author" />-->
<!--    <input type="hidden" name="tempid" value="1" />-->
<!--    <input type="submit" name="Submit22"  class="search_btn icon" value="" />-->
<!--</form>-->

<?php

$notIn = '';
$i = 0;
while ($r = $empire->fetch($sql)) {
    $notIn .=$i ? ','. $r['id'] : $r['id'] ;
    $r['newstime'] = usr_Date($r['newstime']);
    $r['title'] = DoWapClearHtml($r['title']);
    $r['titleurl'] = 'http://m.szhgh.com/show.php?classid='.$r['classid'].'&id='.$r['id'];
    $temp .= '
    <section class="article_list">
        <a href="'.$r['titleurl'].'" title="'.$r['title'].'">
            <div>
                <h2>'.$r['title'].'</h2>
                <p>'.$r['ftitle'].'</p>
                <span>'.$r['newstime'].'</span><span>作者：<i>'.$r['author'].'</i></span>
            </div>
        </a>
    </section>
    ';
    $i++;
}
?>
<div class="content" style="display:block"  id="j-content">
<?=$temp?>
</div>

<?php
    if($num > 20){
?>

<a id="j-getmore" class="load_more" href="javascript:void(0)" data="0">查看更多</a>

<?php
    }
//DoWapFooter('',1);
//DoWapFooter();

if($searchid){
?>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("#j-getmore").getMore({box: "#j-content", act:5, id: <?=$searchid?>, notIn: "<?=$notIn?>"});
    });
</script>

<?php
    
}
db_close();
$empire = null;

