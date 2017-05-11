<?php
/**
 * Created by PhpStorm.
 * User: ����
 * DateTime: 2015/7/29 9:13
 */

//��������ļ�
require("../e/class/connect.php");
require("../e/class/db_sql.php");
require("../e/class/userfun.php");
require("../e/class/q_functions.php");
require("../e/data/dbcache/class.php");

if(!defined('InEmpireCMS')){
    exit();
}

//�������ݿ�
$link=db_connect();
$empire=new mysqlquery();

//��ȡ��������
$act = (int)$_POST['act'];
$id = (int) $_POST['id'];
$id = $id ? $id : 0;
$notIn = (string)$_POST['notin'];
$max = $_POST['max'] ;

//��֤



//echo $exclude."---".$minid;
//Ԥ�������
$line = 20;
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

//�����ѯ����
$start = $max ? "AND id<$max" : "";

if($act == 1){      //��ҳ
    $whereExp = "((addtoqk=101 AND isgood>1) OR (addtoqk=102 and isgood>2) OR (addtoqk=105 and isgood>2))";
    $query = "SELECT classid,id,title,titleurl,titlepic,ftitle,newstime,author FROM {$dbtbpre}ecms_article WHERE $whereExp AND id NOT IN($notIn) $start ORDER BY newstime DESC limit $line";

} elseif($act == 2) {     //���·����б�
    if($id == 101){
        $whereExp = "(addtoqk=101 AND isgood>1)";
    } elseif($id == 102){
        $whereExp = "(addtoqk=102 AND isgood>2)";
    } elseif($id == 105){
        $whereExp = "(addtoqk=105 AND isgood>2)";
    }
    $query = "SELECT classid,id,title,titleurl,titlepic,ftitle,newstime,author FROM {$dbtbpre}ecms_article WHERE $whereExp AND id NOT IN($notIn) $start ORDER BY newstime DESC limit $line";
} elseif($act == 4){     //�Ƽ�ר���б�
    if($id){
        $tr = $empire->fetch1("select tagid from {$dbtbpre}enewstags where tagid='$id' limit 1");
        if(!empty($tr)){
            $whereExp = "tagid=$id";
            $query="select classid,id from {$dbtbpre}enewstagsdata WHERE $whereExp AND id NOT IN($notIn) $start ORDER BY newstime DESC limit $line";
        }
    }
} elseif($act == 5) {       //����

    $search_r = $empire->fetch1("select searchid,keyboard,result_num,orderby,myorder,tbname,tempid,andsql,trueclassid from {$dbtbpre}enewssearch where searchid='$id'");
    if (empty($search_r['searchid']) || InfoIsInTable($search_r[tbname])) {
        $query = '';
    } else {

        $myorder = $search_r[orderby];

        if (empty($search_r[myorder])) {
            $myorder.=" desc";
        }
        $add = stripSlashes($search_r['andsql']);
        $num = $search_r[result_num];

        $whereExp = $add ? ' where ' . substr($add, 5) . ' and id NOT IN('.$notIn.')'.$start : 'id NOT IN('.$notIn.')'.$start ;

        $query = "select classid,id,title,newstime,titleurl,ftitle,author from {$dbtbpre}ecms_" . $search_r[tbname] . $whereExp;
        $query.=" order by " . $myorder . " limit $line";

    }

} else {
    exit();
}


$select = $query;
$sql = $empire->query($query);
$data = array();
$i = 0;

if($act == 4){
    while ($dr = $empire->fetch($sql)) {
        $r = $empire->fetch1("select id,classid,title,titleurl,titlepic,ftitle,newstime,author from {$dbtbpre}ecms_article where classid='$dr[classid]' AND id='$dr[id]' limit 1");
        $r['newstime'] = (string)usr_Date($r['newstime']);
        $r['titleurl'] = "http://m2.szhgh.com/show.php.php?classid=$r[classid]&id=$r[id]";
        $r['select'] = $select;
        $data[] = $r;
        unset($r);
    }
} else {
    while ($r = $empire->fetch($sql)) {
        $r['newstime'] = (string)usr_Date($r['newstime']);
        $r['titleurl'] = "http://m2.szhgh.com/show.php?classid=$r[classid]&id=$r[id]";
        $r['select'] = $select;
        $data[] = $r;
    }
}

//$data = array('aaa','bbb');
//echo json_encode($data,JSON_UNESCAPED_UNICODE);
echo json_encode($data);

//select title,titleurl,titlepic,ftitle,newstime,author from phome_ecms_article where (addtoqk=101 and isgood>1) or (addtoqk=102 and isgood>2) or (addtoqk=105 and isgood>2) order by firsttitle DESC,newstime DESC limit 200



//�Ͽ����ݿ�����
db_close();
$empire=null;