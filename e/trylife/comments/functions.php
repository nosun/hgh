<?php
/**
 * version6.6-20111210 lastupdate 20120103
 *
 *
 *
 *
 */

//后台操作与评论缓存 参数需要更新的 数组
function bg_comments_cache($ar)
{
	foreach($ar as $v)
	{
		$exp=explode(":",$v);
		$classid=$exp[0];
		$id=$exp[1];
		DelFiletext(ECMS_PATH.'comments_cache/'.$classid.'/'.$id.'/0.php');
	}
}

//批量更新评论缓存
function batch_update_comments_cache($ar)
{
	//print_r($ar);
	foreach($ar as $v)
	{
		$exp=explode(":",$v);
		$classid=$exp[0];
		$id=$exp[1];
		get_comments($classid,$id,0,1);
	}
}

function comments_cache_path($classid,$id)
{
	global $empire,$dbtbpre,$public_r;
	
	$path=ECMS_PATH.'comments_cache/'.$classid.'/';
	if(!file_exists($path))
	{
		DoMkdir($path);
	}
	
	$path=$path.$id.'/';
	if(!file_exists($path))
	{
		DoMkdir($path);
	}
	
	return $path;	
}

function get_comments($classid,$id,$page=0,$getcache=0)
{
	global $empire,$dbtbpre,$public_r,$class_r,$fun_r;
        if( !$classid || !$id || !$class_r[$classid][tbname] )
	{
		return false;
	}
	//调取评论的信息不存在
	$info_total=$empire->gettotal("select count(*) as total from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where id='$id' ");
	if(!$info_total)
	{
		return false;
	}
	$ar = $empire->fetch1("select restb from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where id='$id' ");
        $restb= $ar['restb'];
	$cachepath=comments_cache_path($classid,$id);
	$cachepath=$cachepath.$page.'.php';
	if(file_exists($cachepath) && !$getcache )
	{
		ob_start();//trylife 2012-01-03 跨域输出
		@include($cachepath);
		$cache=ob_get_contents();
		ob_clean();//echo $cache;
		return $_GET['jsoncallback'].'('.ecms_json(array('content'=>$cache)).')';
		db_close();
		$empire=null;
		exit;	
	}
	//评论总数 这里的评论总数是住楼层评论总数
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewspl_".$restb." where classid='$classid' and id='$id' and checked='0' ");
	$c=ReturnClassAddField($classid,'comments_order,comments_per_page'); 
	$comments_per_page=$c[comments_per_page]?$c[comments_per_page]:$public_r[pl_num];
	$comments_order=$c[comments_order]?' order by plid asc ' : ' order by plid desc ' ;
        
	if($c[comments_tempid])
	{
		$tempnum=$empire->gettotal("select count(*) as total from ".GetTemptb("trylife_enewspltemp")." where tempid='".$c[comments_tempid]."'");
		$comments_tempid=$tempnum?$c[comments_tempid]:$public_r['add_comments_default_tempid'];
	}
	else
	{
		$comments_tempid=$public_r['add_comments_default_tempid'];
	}
	
	if(empty($comments_tempid))
	{
		$comments_tempid=2;
	}	

	//$path=ECMS_PATH.$comments_cache_path.'/'.$classid.'/'.$id.'/';
	$page_line=8;//链接数量
	$start=0;
	$search='';
	
	//现有的楼层总数
	$top_num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewspl_".$restb." where classid='$classid' and id='$id' and checked='0' and parent=0 ");
        //$sql1="select * from {$dbtbpre}enewspl_".$restb." where classid='$classid' and id='$id' and checked='0' and parent=0  ".$comments_order." limit ".$page*$comments_per_page.",".$comments_per_page;
        //var_dump ($sql1);die;
	$sql=$empire->query("select * from {$dbtbpre}enewspl_".$restb." where classid='$classid' and id='$id' and checked='0' and parent=0  ".$comments_order." limit ".$page*$comments_per_page.",".$comments_per_page);
	
        $get_children_sql=$sql;
	
	if($getcache)
	{
                $cache_header='<?php if(!defined("InEmpireCMS")) { exit(); } ?>';
		$listpage=ajax_page1($top_num,$comments_per_page,$page_line,$start,$page,$search);
		@include_once(ECMS_PATH.'e/data/filecache/template/ajax_plfun'.$comments_tempid.'.php');
		ob_start();
		@include(ECMS_PATH.'e/data/filecache/template/ajax_pl'.$comments_tempid.'.php');
		$cache=ob_get_contents();
		ob_clean();//echo $cache;
		WriteFiletext($cachepath,$cache_header.$cache);
		return true;
	}
        
	$listpage=ajax_page1($top_num,$comments_per_page,$page_line,$start,$page,$search);
	
	ob_start();//trylife 2012-01-03 跨域输出
	@require(ECMS_PATH.'e/data/filecache/template/ajax_plfun'.$comments_tempid.'.php');
	@require(ECMS_PATH.'e/data/filecache/template/ajax_pl'.$comments_tempid.'.php');
	$cache=ob_get_contents();//echo $cache;
        ecms_json();
	ob_clean();//echo $cache;
	return $_GET['jsoncallback'].'('.ecms_json(array('content'=>$cache)).')';
	db_close();
	$empire=null;	
}

