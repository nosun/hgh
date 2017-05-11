<?php

require("../../../e/class/connect.php");
require("../../../e/class/db_sql.php");
require("../../../e/data/dbcache/class.php");
require("../../../e/class/q_functions.php");
require_once(AbsLoadLang('pub/fun.php'));

eCheckCloseMods('search'); //关闭模块
$link = db_connect();
$empire = new mysqlquery();
$getvar = $_GET['getvar'];
if (empty($getvar)) {
    $getfrom = "history.go(-1)";
} else {
    $getfrom = "../../../search/";
}
//搜索结果
$searchid = (int) $_GET['searchid'];
if (empty($searchid)) {
    printerror("SearchNotRecord", $getfrom, 1);
}
$search_r = $empire->fetch1("select searchid,keyboard,result_num,orderby,myorder,tbname,tempid,andsql,trueclassid from {$dbtbpre}enewssearch where searchid='$searchid'");
if (empty($search_r['searchid']) || InfoIsInTable($search_r[tbname])) {
    printerror("SearchNotRecord", $getfrom, 1);
}
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
$listpage = page1($num, $line, $page_line, $start, $page, $search);
//取得模板
if ($search_r['tempid']) {
    $tempr = $empire->fetch1("select temptext,subnews,listvar,rownum,showdate,modid,subtitle,docode from " . GetTemptb("enewssearchtemp") . " where tempid='" . $search_r['tempid'] . "' limit 1");
} elseif (empty($class_r[$search_r[trueclassid]][searchtempid])) {
    $tempr = $empire->fetch1("select temptext,subnews,listvar,rownum,showdate,modid,subtitle,docode from " . GetTemptb("enewssearchtemp") . " where isdefault=1 limit 1");
} else {
    $tempr = $empire->fetch1("select temptext,subnews,listvar,rownum,showdate,modid,subtitle,docode from " . GetTemptb("enewssearchtemp") . " where tempid='" . $class_r[$search_r[trueclassid]][searchtempid] . "' limit 1");
}
$have_class = 1;
//替换公共模板变量
$listtemp = $tempr[temptext];
if ($public_r['searchtempvar']) {
    $listtemp = ReplaceTempvar($listtemp);
}
$search_r[keyboard] = ehtmlspecialchars($search_r[keyboard]);
$listtemp = str_replace("[!--show.page--]", $listpage, $listtemp);
$listtemp = str_replace("[!--keyboard--]", $search_r[keyboard], $listtemp);
$listtemp = str_replace("[!--ecms.num--]", $num, $listtemp);
$url = "<a href='" . ReturnSiteIndexUrl() . "'>" . $fun_r['index'] . "</a>&nbsp;>&nbsp;" . $fun_r['adsearch'];
$pagetitle = $fun_r['adsearch'] . " " . $search_r[keyboard];
$listtemp = ReplaceSvars($listtemp, $url, 0, $pagetitle, $pagetitle, $pagetitle, $add, 0);
$rownum = $tempr[rownum];
if (empty($rownum)) {
    $rownum = 1;
}
$formatdate = $tempr[showdate];
$subnews = $tempr[subnews];
$subtitle = $tempr[subtitle];
$docode = $tempr[docode];
$modid = $tempr[modid];
$listvar = str_replace('[!--news.url--]', $public_r[newsurl], $tempr[listvar]);
//字段
$ret_r = ReturnReplaceListF($tempr[modid]);
//取得列表模板
$list_exp = "[!--empirenews.listtemp--]";
$list_r = explode($list_exp, $listtemp);
$listtext = $list_r[1];
$no = $offset + 1;
$changerow = 1;
while ($r = $empire->fetch($sql)) {
    
    //过滤html标签  ->修改
    $r['smalltext']=htmlspecialchars_decode(strip_tags($r['smalltext']));
    
    //替换列表变量
    $repvar = ReplaceListVars($no, $listvar, $subnews, $subtitle, $formatdate, $url, $have_class, $r, $ret_r, $docode);
    $listtext = str_replace("<!--list.var" . $changerow . "-->", $repvar, $listtext);
    
    //关键词高亮显示    ->修改
    $keyboard_r =  explode(" ", $search_r[keyboard]);  //多关键词搜索
    $keyboard_count=count($keyboard_r);  
    for($i=0;$i<$keyboard_count;$i++){
        $keyboard=trim($keyboard_r[$i]);  //去除关键词两端可能存在的空格，避免因空格产生的无法替换问题
        $listtext= htmlspecialchars_decode(str_replace($keyboard, "<em>$keyboard</em>", $listtext));    //替换关键词并解码特殊字符
    }
     
    $changerow+=1;
    //超过行数
    if ($changerow > $rownum) {
        $changerow = 1;
        $string.=$listtext;
        $listtext = $list_r[1];
    }
    $no++;
}
db_close();
$empire = null;
//多余数据
if ($changerow <= $rownum && $listtext <> $list_r[1]) {
    $string.=$listtext;
}
$string = $list_r[0] . $string . $list_r[2];
echo stripSlashes($string);
?>