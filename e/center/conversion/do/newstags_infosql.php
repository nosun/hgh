<?php
//--------------------- 更新信息表 ---------------------

//评分字段增加
function a(){
    
}

//文章信息表转换
function DoNewstagsInfo($add){
	global $empire,$config,$doline,$doretime;//$empire 数据库对象;
	$doline=(int)$doline;
	$doretime=(int)$doretime;
	$start=(int)$add['start'];
        $sql=$empire->query("SELECT * FROM hgh_ecms_article where id>$start order by id limit $doline");
        $b=0;
	while($r=$empire->fetch($sql))
	{
            $b=1;
            //var_dump($r);

//==============确定新旧表字段对应关系


            $new_start  =   $r['id'];

        //======对应tag表字段

            $temp_taginfo['classid']   =   $classid     =    (int)$r['classid'];
            $temp_taginfo['id']   =   (int)$r['id'];
            $temp_taginfo['newstime']   =   $r['newstime'];                        
            $temp_taginfo['mid']   =   (int)ReturnMid($classid);

            if(substr_count($r['keyboard'],',')){
                $tag_r  =   explode(',', $r['keyboard']);
                foreach ($tag_r as $value) {
                    $tagid  = ReturnTagid($value);
                    if(empty($tagid)){
                        echo '<p style="color:red">无法找到tag“'.$value.'”对应的tagid，当前信息id为：'.$r['id'].'</p>';
                        exit;
                    }  else {
                        
                        $temp_taginfo['tagid']   =   (int)$tagid;
                        InsertTagInfo($temp_taginfo);
                        
                    }
                }                
            }  elseif($r['keyboard']) {
                $temp_taginfo['tagid']  =   (int)ReturnTagid($r['keyboard']);
                InsertTagInfo($temp_taginfo);
            }
            
            unset($temp_taginfo);

	}
        if (!empty($b)){
            echo"<meta charset=utf-8\"utf-8\" http-equiv=\"refresh\" content=\"".$doretime.";url=do.php?ecms=DoNewstagsInfo&start=$new_start\"><h2>正在倒入文章……</h2><p>转换一组数据完毕，正在进入下一组......(ID:<font color=red><b>".$new_start."</b></font>)</p>";
            }
        else{
            echo "TAG数据倒入完毕！";
            exit();
        }  
         
}
?>