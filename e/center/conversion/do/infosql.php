<?php
//--------------------- 更新信息表 ---------------------

//评分字段增加
function a(){
    
}

//文章信息表转换
function DoArticleInfo($add){
	global $empire,$config,$doline,$doretime;//$empire 数据库对象;
	$doline=(int)$doline;
	$doretime=(int)$doretime;
	$start=(int)$add['start'];
        $sql=$empire->query("select * from supe_spaceitems where itemid>$start order by itemid limit $doline");
        $b=0;
	while($r=$empire->fetch($sql))
	{
            $b=1;
            //var_dump($r);

//==============确定新旧表字段对应关系

            //======对应主表字段
                
                $new_start        =   $r['itemid'];     //原信息id，对应副表信息的取得、下一组信息的查询起点依赖此变量
                $old_catid      =   $r['catid'];
                $temp_infos['classid']       =  getNewClassId($r['catid']);    //对应信息栏目id
                $temp_infos['onclick']      = $r['viewnum'];
                $temp_infos['plnum']      = $r['replynum'];
                $temp_infos['userid']      = $r['uid'];
                $temp_infos['username']      = $r['username'];
                $temp_infos['truetime']      = $r['dateline'];
                $temp_infos['newstime']      = $r['dateline'];
                $temp_infos['lastdotime']      = $r['lastpost'];
                $temp_infos['isgood']      = $r['digest'];
                $temp_infos['firsttitle']      = $r['top'];
                if($r['grade']==1){
                    $temp_infos['ttid']     =   1;
                }
                $temp_infos['title']      = addslashes($r['subject']);
                $temp_infos['ispic']      = $r['haveattach'];
                $temp_infos['titlepic']      = $r['picid'];
                $temp_infos['ismember']      = 1; 
                $temp_infos['specialid']      = $r['specialid'];
                $temp_infos['diggtop']      = $r['click_9'];    //顶
                $temp_infos['diggdown']      = $r['click_10'];    //踩
                
                
                //心情字段
                $temp_mood['classid']      =   $temp_infos['classid'];
                $temp_mood['mood2']      =   $r['click_1'];                
                $temp_mood['mood3']      =   $r['click_5'];
                $temp_mood['mood4']      =   $r['click_7'];                  
                $temp_mood['mood5']      =   $r['click_4'];
                $temp_mood['mood6']      =   $r['click_3'];                
                $temp_mood['mood7']      =   $r['click_6'];
                $temp_mood['mood8']      =   $r['click_2'];                  
                
                
                
                //根据当前id查询出副表信息
                
                $selectSQL  =   'SELECT * FROM supe_spacenews where itemid='.$new_start;
                $result  =  mysql_query($selectSQL);
                $message    =array();
                while($r_message=  mysql_fetch_assoc($result)){
                    $message_text    .=  $r_message['message'];     //此处考虑文章分页问题，必须使用拼接运算，以避免文章内容不完整问题；
                    $message[]    =   $r_message;
                }
                
                
                //tag存储格式变换
                $tag_r  =   explode('	',$message[0]['includetags']);
                $counts = count($tag_r);
                if($counts<=5){
                    $tag    =  implode($tag_r,',');
                }else{
                    $tag_r  =   array_slice($tag_r,0,5);
                    $tag    =  implode($tag_r,',');
                }
                
                
                $temp_infos['keyboard']    =   $tag;
                $temp_infos['smalltext']    = esub(strip_tags($message_text), 300);
                $temp_infos['author']      = $message[0]['newsauthor']; 
                $temp_infos['copyfrom']    =   $message[0]['newsfrom'];
                $temp_infos['userip']      = $message[0]['postip'];
                if($message[0]['newsurl']){
                    $temp_infos['isurl']    =   1;
                    $temp_infos['titleurl']    =   $message[0]['newsurl'];
                }

            //对应副表字段
                $temp_infos_data['classid']      = $temp_infos['classid'];
                $temp_infos_data['newstext']    =   addslashes($message_text);
                $temp_infos_data['infotags']    =   $tag;
             
            //对应索引表字段
                $temp_infos_index['classid']   =   $temp_infos['classid'];
                $temp_infos_index['lastdotime']      = $r['lastpost'];            
                $temp_infos_index['truetime']      = $r['dateline'];            
                $temp_infos_index['newstime']      = $r['dateline'];
                $temp_infos_index['checked']      = 1;
                
                
//==============插入结果
		
            //=====主表字段
                $aFieldName  =  array_keys($temp_infos);    //返回包含数组中所有键名的一个新数组
                $strFieldName    =  implode($aFieldName,',');   //键名数组元素之间加“,”把数组元素组合为一个字符串

                $strFieldValue  = getInsertStr($temp_infos);    //遍历数组，将键值用与键名同样的格式以“,”把数组元素组合为一个字符串，getInsertStr()是在conn.php中自定义的函数

                $insert =   'insert into hgh_ecms_article ('.$strFieldName.') values ('.$strFieldValue.')';
//                var_dump($temp_infos);exit;
//                mysql_query($insert) or die('数据插入失败!'.mysql_error());
                if(mysql_query($insert)){
                    $strSqlid    =   'select @@identity';
                    $rs  =   mysql_query($strSqlid);
                    $rsNewid = mysql_fetch_array($rs);
                    $new_infoid   =   $rsNewid['@@identity'];

                }else{
                    echo '<h2 style="color:red;">主表记录插入失败！<br />原信息id为:'.$new_start.'；原分类id为：'.$old_catid.'</h2><hr />';
                    exit();
                }

                echo '<h2 style="color:green;">主表记录插入成功！原信息id为:'.$new_start.'；原分类id为：'.$old_catid.'；<br />新信息id为：'.$new_infoid.'；新分类id：'.$temp_infos['classid'].'；</h2><hr/>';
                
                $temp_infos_data['id']    =   $new_infoid;
                $temp_mood['id']      =   $new_infoid;
            //=======副表字段
                
                $aFieldName  =  array_keys($temp_infos_data);    //返回包含数组中所有键名的一个新数组
                $strFieldName    =  implode($aFieldName,',');   //键名数组元素之间加“,”把数组元素组合为一个字符串

                $strFieldValue  = getInsertStr($temp_infos_data);    //遍历数组，将键值用与键名同样的格式以“,”把数组元素组合为一个字符串，getInsertStr()是在conn.php中自定义的函数

                $insert =   'insert into hgh_ecms_article_data_1 ('.$strFieldName.') values ('.$strFieldValue.')';

                if(mysql_query($insert)){

                }else{
                    echo '<h2 style="color:red;">副表记录插入失败！<br />原信息id为:'.$new_start.'；原分类id为：'.$old_catid.'</h2><hr />';
                    exit();
                }

                echo '<h2 style="color:green;">副表记录插入成功！原信息id为:'.$new_start.'；原分类id为：'.$old_catid.'；<br />新信息id为：'.$new_infoid.'；新分类id：'.$temp_infos['classid'].'；</h2><hr/>';


            //=======索引表字段
                $aFieldName  =  array_keys($temp_infos_index);    //返回包含数组中所有键名的一个新数组
                $strFieldName    =  implode($aFieldName,',');   //键名数组元素之间加“,”把数组元素组合为一个字符串

                $strFieldValue  = getInsertStr($temp_infos_index);    //遍历数组，将键值用与键名同样的格式以“,”把数组元素组合为一个字符串，getInsertStr()是在conn.php中自定义的函数

                $insert =   'insert into hgh_ecms_article_index ('.$strFieldName.') values ('.$strFieldValue.')';

                if(mysql_query($insert)){
                    $strSqlid    =   'select @@identity';
                    $rs  =   mysql_query($strSqlid);
                    $rsNewid = mysql_fetch_array($rs);
                    $new_infoid   =   $rsNewid['@@identity'];

                }else{
                    echo '<h2 style="color:red;">索引表记录插入失败！<br />原信息id为:'.$new_start.'；原分类id为：'.$old_catid.'</h2><hr />';
                    exit();
                }

                echo '<h2 style="color:green;">索引表记录插入成功！原信息id为:'.$new_start.'；原分类id为：'.$old_catid.'；<br />新信息id为：'.$new_infoid.'；新分类id：'.$temp_infos['classid'].'；</h2><hr/>';

                
             //=======心情表字段
                $aFieldName  =  array_keys($temp_mood);    //返回包含数组中所有键名的一个新数组
                $strFieldName    =  implode($aFieldName,',');   //键名数组元素之间加“,”把数组元素组合为一个字符串

                $strFieldValue  = getInsertStr($temp_mood);    //遍历数组，将键值用与键名同样的格式以“,”把数组元素组合为一个字符串，getInsertStr()是在conn.php中自定义的函数

                $insert =   'insert into hgh_ecmsextend_mood ('.$strFieldName.') values ('.$strFieldValue.')';

                if(mysql_query($insert)){
                    $strSqlid    =   'select @@identity';
                    $rs  =   mysql_query($strSqlid);
                    $rsNewid = mysql_fetch_array($rs);
                    $new_infoid   =   $rsNewid['@@identity'];

                }else{
                    echo '<h2 style="color:red;">心情表记录插入失败！<br />原信息id为:'.$new_start.'；原分类id为：'.$old_catid.'</h2><hr />';
                    exit();
                }

                echo '<h2 style="color:green;">心情表记录插入成功！原信息id为:'.$new_start.'；原分类id为：'.$old_catid.'；<br />新信息id为：'.$new_infoid.'；新分类id：'.$temp_infos['classid'].'；</h2><hr/>';
               
                
                
                
            //=======记录新旧表关联字段值（trans_key）

                $trans_key['itemid']  =   $new_start;
                $trans_key['classid']  =   $temp_infos['classid'];
                $trans_key['catid']  =   $old_catid;

                $aFieldName  =  array_keys($trans_key);    //返回包含数组中所有键名的一个新数组
                $strFieldName    =  implode($aFieldName,',');   //键名数组元素之间加“,”把数组元素组合为一个字符串

                $strFieldValue  = getInsertStr($trans_key);    //遍历数组，将键值用与键名同样的格式以“,”把数组元素组合为一个字符串，getInsertStr()是在conn.php中自定义的函数

                $insert =   'insert into trans_key ('.$strFieldName.') values ('.$strFieldValue.')';

                if(mysql_query($insert)){
                    $strSqlid    =   'select @@identity';
                    $rs  =   mysql_query($strSqlid);
                    $rsNewid = mysql_fetch_array($rs);
                    $new_infoid   =   $rsNewid['@@identity'];

                }else{
                    echo '<h2 style="color:red;">新旧表关联记录插入失败！<br />原信息id为:'.$new_start.'；原分类id为：'.$old_catid.'</h2><hr />';
                    exit();
                }

                echo '<h2 style="color:green;">新旧表关联记录插入成功！原信息id为:'.$new_start.'；原分类id为：'.$old_catid.'；<br />新信息id为：'.$new_infoid.'；新分类id：'.$temp_infos['classid'].'；</h2><hr/>';
                echo '<hr />';
                
                //销毁影响下一条数据的变量
                unset($message,$tag_r,$message_text,$message,$r_message,$temp_mood,$temp_infos,$temp_infos_data,$temp_infos_index);

	}
        if (!empty($b)){
            echo"<meta charset=utf-8\"utf-8\" http-equiv=\"refresh\" content=\"".$doretime.";url=do.php?ecms=DoArticleInfo&start=$new_start\"><h2>正在倒入文章……</h2><p>转换一组数据完毕，正在进入下一组......(ID:<font color=red><b>".$new_start."</b></font>)</p>";
            }
        else{
            echo "文章数据倒入完毕！";
            exit();
        }  
         
}
?>