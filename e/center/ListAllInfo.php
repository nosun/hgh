<?php
define('EmpireCMSAdmin','1');
require('../class/connect.php');
require('../class/db_sql.php');
require('../class/functions.php');
require_once(AbsLoadLang('pub/fun.php'));
require("../data/dbcache/class.php");
$link=db_connect();
$empire=new mysqlquery();
//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];
//取得数据表
$tid=(int)$public_r['tid'];
$tbname=$_GET['tbname']?$_GET['tbname']:$public_r['tbname'];
$tbname=RepPostVar($tbname);
$changetbs='';
$havetb=0;
$tbsql=$empire->query("select tid,tbname,tname from {$dbtbpre}enewstable order by tid");
while($tbr=$empire->fetch($tbsql))
{
	$selected='';
	if($tbname==$tbr[tbname])
	{
		$tid=$tbr[tid];
		$selected=' selected';
		$havetb=1;
	}
	$changetbs.="<option value='".$tbr[tbname]."'".$selected.">".$tbr[tname]."(".$tbr[tbname].")</option>";
}
if($havetb==0)
{
	printerror('ErrorUrl','');
}
//取得相应的信息
$user_r=$empire->fetch1("select groupid,adminclass from {$dbtbpre}enewsuser where userid='$logininid'");
//取得用户组
$gr=$empire->fetch1("select doall,doselfinfo from {$dbtbpre}enewsgroup where groupid='$user_r[groupid]'");
//管理员
$where='';
$and='';
$ewhere='';
$search	=empty($tagname)?'&tbname='.$tbname:'&tagname='.$tagname.'&tbname='.$tbname;
$ecmscheck=(int)$_GET['ecmscheck'];
$addecmscheck='';
$indexchecked=1;
if($ecmscheck)
{
	$search.='&ecmscheck='.$ecmscheck;
	$addecmscheck='&ecmscheck='.$ecmscheck;
	$indexchecked=0;
}
$infotb=ReturnInfoMainTbname($tbname,$indexchecked);
//优化
$modid=$etable_r[$tbname][mid];
$yhadd='';
$yhvar='hlist';
$yhid=$etable_r[$tbname][yhid];
if($yhid)
{
	$yhadd=ReturnYhSql($yhid,$yhvar);
	if($yhadd)
	{
		$and=$where?' and ':' where ';
		$where.=$and.$yhadd;
	}
}
if(empty($yhadd))
{
	//时间范围
	$infolday=(int)$_GET['infolday'];
	if(empty($infolday))
	{
		$infolday=$public_r['infolday'];
	}
	if($infolday&&$infolday!=1)
	{
		$ckinfolday=time()-$infolday;
		$and=$where?' and ':' where ';
		$where.=$and."newstime>'$ckinfolday'";
		$search.="&infolday=$infolday";
	}
}
if(!$gr['doall'])
{
	$cids='';
	$a=explode("|",$user_r['adminclass']);
	for($i=1;$i<count($a)-1;$i++)
	{
		$dh=',';
		if(empty($cids))
		{
			$dh='';
		}
		$cids.=$dh.$a[$i];
	}
	if($cids=='')
	{
		$cids=0;
	}
	$and=$where?' and ':' where ';
	$where.=$and.'classid in ('.$cids.')';
}
//只能编辑自己的信息
if($gr['doselfinfo'])
{
	$and=$where?' and ':' where ';
	$where.=$and."userid='$logininid' and ismember=0";
}
$url="<a href=ListAllInfo.php?tbname=".$tbname.$addecmscheck.">管理信息</a>";
$start=0;
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$line=intval($public_r['hlistinfonum']);//每页显示
$page_line=21;
$offset=$page*$line;
    //模型 nosun 20130524 ,6.6to7.0
    $infomod_r=$empire->fetch1('select enter,mid,listfile from '.$dbtbpre.'enewsmod where mid=\''.$modid.'\'');
        //期刊筛选
    $addtoqk =(int)$_GET['addtoqk'];
    if($addtoqk==0){
        $where = $where;   
    }
    elseif($addtoqk==1){
        $and = $where?' and ':' where ';
        $where.= $and."addtoqk >0"; 
    }
    else{
        $and = $where?' and ':' where ';
        $where.= $and. "addtoqk =".$addtoqk;
    }
