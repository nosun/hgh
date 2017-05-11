<?php
require("../../class/connect.php");
if(!defined('InEmpireCMS'))
{
	exit();
}
eCheckCloseMods('member');//关闭模块
$myuserid=(int)getcvar('mluserid',0,TRUE);//LGM修改,把用户ID加密[2014年3月16日21:43]
$mhavelogin=0;
$id=(int)$_GET['id'];
$classid=(int)$_GET['classid'];
if($myuserid)
{
	include("../../class/db_sql.php");
        include("../../class/t_functions.php");
	include("../../member/class/user.php");
	include("../../data/dbcache/MemberLevel.php");
	$link=db_connect();
	$empire=new mysqlquery();
	$mhavelogin=1;
	//数据
	$myusername=RepPostVar(getcvar('mlusername'));
	$myrnd=RepPostVar(getcvar('mlrnd'));
	$r=$empire->fetch1("select ".eReturnSelectMemberF('userid,username,groupid,userfen,money,userdate,havemsg,checked')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$myuserid' and ".egetmf('rnd')."='$myrnd' limit 1");
	if(empty($r[userid])||$r[checked]==0)
	{
		EmptyEcmsCookie();
		$mhavelogin=0;
	}
	//会员等级
	if(empty($r[groupid]))
	{$groupid=eReturnMemberDefGroupid();}
	else
	{$groupid=$r[groupid];}
	$groupname=$level_r[$groupid]['groupname'];
	//点数
	$userfen=$r[userfen];
	//余额
	$money=$r[money];
	//天数
	$userdate=0;
        //头像
        $showpic_r=$empire->fetch1("select userpic from {$dbtbpre}enewsmemberadd where userid=".(int)$r[userid]." limit 1");
        if(empty($showpic_r[userpic])){
            $userpic=$public_r[newsurl]."e/data/images/nouserpic.jpg";             
        } else {
            $userpic=$showpic_r[userpic];
        }

	if($r[userdate])
	{
		$userdate=$r[userdate]-time();
		if($userdate<=0)
		{$userdate=0;}
		else
		{$userdate=round($userdate/(24*3600));}
	}
	//是否有短消息
	$havemsg="";
	if($r[havemsg])
	{
            include_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'com_functions.php');
            $msgcount=GetSiteMsgCount($myusername,FALSE);
            if($msgcount<2) $msgcount='';
            else $msgcount='('.$msgcount.')';
            $havemsg="<a href='".$public_r[newsurl]."e/member/msg/' target=_blank><font color=red>您有新消息{$msgcount}</font></a>";
	}
	//$myusername=$r[username];

        //头像
        $showpic_r=$empire->fetch1("select userpic from {$dbtbpre}enewsmemberadd where userid=".(int)$r[userid]." limit 1");
        if(empty($showpic_r[userpic])){
            $userpic=$public_r[newsurl]."e/data/images/nouserpic.jpg";             
        } else {
            $userpic=sys_ResizeImg($showpic_r[userpic],64,64,1,'');
        }

	db_close();
	$empire=null;
}
if($mhavelogin==1)
{
?>
document.write("<div id=\"plpost\" class=\"section_content\" havelogin=\"<?=$mhavelogin?>\">    <div class=\"inputpl\">        <div class=\"avatar left\">            <img src=\"<?=$userpic?>\" />        </div>        <div class=\"inputarea right\">            <form action=\"http://www.szhgh.com/e/pl/doaction.php\" method=\"post\" name=\"saypl\" id=\"saypl\" onsubmit=\"return CheckPl(document.saypl)\">                                       <div class=\"retext\">                    <textarea id=\"saytext\" name=\"saytext\" cols=\"58\" rows=\"4\"></textarea>                    <div class=\"state\" id=\"statebox\" style=\"display: none;\">                     </div>                </div>                <div class=\"subtool\">                    <div class=\"left\">                        <div id=\"face\" class=\"face\">                            <a class=\"facebutton\" title=\"点击查看可插入的表情\">表情</a>                            <div class=\"facebox\" style=\"display: none;\">                                <script src=\"http://www.szhgh.com/d/js/js/plface.js\"></script>                            </div>                        </div>                        <div class=\"sync\">                        </div>                    </div>                    <div class=\"right\"><span><script type=\"text/javascript\">document.write(\'<script  type=\"text/javascript\" src=\"http://www.szhgh.com/e/memberconnect/panjs.php?type=pl&dclass=pl<?php  $op_pt='&pagetitle='.urlencode($_REQUEST['pagetitle']);if(!empty($_REQUEST['titlepic'])) $op_pt .='&titlepic='.urlencode($_REQUEST['titlepic']);if(!empty($_REQUEST['playurl'])){ if(isset($GLOBALS['__playurl']))$GLOBALS['__playurl'] = array(); $GLOBALS['__playurl'][$id]=$_REQUEST['playurl'];} $op_url ='&classid='.$classid.'&id='.$id. $op_pt.'&titleurl='.urlencode($_REQUEST['titleurl']);echo $op_url; ?>&t=\'+Math.random()+\'\"><\'+\'/script>\');</script></span><input class=\"submitbutton\" name=\"imageField\" type=\"submit\" id=\"imageField\" value=\"发表评论\" /></div>                    <div class=\"clear\"></div>                </div>                <input name=\"id\" type=\"hidden\" id=\"id\" value=\"<?=$id?>\" />                <input name=\"classid\" type=\"hidden\" id=\"classid\" value=\"<?=$classid?>\" />                <input name=\"enews\" type=\"hidden\" id=\"enews\" value=\"AddPl\" />                <input name=\"repid\" type=\"hidden\" id=\"repid\" value=\"0\" />            </form>        </div>        <div class=\"clear\"></div>    </div></div>");
<?
}
else
{
?>
document.write("<div id=\"plpost\" class=\"section_content\" havelogin=\"<?=$mhavelogin?>\">    <div class=\"inputpl\">        <div class=\"avatar left\">            <img src=\"http://www.szhgh.com/e/data/images/nouserpic.jpg\" />        </div>        <div class=\"inputarea right\">            <form action=\"http://www.szhgh.com/e/pl/doaction.php\" method=\"post\" name=\"saypl\" id=\"saypl\" onsubmit=\"return CheckPl(document.saypl)\">                                       <div class=\"retext\">                    <div class=\"state\" id=\"statebox\" style=\"\">                        <span class=\"msg\">请先&nbsp;<a href=\"#loginmodal\" title=\"点此登录\" class=\"flatbtn loginbutton\" id=\"modaltrigger_plinput\">登录</a>&nbsp;<a class=\"registerbutton\" href=\"http://www.szhgh.com/e/member/register/\" title=\"点此注册本站账号\" target=\"_blank\">还没有注册？</a><span><script type=\"text/javascript\">document.write(\'<script type=\"text/javascript\" src=\"http://www.szhgh.com/e/memberconnect/panjs.php?type=login&dclass=login<?php  $op_pt='&pagetitle='.urlencode($_REQUEST['pagetitle']);if(!empty($_REQUEST['titlepic'])) $op_pt .='&titlepic='.urlencode($_REQUEST['titlepic']);if(!empty($_REQUEST['playurl'])){ $cur_id = 0;if(isset($id))$cur_id = $id;else $cur_id = $_REQUEST['id'];if($cur_id)    {     if(isset($GLOBALS['__playurl']))$GLOBALS['__playurl'] = array();     $GLOBALS['__playurl'][$cur_id]=$_REQUEST['playurl'];     $op_pt.='&id'.$cur_id;    }} $op_url =$op_pt.'&titleurl='.urlencode($_REQUEST['titleurl']);echo $op_url; ?>&t=\'+Math.random()+\'\"></\'+\'script>\');</script></span></span>                    </div>                </div>                <div class=\"subtool\">                    <div class=\"left\">                        <div class=\"sync\">                        </div>                    </div>                    <div class=\"right\"><input class=\"submitbutton\" name=\"imageField\" type=\"submit\" id=\"imageField\" disabled=\"true\" value=\"发表评论\" /></div>                    <div class=\"clear\"></div>                </div>                <input name=\"id\" type=\"hidden\" id=\"id\" value=\"<?=$id?>\" />                <input name=\"classid\" type=\"hidden\" id=\"classid\" value=\"<?=$classid?>\" />                <input name=\"enews\" type=\"hidden\" id=\"enews\" value=\"AddPl\" />                <input name=\"repid\" type=\"hidden\" id=\"repid\" value=\"0\" />            </form>        </div>        <div class=\"clear\"></div>    </div></div>");
<?
}
?>