<?php
//--------------- 修改信息函数 ---------------


/**
 * 修改安全信息,包括用户名,密码,邮箱(LGM修改[2014年3月19日19:16])
 * @global resource $empire
 * @global string $dbtbpre
 * @global array $public_r
 * @global array $ecms_config
 * @param array $add
 */
function EditSafeInfo($add){
	global $empire,$dbtbpre,$public_r,$ecms_config;
	$user_r=islogin();//是否登陆
	$userid=$user_r['userid'];
	$username=$user_r['username'];
        $newusername = $add['username'];
	$rnd=$user_r['rnd'];
        $UserInitial = getcvar('UserInitial', 0, TRUE);
        if(empty($UserInitial)) $UserInitial = FALSE;
        else {
             $kp2=explode('@', $UserInitial);
             $UserInitial = FALSE;
             if((int)$userid == (int)$kp2[0])
             {
                $UserInitial = TRUE; 
                //判断用户名是否存在 :
                if(!empty($newusername) && $newusername!=$username)
                {
                    $sqlcmd= "select count(1) as total from ".eReturnMemberTable()." where ".egetmf('username')."='".mysql_real_escape_string($newusername)."'";                    
                   $num=$empire->gettotal($sqlcmd);
                   if($num>0) printerror('ReUsername',"history.go(-1)",1);
                }
             }
        }
	//邮箱
	$email=trim($add['email']);
	if(!$email||!chemail($email))
	{
		printerror("EmailFail","history.go(-1)",1);
	}
	$email=RepPostStr($email);
        if(!$UserInitial)
        {
            //验证原密码
            $oldpassword=RepPostVar($add['oldpassword']);
            if(!$oldpassword)
            {
                    printerror('FailOldPassword','',1);
            }
            $add['password']=RepPostVar($add['password']);
            $num=0;
            $ur=$empire->fetch1("select ".eReturnSelectMemberF('userid,password,salt')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$userid'");
            if(empty($ur['userid']))
            {
                    printerror('FailOldPassword','',1);
            }
            if(!eDoCkMemberPw($oldpassword,$ur['password'],$ur['salt']))
            {
                    printerror('FailOldPassword','',1);
            }
        }
	//------------------- Ucenter --------------------------
        if(defined('UC_ENABLE') && UC_ENABLE){
	$truepassword=$add['password'];
	$trueoldpassword=$oldpassword;
        }
	//邮箱是否检查与旧的邮箱地址(LGM修改[2014年3月20日20:05])
	$pr2=$empire->query("select regemailonly as d from {$dbtbpre}enewspublic  limit 1 UNION ALL select ".egetmf('email')." as d  from ".eReturnMemberTable()." where ".egetmf('userid')."='$userid'");
        $regemailonly = FALSE;
        $oldmail = NULL;
        if(!empty($pr2))
        {
           $rdd = $empire->fetch($pr2);
           $regemailonly = (int)$rdd['d'];
           $rdd = $empire->fetch($pr2);
           $oldmail = $rdd['d'];
        }
	if($regemailonly && !empty($email) && ($email <> $oldmail))
	{
		$num=$empire->gettotal("select count(*) as total from ".eReturnMemberTable()." where ".egetmf('email')."='$email' and ".egetmf('userid')."<>'$userid' limit 1");
		if($num)
		{
			printerror("ReEmailFail","history.go(-1)",1);
		}
	}
	//密码
	$a='';
	$salt='';
	$truepassword='';
	if($add['password'])
	{
		if($add['password']!==$add['repassword'])
		{
			printerror('NotRepassword','history.go(-1)',1);
		}
		$salt=eReturnMemberSalt();
		$password=eDoMemberPw($add['password'],$salt);
		$a=",".egetmf('password')."='$password',".egetmf('salt')."='$salt'";
		$truepassword=$add['password'];
	}
        //用户名
        if($UserInitial && !empty($add['username']) && $username!= $newusername)
        {
            $a .= ",".egetmf('username')."='".mysql_real_escape_string($newusername)."'";
        }
        //邮箱
        $sendactmail = FALSE;
        if(!empty($email) && ($email <> $oldmail))
        {
            $a .= ",".egetmf('email')."='".$email."'";
            if($public_r['regacttype']==1)//改变了邮箱,需要重新验证
            {
               $a .= ",".egetmf('checked')."=0";
               $sendactmail = TRUE;
            }
            
        }
        if(empty($a))
            printerror('您没有修改任何信息','',1);
        else $a = substr($a,1);//去掉前面的,
       
	//--------------------------- Ucenter ---------------------------
        if(defined('UC_ENABLE') && UC_ENABLE){
          $ucresult =   NULL;
          if(!$UserInitial) $ucresult = uc_user_edit($username,$trueoldpassword,$truepassword,$email,FALSE);
          else $ucresult = uc_user_modify($userid,$newusername,$trueoldpassword,$truepassword,$email,TRUE);              
	if($ucresult == -1)
	{
		printerror('旧密码不正确','',1,0,1);
	} 
	elseif($ucresult == -4)
	{
		printerror('Email 格式有误','',1,0,1);
	}
	elseif($ucresult == -5)
	{
		printerror('Email 不允许注册','',1,0,1);
	}
	elseif($ucresult == -6)
	{
		printerror('该 Email 已经被注册','',1,0,1);
	}
	@mysql_select_db($ecms_config['db']['dbname']);
        }
    $sqlcmd= "update ".eReturnMemberTable()." set {$a} where ".egetmf('userid')."='$userid'";
    $sql=$empire->query($sqlcmd);
    if ($sql)
    {
        delcookie('UserInitial', 0); //删除Cookie
        //修改短消息的用户名:
        if ($username != $newusername && !empty($newusername))
        {
            $sqlcmd = "update {$dbtbpre}enewsqmsg set to_username = '" . mysql_real_escape_string($newusername) . "' where  to_username='" . mysql_real_escape_string($username) . "'";
            $sql2 = $empire->query($sqlcmd);
            esetcookie('mlusername', $newusername,0,0);//修改Cookie里的旧用户名
            $username = $newusername;            
        }
        //删除改密码的消息:
        $maxids = $empire->fetch1("select min(mid) from {$dbtbpre}enewsqmsg where to_username='" . mysql_real_escape_string($username) . "' AND issys=1 AND isadmin=1", MYSQL_NUM);
        if (!empty($maxids))
        {
            $mid = (int) $maxids[0];
            include_once('msgfun.php');
            DelMsgPro(intval($mid)); //删除消息
        }
        //易通行系统
        DoEpassport('editpassword', $userid, $username, $truepassword, $salt, $email, $user_r['groupid'], '');
        if($sendactmail)//发送激活邮件:
        {
            EmptyEcmsCookie();
            include_once('member_actfun.php');
            SendActUserEmail($userid,$username,$email);
        }        
        printerror("EditInfoSuccess", "../member/cp/", 1);
    } else
    {
        printerror("DbError", "history.go(-1)", 1);
    }
}

//信息修改
function EditInfo($post){
	global $empire,$dbtbpre,$public_r;
	$user_r=islogin();//是否登陆
	$userid=$user_r[userid];
	$username=$user_r[username];
	$dousername=$username;
	$rnd=$user_r[rnd];
	$groupid=$user_r[groupid];
	if(!$userid||!$username)
	{
		printerror("NotEmpty","history.go(-1)",1);
	}
	//验证附加表必填项
	$addr=$empire->fetch1("select * from {$dbtbpre}enewsmemberadd where userid='$userid'");
	$user_r=$empire->fetch1("select ".eReturnSelectMemberF('groupid')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$userid'");
	$fid=GetMemberFormId($user_r['groupid']);
	if(empty($addr[userid]))
	{
		$mr['add_filepass']=$userid;
		$member_r=ReturnDoMemberF($fid,$post,$mr,0,$dousername);
	}
	else
	{
		$addr['add_filepass']=$userid;
		$member_r=ReturnDoMemberF($fid,$post,$addr,1,$dousername);
	}
	//附加表
	if(empty($addr[userid]))
	{
		//IP
		$regip=egetip();
		$lasttime=time();
		$sql=$empire->query("insert into {$dbtbpre}enewsmemberadd(userid,regip,lasttime,lastip,loginnum".$member_r[0].") values('$userid','$regip','$lasttime','$regip',1".$member_r[1].");");
    }
	else
	{
		$sql=$empire->query("update {$dbtbpre}enewsmemberadd set userid='$userid'".$member_r[0]." where userid='$userid'");
    }
	//更新附件
	UpdateTheFileEditOther(6,$userid,'member');
    if($sql)
    {
		printerror("EditInfoSuccess","../member/EditInfo/",1);
	}
    else
    {
		printerror("DbError","history.go(-1)",1);
	}
}
?>