//前台分页
function ajax_page1($num,$line,$page_line,$start,$page,$search){
	global $fun_r;
	if($num<=$line)
	{
		return '';
	}
	$search		=	htmlspecialchars($search,ENT_QUOTES);
	$url		=	$_SERVER['PHP_SELF'].'?page';
	$snum		=	2;//最小页数
	$totalpage	=	ceil($num/$line);//取得总页数
	$firststr	=	'<a title="'.$fun_r['trecord'].'">&nbsp;<b>'.$num.'</b> </a>&nbsp;&nbsp;';
	//上一页
	if($page<>0)
	{
		$toppage	=	'<a href="javascript:void(0);" onclick="javascript:get_comments(0,0);">'.$fun_r['startpage'].'</a>&nbsp;';
		$pagepr		=	$page-1;
		$prepage	=	'<a href="javascript:void(0);" class="prev" onclick="javascript:get_comments('.$pagepr.',1);">'.$fun_r['pripage'].'</a>';
	}
	//下一页
	if($page!=$totalpage-1)
	{
		$pagenex	=	$page+1;
		$nextpage	=	'&nbsp;<a href="javascript:void(0);" onclick="javascript:get_comments('.$pagenex.',1);">'.$fun_r['nextpage'].'</a>';
		$lastpage	=	'&nbsp;<a href="javascript:void(0);" onclick="javascript:get_comments('.($totalpage-1).',1);">'.$fun_r['lastpage'].'</a>';
	}
	$starti=$page-$snum<0?0:$page-$snum;
	$no=0;
	for($i=$starti;$i<$totalpage&&$no<$page_line;$i++)
	{
		$no++;
		if($page==$i)
		{
			$is_1='<a href="javascript:void(0);" class="current" onclick="javascript:void(0);">';
			$is_2="</a>";
		}
		else
		{
			$is_1='<a href="javascript:void(0);" class="'.$current.'" onclick="javascript:get_comments('.$i.',1);">';
			$is_2="</a>";
		}
		$pagenum=$i+1;
		$returnstr.="&nbsp;".$is_1.$pagenum.$is_2;
	}
	$returnstr='<div id="ecms_comments_pagelist" class="pagination">'.$firststr.$toppage.$prepage.$returnstr.$nextpage.$lastpage.'</div>';
	return $returnstr;
}

