<?php
//------ 函数库文件 ------

//验证是否提交过
function user_CheckHaveMood($classid, $id, $doset = 0)
{
    global $empire, $dbtbpre, $redomood;
    if ($redomood == 0) {
        return 1;
    } elseif ($redomood == 1) {
        $cktime = 0;
    } else {
        $cktime = time() + 30 * 24 * 3600;
    }
    $var = 'usermood';
    $val = $classid . '|' . $id;
    $doupdate = 1;
    $moodrecord = getcvar($var);
    if (strstr($moodrecord, ',' . $val . ',')) {
        $doupdate = 0;
    } else {
        if ($doset) {
            $newval = empty($moodrecord) ? ',' . $val . ',' : $moodrecord . $val . ',';
            esetcookie($var, $newval, $cktime);
        }
    }
    if (empty($_COOKIE)) {
        $doupdate = 0;
    }

    // 红星 2015-7-21 11:59:28
    // 验证提交IP
    $doip = egetip();
    $r = $empire->fetch1("select * from {$dbtbpre}ecms_statistics where id='$id' and classid='$classid' and mip='$doip' limit 1");
    if ($r) {
        $doupdate = 0;
    }

    return $doupdate;
}

//提交表态
function user_UpdateMood($add)
{
    global $empire, $dbtbpre, $class_r;
    $classid = (int)$add['classid'];
    $id = (int)$add['id'];
    $changemood = (int)$add['changemood'];
    if (!$classid || !$id || $changemood < 1 || $changemood > 12) {
        printerror2('您来自的链接不存在', '', 9);
    }
    if (empty($class_r[$classid][tbname])) {
        printerror2('您来自的链接不存在', '', 9);
    }
    //验证信息
    $r = $empire->fetch1("select id,classid from {$dbtbpre}ecms_" . $class_r[$classid][tbname] . " where id='$id' limit 1");
    if (!$r[id] || $classid != $r[classid]) {
        printerror2('此信息不存在', '', 9);
    }
    //是否提交过
    $doupdate = user_CheckHaveMood($classid, $id, 1);
    if ($doupdate == 0) {
        printerror2('您已经表过态了！', '', 9);
    }
    $updatef = 'mood' . $changemood;
    $moodnum = $empire->gettotal("select count(*) as total from {$dbtbpre}ecmsextend_mood where id='$id' and classid='$classid' limit 1");
    if (!$moodnum) {
        $sql = $empire->query("insert into {$dbtbpre}ecmsextend_mood(classid,id," . $updatef . ") values('$classid','$id','1');");
    } else {
        $sql = $empire->query("update {$dbtbpre}ecmsextend_mood set " . $updatef . "=" . $updatef . "+1 where id='$id' and classid='$classid' limit 1");
    }

    //红星 2015-7-19 14:32:25 增加心情操作的统计信息，用于了解灌水行为特点：IP、操作频率
    //---- start ----

    //准备参数

    $doip = egetip();
    $newtime = time();
    $mnum_r = $empire->fetch1("select * from {$dbtbpre}ecmsextend_mood where id='$id' and classid='$classid' limit 1");
    $mnum = $mnum_r['mood1'] + $mnum_r['mood2'] + $mnum_r['mood3'] + $mnum_r['mood4'] + $mnum_r['mood5'] + $mnum_r['mood6'] + $mnum_r['mood7'] + $mnum_r['mood8'];
    unset($mnum_r);

    //传值给user_RecordStatistics()记录统计信息
    user_RecordStatistics($classid, $id, $doip, $newtime, $mnum);

    //---- end ----


    $reurl = "index.php?classid=$classid&id=$id";
    if ($sql) {
        Header("Location:$reurl");
        db_close();
        exit();
    } else {
        printerror2('数据库出错', '', 9);
    }
}


/**
 *
 * 针对心情操作统计操作者的IP、频率
 * 红星 2015-7-19 14:37:27
 * @param int $classid 文章classid
 * @param int $id 文章id
 * @param string $mip 用户ip
 * @param int $newtime 当前时间戳
 * @param int $mnum 心情总数
 *
 * @return none
 */

function user_RecordStatistics($classid, $id, $mip, $newtime, $mnum)
{
    global $empire, $dbtbpre;
    $r = $empire->fetch1("select * from {$dbtbpre}ecms_statistics where id='$id' and classid='$classid' and mip='$mip' limit 1");
    if ($r) {
        $sql = $empire->query("update {$dbtbpre}ecms_statistics set lastdotime='$newtime',mnum='$mnum',click=click+1 where id='$id' and classid='$classid' and mip='$mip' limit 1");
    } else {
        $sql = $empire->query("insert into {$dbtbpre}ecms_statistics(classid,id,mip,newstime,lastdotime,mnum,click) values('$classid','$id','$mip','$newtime','$newtime','$mnum',1)");
    }
    if (!$sql) {
        printerror2('数据库出错', '', 9);
    }
}