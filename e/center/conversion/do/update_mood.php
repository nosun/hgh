<?php

require("../lib/connect.php");
require("../lib/db_sql.php");
require("../lib/fun.php");
error_reporting(E_ALL ^ E_NOTICE);
$link=db_connect();
$empire=new mysqlquery();
//--------------------- 信息操作 ---------------------

        //查出心情表中的重复记录
        $sql=$empire->query("select id from hgh_ecmsextend_mood_copy group by id having count(id) > 1 order by id ASC");
        
	while($r=$empire->fetch($sql))
	{
            $id=(int)$r['id'];
            

            
            //查询出心情表中同一id的所以记录，并循环累加心情数
            $query=$empire->query("select * from hgh_ecmsextend_mood_copy where id=".$id);
            while($mood_r=$empire->fetch($query)){
                $m_id=(int)$mood_r['id'];
                $m_classid=(int)$mood_r['classid'];
                
                //累加心情数
                for($i=1;$i<13;$i++){
                    $key=(string)$key="mood".(string)$i;
                    $$key+=(int)$mood_r[$key];
                }
                
                //查询出文章所属的栏目
                $art_r=$empire->fetch1("select classid from hgh_ecms_article where id=".$m_id);
                $cur_classid=(int)$art_r['classid'];      
                if($m_classid<>$cur_classid){
                    $empire->query("delete from hgh_ecmsextend_mood_copy where id=".$m_id." and classid=".$m_classid);
                }
            }
            
            for($i=1;$i<13;$i++){
                $key=(string)$key="mood".(string)$i;
                $setkv.=$i<12?$key.'='.$$key.',':$key.'='.$$key;
            }  

            $update =   'UPDATE hgh_ecmsextend_mood_copy SET '.$setkv.' where classid='.$cur_classid.' and id='.$id;
            if (mysql_query($update)) {
                echo '<p style="color:green;">数据更新成功！</p>';
            }  else {
                echo '<p style="color:red;">数据更新失败！id为'.$r['id'].' classid为'.$cur_classid.'</p>';
                exit;
            }
            echo 'id为'.$id.'<br />';
            
            for($i=1;$i<=12;$i++){
                $key=(string)$key="mood".(string)$i;
                unset($$key);
            }
            
            unset($art_r,$mood_r,$moodnum_r,$setkv);
	}

        echo "<strong style='color:green;'>数据全部处理完毕！</strong>";
        exit();
//--------------------- 信息操作 ---------------------
        
mysql_close($link);


?>