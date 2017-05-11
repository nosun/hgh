<?php
/*********************作者模型相关的函数	---	start	***************************/
/**
 * 作者$author最后发布文章时间...
 * @param $mid：系统模型ID
 * @param $f：字段名
 * @param $isadd：值为0时是增加信息;值为1时是修改信息
 * @param $isq：值为0时是后台处理;值为1时是前台处理
 * @param $value：字段原内容
 * @param $cs：自定义参数
 * 
 */
function author_wz_lastpublic_time($mid,$f,$isadd,$isq,$value,$cs){
	global $empire,$dbtbpre;
	$rs	=	$empire->fetch1("select max(newstime) as newstime from {$dbtbpre}ecms_article where author='".$_POST['title']."'");
    return $rs['newstime'];
}

/**
 * 作者$author最后更新(最多点击)的$num篇文章 ...
 * author_wz_lastpubluc_id($mid,$f,$isadd,$isq,$value,$cs)
 * @param $mid：系统模型ID
 * @param $f：字段名
 * @param $isadd：值为0时是增加信息;值为1时是修改信息
 * @param $isq：值为0时是后台处理;值为1时是前台处理
 * @param $value：字段原内容
 * @param $cs：自定义参数。0为最新更新，1为最热文章
 * @return string
 */
function author_wz_lastpubluc_id($mid,$f,$isadd,$isq,$value,$cs){
	global $empire,$dbtbpre;
	$return	=	'';
	$arr	=	array();
	$order	=	$cs	?	'order by onclick desc'	:	'order by newstime desc '	;
	$sql	=	$empire->query("select id,author from {$dbtbpre}ecms_article where author='".$_POST['title']."' ".$order." limit 0,5");

	while($r=$empire->fetch($sql))        //循环获取查询记录
	{
		$arr[]	=	$r['id'];
	}
	$return	=	implode(',', $arr);
	return $return;
}

/**
 * 作者文章最近的一些数据汇总 ...
 * @param $mid：系统模型ID
 * @param $f：字段名
 * @param $isadd：值为0时是增加信息;值为1时是修改信息
 * @param $isq：值为0时是后台处理;值为1时是前台处理
 * @param $value：字段原内容
 * @param $cs：自定义参数
 * 			0,0:点击量总和(onclick)
 * 			1,0:30天内发布文章点击量总和(onclick)
 * 			0,1:评论数总和(plnum)
 * 			1,1:30天内发布文章评论数总和(plnum)
 * 			0,2:鲜花数总和(diggtop)
 * 			1,2:30天内发布文章鲜花数总和(diggtop)
 * @return Ambiguous
 * 
 */
function author_wz_total($mid,$f,$isadd,$isq,$value,$cs){
	global $empire,$dbtbpre;
	$arr_cs	=	explode(',', $cs);
	$condition	=	$arr_cs[0]	?	''	:	' and newstime>'.(time()-86400*30);
	switch ((int)$arr_cs[1]){
		case 0:		
			$field	=	'onclick';
			break;
		case 1:
			$field	=	'plnum';
			break;
		case 2:
			$field	=	'diggtop';
			break;
	}
	
	$str_sql	=	"select sum({$field}) as total from {$dbtbpre}ecms_article 
		where author='{$_POST['title']}'{$condition}";
	$rs=$empire->fetch1($str_sql);
    return $rs['total'];
}

//function special_url($mid,$f,$isadd,$isq,$value,$cs){
//	global $empire,$dbtbpre;
//	switch ($_POST['ttid'])
//	{
//		case 1:			//Tags专题
//			$title	=	$_POST['title'];
//			$str_sql=	'select id from '.$dbtbpre.'enewstags where tagname=\''.$title.'\'';
//			$tags_r	=	$empire->fetch1($str_sql);
//			
//			
//			break;
//		case 2:			//普通专题
//			break;
//		case 3:			//复杂专题
//			break;
//		case 4:			//图书专题
//			break;
//	}
//	return $url;
//}