//发表评论
function ajax_AddPl($username,$password,$nomember,$key,$saytext,$id,$classid,$repid,$add){
	global $empire,$dbtbpre,$public_r,$class_r,$level_r;
	//验证本时间允许操作
	eCheckTimeCloseDo('pl');
	//验证IP
	ajax_eCheckAccessDoIp('pl');
	$id=(int)$id;
	$repid=(int)$repid;
	$classid=(int)$classid;
	//验证码
	$keyvname='checkplkey';
	if($public_r['plkey_ok'])
	{
		ajax_ecmsCheckShowKey($keyvname,$key,1);
	}
        $ar = $empire->fetch1("select restb from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where id='$id' ");
        $restb= $ar['restb'];
	$parent=(int)$_POST['comment_parent'];//trylife 2011-09-01
	$parent_r=$empire->fetch1("select plid,parent,username from {$dbtbpre}enewspl_".$restb." where plid='$parent' limit 1");
        if($parent_r['plid'] && $parent_r['parent'])
	{
		$parent=$parent_r['parent'];
		$replyid=$parent_r['plid'];
		
	}
	elseif($parent_r['plid'] && !$parent_r['parent'])
	{
		$parent=$parent_r['plid'];
		$replyid=$parent_r['plid'];
	}
	else
	{
		$parent=0;
		$replyid=0;
	} 
	$username=RepPostVar($username);
	$password=RepPostVar($password);
	$muserid=(int)getcvar('mluserid',0,TRUE);//LGM修改,把用户ID加密[2014年3月16日21:43]
	$musername=RepPostVar(getcvar('mlusername'));
	$mgroupid=(int)getcvar('mlgroupid');
	if($muserid)//已登陆
	{
		$cklgr=qCheckLoginAuthstr();
		if($cklgr['islogin'])
		{
			$username=$musername;
		}
		else
		{
			$muserid=0;
		}
	}
	else
	{
		if(empty($nomember))//非匿名
		{
			if(!$username||!$password)
			{
				json_printerror("FailPassword",99);
			}
			$ur=$empire->fetch1("select ".eReturnSelectMemberF('userid,salt,password,checked,groupid')." from ".eReturnMemberTable()." where ".egetmf('username')."='$username' limit 1");
			if(empty($ur['userid']))
			{
				json_printerror("FailPassword",99);
			}
			if(!eDoCkMemberPw($password,$ur['password'],$ur['salt']))
			{
				json_printerror("FailPassword",99);
			}
			if($ur['checked']==0)
			{
                                json_printerror("NotCheckedUser",99);
			}
			$muserid=$ur['userid'];
			$mgroupid=$ur['groupid'];
		}
		else
		{
			$muserid=0;
		}
	}
	if($public_r['plgroupid'])
	{
		if(!$muserid)
		{
			json_printerror("GuestNotToPl",99);
		}
		if($level_r[$mgroupid][level]<$level_r[$public_r['plgroupid']][level])
		{
			json_printerror("NotLevelToPl",99);
		}
	}
	//专题
	$doaction=$add['doaction'];
	if($doaction=='dozt')
	{
		if(!trim($saytext)||!$classid)
		{
			json_printerror("EmptyPl",99);
		}
		//是否关闭评论
		$r=$empire->fetch1("select ztid,closepl,checkpl,restb from {$dbtbpre}enewszt where ztid='$classid'");
		if(!$r['ztid'])
		{
			json_printerror("ErrorUrl",99);
		}
		if($r['closepl'])
		{
			json_printerror("CloseClassPl",99);
		}
		//审核
		if($r['checkpl'])
		{$checked=1;}
		else
		{$checked=0;}
		$restb=$r['restb'];
		$pubid='-'.$classid;
		$id=0;
		$returl=$public_r['plurl']."?doaction=dozt&classid=$classid";
	}
	else//信息
	{
		if(!trim($saytext)||!$id||!$classid)
		{
			json_printerror("EmptyPl",99);
		}
		//表存在
		if(empty($class_r[$classid][tbname]))
		{
			json_printerror("ErrorUrl",99);
		}
		//是否关闭评论
		$r=$empire->fetch1("select classid,stb,restb from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where id='$id' limit 1");
		if(!$r['classid']||$r['classid']!=$classid)
		{
			json_printerror("ErrorUrl",99);
		}
		if($class_r[$r[classid]][openpl])
		{
			json_printerror("CloseClassPl",99);
		}
		//单信息关闭评论
		$pubid=ReturnInfoPubid($classid,$id);
		$finfor=$empire->fetch1("select closepl from {$dbtbpre}ecms_".$class_r[$classid][tbname]."_data_".$r['stb']." where id='$id' limit 1");
		if($finfor['closepl'])
		{
			json_printerror("CloseInfoPl",99);
		}
		//审核
		if($class_r[$classid][checkpl])
		{$checked=1;}
		else
		{$checked=0;}
		$restb=$r['restb'];
		$returl=$public_r['plurl']."?classid=$classid&id=$id";
	}
	//设置参数
	$plsetr=$empire->fetch1("select pltime,plsize,plincludesize,plclosewords,plmustf,plf,plmaxfloor,plquotetemp from {$dbtbpre}enewspl_set limit 1");
	if(strlen($saytext)>$plsetr['plsize'])
	{
		$GLOBALS['setplsize']=$plsetr['plsize'];
		json_printerror("PlSizeTobig",99);
	}
	$fn=substr_count($saytext,"[~e.");
	if($fn>$public_r[add_comments_max_face])
	{
		return ecms_json(array('msgid'=>99,'msg'=>'表情数量超过限制'));
	}        
	$time=time();
	$saytime=$time;
	$pltime=getcvar('lastpltime');
	if($pltime)
	{
		if($time-$pltime<$plsetr['pltime'])
		{
			$GLOBALS['setpltime']=$plsetr['pltime'];
			printerror("PlOutTime","history.go(-1)",1);
		}
	}
	$sayip=egetip();
	$username=RepPostStr($username);
	$username=str_replace("\r\n","",$username);
	$saytext=nl2br(RepFieldtextNbsp($saytext));
	if($repid)
	{
		CkPlQuoteFloor($plsetr['plmaxfloor'],$saytext);//验证楼层
		$saytext=RepPlTextQuote($repid,$saytext,$plsetr,$restb);
	}
	//过滤字符
	$saytext=json_ReplacePlWord($pr['plclosewords'],$saytext);
	$saytext=str_replace($plwords[0],$plwords[1],$saytext);
	if($level_r[$mgroupid]['plchecked'])
	{
		$checked=0;
	}
	$ret_r=ReturnPlAddF($add,$plsetr,0);
        
        //floor
	if($parent)
	{
		$floor=0;
	}
	else
	{
		//录入评论 真实楼层查询
		//$total_floor=$empire->gettotal("select count(*) as total from {$dbtbpre}enewspl_".$restb." where classid='$classid' and id='$id' and parent=0 ");
		//$floor=$total_floor+1;
                $mx =$empire->fetch1("select max(floor) as max from {$dbtbpre}enewspl_".$restb." where classid='$classid' and id='$id'");
		$floor=$mx[max]+1;
	}
	//主表
                $sql=$empire->query("insert into {$dbtbpre}enewspl_".$restb."(pubid,username,sayip,saytime,id,classid,checked,zcnum,fdnum,userid,isgood,saytext,parent,replyid,replyname,floor".$ret_r['fields'].") values('$pubid','".$username."','$sayip','$saytime','$id','$classid','$checked',0,0,'$muserid',0,'".mysql_real_escape_string($saytext)."',$parent,$replyid,'".$parent_r[username]."',$floor".$ret_r['values'].");");
	$plid=$empire->lastid();
	if($doaction!='dozt')
	{
		//信息表加1
		$usql=$empire->query("update {$dbtbpre}ecms_".$class_r[$classid][tbname]." set plnum=plnum+1 where id='$id' limit 1");
	}
	//更新新评论数
	DoUpdateAddDataNum('pl',$restb,1);
	//设置最后发表时间
	$set1=esetcookie("lastpltime",time(),time()+3600*24);
	ecmsEmptyShowKey($keyvname);//清空验证码
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
			'restb'=>$restb,
			'parent'=>$parent,
			'replyid'=>$replyid,
			'replyname'=>$parent_r[username],
			'floor'=>$floor,
			'saytext'=>addslashes($saytext),
		);

		$c=ReturnClassAddField($classid,'comments_order,comments_tempid,comments_per_page'); 
		$comments_per_page=$c[comments_per_page]?$c[comments_per_page]:$public_r[pl_num];
		$comments_order=$c[comments_order]?' order by plid asc ' : ' order by plid desc ' ;
		if($c[comments_tempid])
		{
			$tempnum=$empire->gettotal("select count(*) as total from ".GetTemptb("trylife_enewspltemp")." where tempid='".$c[comments_tempid]."'");
			$comments_tempid=$tempnum?$c[comments_tempid]:$public_r['add_comments_default_tempid'];
		}
		else
		{
			$comments_tempid=$public_r['add_comments_default_tempid'];
		}
		
		if(empty($comments_tempid))
		{
			$comments_tempid=1;
		}
		
		require_once(ECMS_PATH.'e/data/filecache/template/ajax_plfun'.$comments_tempid.'.php');
		
		if($parent)
		{
			$code=children_comment($plr);
		}
		else
		{
			$code=parent_comment($plr);
		}

		$msg=$checked?'评论提交成功，需要管理员审核后才能正式显示':'评论成功';
		if(!$checked)
		{
			//缓存第一页
			get_comments($classid,$id,0,1);
		}
		//trylife 2012-01-03 跨域输出
		return $_GET['jsoncallback'].'('.ecms_json(array('msgid'=>0,'msg'=>$msg,'parent'=>$parent,'code'=>$code,'plid'=>$plid,)).')';
	}
	else
	{json_printerror("DbError",99);}
   
}
//发表评论
function ajax_AddPl1($username,$password,$nomember,$key,$saytext,$id,$classid,$repid,$add){
	global $empire,$public_r,$class_r,$user_userid,$user_username,$user_password,$user_dopass,$user_tablename,$user_salt,$user_checked,$user_group,$dbtbpre,$level_r,$plwords;
	//验证IP
	ajax_eCheckAccessDoIp('pl');
	$id=(int)$id;
	$repid=(int)$repid;
	$classid=(int)$classid;
        $ar = $empire->fetch1("select restb from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where id='$id' ");
        $restb= $ar['restb'];
	$parent=(int)$_POST['comment_parent'];//trylife 2011-09-01
	$parent_r=$empire->fetch1("select plid,parent,username from {$dbtbpre}enewspl_1 where plid='$parent' limit 1");
	if($parent_r[plid] && $parent_r['parent'])
	{
		$parent=$parent_r['parent'];
		$replyid=$parent_r[plid];
		
	}
	elseif($parent_r[plid] && !$parent_r['parent'])
	{
		$parent=$parent_r[plid];
		$replyid=$parent_r[plid];
	}
	else
	{
		$parent=0;
		$replyid=0;
	}
	
	//验证码
        $keyvname='checkplkey';
	if($public_r['plkey_ok'])
	{
		ecmsCheckShowKey($keyvname,$key,1);
	}
	$keyvname='checkplkey';
	//if($public_r['plkey_ok'])
	//{
	//	ajax_ecmsCheckShowKey($keyvname,$key,1);
	//}
	$username=RepPostVar($username);
	$password=RepPostVar($password);
	$muserid=(int)getcvar('mluserid',0,TRUE);//LGM修改,把用户ID加密[2014年3月16日21:43]
	$musername=RepPostVar(getcvar('mlusername'));
	$mgroupid=(int)getcvar('mlgroupid');
	if($muserid)//已登陆
	{
		$username=$musername;
	}
	else
	{
		if(empty($nomember))//非匿名
		{
			//编码转换
			$utfusername=doUtfAndGbk($username,0);
			$password=doUtfAndGbk($password,0);
			//密码
			if(empty($user_dopass))
			{
				$password=md5($password);
			}
			if($user_dopass==3)//16位md5
			{
				$password=substr(md5($password),8,16);
			}
			//双重md5
			if($user_dopass==2)
			{
				$ur=$empire->fetch1("select ".$user_userid.",".$user_salt.",".$user_password.",".$user_checked.",".$user_group." from ".$user_tablename." where ".$user_username."='$utfusername' limit 1");
				$password=md5(md5($password).$ur[$user_salt]);
				$cuser=0;
				if($password==$ur[$user_password])
				{
					$cuser=1;
				}
				if(empty($ur[$user_userid]))
				{
					$cuser=0;
				}
			}
			else
			{
				$ur=$empire->fetch1("select ".$user_userid.",".$user_checked.",".$user_group." from ".$user_tablename." where ".$user_username."='$utfusername' and ".$user_password."='$password' limit 1");
				$cuser=0;
				if($ur[$user_userid])
				{
					$cuser=1;
				}
			}
			if(empty($cuser))
			{
				json_printerror("FailPassword",99);
			}
			if($ur[$user_checked]==0)
			{
				json_printerror("NotCheckedUser",99);
			}
			$muserid=$ur[$user_userid];
			$mgroupid=$ur[$user_group];
		}
		else
		{
			$muserid=0;
		}
	}
	if($public_r['plgroupid'])
	{
		if(!$muserid)
		{
			json_printerror("GuestNotToPl",99);
		}
		if($level_r[$mgroupid][level]<$level_r[$public_r['plgroupid']][level])
		{
			json_printerror("NotLevelToPl",99);
		}
	}
	if(!trim($saytext)||!$id||!$classid)
	{
		json_printerror("EmptyPl",99);
	}
	//表存在
	if(empty($class_r[$classid][tbname]))
	{
		json_printerror("ErrorUrl",99);
	}
	
	if(strlen($saytext)>$public_r[plsize])
	{
		json_printerror("PlSizeTobig",99);
	}
	
	$fn=substr_count($saytext,"[~e.");
	if($fn>$public_r[add_comments_max_face])
	{
		return ecms_json(array('msgid'=>99,'msg'=>'表情数量超过限制'));
	}
	
	$time=time();
	$saytime=$time;
	$pltime=getcvar('lastpltime');
	if($pltime)
	{
		if($time-$pltime<$plsetr['pltime'])
		{
			$GLOBALS['setpltime']=$plsetr['pltime'];
			printerror("PlOutTime","history.go(-1)",1);
		}
	}
	//是否关闭评论
	$r=$empire->fetch1("select classid,closepl from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where id='$id' and classid='$classid'");
	if(empty($r[classid]))
	{json_printerror("ErrorUrl",99);}
	if($class_r[$r[classid]][openpl])
	{json_printerror("CloseClassPl",99);}
	//单信息关闭评论
	if($r['closepl'])
	{
		json_printerror("CloseInfoPl",99);
	}
	$sayip=egetip();
	$username=RepPostStr($username);
	$username=str_replace("\r\n","",$username);
	$saytext=nl2br(RepFieldtextNbsp($saytext));
	$pr=$empire->fetch1("select plclosewords,plf,plmustf,pldeftb from {$dbtbpre}enewspublic limit 1");
	if($repid)
	{
		if(trim($saytext)=="[quote]".$repid."[/quote]")
		{
			json_printerror("EmptyPl",99);
		}
		$saytext=RepPlTextQuote($repid,$saytext,$pr);
	}
	//过滤字符
	$saytext=json_ReplacePlWord($pr['plclosewords'],$saytext);
	
	$saytext=str_replace($plwords[0],$plwords[1],$saytext);
	
	//审核
	if($class_r[$classid][checkpl])
	{$checked=1;}
	else
	{$checked=0;}
	$ret_r=ReturnPlAddF($add,$pr,0);
	//主表
	
	//floor
	if($parent)
	{
		$floor=0;
	}
	else
	{
		//录入评论 真实楼层查询
		$total_floor=$empire->gettotal("select count(*) as total from {$dbtbpre}enewspl_1 where classid='$classid' and id='$id' and parent=0 ");
		//$total_floor=$empire->gettotal("select floor as total from {$dbtbpre}enewspl where classid='$classid' and id='$id' and parent=0 order by floor desc limit 1 ");
		$floor=$total_floor+1;
	}
	$sql=$empire->query("insert into {$dbtbpre}enewspl_1(username,sayip,saytime,id,classid,checked,zcnum,fdnum,userid,isgood,stb,parent,replyid,replyname,floor) values('".$username."','$sayip','$saytime','$id','$classid','$checked',0,0,'$muserid',0,'$pr[pldeftb]',$parent,$replyid,'".$parent_r[username]."',$floor);");
	$plid=$empire->lastid();
	//副表
                $fsql=$empire->query("insert into {$dbtbpre}enewspl_1(plid,classid,id,saytext".$ret_r['fields'].") values('$plid','$classid','$id','".mysql_real_escape_string($saytext)."'".$ret_r['values'].");");
	//信息表加1
	$usql=$empire->query("update {$dbtbpre}ecms_".$class_r[$classid][tbname]." set plnum=plnum+1 where id='$id'");
	//设置最后发表时间
	$set1=esetcookie("lastpltime",time(),time()+3600*24);
	ecmsEmptyShowKey($keyvname);//清空验证码
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
			'restb'=>$restb,
			'parent'=>$parent,
			'replyid'=>$replyid,
			'replyname'=>$parent_r[username],
			'floor'=>$floor,
			'saytext'=>addslashes($saytext),
		);

		$c=ReturnClassAddField($classid,'comments_order,comments_tempid,comments_per_page'); 
		$comments_per_page=$c[comments_per_page]?$c[comments_per_page]:$public_r[pl_num];
		$comments_order=$c[comments_order]?' order by plid asc ' : ' order by plid desc ' ;
		if($c[comments_tempid])
		{
			$tempnum=$empire->gettotal("select count(*) as total from ".GetTemptb("trylife_enewspltemp")." where tempid='".$c[comments_tempid]."'");
			$comments_tempid=$tempnum?$c[comments_tempid]:$public_r['add_comments_default_tempid'];
		}
		else
		{
			$comments_tempid=$public_r['add_comments_default_tempid'];
		}
		
		if(empty($comments_tempid))
		{
			$comments_tempid=1;
		}
		
		require_once(ECMS_PATH.'e/data/filecache/template/ajax_plfun'.$comments_tempid.'.php');
		
		if($parent)
		{
			$code=children_comment($plr);
		}
		else
		{
			$code=parent_comment($plr);
		}

		$msg=$checked?'评论提交成功，需要管理员审核后才能正式显示':'评论成功';
		if(!$checked)
		{
			//缓存第一页
			get_comments($classid,$id,0,1);
		}
		//trylife 2012-01-03 跨域输出
		return $_GET['jsoncallback'].'('.ecms_json(array('msgid'=>0,'msg'=>$msg,'parent'=>$parent,'code'=>$code,'plid'=>$plid,)).')';
	}
	else
	{json_printerror("DbError",99);}
}

