<?php
//--------------------- 更新信息表 ---------------------

//评分字段增加
function a(){
    
}

//文章信息表转换
function DoCopyfromInfo($add){
	global $empire,$config,$doline,$doretime;//$empire 数据库对象;
	$doline=(int)$doline;
	$doretime=(int)$doretime;
	$start=(int)$add['start'];
        $sql=$empire->query("select * from supe_friendlinks where id>$start order by id limit $doline");
        $b=0;
	while($r=$empire->fetch($sql))
	{
            $b=1;
            //var_dump($r);

//==============确定新旧表字段对应关系

            //======对应主表字段
                
                $new_start  =   $r['id'];     //文章id
                $temp_infos['classid']       =  35;    //文章栏目id
                $temp_infos['title']      = $r['name'];
                $temp_infos['webaddr']      = $r['url'];
                
                $time   =   time();
                $temp_infos['lastdotime']      = $time;            
                $temp_infos['truetime']      = $time;            
                $temp_infos['newstime']      = $time;


            //对应副表字段
                
                $temp_infos_data['classid']    =   $temp_infos['classid'];
             
            //对应索引表字段
                
                $temp_infos_index['classid']   =   $temp_infos['classid'];
                $temp_infos_index['checked']   =   1;
                $temp_infos_index['lastdotime']      = $time;            
                $temp_infos_index['truetime']      = $time;            
                $temp_infos_index['newstime']      = $time;
                

                
//==============插入结果
		
            //=====主表字段
                $aFieldName  =  array_keys($temp_infos);    //返回包含数组中所有键名的一个新数组
                $strFieldName    =  implode($aFieldName,',');   //键名数组元素之间加“,”把数组元素组合为一个字符串

                $strFieldValue  = getInsertStr($temp_infos);    //遍历数组，将键值用与键名同样的格式以“,”把数组元素组合为一个字符串，getInsertStr()是在conn.php中自定义的函数

                $insert =   'insert into hgh_ecms_copyfrom ('.$strFieldName.') values ('.$strFieldValue.')';

                if(mysql_query($insert)){
                    $strSqlid    =   'select @@identity';
                    $rs  =   mysql_query($strSqlid);
                    $rsNewid = mysql_fetch_array($rs);
                    $new_infoid   =   $rsNewid['@@identity'];

                }else{
                    echo '<h2 style="color:red;">主表记录插入失败！<br />原信息id为:'.$new_start.'；</h2><hr />';
                    exit();
                }

                echo '<h2 style="color:green;">主表记录插入成功！原信息id为:'.$new_start.'；<br />新信息id为：'.$new_infoid.'；新分类id：'.$temp_infos['classid'].'；</h2><hr/>';
                
                $temp_infos_data['id']    =   $new_infoid;

            //=======副表字段
                
                $aFieldName  =  array_keys($temp_infos_data);    //返回包含数组中所有键名的一个新数组
                $strFieldName    =  implode($aFieldName,',');   //键名数组元素之间加“,”把数组元素组合为一个字符串

                $strFieldValue  = getInsertStr($temp_infos_data);    //遍历数组，将键值用与键名同样的格式以“,”把数组元素组合为一个字符串，getInsertStr()是在conn.php中自定义的函数

                $insert =   'insert into hgh_ecms_copyfrom_data_1 ('.$strFieldName.') values ('.$strFieldValue.')';

                if(mysql_query($insert)){

                }else{
                    echo '<h2 style="color:red;">副表记录插入失败！<br />原信息id为:'.$new_start.'；</h2><hr />';
                    exit();
                }

                echo '<h2 style="color:green;">副表记录插入成功！原信息id为:'.$new_start.'；<br />新信息id为：'.$new_infoid.'；新分类id：'.$temp_infos['classid'].'；</h2><hr/>';


            //=======索引表字段
                $aFieldName  =  array_keys($temp_infos_index);    //返回包含数组中所有键名的一个新数组
                $strFieldName    =  implode($aFieldName,',');   //键名数组元素之间加“,”把数组元素组合为一个字符串

                $strFieldValue  = getInsertStr($temp_infos_index);    //遍历数组，将键值用与键名同样的格式以“,”把数组元素组合为一个字符串，getInsertStr()是在conn.php中自定义的函数

                $insert =   'insert into hgh_ecms_copyfrom_index ('.$strFieldName.') values ('.$strFieldValue.')';

                if(mysql_query($insert)){
                    $strSqlid    =   'select @@identity';
                    $rs  =   mysql_query($strSqlid);
                    $rsNewid = mysql_fetch_array($rs);
                    $new_infoid   =   $rsNewid['@@identity'];

                }else{
                    echo '<h2 style="color:red;">索引表记录插入失败！<br />原信息id为:'.$new_start.'；</h2><hr />';
                    exit();
                }

                echo '<h2 style="color:green;">索引表记录插入成功！原信息id为:'.$new_start.'；<br />新信息id为：'.$new_infoid.'；新分类id：'.$temp_infos['classid'].'；</h2><hr/>';

                
                //销毁影响下一条数据的变量
                unset($message,$temp_infos,$temp_infos_data,$temp_infos_index);

	}
        if (!empty($b)){
            echo"<meta charset=utf-8\"utf-8\" http-equiv=\"refresh\" content=\"".$doretime.";url=do.php?ecms=DoCopyfromInfo&start=$new_start\"><h2>正在转换作者数据……</h2><p>转换一组数据完毕，正在进入下一组......(ID:<font color=red><b>".$new_start."</b></font>)</p>";
            }
        else{
            echo "作者数据倒入完毕！";
            exit();
        }  
         
}
?>