/**
 * Enter description here ...
 * @param unknown_type $tags
 * @param unknown_type $classid
 * @param unknown_type $id
 * @param unknown_type $newstime
 * @return string
 * @author GUOGUO 2012-02-24
 */
function eInsertTags2($tags,$classid,$id,$newstime){
	global $empire,$dbtbpre,$class_r;
	if(!trim($tags))
	{
		printerror("TAGS信息不能为空", "", 1, 0, 1);
		return '';
	}
	
	$count = count($id); //统计ID数量
	$tags = RepPostVar($tags);
	$tag = explode(",", $tags);
	if (empty($count))
	{
		printerror("未选择信息ID", "", 1, 0, 1);
	}
	if (count($tag)>1)
	{
		printerror("只能添加一个TAGS词", "", 1, 0, 1);
	}

	$classid=(int)$classid;
	$id[$i] = (int)$id[$i];
	$mid=(int)$class_r[$classid][modid];
	for($i=0;$i<$count;$i++)
	{
		$tbname=$class_r[$classid][tbname];//获取表名
		$r=$empire->fetch1("select tagid from {$dbtbpre}enewstags where tagname='$tags' limit 1");
		$t = $empire->fetch1("select infotags from {$dbtbpre}ecms_".$tbname." where id='$id[$i]'");
		$taga=$t['infotags'].",".$tags; //组合TAGS
		$tagb[$i] = explode(",",$taga); //设置数组
		$tagc=array_values(array_unique($tagb[$i])); //数组排重
		for($t=0;$t<count($tagc);$t++)
		{//二级子循环TAGS数组输出
			$newtags[$i].= ",".$tagc[$t];
		}
		if($r[tagid])
		{
			$datar=$empire->fetch1("select tagid,classid,newstime from {$dbtbpre}enewstagsdata where tagid='$r[tagid]' and id='$id[$i]' and mid='$mid' limit 1");
			if($datar[tagid])
			{
				if($datar[classid]!=$classid||$datar[newstime]!=$newstime)
				{
					$empire->query("update {$dbtbpre}enewstagsdata set classid='$classid',newstime='$newstime' where tagid='$r[tagid]' and id='$id[$i]' and mid='$mid' limit 1");
				}
			}
			else
			{
				$empire->query("update {$dbtbpre}enewstags set num=num+1 where tagid='$r[tagid]'");
				$empire->query("update {$dbtbpre}ecms_".$tbname." set infotags='".trim($newtags[$i],",")."' where id='$id[$i]'");
				$empire->query("insert into {$dbtbpre}enewstagsdata(tagid,classid,id,newstime,mid) values('$r[tagid]','$classid','$id[$i]','$newstime','$mid');");
			}
		}
		else
		{
			$empire->query("update {$dbtbpre}ecms_".$tbname." set infotags='".trim($newtags[$i],",")."' where id='$id[$i]'");
			$empire->query("insert into {$dbtbpre}enewstags(tagname,num,isgood,cid) values('$tags',1,0,0);");
			$tagid=$empire->lastid();
			$empire->query("insert into {$dbtbpre}enewstagsdata(tagid,classid,id,newstime,mid) values('$tagid','$classid','$id[$i]','$newstime','$mid');");
		}
	}
	printerror("批量添加TAGS成功", "", 1, 0, 1);
}


//去html代码的正则
function setNoHtml($str){
	$search = array ("'<script[^>]*?>.*?</script>'si",  // 去掉 javascript
	                 "'<b>'si",         
	                 "'</b>'si",        
	);                    // 作为 PHP 代码运行
	
	$replace = array ("",
	                  "",
					  "",
					);
	
	$str = preg_replace ($search, $replace, $str);	
	
	return $str;
}


function user_showhyperlink(){
	global $empire,$dbtbpre;
	$str='<ul>';
	$str.='<li><a href=\"\">推荐</a></li>';
	$sql=$empire->query("select typeid,tname from {$dbtbpre}enewsinfotype where mid=11 order by myorder asc,typeid asc");
	while($r=$empire->fetch($sql))        //循环获取查询记录
	{
		$str.='<li><a href=\"\">'.$r['tname'].'</a></li>';
	}
	$str.='</ul>';
	echo $str;
}


