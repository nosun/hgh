<?php
//--------------------- 更新信息表 ---------------------

//评分字段增加
function a(){
    
}

//文章信息表转换
function DoMaintenance($add){
	global $empire,$config,$doline,$doretime;//$empire 数据库对象;
	$doline=(int)$doline;
	$doretime=(int)$doretime;
	$start=(int)$add['start'];
        $sql=$empire->query("select itemid,fromtype from supe_spaceitems where itemid>$start order by itemid limit $doline");
        $b=0;
	while($r=$empire->fetch($sql))
	{
            $b=1;
            //var_dump($r);

//==============确定新旧表字段对应关系

            //======对应主表字段
                
                $new_start        =   $r['itemid'];     //原信息id，对应副表信息的取得、下一组信息的查询起点依赖此变量
                
                $temp_r=$empire->fetch1('SELECT id FROM trans_key WHERE itemid='.$new_start);
                $id     =   $temp_r[0];
                if($r['fromtype']=='userpost'){
                    $update     =   'UPDATE hgh_ecms_article SET ismember=1 WHERE id='.$id;
                    
                    if(mysql_query($update)){
                        echo '<h2 style="color:green;">更新成功！</h2>';
                    }else{
                        echo '<h2 style="color:red;">更新失败！</h2>';
                        exit();
                    }                    
                    
                }
                echo '<h2>fromtype='.$r['fromtype'].'；itemid='.$r['itemid'].'</h2>';
                echo '<hr />';
                
                //销毁影响下一条数据的变量
                unset($temp_r);

	}
        if (!empty($b)){
            echo"<meta charset=utf-8\"utf-8\" http-equiv=\"refresh\" content=\"".$doretime.";url=do.php?ecms=maintenance&start=$new_start\"><h2>正在倒入文章……</h2><p>转换一组数据完毕，正在进入下一组......(ID:<font color=red><b>".$new_start."</b></font>)</p>";
            }
        else{
            echo "文章数据倒入完毕！";
            exit();
        }  
         
}
?>