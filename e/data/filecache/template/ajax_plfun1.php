<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<?php
//children comments 
function children_comments($children_r,$avatar_r)
{
	global $empire,$dbtbpre,$fun_r,$public_r;
	$count=count($children_r);
	if($count)
	{
		?>
		<ul class='children'>
		<?
		foreach($children_r as $plid=>$r)
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
			$fr=$empire->fetch1("select * from {$dbtbpre}enewspl_".$r[restb]." where plid='$r[plid]'");
                        $classid=$fr[classid];
                        $id=$fr[id];
			$saytext=$r[uncontent]?stripSlashes($public_r[add_comments_default_uncontent]):RepPltextFace(stripSlashes($fr['saytext']));//替换表情
			if($r[replyid])
			{
				$r[replyname]=empty($r[replyname])?$fun_r['nomember']:$r[replyname];
				$saytext='<a href="#comment-'.$r[replyid].'" rel="nofollow">@'.$r[replyname].'</a> '.$saytext;
			}				
			$includelink=" onclick=\"javascript:document.saypl.saytext.value+='[quote]".$r[plid]."[/quote]';document.saypl.repid.value='".$r[plid]."';document.saypl.saytext.focus();\"";
			$depth=1;
			?>
			
        <li class="comment even thread-even depth-<?=$depth?> clearfix" id="li-comment-<?=$r[plid]?>">
          <div id="comment-<?=$r[plid]?>">
            <div class="comment-author-avatar"> <img src='<?=$avatar_r[$r[userid]]?>' class='avatar' height='50' width='50' /></div>
            <div class="comment-author"> <span class="vcard"> <cite class="fn"><?=$plusername?></cite>&nbsp; <span class="says">说：</span> </span> </div>
            <div class="comment-content"><?=$saytext?></div>
            <div class="comment-meta commentmetadata"> <span class="comment-meta-l"> <cite><?=date('Y-m-d H:m:s',$r[saytime])?> </cite>&nbsp;&nbsp;<cite> <?=$sayip?></cite>&nbsp;&nbsp;</span> <span class="reply">
             <a rel='nofollow' class='comment-reply-link' href='<?=$titleurl?>#respond' onclick='return addComment.moveForm("comment-<?=$r[plid]?>", "<?=$r[plid]?>", "respond", "<?=$classid?>","<?=$id?>")'>回复</a> &nbsp; 
<a href="JavaScript:makeRequest('http://www.szhgh.com/e/pl/doaction.php?enews=DoForPl&plid=<?=$r[plid]?>&classid=<?=$classid?>&id=<?=$id?>&dopl=1&doajax=1&ajaxarea=zcpldiv<?=$r[plid]?>','EchoReturnedText','GET','');">支持</a>[<span id="zcpldiv<?=$r[plid]?>"><?=$r[zcnum]?></span>]&nbsp; 
<a href="JavaScript:makeRequest('http://www.szhgh.com/e/pl/doaction.php?enews=DoForPl&plid=<?=$r[plid]?>&classid=<?=$classid?>&id=<?=$id?>&dopl=0&doajax=1&ajaxarea=fdpldiv<?=$r[plid]?>','EchoReturnedText','GET','');">反对</a>[<span id="fdpldiv<?=$r[plid]?>"><?=$r[fdnum]?></span>]
</span> </div>
          </div>
        </li>
        
			<?
		}
		?>
		</ul>
		<?
	}
}

//get parent comment
function parent_comment($r)
{
	global $empire,$dbtbpre,$public_r,$fun_r;
	$plusername=$r[username];
        $restb =$r['restb'];
	if(empty($r[username]))
	{
		$plusername=$fun_r['nomember'];
	}
	if($r[userid])
	{
		$plusername="<a href='$public_r[newsurl]e/space/?userid=$r[userid]' target='_blank'>$r[username]</a>";
	}
	
	if($r[userid]==0)
	{
		$avatar_r[$r[userid]]=$public_r[newsurl].$public_r[add_comments_default_avatar];
	}
	else
	{
		$userpic=$empire->gettotal("select userpic as total from {$dbtbpre}enewsmemberadd where userid='".$r[userid]."' limit 1");
		$avatar_r[$r[userid]]=empty($userpic)?$avatar_r[$r[userid]]:$userpic;
	}

	$sayip=ToReturnXhIp($r[sayip]);
	$fr=$empire->fetch1("select * from {$dbtbpre}enewspl_".$restb." where plid='$r[plid]'");
	$saytext=RepPltextFace(stripSlashes($fr['saytext']));//替换表情
	if($r[replyid])
	{
		$r[replyname]=empty($r[replyname])?$fun_r['nomember']:$r[replyname];
		$saytext='<a href="#comment-'.$r[replyid].'" rel="nofollow">@'.$r[replyname].'</a> '.$saytext;
	}				
	$includelink=" onclick=\"javascript:document.saypl.saytext.value+='[quote]".$r[plid]."[/quote]';document.saypl.repid.value='".$r[plid]."';document.saypl.saytext.focus();\"";
	$depth=1;
        $default_avatar=$public_r[newsurl].$public_r[add_comments_default_avatar];
        $avatar_r[$r[userid]]=empty($avatar_r[$r[userid]])?$default_avatar:$avatar_r[$r[userid]];
	ob_start();
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
        
      </ul>
      <!--子评论结束--> 
    </div>
  </li>
  
	<?
	$code=ob_get_contents();
	ob_clean();
	return $code;
}