//将时间字段转成红色字符串输出
function set_redDate($strTime){
	if($strTime>mktime(0,0,0,date("m"),date("d")-0,date("Y")))
	{
		$dnewstime='<font color=\"#ff0000\">'.date("m-d",$strTime).'</font>';
	}else {
	    $dnewstime=date("m-d",$strTime);
	}
	echo $dnewstime;
}


//取得相关链接
function video_GetKeyboard($keyid,$classid,$link_num){
	global $empire,$public_r,$class_r,$dbtbpre;
	if($keyid&&$link_num)
	{
		$add="id in (".$keyid.")";
		$key_sql=$empire->query("select id,title,titleurl,classid,titlepic,plnum,onclick from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where ".$add." order by newstime desc limit $link_num");
		while($link_r=$empire->fetch($key_sql))
		{
			$keyboardtext.='<li class="clearfix">
                                            <div class="v-thumb left">
                                                <a title="'.$link_r[title].'" href="'.$link_r[titleurl].'" target="_blank"><img alt="'.$link_r[title].'" src="'.$link_r[titlepic].'"></a>
                                            </div>
                                            <div class="v-meta right">
                                                    <div class="v-meta-title">
                                                            <a href="'.$link_r[titleurl].'" title="'.$link_r[title].'" target="_blank">'.$link_r[title].'</a>
                                                    </div>
                                                    <div class="v-meta-detail">
                                                            <span class="v-hnum">'.$link_r[onclick].'</span><span class="v-cnum">'.$link_r[plnum].'</span>
                                                    </div>
                                            </div>
                                       </li>';
		}
	}
	else
	{
		$keyboardtext='无相关信息';
	}
	echo $keyboardtext;
}

function pics_GetKeyboard($keyid,$classid,$link_num){
	global $empire,$public_r,$class_r,$dbtbpre;
	if($keyid&&$link_num)
	{
		$add="id in (".$keyid.")";
		$key_sql=$empire->query("select title,titleurl,titlepic from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where ".$add." order by newstime desc limit $link_num");
		while($link_r=$empire->fetch($key_sql))
		{
			$keyboardtext.='<li>
						<a href="'.$link_r['titleurl'].'" target="_blank"><img src="'.$link_r['titlepic'].'" alt="'.$link_r['title'].'" /></a>									
						<p><a href="'.$link_r['titleurl'].'" target="_blank">'.$link_r['title'].'</a></p>

                                        </li>';
		}
	}
	else
	{
		$keyboardtext='无相关信息';
	}
	echo $keyboardtext;
}

