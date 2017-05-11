<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<h3 class="comments_h3"><a href="#commentform">已经有<?=$num?>评论!</a></h3>
<ol class="commentlist" id="thecomments">
  
<?php
$comments_r=array();
$userids='';
$ar = $empire->fetch1("select restb from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where id='$id' ");
$restb= $ar['restb'];
while($father_r=$empire->fetch($sql))
{
	$parentIDs.=$father_r[plid].',';
	$comments_r[$father_r[plid]]=$father_r;
	if($father_r[userid])
	{
		$userids.=$father_r[userid].',';
	}
}
$parentIDs=substr($parentIDs,0,-1);
if(!empty($parentIDs))
{
	$children_sql=$empire->query("select * from {$dbtbpre}enewspl_".$restb." where parent in(".$parentIDs.") order by plid asc");
	while($children_r=$empire->fetch($children_sql))
	{
                $children_r[restb]=$restb;
		$comments_r[$children_r[parent]]['children'][$children_r[plid]]=$children_r;
		if($children_r[userid])
		{
			$userids.=$children_r[userid].',';
		}
	}
}
//comments user avatar
$avatar_r=array();
$userids=substr($userids,0,-1);
$avatar_r[0]=$public_r[newsurl].$public_r[add_comments_default_avatar];
if(!empty($userids))
{
	$userpic=$empire->query("select userid,userpic from {$dbtbpre}enewsmemberadd where userid in(".$userids.") ");
	while($userpic_r=$empire->fetch($userpic))
	{
		$userpic_r[userpic]	=	trim($userpic_r[userpic]);
		if(empty($userpic_r[userpic]))
		{
			$avatar_r[$userpic_r[userid]]=$avatar_r[0];
		}
		else
		{
			$avatar_r[$userpic_r[userid]]=$userpic_r[userpic];
		}
		$avatar = $avatar_r[$userpic_r[userid]];
	}
}


foreach($comments_r as $plid=>$r)
{
	$plusername=$r[username];
	if(empty($r[username]))
	{
		$plusername=$fun_r['nomember'];
	}
	if($r[userid])
	{
		$plusername="<a href='$public_r[newsurl]e/space/?userid=$r[userid]' target='_blank'>$r[username]</a>";
	}
	if($r[is_admin])
	{
		$plusername=stripSlashes($public_r[add_comments_default_admin_pre]).$r[username];
		$avatar_r[$r[userid]]=$public_r[newsurl].$public_r[add_comments_default_admin_avatar];
	}	
	
	$sayip=ToReturnXhIp($r[sayip]);
	$fr=$empire->fetch1("select * from {$dbtbpre}enewspl_".$restb." where plid='$r[plid]'");
	$saytext=$r[uncontent]?stripSlashes($public_r[add_comments_default_uncontent]):RepPltextFace(stripSlashes($fr['saytext']));//替换表情
	if($r[replyid])
	{
		$r[replyname]=empty($r[replyname])?$fun_r['nomember']:$r[replyname];
		$saytext='<a href="#comment-'.$r[replyid].'" rel="nofollow">@'.$r[replyname].'</a> '.$saytext;
	}	
	//$saytext	=	setNoHtml($saytext);	
	$includelink=" onclick=\"javascript:document.saypl.saytext.value+='[quote]".$r[plid]."[/quote]';document.saypl.repid.value='".$r[plid]."';document.saypl.saytext.focus();\"";
	$depth=0;
        $default_avatar=$public_r[newsurl].$public_r[add_comments_default_avatar];
        $avatar_r[$r[userid]]=empty($avatar_r[$r[userid]])?$default_avatar:$avatar_r[$r[userid]];
?>

  <li class="comment even thread-even depth-<?=$depth?> clearfix" id="li-comment-<?=$r[plid]?>">
    <div id="comment-<?=$r[plid]?>">
      <div class="comment-author-avatar"> <img src='<?=$avatar_r[$r[userid]]?>' class='avatar' height='50' width='50' /></div>
      <div class="comment-author"> <span class="vcard"> <cite class="fn"><?=$plusername?></cite>&nbsp; <span class="says">说：</span> </span> </div>
      <div class="comment-content"><?=$saytext?></div>
      <div class="comment-meta commentmetadata"> <span class="comment-meta-l"> <cite><b>#<?=$r[floor]?> </b></cite>&nbsp;&nbsp;<cite><?=date('Y-m-d H:m:s',$r[saytime])?> </cite>&nbsp;&nbsp;<cite> <?=$sayip?></cite>&nbsp;&nbsp;</span> 
<span class="reply"> <a rel='nofollow' class='comment-reply-link' href='<?=$titleurl?>#respond' onclick='return addComment.moveForm("comment-<?=$r[plid]?>", "<?=$r[plid]?>", "respond", "<?=$classid?>","<?=$id?>")'>回复</a></span> &nbsp; 
 &nbsp; 
<a href="JavaScript:makeRequest('http://www.szhgh.com/e/pl/doaction.php?enews=DoForPl&plid=<?=$r[plid]?>&classid=<?=$classid?>&id=<?=$id?>&dopl=1&doajax=1&ajaxarea=zcpldiv<?=$r[plid]?>','EchoReturnedText','GET','');">支持</a>[<span id="zcpldiv<?=$r[plid]?>"><?=$r[zcnum]?></span>]&nbsp; 
<a href="JavaScript:makeRequest('http://www.szhgh.com/e/pl/doaction.php?enews=DoForPl&plid=<?=$r[plid]?>&classid=<?=$classid?>&id=<?=$id?>&dopl=0&doajax=1&ajaxarea=fdpldiv<?=$r[plid]?>','EchoReturnedText','GET','');">反对</a>[<span id="fdpldiv<?=$r[plid]?>"><?=$r[fdnum]?></span>]
</div>
      <!--子评论开始-->
      <ul class='children' id="children_<?=$r[plid]?>">
        <?=children_comments($r[children],$avatar_r)?>
      </ul>
      <!--子评论结束--> 
    </div>
  </li>
  
<?
}
?>

</ol>
<?=$listpage?>