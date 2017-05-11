<?php

//------ 函数库文件 ------
//验证是否提交过
function user_CheckHaveMood($classid, $id, $doset = 0) {
    global $redomood;  //setconfig中配置的值
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
            $newval = empty($moodrecord) ?  ',' . $val . ',' : $moodrecord . $val . ',';
            esetcookie($var, $newval, $cktime);
        }
    }
    if (empty($_COOKIE)) {
        $doupdate = 0;
    }
    return $doupdate;
}

//提交表态
function user_UpdateMood($add) {
    global $empire, $dbtbpre, $class_r;
    $classid = (int) $add['classid'];
    $modid = (int) ReturnMid($classid);
    $id = (int) $add['id'];
    $changemood = (int) $add['changemood'];
    if (!$classid || !$id || $changemood < 1 || $changemood > 12) {
        printerror2('您来自的链接不存在', '', 9);
    }
    if (empty($class_r[$classid][tbname])) {
        printerror2('您来自的链接不存在', '', 9);
    }
    //验证信息
    if ($modid) {
        $extendmood_r = $empire->fetch1("select classid from {$dbtbpre}ecmsextend_mood where id='$id' and modid='$modid' limit 1");
        if ($extendmood_r) {
            $tochk_classid = (int) $extendmood_r['classid'];
            if ($tochk_classid <> $classid) {
                $empire->query("update {$dbtbpre}ecmsextend_mood set classid='".$classid."' where classid='$tochk_classid' and id='$id'");
            }
        }
    }  else {
        printerror2('此信息不存在', '', 9);
    }

    //是否提交过
    $doupdate = user_CheckHaveMood($classid, $id, 1);
    if ($doupdate == 0) {
        printerror2('你已经表过态了！', '', 9);
    }
    $updatef = 'mood' . $changemood;
    $moodnum = $empire->gettotal("select count(*) as total from {$dbtbpre}ecmsextend_mood where id='$id' and classid='$classid' limit 1");
    if (!$moodnum) {
        $sql = $empire->query("insert into {$dbtbpre}ecmsextend_mood(modid,classid,id," . $updatef . ") values('$modid','$classid','$id','1');");
    } else {
        $sql = $empire->query("update {$dbtbpre}ecmsextend_mood set " . $updatef . "=" . $updatef . "+1 where id='$id' and classid='$classid' limit 1");
    }
    $reurl = "index.php?classid=$classid&id=$id";
    if ($sql) {
        Header("Location:$reurl");
        db_close();
        exit();
    } else {
        printerror2('数据库出错', '', 9);
    }
}
//取得模型id
function ReturnMid($classid) {
    global $empire, $dbtbpre;
    $r=$empire->fetch1("SELECT modid FROM {$dbtbpre}enewsclass where classid=".$classid);
    if($r){
        return $r['modid'];
    } else {
        return 0;
    }     
}

?>