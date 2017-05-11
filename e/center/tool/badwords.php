<?php
/**
 * Created by PhpStorm.
 * User: 红星
 * DateTime: 2015/12/10 23:01
 */

define('EmpireCMSAdmin', '1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require("../../class/com_functions.php");
require_once(AbsLoadLang('pub/fun.php'));
$link = db_connect();
$empire = new mysqlquery();

//验证用户
$lur = is_login();
$logininid = $lur['userid'];
$loginin = $lur['username'];
$loginrnd = $lur['rnd'];
$loginlevel = $lur['groupid'];
$loginadminstyleid = $lur['adminstyleid'];

//验证权限
CheckLevel($logininid, $loginin, $classid, "gbook");
$enews = $_GET['enews'];
$cid = (int)$_POST['cid'];
$cid = $cid ? $cid : 0;

if (empty($enews)) {
    $enews = $_POST['enews'];
}

if ($enews == "AddWord") {
    $word = RepPostVar2($_POST['word']);

    if (empty($word)) {
        printerror2('关键词不能为空！', 'badwords.php');
    }

    $num = $empire->num("SELECT word FROM {$dbtbpre}enewsbadwords WHERE word='$word'");
    if ($num > 0) {
        printerror2('关键词重复！请修改后重新提交', 'badwords.php');
    }
    $insert = $empire->query("insert into {$dbtbpre}enewsbadwords set word='$word',cid=$cid");
    if ($insert) {
        //重新生成XML文件
        usr_Db2XML();

        printerror2('添加成功！', 'badwords.php');
    } else {
        printerror2('插入失败！', 'badwords.php');
    }

}

if ($enews == "DelWord") {
    $kid = (int)$_GET['kid'];
    $del = $empire->query("DELETE from {$dbtbpre}enewsbadwords WHERE kid=$kid");
    if ($del) {
        //重新生成XML文件
        usr_Db2XML();

        printerror2('删除成功！', 'badwords.php');
    } else {
        printerror2('删除失败！', 'badwords.php');
    }


}

if ($enews == "DelAllword") {
    $count = count($_POST['kid']);
    echo $count;
    if (empty($count)) {
        printerror2("您未选择要删除的关键词！", "badwords.php");
    }
    for ($i = 0; $i < $count; $i++) {
        $kid[$i] = (int)$kid[$i];
        $add .= "kid='$kid[$i]' or ";
    }
    echo $add;
    $add = substr($add, 0, strlen($add) - 4);
    echo "delete from {$dbtbpre}enewsbadwords where " . $add;
    $sql = $empire->query("delete from {$dbtbpre}enewsbadwords where " . $add);
    if ($sql) {      //操作日志
        //重新生成XML文件
        usr_Db2XML();

        printerror2("删除成功！", 'badwords.php');
    } else {
        printerror2("删除失败！请重新提交", 'history.go(-1)');
    }

//    $kid = (int)$_GET['kid'];
//    $del = $empire->query("DELETE from {$dbtbpre}enewsbadwords WHERE kid=$kid");
//    if ($del) {
//        printerror2('删除成功！', 'badwords.php');
//    } else {
//        printerror2('删除失败！', 'badwords.php');
//    }
}


$kid = (int)$_GET['kid'];
if (empty($kid)) {
    $kid = $_POST['kid'];
}
$word = RepPostVar2($_POST['word']);
$mark_enews = $kid ? "EditWord" : "AddWord";
$msg_button = $kid ? "修改" : "增加";
$mark_kid = $kid ? '<input type="hidden" name="kid" value="' . $kid . '">' : '';

if ($enews == "EditWord") {
    $er = $empire->fetch1("select * from {$dbtbpre}enewsbadwords where kid=$kid");
    $eword = $er['word'] ? $er['word'] : "";
    if (!empty($word)) {
        $update = $empire->query("UPDATE {$dbtbpre}enewsbadwords set word='$word',cid=$cid WHERE kid=$kid");
        if ($update) {
            //重新生成XML文件
            usr_Db2XML();

            printerror2('修改成功！', 'badwords.php');
        } else {
            printerror2('修改失败！请修改后重新提交', 'badwords.php?enews=EditWord&kid=' . $kid);
        }
    }
    $mark_cid = $er['cid'] ? " selected='selected'" : "";
}

usr_XML2Db('badwords.xml');
//----------------- 预定义函数 -----------------

/**
 *
 * function usr_XML2Db()  将XML数据导入数据库
 * @param $file string XML文件路径
 *
 */
function usr_XML2Db($file)
{
    global $empire, $dbtbpre;
    if (file_exists($file)) {
        $xmldata = simplexml_load_file($file);
        $count = count($xmldata->word);

        for ($i = 0; $i < $count; $i++) {       //第二部分
            $word = $xmldata->word[$i];
            $title = $word->title;
            $title = RepPostVar2($title);
//            echo $title . '<br />';
            if (empty($title)) {
                continue;
            }

            $num = $empire->num("SELECT word FROM {$dbtbpre}enewsbadwords WHERE word='$title'");
            if ($num > 0) {
                continue;
            }

            $insert = $empire->query("insert into {$dbtbpre}enewsbadwords set word='$title',cid=0");
            if (!$insert) {
                printerror2('插入失败', 'badwords.php');
            }
        }
    } else {
        exit('Error.');
    }
}


/**
 *
 * function usr_XML2Db()  将XML数据导入数据库
 * @param $file string XML文件路径
 *
 */
function usr_Db2XML() {
    global $empire, $dbtbpre;

    $data_array =array();
    $i=0;
    $sql = $empire->query("SELECT * FROM {$dbtbpre}enewsbadwords");

    while ($r = $empire->fetch($sql)) {
        $data_array[$i]['title'] = $r['word'];
        $data_array[$i]['style'] = 'normal';
        $i++;
    }

    $string = <<<XML
<?xml version='1.0' encoding='utf-8'?>
<words>
</words>
XML;
    $xml = simplexml_load_string($string);
    foreach ($data_array as $data) {
        $word = $xml->addChild('word');
        if (is_array($data)) {
            foreach ($data as $key => $row) {
                $node = $word->addChild($key, $row);
            }
        }
    }

    $xmlstring = $xml->asXML();
    $myfile = fopen("badwords.xml", "w") or die("Unable to open file!");
    fwrite($myfile, $xmlstring);
    fclose($myfile);

}
//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);




$url = "<a href=badwords.php>管理关键词</a>";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>关键词管理</title>
    <link href="../adminstyle/<?= $loginadminstyleid ?>/adminstyle.css" rel="stylesheet" type="text/css">
    <link href="../../trylife/common/css/adminstyle.css" rel="stylesheet" type="text/css">
    <script src="../../trylife/common/JQuery/jquery-1.4.2.min.js"></script>
    <script src="badwords.js"></script>
    <script>
        function CheckAll(form) {
            for (var i = 0; i < form.elements.length; i++) {
                var e = form.elements[i];
                if (e.name != 'chkall')
                    e.checked = form.chkall.checked;
            }
        }
    </script>
</head>

<body>

<link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
<script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<style>
    .btn {
        padding: 1px 12px;
    }

    .list-word .btn {
        font-size: 10px;
    }
</style>

<div class="container">
    <div class="row">
        <div id="Message_info"></div>
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
            <tr>
                <td width="50%">位置:
                    <?= $url ?>
                </td>
                <td>
                    <div align="right" class="emenubutton">
                        <input type="button" name="Submit5" value="留言分类管理"
                               onclick="self.location.href='GbookClass.php';">
                        &nbsp;&nbsp;
                        <input type="button" name="Submit52" value="批量删除留言"
                               onclick="self.location.href='DelMoreGbook.php';">
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <?= $word ?>
    <div class="row">
        <form name="thisform" method="post" action="badwords.php" onsubmit="return confirm('确认要执行操作?');">
            <div class="form-group">
                <label for="name">关键词</label>
                <input type="text" class="form-control" id="name" name="word" value="<?= $eword ?>"
                       placeholder="请输入要过滤的关键词">

                <label for="name">选择分类</label>
                <select class="form-control" name="cid">
                    <option value="0"<?= $mark_cid ?>>未分类</option>
                    <option value="1"<?= $mark_cid ?>>禁止提交关键词</option>
                    <option value="2"<?= $mark_cid ?>>其他</option>
                </select>
                <?= $mark_kid ?>
                <input type="hidden" name="enews" value="<?= $mark_enews ?>">
            </div>
            <button type="submit" class="btn btn-default"><?= $msg_button ?></button>
        </form>
    </div>

    <div class="row">
        <div class="table-responsive">
            <form name="thisform" method="post" action="badwords.php" onsubmit="return confirm('确认要执行操作?');">
                <table class="table table-bordered  list-word">
                    <caption>过滤关键词列表</caption>
                    <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>关键词</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?
                    $sql = $empire->query("SELECT * FROM {$dbtbpre}enewsbadwords ORDER BY kid DESC");
                    while ($r = $empire->fetch($sql)) {      //循环获取查询记录
                        ?>

                        <tr>
                            <td>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="kid[]" id="kid[]" value="<?= $r['kid'] ?>">
                                </label>
                            </td>
                            <td><?= $r['kid'] ?></td>
                            <td><?= $r['word'] ?></td>
                            <td>
                                <a class="btn btn-default" href="badwords.php?enews=EditWord&kid=<?= $r['kid'] ?>"
                                   role="button">修改</a>
                                <a class="btn btn-default" href="badwords.php?enews=DelWord&kid=<?= $r['kid'] ?>"
                                   role="button">删除</a>
                            </td>
                        </tr>

                        <?
                    }
                    ?>
                    <tr>
                        <td>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="chkall" value="on" onclick="CheckAll(this.form)">全选
                            </label>
                        </td>
                        <td></td>
                        <td>
                            <input type="hidden" name="enews" value="DelAllword">
                            <button type="submit" class="btn btn-default">删除</button>
                        </td>
                        <td></td>
                    </tr>

                    </tbody>
                </table>
            </form>
        </div>
    </div>

</div>


</body>
</html>
<?
db_close();
$empire = null;
?>