//栏目ID
/*
 
$classid=intval($_GET['classid']);
if($classid)
{
	$and=$where?' and ':' where ';
	if($class_r[$classid][islast])
	{
		$where.=$and."classid='$classid'";
	}
	else
	{
		$where.=$and."(".ReturnClass($class_r[$classid][sonclass]).")";
	}
	$search.="&classid=$classid";
}
 */

    //标题分类
    $ttid		=	(int)$_GET['ttid'];
    if($ttid)
    {
            $and	=	$where?' and ':' where ';
            $where	.=	$and.'ttid=\''.$ttid.'\'';
            $search	.=	'&ttid='.$ttid;
    }

//标题分类
$tts='';
$ttsql=$empire->query("select typeid,tname from {$dbtbpre}enewsinfotype where mid='$infomod_r[mid]' order by myorder");
while($ttr=$empire->fetch($ttsql))
{
	$select='';
	if($ttr[typeid]==$ttid)
	{
		$select=' selected';
	}
	$tts.="<option value='$ttr[typeid]'".$select.">$ttr[tname]</option>";
}
$stts=$tts?"<select name='ttid'><option value='0'>标题分类</option>$tts</select>":"";
$fieldexp	=	"<!--field--->";
$recordexp	=	"<!--record-->";
$infomod_r['enter'].='发布者<!--field--->username<!--record-->关键字<!--field--->keyboard<!--record-->ID<!--field--->id<!--record-->';
$searchoptions_r	=	ReturnSearchOptions($infomod_r['enter'],$fieldexp,$recordexp);
//搜索
$sear=$_GET['sear'];
if($sear)
{
	$and=$where?' and ':' where ';
	$showspecial=$_GET['showspecial'];
	$specialvalue	=	$_GET['specialvalue'];
	$compare		=	$_GET['compare'];
	if($showspecial==1)//置顶
	{
		if ($specialvalue){
			if($compare)
			{
			$where.=$and.'istop '.$compare.' '. $specialvalue;
			}
			else
			{		
			$where.=$and.'istop='.$specialvalue;
			}
		}else{
			$where.=$and.'istop>0';
		}
	}
	elseif($showspecial==2)//推荐
	{
		if ($specialvalue){
			if($compare)
			{
			$where.=$and.'isgood '.$compare.' '. $specialvalue;
			}
			else
			{		
			$where.=$and.'isgood='.$specialvalue;
			}
		}else{
			$where.=$and.'isgood>0';
		}
	}
	elseif($showspecial==3)//头条
	{
		if ($specialvalue){
			if($compare)
			{
			$where.=$and.'firsttitle '.$compare.' '. $specialvalue;
			}
			else
			{
			$where.=$and.'firsttitle='.$specialvalue;
			}
		}else{
			$where.=$and.'firsttitle>0';
		}
        }        
	elseif($showspecial==5)//签发
	{
		$where.=$and.'isqf=1';
	}
	elseif($showspecial==7)//投稿
	{
		$where.=$and.'ismember=1';
	}
	elseif($showspecial==8)//我的信息
	{
		$where.=$and."userid='$logininid' and ismember=0";
	}
	$and=$where?' and ':' where ';
	//栏目ID
	$classid=intval($_GET['classid']);
	
	if($classid)
	{
		$and=$where?	' and '	:	' where ';
		
		if($class_r[$classid][islast])
		{
			$where	.=	$and.'classid='.$classid;
		}
		else
		{
			$where	.=	$and.'('.ReturnClass($class_r[$classid][sonclass]).')';
		}
		$search	.=	'&classid='.$classid;
	}
	
	//关键字
	if($_GET['keyboard'])
	{
		$and	=	$where	?	' and '	:	' where ';
		
		$keyboard	=	RepPostVar2($_GET['keyboard']);
		$show		=	RepPostVar($_GET['show']);
		//搜索全部
		if(!$show)
		{
			$where	.=	$and.'('.str_replace('[!--key--]',$keyboard,$searchoptions_r['searchallfield']).')';
		}
		//搜索字段
		elseif($show&&strstr($infomod_r['enter'],'<!--field--->'.$show.'<!--record-->'))
		{
			$where	.=		$show!="id"	?	$and.' ('.$show.' like \'%'.$keyboard.'%\')'	:	$and.'  ('.$show.'=\''.$keyboard.'\')';
			$searchoptions_r['select']	=	str_replace(' value="'.$show.'">',' value="'.$show.'" selected>',$searchoptions_r['select']);
		}
	}
		$search	.=	'&sear=1&keyboard='.$keyboard.'&show='.$show.'&showspecial='.$showspecial.'&specialvalue='.$specialvalue.'&compare='.$compare;
}
//显示重复标题
if($_GET['showretitle']==1)
{
	$and=$where?' and ':' where ';
	$search.="&showretitle=1&srt=".$_GET['srt'];
	$addsrt="";
	$srtid="";
	$first=1;
	$srtsql=$empire->query("select id,title from ".$infotb." group by title having(count(*))>1");
	while($srtr=$empire->fetch($srtsql))
	{
		if($first==1)
		{
			$addsrt.="title='".addslashes($srtr['title'])."'";
			$srtid.=$srtr['id'];
			$first=0;
		}
		else
		{
			$addsrt.=" or title='".addslashes($srtr['title'])."'";
			$srtid.=",".$srtr['id'];
		}
	}
	if(!empty($addsrt))
	{
		if($_GET['srt']==1)
		{
			$where.=$and."(".$addsrt.") and id not in (".$srtid.")";
		}
		else
		{
			$where.=$and."(".$addsrt.")";
		}
	}
	else
	{
		printerror("HaveNotReInfo","ListAllInfo.php?tbname=".$tbname.$addecmscheck);
	}
}
$tb = $empire->fetch1("select mid from {$dbtbpre}enewstable where tbname='".$tbname."'");
$mid = $tb[mid];
$tagid=$_GET[tagid];
if ($tagid){
    $where .= " and id in (select id from {$dbtbpre}enewstagsdata where mid={$mid} and tagid={$tagid})";
    $search .= '&tagid='.$tagid;
}
//排序
$orderby=$_GET['orderby'];
$doorderby=$orderby?'asc':'desc';
$myorder=$_GET['myorder'];
if($myorder==1)//ID号
{$doorder="id";}
elseif($myorder==2)//时间
{$doorder="newstime";}
elseif($myorder==5)//评论数
{$doorder="plnum";}
elseif($myorder==3)//人气
{$doorder="onclick";}
elseif($myorder==4)//下载
{$doorder="totaldown";}
else//默认排序
{$doorder="newstime";}
$doorder.=' '.$doorderby;
$search.="&myorder=$myorder&orderby=$orderby";
$totalquery="select count(*) as total from ".$infotb.$where;
//表信息数
$tbinfos=eGetTableRowNum("{$dbtbpre}ecms_".$tbname);
$tbckinfos=eGetTableRowNum("{$dbtbpre}ecms_".$tbname."_check");
//取得总条数
$totalnum=intval($_GET['totalnum']);
if($totalnum<1)
{
	if(empty($where))
	{
		$num=$indexchecked==1?$tbinfos:$tbckinfos;
	}
	else
	{
		$num=$empire->gettotal($totalquery);
	}
}
else
{
	$num=$totalnum;
}
$search1=$search;
$search.="&totalnum=$num";
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
$phpmyself=urlencode(eReturnSelfPage(1));
//导入页面
$deftempfile=ECMS_PATH.'e/data/html/list/alllistinfo.php';
if($infomod_r[listfile])
{
	$tempfile=ECMS_PATH.'e/data/html/list/all'.$infomod_r[listfile].'.php';
	if(!file_exists($tempfile))
	{
		$tempfile=$deftempfile;
	}
}
else
{
	$tempfile=$deftempfile;
}
require($tempfile);
db_close();
$empire=null;


//返回搜索字段列表
function ReturnSearchOptions($enter,$field,$record){
	global $modid,$emod_r;
	
	$r		=	explode($record,$enter);
	$count	=	count($r)-1;
	for($i=0;$i<$count;$i++)
	{
		if($i==0)
		{
			$or="";
		}
		else
		{
			$or=" or ";
		}
		$r1=explode($field,$r[$i]);
		
		if($r1[1]=="special.field"||strstr($emod_r[$modid]['tbdataf'],','.$r1[1].','))
		{
			continue;
		}
		if($r1[1]=="id")
		{
			$sr['searchallfield'].=$or.$r1[1].'=\'[!--key--]\'';
			$sr['select'].='<option value="'.$r1[1].'">'.$r1[0].'</option>';
			continue;
		}
		$sr['searchallfield'].=$or.$r1[1].' like \'%[!--key--]%\'';
		$sr['select'].='<option value="'.$r1[1].'">'.$r1[0].'</option>';
	}
	return $sr;
}

?>