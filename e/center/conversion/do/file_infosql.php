<?php
//--------------------- 更新信息表 ---------------------

//评分字段增加
function a(){
    
}

//文章信息表转换
function DoFileInfo($add){
	global $empire,$config,$doline,$doretime;//$empire 数据库对象;
	$doline=(int)$doline;
	$doretime=(int)$doretime;
	$start=(int)$add['start'];
        $sql=$empire->query("SELECT * FROM supe_attachments where aid>$start order by aid limit $doline");
        $b=0;
	while($r=$empire->fetch($sql))
	{
            $b=1;
            //var_dump($r);

//==============确定新旧表字段对应关系

            //======对应附件表字段
                
                //大图
                $new_start  =   $r['aid'];
                
                $filepath_r =   explode("/",$r['filepath']);
                $temp_infos['fpath']   =   1;  
                $temp_infos['path']   =   $filepath_r['0'].'/'.$filepath_r['1'];
                $temp_infos['filename']   =   $filepath_r['2'];
                
                $temp_infos['no']   =   $r['filename'];
                $temp_infos['filesize']   =   $r['size'];
                $temp_infos['adduser']   =   '红星';
                $temp_infos['filetime']   =   $r['dateline'];
                $temp_infos['classid']   =   $classid    =   (int)getNewClassId($r['catid']);
                $temp_infos['type']   =   $r['isimage']; 
                
                $r_id   =   getId($r['itemid']);
                $temp_infos['id']   =    $id    =   (int)$r_id['1'];                
                
                $tid    =   (int)ReturnTid($classid);
                $temp_infos['pubid']  = ReturnInfoPubid($classid, $id, $tid);                

                
            //==============插入记录
                
                if(empty($id)){
                    continue;
                }
                
                $aFieldName  =  array_keys($temp_infos);    //返回包含数组中所有键名的一个新数组
                $strFieldName    =  implode($aFieldName,',');   //键名数组元素之间加“,”把数组元素组合为一个字符串
                $strFieldValue  = getInsertStr($temp_infos);    //遍历数组，将键值用与键名同样的格式以“,”把数组元素组合为一个字符串，getInsertStr()是在conn.php中自定义的函数

                $tag_insert =   'insert ignore into hgh_enewsfile_1 ('.$strFieldName.') values ('.$strFieldValue.')';
//                var_dump($tag_insert);exit;
//                mysql_query($tag_insert) or die('数据插入失败!'.mysql_error());
                if( mysql_query($tag_insert)){
                    $strSqlid    =   'select @@identity';
                    $rs  =   mysql_query($strSqlid);
                    $rsNewid = mysql_fetch_array($rs);
                    $new_infoid   =   $rsNewid['@@identity'];
                }else{
                    echo '<p>对附件表信息插入失败！</p>';
                    echo '<p>aid:'.$new_start.', itemid:'.$r['itemid'].'</p>';
                    exit();
                }

                echo '<p>aid:'.$new_start.', itemid:'.$r['itemid'].'成功插入</p>';

                echo '<hr/>';
            //=======记录新旧表关联字段值（file_key）

                $file_key['aid']  =   $new_start;
                $file_key['itemid']  =   $r['itemid'];
                $file_key['newid']  =   $id;
                $file_key['catid']  =   $r['catid'];
                $file_key['classid']  =   $classid;
                $file_key['filepath']  =   $r['filepath'];

                $aFieldName  =  array_keys($file_key);    //返回包含数组中所有键名的一个新数组
                $strFieldName    =  implode($aFieldName,',');   //键名数组元素之间加“,”把数组元素组合为一个字符串

                $strFieldValue  = getInsertStr($file_key);    //遍历数组，将键值用与键名同样的格式以“,”把数组元素组合为一个字符串，getInsertStr()是在conn.php中自定义的函数

                $insert =   'insert into file_key ('.$strFieldName.') values ('.$strFieldValue.')';

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
                unset($temp_infos,$file_key,$r_id,$filepath_r);

	}
        if (!empty($b)){
            echo"<meta charset=utf-8\"utf-8\" http-equiv=\"refresh\" content=\"".$doretime.";url=do.php?ecms=DoFileInfo&start=$new_start\"><h2>正在倒入附件表……</h2><p>转换一组数据完毕，正在进入下一组......(ID:<font color=red><b>".$new_start."</b></font>)</p>";
            }
        else{
            echo "附件表数据倒入完毕！";
            exit();
        }  
         
}
?>