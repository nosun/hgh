<?php

//--------------------------------------连库
require("../lib/connect.php");
require("../lib/db_sql.php");
require("../lib/fun.php");

$link=db_connect();
$empire=new mysqlquery();

//---------------------------------------循环
        $sql=$empire->query("select * from supe_postitems a inner join supe_postmessages b on a.itemid=b.itemid order by a.itemid ASC,b.pageorder ASC");

	while($r=$empire->fetch($sql))
	{

            //var_dump($r);

//==============确定新旧表字段对应关系


                //======对应主表字段
                    $new_start  =   $r['itemid'];     //文章id
                    
                    $old_catid  =   $r['catid'];
                    $temp_infos['classid']       =  getNewClassId($r['catid']);    //文章栏目id
                    $temp_infos['userid']      = $r['uid'];
                    $temp_infos['username']      = $r['username'];
                    $temp_infos['title']      = addslashes($r['subject']);
                    $temp_infos['lastdotime']      = $r['lastpost'];            
                    $temp_infos['truetime']      = $r['dateline'];            
                    $temp_infos['newstime']      = $r['dateline'];
                    if($r['fromtype']=="userpost"){
                        $temp_infos['ismember']      = 1; 
                    } elseif($r['fromtype']=="adminpost"){
                        $temp_infos['ismember']      = 0; 
                    }
                    
                    $r['fromtype'];        

                    //根据当前id查询出副表信息

                    
                    $oitemid = (int)$r['oitemid'];
                    $itemid = (int)$r['itemid'];
                    if(empty($a)){
                        $a = 0;
                        $message = "";
                    }


                    $message .= $r['message'];  
                    
                    if(!empty($oitemid)){
                        
                        $selectSQL  =  $empire->query('SELECT * FROM supe_postitems where oitemid='.$oitemid);
                        $count = mysql_num_rows($selectSQL);                         
                        
                        if($count>1){
                            $a++;
                            if($a==$count){
                                echo "<h2 style='color:red;'>a=$a<br />count=$count<br />oitemid=$oitemid<br />itemid=$itemid</h2><hr />";
                                unset($a);
                            } else {
                                echo "<h2 style='color:red;'>a=$a<br />count=$count<br />oitemid=$oitemid<br />itemid=$itemid</h2><hr />";
                                continue;
                            }
                        }
                    }
                    //tag存储格式变换
                    $tag_r  =   explode('	',$r['includetags']);
                    $counts = count($tag_r);
                    if($counts<=5){
                        $tag    =  implode($tag_r,',');
                    }else{
                        $tag_r  =   array_slice($tag_r,0,5);
                        $tag    =  implode($tag_r,',');
                    }


                    $temp_infos['keyboard']    =   $tag;
                    $temp_infos['author']      = $r['newsauthor']; 
                    $temp_infos['smalltext']      = esub(strip_tags($message),300); 
                    $temp_infos['copyfrom']    =   $r['newsfrom'];
                    $temp_infos['fromlink']    =   $r['newsfromurl'];
                    $temp_infos['userip']      = $r['postip'];
                    if($r['newsurl']){
                        continue;
                    }

                //对应副表字段
                    $temp_infos_data['classid']      = $temp_infos['classid'];
                    $temp_infos_data['newstext']    =   addslashes($message);
                    $temp_infos_data['infotags']    =   $tag;

                //对应索引表字段
                    $temp_infos_index['classid']   =   $temp_infos['classid'];
                    $temp_infos_index['lastdotime']      = $r['lastpost'];            
                    $temp_infos_index['truetime']      = $r['dateline'];            
                    $temp_infos_index['newstime']      = $r['dateline'];
                    $temp_infos_index['checked']      = 1;

                
//==============插入结果
		
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

                    
            //=====主表字段
                $aFieldName  =  array_keys($temp_infos);    //返回包含数组中所有键名的一个新数组
                $strFieldName    =  implode($aFieldName,',');   //键名数组元素之间加“,”把数组元素组合为一个字符串

                $strFieldValue  = getInsertStr($temp_infos);    //遍历数组，将键值用与键名同样的格式以“,”把数组元素组合为一个字符串，getInsertStr()是在conn.php中自定义的函数

                $insert =   'insert into hgh_ecms_article ('.$strFieldName.') values ('.$strFieldValue.')';

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

                //销毁影响下一条数据的变量
                unset($message,$r,$temp_infos,$temp_infos_data,$temp_infos_index);

	}

            echo "post数据倒入完毕！";
            exit();


?>