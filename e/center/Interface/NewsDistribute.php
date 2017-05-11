<?php
//define('EmpireCMSAdmin','1'); //只能在后台调用
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'connect.php');
require_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'db_sql.php');
require_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'functions.php');
require_once(AbsLoadLang('pub/fun.php'));
require_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'delpath.php');
require_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'copypath.php');
require_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'t_functions.php');
require_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'dbcache'.DIRECTORY_SEPARATOR.'class.php');
require_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'dbcache'.DIRECTORY_SEPARATOR.'MemberLevel.php');
include_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'adminfun.php');//载入日志记录函数
require_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'hinfofun.php');
require_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'Array2XML.php');

/**
 * 访问令牌类
 * @author LGM 2014年2月21日16:39
 *
 */
class NDAOuth
{
	private $ConnentIni;
	private $access_token;
	private $user_data;
	const TOKEN_EXPIRED=604800000; //7*24*60*60*1000 7天过期
	function __construct($username,$password)
	{
		global $link,$empire,$dbtbpre;
		if(empty($link))
		{
			$this->ConnentIni=False;
			$link=db_connect();
			$empire=new mysqlquery();
		}
		else
			$this->ConnentIni=TRUE;
		$this->user_data=NULL;
		$this->access_token=NULL;
		if(!empty($username) && !empty($password))
		{
		   $this->CreateTokenData ($username, $password);
		}
	}
	function __destruct(){		
		if(!$this->ConnentIni)
		{
			global $link,$empire;
			$empire=NULL;
			db_close();
		}
	}
	function __toString() {
		return $this->GetAccessToken();
	}
	public function GetAccessToken()
	{
		return $this->access_token;
	}	
	
	private function CreateTokenData($username,$password)
	{		
		$r=$this->chklogin($username, $password);
		if(is_array($r)&& empty($r['fault']))
		{
			$this->access_token = $r['accesstoken'];
			$this->user_data = $r;
		}
		return $r;
	}
	/**
	 * 用户登录
	 * @param string $username 用户名
	 * @param string $password 用户密码
	 * @return array : array('data'=>array('userid','username','accesstoken','userlevel'))或者 array('fault')
	 */
	private function chklogin($username,$password){
		global $empire,$public_r,$dbtbpre,$do_loginauth,$logininid,$loginin,$loginrnd,$loginlevel,$message_r,$ecms_config;
		$loginip=egetip();	
		$toDisableIp=self::ChkIP($loginip);
		if($toDisableIp)
		{
			return array('fault'=>(array('faultCode'=>-110,'faultString'=>MsgCore::TranslatMsg($message_r,'InvalidIP','非法IP登录'))));
		}
		$username=mysql_real_escape_string($username,$GLOBALS['link']);
		$password=mysql_real_escape_string($password,$GLOBALS['link']);
		if(!$username||!$password)
		{
			return array('fault'=>(array('faultCode'=>-101,'faultString'=>MsgCore::TranslatMsg($message_r,'LostUserOrPwd','用户名或者密码未提供完整'))));
		}
		if(strlen($username)>30||strlen($password)>30)
		{
			return array('fault'=>(array('faultCode'=>-102,'faultString'=>MsgCore::TranslatMsg($message_r,'InvalidUserOrPwd','无效的用户名或者密码'))));
		}
	
		$logintime=time();
		$user_r=$empire->fetch1("select userid,password,salt,rnd,groupid from {$dbtbpre}enewsuser where username='".$username."' and checked=0 limit 1",MYSQL_ASSOC);
		if(!$user_r['userid'])
		{
			//InsertErrorLoginNum($username,$password,0,$loginip,$logintime);
			return array('fault'=>(array('faultCode'=>-103,'faultString'=>MsgCore::TranslatMsg($message_r,'LoginFail','无效的用户名或者密码'))));
		}		
		$ch_password=md5(md5($password).$user_r['salt']);
		if($user_r['password']!=$ch_password)
		{
			InsertErrorLoginNum($username,$password,0,$loginip,$logintime);
			return array('fault'=>(array('faultCode'=>-104,'faultString'=>MsgCore::TranslatMsg($message_r,'LoginFail','无效的用户名或者密码'))));
		}	
		$logininid=$user_r['userid'];
		$loginin=$username;
		$loginrnd=$user_r['rnd'];
		$loginlevel=$user_r['groupid'];
		$this->access_token=$rnd=make_password(20);		
		$sql=$empire->query("update {$dbtbpre}enewsuser set rnd='$rnd',loginnum=loginnum+1,lastip='$loginip',lasttime=UNIX_TIMESTAMP() where username='$username' limit 1");
		//写入日志
		insert_log($username,'',1,$loginip,0);
		if($sql){
                    return array(
				'userid'=>$logininid,
				'username'=>$loginin,
				'accesstoken'=>$rnd,
				'userlevel'=>$loginlevel
                );                    
                }
		else 
			return array('fault'=>(array('faultCode'=>-113,'faultString'=>MsgCore::TranslatMsg($message_r,'DbError','写入数据库时出错'))));
	}
	/**
	 * 检查IP是否合法（后台模式）
	 * @param string $ip
	 * @return boolean
	 */
	static function ChkIP($ip)
	{
		global $public_r;
		$close=1;

		
		if(!empty($public_r['hopenip']))//允许IP
		{
			foreach(explode("\n",$public_r['hopenip']) as $ctrlip)
			{
				if(preg_match("/^(".preg_quote(($ctrlip=trim($ctrlip)),'/').")/",$ip))
				{
					$close=0;
					break;
				}
			}
		}
		else
			$close==0;
		if($close==0 && !empty($public_r['hcloseip']))
		{
			foreach(explode("\n",$public_r['closeip']) as $ctrlip)
			{
				if(preg_match("/^(".preg_quote(($ctrlip=trim($ctrlip)),'/').")/",$ip))
				{
					$close=1;
					break;
				}
			}
		}
		return !((boolean)$close);
	}	
	/**
	 * 获取accessToken所关联的用户信息
	 * @param string $accessToken
	 * @return array | NULL :array('userid','username','accesstoken','userlevel')
	 */
	public function GetAccessTokenInfo($accessToken=NULL)
	{
		if(empty($accessToken))
			return $this->user_data;
		global $empire,$public_r,$dbtbpre;
		$at= mysql_real_escape_string($accessToken,$GLOBALS['link']);
		$user_r=$empire->fetch1("select userid,username,groupid from {$dbtbpre}enewsuser where rnd='".$at."' limit 1");
		if(!empty($user_r))
		{
			return array(
					'userid'=>$user_r['userid'],
					'username'=>$user_r['username'],
					'accesstoken'=>$accessToken,
					'userlevel'=>$user_r['groupid']
			);
		}
		return NULL;
	}
	/**
	 * 检查accessToken是否有效
	 * @param string $accessToken
	 * @return array |boolean
	 */
	static public function ChkAccessToken($accessToken)
	{
		if(empty($accessToken))
		{
			return array('fault'=> array('faultCode'=>-111,
					'faultString'=>'缺少必要的参数access_token'));
		}
		global $empire,$public_r,$dbtbpre,$message_r;
		$access_token= mysql_real_escape_string($accessToken,$GLOBALS['link']);
		//检测$access_token是否合法与过期 
		$cmd="update {$dbtbpre}enewsuser set lastip='".egetip()."' where rnd='$access_token' AND (lasttime+".self::TOKEN_EXPIRED.">UNIX_TIMESTAMP()) AND checked=0 limit 1";
		$sql=(bool)$empire->query($cmd);
		if(!$sql)
		{
			return array('fault'=> array('faultCode'=>-112,
					'faultString'=>'access_token无效或者过期'));
		}
		return TRUE;
	}

}

/**
 * 文章分发
 * @author LGM 2014年2月21日8:09
 * 输出为基本格式
 */
class NewsDistribute
{

