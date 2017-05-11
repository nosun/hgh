<?php
/**
 * 验证登录方式,是否存在指定的应用类型
 * @global mysqlquery $empire
 * @global string $dbtbpre
 * @param string $apptype
 * @return array
 */
function MemberConnect_CheckApptype($apptype){
	global $empire,$dbtbpre;
                $apptype = mysql_real_escape_string($apptype);
	$appr=$empire->fetch1("select * from {$dbtbpre}enewsmember_connect_app where apptype='$apptype' and isclose=0 limit 1",MYSQL_ASSOC);
	if(!$appr['id'])
	{
		printerror2('请选择登录方式','../../../');
	}
	return $appr;
}
/**
 * 验证登录方式,是否存在指定的应用ID
 * @global mysqlquery $empire
 * @global string $dbtbpre
 * @param int $appid
 * @return array
 */
function MemberConnect_CheckAppId($appid){
	global $empire,$dbtbpre;
        $appid = (int)$appid;
	$appr=$empire->fetch1("select * from {$dbtbpre}enewsmember_connect_app where id=$appid and isclose=0 limit 1",MYSQL_ASSOC);
	if(!$appr['id'])
	{
		printerror2('请选择登录方式','../../../');
	}
	return $appr;
}

//验证openid
function MemberConnect_CheckOpenid($appid,$openid){
	global $empire,$dbtbpre;
	$mcr['id']=0;
	$mcr['userid']=0;
	if(!$appid||!trim($openid))
	{
		return $mcr;
	}
        $appid = (int)$appid;
	$mcr=$empire->fetch1("select id,userid from {$dbtbpre}enewsmember_connect where aid=$appid and openid='$openid' limit 1",MYSQL_ASSOC);
	return $mcr;
}

/**
 * 处理登录
 * @author LGM 修改,新增4个字段(token,expired,scope,refresh_token)
 * @param int $appid 应用ID,enewsmember_connect_app.id
 * @param int $openid  用户在第三方应用的ID
 * @param string $token access_token
 * @param int $expired 有效时间戳
 * @param string $scope 申请的权限
 * @param string $refresh_token  refresh_token
 */
