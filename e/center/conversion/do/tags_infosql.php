<?php
//--------------------- 更新信息表 ---------------------

//评分字段增加
function a(){
    
}

//文章信息表转换
function DoTagsInfo($add){
	global $empire,$config,$doline,$doretime;//$empire 数据库对象;
	$doline=(int)$doline;
	$doretime=(int)$doretime;
	$start=(int)$add['start'];
        $sql=$empire->query("SELECT * FROM supe_tags where tagid>$start order by tagid limit $doline");
        $b=0;
	while($r=$empire->fetch($sql))
	{
            $b=1;
            //var_dump($r);

//==============确定新旧表字段对应关系

            //======对应tag表字段
                
                $temp_infos['tagname'] =   $r['tagname'];
                $new_start  =   $r['tagid'];
                
                
            //==============插入记录
                
                $aFieldName  =  array_keys($temp_infos);    //返回包含数组中所有键名的一个新数组
                $strFieldName    =  implode($aFieldName,',');   //键名数组元素之间加“,”把数组元素组合为一个字符串
                $strFieldValue  = getInsertStr($temp_infos);    //遍历数组，将键值用与键名同样的格式以“,”把数组元素组合为一个字符串，getInsertStr()是在conn.php中自定义的函数

                $tag_insert =   'insert ignore into hgh_enewstags ('.$strFieldName.') values ('.$strFieldValue.')';

                if( mysql_query($tag_insert)){
                    $strSqlid    =   'select @@identity';
                    $rs  =   mysql_query($strSqlid);
                    $rsNewid = mysql_fetch_array($rs);
                    $new_infoid   =   $rsNewid['@@identity'];
                }else{
                    echo '<p>对tag表信息插入失败！</p>';
                    echo '<p>原tagid:'.$new_start.', tagname:'.$temp_infos['tagname'].'</p>';
                    exit();
                }

                echo '<p>原tagid:'.$new_start.', tagname:'.$temp_infos['tagname'].'成功插入</p>';

                echo '<hr/>';
            //=======记录新旧表关联字段值（tags_key）

                $tags_key['tagid']  =   $new_start;
                $tags_key['tagname']  =   $r['tagname'];

                $aFieldName  =  array_keys($tags_key);    //返回包含数组中所有键名的一个新数组
                $strFieldName    =  implode($aFieldName,',');   //键名数组元素之间加“,”把数组元素组合为一个字符串

                $strFieldValue  = getInsertStr($tags_key);    //遍历数组，将键值用与键名同样的格式以“,”把数组元素组合为一个字符串，getInsertStr()是在conn.php中自定义的函数

                $insert =   'insert into tags_key ('.$strFieldName.') values ('.$strFieldValue.')';

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
                unset($temp_infos);

	}
        if (!empty($b)){
            echo"<meta charset=utf-8\"utf-8\" http-equiv=\"refresh\" content=\"".$doretime.";url=do.php?ecms=DoTagsInfo&start=$new_start\"><h2>正在倒入文章……</h2><p>转换一组数据完毕，正在进入下一组......(ID:<font color=red><b>".$new_start."</b></font>)</p>";
            }
        else{
            echo "TAG数据倒入完毕！";
            exit();
        }  
         
}
?>