	/**
	 * 是否已经将数据库连接初始化
	 * @var bool
	 */
	private $ConnentIni;
	private $access_token;
	/**
	 * 文章分发的构造函数
	 * @param string $accessToken
	 */
	function __construct($accessToken)
	{		
		global $link,$empire,$public_r,$incftp;		
		if(empty($link))
		{
			$this->ConnentIni=False;
			$link=db_connect();
			$empire=new mysqlquery();
		}
		else 
			$this->ConnentIni=TRUE;
		$this->access_token=$accessToken;
		if($public_r['phpmode'])
		{
			@include_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'ftp.php');
			$incftp=1;
		}
		//防采集
		 if($public_r['opennotcj'])
		 {
		 @include_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'dbcache'.DIRECTORY_SEPARATOR.'notcj.php');
		}
	}
	function __destruct(){
		global $link,$empire;
		if(!$this->ConnentIni)
		{			
			$empire=NULL;
			db_close();
		}
	}


	/**
	 * 检查是否存在相同的标题
	 * @param array $add array('classid','title','mode','access_token')  title为必须，mode为模型名字简写,如：news/download
	 * @return array array('params'=>array('data'=>'找到的第一个匹配项的ID','check'=>'0为没有审核','mode'=>'模型名'))
	 */
	public function SearchTitle(&$add){
		global $empire,$dbtbpre,$class_r,$message_r;
		$aar=NDAOuth::ChkAccessToken((empty($add['access_token'])?$this->access_token:$add['access_token']));if($aar!==TRUE) return $aar;//验证
		$classid=(int)$add['classid'];
		if(empty($add['title']))
		{
			return array('fault'=>(array('faultCode'=>-10,'faultString'=>MsgCore::TranslatMsg($message_r,'EmptyTitle','请正确设置标题(title)'))));
		}
		$title=AddAddsData($add['title']);
		$tables=array();
		$where='';
		$tableper=$dbtbpre.'ecms_';
		if(!empty($add['classid']))
		{
			$tables[]=$tableper.$class_r[$classid]['tbname'];
			$where=' and classid='.$classid;
		}
		else 
		{  
			if(empty($add['mode']))	
				$tables = GetModesTableName();
			else 
				$tables[]=$tableper.$add['mode'];			
		}
		$ck=0;//已审核
		$num=0;
		$cti=0;
		foreach ($tables as $t)
		{
			$tobj=NULL;
			$cmd="select id from `".$t."` where title='".($title)."'".$where." limit 1";
			$num=0;$ck=0;
			$tobj=$empire->fetch1($cmd,MYSQL_ASSOC);
			if(!empty($tobj)) $num=intval($tobj['id']);			
			if($num==0)
			{    //未审核
				$cmd= "select id from `".$t."_check` where title='".($title)."'".$where." limit 1";
				$tobj=$empire->fetch1($cmd,MYSQL_ASSOC);
				if(!empty($tobj)) 
				{
					$num=intval($tobj['id']);
					$ck=1;
				}
				else $num=0;				
			}
			if($num!=0)
				break;
			$cti++;
		}		
		$r=0;
		if($num!=0)
		{
			$r=$num;
		}
		$dr= array('params'=>array('data'=>$r));
		if($ck)
			$dr['params']['check']=0;
		if($num!=0)
			$dr['params']['mode']=substr($tables[$cti],strlen($tableper));
		return $dr;
	}
	
	/**
	 * 加入专题
	 * @param string $ztids 专题ID列表,用英文逗号分隔
	 * @param string $zcids 专题ID列表,用英文逗号分隔
	 * @param int $classid 分类ID
	 * @param int $id 文章ID
	 * @param datetime $newstime 时间
	*/	
	public static  function zzInsertZtInfo($ztids,$zcids,$classid,$id,$newstime){
		global $empire,$dbtbpre,$class_r;
		if($ztids)
		{
			$zr=explode(',',$ztids);
			$cr=explode(',',$zcids);
			$count=count($zr);
			for($i=0;$i<$count;$i++)
			{
			$zid=(int)$zr[$i];
			if (count($cr)>$i){
			$cid=(int)$zr[$i];
			}
				else{
					$cid=-1;
			}
					if(!$cid)
					{
					$cid=-1;
			}
				if($cid<0)
					{
					$cid=0;
			}
			AddInfoToZt($zid,$cid,$classid,$id,$newstime,0,1);
			}
			}
	}
			
