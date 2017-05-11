<?php
//--------------------- 更新信息表 ---------------------

//评分字段增加
function a(){
    
}

//文章信息表转换
function DoCommentsInfo($add){
	global $empire,$config,$doline,$doretime;//$empire 数据库对象;
	$doline=(int)$doline;
	$doretime=(int)$doretime;
	$start=(int)$add['start'];
        $sql=$empire->query("SELECT * FROM supe_spacecomments where cid>$start order by cid limit $doline");
        $b=0;
	while($r=$empire->fetch($sql))
	{
            $b=1;
            //var_dump($r);

//==============确定新旧表字段对应关系

            //======对应评论表字段
                
                $new_start  =   $r['cid'];
                $temp_infos['username']   =   $r['author'];
                $temp_infos['sayip']   =   $r['ip'];                
                $temp_infos['saytime']   =   $r['dateline'];
                
                $r_id   =   getId($r['itemid']);
                $temp_infos['classid']   =  $classid =   (int)$r_id['0'];
                $temp_infos['id']   =    $id    =   (int)$r_id['1'];
                
                $tid    = ReturnTid($classid);
                $temp_infos['pubid']  = ReturnInfoPubid($classid, $id, $tid);
                
                $temp_infos['zcnum']   =   $r['click_33'];                
                $temp_infos['fdnum']   =   $r['click_34'];
                $temp_infos['userid']   =   $r['authorid'];                
                $temp_infos['saytext']   =   addslashes($r['message']);
                
       
                
                
            //==============插入记录
                
                $aFieldName  =  array_keys($temp_infos);    //返回包含数组中所有键名的一个新数组
                $strFieldName    =  implode($aFieldName,',');   //键名数组元素之间加“,”把数组元素组合为一个字符串
                $strFieldValue  = getInsertStr($temp_infos);    //遍历数组，将键值用与键名同样的格式以“,”把数组元素组合为一个字符串，getInsertStr()是在conn.php中自定义的函数

                $tag_insert =   'insert ignore into hgh_enewspl_1 ('.$strFieldName.') values ('.$strFieldValue.')';
//                var_dump($tag_insert);exit;
//                mysql_query($tag_insert) or die('数据插入失败!'.mysql_error());
                if( mysql_query($tag_insert)){
                    $strSqlid    =   'select @@identity';
                    $rs  =   mysql_query($strSqlid);
                    $rsNewid = mysql_fetch_array($rs);
                    $new_infoid   =   $rsNewid['@@identity'];
                }else{
                    echo '<p>对评论表信息插入失败！</p>';
                    echo '<p>cid:'.$new_start.', itemid:'.$r['itemid'].'</p>';
                    exit();
                }

                echo '<p>cid:'.$new_start.', itemid:'.$r['itemid'].'成功插入</p>';

                echo '<hr/>';
            //=======记录新旧表关联字段值（comments_key）

                $comments_key['cid']  =   $new_start;
                $comments_key['itemid']  =   $r['itemid'];

                $aFieldName  =  array_keys($comments_key);    //返回包含数组中所有键名的一个新数组
                $strFieldName    =  implode($aFieldName,',');   //键名数组元素之间加“,”把数组元素组合为一个字符串

                $strFieldValue  = getInsertStr($comments_key);    //遍历数组，将键值用与键名同样的格式以“,”把数组元素组合为一个字符串，getInsertStr()是在conn.php中自定义的函数

                $insert =   'insert into comments_key ('.$strFieldName.') values ('.$strFieldValue.')';

                if(mysql_query($insert)){
                    $strSqlid    =   'select @@identity';
                    $rs  =   mysql_query($strSqlid);
                    $rsNewid = mysql_fetch_array($rs);
                    $new_infoid   =   $rsNewid['@@identity'];

                }else{
                    echo '<h2 style="color:red;">新旧表关联记录插入失败！<br />原信息id为:'.$new_start.'</h2><hr />';
                    exit();
                }

                echo '<h2 style="color:green;">新旧表关联记录插入成功！原信息id为:'.$new_start.'；<br />新信息id为：'.$new_infoid.'；</h2><hr/>';
                echo '<hr />';
                
                //销毁影响下一条数据的变量
                unset($temp_infos,$comments_key,$r_id);

	}
        if (!empty($b)){
            echo"<meta charset=utf-8\"utf-8\" http-equiv=\"refresh\" content=\"".$doretime.";url=do.php?ecms=DoCommentsInfo&start=$new_start\"><h2>正在倒入评论表……</h2><p>转换一组数据完毕，正在进入下一组......(ID:<font color=red><b>".$new_start."</b></font>)</p>";
            }
        else{
            echo "评论表数据倒入完毕！";
            exit();
        }  
         
}
?>