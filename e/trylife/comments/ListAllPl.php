<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/userfun.php");
require("../../class/functions.php");
require_once(AbsLoadLang('pub/fun.php'));
require("../../data/dbcache/class.php");
$link=db_connect();
$empire=new mysqlquery();

//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];
//验证权限
CheckLevel($logininid,$loginin,$classid,"pl");
$start=0;
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$line=50;//每页显示
$page_line=12;
$offset=$page*$line;
$search='';
$add='';
$and='';
//评论表
$restb=(int)$_GET['restb'];
if($restb)
{
	if(!strstr($public_r['pldatatbs'],','.$restb.','))
	{
		printerror('ErrorUrl','');
	}
	$search.='&restb='.$restb;
}
else
{
	$restb=$public_r['pldeftb'];
}
//单个
$id=(int)$_GET['id'];
if($id)
{
	$add.=" where id='$id'";
	$search.="&id=$id";
}
//专题ID
$ztid=(int)$_GET['ztid'];
if($ztid)
{
	$sztr=$empire->fetch1("select ztid,restb from {$dbtbpre}enewszt where ztid='$ztid'");
	if($sztr['ztid'])
	{
		$and=empty($add)?' where ':' and ';
		$add.=$and."pubid='-$ztid'";
		$restb=$sztr['restb'];
	}
	$search.="&ztid=$ztid";
}
//单个
$classid=(int)$_GET['classid'];
if($classid)
{
	$and=empty($add)?' where ':' and ';
	if($class_r[$classid][islast])
	{
		$add.=$and."classid='$classid'";
	}
	else
	{
		$add.=$and.'('.ReturnClass($class_r[$classid][sonclass]).')';
	}
	$search.="&classid=$classid";
}
//审核
$checked=(int)$_GET['checked'];
$addorder=' plid desc ';
if($checked==0)
{
	$and=empty($add)?' where ':' and ';
	$add.=$and."checked=1";
	$search.="&checked=$checked";
	$addorder=' plid ASC ';
}
elseif($checked==1)
{
	$and=empty($add)?' where ':' and ';
	$add.=$and."checked=0";
	$search.="&checked=$checked";
}
elseif($checked==2)
{
	$search.="&checked=$checked";
}
//推荐
$isgood=(int)$_GET['isgood'];
if($isgood)
{
	$and=empty($add)?' where ':' and ';
	$add.=$and."isgood=1";
	$search.="&isgood=$isgood";
}
//搜索
$keyboard=RepPostVar2($_GET['keyboard']);
if($keyboard)
{
	$and=empty($add)?' where ':' and ';
	$show=(int)$_GET['show'];
	if($show==1)//发表者
	{
		$add.=$and."(username like '%".$keyboard."%')";
	}
	elseif($show==2)//ip
	{
		$add.=$and."(sayip like '%".$keyboard."%')";
	}
	elseif($show==3)//内容
	{
		$add.=$and."(saytext like '%".$keyboard."%')";
	}
	$search.="&keyboard=$keyboard&show=$show";
}
$totalquery="select count(*) as total from {$dbtbpre}enewspl_".$restb.$add;
$query="select * from {$dbtbpre}enewspl_".$restb.$add;
//取得总条数
$totalnum=(int)$_GET['totalnum'];
if($totalnum>0)
{
	$num=$totalnum;
}
else
{
	$num=$empire->gettotal($totalquery);
}
$query.=" order by ".$addorder." limit $offset,$line";
$sql=$empire->query($query);
$search.='&totalnum='.$num;
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
//位置
$url="<a href=ListAllPl.php?restb=$restb>管理评论 - 分表".$restb."</a>";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理评论</title>
<link href="../common/css/adminstyle.css" rel="stylesheet" type="text/css">
<script src="../common/JQuery/jquery-1.4.2.min.js"></script>
<script src="../common/artDialog/artDialog.min.js"></script>
<script src="../common/js/badwords.js"></script>
<script src="admin.js"></script>
<script type="text/javascript">
// 设置对话框全局默认配置
(function(){
    var d = art.dialog.defaults;
    
    // 按需加载要用到的皮肤，数组第一个为默认皮肤
    // 如果只使用默认皮肤，可以不填写skin
    //d.skin = [  'facebook', 'chrome','aero', 'default'];
    
    // 支持拖动
    d.drag = true;
    
    // 超过此面积大小的对话框使用替身拖动
    d.showTemp = 100000;
})();


var newsurl='/';
var sitename='乌有之乡管理系统';

function CheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
  }
