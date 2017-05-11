<?php
require("../../class/connect.php");
eCheckCloseMods('pl');//关闭模块
$id=(int)$_GET['id'];
$classid=(int)$_GET['classid'];
$num=(int)$_GET['num'];
if($num<1||$num>80)
{
	$num=10;
}
$doaction=$_GET['doaction']=='dozt'?'dozt':'';
require("../../class/db_sql.php");
require("../../class/q_functions.php");
require("../../class/t_functions.php");
$link=db_connect();
$empire=new mysqlquery();
//专题
if($doaction=='dozt')
{
	if(empty($classid))
	{
		exit();
	}
	//信息
	$infor=$empire->fetch1("select ztid,restb from {$dbtbpre}enewszt where ztid='$classid' limit 1");
	if(!$infor['ztid'])
	{
		exit();
	}
	$pubid='-'.$classid;
}
else
{
	if(empty($id)||empty($classid))
	{
		exit();
	}
	include("../../data/dbcache/class.php");
	if(empty($class_r[$classid]['tbname']))
	{
		exit();
	}
	//信息
	$infor=$empire->fetch1("select classid,restb from {$dbtbpre}ecms_".$class_r[$classid]['tbname']." where id='$id' limit 1");
	if(!$infor['classid']||$infor['classid']!=$classid)
	{
		exit();
	}
	$pubid=ReturnInfoPubid($classid,$id);
}
//排序
$addorder='saytime desc';
$myorder=(int)$_GET['myorder'];
if($myorder==1)
{
	$addorder='plid';
}
$sql=$empire->query("select * from {$dbtbpre}enewspl_".$infor['restb']." where pubid='$pubid' and checked=0 order by ".$addorder." limit ".$num);
$num=$empire->num1($sql);
if($num){
?>
document.write("<ul class=\"comment_list\">");
<?php
while($r=$empire->fetch($sql))
{
	$plusername=$r[username];
	if(empty($r[username]))
	{
		$plusername='匿名';
	}
	if($r[userid])
	{
            $showpic_r=$empire->fetch1("select userpic from {$dbtbpre}enewsmemberadd where userid=".(int)$r[userid]." limit 1");
            if(empty($showpic_r[userpic])){
                $userpic="$public_r[newsurl]e/data/images/nouserpic.jpg";             
            } else {
                $userpic=sys_ResizeImg($showpic_r[userpic],64,64,1,'');
            }
            $plusername="<a href='$public_r[newsurl]e/member/cp/' target='_blank'>$r[username]</a>";
	} else {
            $userpic="$public_r[newsurl]/e/data/images/nouserpic.jpg";
        }

	$saytime=date('Y-m-d H:i:s',$r['saytime']);
	//ip
	$sayip=ToReturnXhIp($r[sayip]);
        $saytext=str_replace("\r\n","<br />",$r['saytext']);
        $saytext=str_replace("\r","<br />",$saytext);
        $saytext=str_replace("\n","<br />",$saytext);
        $saytext=str_replace("<br /><br />","<br />",$saytext);
	$saytext=addslashes(RepPltextFace(stripSlashes($saytext)));//替换表情
?>
document.write("<li>    <div class=\"userpic left\"><img src=\"<?=$userpic?>\" /></div>    <div class=\"comment right\">        <div class=\"property\">            <strong class=\"left\"><?=$plusername?></strong>            <em class=\"right\"><?=$saytime?></em>            <div class=\"clear\"></div>        </div>        <div class=\"pltext\"><?=$saytext?></div>        <div class=\"interaction\">            <a onclick=\"javascript:document.saypl.repid.value=\'<?=$r[plid]?>\';document.saypl.saytext.focus();return false;\" href=\"#tosaypl\">回复</a>            <a href=\"JavaScript:makeRequest(\'http://www.szhgh.com/e/pl/doaction.php?enews=DoForPl&plid=<?=$r[plid]?>&classid=<?=$classid?>&id=<?=$id?>&dopl=1&doajax=1&ajaxarea=zcpldiv<?=$r[plid]?>\',\'EchoReturnedText\',\'GET\',\'\');\">支持</a>[<span id=\"zcpldiv<?=$r[plid]?>\"><?=$r[zcnum]?></span>]&nbsp;             <a href=\"JavaScript:makeRequest(\'http://www.szhgh.com/e/pl/doaction.php?enews=DoForPl&plid=<?=$r[plid]?>&classid=<?=$classid?>&id=<?=$id?>&dopl=0&doajax=1&ajaxarea=fdpldiv<?=$r[plid]?>\',\'EchoReturnedText\',\'GET\',\'\');\">反对</a>[<span id=\"fdpldiv<?=$r[plid]?>\"><?=$r[fdnum]?></span>]        </div>    </div>    <div class=\"clear\"></div></li>");
<?php
}
?>
document.write("</ul>");
<?php
}else{
    echo 'document.write("<div class=\"comment\">暂无评论</div>");';
}
db_close();
$empire=null;
?>