//禁用字符
function json_ReplacePlWord($plclosewords,$text){
	global $empire,$dbtbpre;
	if(empty($text))
	{
		return $text;
	}
	json_toCheckCloseWord($text,$plclosewords,'HavePlCloseWords');
	return $text;
}

//验证包含字符
function json_toCheckCloseWord($word,$closestr,$mess){
	if($closestr&&$closestr!='|')
	{
		$checkr=explode('|',$closestr);
		$ckcount=count($checkr);
		for($i=0;$i<$ckcount;$i++)
		{
			if($checkr[$i]&&stristr($word,$checkr[$i]))
			{
				json_printerror($mess,"history.go(-1)",1);
			}
		}
	}
}

//错误提示
function json_printerror($error="",$msgid=''){
	global $empire,$editor,$ecmslang,$public_r;
	$a=ECMS_PATH.'e/data/';


	@require_once(AbsLoadLang('pub/q_message.php'));
	$msg=$qmessage_r[$error];
	
	if(empty($msg))
	{
		@require_once(AbsLoadLang('pub/message.php'));
		$msg=$message_r[$error];
	}
	
	//trylife 2012-01-03 跨域输出
	echo $_GET['jsoncallback'].'('.ecms_json(array('msgid'=>$msgid,'msg'=>$msg,)).')';

	db_close();
	$empire=null;
	exit();
}