</script>
<style>
.ecomment {margin:0;padding:0;}
.ecomment {margin-bottom:12px;overflow-x:hidden;overflow-y:hidden;padding-bottom:3px;padding-left:3px;padding-right:3px;padding-top:3px;background:#FFFFEE;padding:3px;border:solid 1px #999;}
.ecommentauthor {float:left; color:#F96; font-weight:bold;}
.ecommenttext {clear:left;margin:0;padding:0;}
</style>
</head>

<body>
<div id="Message_info"></div>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="18%">位置： 
      <?=$url?>
    </td>
    <td width="82%">
        <div align="left" class="emenubutton"> 
            	<input type="button" name="Submit5" value="未审核" onClick="self.location.href='ListAllPl.php?checked=0';">
            	<input type="button" name="Submit5" value="已审核" onClick="self.location.href='ListAllPl.php?checked=1';">            
            	<input type="button" name="Submit52" value="所有评论" onClick="self.location.href='ListAllPl.php?checked=2';">            
        </div>
    </td>
  </tr>
</table>

  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <form name="form2" method="get" action="ListAllPl.php">
    <tr> 
      <td>信息ID： 
        <input name="id" type="text" id="id" value="<?=$id?>" size="6">
        专题ID：
        <input name="ztid" type="text" id="ztid" value="<?=$ztid?>" size="6">
        关键字： 
        <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>"> 
        <select name="show" id="show">
          <option value="1"<?=$show==1?' selected':''?>>发表者</option>
          <option value="2"<?=$show==2?' selected':''?>>IP地址</option>
		  <option value="3"<?=$show==3?' selected':''?>>评论内容</option>
        </select>
        <select name="checked" id="checked">
          <option value="0"<?=$checked==0?' selected':''?>>未审核</option>
          <option value="1"<?=$checked==1?' selected':''?>>已审核</option>
          <option value="2"<?=$checked==2?' selected':''?>>所有评论</option>
        </select>
        <span id="listplclassnav"></span>
		<input name="isgood" type="checkbox" id="isgood" value="1"<?=$isgood==1?' checked':''?>>
        推荐&nbsp;
        <input type="submit" name="Submit2" value="搜索评论">
		<input type=hidden name=restb value=<?=$restb?>>
      </td>
    </tr>
    <tr>
      <td> </td>
    </tr>
	</form>
  </table>
<form name="form1" method="post" action="ecmspl.php" onsubmit="return confirm('确认要操作?');">
<input type=hidden name=classid value=<?=$classid?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=restb value=<?=$restb?>>
  <input name="isgood" type="hidden" id="isgood" value="1">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
    <tr class="header"> 
      <td width="4%" height="25"><div align="center">选择</div></td>
      <td width="16%" height="25"><div align="center">所属信息</div></td>
      <td width="64%" height="25"><div align="center">评论内容(双击内容，进入信息评论页)</div></td>
      <td width="16%" height="25"><div align="center">网名</div></td>
    </tr>
    <?php

	while($r=$empire->fetch($sql))
	{
            	$isgood		=	"";
		$checkedstr	=	"";
		if(!empty($r[checked]))
		{$checked=" title='未审核' style='background:#99C4E3'";}
		else
		{$checked="";}
		if($r['userid'])
		{
			$r['username']="<a href='../../member/AddMember.php?enews=EditMember&userid=$r[userid]' target='_blank'><b>$r[username]</b></a>";
		}
		if(empty($r['username']))
		{
			$r['username']='匿名';
		}
		$r['saytime']=date('Y-m-d H:i:s',$r['saytime']);
		if($r[isgood])
		{
			$r[saytime]='<font color=red>'.$r[saytime].'</font>';
			$isgood='<font color=green>推荐</font>';
		}

		//替换表情
		$saytext=RepPltextFace(stripSlashes($r['saytext']));
                $saytext=setNoHtml($saytext);
		//专题
		$title='';
		if($r['pubid']<0)
		{
			$ztr['ztid']=$r['classid'];
			$titleurl=sys_ReturnBqZtname($ztr);
			$title="<a href='$titleurl' target='_blank'>".$class_zr[$r['classid']]['ztname']."</a>";
			$plurl=$public_r['plurl']."?doaction=dozt&classid=$r[classid]";
		}
		else//信息
		{
			if($class_r[$r[classid]][tbname])
			{
				$index_r=$empire->fetch1("select checked from {$dbtbpre}ecms_".$class_r[$r[classid]][tbname]."_index where id='$r[id]' limit 1");
				//返回表
				$infotb=ReturnInfoMainTbname($class_r[$r[classid]][tbname],$index_r['checked']);
				$infor=$empire->fetch1("select isurl,titleurl,classid,id,title from ".$infotb." where id='$r[id]' limit 1");
				$titleurl=sys_ReturnBqTitleLink($infor);
				$title="<a href='$titleurl' target='_blank'>".stripSlashes($infor[title])."</a>";
			}
			$plurl=$public_r['plurl']."?classid=$r[classid]&id=$r[id]";
		}
                $bclassid	=	(int)$class_r[$r['classid']]['bclassid'];
		if($r['checked'])
		{
			$checkedstr='<font color="orange">待审</font>';
		}
	?>
    <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'" id="comment-<?=$r[plid]?>"> 
      <td height="25" valign="top"> <div align="center"> 
          <input name="plid[]" type="checkbox" id="plid" value="<?=$r[plid]?>"<?=$checked?>>
        </div></td>
       <td height="25"><div align="left"> 
          <?=$title?> <b><?=$r[floor]?'#'.$r[floor]:''?></b>
       </div>
       </td>  
      <td height="25" valign="top" onDblClick="window.open('<?=$titleurl?>');"> 
        <div id="saytext-<?=$r[plid]?>" class="saytext"><?=$saytext?></div>
        <div align="right" style="padding:2px; border-top:1px solid #ccc;margin:3px"> 
        	<a class="ahand"  onclick="admin_edit_comment(<?=$r[plid]?>,<?=$restb?>,1)">[修改]</a>   
        	<a class="ahand"  onclick="admin_reply(<?=$r[plid]?>,<?=$restb?>,1)">[回复]</a> 
        	<?php if($r['checked']){?>
        		<a id="checked-<?=$r['plid']?>" class="ahand" onclick="admin_func(<?=$r['plid']?>,<?=$r['id']?>,<?=$bclassid?>,<?=$r['classid']?>,<?=$restb?>,'checked',0)">[审核]</a>
        	<?php }?>
        	<?php if($r['isgood']){?>
        		<a id="isgood-<?=$r['plid']?>"  class="ahand" onclick="admin_func(<?=$r['plid']?>,<?=$r['id']?>,<?=$bclassid?>,<?=$r['classid']?>,<?=$restb?>,'isgood',0)" >[取消推荐]</a>
        	<?php }else{?>
        		<a id="isgood-<?=$r['plid']?>"  class="ahand" onclick="admin_func(<?=$r['plid']?>,<?=$r['id']?>,<?=$bclassid?>,<?=$r['classid']?>,<?=$restb?>,'isgood',1)" >[推荐]</a>
        	<?php }?>
        		<a class="ahand" onclick="admin_func(<?=$r['plid']?>,<?=$r['id']?>,<?=$bclassid?>,<?=$r['classid']?>,<?=$restb?>,'delete',1)" >[删除]</a>
        </div>
        <div id="reply-content-<?=$r[plid]?>"></div>
        <div id="reply-form-<?=$r[plid]?>"></div>
       
      </td>  
      <td height="25" valign="top"><div align="center"> 
          <table width="100%" border="0" cellspacing="1" cellpadding="3">
            <tr> 
              <td width="20%">网名</td>
              <td width="80%"> 
                 <?=$r[is_admin]?stripSlashes($public_r[add_comments_default_admin_pre]):''?><?=$r[username]?> </td>
              </td>
            </tr>
            <tr> 
              <td>IP</td>
              <td> 
                <?=$r[sayip]?>
              </td>
            </tr>
            <tr> 
              <td>时间</td>
              <td> 
                <?=$r['saytime']?>
              </td>
            </tr>
            <tr> 
              <td></td>
              <td>  <?=$uncontent?> <?=$isgood?><?=$checkedstr?><span id="info-<?=$r[plid]?>"></span> </td>
            </tr> 
          </table>
        </div></td>
    </tr>
    <?php
	}
	db_close();
	$empire=null;
	?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"> <div align="center"> 
          <input type=checkbox name=chkall value=on onclick=CheckAll(this.form)>
        </div></td>
      <td height="25" colspan="3"> <div align="right">
          <input type="submit" name="Submit" value="审核评论" onClick="document.form1.enews.value='CheckPl_all';">
          &nbsp;&nbsp;&nbsp; 
          <input type="submit" name="Submit3" value="推荐评论" onClick="document.form1.enews.value='DoGoodPl_all';document.form1.isgood.value='1';">
          &nbsp;&nbsp;&nbsp; 
          <input type="submit" name="Submit4" value="取消推荐评论" onClick="document.form1.enews.value='DoGoodPl_all';document.form1.isgood.value='0';">
          &nbsp;&nbsp;&nbsp; 
          <input type="submit" name="Submit" value="删除" onClick="document.form1.enews.value='DelPl_all';">
          <input name="enews" type="hidden" id="enews" value="DelPl_all">
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">&nbsp;</td>
      <td height="25" colspan="3"> 
        <?=$returnpage?>
         </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="4"><font color="#666666">说明：多选框为蓝色代表未审核评论，加粗网名为登陆会员，发布时间红色为推荐评论</font></td>
    </tr>
  </table>
</form>
</body>
</html>