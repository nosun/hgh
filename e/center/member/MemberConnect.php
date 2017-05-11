<?php
//ini_set("display_errors", true);error_reporting(E_ALL);
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
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
CheckLevel($logininid,$loginin,$classid,"memberconnect");

function ReFreshCfg($errurl=NULL) {
    global $empire, $dbtbpre;
    $empire->query("update {$dbtbpre}enewspubvar set varvalue='' where myvar='EITypes'"); //重置扩展变量EITypes[2014年2月17日19:58 LGM增加]
    //生成动态的代码，以构造 tranregexlist 和  trankeywordslist
    $ccmd = "select `id`,`apptype`,`tranregexlist`,`trankeywordslist`,`appname` FROM `{$dbtbpre}enewsmember_connect_app`";
    $sqlq = $empire->query($ccmd);
    $fc = "<?php \r\n \$apprw__arr= array();";
    while ($r = $empire->fetch($sqlq, MYSQL_ASSOC)) {
        $tr = $r['tranregexlist'];
        if ($tr == NULL)
            $tr = '';
        else {
            $tr = trim($tr, ' ,');
        }
        $tk = $r['trankeywordslist'];
        if ($tk == NULL)
            $tk = '';
        else {
            $tk = trim($tk, ' ,');
        }
        $fc .= "\$apprw__arr['" . $r['appname'] . "']=array('tranregexlist'=>array(" . $tr . "),'trankeywordslist'=>array(" . $tk . "));";
    }
    $fc .= "return \$apprw__arr;\r\n?>";
    $pl_filename = ECMS_PATH . 'e' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'apprwcode.php';
    $wr = WriteFiletext_n($pl_filename, $fc); //LGM 增加 ：生成代码文件 2014年2月19日9:41   
    if ($wr > 20) {
        //测试：
        try {
            if (file_exists($pl_filename))
                $testvar = @include($pl_filename);
            unset($apprw__arr);
        }
        catch (Exception $e) {
            $wr = 0;
        }
    }
    if(empty($errurl))$errurl = "history.go(-1)";
    if ($wr < 20 || !isset($testvar) || !is_array($testvar)) {
        printerror("转换正则规则表或者转换关键词表出错！", $errurl);
    }
    GetConfig();
}

/**
 * 设置接口 (2014年1月21日11:39 LGM修改)
 * @param array $add
 * @param int $userid
 * @param string $username
 */