//验证提交IP
function ajax_eCheckAccessDoIp($doing){
	global $public_r,$empire,$dbtbpre;
	$pr=$empire->fetch1("select opendoip,closedoip,doiptype from {$dbtbpre}enewspublic limit 1");
	if(!strstr($pr['doiptype'],','.$doing.','))
	{
		return '';
	}
	$userip=egetip();
	//允许IP
	if($pr['opendoip'])
	{
		$close=1;
		foreach(explode("\n",$pr['opendoip']) as $ctrlip)
		{
			if(preg_match("/^(".preg_quote(($ctrlip=trim($ctrlip)),'/').")/",$userip))
			{
				$close=0;
				break;
			}
		}
		if($close==1)
		{
			json_printerror('NotCanPostIp',99);
		}
	}
	//禁止IP
	if($pr['closedoip'])
	{
		foreach(explode("\n",$pr['closedoip']) as $ctrlip)
		{
			if(preg_match("/^(".preg_quote(($ctrlip=trim($ctrlip)),'/').")/",$userip))
			{
				json_printerror('NotCanPostIp',99);
			}
		}
	}
}

//检查验证码
function ajax_ecmsCheckShowKey($varname,$postval,$dopr,$ecms=0){
	global $public_r;
	$r=explode(',',getcvar($varname,$ecms));
	$cktime=$r[0];
	$pass=$r[1];
	$val=$r[2];
	$time=time();
	if($cktime>$time||$time-$cktime>$public_r['keytime']*60)
	{
		json_printerror('OutKeytime',99);
	}
	if(empty($postval)||md5($postval)<>$val)
	{
		json_printerror('FailKey',99);
	}
	$checkpass=md5(md5(md5($postval).'EmpireCMS'.$cktime).$public_r['keyrnd']);
	if($checkpass<>$pass)
	{
		json_printerror('FailKey',99);
	}
}

