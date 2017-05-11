<?php
/**
 * 献花
 *
 * @author guoguo
 *
 * @param array $add 通过url传递过来的参数
 *
 */

function addCounts($id)
{
    global $empire, $dbtbpre;
//30秒
    $postTime = time();
    $OldPostTime = getcvar('flowers');
    if ($OldPostTime) {
        if ($postTime - $OldPostTime < 30) {
            return false;
        }
    }
   setcookie("flowers",$postTime,$postTime+3600*24);

    $strSql = "update  {$dbtbpre}flowers set counts=counts+1 where id=$id";
    $sql = $empire->query($strSql);
    if ($sql) {
        $strSql = "select counts from {$dbtbpre}flowers where id=1";
        $r = $empire->fetch1($strSql);
        $counts = $r['counts'];
        return $counts;
    } else {
        return false;
    }
}

?>