/**
 * 发布文章
 * @param  $info array : array('access_token','classid','title'...)
 * @param  $info[access_token] string :访问令牌
 * @param  $info[classid] int :分类ID
 * @param  $info[title] string :文章标题
 * @param  $info[ftitle] string :文章副标题
 * @param  $info[wztitle] string :文章完整主标题
 * @param  $info[bclassid] int :父分类ID(一般不需要填)
 * @param  $info[titlecolor] string :标题颜色
 * @param  $info[ztids] string :专题ID列表,用英文逗号分隔
 * @param  $info[zcids] string :专题分类ID列表,用英文逗号分隔
 * @param  $info[titleurl] string :文章链接(链接型文章)
 * @param  $info[ttid] int :标题分类ID
 * @param  $info[titlefont] array|string :标题字体选项,为字符串时用英文逗号分隔
 * @param  $info[titlecolor] string :标题颜色
 * @param  $info[checked] bit :是否已经审核(1-是,0-否)
 * @param  $info[author] string :作者
 * @param  $info[authorsft] bit :作者开关
 * @param  $info[isgood] int :推荐级别
 * @param  $info[firsttitle] int :头条级别
 * @param  $info[keyboard] int :关键词
 * @param  $info[newstime] datetime :发布时间
 * @param  $info[copyfrom] string :文章来源
 * @param  $info[fromlink] string :文章来源原文链接
 * @param  $info[titlepic] string :标题图片路径
 * @param  $info[smalltext] string :文章概览
 * @param  $info[newstext] string :文章内容
 * @param  $info[dokey] bit :是否替换内容关键字
 * @param  $info[copyflash] bit :是否保存FLASH
 * @param  $info[qz_url] bit :保存FLASH地址前缀 
 * @param  $info[autopage] bit :是否自动分页
 * @param  $info[autosize] int :自动分页每页字数
 * @param  $info[getfirsttitlepic] bit :是否生成缩略图
 * @param  $info[getfirsttitlespicw] int :缩略图宽度
 * @param  $info[getfirsttitlespich] int :缩略图高度
 * @param  $info[editor] string :责任编辑用户名
 * @param  $info[lteditor] string :最后编辑编辑用户名
 * @param  $info[userip] string :投稿者IP
 * @param  $info[editnotes] string :编辑附注
 * @param  $info[infotags] string :TAGS,用英文逗号分隔
 * @param  $info[istop] int :置顶级别
 * @param  $info[newstempid] int :内容模板ID,缺省为0
 * @param  $info[groupid] int :查看会员组ID
 * @param  $info[userfen] int :查看会员组扣除点数
 * @param  $info[onclick] int :点击数
 * @param  $info[totaldown] int :下载数
 * @param  $info[newspath] string :文章生成路径目录
 * @param  $info[copyclassid] array|string :同时发布到的栏目ID列表,为字符串时用英文逗号分隔
 * @param  $info[vote_title] string :投票主题标题
 * @param  $info[vote_name] array|string :投票项列表,为字符串时用竖线或者英文逗号分隔
 * @param  $info[vote_num] array|string :投票项票数列表,为字符串时用英文逗号分隔
 * @param  $info[v_vote_num] int :投票扩展数量
 * @param  $info[v_editnum] int :投票项数量
 * @param  $info[vote_class] int :投票类型
 * @param  $info[dovote_ip] bit :投票IP限制,同一IP只能投一次票,0为不限制,1为限制
 * @param  $info[vote_dotime] datetime :投票期限
 * @param  $info[vote_width] int :投票窗口宽度
 * @param  $info[vote_height] int :投票窗口高度
 * @param  $info[vote_tempid] int :投票模板ID
 * @param  $userid int 用户ID
 * @param  $username string 用户名字 
 * @return array
 */
	private function AddNews(array $info,$userid,$username){
		global $empire,$class_r,$class_zr,$bclassid,$public_r,$dbtbpre,$emod_r,$message_r;
		if(empty($info['filepass']))$info['filepass']=time(); //zzcity add
		$navtheid=(int)$info['filepass'];
		if(isset($info['titlefont']) && !is_array($info['titlefont']))
		{
			$info['titlefont'] = explode(',', strval($info['titlefont']));
		}
		if(isset($info['copyclassid']) && !is_array($info['copyclassid']))
		{
			$info['copyclassid'] = explode(',', strval($info['copyclassid']));
		}
		if(isset($info['vote_name']) && !is_array($info['vote_name']))
		{
			if(strpos($info['vote_name'],'|')>0)
			   $info['vote_name'] = explode('|', strval($info['vote_name']));
			else
			  $info['vote_name'] = explode(',', strval($info['vote_name']));
		}
		if(isset($info['vote_num']) && !is_array($info['vote_num']))
		{
			  $info['vote_num'] = explode(',', strval($info['vote_num']));
		}						
		$info=DoPostInfoVar($info);//处理增加信息变量
		$info['classid']=(int)$info['classid'];			
		if (!isset($info['checked'])) //默认为不审核
		{
			$info['checked']=$class_r[$info['classid']]['checked'];
		}
		elseif($info['checked']==NULL) 
		   $info['checked']='0'; 

		if (!isset($info['bclassid'])){ //分类父栏目ID
			$info['bclassid']=$class_r[$info['classid']]['bclassid'];
		}
		if (!isset($info['newstempid'])){$info['newstempid']='0';} //内容模板ID，默认新闻内容模板8，默认下载内容模板2
		//if (empty($_POST['fh'])){$_POST['fh']='0';} //模板
		if (!isset($info['autopage'])){$info['autopage']='1';} //自动分页
		if (empty($info['autosize'])){$info['autosize']=AUTOPAGESIZE;} //分页大小
		if (!isset($info['onclick'])){   //点击数
			$info['onclick']='0';
		}elseif(intval($info['onclick'])==-1) {//如果点击数为-1则为随机数
			$info['onclick']=rand(0,200);
		}
		if (!empty($info['newstime'])){   //发布日期
		
			$info['newstime']=str_replace("/","-",trim($info['newstime']));
			$info['newstime']=str_replace(array("年","月"),"-",trim($info['newstime']));
			$info['newstime']=str_replace(array("时","分"),":",trim($info['newstime']));
			$info['newstime']=str_replace(array("日","秒")," ",trim($info['newstime']));
			$info['newstime']=preg_replace('/ {2,}/',' ',trim($info['newstime']));
			$info['newstime']=preg_replace('/\-(\d)\-/','-0$1-',trim($info['newstime']));
			$info['newstime']=preg_replace('/\-(\d) /','-0$1 ',trim($info['newstime']));
			$info['newstime']=preg_replace('/\-(\d)$/','-0$1',trim($info['newstime']));
			$info['newstime']=preg_replace('/ (\d):/',' 0$1:',trim($info['newstime']));
			$info['newstime']=preg_replace('/:(\d):/',':0$1:',trim($info['newstime']));
			$info['newstime']=preg_replace('/:(\d)$/',':0$1',trim($info['newstime']));
			if (strtotime($info['newstime'])==false||strtotime($info['newstime'])==-1){
				return  array('fault'=>(array('faultCode'=>-105,'faultString'=>MsgCore::TranslatMsg($message_r,'InvalidTimeFormat','发布时间格式错误'))));
			}
			//$_POST['newstime']=strtotime($_POST['newstime']);
		}
		if (!empty($info['info_infouptime'])){   //定时上线发布日期
		
			$info['info_infouptime']=str_replace("/","-",trim($info['info_infouptime']));
			$info['info_infouptime']=str_replace(array("年","月"),"-",trim($info['info_infouptime']));
			$info['info_infouptime']=str_replace(array("时","分"),":",trim($info['info_infouptime'] ) );
			$info ['info_infouptime'] = str_replace ( array (
					"日",
					"秒" 
			), " ", trim ( $info ['info_infouptime'] ) );
			$info ['info_infouptime'] = preg_replace ( '/ {2,}/', ' ', trim ( $info ['info_infouptime'] ) );
			$info ['info_infouptime'] = preg_replace ( '/\-(\d)\-/', '-0$1-', trim ( $info ['info_infouptime'] ) );
			$info ['info_infouptime'] = preg_replace ( '/\-(\d) /', '-0$1 ', trim($info['info_infouptime']));
			$info['info_infouptime']=preg_replace('/\-(\d)$/','-0$1',trim($info['info_infouptime']));
			$info['info_infouptime']=preg_replace('/ (\d):/',' 0$1:',trim($info['info_infouptime']));
			$info['info_infouptime']=preg_replace('/:(\d):/',':0$1:',trim($info['info_infouptime']));
			$info['info_infouptime']=preg_replace('/:(\d)$/',':0$1',trim($info['info_infouptime']));
			if (strtotime($info['info_infouptime'])==false||strtotime($info['info_infouptime'])==-1){
				return  array('fault'=>(array('faultCode'=>-106,'faultString'=>MsgCore::TranslatMsg($message_r,'InvalidTimeFormat','定时上线时间格式错误'))));
			}
		}
		if (!empty($info['info_infodowntime'])){   //定时下线发布日期
		
			$info['info_infodowntime']=str_replace("/","-",trim($info['info_infodowntime']));
			$info['info_infodowntime']=str_replace(array("年","月"),"-",trim($info['info_infodowntime']));
			$info['info_infodowntime']=str_replace(array("时","分"),":",trim($info['info_infodowntime']));
			$info['info_infodowntime']=str_replace(array("日","秒")," ",trim($info['info_infodowntime']));
			$info['info_infodowntime']=preg_replace('/ {2,}/',' ',trim($info['info_infodowntime']));
			$info['info_infodowntime']=preg_replace('/\-(\d)\-/','-0$1-',trim($info['info_infodowntime']));
			$info['info_infodowntime']=preg_replace('/\-(\d) /','-0$1 ',trim($info['info_infodowntime']));
			$info['info_infodowntime']=preg_replace('/\-(\d)$/','-0$1',trim($info['info_infodowntime']));
			$info['info_infodowntime']=preg_replace('/ (\d):/',' 0$1:',trim($info['info_infodowntime']));
			$info['info_infodowntime']=preg_replace('/:(\d):/',':0$1:',trim($info['info_infodowntime']));
			$info['info_infodowntime']=preg_replace('/:(\d)$/',':0$1',trim($info['info_infodowntime']));
			if (strtotime($info['info_infodowntime'])==false||strtotime($info['info_infodowntime'])==-1){
				return  array('fault'=>(array('faultCode'=>-107,'faultString'=>MsgCore::TranslatMsg($message_r,'InvalidTimeFormat','定时下线发布日期格式错误'))));
			}
		}
		

		if(empty($info['newspath']))
		  $info['newspath']=date($class_r[$info['classid']]['newspath']);
		$userid=(int)$userid;
		if(!$info['title']||!$info['classid'])
		{
			return array('fault'=>(array('faultCode'=>-10,'faultString'=>MsgCore::TranslatMsg($message_r,'EmptyTitle','请正确设置标题(title)和栏目(classid)'))));
		}
	
		//操作权限
		$doselfinfo=CheckLevel($userid,$username,$info['classid'],"news");
		if(!$doselfinfo['doaddinfo'])//增加权限
		{
			return array('fault'=>(array('faultCode'=>-10,'faultString'=>MsgCore::TranslatMsg($message_r,'NotAddInfoLevel','该用户没有增加信息的权限'))));
		}
	
		$ccr=$empire->fetch1("select classid,modid,listdt,haddlist,sametitle,addreinfo,wburl,repreinfo from {$dbtbpre}enewsclass where classid='{$info['classid']}' and islast=1 limit 1",MYSQL_ASSOC);
		if(!$ccr['classid']||$ccr['wburl'])
		{
			return array('fault'=>(array('faultCode'=>-11,'faultString'=>MsgCore::TranslatMsg($message_r,'ErrorUrl','无效的分类或链接'))));
		}
		if($ccr['sametitle'])//验证标题重复
		{
			if(ReturnCheckRetitle($info))
			{
				return array('fault'=>(array('faultCode'=>-12,'faultString'=>MsgCore::TranslatMsg($message_r,'ReInfoTitle','标题重复，增加不成功'))));
			}
		}
		$ret_r=ReturnAddF($info,$class_r[$info['classid']]['modid'],$userid,$username,0,0,1);//返回自定义字段
		$newspath=FormatPath($info['classid'],$info['newspath'],1);//查看目录是否存在，不存在则建立
		//签发
		$isqf=0;
		if($class_r[$info['classid']]['wfid'])
		{
			$info['checked']=0;
			$isqf=1;
		}	
		//$truetime=time();
		$newstime=empty($info['newstime'])?time():to_time($info['newstime']);
		$truetime=$newstime;
		$lastdotime=$truetime;
		//是否生成
		$havehtml=0;
		if($info['checked']==1&&$ccr['addreinfo'])
		{
			$havehtml=1;
		}
		//返回关键字组合
		if($info['info_diyotherlink'])
		{
			$keyid=DoPostDiyOtherlinkID($info['info_keyid']);
		}
		else
		{
			$keyid=GetKeyid($info['keyboard'],$info['classid'],0,$class_r[$info['classid']]['link_num']);
		}
		//附加链接参数
		$addecmscheck=empty($info['checked'])?'&ecmscheck=1':'';
		//索引表
		$cmdp="insert into {$dbtbpre}ecms_".$class_r[$info['classid']]['tbname']."_index(classid,checked,newstime,truetime,lastdotime,havehtml) values('{$info['classid']}','{$info['checked']}','$newstime','$truetime','$lastdotime','$havehtml');";
		$sql=$empire->query($cmdp);
		$id=$empire->lastid();
		$pubid=ReturnInfoPubid($info['classid'],$id);
		$infotbr=ReturnInfoTbname($class_r[$info['classid']]['tbname'],$info['checked'],$ret_r['tb']);
		$filename='';
		//主表
		$cmdp="insert into ".$infotbr['tbname']."(id,classid,ttid,onclick,plnum,totaldown,newspath,userid,username,firsttitle,isgood,ispic,istop,isqf,ismember,isurl,truetime,lastdotime,havehtml,groupid,userfen,titlefont,titleurl,stb,fstb,restb,keyboard".$ret_r['fields'].") values('$id','{$info['classid']}','{$info['ttid']}','{$info['onclick']}',0,'{$info['totaldown']}','$newspath','$userid','".addslashes($username)."','{$info['firsttitle']}','{$info['isgood']}','{$info['ispic']}','{$info['istop']}','$isqf',0,'{$info['isurl']}','$truetime','$lastdotime','$havehtml','{$info['groupid']}','{$info['userfen']}','".addslashes($info['my_titlefont'])."','".addslashes($info['titleurl'])."','{$ret_r['tb']}','{$public_r['filedeftb']}','{$public_r['pldeftb']}','".addslashes($info['keyboard'])."'".$ret_r['values'].");";
		$infosql=$empire->query($cmdp);
		$id=$empire->lastid();
		//副表
		$cmdp="insert into ".$infotbr['datatbname']."(id,classid,keyid,dokey,newstempid,closepl,haveaddfen,infotags".$ret_r['datafields'].") values('$id','{$info['classid']}','$keyid','{$info['dokey']}','{$info['newstempid']}','{$info['closepl']}',0,'".addslashes($info['infotags'])."'".$ret_r['datavalues'].");";
		$finfosql=$empire->query($cmdp);
	
		//更新栏目信息数
		AddClassInfos($info['classid'],'+1','+1',$info['checked']);
		//更新新信息数
		DoUpdateAddDataNum('info',$class_r[$info['classid']]['tid'],1);		
		//签发
		if($isqf==1)
		{
			InfoInsertToWorkflow($id,$info['classid'],$class_r[$info['classid']]['wfid'],$userid,$username);
		}
		//更新附件表
		UpdateTheFile($id,$info['filepass'],$info['classid'],$public_r['filedeftb']);
		// 取第一张图作为标题图片
		if ($info ['getfirsttitlepic'] && empty ( $info ['titlepic'] )) {
			$firsttitlepic = GetFpicToTpic ( $info['classid'], $id, $info['getfirsttitlepic'], $info['getfirsttitlespic'], $info['getfirsttitlespicw'], $info ['getfirsttitlespich'], $public_r['filedeftb'] );
			if ($firsttitlepic) {
				$addtitlepic = ",titlepic='" . addslashes( $firsttitlepic ) . "',ispic=1";
			}
		}
		//文件命名
		if($info['filename'])
		{
			$filename=$info['filename'];
		}
		else
		{
			$filename=ReturnInfoFilename($info['classid'],$id,'');
		}
		//信息地址
		$updateinfourl='';
		if(!$info['isurl'])
		{
			$infourl=GotoGetTitleUrl($info['classid'],$id,$newspath,$filename,$info['groupid'],$info['isurl'],$info['titleurl']);
			$updateinfourl=",titleurl='$infourl'";
		}
		$usql=$empire->query("update ".$infotbr['tbname']." set filename='$filename'".$updateinfourl.$addtitlepic." where id='$id'");
		//替换图片下一页
		if($info['repimgnexturl'])
		{
			UpdateImgNexturl($info['classid'],$id,$info['checked']);
		}		
		//投票
		AddInfoVote($info['classid'],$id,$info);
		//加入专题
		self::zzInsertZtInfo($info['ztids'],$info['zcids'],$info['classid'],$id,$newstime);
		//TAGS
		if($info['infotags']&&$info['infotags']<>$info['oldinfotags'])
		{
			eInsertTags($info['infotags'],$info['classid'],$id,$newstime);
		}
		//增加信息是否生成文件
		if($ccr['addreinfo']&&$info['checked'])
		{
			GetHtml($info['classid'],$id,'',0);
		}
		//生成上一篇
		if($ccr['repreinfo']&&$info['checked'])
		{
			$prer=$empire->fetch1("select * from {$dbtbpre}ecms_".$class_r[$info['classid']]['tbname']." where id<$id and classid='{$info['classid']}' order by id desc limit 1");
			GetHtml($info['classid'],$prer['id'],$prer,1);
		}
		//生成栏目
		if($ccr['haddlist']&&$info['checked'])
		{
			hAddListHtml($info['classid'],$ccr['modid'],$ccr['haddlist'],$ccr['listdt']);//生成信息列表
			if($info['ttid'])//生成标题分类列表
			{
				ListHtml($info['ttid'],'',5);
			}
		}
		//同时发布
		$copyclassid=$info['copyclassid'];
		$cpcount=count($copyclassid);
		if($cpcount)
		{
			$copyids=AddInfoToCopyInfo($info['classid'],$id,$copyclassid,$userid,$username,$doselfinfo);
			if($copyids)
			{
				UpdateInfoCopyids($info['classid'],$id,$copyids);
			}
		}
		if($sql)
		{
			insert_dolog("classid={$info['classid']}<br>id=".$id."<br>title=".$info['title'].'<br>info:由".__CLASS__."接口发布',$pubid);//操作日志
			return array('params'=>array('data'=>1,'id'=>$id,'info'=>MsgCore::TranslatMsg($message_r,'AddNewsSuccess','新增文章成功')));
		}
		else
		{
			return array('fault'=>(array('faultCode'=>-100,'faultString'=>MsgCore::TranslatMsg($message_r,'DbError','新增文章时SQL执行错'))));
		}
	}
	/**
	 * 修改文章
	 * @param array $info
	 * @param int $userid
	 * @param string $username
	 * @return string
	 */
	private function toEditNews(array $info,$userid,$username)
	{
		global $empire,$class_r,$class_zr,$bclassid,$public_r,$dbtbpre,$emod_r,$message_r,$bclassid;
		//参数预处理:
		if(empty($info['filepass']))$info['filepass']=time();
		$navtheid=(int)$info['filepass'];
		$id= (int)$info['id'];
		if(isset($info['titlefont']) && !is_array($info['titlefont']))
		{
			$info['titlefont'] = explode(',', strval($info['titlefont']));
		}
		if(isset($info['vote_name']) && !is_array($info['vote_name']))
		{
			if(strpos($info['vote_name'],'|')>0)
				$info['vote_name'] = explode('|', strval($info['vote_name']));
			else
				$info['vote_name'] = explode(',', strval($info['vote_name']));
		}
		if(isset($info['vote_num']) && !is_array($info['vote_num']))
		{
			$info['vote_num'] = explode(',', strval($info['vote_num']));
		}
		$classid= (int)$info['oldclassid'];//旧的classid		
		$modid=$class_r[$classid]['modid'];//模型
		$enter=$emod_r[$modid]['enter'];
		if(empty($info['classid'])) $info['classid'] = $classid;
		$newclassid= (int)$info['classid'];//新的classid
		if(!isset($info['bclassid'])) $info['bclassid'] = $class_r[$newclassid]['bclassid'];
		$bclassid = (int)$info['bclassid'];
		//检查old*参数:
		if(empty($info['oldgroupid']) ||  empty($info['oldchecked']) ||   empty($info['oldttid']) ||   empty($info['oldttid']) ||   empty($info['info_keyid']) ||   empty($info['oldinfotags']))
		{
			//从数据库里读取旧值:
			//索引表
                        $dcmd="select id,classid,checked from {$dbtbpre}ecms_".$class_r[$classid]['tbname']."_index where id='$id' limit 1";
			$index_r=$empire->fetch1($dcmd,MYSQL_ASSOC);
			if(empty($index_r) || !$index_r['id'])
			{
				printerror("HaveNotInfo",array('id'=>$id,'classid'=>$classid));//报错,此信息不存在
			}
			//返回表
			$infotb=ReturnInfoMainTbname($class_r[$classid]['tbname'],$index_r['checked']);
			//主表
                        $dcmd="select * from ".$infotb." where id='$id' limit 1";
			$r=$empire->fetch1($dcmd,MYSQL_ASSOC);
			//签发表
			if($r['isqf'])
			{
				$wfinfor=$empire->fetch1("select tstatus,checktno from {$dbtbpre}enewswfinfo where id='$id' and classid='$classid' limit 1",MYSQL_ASSOC);//签发
			}
			//返回表信息
			$infodatatb=ReturnInfoDataTbname($class_r[$classid]['tbname'],$index_r['checked'],$r['stb']);
			//副表
			$finfor=$empire->fetch1("select ".ReturnSqlFtextF($modid)." from ".$infodatatb." where id='$id' limit 1",MYSQL_ASSOC);
			$r=array_merge($r,$finfor);
			//时间
			$r['checked']=$index_r['checked'];			
			if(!$r['isurl'])
			{
				$r['titleurl']='';
			}
                        if(!isset($info['oldchecked']) && isset($info['checked'])) $info['oldchecked'] = (int)$r['checked'];
			//if(empty($info['fstb']))$info['fstb']=$r['fstb'];
			if(!isset($info['oldttid']) && isset($info['ttid']))$info['oldttid']=$r['ttid'];
                        if(empty($info['oldfilename']) && !empty($r['filename']))$info['oldfilename']=$r['filename']; if(empty($info['filename']) && !empty($info['oldfilename'])) $info['filename'] = $info['oldfilename'];
                        if(empty($info['oldgroupid']) && isset($info['groupid']))$info['oldgroupid']=$r['groupid'];
                        if(empty($info['oldinfotags']) && !empty($info['infotags']) && !empty($r['infotags']))$info['oldinfotags']=$r['infotags']; 
                        if(empty($info['info_keyid']) && !empty($r['keyid']))$info['info_keyid']=$r['keyid'];
		}
		$rdata=EditNews($info,$userid,$username);
		return $rdata;
	}
        /**
         * NewsDistribute类操作入口
         * @param string $methodName 方法名称
         * @param array $info 输入参数数组
         * @return string
         */
	public function DoAction($methodName,&$info)
	{
		$token= (empty($info['access_token'])?$this->access_token:$info['access_token']);
		$aar=NDAOuth::ChkAccessToken($token);if($aar!==TRUE) return $aar;//验证
		$oauth = new NDAOuth(NULL,NULL);
		$udata= $oauth->GetAccessTokenInfo($token);//取得用户名与用户ID
		$userid = intval($udata['userid']);
		$username= $udata['username'];
		$rdata=NULL;
		$curSilent = MsgCore::ScreenSilent();
		if(!$curSilent)	MsgCore::ScreenSilent(TRUE);//进入静默模式
		try{
		    if($methodName=='AddNews')
		    {
		    	$rdata = $this->AddNews($info,$userid,$username);
		    }
			elseif($methodName=="SearchTitle")//是否存在相同标题的文章
			{
				$rdata=$this->SearchTitle($info);
			}		    
			elseif($methodName=="EditNews")//修改信息
			{
				$navtheid=(int)$info['id'];
				$rdata=$this->toEditNews($info,$userid,$username);
			}
			elseif($methodName=="EditInfoSimple")//修改信息(快速)
			{
				$navtheid=(int)$info['id'];
				$rdata=EditInfoSimple($info,$userid,$username);
			}
			elseif($methodName=="DelNews")//删除信息
			{
				$id=$info['id'];
				$classid=$info['classid'];
				$bclassid=$info['bclassid'];
				$rdata=DelNews($id,$classid,$userid,$username);
			}
			elseif($methodName=="DelNews_all")//批量删除信息
			{
				$id=$info['id'];
				$classid=$info['classid'];
				$bclassid=$info['bclassid'];
				$ecms=$info['ecmscheck']?2:0;
				$rdata=DelNews_all($id,$classid,$userid,$username,$ecms);
			}
			elseif($methodName=="EditMoreInfoTime")//批量修改信息时间
			{
				$rdata = EditMoreInfoTime($info,$userid,$username);
			}
			elseif($methodName=="DelInfoDoc_all")//删除归档
			{
				$id=$info['id'];
				$classid=$info['classid'];
				$bclassid=$info['bclassid'];
				$rdata=DelNews_all($id,$classid,$userid,$username,1);
			}
			elseif($methodName=='AddInfoToReHtml')//刷新页面
			{
				$rdata = AddInfoToReHtml($info['classid'],$info['dore']);
			}
			elseif($methodName=="TopNews_all")//信息置顶
			{
				$bclassid=$info['bclassid'];
				$classid=$info['classid'];
				$id=$info['id'];
				$istop=$info['istop'];
				$rdata = TopNews_all($classid,$id,$istop,$userid,$username);
			}
			elseif($methodName=="CheckNews_all")//审核信息
			{
				$bclassid=$info['bclassid'];
				$classid=$info['classid'];
				$id=$info['id'];
				$rdata = CheckNews_all($classid,$id,$userid,$username);
			}
			elseif($methodName=="NoCheckNews_all")//取消审核信息
			{
				$bclassid=$info['bclassid'];
				$classid=$info['classid'];
				$id=$info['id'];
				$rdata = NoCheckNews_all($classid,$id,$userid,$username);
			}
			elseif($methodName=="MoveNews_all")//移动信息
			{
				$bclassid=$info['bclassid'];
				$classid=$info['classid'];
				$id=$info['id'];
				$to_classid=$info['to_classid'];
				$rdata = MoveNews_all($classid,$id,$to_classid,$userid,$username);
			}
			elseif($methodName=="CopyNews_all")//复制信息
			{
				$bclassid=$info['bclassid'];
				$classid=$info['classid'];
				$id=$info['id'];
				$to_classid=$info['to_classid'];
				$rdata = CopyNews_all($classid,$id,$to_classid,$userid,$username);
			}
			elseif($methodName=="MoveClassNews")//批量移动信息
			{
				$add=$info['add'];
				$rdata = MoveClassNews($add,$userid,$username);
			}
			elseif($methodName=="GoodInfo_all")//批量推荐/头条信息
			{
				$classid=$info['classid'];
				$id=$info['id'];
				$doing=$info['doing'];
				$isgood=empty($doing)?$info['isgood']:$info['firsttitle'];
				$rdata = GoodInfo_all($classid,$id,$isgood,$doing,$userid,$username);
			}
			elseif($methodName=="SetAllCheckInfo")//本栏目信息全部审核
			{
				$classid=$info['classid'];
				$bclassid=$info['bclassid'];
				$rdata = SetAllCheckInfo($bclassid,$classid,$userid,$username);
			}
			elseif($methodName=="DoWfInfo")//签发信息
			{
				$rdata = DoWfInfo($info,$userid,$username);
			}
			elseif($methodName=="DelInfoData")//删除信息页面
			{
				$start=$info['start'];
				$classid=$info['classid'];
				$from=$info['from'];
				$retype=$info['retype'];
				$startday=$info['startday'];
				$endday=$info['endday'];
				$startid=$info['startid'];
				$endid=$info['endid'];
				$tbname=$info['tbname'];
				$rdata = DelInfoData($start,$classid,$from,$retype,$startday,$endday,$startid,$endid,$tbname,$_GET,$userid,$username);
			}
			elseif($methodName=="InfoToDoc")//归档信息
			{
				if($info['ecmsdoc']==1)//栏目
				{
					$rdata = InfoToDoc_class($info,$userid,$username);
				}
				elseif($info['ecmsdoc']==2)//条件
				{
					$rdata = InfoToDoc($info,$userid,$username);
				}
				else//信息
				{
					$rdata = InfoToDoc_info($info,$userid,$username);
				}
			}
			elseif($methodName=="DoInfoAndSendNotice")//处理信息并通知
			{
				$doing=(int)$info['doing'];
				$adddatar=$info;
				if($doing==1)//删除
				{
					$methodName='DelNews';
					$rdata = DelNews($adddatar['id'],$adddatar['classid'],$userid,$username);
				}
				elseif($doing==2)//审核通过
				{
					$methodName='CheckNews_all';
					$doid[0]=$adddatar['id'];
					$rdata = CheckNews_all($adddatar['classid'],$doid,$userid,$username);
				}
				elseif($doing==3)//取消审核
				{
					$methodName='NoCheckNews_all';
					$doid[0]=$adddatar['id'];
					$rdata = NoCheckNews_all($adddatar['classid'],$doid,$userid,$username);
				}
				elseif($doing==4)//转移
				{
					$methodName='MoveNews_all';
					$doid[0]=$adddatar['id'];
					$rdata = MoveNews_all($adddatar['classid'],$doid,$adddatar['to_classid'],$userid,$username);
				}
		  }
		}
		catch(PutInfoException $e)
		{
			$sfi= $e->getMeta();
			if(!empty($sfi))
			{
				if($sfi['T']=='F')
				{
					//返回失败信息：
					$rdata = array('fault'=>(array('faultCode'=>$sfi['L'],'faultString'=>$e->getMsgBody())));
				}
				elseif($sfi['T']=='S')
				{
					$rdata =  array('params'=>array('data'=>$sfi['D'],'info'=>$e->getMsgBody()));
				}
				else 
				{
					$rdata = array('fault'=>(array('faultCode'=>$sfi['L'],'faultString'=>$e->getMsgBody())));//也归类为失败
				}
			}
			else 
			{
				//没有消息头，处置为失败:
				$rdata = array('fault'=>(array('faultCode'=>-128,'faultString'=>$e->getMsgBody())));
			}
			
		}
		//catch(Exception $ev){DebugVar($ev);}
		if(!$curSilent) MsgCore::ScreenSilent(FALSE);//恢复静默模式
		//没有抛出异常的情况，正常返回结果：
		if(is_bool($rdata))
		{
			if($rdata)
				$rdata =  array('params'=>array('data'=>1));
			else 
				$rdata = array('fault'=>(array('faultCode'=>-127)));
		}
		else 
		{
			if(is_string($rdata))//如果是消息头
			{
				if(MsgCore::IsContainInfoHeader($rdata))
				{
					$msgbody='';
					$sfi = MsgCore::ParseInfoHeader($rdata,TRUE,$msgbody);
					if(!empty($sfi))
					{
						if($sfi['T']=='F')
						{
							//返回失败信息：
							$rdata = array('fault'=>(array('faultCode'=>$sfi['L'],'faultString'=>$msgbody)));
						}
						elseif($sfi['T']=='S')
						{
							$rdata =  array('params'=>array('data'=>$sfi['D'],'info'=>$msgbody));
						}
						else
						{
							$rdata = array('fault'=>(array('faultCode'=>$sfi['L'],'faultString'=>$msgbody)));//也归类为失败
						}
					}
					else 
						$rdata = array('fault'=>(array('faultCode'=>-126,'faultString'=>$msgbody)));//归类为失败
				}
				else
					$rdata = array('fault'=>(array('faultCode'=>-126,'faultString'=>$msgbody)));//归类为失败
				
			}
			else 
			{
				if(is_array($rdata))
				{
					//已经是数组型的消息头：
					if(!isset($rdata['params']) && !isset($rdata['fault']) && isset($rdata['T']))
					{
						$sfi = $rdata;						
						if($sfi['T']=='F')
						{
							//返回失败信息：
							$rdata = array('fault'=>(array('faultCode'=>$sfi['L'],'faultString'=>$msgbody)));
						}
						elseif($sfi['T']=='S')
						{
							$rdata =  array('params'=>array('data'=>$sfi['D'],'info'=>$msgbody));
						}
						else
						{
							$rdata = array('fault'=>(array('faultCode'=>$sfi['L'],'faultString'=>$msgbody)));//也归类为失败
						}
					}
				}
			}
		}
		return $rdata;
		
    }
}

