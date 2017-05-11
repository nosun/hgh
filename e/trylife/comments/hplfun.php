<?php
//************************************ 评论 ************************************
//管理员修改评论
function adminEditComment($add)
{
	global $empire,$public_r,$plwords,$lur,$dbtbpre,$class_r;
	$saytext=$add['saytext'];
	$restb=(int)$add[restb];
	$plid=(int)$add[plid];
	$saytext=str_replace($plwords[0],$plwords[1],$saytext);
	//副表
	$fsql=$empire->query("update {$dbtbpre}enewspl_".$restb." set saytext='".addslashes($saytext)."' where plid='$plid' limit 1");
	if($fsql)
	{
		$r=$empire->fetch1("select classid,id from {$dbtbpre}enewspl_".$restb." where plid='$plid' limit 1");
		get_comments($r[classid],$r[id],0,1);
		return ecms_json(array('msgid'=>0,'code'=>RepPltextFace(stripSlashes($saytext)),'plid'=>$plid,'enews'=>$add[enews]));
	}
	else
	{ 
            return ecms_json(array('msgid'=>99,'msg'=>'数据库连接出错'));}
}

//管理员回复评论
function adminReplyComment($add)
{
	global $empire,$public_r,$plwords,$lur,$dbtbpre,$class_r;
        $restb=(int)$add[restb];
	$parent_r=$empire->fetch1("select plid,parent,username,classid,id from {$dbtbpre}enewspl_".$restb." where plid='".$add[plid]."' limit 1");
	$saytext=$add['saytext'];
	$classid=$parent_r[classid];
	$id=$parent_r[id];
	$replyid=$parent_r[plid];
	if($parent_r['parent'])
	{
		$parent=$parent_r['parent'];
		
	}
	else
	{
		$parent=$parent_r[plid];
	}
	
	$username=$empire->gettotal("select truename as total from {$dbtbpre}enewsuser where userid='".$lur['userid']."' limit 1");
	$username=empty($username)?'匿名管理':$username;
	
	if(!trim($saytext)||!$id||!$classid)
	{
		return ecms_json(array('msgid'=>99,'msg'=>'评论为空'));
	}
	//表存在
	if(empty($class_r[$classid][tbname]))
	{
		return ecms_json(array('msgid'=>99,'msg'=>'表不存在'));
	}

	$time=time();
	$saytime=$time;
        $sayip=egetip();
	$username=RepPostStr($username);
	$username=str_replace("\r\n","",$username);
	$saytext=nl2br(RepFieldtextNbsp(RepPostStr($saytext)));
	$pr=$empire->fetch1("select plclosewords,plf,plmustf from {$dbtbpre}enewspl_set limit 1");	
	$saytext=str_replace($plwords[0],$plwords[1],$saytext);
	$checked=0;
	$ret_r=ReturnPlAddF($add,$pr,0);
	$floor=0;
	$sql=$empire->query("insert into {$dbtbpre}enewspl_".$restb."(username,sayip,saytime,id,classid,checked,zcnum,fdnum,userid,isgood,parent,replyid,replyname,floor,is_admin,saytext) values('".$username."','$sayip','$saytime','$id','$classid','$checked',0,0,'".$lur['userid']."',0,$parent,$replyid,'".$parent_r[username]."',$floor,1,'".addslashes($saytext)."');");
        $usql=$empire->query("update {$dbtbpre}ecms_".$class_r[$classid][tbname]." set plnum=plnum+1 where id='$id'");
	if($sql)
	{
		//这里没有计算自定义字段
		$plr=array
		(
			'plid'=>$plid,
			'username'=>$username,
			'sayip'=>$sayip,
			'saytime'=>$saytime,
			'id'=>$id,
			'classid'=>$classid,
			'checked'=>$checked,
			'zcnum'=>0,
			'fdnum'=>0,
			'userid'=>$muserid,
			'isgood'=>0,
			'parent'=>$parent,
			'replyid'=>$replyid,
                        //'restb'=>$restb,
			'replyname'=>$parent_r[username],
			'floor'=>$floor,
			'saytext'=>addslashes($saytext),
		);
		$code='<b>'.stripSlashes($public_r[add_comments_default_admin_pre]).$username.'</b> '.RepPltextFace(stripSlashes($plr[saytext]));
		$msg=$checked?'评论提交成功，需要管理员审核后才能正式显示':'评论成功';
		if(!$checked)
		{
			//缓存第一页
			get_comments($classid,$id,0,1);
		}
		return ecms_json(array('msgid'=>0,'msg'=>$msg,'parent'=>$parent,'code'=>$code,'plid'=>$plid,'enews'=>$add[enews]));
	}
	else
	{json_printerror("DbError",99);}
}