//生成评论文件
//1.生成评论列表调用文件
//2.生成父子单评论回调函数
function exta_GetPlTempPage($pltempid=0)
{
	exta_GetPlTempPage1($pltempid);
	exta_GetPlTempPage2($pltempid);
}
function exta_GetPlTempPage1($pltempid=0){
	global $empire,$public_r,$fun_r,$dbtbpre;
	$pl_t_filename=ECMS_PATH.'e/trylife/comments/pltemp.txt';
	$yplfiletemp=ReadFiletext($pl_t_filename);
	$yplfiletemp=str_replace("\\","\\\\",$yplfiletemp);
	//导航栏
	$url="<a href=".$public_r[newsurl].">".$fun_r['index']."</a>&nbsp;>&nbsp;[!--title--]&nbsp;>&nbsp;".$fun_r['newspl']."&nbsp;>";
	$pagetitle="<?=\$pagetitle?> ".$fun_r['newspl'];
	$pagekey=$pagetitle;
	$pagedes=$pagetitle;
	//$pr=$empire->fetch1("select plf from {$dbtbpre}enewsplf limit 1");
	//回车字段
	$tobrf=',';
	$plfsql=$empire->query("select f from {$dbtbpre}enewsplf where ftype='VARCHAR' or ftype='TEXT' or ftype='MEDIUMTEXT' or ftype='LONGTEXT'");
	while($plfr=$empire->fetch($plfsql))
	{
		$tobrf.=$plfr[f].',';
	}
	$pr['pltobrf']=$tobrf;
	//取得评论页面模板
	$where=$pltempid?" where tempid='$pltempid'":'';
	$ptsql=$empire->query("select tempid,temptext from ".GetTemptb("trylife_enewspltemp").$where);
	while($ptr=$empire->fetch($ptsql))
	{
		$plfiletemp=$yplfiletemp;
		$pl_filename=ECMS_PATH.'e/data/filecache/template/ajax_pl'.$ptr[tempid].'.php';
		$pltemp=$ptr['temptext'];
		//头部变量
		$pltemp=ReplaceSvars($pltemp,$url,0,$pagetitle,$pagekey,$pagedes,$add,1);
		$pltemp=RepSearchRtemp($pltemp,$url);
		//变量
		$pltemp=str_replace("[!--title--]","<?=\$title?>",$pltemp);
		$pltemp=str_replace("[!--titleurl--]","<?=\$titleurl?>",$pltemp);
		$pltemp=str_replace("[!--id--]","<?=\$id?>",$pltemp);
		$pltemp=str_replace("[!--classid--]","<?=\$classid?>",$pltemp);
		$pltemp=str_replace("[!--plnum--]","<?=\$num?>",$pltemp);
		$pltemp=str_replace("[!--depth--]","<?=\$depth?>",$pltemp);		
		
		//评分
		$pltemp=str_replace("[!--pinfopfen--]","<?=\$pinfopfen?>",$pltemp);
		$pltemp=str_replace("[!--infopfennum--]","<?=\$infopfennum?>",$pltemp);
		//登录
		$pltemp=str_replace("[!--key.url--]",$public_r[newsurl]."e/ShowKey/?v=pl",$pltemp);
		$pltemp=str_replace("[!--lusername--]","<?=\$lusername?>",$pltemp);
		$pltemp=str_replace("[!--lpassword--]","<?=\$lpassword?>",$pltemp);
		
		//列表变量
		$listtemp_r=explode("[!--empirenews.listtemp--]",$pltemp);
		$plfiletemp=str_replace("<!--empire.listtemp.top-->",$listtemp_r[0],$plfiletemp);
		$plfiletemp=str_replace("<!--empire.listtemp.footer-->",$listtemp_r[2],$plfiletemp);
		
		
		//列表中间
		$listtemp_center=str_replace("[!--plid--]","<?=\$r[plid]?>",$listtemp_r[1]);
		$listtemp_center=str_replace("[!--pltext--]","<?=\$saytext?>",$listtemp_center);
		$listtemp_center=str_replace("[!--pltime--]","<?=date('Y-m-d H:m:s',\$r[saytime])?>",$listtemp_center);
		$listtemp_center=str_replace("[!--plip--]","<?=\$sayip?>",$listtemp_center);
		$listtemp_center=str_replace("[!--username--]","<?=\$plusername?>",$listtemp_center);
		$listtemp_center=str_replace("[!--userid--]","<?=\$r[userid]?>",$listtemp_center);
		$listtemp_center=str_replace("[!--includelink--]","<?=\$includelink?>",$listtemp_center);
		$listtemp_center=str_replace("[!--zcnum--]","<?=\$r[zcnum]?>",$listtemp_center);
		$listtemp_center=str_replace("[!--fdnum--]","<?=\$r[fdnum]?>",$listtemp_center);
		$listtemp_center=str_replace("[!--avatar--]","<?=\$avatar_r[\$r[userid]]?>",$listtemp_center);
		$listtemp_center=str_replace("[!--floor--]","<?=\$r[floor]?>",$listtemp_center);
		$listtemp_center=str_replace("[!--even_odd--]","<?=\$even_odd?>",$listtemp_center);
		$listtemp_center=ReplacePlListVars($listtemp_center,$r,$pr,0);
		
		//子评论
		$center_r=explode("[!--empirenews.children--]",$listtemp_center);
		$listtemp_center=$center_r[0]."<!--children.var-->".$center_r[2];
				
		$plfiletemp=str_replace("<!--empire.listtemp.center-->",$listtemp_center,$plfiletemp);
		$plfiletemp=str_replace("<!--children.var-->",'<?=children_comments(\$r[children],\$avatar_r)?>',$plfiletemp);
		$plfiletemp=str_replace("<!--empire.listtemp.children-->",$center_r[1],$plfiletemp);		
		WriteFiletext($pl_filename,$plfiletemp);
	}
}