/**
 * 分发包装器基类
 * @author LGM 2014年2月24日10:25
 *
 */
class DistributeWrapper
{
	/**
	 * 原始输入数据
	 * @var object
	 */
	protected $originalData;
	/**
	 * 处理后的数据
	 * @var object
	 */
	protected $finalData;
	/**
	 * 包装器格式
	 * @var string
	 */
	protected $wrapType='';
	/**
	 * 包装根节点的名称
	 * @var string
	 */
	protected $wrapRootName='';
	/**
	 * 执行者
	 * @var object
	 */
	protected $activer;
	function __construct($data,$wraprootname='')
	{
		$this->wrapRootName=$wraprootname;
		//初始化
		if(!isset($data))
		{
			$data= array();
			if(!empty($_POST))$data= $_POST;
			if(!empty($_GET))$data= array_merge($data,$_GET);
			
		}
		if(!is_array($data))
		{
			if(is_scalar($data))
			{
				$data = array('data'=>$data);
			}
			else 
			{
				if(is_object($data))
				{
					$data = (array)$data;
				}
				else
					$data = array('data'=>strval($data));
			}
		}
	}
	function __destruct(){
		;
	}
	/**
	 * 接受外部输入
	 * @param object $data
	 */
	public function Input($data)
	{
		$this->originalData=$data;
		$this->finalData = $data;
	}
	/**
	 * 结果输出
	 * @param 输出的数据格式
	 * @return object
	 */
	public function Output($format,$otherarg)
	{
		return $this->finalData;
	}
	/**
	 * 在多维数组中查找指定的键
	 * @param string $find 要查找的键
	 * @param array $src_array
	 * @return array|NULL
	 */
	public static  function &Find_array_key($find,array &$src_array)
	{
		if (isset($src_array[$find]))
		{
			return $src_array;
		}
		else
		{
			foreach ($src_array as $key => $value)
			{
				if (is_array($value))
				{
					return self::Find_array_key($find, $value);
				}
			}
		}
		return NULL;
	}
	/**
	 * 将对象转换为多维数组
	 *
	 **/
	public static function ObjectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object with get_object_vars function
			$d = get_object_vars($d);
		}
		if (is_array($d)) {
			/*
			 * Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map('self::'.__FUNCTION__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}
	
	
}
/**
 * XML包装器
 * @version 1.0
 * @author LGM
 *
 */
class XMLWarpper extends DistributeWrapper
{
	/**
	 * XML包装器
	 * @param object $data
	 * @param string $xmlrootname XML根节点的名称
	 */
	function __construct($data,$wraprootname='methodRequest')
	{		
		$this->wrapType='XML';	
		$this->wrapRootName=$wraprootname;
		if(empty($this->wrapRootName))
			$this->wrapRootName = 'methodRequest';
		$this->activer = new DOMDocument('1.0','UTF-8');
		$this->activer->preserveWhiteSpace= FALSE;
		$this->Input($data);
	}
	function __destruct(){
	
	}
	/**
	 * 接受外部输入,转化成DOMDocument
	 * @param object $data
	 */
	public function Input($data)
	{		
		if(!empty($data) && $data==$this->originalData)
			 return;
		$this->originalData=$data;		
		if(!isset($data))
		{
			$data= array();
			if(!empty($_POST))$data= $_POST;
			if(!empty($_GET))$data= array_merge($data,$_GET);
		}
		$activerIni=FALSE;		
		if($data instanceof DOMDocument)
		{
			$this->activer=$data;
		    $activerIni=TRUE;
		}
		else 
		{
			if($data instanceof DOMElement)
			{
				$this->activer = $data->ownerDocument;
				$activerIni=TRUE;
			}
			elseif($data instanceof SimpleXMLElement)
			{
				$domele = dom_import_simplexml($data);
				if($domele)
					$this->activer = $domele->ownerDocument;
				if($this->activer)
				  $activerIni=TRUE;
			}
		}
		if( !empty($data)  && is_string($data))
		{
			$taga = strpos($data,'<');
			$tagb = strpos($data,'>',2);
			if($taga!==FALSE && $tagb>$taga)
			{
				
			    if( $this->activer->loadXML($data)==FALSE)
				{
					$sxml= simplexml_load_string($data);
					if($sxml)
					{
						$this->activer = dom_import_simplexml($sxml);
						$activerIni=TRUE;
					}
					elseif($this->activer->loadHTML( $data))
					{
					   $activerIni = true;
					   // dirty fix
					   foreach ($this->activer->childNodes as $item)
					   {
						   if ($item->nodeType == XML_PI_NODE || $item->nodeType == XML_DOCUMENT_TYPE_NODE || $item->nodeType == XML_COMMENT_NODE  || $item->nodeType == XML_DOCUMENT_FRAG_NODE )
						   {
						   	  $this->activer->removeChild($item); // remove XML非内容节点						
						   }
					   }

					}
				}
				else
				{
					$activerIni=TRUE;				
				}
			}			
			if(!$activerIni)
			{
				$data = array('data'=>$data);
			}
		}
		if(!$activerIni && !is_array($data))
		{
			if(is_scalar($data))
			{
				$data = array('data'=>$data);
			}
			else
			{
				if(is_object($data))
				{
					$data = (array)$data;
				}
				else
					$data = array('data'=>strval($data));
			}
		}		
		if(!$activerIni && is_array($data))
		{
			//把数组转化成DOM			
			$wa=DistributeWrapper::Find_array_key($this->wrapRootName, $data);//只取有效部份			
			if($wa==NULL) $wa = array($this->wrapRootName=>$data);			
			$this->activer = Array2XML::createXML($this->wrapRootName, $wa[$this->wrapRootName]);
		}
		//选取[wrapRootName]节点			
		if($this->activer && $this->activer->childNodes->length>0)
		{
			if($this->activer->childNodes->item(0)->nodeName!=$this->wrapRootName)//只要第一个是指定节点名,允许附加其它节点
			{
				$this->activer->preserveWhiteSpace= FALSE;
				$xpath = new DOMXPath($this->activer);
				$xpathq= '//'.$this->wrapRootName.'[node()][1]';
				$xxxmlrootname=strtolower($this->wrapRootName);
				if($xxxmlrootname != $this->wrapRootName)//注意大小写
					$xpathq.='|//'.$xxxmlrootname.'[node()][1]';
				$xqr=$xpath->query($xpathq);
				$mrequest = NULL;//DOMNode 类型
				if($xqr->length>0)
				{			
					$mrequest = $xqr->item(0);	
					$this->activer = new DOMDocument('1.0', 'UTF-8');//清空
					$this->activer->preserveWhiteSpace= FALSE;
					$domnode = $this->activer->importNode($mrequest, TRUE);
					$this->activer->appendChild($domnode);
				}
				else 
				{
					//用[wrapRootName]包裹: 
					$lists = $this->activer->documentElement;
					$newcld=$this->activer->appendChild(new DOMElement($this->wrapRootName));//XML_ELEMENT_NODE 
					$newcld->appendChild($lists);//这是移动 			
				}
			}
			
		}	
		
	}
	/**
	 * 输出结果
	 * @param 输出的数据格式
	 * @return XML|Array|JSON
	 */
	public function Output($format,$putHeader=FALSE)
	{
		if(empty($format))
			$format=$this->wrapType;
		if($this->activer)
		{
			switch(strtolower($format))
			{
				case 'xml':
					 $this->finalData = $this->activer;
					 if($putHeader) header("Content-type:application/xml;charset=utf-8");
					break;
				case 'array':
					$this->finalData = XML2Array::createArray($this->activer);
					break;
				case 'json':
					$tar = XML2Array::createArray($this->activer);
					$this->finalData = json_encode($tar);
					if($putHeader) header("application/json;charset=utf-8");
					break;
				default:
					$this->finalData = NULL;
					break;					
			}			
		}		
		return $this->finalData;
	}
} 
/**
 * JSON包装器
 * @version 1.0
 * @author LGM
 *
 */
class JSONWarpper extends DistributeWrapper
{
	function __construct($data,$wraprootname='')
	{
		$this->wrapType='JSON';
		$this->wrapRootName=$wraprootname;
		if(!isset($data))
		{
			$data= array();
			if(!empty($_POST))$data= $_POST;
			if(!empty($_GET))$data= array_merge($data,$_GET);				
		}
		$this->Input($data);
	}
	function __destruct(){
		;
	}
	/**
	 * 加壳与脱壳
	 * @param DOMDocument $data
	 * @return DOMDocument
	 */
	private function &toWrap(DOMDocument &$data)
	{
		if(empty($this->wrapRootName) || $data==NULL || $data->childNodes->length==0)
			return $data;
		if($data->childNodes->item(0)->nodeName==$this->wrapRootName)//直接返回,包括附加的数据
			return $data;
		$xpath = new DOMXPath($data);
		$xpathq= '//'.$this->wrapRootName.'[node()][1]';
		$xxxmlrootname=strtolower($this->wrapRootName);
		if($xxxmlrootname != $this->wrapRootName)//注意大小写
			$xpathq.='|//'.$xxxmlrootname.'[node()][1]';
		$xqr=$xpath->query($xpathq);
		$mrequest = NULL;//DOMNode 类型
		if($xqr->length>0)
		{
			$mrequest = $xqr->item(0);
			$data = new DOMDocument('1.0', 'UTF-8');//清空
			$data->preserveWhiteSpace= FALSE;
			$domnode = $data->importNode($mrequest, TRUE);
			$data->appendChild($domnode);
		}
		else
		{
			//用[wrapRootName]包裹:
			$lists = $data->documentElement;
			$newcld=$data->appendChild(new DOMElement($this->wrapRootName));//XML_ELEMENT_NODE
			$newcld->appendChild($lists);//这是移动
		}
		return $data;
	}

	
	/**
	 * 接受外部输入(不会进行加壳处理),保存为json格式
	 * @param array $data
	 */
	public function Input($data)
	{
		if(!empty($data) && $data==$this->originalData)
			return;
		if(is_string($data))
		{
			$taga = strpos($data,'<');
			$tagb = strpos($data,'>',2);
			if($taga!==FALSE && $tagb>$taga)
			{
				$Xml_Parse = new DOMDocument('1.0', 'UTF-8');
				$Xml_Parse->preserveWhiteSpace= FALSE;
				if($Xml_Parse->loadXML($data)==FALSE)
				{
					$sxml= simplexml_load_string($data);
					if($sxml)
					{
						$Xml_Parse = dom_import_simplexml($sxml);
						if($Xml_Parse)
							$Xml_Parse = $Xml_Parse->ownerDocument;
					}
					elseif($Xml_Parse->loadHTML($data))
					{
						// dirty fix
						foreach ($Xml_Parse->childNodes as $item)
						{
							if ($item->nodeType == XML_PI_NODE || $item->nodeType == XML_DOCUMENT_TYPE_NODE || $item->nodeType == XML_COMMENT_NODE  || $item->nodeType == XML_DOCUMENT_FRAG_NODE )
							{
								$Xml_Parse->removeChild($item); // remove XML非内容节点
							}
						}
				
					}
				}
				if($Xml_Parse)
				{					
					if(empty($this->wrapRootName))
						$data= XML2Array::createArray(self::toWrap($Xml_Parse));
					else 
						$data= self::toWrap($Xml_Parse);
				}
			}
		}
		if(!empty($this->wrapRootName) && $data!==NULL && $data!=='')
		{
			if(is_array($data))
			{
				$td=DistributeWrapper::Find_array_key($this->wrapRootName,$data);
				if($td!=NULL)
					$data = $td;//允许其它同级附加节点存在
				else 
				{
					$dv = array($this->wrapRootName=>$data);
					$data = $dv;
				}
			} //如果为XML格式,则转化为array:
			elseif($data instanceof DOMDocument)
			{
				$arr= XML2Array::createArray($data);
				return  $this->Input($arr);
			}
			elseif($data instanceof SimpleXMLElement)
			{
				$domele = dom_import_simplexml($data)->ownerDocument;
				$arr= XML2Array::createArray($domele);
				return  $this->Input($arr);
			}
			elseif(is_string($data))
			{
				//是否为JSON格式?
				$jsontest= json_decode($data);
				if($jsontest!==NULL)
				{
					return  $this->Input($jsontest);//递归
				}
			}			
		}
		$this->activer = NULL;
		//是否为JSON格式?
		if(is_string($data))
		{
		  $jsontest= json_decode($data);
		  if($jsontest!==NULL)
		  {
		  	$this->activer = $data;
		  }
		}
		if($this->activer === NULL)
		{
			$this->activer = json_encode($data);
		}
	}
	/**
	 * 输出结果(进行加壳处理)
	 * @param 输出的数据格式
	 * @return JSON|XML|Array
	 */
	public function Output($format,$putHeader)
	{
		if(empty($format))
			$format=$this->wrapType;
		$this->finalData = NULL;
		if($this->activer)
		{
			switch(strtolower($format))
			{
				case 'json':
					$this->finalData =$this->activer;					
					if($putHeader) header("application/json;charset=utf-8");
					break;
				case 'xml':					
					$txml=json_decode($this->activer);
					if($putHeader) header("Content-type: application/xml;charset=utf-8");					
					if($txml instanceof DOMDocument)
						$this->finalData = $this->toWrap($txml);
					elseif($txml instanceof SimpleXMLElement)
					{
						$domele = dom_import_simplexml($txml);
						$this->finalData = $this->toWrap($domele);
					}
					else
					{
						$tarr=json_decode($this->activer,TRUE);
						if(!is_array($tarr))//为标量
						{
							$mkey = $this->wrapRootName;
							if(empty($mkey)) $mkey = 'data';
							$tarr = array($mkey=>$tarr);
						}	
					    else//为数组
					    {
					    	if(!empty($this->wrapRootName))
						    {
						    	$mv = DistributeWrapper::Find_array_key($this->wrapRootName,$tarr);
						    	if($mv!=NULL)
						    	{
						    		$tarr = $mv;
						    	}
						    	else 
						    		$tarr = array($this->wrapRootName=>$tarr);
						    	if(is_object($tarr[$this->wrapRootName]) && !is_array($tarr[$this->wrapRootName]))
						    	{
						    		$tarr[$this->wrapRootName] = DistributeWrapper::ObjectToArray($tarr[$this->wrapRootName]);
						    	}						    	
						    }
						    else 
						    {
						    	if(count($tarr)>1)
						    	{
						    		$tarr = array('data'=>$tarr);
						    	}
						    }
					    }
					    //已经转化成数组了:
						$keys = array_keys($tarr);
						if(count($keys)==1)
						{
							if(!is_array($tarr[$keys[0]]))
							{
								//创建XML:
								$data = new DOMDocument('1.0', 'UTF-8');//清空
								$data->preserveWhiteSpace= FALSE;
								$data->appendChild($data->createElement($keys[0],strval($tarr[$keys[0]])));
								$this->finalData = $data;
							}
							else 
							{
								$this->finalData =  Array2XML::createXML($keys[0],$tarr[$keys[0]]);
							}
						}
						else
						{	
							if(!empty($this->wrapRootName))
							{
								if(isset($tarr[$this->wrapRootName]))
								{
									$this->finalData =  Array2XML::createXML($this->wrapRootName,$tarr[$this->wrapRootName]);
								}
								else 
									$this->finalData =  Array2XML::createXML($this->wrapRootName,$tarr);
							}
							else								    
							   $this->finalData = Array2XML::createXML('data',$tarr);
						}
					}
					break;
				case 'array':
					$mp= json_decode($this->activer);
					if($mp instanceof DOMDocument)
					{
						$this->finalData = $this->toWrap(XML2Array::createArray($mp));
					}
					elseif($mp instanceof SimpleXMLElement)
					{
						$domele = dom_import_simplexml($mp);
						$this->finalData = $this->toWrap(XML2Array::createArray($domele));						
					}
					else
					{
						$this->finalData =json_decode($this->activer,TRUE);
						if($this->finalData instanceof DOMDocument)
						{
							$this->finalData = XML2Array::createArray($this->finalData);
						}
						elseif($this->finalData instanceof SimpleXMLElement)
						{
							$domele = dom_import_simplexml($this->finalData);
							$this->finalData = XML2Array::createArray($domele);
						}
						if(is_array($this->finalData))
						{
							if(!empty($this->wrapRootName) )
							{
								if(isset($this->finalData[$this->wrapRootName]))
								   $this->finalData = $this->finalData[$this->wrapRootName];
								else 
								{
									$tempa = array($this->wrapRootName =>$this->finalData);
									$this->finalData = $tempa;
								}
							}
						}
						else 
						{
							if(!empty($this->wrapRootName))
							  $this->finalData = array($this->wrapRootName =>$this->finalData);
							else 
							   $this->finalData = array('data' =>$this->finalData);
						}
					}
					break;
				default:
					$this->finalData = json_decode($this->activer);//不加壳
					break;
			}
				
		}
		return $this->finalData;

	}
}