//get children comment
function children_comment($r)
{
	global $empire,$dbtbpre,$public_r,$fun_r;
	$plusername=$r[username];
	if(empty($r[username]))
	{
		$plusername=$fun_r['nomember'];
	}
	if($r[userid])
	{
		$plusername="<a href='$public_r[newsurl]e/space/?userid=$r[userid]' target='_blank'>$r[username]</a>";
	}
	
	if($r[userid]==0)
	{
		$avatar_r[$r[userid]]=$public_r[newsurl].$public_r[add_comments_default_avatar];
	}
	else
	{
		$userpic=$empire->gettotal("select userpic as total from {$dbtbpre}enewsmemberadd where userid='".$r[userid]."' limit 1");
		$avatar_r[$r[userid]]=empty($userpic)?$avatar_r[$r[userid]]:$userpic;
	}
        $restb =$r['restb'];
	$sayip=ToReturnXhIp($r[sayip]);
	$fr=$empire->fetch1("select * from {$dbtbpre}enewspl_".$restb." where plid='$r[plid]'");
	$saytext=RepPltextFace(stripSlashes($fr['saytext']));//替换表情
	if($r[replyid])
	{
		$r[replyname]=empty($r[replyname])?$fun_r['nomember']:$r[replyname];
		$saytext='<a href="#comment-'.$r[replyid].'" rel="nofollow">@'.$r[replyname].'</a> '.$saytext;
	}				
	$includelink=" onclick=\"javascript:document.saypl.saytext.value+='[quote]".$r[plid]."[/quote]';document.saypl.repid.value='".$r[plid]."';document.saypl.saytext.focus();\"";
	$depth=1;
        $default_avatar=$public_r[newsurl].$public_r[add_comments_default_avatar];
        $avatar_r[$r[userid]]=empty($avatar_r[$r[userid]])?$default_avatar:$avatar_r[$r[userid]];
	ob_start();
	?>
	
        <li class="comment even thread-even depth-<?=$depth?> clearfix" id="li-comment-<?=$r[plid]?>">
          <div id="comment-<?=$r[plid]?>">
            <div class="comment-author-avatar"> <img src='<?=$avatar_r[$r[userid]]?>' class='avatar' height='50' width='50' /></div>
            <div class="comment-author"> <span class="vcard"> <cite class="fn"><?=$plusername?></cite>&nbsp; <span class="says">说：</span> </span> </div>
            <div class="comment-content"><?=$saytext?></div>
            <div class="comment-meta commentmetadata"> <span class="comment-meta-l"> <cite><?=date('Y-m-d H:m:s',$r[saytime])?> </cite>&nbsp;&nbsp;<cite> <?=$sayip?></cite>&nbsp;&nbsp;</span> <span class="reply">
             <a rel='nofollow' class='comment-reply-link' href='<?=$titleurl?>#respond' onclick='return addComment.moveForm("comment-<?=$r[plid]?>", "<?=$r[plid]?>", "respond", "<?=$classid?>","<?=$id?>")'>回复</a> &nbsp; 
<a href="JavaScript:makeRequest('http://www.szhgh.com/e/pl/doaction.php?enews=DoForPl&plid=<?=$r[plid]?>&classid=<?=$classid?>&id=<?=$id?>&dopl=1&doajax=1&ajaxarea=zcpldiv<?=$r[plid]?>','EchoReturnedText','GET','');">支持</a>[<span id="zcpldiv<?=$r[plid]?>"><?=$r[zcnum]?></span>]&nbsp; 
<a href="JavaScript:makeRequest('http://www.szhgh.com/e/pl/doaction.php?enews=DoForPl&plid=<?=$r[plid]?>&classid=<?=$classid?>&id=<?=$id?>&dopl=0&doajax=1&ajaxarea=fdpldiv<?=$r[plid]?>','EchoReturnedText','GET','');">反对</a>[<span id="fdpldiv<?=$r[plid]?>"><?=$r[fdnum]?></span>]
</span> </div>
          </div>
        </li>
        
	<?
	$code=ob_get_contents();
	ob_clean();
	return $code;
}
?>