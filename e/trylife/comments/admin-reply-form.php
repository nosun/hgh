<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
$link=db_connect();
$empire=new mysqlquery();
$enews=$_POST['enews'];


//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];

$enews=$_POST['enews'];
if(empty($enews))
{
	exit;
}
$plid=(int)$_POST['plid'];
$restb=(int)$_POST['restb'];
if($enews=='adminEditComment')
{
	$saytext=$empire->gettotal("select saytext as total from {$dbtbpre}enewspl_".$restb." where plid='$plid' limit 1");
	$cancel='admin_edit_comment('.$plid.','.$restb.',0)';
} else {
	$cancel='admin_reply('.$plid.',0)';
}
db_close();
$empire=null;

?>
<div id="respond">
  <form action="enews.php" method="post" id="commentform">
    <p id="comments_face"></p>
    <p>
      <textarea name="saytext" rows=6 id="saytext" cols="100%" tabindex="3" style="font-size: 14px;" ><?=stripSlashes($saytext)?></textarea>
    </p>
    <p>
      <input  name="submit" type="button" id="comments_submit" onclick="admin_add_comment(<?=$plid?>);" tabindex="4" value="发送" />
      
     <a href="#" onclick="<?=$cancel?>">取消</a>
      
      <span id="comments_submit_msg"></span> </p>
    <p id="cancel-comment-reply"></p>
    <input type='hidden' name='plid' id="plid" value='<?=$plid?>'/>
    <input type='hidden' name='restb' id="restb" value='<?=$restb?>'/>
    <input type='hidden' name='enews' id="enews" value='<?=$enews?>'/>
  </form>
</div>