function EditMemberConnect($add,$userid,$username){
	global $empire,$dbtbpre;
	$add[id]=(int)$add[id];
	if(empty($add[appname])||!$add[id])
	{
		printerror("EmptyMemberConnect","history.go(-1)");
    }
	$add['isclose']=(int)$add['isclose'];
	$add['myorder']=(int)$add['myorder'];
	$add['appname']=eaddslashes(ehtmlspecialchars($add['appname']));
	$add['appid']=eaddslashes($add['appid']);
	$add['appkey']=eaddslashes($add['appkey']);
	$add['qappname']=eaddslashes($add['qappname']);
	$add['appsay']=eaddslashes($add['appsay']);
	$exsql='';//增加五个字段[2014年1月21日11:54 LGM修改]
	if(isset($add['callbackurl'])) $exsql.=',callbackurl=\''.eaddslashes($add['callbackurl']).'\'';
	if(isset($add['callbackurl2'])) $exsql.=',callbackurl2=\''.eaddslashes($add['callbackurl2']).'\'';
	if(isset($add['tranregexlist'])) $exsql.=',tranregexlist=\''.eaddslashes($add['tranregexlist']).'\'';
	if(isset($add['trankeywordslist'])) $exsql.=',trankeywordslist=\''.eaddslashes($add['trankeywordslist']).'\'';
	if(isset($add['info'])) $exsql.=',info=\''.eaddslashes($add['info']).'\'';        
	$sql=$empire->query("update {$dbtbpre}enewsmember_connect_app set appname='$add[appname]',appid='$add[appid]',appkey='$add[appkey]',isclose='$add[isclose]',myorder='$add[myorder]',qappname='$add[qappname]',appsay='$add[appsay]'{$exsql} where id='$add[id]'");
	
        $appr=$empire->fetch1("select apptype from {$dbtbpre}enewsmember_connect_app where id='$add[id]'");
	if($sql)
	{
		ReFreshCfg(); 
		//操作日志
		insert_dolog("id=".$add[id]."&apptype=".$appr['apptype']."<br>appname=".$add[appname]);             
		printerror("EditMemberConnectSuccess","MemberConnect.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

function NewMemberConnect($add,$userid,$username){
	global $empire,$dbtbpre;
	$newps = array();
	$newps['appname']=eaddslashes(ehtmlspecialchars($add['appname']));        
        $isexists=$empire->fetch1("select id from {$dbtbpre}enewsmember_connect_app where appname='".$newps['appname']."'",MYSQL_NUM); 
        if(isset($isexists['id']) && $isexists['id']!=0)
        {
            printerror("已经存在{$add['appname']},请重新命名一个应用!","history.go(-1)");
        }
	if(empty($add['appname']) || $add['id'])
	{
		printerror("EmptyMemberConnect","history.go(-1)");
        }
        $newps['appname']=$add['appname'];
        $newps['apptype']=$add['apptype'];
	$newps['isclose']=(int)$add['isclose'];
	$newps['myorder']=(int)$add['myorder'];
	$newps['appid']=$add['appid'];
	$newps['appkey']=$add['appkey'];
	$newps['qappname']=$add['qappname'];
	$newps['appsay']=$add['appsay'];
        $newps['callbackurl']=$add['callbackurl'];
        $newps['callbackurl2']=$add['callbackurl2'];
        $newps['tranregexlist']=$add['tranregexlist'];
        $newps['trankeywordslist']=$add['trankeywordslist'];
        $newps['info']=$add['info'];
        $sqlcmd = BuildSingleTableInsertSqlExp("{$dbtbpre}enewsmember_connect_app", $newps);
        $sql = FALSE;
        if (empty($sqlcmd) == false)
        {
               $sql = $empire->query($sqlcmd);
        }
	if($sql)
	{
                $newid = $empire->lastid();                
                ReFreshCfg("SetMemberConnect.php?enews=EditMemberConnect&id={$newid}"); 		
		//操作日志
		insert_dolog("id=".$add[id]."&apptype=".$newps['apptype']."<br>appname=".$newps['appname']);             
		printerror("NewMemberConnectSuccess","MemberConnect.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}
function DelMemberConnect($delid,$userid,$username)
{
    global $empire,$dbtbpre;
    $did=(int)$delid;
    $cmd = "DELETE FROM {$dbtbpre}enewsmember_connect_app where id={$did}";
    $sql=$empire->query($cmd);
    if($sql && $empire->affectnum()>0)
     {
          ReFreshCfg(); 
	   //操作日志
	  insert_dolog("id=".$did);             
	  printerror("DelMemberConnectSuccess","MemberConnect.php");      
     }
}
$enews = $_POST['enews'];
if (empty($enews)) {
    $enews = $_GET['enews'];
}
//修改用户
if($enews == "EditMemberConnect") {
    EditMemberConnect($_POST, $logininid, $loginin);
}
elseif($enews == "NewMemberConnect")
{
   NewMemberConnect($_POST, $logininid, $loginin);  
}
elseif($enews == "DelMemberConnect")
{
    $delid = (int)$_GET['id'];
   DelMemberConnect($delid, $logininid, $loginin);  
}
$sql = $empire->query("select a.id,a.appname,a.appkey,a.isclose,a.myorder,a.qappname,a.appsay,a.callbackurl,a.callbackurl2,a.tranregexlist,a.trankeywordslist,a.apptype,a.appid,(select count(*) from {$dbtbpre}enewsmember_connect_app as b where b.apptype = a.apptype) as typecount from {$dbtbpre}enewsmember_connect_app a order by a.myorder,a.id",MYSQL_NUM);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>外部登录接口</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script src="/skin/default/js/jquery-1.8.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/skin/default/js/custom.js"></script>
    <script type="text/javascript">
       function ChangeNewEiUrl(sender,elink,einfo)
       {
           var sel = $(sender);
           var selv = sel.val();
           var jopt = $('option:selected',sel);
           if(typeof(einfo)!='undefined' && !!einfo)   $("#"+einfo).text(jopt.data("title"));
           var mlink = $("#"+elink);
           if(!!mlink && mlink.length>0)
           {
               if(selv.length>0)
                  mlink.attr("href","SetMemberConnect.php?enews=NewMemberConnect&apptype="+selv);
              else
                  mlink.attr("href","javascript:alert('请先选择一种应用类型');return false;");
           }
       }
    </script>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="50%">位置：外部接口 &gt; <a href="MemberConnect.php">管理外部登录接口</a> </td>
    <td><div align="right" class="emenubutton"></div></td>
  </tr>
</table>
<br>
<table width="800" border="0" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header">
    <td width="7%"><div align="center">ID</div></td> 
    <td width="32%"><div align="center">接口名称</div></td>
    <td width="10%"><div align="center">状态</div></td>
    <td width="17%" height="25"><div align="center">接口类型</div></td>
    <td width="10%"><div align="center">绑定人数</div></td>
    <td width="10%"><div align="center">登录次数</div></td>
    <td width="14%" height="25"><div align="center">操作</div></td>
  </tr>
  <?php
  while($r=$empire->fetch($sql))
  {
        $membernum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsmember_connect where aid='{$r[id]}' limit 1");
        $loginnum=$empire->gettotal("select sum(loginnum) as total from {$dbtbpre}enewsmember_connect where aid='{$r[id]}' limit 1");        
        $delurl = ' href="MemberConnect.php?enews=DelMemberConnect&id='.((int)$r['id']).'" onclick="javascript:return confirm(\'你真的确定要删除吗?\')"';
        $showtype = $r['apptype'].'('.$r['typecount'].'个)';
        if($r['typecount']<2){ 
            $delurl = ' href="/e/memberconnect/'.$r['apptype'].'/install/index.php"';
            $showtype = $r['apptype'];
        }       
        
  ?>
  <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'">
    <td align="center"><?=$r['id']?></td> 
    <td height="38" align="center"> 
      <?=$r['appname']?>    </td>
    <td><div align="center">
        <?=$r['isclose']==0?'开启':'关闭'?>
      </div></td>
    <td height="38"> <div align="center">
        <?=$showtype?>
      </div></td>
    <td><div align="center"><?=$membernum?></div></td>
    <td><div align="center"><?=intval($loginnum)?></div></td>
    <td height="38"> <div align="center">
            <a href="SetMemberConnect.php?enews=EditMemberConnect&id=<?=$r['id']?>" class="edit">配置应用</a>&nbsp;&nbsp;
            <a <?=$delurl?> class="del">删除</a>
        </div></td>
  </tr>
<?php } ?>
</table>

<div id="newappboxout">
<select id="apptypesel" onchange="javascript:ChangeNewEiUrl(this,'addapplink');">
 <option value="">请选择类型</option>
<?php
  $stypes = $empire->query("select distinct apptype from {$dbtbpre}enewsmember_connect_app",MYSQL_NUM);
   while($r2=$empire->fetch($stypes))
  { 
       echo '<option value="'.$r2[0].'">'.$r2[0].'</option>';
  }
  db_close();
  $empire=null;  
?> 
</select>  
    <span class="addappsit"><a href="javascript:alert('请先选择一种应用类型');return false;" id="addapplink">新增应用</a></span>   
</div>
</body>
</html>
