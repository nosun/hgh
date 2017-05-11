<?php
//重复升级检查
function CheckReUpdate(){
    if($_COOKIE['ecmsupdate']){
        echo"升级程序不能重复运行";
        exit();
    }
    @setcookie('ecmsupdate','empirecms');
}

//返回插入值字符串，用于插入语句
function getInsertStr($arr){
    $counts=0;
    foreach ($arr as $k=>$v){
        if($counts>0){
            if(is_numeric($v)){
                $strFieldValue  .=','.$v;
            }  else {
                $strFieldValue   .=',\''.$v.'\''; 
            }
        
        }else{
            if(is_numeric($v)){
                $strFieldValue  =$v;
            }  else {
                $strFieldValue  ='\''.$v.'\''; 
            }
            
        }
        $counts++;
    }
    return $strFieldValue;
}

//返回旧分类id对应的新栏目id
function getNewClassId($oldClassId){
    switch ($oldClassId){
        case 1:
            $newClassId =   29;
            break;
        case 84:
            $newClassId =   16;
            break;
        case 76:
            $newClassId =   4;
            break;
        case 61:
            $newClassId =   30;
            break;        
        case 62:
            $newClassId =   17;
            break;
        case 65:
            $newClassId =   18;
            break;
        case 63:
            $newClassId =   19;
            break;
        case 66:
            $newClassId =   20;
            break;          
        case 64:
            $newClassId =   21;
            break;
        case 68:
            $newClassId =   22;
            break;
        case 67:
            $newClassId =   23;
            break;
        case 2:
            $newClassId =   6;
            break;        
        case 3:
            $newClassId =   7;
            break;
        case 5:
            $newClassId =   31;
            break;
        case 58:
            $newClassId =   24;
            break;
        case 59:
            $newClassId =   25;
            break;         
        case 60:
            $newClassId =   26;
            break;
        case 82:
            $newClassId =   27;
            break;
        case 7:
            $newClassId =   9;
            break;
        case 72:
            $newClassId =   10;
            break;        
        case 4:
            $newClassId =   11;
            break;
        case 6:
            $newClassId =   12;
            break;
        case 8:
            $newClassId =   32;
            break;
        case 83:
            $newClassId =   28;
            break;          
        case 9:
            $newClassId =   14;
            break;
        case 19:
            $newClassId =   15;
            break;
        case 77:
            $newClassId =   33;
            break;
        case defaut:
            $newClassId =   0;
            break;
    }
    return $newClassId;
}

//返回评论对应的新信息id和所属新栏目id
function getId($itemid) {
    
    $selectSQL  =   'SELECT classid,id FROM trans_key where itemid='.$itemid;
    if($result=mysql_query($selectSQL)){
        $r    = mysql_fetch_array($result);
        $temp_id    =   array();
        $temp_id[]    = $r['classid'];
        $temp_id[]    =   $r['id'];
        return $temp_id;
    } else {
        echo '<h4 style="color:red;">无法查找到当前评论的栏目id，终止下一步执行！</h4>';
        exit;
    }

}

//返回栏目数据表ID
function ReturnTid($classid) {
    $selectSQL  =   'SELECT tid FROM hgh_enewsclass where classid='.$classid;
    if($result=mysql_query($selectSQL)){
        $r    = mysql_fetch_array($result);
        return$r['tid'];
    } else {
        echo '<h4 style="color:red;">无法查找到当前栏目数据表id，终止下一步执行！</h4>';
        exit;
    } 
}


//返回公共表索引ID
function ReturnInfoPubid($classid,$id,$tid=0){
	global $class_r;
	if(empty($tid))
	{
		$tid=$class_r[$classid]['tid'];
	}
	$pubid='1'.ReturnAllInt($tid,5).ReturnAllInt($id,10);
	return $pubid;
}

//补零
function ReturnAllInt($val,$num){
	$len=strlen($val);
	$zeronum=$num-$len;
	if($zeronum==1)
	{
		$val='0'.$val;
	}
	elseif($zeronum==2)
	{
		$val='00'.$val;
	}
	elseif($zeronum==3)
	{
		$val='000'.$val;
	}
	elseif($zeronum==4)
	{
		$val='0000'.$val;
	}
	elseif($zeronum==5)
	{
		$val='00000'.$val;
	}
	elseif($zeronum==6)
	{
		$val='000000'.$val;
	}
	elseif($zeronum==7)
	{
		$val='0000000'.$val;
	}
	elseif($zeronum==8)
	{
		$val='00000000'.$val;
	}
	elseif($zeronum==9)
	{
		$val='000000000'.$val;
	}
	elseif($zeronum==10)
	{
		$val='0000000000'.$val;
	}
	return $val;
}

//取得模型id
function ReturnMid($classid) {
    $selectSQL  =   'SELECT modid FROM hgh_enewsclass where classid='.$classid;
    if($result=mysql_query($selectSQL)){
        $r    = mysql_fetch_array($result);
        return$r['modid'];
    } else {
        echo '<h4 style="color:red;">无法查找到当前栏目数据表id，终止下一步执行！</h4>';
        exit;
    }     
}

//取得文章发布时间
function ReturnNewsInfo($id) {
    $selectSQL  =   'SELECT newstime,isgood FROM hgh_enewsclass where id='.$id;
    if($result=mysql_query($selectSQL)){
        $r    = mysql_fetch_array($result);
        return$r;
    } else {
        echo '<h4 style="color:red;">无法查找到当前栏目数据表id，终止下一步执行！</h4>';
        exit;
    }     
}

//判断tag是否已经存在
function ReturnTagid($tagname) {
    $selectSQL  =   'SELECT tagid FROM hgh_enewstags where tagname="'.(string)$tagname.'"';
    mysql_query($selectSQL) or die('数据查询失败!'.mysql_error());
    $result=mysql_query($selectSQL);
    if($r = mysql_fetch_array($result)){
        return $r[tagid];
    } else {
        $tag_insert =   'insert ignore into hgh_enewstags (tagname) values ("'.$tagname.'")';
        if( mysql_query($tag_insert)){
            $strSqlid    =   'select @@identity';
            $rs  =   mysql_query($strSqlid);
            $rsNewid = mysql_fetch_array($rs);
            $tagid   =   $rsNewid['@@identity'];
            return $tagid;
        }else{
            echo '<p style="color:red;">尝试创建tag失败！</p>';
        }
    }  
}

//插入TAG信息
function InsertTagInfo($temp_taginfo) {
    
    $aFieldName  =  array_keys($temp_taginfo); 
    $strFieldName    =  implode($aFieldName,','); 
    $strFieldValue  = getInsertStr($temp_taginfo); 
    
    $tag_insert =   'insert ignore into hgh_enewstagsdata ('.$strFieldName.') values ('.$strFieldValue.')';
    if( mysql_query($tag_insert)){
        $strSqlid    =   'select @@identity';
        $rs  =   mysql_query($strSqlid);
        $rsNewid = mysql_fetch_array($rs);
        $tagid   =   $rsNewid['@@identity'];
        return $tagid;
    }else{
        echo '<p style="color:red;">插入tag信息失败！<br />tagid：'.$temp_taginfo['tagid'].'；<br />信息id：'.$temp_taginfo['id'].'；<br />栏目id：'.$temp_taginfo['classid'].'；</p>';
        exit;
    }    
}



?>