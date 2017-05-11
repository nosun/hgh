<?php
//--------------- 注册函数 ---------------

//验证会员组是否可注册
function CheckMemberGroupCanReg($groupid){
	global $empire,$dbtbpre;
	$groupid=(int)$groupid;
	$r=$empire->fetch1("select groupid from {$dbtbpre}enewsmembergroup where groupid='$groupid' and canreg=1");
	if(empty($r['groupid']))
	{
		printerror('ErrorUrl','javascript:history.go(-1)',1);
	}
}

//验证注册时间
function eCheckIpRegTime($ip,$time){
	global $empire,$dbtbpre;
	if(empty($time))
	{
		return '';
	}
	$uaddr=$empire->fetch1("select userid from {$dbtbpre}enewsmemberadd where regip='$ip' order by userid desc limit 1");
	if(empty($uaddr['userid']))
	{
		return '';
	}
	$ur=$empire->fetch1("select ".eReturnSelectMemberF('userid,registertime')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$uaddr[userid]' limit 1");
	if(empty($ur['userid']))
	{
		return '';
	}
	$registertime=eReturnMemberIntRegtime($ur['registertime']);
	if(time()-$registertime<=$time*3600)
	{
		printerror('RegisterReIpError','',1);
	}
}

//用户注册
function register($add){

	global $empire,$dbtbpre,$public_r,$ecms_config;
	//关闭注册
	if($public_r['register_ok'] && !$add['JumpChk'])//LGM修改,如果绕过验证则不执行[2014年3月18日17:29]
	{
		printerror('CloseRegister','',1);
	}
	//验证时间段允许操作

	if(!$add['JumpChk'])eCheckTimeCloseDo('reg'); //LGM修改,如果绕过验证则不执行[2014年3月18日17:29]
	//验证IP
	if(!$add['JumpChk'])eCheckAccessDoIp('register');//LGM修改,如果绕过验证则不执行[2014年3月18日17:29]
	if(!empty($ecms_config['member']['registerurl']))
	{
            if(!empty($_SERVER['QUERY_STRING']))
		Header("Location:".$ecms_config['member']['registerurl'].'?'.$_SERVER['QUERY_STRING']);
            else
               Header("Location:".$ecms_config['member']['registerurl']); 
		exit();
        }

	//已经登陆不能注册
	if(getcvar('mluserid',0,TRUE))//LGM修改,把用户ID加密[2014年3月16日21:43]
	{ 
		printerror('LoginToRegister','',1);
	}
	CheckCanPostUrl();//验证来源
            $aa = $add['ms'];
        if(!isset($aa)){

            return;
        }

	$username=trim($add['username']);
	$password=trim($add['password']);
	$password=RepPostVar($password);
	$email=RepPostStr($add['email']);
        if($add['randompwd'])
        {
            if(empty($password))
            {
                $password = md5(microtime());
                if(strlen($password)>10) $password = substr($password,0,10);
            }
        }

	if(defined('UC_ENABLE') && UC_ENABLE) $truepassword=$password;//Ucenter

        $tobind = $_REQUEST['tobind']; 
        if(isset($add['tobind'])) $tobind=(bool)((int)$add['tobind']);
        if(empty($tobind))$tobind=FALSE; //LGM 添加,判断是否为第三方交互绑定时,[2014年3月13日19:33]
	if($tobind && empty($email))//如果用第三方账户登录,并且没有邮箱[2014年1月21日17:05 LGM修改]
	{
		$apptype=$_SESSION['apptype'];
		if (!empty($apptype)) {
			include_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'memberconnect'.DIRECTORY_SEPARATOR.$apptype.DIRECTORY_SEPARATOR.'class.php');
                         $r = EIService::GetENV($_SESSION['cappid']);
                         $app_id = $r['appkey'];// 应用的APPID
                         $app_secret = $r['appsecret'];// 应用的APPKEY
			if ($r) {
				$pi= array();
				$tv= EIService::GetTokenFromSession($_SESSION['cappid']);
				if($tv){
					$pi['access_token']=$tv['access_token'];
				}
				$pi["appkey"]=$app_id;
				$pi["appsecret"]=$app_secret;
				$actioner= EIService::GetAction($_SESSION['cappid'], $pi);
				if($actioner)
				{
					$ep= array("access_token"=>$tv['access_token']);
                                        try{
					$evv=$actioner->GetUserEmail($ep);
                                        }
                                        catch(EiException $de)
                                         {
                                            ;}
					if(!isset($evv['error_code']) && !empty($evv['email']))
					{
						$email=$evv['email'];
					}
				}
			}
		}
	}
	if(!$username||(!$add['JumpChk'] && (!$password||!$email)))
	{
		printerror("EmptyMember","history.go(-1)",1);
	}        
        if(empty($email) && 'email'==$add['notifychgpwd']) $add['notifychgpwd'] = 'sitemsg'; //如果不存在邮箱,又希望发消息的,改为站内消息
	//验证码
	$keyvname='checkregkey';        
	if($public_r['regkey_ok'] && !$tobind && !((bool)$add['randompwd']))//非绑定,非自动注册
	{
		ecmsCheckShowKey($keyvname,$add['key'],1);
	}
	$user_groupid=eReturnMemberDefGroupid();
	$groupid=(int)$add['groupid'];
	$groupid=empty($groupid)?$user_groupid:$groupid;
	CheckMemberGroupCanReg($groupid);
	//IP
	$regip=egetip();
        $pr=$empire->fetch1("select min_userlen,max_userlen,min_passlen,max_passlen,regretime,regclosewords,regemailonly from {$dbtbpre}enewspublic limit 1",MYSQL_ASSOC);
        if(!$add['JumpChk'])//如果无跳过验证
        {
            //用户字数            
            $userlen=strlen($username);
            if($userlen<$pr['min_userlen']||$userlen>$pr['max_userlen'])
            {
                $pa = array();
                $pa['min_userlen'] = (int)$pr['min_userlen'];
                $pa['max_userlen'] = (int)$pr['max_userlen'];
                printerror('FaiUserlen','javascript:history.go(-1)',1,$pa);//LGM修改,给定详细参数[2014年3月14日12:39]
            }
            //密码字数
            $passlen=strlen($password);
            if($passlen<$pr[min_passlen]||$passlen>$pr[max_passlen])
            {
                $pa = array();
                $pa['min_passlen'] = (int)$pr['min_passlen'];
                $pa['max_passlen'] = (int)$pr['max_passlen'];
                printerror('FailPasslen','javascript:history.go(-1)',1,$pa);//LGM修改,给定详细参数[2014年3月14日12:39]
            }
            if($add['repassword']!==$password)
            {
                    printerror('NotRepassword','javascript:history.go(-1)',1);
            }
            if(!chemail($email))
            {
                    printerror('EmailFail','javascript:history.go(-1)',1);
            }
            //同一IP注册
            eCheckIpRegTime($regip,$pr['regretime']);  
        }
	if(strstr($username,'|')||strstr($username,'*'))
	{
		printerror('NotSpeWord','javascript:history.go(-1)',1);
	}	
	//保留用户
	toCheckCloseWord($username,$pr['regclosewords'],'RegHaveCloseword');
        $sqluname = mysql_real_escape_string($username);
	//重复用户
        $num=$empire->gettotal("select count(*) as total from ".eReturnMemberTable()." where ".egetmf('username')."='{$sqluname}' limit 1");
	if($num)
	{
		printerror('ReUsername','javascript:history.go(-1)',1);
	}
	//重复邮箱
	if($pr['regemailonly'] || ($tobind && !empty($email)))
	{
		$edata=$empire->fetch1('select '.eReturnSelectMemberF('userid,username').' from '.eReturnMemberTable()." where ".egetmf('email')."='$email' limit 1",MYSQL_NUM);
		if(!empty($edata[0]))
		{
                    if(!$tobind)
			printerror('ReEmailFail','javascript:history.go(-1)',1);
                    elseif($email)
                    {
                        //绑定到对应的旧用户 
                        $loginurl=GetEPath().'member/login/?tobind=1&defname='.urlencode($edata[1]);
                        Header('HTTP/1.1 303 See Other'); 
                        Header("Location: $loginurl"); 
                        exit();
                    }
		}
	}
	//注册时间
	$lasttime=time();
	$registertime=eReturnAddMemberRegtime();
	$rnd=make_password(20);//产生随机密码
	$userkey=eReturnMemberUserKey();
	//密码
	$truepassword=$password;
	$salt=eReturnMemberSalt();
	$password=eDoMemberPw($password,$salt);
	//审核
	$checked=ReturnGroupChecked($groupid);
	if($checked&&$public_r['regacttype']==1)
	{
		$checked=0;
	}
        if($checked==0 && $tobind && empty($email))//如果是绑定,而且无邮件地址,则设置为不需要审核,否则无数收到短消息
        {
            $checked = 1;
        }
	//验证附加表必填项
	$mr['add_filepass']=ReturnTranFilepass();
	$fid=GetMemberFormId($groupid);
	$member_r=ReturnDoMemberF($fid,$add,$mr,0,$username);
        $userid='';
        $sql=NULL;
	//--------------------------- Ucenter ---------------------------        
        if(defined('UC_ENABLE') && UC_ENABLE){
	$uid=uc_user_register($username,$truepassword,$email);
	if($uid <= 0)
	{
		if($uid == -1)
		{
			printerror('用户名不合法','javascript:history.go(-1)',1,0,1);
		} 
		elseif($uid == -2)
		{
			printerror('包含要允许注册的词语','javascript:history.go(-1)',1,0,1);
		} 
		elseif($uid == -3)
		{
			printerror('用户名已经存在','javascript:history.go(-1)',1,0,1);
		}
		elseif($uid == -4)
		{
			printerror('Email 格式有误','javascript:history.go(-1)',1,0,1);
		}
		elseif($uid == -5)
		{
			printerror('Email 不允许注册','javascript:history.go(-1)',1,0,1);
		}
		elseif($uid == -6)
		{
			printerror('该 Email 已经被注册','javascript:history.go(-1)',1,0,1);
		} 
		else
		{
			printerror('未定义','javascript:history.go(-1)',1,0,1);
		}
	}
	@mysql_select_db($ecms_config['db']['dbname']);
        $userid=$uid;
        $sql=$empire->query("insert into ".eReturnMemberTable()."(".eReturnInsertMemberF('userid,username,password,rnd,email,registertime,groupid,userfen,userdate,money,zgroupid,havemsg,checked,salt,userkey').") values('$userid','{$sqluname}','$password','$rnd','$email','$registertime','$groupid','$public_r[reggetfen]','0','0','0','0','$checked','$salt','$userkey');");
        }
        else {
            $sql=$empire->query("insert into ".eReturnMemberTable()."(".eReturnInsertMemberF('username,password,rnd,email,registertime,groupid,userfen,userdate,money,zgroupid,havemsg,checked,salt,userkey').") values('{$sqluname}','$password','$rnd','$email','$registertime','$groupid','$public_r[reggetfen]','0','0','0','0','$checked','$salt','$userkey');");
            $userid=$empire->lastid();//取得userid LGM修改,当没有接入Ucenter时,应该取得新插入的uid[2014年3月14日9:48]                
        }        
	//附加表
	$addr=$empire->fetch1("select * from {$dbtbpre}enewsmemberadd where userid='$userid'");
	if(!$addr['userid'])
	{
		$spacestyleid=ReturnGroupSpaceStyleid($groupid);
		$sql1=$empire->query("insert into {$dbtbpre}enewsmemberadd(userid,spacestyleid,regip,lasttime,lastip,loginnum".$member_r[0].") values('$userid','$spacestyleid','$regip','$lasttime','$regip','1'".$member_r[1].");");
	}
	//更新附件
	UpdateTheFileOther(6,$userid,$mr['add_filepass'],'member');
	ecmsEmptyShowKey($keyvname);//清空验证码
	//绑定帐号
	if($tobind)
	{
		//绑定新的外部应用ID [2014年1月21日9:20 LGM修改]
                include_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'memberconnect'.DIRECTORY_SEPARATOR.'memberconnectfun.php');                
                BindMemberFromEI($userid);
	}
	if($sql)
	{
                if(!empty($add['notifychgpwd']))
                {
                    //发送密码修改消息:
                    switch (strtolower($add['notifychgpwd']))
                    {
                        case 'sitemsg'://站内消息
                            include_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'com_functions.php');
                            $msgbody=md5(strrev($password));
                            $turl = eReturnDomainSiteUrl().'e/member/EditInfo/EditSafeInfo.php?userid='.$userid.'&oldpwd='. urlencode($msgbody);
                            $msgurl ='<div class="msgcenter"><a href="'.$turl .'" target="_blank" class="attention">设置密码及用户信息</a><br/>链接是:'.$turl.'</div>';
                            if(empty($add['chgpwdtitle']))  $add['chgpwdtitle'] ='请您修改密码';
                            if(empty($add['chgpwdinfo'])) 
                            {
                              $add['chgpwdinfo'] =<<<EOF_E
点击或者将如下链接复制到浏览器地址栏即可快速修改密码:  
[!--url--]
---------------------              
  本链接不能重复打开!如有疑惑或无法解决的问题,请与我们联系.
EOF_E;
                            }
                            $smp = array('username'=>$username,'userid'=>$userid,'password'=>$add['password'],'groupid'=>$groupid,'email'=>$add['email'],'url'=>$msgurl,'apptype'=>$_SESSION['apptype']);
                            SendSiteMsg($add['chgpwdtitle'], $add['chgpwdinfo'], NULL,$userid, $username, FALSE,TRUE, $smp); //发送系统消息
                            break;
                        case 'email'://邮件
                            include_once('class/member_actfun.php');
                            $elp = array('username'=>$username,'email'=>$add['email']);
			    SendGetPasswordEmail($elp,'['.$public_r['sitename'].']创建了新账号,请及时修改您的密码',NULL,TRUE);
                            break;  
                        case 'sms'://短信,待完善,LGM注[2014年3月18日19:06]
                            break;                          
                        default:
                            if($public_r['regacttype']==1)
                            {
                                include_once('class/member_actfun.php');
                                $elp = array('username'=>$username,'email'=>$add['email']);
                                SendGetPasswordEmail($elp,'['.$public_r['sitename'].']创建了新账号,请及时修改您的密码',NULL,TRUE);   
                            }
                            break;                  
                    }
                }
                //邮箱激活
		if($checked==0 && $public_r['regacttype']==1)
		{
			include_once('class/member_actfun.php');
			SendActUserEmail($userid,$username,$email);
		}
		//审核
		if($checked==0)
		{
			$location=DoingReturnUrl("../../",$_POST['ecmsfrom']);
			printerror("RegisterSuccessCheck",$location,1);
		}
		$logincookie=0;
		if($ecms_config['member']['regcookietime'])
		{
			$logincookie=time()+$ecms_config['member']['regcookietime'];
		}
		$r=$empire->fetch1("select ".eReturnSelectMemberF('*')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$userid' limit 1");
                esetcookie('lastlogin',time().'@'.$userid,time()+7776000,0,TRUE);//网站登录的最后时间,以标识已经注册了账户.保存三个月(LGM添加[2014年3月18日9:31])
		$set1=esetcookie("mlusername",$username,$logincookie);
		$set2=esetcookie("mluserid",$userid,$logincookie,0,TRUE);//LGM修改,把用户ID加密[2014年3月16日21:43]
		$set3=esetcookie("mlgroupid",$groupid,$logincookie);
		$set4=esetcookie("mlrnd",$rnd,$logincookie);
		//验证符
		qGetLoginAuthstr($userid,$username,$rnd,$groupid,$logincookie);
		//登录附加cookie
		AddLoginCookie($r,0);
		$location="../member/cp/";
		$returnurl=GetValueFromCookie('returnurl',$logincookie,TRUE);//LGM修改,从COOKIE中读出转跳地址并且删除[2014年3月13日22:00]
		if($returnurl&&!strstr($returnurl,"e/member/iframe")&&!strstr($returnurl,"e/member/register")&&!strstr($returnurl,"enews=exit"))
		{
			$location=$returnurl;
		}
		//易通行系统
		DoEpassport('reg',$userid,$username,$truepassword,$salt,$email,$groupid,$registertime);
		$location=DoingReturnUrl($location,$_POST['ecmsfrom']);
		printerror("RegisterSuccess",$location,1);
	}
	else
	{printerror("DbError","history.go(-1)",1);}
}
?>
