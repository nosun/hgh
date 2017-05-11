<?php
//--------------- 登录函数 ---------------

//登录
function qlogin($add){
	global $empire, $dbtbpre, $public_r, $ecms_config;
    if ($ecms_config['member']['loginurl'])
    {
        if (!empty($_SERVER['QUERY_STRING']))
            Header("Location:" . $ecms_config['member']['loginurl'] .(strpos($ecms_config['member']['loginurl'],'?')===FALSE?'?':'&') . $_SERVER['QUERY_STRING']);
        else
            Header("Location:" . $ecms_config['member']['loginurl']);
        exit();
    }
    $dopr=1;
	if($_POST['prtype'])
	{
		$dopr=9;
	}
	$username=trim($add['username']);
	$password=trim($add['password']);
	if(!$username||!$password)
	{
		printerror("EmptyLogin","history.go(-1)",$dopr);
	}
	$tobind=(int)$add['tobind'];
	//验证码
	$keyvname='checkloginkey';
	if($public_r['loginkey_ok'])
	{
		ecmsCheckShowKey($keyvname,$add['key'],$dopr);
	}
	$username=RepPostVar($username);
	$password=RepPostVar($password);
	

	//--------------------------- Ucenter ---------------------------
         if(defined('UC_ENABLE') && UC_ENABLE){   
         $truepassword=$password;
	list($uid, $username, $ucpassword, $email) = uc_user_login($username,$truepassword);
	if($uid <= 0)
	{
		if($uid == -1)
		{
			printerror('用户不存在,或者被删除','javascript:history.go(-1)',1,0,1);
		}
		elseif($uid == -2)
		{
			printerror('密码错','javascript:history.go(-1)',1,0,1);
		}
		else
		{
			printerror('未定义','javascript:history.go(-1)',1,0,1);
		}
	}
	@mysql_select_db($ecms_config['db']['dbname']);

	$uid=(int)$uid;
	//取得用户资料
	$r=$empire->fetch1("select ".eReturnSelectMemberF('*')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$uid' limit 1");
	$doactive=0;
	if(!$r['userid'])
	{
		$salt=eReturnMemberSalt();
		$password=eDoMemberPw($password,$salt);
		$rnd=make_password(20);
		$lasttime=time();
		$registertime=eReturnAddMemberRegtime();
		$user_groupid=eReturnMemberDefGroupid();
		$checked=1;
		$userkey=eReturnMemberUserKey();
		$regip=egetip();
		$isql=$empire->query("insert into ".eReturnMemberTable()."(".eReturnInsertMemberF('userid,username,password,rnd,email,registertime,groupid,userfen,userdate,money,zgroupid,havemsg,checked,salt,userkey').") values('$uid','$username','$password','$rnd','$email','$registertime','$user_groupid','$public_r[reggetfen]','0','0','0','0','$checked','$salt','$userkey');");
		//附加表
		$addr=$empire->fetch1("select * from {$dbtbpre}enewsmemberadd where userid='$uid'");
		if(!$addr[userid])
		{
			$sql1=$empire->query("insert into {$dbtbpre}enewsmemberadd(userid,regip,lasttime,lastip,loginnum) values('$uid','$regip','$lasttime','$regip','0');");
		}
		$doactive=1;
		$r=$empire->fetch1("select ".eReturnSelectMemberF('*')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$uid' limit 1");
	}
	$set4=delcookie('mldoactive',0);//LGM修改,统一删除方式
        }
	else{
            $num=0;
            $r=$empire->fetch1("select ".eReturnSelectMemberF('*')." from ".eReturnMemberTable()." where ".egetmf('username')."='$username' limit 1");
            if(!$r['userid'])
            {
                    printerror("FailPassword","history.go(-1)",$dopr);
            }
            if(!eDoCkMemberPw($password,$r['password'],$r['salt']))
            {
                    printerror("FailPassword","history.go(-1)",$dopr);
            }
        }

	if($r['checked']==0)
	{
		if($public_r['regacttype']==1)
		{
			printerror('NotCheckedUser','../member/register/regsend.php',1);
		}
		else
		{
			printerror('NotCheckedUser','',1);
		}
	}
	//绑定帐号
	if($tobind)
	{
	    //绑定新的外部应用ID [2014年1月21日9:20 LGM修改]
            BindMemberFromEI($r['userid']);
	}
	$rnd=make_password(20);//取得随机密码
	//默认会员组
	if(empty($r['groupid']))
	{
		$r['groupid']=eReturnMemberDefGroupid();
	}
	$r['groupid']=(int)$r['groupid'];
	$lasttime=time();
	//IP
	$lastip=egetip();
	$usql=$empire->query("update ".eReturnMemberTable()." set ".egetmf('rnd')."='$rnd',".egetmf('groupid')."='$r[groupid]' where ".egetmf('userid')."='$r[userid]'");
	$empire->query("update {$dbtbpre}enewsmemberadd set lasttime='$lasttime',lastip='$lastip',loginnum=loginnum+1 where userid='$r[userid]'");
	//设置cookie
	$lifetime=(int)$add['lifetime'];
	$logincookie=0;
	if($lifetime)
	{
		$logincookie=time()+$lifetime;
	}
        esetcookie('lastlogin',time().'@'.$r['userid'],time()+7776000,0,TRUE);//网站登录的最后时间,以标识已经注册了账户.保存三个月(LGM添加[2014年3月18日9:31])
	$set1=esetcookie("mlusername",$username,$logincookie);
	$set2=esetcookie("mluserid",$r['userid'],$logincookie,0,TRUE);//LGM修改,把用户ID加密[2014年3月16日21:43]
	$set3=esetcookie("mlgroupid",$r['groupid'],$logincookie);
	$set4=esetcookie("mlrnd",$rnd,$logincookie);
	//验证符
	qGetLoginAuthstr($r['userid'],$username,$rnd,$r['groupid'],$logincookie);
	//登录附加cookie
	AddLoginCookie($r,0);
	$location="../member/cp/";
	$returnurl=  GetValueFromCookie('returnurl',$logincookie,TRUE);//LGM修改,从COOKIE取出returnurl并且删除[2014年3月13日22:30]
	if(!empty($returnurl))
	{   
            $returnurl = '?returnurl='.urlencode($returnurl);
	}
        else
            $returnurl = '';
	if(strstr($_SERVER['HTTP_REFERER'],"e/member/iframe"))
	{
		$location="../member/iframe/".$returnurl;
	}
	if(strstr($location,"enews=exit")||strstr($location,"e/member/register")||strstr($_SERVER['HTTP_REFERER'],"e/member/register"))
	{
		$location="../member/cp/".$returnurl;
		$_POST['ecmsfrom']='';
	}
        
	ecmsEmptyShowKey($keyvname);//清空验证码
	if($set1&&$set2)
	{
		//易通行系统
		DoEpassport('login',$r['userid'],$username,$password,$r['salt'],$r['email'],$r['groupid'],$r['registertime']);

		//--------------------------- Ucenter ---------------------------
		if(defined('UC_ENABLE') && UC_ENABLE) echo uc_user_synlogin($uid);

		$location=DoingReturnUrl($location,$_POST['ecmsfrom']);
		printerror("LoginSuccess",$location,$dopr);
    }
	else
	{
		printerror("NotCookie","history.go(-1)",$dopr);
	}
}

//退出登陆
function qloginout($userid,$username,$rnd){
	global $empire,$public_r,$ecms_config;
	include_once(ECMS_PATH."e/class/userfun.php");	//LGM Add 2014年2月13日17:28
	//是否登陆
	$user_r=islogin();
	if($ecms_config['member']['quiturl'])
	{
		Header("Location:".$ecms_config['member']['quiturl']);
		exit();
	}
	EmptyEcmsCookie();
	if(defined('UC_ENABLE') && UC_ENABLE) delcookie('mldoactive',0);//Ucenter LGM修改,统一删除方式
	//清除session变量： LGM ADD 2014年2月12日15:33
	$isenableses=($_SESSION==NULL);
	if(!$isenableses)session_start();
	unset($_SESSION['mlbindeis']);
        unset($_SESSION['e_token']);
	if(!$isenableses)session_destroy();
	$dopr=1;
	if($_GET['prtype'])
	{
		$dopr=9;
	}
	$gotourl="../../";
	if(strstr($_SERVER['HTTP_REFERER'],"e/member/iframe"))
	{
		$gotourl=$public_r['newsurl']."e/member/iframe/";
	}
	//易通行系统
	DoEpassport('logout',$user_r['userid'],$user_r['username'],'','','','','');
	$gotourl=DoingReturnUrl($gotourl,$_GET['ecmsfrom']);
	//--------------------------- Ucenter ---------------------------
	if(defined('UC_ENABLE') && UC_ENABLE) echo uc_user_synlogout();
        if(!empty($gotourl))header("Location:$gotourl");//直接转跳
	printerror("ExitSuccess",$gotourl,$dopr);
}
?>