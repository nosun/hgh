<?php
//--------------------- 更新信息表 ---------------------

//评分字段增加
function a(){
    
}

//文章信息表转换
function DoZtInfo($add){
	global $empire,$config,$doline,$doretime;//$empire 数据库对象;
	$doline=(int)$doline;
	$doretime=(int)$doretime;
	$start=(int)$add['start'];
        $sql=$empire->query("select * from supe_ztitems where itemid>$start order by itemid limit $doline");
        $b=0;
	while($r=$empire->fetch($sql))
	{
            $b=1;
            //var_dump($r);

//==============确定新旧表字段对应关系

            //======对应专题主表字段
                
                $new_start  =   $r['itemid'];     
                $old_catid  =   $r['catid'];
                $temp_infos['ztname']      = $r['subject'];
                $temp_infos['onclick']      = $r['viewnum'];
                $temp_infos['addtime']      = $r['dateline'];
                
                $temp_infos['ztpath']      = 's/'.$new_start;
                $temp_infos['zttype']      = '.html';                
                $temp_infos['ztnum']      = 25;  
                $temp_infos['classtempid']      = 1;
                $temp_infos['listtempid']      = 8;
                $temp_infos['reorder']      = 'newstime DESC';                 

                //根据当前id查询出副表信息
                
                $selectSQL  =   'SELECT * FROM supe_ztmessage where itemid='.$new_start;
                $result  =  mysql_query($selectSQL);
                $message    = mysql_fetch_array($result);
                
                $temp_infos['intro']    =   strip_tags($message['message']);
                $temp_infos['ztimg']    =   $message['zt_pic'];
   
//==============插入结果
		  
            //=====主表字段
                $aFieldName  =  array_keys($temp_infos);    //返回包含数组中所有键名的一个新数组
                $strFieldName    =  implode($aFieldName,',');   //键名数组元素之间加“,”把数组元素组合为一个字符串

                $strFieldValue  = getInsertStr($temp_infos);    //遍历数组，将键值用与键名同样的格式以“,”把数组元素组合为一个字符串，getInsertStr()是在conn.php中自定义的函数

                $insert =   'insert into hgh_enewszt ('.$strFieldName.') values ('.$strFieldValue.')';

                if(mysql_query($insert)){
                    $strSqlid    =   'select @@identity';
                    $rs  =   mysql_query($strSqlid);
                    $rsNewid = mysql_fetch_array($rs);
                    $new_infoid   =   $rsNewid['@@identity'];

                }else{
                    echo '<h2 style="color:red;">专题主表记录插入失败！<br />原信息id为:'.$new_start.'；原分类id为：'.$old_catid.'</h2><hr />';
                    exit();
                }

                echo '<h2 style="color:green;">专题主表记录插入成功！原专题id为:'.$new_start.'；新专题id为：'.$new_infoid.'；</h2><hr/>';
                

            //=======副表字段
                
                
                
                //对应专题副表字段
                $temp_infos_data['ztid']    =   $new_infoid;
                
                //=============================
                $aFieldName  =  array_keys($temp_infos_data);    //返回包含数组中所有键名的一个新数组
                $strFieldName    =  implode($aFieldName,',');   //键名数组元素之间加“,”把数组元素组合为一个字符串

                $strFieldValue  = getInsertStr($temp_infos_data);    //遍历数组，将键值用与键名同样的格式以“,”把数组元素组合为一个字符串，getInsertStr()是在conn.php中自定义的函数

                $insert =   'insert into hgh_enewsztadd ('.$strFieldName.') values ('.$strFieldValue.')';

                if(mysql_query($insert)){

                }else{
                    echo '<h2 style="color:red;">专题副表记录插入失败！<br />原信息id为:'.$new_start.'</h2><hr />';
                    exit();
                }

                echo '<h2 style="color:green;">副表记录插入成功！原信息id为:'.$new_start.'；<br />新信息id为：'.$new_infoid.'；</h2><hr/>';


            //=======专题信息表字段
                
                //对应专题信息表字段
                
                $ztid   =    $new_infoid;              
                //===================
                $newssql=$empire->query("select * from hgh_ecms_article where specialid=$new_start order by id");

                while($news_r=$empire->fetch($newssql))
                {
                    $temp_infos_index['ztid']      = $ztid;
                    $temp_infos_index['classid']      = $news_r['classid']; 
                    $temp_infos_index['id']      = $news_r['id'];
                    $temp_infos_index['newstime']      =    $news_r['newstime'];
                    $temp_infos_index['isgood']      =    $news_r['isgood'];

                    $temp_infos_index['mid']      = (int)ReturnMid($news_r['classid']); 

                    //====================================
                    $aFieldName  =  array_keys($temp_infos_index);    //返回包含数组中所有键名的一个新数组
                    $strFieldName    =  implode($aFieldName,',');   //键名数组元素之间加“,”把数组元素组合为一个字符串

                    $strFieldValue  = getInsertStr($temp_infos_index);    //遍历数组，将键值用与键名同样的格式以“,”把数组元素组合为一个字符串，getInsertStr()是在conn.php中自定义的函数

                    $insert =   'insert into hgh_enewsztinfo ('.$strFieldName.') values ('.$strFieldValue.')';

                    if(mysql_query($insert)){
                        $strSqlid    =   'select @@identity';
                        $rs  =   mysql_query($strSqlid);
                        $rsNewid = mysql_fetch_array($rs);
                        $new_infoid   =   $rsNewid['@@identity'];

                    }else{
                        echo '<h2 style="color:red;">专题信息表记录插入失败！<br />信息id为:'.$news_r['id'].'；专题id为：'.$new_infoid.'</h2><hr />';
                        exit();
                    }

                    echo '<h2 style="color:green;">专题信息表记录插入成功！<br />信息id为:'.$news_r['id'].'；<br />专题id为：'.$new_infoid.'；<br />分类id：'.$news_r['classid'].'；</h2><hr/>';
                    
                    unset($temp_infos_index);
                }
                
                //销毁影响下一条数据的变量
                unset($message,$temp_infos,$temp_infos_data);

	}
        if (!empty($b)){
            echo"<meta charset=utf-8\"utf-8\" http-equiv=\"refresh\" content=\"".$doretime.";url=do.php?ecms=DoZtInfo&start=$new_start\"><h2>正在转换作者数据……</h2><p>转换一组数据完毕，正在进入下一组......(ID:<font color=red><b>".$new_start."</b></font>)</p>";
            }
        else{
            echo "专题数据倒入完毕！";
            exit();
        }  
         
}
?>