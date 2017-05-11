<?php
//--------------- 站内消息函数 ---------------

//发送短消息
function AddMsg($add){
	global $empire,$level_r,$dbtbpre;
	$user=islogin();
	$title=RepPostStr(trim($add['title']));
	$to_username=RepPostVar(trim($add['to_username']));
	$msgtext=RepPostStr($add['msgtext']);
	if(empty($title)||!trim($msgtext)||empty($to_username))
	{printerror("EmptyMsg","",1);}
	if($user['username']==$to_username)
	{printerror("MsgToself","",1);}
	//字数
	$len=strlen($msgtext);
	if($len>$level_r[$user[groupid]][msglen])
	{
		printerror("MoreMsglen","",1);
	}
	//接收方是否存在
	$r=$empire->fetch1("select ".eReturnSelectMemberF('userid,groupid')." from ".eReturnMemberTable()." where ".egetmf('username')."='$to_username' limit 1");
	if(!$r['userid'])
	{
		printerror("MsgNotToUsername","",1);
    }
	//对方短消息是否满
	$mnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsqmsg where to_username='$to_username'");
	if($mnum+1>$level_r[$r[groupid]][msgnum])
	{
		printerror("UserMoreMsgnum","",1);
	}
	$msgtime=date("Y-m-d H:i:s");
	$sql=$empire->query("insert into {$dbtbpre}enewsqmsg(title,msgtext,haveread,msgtime,to_username,from_userid,from_username,isadmin,issys) values('".addslashes($title)."','".addslashes($msgtext)."',0,'$msgtime','$to_username','$user[userid]','$user[username]',0,0);");
	$newhavemsg=eReturnSetHavemsg($user['havemsg'],0);
	$usql=$empire->query("update ".eReturnMemberTable()." set ".egetmf('havemsg')."='$newhavemsg' where ".egetmf('username')."='$to_username' limit 1");
	if($sql)
	{
		printerror("AddMsgSuccess","../member/msg/",1);
	}
	else
	{printerror("DbError","",1);}
}

//删除短消息
function DelMsg($mid){
	global $empire,$dbtbpre;
	$user=islogin();
	$mid=(int)$mid;
	if(!$mid)
	{printerror("EmptyDelMsg","",1);}
	$sql=$empire->query("delete from {$dbtbpre}enewsqmsg where mid='$mid' and to_username='$user[username]'");
	if($sql)
	{
		$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsqmsg where to_username='$user[username]' and haveread=0 limit 1");
		if(!$num)
		{
			$newhavemsg=eReturnSetHavemsg($user['havemsg'],0);
			$newhavemsg=$newhavemsg==2||$newhavemsg==3?2:0;
			$empire->query("update ".eReturnMemberTable()." set ".egetmf('havemsg')."='$newhavemsg' where ".egetmf('userid')."='$user[userid]'");
		}
		printerror("DelMsgSuccess","../member/msg/",1);
    }
	else
	{printerror("DbError","",1);}
}
/**
 * 删除短消息.不需要登录,不弹出对话框(LGM增加[2014年3月19日22:04])
 * @global resource $empire
 * @global string $dbtbpre
 * @param int $mid
 * @return boolean
 */
function DelMsgPro($mid)
{
    global $empire, $dbtbpre;
    $mid = (int) $mid;
    if (!$mid)
        return FALSE;
    $una = $empire->fetch1("select to_username from {$dbtbpre}enewsqmsg where mid = $mid", MYSQL_NUM);
    $sql = $empire->query("delete from {$dbtbpre}enewsqmsg where mid='$mid'");
    if ($sql)
    {
        if (!empty($una))
        {
            $usern = mysql_real_escape_string($una[0]);
            $num = $empire->gettotal("select count(1) as total from {$dbtbpre}enewsqmsg where to_username='{$usern}' and haveread=0");
            if (!$num)
            {
                $uinfo = $empire->fetch1("select " . egetmf('userid') . "," . egetmf('havemsg') . " from " . eReturnMemberTable() . " where " . egetmf('username') . "='{$usern}' limit 1", MYSQL_NUM);
                if (!empty($uinfo))
                {
                    $newhavemsg = eReturnSetHavemsg($uinfo[1], 0);
                    $newhavemsg = $newhavemsg == 2 || $newhavemsg == 3 ? 2 : 0;
                    $empire->query("update " . eReturnMemberTable() . " set " . egetmf('havemsg') . "='$newhavemsg' where " . egetmf('userid') . "='{$uinfo[0]}'");
                }
            }
        }

        return TRUE;
    }
    return FALSE;
}

//批量删除短消息
function DelMsg_all($mid){
	global $empire,$dbtbpre;
	$user=islogin();
	$count=count($mid);
	if(!$count)
	{printerror("EmptyDelMsg","",1);}
	for($i=0;$i<$count;$i++)
	{
		$add.="mid='".intval($mid[$i])."' or ";
    }
	$add=substr($add,0,strlen($add)-4);
	$sql=$empire->query("delete from {$dbtbpre}enewsqmsg where (".$add.") and to_username='$user[username]'");
    if($sql)
	{
		$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsqmsg where to_username='$user[username]' and haveread=0 limit 1");
		if(!$num)
		{
			$newhavemsg=eReturnSetHavemsg($user['havemsg'],0);
			$newhavemsg=$newhavemsg==2||$newhavemsg==3?2:0;
			$empire->query("update ".eReturnMemberTable()." set ".egetmf('havemsg')."='$newhavemsg' where ".egetmf('userid')."='$user[userid]'");
		}
		printerror("DelMsgSuccess","../member/msg/",1);
    }
    else
	{printerror("DbError","",1);}
}
?>