function MemberConnect_DoLogin($appid, $openid, $token = NULL, $expired = NULL, $scope = NULL,$refresh_token=NULL)
{

    global $empire, $dbtbpre;
    $appid = intval($appid);
    $openid = RepPostVar($openid);
    $mcr = MemberConnect_CheckOpenid($appid, $openid);
	
    if ($mcr['id'])//已经绑定
    {
        $olduid= (int)$mcr['userid'];
        //判断用户是否存在:
        $olduname = GetUserName($olduid);
        if(empty($olduname))
        {
            //删除这条记录:
            $sqlcmd = "delete from {$dbtbpre}enewsmember_connect where userid='{$olduid}' AND aid='{$appid}' AND openid='{$openid}';";
            if ($empire->query($sqlcmd))
            {
                printerrortourl('../tobind.php');
            } else
                printerror();
        }
        //检查用户是否有效:
        $cr=$empire->fetch1("select ".eReturnSelectMemberF('checked,registertime')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$olduid' limit 1",MYSQL_ASSOC);
        if(empty($cr) || ((int)$cr['checked'])<1)
        {
            EmptyEcmsCookie();
            printerror("NotCheckedUser",'/',1);
        }
        $lifetime = 0;
        $cmd = "select " . eReturnSelectMemberF('*') . " from " . eReturnMemberTable() . " where " . egetmf('userid') . "='" . $olduid . "' limit 1";
        $r = $empire->fetch1($cmd, MYSQL_ASSOC);
        DoEcmsMemberLogin($r, $lifetime);        
        MemberConnect_UpdateBindLogin($mcr['id'], $token, $expired, $scope,$refresh_token); //更新信息        
        MemberConnect_ResetVar();
        printerrortourl(NULL, '', 0);
    } else
    {
        printerrortourl('../tobind.php');
    }
}

/**
 * 更新登录绑定
 * @author LGM 修改,新增4个字段(token,expired,scope,refresh_token)
 * @param int $id 授权表的ID
 * @param string $token
 * @param int $expired 时间戳
 * @param string $scope
 * @param string $refreshtoken
 */
function MemberConnect_UpdateBindLogin($id,$token=NULL,$expired=NULL,$scope=NULL,$refreshtoken=NULL){
	global $empire,$dbtbpre;
	$id=(int)$id;
	$lasttime=time();
	$ecmd='';

         //取出原来的token,expired,scope:
         $olddata = $empire->fetch1("SELECT token,rtoken,UNIX_TIMESTAMP(expired) as expired,scope,userid,rtoken FROM {$dbtbpre}enewsmember_connect where id='$id' limit 1", MYSQL_ASSOC);        
        if(!empty($token) && $olddata['token']!=$token) $ecmd.=',token=\''.mysql_real_escape_string($token).'\'';
        if(!empty($refreshtoken) && $olddata['rtoken']!=$refreshtoken) $ecmd.=',rtoken=\''.mysql_real_escape_string($refreshtoken).'\'';
                if(!empty($expired) && $olddata['expired']!=$expired && abs($expired-$olddata['expired'])>7200) $ecmd.=',expired=from_unixtime('.mysql_real_escape_string($expired).')';
	if(!empty($scope) && $olddata['scope']!=$scope) $ecmd.=',scope=\''.mysql_real_escape_string($scope).'\'';
        if($empire->query("update {$dbtbpre}enewsmember_connect set loginnum=loginnum+1,lasttime='$lasttime'{$ecmd} where id={$id} limit 1"))
        {
            if(EIService::GetCUBEISForSession()!=NULL && $olddata && !empty($ecmd))
            {
                if($olddata['token']!=$token || ($expired - $olddata['expired']>14400)||  $olddata['scope'] != $scope || $olddata['rtoken'] != $refreshtoken)//如果有所更改,则更新$_SESSION['mlbindeis'],半天内的有效期变化不动作.
                {                    
                    if(!isset($_SESSION))session_start();//开启session
                     $userid = intval($olddata['userid']);
                    $eis=EIService::GetBindEISForUID($userid);
                    EIService::SetCUBEISForSession($eis);
                }
            }
        }
}

/**
 * 写入登陆绑定
 * @author LGM 修改,新增4个字段(token,expired,scope,refresh_token)
 * @param int $appid 应用ID,enewsmember_connect_app.id
 * @param string $openid 第三方平台ID
 * @param int $userid 会员ID
 * @param string $openname 第三方平台用户名称
 * @param string $token 
 * @param int $expired 时间戳
 * @param string $scope
 */
function MemberConnect_InsertBind($appid,$openid,$userid,$openname=NULL,$token=NULL,$expired=NULL,$scope=NULL,$refresh_token=NULL){
	global $empire,$dbtbpre;
	$appid=intval($appid);
        if(isset($_SESSION['e_token'][$appid]))
        {
            $vp = &$_SESSION['e_token'][$appid];
            if(empty($token) && isset($vp['access_token'])) $token = $vp['access_token'];
            if(empty($refresh_token) && isset($vp['refresh_token'])) $refresh_token = $vp['refresh_token'];
            if(empty($scope) && isset($vp['scope'])) $scope = $vp['scope'];
        }
        if(empty($openname) && isset($_SESSION['openname'])) $openname = $_SESSION['openname'];
        if(empty($openid) && isset($_SESSION['openid'])) $openid = $_SESSION['openid'];       
        $apptype = mysql_real_escape_string(EIService::GetAppType($appid));
	$openid=mysql_real_escape_string($openid);        
	$openname=mysql_real_escape_string($openname);
	$userid=(int)$userid;
	$time=time();
	//验证是否重复
        if(!empty($token))
	   MemberConnect_CheckReBind($appid,$token);
	$exf='';
	$exv='';
	if(!empty($token))
	{
		$exf.=',token';
		$exv.=',\''.mysql_real_escape_string($token).'\'';
	}
	if(!empty($refresh_token))
	{
		$exf.=',rtoken';
                        $exv.=',\''.mysql_real_escape_string($refresh_token).'\'';
	}        
	if(!empty($expired))
	{
		$exf.=',expired';
		$exv.=',from_unixtime('.$expired.')';		
	}
	if(!empty($scope))
	{
		$exf.=',scope';
		$exv.=',\''.mysql_real_escape_string($scope).'\'';
	}
        $aid = (int)$_SESSION['cappid'];
        $cmd = "insert into {$dbtbpre}enewsmember_connect(aid,userid,apptype,openid,bindtime,bindname,loginnum,lasttime{$exf}) values({$aid}, '$userid','$apptype','$openid','$time','{$openname}',1,'$time'{$exv});";
        $empire->query($cmd);
}

/**
 * 指定用户已经绑定的应用token个数
 * @global resource $empire
 * @global string $dbtbpre
 * @param int $appid 应用ID
 * @param int $userid 会员ID
 * @return int
 */
function MemberConnect_CheckHasBindForUser($appid,$userid){
	global $empire,$dbtbpre;
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsmember_connect where userid='$userid' and aid='$appid' limit 1");
	return $num;	
}


/**
 * 检测是否重复绑定
 * @global resource $empire
 * @global string $dbtbpre
 * @param int $appid 应用ID
 * @param string $token
 */
function MemberConnect_CheckReBind($appid, $token)
{
    global $empire, $dbtbpre;
    $num = $empire->gettotal("select count(*) as total from {$dbtbpre}enewsmember_connect where token='$token' and aid='$appid' limit 1");
    if ($num)
    {
        printerror2("此帐号已绑定过，不能重复绑定", "history.go(-1)");
    }
}

//删除登陆绑定
function MemberConnect_DelBind($id){
	global $empire,$dbtbpre;
	$user_r=islogin();//是否登陆
	$id=(int)$id;
        $r= FALSE;
	$app=$empire->fetch1("select apptype,aid from {$dbtbpre}enewsmember_connect where id={$id}",MYSQL_NUM);
	if(isset($app[0]))
	{
		if(file_exists($app[0].'/class.php'))
		{
		   require_once($app[0].'/class.php');  
                   $o=EIService::GetOAuth((int)$app[1],array('id'=>$id));
                   $r= $o->Revokeoauth(); //取消授权：
		}
                else
                {
                    $r=$empire->query("delete from {$dbtbpre}enewsmember_connect where id='$id' and userid=".$user_r['userid'].";");
                }		
	}
        if($r) printerror2("已解除绑定","../memberconnect/ListBind.php");
	else	printerror("DbError","history.go(-1)",1);

}


/**
 * 原帐号绑定登录
 * @author 新增4个字段(token,expired,scope,mysql_real_escape_string() [2014年1月21日11:58 LGM修改]
 * @param unknown $userid
 * @param string $token
 * @param string $expired 时间戳
 * @param string $scope
 * @param string $refresh_token
 */
function MemberConnect_BindUser($userid, $token = NULL, $expired = NULL, $scope = NULL, $refresh_token = NULL)
{
    $openid = $_SESSION['openid'];
    $openname = $_SESSION['openname'];
    $app_id = $_SESSION['cappid'];
    $tv = $_SESSION['e_token'][$app_id];
    if (empty($token) && !empty($tv))
    {
        $token = $tv['access_token'];
        if (empty($expired))
        {
            $expired = 0;
            if (isset($token['create_at']) && isset($token['expire_in']))
                $expired = intval($token['create_at']) + intval($token['expire_in']);
            elseif (isset($token['expires_in']))
            {
                $gtime = time();
                if (isset($_SERVER['REQUEST_TIME']))
                    $gtime = (int) $_SERVER['REQUEST_TIME'];
                $expired = $gtime + (int) $token['expires_in'];
            }
        }
        $scope = $tv['scope'];
    }
    if (!$app_id || !trim($openid))
    {
        printerror2('来自的链接不存在', '../../../');
    }
    if (class_exists("EIService") == FALSE)  require_once(ECMS_PATH . 'e' . DIRECTORY_SEPARATOR . 'memberconnect' . DIRECTORY_SEPARATOR . 'eibase.php' );
    $appr = MemberConnect_CheckAppId($app_id); //验证登录方式
    MemberConnect_CheckBindKey($app_id, $openid);
    MemberConnect_InsertBind($app_id, $openid, $userid, $openname, $token, $expired, $scope, $refresh_token);
    MemberConnect_ResetVar();
}

//绑定验证符
function MemberConnect_GetBindKey($cappid,$openid){
	global $ecms_config;
	$checkpass=md5(md5('check-'.$cappid.'-empirecms-'.$openid).'-#empire.cms!-'.$openid.'-|-empirecms-|-'.$ecms_config['cks']['ckrndtwo']);
	return $checkpass;
}

//验证绑定验证符
function MemberConnect_CheckBindKey($cappid,$openid){
	global $ecms_config;
	$pass=md5(md5('check-'.$cappid.'-empirecms-'.$openid).'-#empire.cms!-'.$openid.'-|-empirecms-|-'.$ecms_config['cks']['ckrndtwo']);
	$checkpass=$_SESSION['openidkey'];
	if($pass!=$checkpass)
	{
		printerror2('来自的链接不存在','../../../');
	}
}
/**
 * 绑定会员与第三方交互平台(LGM增加[2014年3月18日18:33])
 * @param int $userid 本站会员ID
 */
function BindMemberFromEI($userid)
{
    $userid = (int) $userid;
    if ($userid == 0)
        return FALSE;
    if (!isset($_SESSION))
        session_start();
    $app_id = $_SESSION['cappid'];
    if ($app_id && isset($_SESSION['e_token'][$app_id]) && $_SESSION['e_token'][$app_id]['access_token'])
    {
        $tv = $_SESSION['e_token'][$app_id];
        $expired = 0;
        if (isset($tv['create_at']) && isset($tv['expire_in']))
            $expired = intval($tv['create_at']) + intval($tv['expire_in']);
        elseif (isset($tv['expires_in']))
        {
            $gtime = time();
            if (isset($_SERVER['REQUEST_TIME']))
                $gtime = (int) $_SERVER['REQUEST_TIME'];
            $expired = $gtime + (int) $tv['expires_in'];
        }
        MemberConnect_BindUser($userid, $tv['access_token'], $expired, $tv['scope'], $tv['refresh_token']);
    } else
        MemberConnect_BindUser($userid);
    return TRUE;
}

/**
 * 清除$_SESSOIN[state,openid,openname,cappid,apptype,openidkey,openerinfo],保留$_SESSION[n_token]
 */
function MemberConnect_ResetVar(){
	unset($_SESSION['state']);
	unset($_SESSION['openid']);
	unset($_SESSION['openname']);
	unset($_SESSION['cappid']);
        unset($_SESSION['apptype']);
	unset($_SESSION['openidkey']);
        unset($_SESSION['openerinfo']);
}