function my_ResizeImg($file,$width,$height,$docut=0,$target_filename='',$target_path='d/file/small/'){
	global $public_r,$ecms_config;
        if(!$file||!$width||!$height)
	{
		return $file;
	}
	//扩展名
	$filetype=GetFiletype($file);
	if(!strstr($ecms_config['sets']['tranpicturetype'],','.$filetype.','))
	{
		return $file;
	}
	$efileurl=eReturnFileUrl();
	if(strstr($file,$efileurl))
	{
		$file=str_replace($efileurl,'/d/file/',$file);
	}
	if(strstr($file,'://'))
	{
		return $file;
	}
	$filename=ECMS_PATH.substr($file,1);
        if(!file_exists($filename))
	{
		return $file;
	}
	if($target_filename)
	{
		$newfilename=$target_filename;
	}
	else
	{
		$newfilename=md5($file.'-'.$width.'-'.$height.'-'.$docut);
	}
        $time=time();
        $ymd=date("Ymd",$time);
        $ym=  substr($ymd,0,6);
	$newpath=ECMS_PATH.$target_path.$ym.'/';
        
        if($public_r['openfileserver']){
            $newurl=$public_r['fs_purl'].'small/'.$ym.'/';}
            else{
            $newurl=$public_r['newsurl'].$target_path.$ym.'/';
        }
        $mk1=DoMkdir($newpath);
	$newname=$newpath.$newfilename;
	if(empty($target_filename)&&file_exists($newname.$filetype))
	{
		return $newurl.$newfilename.$filetype;
	}
	if(!defined('InEmpireCMSGd'))
	{
		include_once ECMS_PATH.'e/class/gd.php';
	}
	$filer=ResizeImage($filename,$newname,$width,$height,$docut);
        $fileurl=$newurl.$newfilename.$filer['filetype'];
        $lfile=$newname.$filer['filetype'];
        // ftp up
	if(!defined('InEmpireCMSFtp'))
	{
		include(ECMS_PATH.'e/class/ftp.php');
	}
	$pr=ReturnPostFtpInfo(1);
	$efileftp=new EmpireCMSFTP();
	$efileftp->fconnect($pr['ftphost'],$pr['ftpport'],$pr['ftpusername'],$pr['ftppassword'],$pr['ftppath'],$pr['ftpssl'],$pr['ftppasv'],$pr['ftpmode'],$pr['ftpouttime']);
	$basepath=$pr['ftppath'].'/';
        $basepath1=$basepath.'small/';
        $efileftp->ftp_mkdirs($basepath1,$ym);
        $hfile=$basepath.ReturnPostFtpFilename($lfile);
        $efileftp->fTranFile($hfile,$lfile,0,0);
	$efileftp->fExit();
        return $fileurl;
}


function ReturnMorepicHtml($pics){
        $str1='';
        $str2='';
        $path_r=explode("\r\n",$pics);
        $picnum=count($path_r);
        for($i=0;$i<$picnum;$i++)
                {
                        $picr=explode('::::::',$path_r[$i]);
                        $str1.='<div class="Hidden picone">
                        <div class="SliderPicBorder"><img src="'.$picr[1].'" /></div>
                        <div class="Summary">'.$picr[2].'</div>
                        </div>';

                        $str2.='<li rel="'.($i+1).'"><img src="'.$picr[0].'" /></li>';
                }
               $value[0]=$str1;
               $value[1]=$str2;
               $value[2]=$picnum;
               return $value;
}
/**
 * 取第三方交互平台参数数组
 * @return array | NULL  array(appname=>array(id,type,name,appkey,appsecret,callbackurl,callbackurl2,info))
 */
function GetEIApps()
{
	global $public_r;
	$vn='add_'.PV_EINAME;
	$vo=$public_r[$vn];
	if(!is_array($vo) && !empty($vo))
	{
		$vo=@unserialize($public_r[$vn]);
                if($vo==FALSE) $vo=@unserialize(stripslashes($public_r[$vn]));
		$public_r[$vn]=$vo;
	}
	return $vo;
}
/**
 * 根据classid和id获取文章的URL和链接
 * @param int $classid
 * @param int $arcid
 * @return array('titleurl','title') | NULL
 */
function GetArcTitleAndUrl($classid, $arcid) {
	global $empire, $dbtbpre, $class_r,$public_r,$link;
	if ($arcid && $classid) {
		if(empty($public_r))include_once ( '/e/class/connect.php');
		if (! class_exists ( 'mysqlquery' ))include_once (ECMS_PATH . 'e' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'db_sql.php');		
		if(empty($class_r)) include_once (ECMS_PATH . 'e' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'dbcache' . DIRECTORY_SEPARATOR . 'class.php');
		$dataini = TRUE;
		if (empty($empire)) {
			$dataini = FALSE;
			$link = db_connect ();
			$empire = new mysqlquery ();
		}
		$r = array();              
		if (! empty ( $class_r [$classid] ['tbname'] ) && ! InfoIsInTable ( $class_r [$classid] ['tbname'] )) {			
			$cmd= "select isurl,titleurl,classid,id,title from {$dbtbpre}ecms_" . $class_r [$classid] ['tbname'] . " where id='{$arcid}' limit 1";
			$rr = $empire->fetch1($cmd,MYSQL_ASSOC);
			if (! empty ( $rr )) {
				$r['titleurl'] = sys_ReturnBqTitleLink ( $rr );
				$r['title'] = $rr['title'];
			}
		}		
		if (! $dataini) {
			db_close ();
			$empire = null;
		}
		if(count($r)==0) return NULL;
		return $r;
	}
	return NULL;
}