//批量删除评论
function DelPl_all($plid,$id,$bclassid,$classid,$userid,$username){
	global $empire,$class_r,$dbtbpre,$public_r;
	//验证权限
	//CheckLevel($userid,$username,$classid,"news");
	$restb=(int)$_POST['restb'];
	$count=count($plid);
	if(empty($count)||!$restb)
	{
		printerror("NotDelPlid","history.go(-1)");
	}
	if(!strstr($public_r['pldatatbs'],','.$restb.','))
	{
		printerror("NotDelPlid","history.go(-1)");
	}
	for($i=0;$i<$count;$i++)
	{
		$add.="plid='".intval($plid[$i])."' or parent='".intval($plid[$i])."' or ";
	}
	$add=substr($add,0,strlen($add)-4);
	//更新数据表
	$fsql=$empire->query("select id,classid,plid,pubid from {$dbtbpre}enewspl_{$restb} where ".$add);
	$update_r=array();
	$i=0;        
	while($r=$empire->fetch($fsql))
	{
		if($class_r[$r[classid]][tbname]&&$r['pubid']>0)
		{
			$index_r=$empire->fetch1("select checked from {$dbtbpre}ecms_".$class_r[$r[classid]][tbname]."_index where id='$r[id]' limit 1");
			//返回表
			$infotb=ReturnInfoMainTbname($class_r[$r[classid]][tbname],$index_r['checked']);
			$empire->query("update ".$infotb." set plnum=plnum-1 where id='$r[id]'");
		}
                		//更新评论数组
		$update_member=$r[classid].':'.$r[id];
		if(!in_array($update_member,$update_r))
		{
			$update_r[$i]=$update_member;
			$i++;
		}
    }
	$sql=$empire->query("delete from {$dbtbpre}enewspl_{$restb} where ".$add);
	if($sql)
	{
		//操作日志
		insert_dolog("classid=".$classid."<br>classname=".$class_r[$classid][classname]);
                batch_update_comments_cache($update_r);
		printerror("DelPlSuccess",$_SERVER['HTTP_REFERER']);
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//批量审核评论
function CheckPl_all($plid,$id,$bclassid,$classid,$userid,$username){
	global $empire,$class_r,$dbtbpre,$public_r;
	//验证权限
	//CheckLevel($userid,$username,$classid,"news");
	$restb=(int)$_POST['restb'];
	$count=count($plid);
	if(empty($count)||!$restb)
	{
		printerror("NotCheckPlid","history.go(-1)");
	}
	if(!strstr($public_r['pldatatbs'],','.$restb.','))
	{
		printerror("NotCheckPlid","history.go(-1)");
	}
	for($i=0;$i<$count;$i++)
	{
		$add.="plid='".intval($plid[$i])."' or ";
	}
	$add=substr($add,0,strlen($add)-4);
	$sql=$empire->query("update {$dbtbpre}enewspl_{$restb} set checked=0 where ".$add);
	//$nowtime=time();
	//$ptquery="update {$dbtbpre}ecms_".$class_r[$classid][tbname]." set lastdotime=".$nowtime." where classid = ".$classid." and id = ".$id;
	//$pltime=$empire->query("update {$dbtbpre}ecms_".$class_r[$classid][tbname]." set lastdotime=".$nowtime." where classid = ".$classid." and id = ".$id);
	//if($pltime)
	//{
	//$dologline="lastdotime = ".date('Y-m-d H:i',$nowtime)."<br>classid=".$classid."<br>classname=".$class_r[$classid][classname];
	//insert_dolog($dologline);
	//} 
	if($sql)
	{
		//操作日志
		insert_dolog("classid=".$classid."<br>classname=".$class_r[$classid][classname]);
		
		//生成评论缓存 [s]
		$fsql=$empire->query("select id,classid,plid from {$dbtbpre}enewspl_{$restb} where ".$add);
		$update_r=array();
		while($r=$empire->fetch($fsql))
		{
			$update_member=$r[classid].':'.$r[id];
			if(!in_array($update_member,$update_r))
			{
				$update_r[$i]=$update_member;
				$i++;
			}
		}
		batch_update_comments_cache($update_r);
		//生成评论缓存 [e]                
		printerror("CheckPlSuccess",$_SERVER['HTTP_REFERER']);
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//批量推荐/取消评论
function DoGoodPl_all($plid,$id,$bclassid,$classid,$isgood,$userid,$username){
	global $empire,$class_r,$dbtbpre,$public_r;
	//验证权限
	//CheckLevel($userid,$username,$classid,"news");
	$restb=(int)$_POST['restb'];
	$count=count($plid);
	if(empty($count)||!$restb)
	{
		printerror("NotGoodPlid","history.go(-1)");
	}
	if(!strstr($public_r['pldatatbs'],','.$restb.','))
	{
		printerror("NotGoodPlid","history.go(-1)");
	}
	$isgood=(int)$isgood;
	for($i=0;$i<$count;$i++)
	{
		$add.="plid='".intval($plid[$i])."' or ";
	}
	$add=substr($add,0,strlen($add)-4);
	$sql=$empire->query("update {$dbtbpre}enewspl_{$restb} set isgood='$isgood' where ".$add);
	if($sql)
	{
		//操作日志
		insert_dolog("isgood=$isgood<br>classid=".$classid."<br>classname=".$class_r[$classid][classname]);
		
		//生成评论缓存 [s]
		$fsql=$empire->query("select id,classid,plid from {$dbtbpre}enewspl_{$restb} where ".$add);
		$update_r=array();
		while($r=$empire->fetch($fsql))
		{
			$update_member=$r[classid].':'.$r[id];
			if(!in_array($update_member,$update_r))
			{
				$update_r[$i]=$update_member;
				$i++;
			}
		}
		batch_update_comments_cache($update_r);
		//生成评论缓存 [e]		
		                
		printerror("DoGoodPlSuccess",$_SERVER['HTTP_REFERER']);
	}
	else
	{printerror("DbError","history.go(-1)");}
}



//单条推荐/取消-屏蔽/取消-审核 评论
function DoFuncPl_one($plid,$id,$bclassid,$classid,$restb,$method,$value,$userid,$username){
	global $empire,$class_r,$dbtbpre;
	$plid	=	intval($plid);
        $restb  =       intval($restb);
	if(empty($plid)){
		echo	'参数错误';
		die();
	}
        $add    =       ' plid='.intval($plid);
	$add1	=	' plid='.intval($plid).' or parent='.intval($plid);//删除评论以及评论的回复,nosun;
	$value	=	(int)$value;
	$str	=	'';
        if($method=="delete"){
		$r	=	$empire->fetch1('select id,classid,plid from '.$dbtbpre.'enewspl_'.$restb.' where '.$add);
		if($class_r[$r[classid]][tbname])
		{
			$usql=$empire->query('update '.$dbtbpre.'ecms_'.$class_r[$r[classid]][tbname].' set plnum=plnum-1 where id='.$r[id]);
		}
		$sql=$empire->query('delete from '.$dbtbpre.'enewspl_'.$restb.' where '.$add1);
		
	}else{
		$sql	=	$empire->query('update '.$dbtbpre.'enewspl_'.$restb.' set '.$method.'='.$value.' where '.$add);
		if($method=="checked")
		{
			$nowtime=time();
			$ptquery="update {$dbtbpre}ecms_".$class_r[$classid][tbname]." set lastdotime=".$nowtime." where classid = ".$classid." and id = ".$id;
			$pltime=$empire->query("update {$dbtbpre}ecms_".$class_r[$classid][tbname]." set lastdotime=".$nowtime." where classid = ".$classid." and id = ".$id);
			if($pltime)
			{
			$dologline="lastdotime = ".date('Y-m-d H:i',$nowtime)."<br> classid=".$classid." and id=".$id."<br>classname=".$class_r[$classid][classname];
			insert_dolog($dologline);
			}
		}
	}
        
	if($sql)
	{
		//操作日志
		insert_dolog($method.'='.$value.'<br>classid='.$classid.'&id='.$id.'<br>classname='.$class_r[$classid][classname]);
		
		//生成评论缓存 [s]
		//$fsql=$empire->query('select id,classid,stb,plid from '.$dbtbpre.'enewspl where '.$add);
		//$update_r=array();
		//while($r=$empire->fetch($fsql))
		//{
		//	$update_member=$r[classid].':'.$r[id];
		//	if(!in_array($update_member,$update_r))
		//	{
		//		$update_r[$i]=$update_member;
		//		$i++;
		//	}
		//}
               // batch_update_comments_cache($update_r);
		//生成评论缓存 [e]
                $r=$empire->fetch1("select classid,id from {$dbtbpre}enewspl_".$restb." where plid='$plid' limit 1");
		get_comments($r[classid],$r[id],0,1);
                
                switch ($method){
			case 'isgood':
				$str	=	$value	? ' <font color="red">推荐成功</font> '	: 	' <font color="red">取消推荐成功</font> ';
				break;
			case 'uncontent':
				$str	=	$value	? ' <font color="red">屏蔽成功</font> '	: 	' <font color="red">取消屏蔽成功</font> ';
				break;
			case 'checked':
				$str	=	$value	? ''	: 	' <font color="red">审核成功</font> ';
				break;
		}
		
	}
	else
	{
		$str	=	'参数错误，请将错误及操作过程提交开发人员';
	}
	
	echo $str;
}

?>