function exta_GetPlTempPage2($pltempid=0){
	global $empire,$public_r,$fun_r,$dbtbpre;
	$pl_t_filename=ECMS_PATH.'e/trylife/comments/pltempfun.txt';
	$yplfiletemp=ReadFiletext($pl_t_filename);
	$yplfiletemp=str_replace("\\","\\\\",$yplfiletemp);
	//导航栏
	$url="<a href=".$public_r[newsurl].">".$fun_r['index']."</a>&nbsp;>&nbsp;[!--title--]&nbsp;>&nbsp;".$fun_r['newspl']."&nbsp;>";
	$pagetitle="<?=\$pagetitle?> ".$fun_r['newspl'];
	$pagekey=$pagetitle;
	$pagedes=$pagetitle;
	//$pr=$empire->fetch1("select plf from {$dbtbpre}enewspublic limit 1");
	//回车字段
	$tobrf=',';
	$plfsql=$empire->query("select f from {$dbtbpre}enewsplf where ftype='VARCHAR' or ftype='TEXT' or ftype='MEDIUMTEXT' or ftype='LONGTEXT'");
	while($plfr=$empire->fetch($plfsql))
	{
		$tobrf.=$plfr[f].',';
	}
	$pr['pltobrf']=$tobrf;
	//取得评论页面模板
	$where=$pltempid?" where tempid='$pltempid'":'';
	$ptsql=$empire->query("select tempid,temptext from ".GetTemptb("trylife_enewspltemp").$where);
	while($ptr=$empire->fetch($ptsql))
	{
		$plfiletemp=$yplfiletemp;
		$pl_filename=ECMS_PATH.'e/data/filecache/template/ajax_plfun'.$ptr[tempid].'.php';
		$pltemp=$ptr['temptext'];
		//头部变量
		$pltemp=ReplaceSvars($pltemp,$url,0,$pagetitle,$pagekey,$pagedes,$add,1);
		$pltemp=RepSearchRtemp($pltemp,$url);
		//变量
		$pltemp=str_replace("[!--title--]","<?=\$title?>",$pltemp);
		$pltemp=str_replace("[!--titleurl--]","<?=\$titleurl?>",$pltemp);
		$pltemp=str_replace("[!--id--]","<?=\$id?>",$pltemp);
		$pltemp=str_replace("[!--classid--]","<?=\$classid?>",$pltemp);
		$pltemp=str_replace("[!--plnum--]","<?=\$num?>",$pltemp);
		$pltemp=str_replace("[!--depth--]","<?=\$depth?>",$pltemp);		
		
		//评分
		$pltemp=str_replace("[!--pinfopfen--]","<?=\$pinfopfen?>",$pltemp);
		$pltemp=str_replace("[!--infopfennum--]","<?=\$infopfennum?>",$pltemp);
		//登录
		$pltemp=str_replace("[!--key.url--]",$public_r[newsurl]."e/ShowKey/?v=pl",$pltemp);
		$pltemp=str_replace("[!--lusername--]","<?=\$lusername?>",$pltemp);
		$pltemp=str_replace("[!--lpassword--]","<?=\$lpassword?>",$pltemp);
		
		//列表变量
		$listtemp_r=explode("[!--empirenews.listtemp--]",$pltemp);
		$plfiletemp=str_replace("<!--empire.listtemp.top-->",$listtemp_r[0],$plfiletemp);
		$plfiletemp=str_replace("<!--empire.listtemp.footer-->",$listtemp_r[2],$plfiletemp);
		
		
		//列表中间
		$listtemp_center=str_replace("[!--plid--]","<?=\$r[plid]?>",$listtemp_r[1]);
		$listtemp_center=str_replace("[!--pltext--]","<?=\$saytext?>",$listtemp_center);
		$listtemp_center=str_replace("[!--pltime--]","<?=date('Y-m-d H:m:s',\$r[saytime])?>",$listtemp_center);
		$listtemp_center=str_replace("[!--plip--]","<?=\$sayip?>",$listtemp_center);
		$listtemp_center=str_replace("[!--username--]","<?=\$plusername?>",$listtemp_center);
		$listtemp_center=str_replace("[!--userid--]","<?=\$r[userid]?>",$listtemp_center);
		$listtemp_center=str_replace("[!--includelink--]","<?=\$includelink?>",$listtemp_center);
		$listtemp_center=str_replace("[!--zcnum--]","<?=\$r[zcnum]?>",$listtemp_center);
		$listtemp_center=str_replace("[!--fdnum--]","<?=\$r[fdnum]?>",$listtemp_center);
		$listtemp_center=str_replace("[!--avatar--]","<?=\$avatar_r[\$r[userid]]?>",$listtemp_center);
		$listtemp_center=str_replace("[!--floor--]","<?=\$r[floor]?>",$listtemp_center);
		$listtemp_center=str_replace("[!--even_odd--]","<?=\$even_odd?>",$listtemp_center);
		$listtemp_center=ReplacePlListVars($listtemp_center,$r,$pr,0);
		
		//子评论
		$center_r=explode("[!--empirenews.children--]",$listtemp_center);
		$listtemp_center=$center_r[0].$center_r[2];
				
		$plfiletemp=str_replace("<!--empire.listtemp.center-->",$listtemp_center,$plfiletemp);
		$plfiletemp=str_replace("<!--empire.listtemp.children-->",$center_r[1],$plfiletemp);		
	
		WriteFiletext($pl_filename,$plfiletemp);
	}
}

?>