/**
 * 增加扩展菜单分类(不需要验证权限)
 * @global mysqlquery $empire
 * @global string $dbtbpre
 * @param string $classname
 * @param int $classtype
 * @param string $otherhtmlcode 要输出的其它代码
 * @param array $msgs 消息列表
 * @param array $logindata 用户登录数据
 * @return int
 */
function AddAdminMenuClass($classname, $classtype,&$otherhtmlcode = '', array &$msgs = array(),array &$logindata=array())
{
    global $empire, $dbtbpre,$classid;
    $menuid = 0;
    if(empty($logindata))
    {
       $logindata=is_login(); 
    }
     if(empty($logindata))  return 0; 
    CheckLevel($logindata['userid'],$logindata['username'],$classid,"menu");
    $classname2 = mysql_real_escape_string($classname);
    $classtype = (int) $classtype;
    $menuidobj = $empire->fetch1("select classid from {$dbtbpre}enewsmenuclass where classname='{$classname2}' and classtype={$classtype} limit 1", MYSQL_ASSOC);
    if (empty($menuidobj['classid']))
    {
        $sql = $empire->query("insert into {$dbtbpre}enewsmenuclass (classname,myorder,classtype) values('{$classname2}',0,{$classtype});");
        if ($sql)
        {
            $menuid = $empire->lastid();
            $otherhtmlcode .= MenuClassToShow();
            insert_dolog("classid=" . $menuid . "<br>classname={$classname}"); //操作日志
            $msgs[] = "增加扩展菜单分类[{$classname}]成功!";
        } else
            $msgs[] = "增加扩展菜单分类[{$classname}]失败!";
    } else
        $menuid = intval($menuidobj['classid']);
    return $menuid;
}

/**
 * 增加菜单(不需要验证权限)
 * @global mysqlquery $empire
 * @global string $dbtbpre
 * @param int $classid
 * @param string $menuname
 * @param string $menuurl
 * @param int $myorder
 * @param array $msgs 消息列表
 * @param array $logindata 用户登录数据
 * @return int
 */
function AddAdminMenu($classid, $menuname, $menuurl, $myorder, array &$msgs = array(),array &$logindata=array())
{
    global $empire, $dbtbpre;
    if(empty($logindata))
    {
       $logindata=is_login(); 
    }
     if(empty($logindata))  return 0; 
    CheckLevel($logindata['userid'],$logindata['username'],0,"menu");
    $classid = (int) $classid;
    if (!$classid || !$menuname || !$menuurl)
    {
        return FALSE;
    }
    $myorder = (int) $myorder;
    $menuname = hRepPostStr($menuname, 1);
    $menuurl = hRepPostStr($menuurl, 1);
    $retid = 0;
    $menuidobj=$empire->fetch1("select menuid from {$dbtbpre}enewsmenu where menuname='{$menuname}' and classid={$classid} limit 1",MYSQL_ASSOC);
    if(empty($menuidobj['menuid']))
    {
        $sql = $empire->query("insert into {$dbtbpre}enewsmenu(menuname,menuurl,myorder,classid) values('" . $menuname . "','" . $menuurl . "','$myorder','$classid');");
        if($sql)
        {
            $retid = $empire->lastid();
            insert_dolog("classid=$classid<br>menuid=" . $retid . "&menuname=" . $menuname); //操作日志
            $msgs[] = "增加扩展菜单[{$retid}-{$menuname}]成功!如果没有出现,请刷新后台框架."; 
        }
    }
    else
    {
         $retid =  intval($menuidobj['menuid']);
    }
   return $retid;
}

?>