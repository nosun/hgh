<?php
require("../e/class/connect.php");
require("../e/class/db_sql.php");
require("../e/class/q_functions.php");
require("../e/data/dbcache/class.php");
$link = db_connect();
$empire = new mysqlquery();
define('WapPage', 'list');
require("wapfun.php");

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
        <h2><a href="list.php?classid=<?= $classid ?>&style=0&bclassid=0"><?= $class_r[$classid][classname] ?></a>

            <div class='jian'></div>
        </h2>
        <div id="content" class="m-list">
            <?php
            $vi = 0;
            while ($r = $empire->fetch($sql)) {
                $vi1 = $vi % 2;
                $titleurl = "show.php?classid=" . $r[classid] . "&id=" . $r[id] . "&style=" . $wapstyle . "&cpage=" . $page . "&cid=" . $classid . "&bclassid=" . $bclassid;
                ?>
                <section class="li<?= $vi1 ?>">
                    <h3><a href="<?= $titleurl ?>"><?= $r[title] ?></a></h3>
                </section>
                <?php
                $vi++;
            }
            if ($returnpage) {
                echo $returnpage;
            }
            ?>
        </div>
    </section>
    <section class='column'>
        <section><h2 class="u-tools"><a href="index.php?style=<?=$wapstyle?>" id="vhomelink">网站首页</a><a class="pclink2" id="j-2pc-2" href="#">电脑版</a></h2></section>
    </section>
<?php

DoWapFooter();

db_close();
$empire = null;

?>