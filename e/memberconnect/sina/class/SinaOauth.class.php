<?php

//use SinaTranSrv;

include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'eibase.php' );
include_once(SINACLASS_PATH.'SinaTranSrv.class.php');
include_once(SINACLASS_PATH.'SinaAction.class.php');
/**
 * 新浪微博 OAuth 认证类(OAuth2)
 * 授权机制说明请大家参考微博开放平台文档：{@link http://open.weibo.com/wiki/Oauth2}
 *
 * @author LGM
 * @version 1.0
 */
class SinaOAuth extends EOAuth {

	/**
	 * @ignore
	 */
	public $refresh_token;
	/**
	 * Set up the API root URL.
	 *
	 * @ignore
	 */
	public $host = "https://api.weibo.com/2/";
	/**
	 * Set timeout default.
	 *
	 * @ignore
	 */
	public $format = 'json';
	/**
	 * Decode returned json data.
	 *
	 * @ignore
	 */
	public $decode_json = TRUE;
	/**
	 * 授权回调地址
	 * @var string
	 */
	public $call_back_url;

	const GET_TOKEN_INF_URL='https://api.weibo.com/oauth2/get_token_info';
	const GET_UID_URL='https://api.weibo.com/2/account/get_uid.json';
 /**
	 * 重载构造函数 
	 * @param array $params  : array("appkey","appsecret","TranSrv","access_token","refresh_token","callbackurl"),array("应用号","应用密钥","访问令牌","更新的令牌")|array('id')  	
	 */
     private function construct2(array &$params) {
        if (empty($params)) {
            parent::__construct();
            return;
        }
        $myps = $params;
        $this->_tranSrv = TranSrv::CreateFromParams($params);        
          if(!empty($this->_tranSrv)){
              $myps = $this->_tranSrv->ConvertParam($params, __FUNCTION__); 
                      $this->_appname = $this->_tranSrv->GetAppName();
          }         
        if (count($myps) != 1) {
            if (isset($myps ['appkey']) && isset($myps ['appsecret'])) {
                return $this->__construct(
                        $myps ['appkey'], 
                        $myps ['appsecret'],
                        (isset($myps ['TranSrv']) ? $myps ['TranSrv'] : $this->_tranSrv),
                        (isset($myps ['access_token']) ? $myps ['access_token'] : NULL),
                        (isset($myps ['callbackurl']) ? $myps ['callbackurl'] : NULL), 
                        (isset($myps ['refresh_token']) ? $myps ['refresh_token'] : NULL)
                );
            }
        }
        elseif (isset($myps['id'])) {
            return $this->__construct((int)$myps['id']);
        }
    }
    
 
    /**
	 * 构造新浪微博验证类
	 * @param object $appkey_aps 当只有$appkey_aps一个参数而且为int时，表示为此授权的ID,当只有$appkey_aps一个参数而且为array时此项为array("应用号","应用密钥","访问令牌","更新的令牌")
	 * @param string $appsecret
         * @param SinaTranSrv  $tranSrvObj 转换翻译对象 
        * @param string $access_token  如果为空，又已经登录而且绑定了第三方交互的情况下，自动到数据库获取对应的access_token
	 * @param string $callbackurl 授权回调地址
	 * @param string $refresh_token 刷新令牌号
	 */
  function __construct($appkey_aps, $appsecret= NULL,SinaTranSrv $tranSrvObj = NULL, $access_token = NULL,$callbackurl=NULL, $refresh_token = NULL) {
		if(func_num_args()==1 && is_array($appkey_aps))
			return $this->construct2($appkey_aps);
                $this->call_back_url = $callbackurl;
                $this->appkey = $appkey_aps;
                $this->appsecret = $appsecret;
                if($tranSrvObj != $this->_tranSrv)
                   $this->_tranSrv = $tranSrvObj;                
		$this->_authorizeUrl='https://api.weibo.com/oauth2/authorize';
		$this->_accessTokenURL='https://api.weibo.com/oauth2/access_token';
		$this->_revokeoauthUrl='https://api.weibo.com/oauth2/revokeoauth2';

               $this->_EiService = new EIService("Sae T OAuth2 v0.1"); 
                $lparams = array();
                if(func_num_args()==1 && is_int($appkey_aps))
                {
                    $lparams['id'] = $appkey_aps;
                    $this->LoadData($lparams);
                }
		else
                {
                    if(!empty($appkey_aps)) $lparams['appkey'] = $appkey_aps;
                    if(!empty($appsecret)) $lparams['appsecret'] = $appsecret;
                    if(!empty($access_token)) $lparams['access_token'] = $access_token;
                    if(!empty($refresh_token)) $lparams['refresh_token'] = $refresh_token;   
                    $gr = $this->LoadData($lparams);
                    if(!$gr)
                    {
                        $this->appkey = $appkey_aps;
                        $this->appsecret = $appsecret;
                    }
                    if(!empty($access_token))  $this->access_token = $access_token;
                    if(!empty($refresh_token))  $this->refresh_token = $refresh_token;
                }
                if (empty($this->_tranSrv)) {
                    $tsp = array('AppName' => $this->GetAppName());
                    $this->_tranSrv = new SinaTranSrv($tsp);
                }
                if(!$this->userid) $this->userid = intval(getcvar('mluserid',0,TRUE));                
		if(empty($this->access_token) && empty($this->refresh_token))
		{
			if($this->userid)//查找已经绑定access_token：				
				$this->access_token = EIService::GetAccessTokenForUID($this->userid, $this->GetAID());		
		} 
               if(empty($this->remote_ip))  $this->remote_ip=EIService::GetIP();
	}
 
	/**
	 * authorize接口,用于获取Authorization Code
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/Oauth2/authorize Oauth2/authorize}
	 *
	 * @param string $param['redirect_uri'] 授权后的回调地址,站外应用需与回调地址一致,站内应用需要填写canvas page的地址
	 * @param string $param['response_type'] 支持的值包括 code 和token 默认值为code
	 * @param string $param['state'] 用于保持请求和回调的状态。在回调时,会在Query Parameter中回传该参数
	 * @param string $param['display'] 授权页面类型 可选范围:
	 *  - default		默认授权页面
	 *  - mobile		支持html5的手机
	 *  - popup			弹窗授权页
	 *  - wap1.2		wap1.2页面
	 *  - wap2.0		wap2.0页面
	 *  - js			js-sdk 专用 授权页面是弹窗，返回结果为js-sdk回掉函数
	 *  - apponweibo	站内应用专用,站内应用不传display参数,并且response_type为token时,默认使用改display.授权后不会返回access_token，只是输出js刷新站内应用父框架
         *  @param bool $param['forcelogin'] 是否强制弹出登录窗口
         *  @param string $param['state'] 自定义的验证码.(不提供则自动生成)
	 * @return string
	 */
	public function AuthorizeURL(array &$param=NULL){
		if(isset($param))
		{
			$params = array();
                      if(isset($this->_tranSrv)){
                          $params = $this->GetTranSrv()->ConvertParam($param,__FUNCTION__);
                         }	
			$params['response_type'] = $param['response_type'];
			$params['display'] = $param['display'];
			if(empty($param['response_type']))$params['response_type'] ='code';
			if(empty($param['client_id']))$params['client_id'] = $this->appkey;
			else $params['client_id'] = $param['client_id'];
			if(empty($param['redirect_uri'])==FALSE)$params['redirect_uri'] = $param['redirect_uri'];
			else  $params['redirect_uri'] =  $this->call_back_url;
			if(empty($param['scope'])==FALSE) $params['scope'] =  $param['scope'];
			if(empty($param['forcelogin'])==FALSE) $params['forcelogin'] =  (((bool)$param['forcelogin'])?'true':'false');//必须是true 或者 false
			if(empty($param['language'])==FALSE) $params['language'] = $param['language'];
			if(empty($param['state'])==FALSE) $params['state'] = $param['state'];
                         else {
                            $params['state'] =  md5(uniqid(rand(), TRUE));
                            if(isset($_SESSION)==FALSE) session_start ();
                            $_SESSION['state'] = $params['state'];
                        }
			return $this->_authorizeUrl . ((strpos($this->_authorizeUrl,'?')===FALSE)?'?':'&') . http_build_query($params);
		}
		return NULL;
		 
	}
	/**
	 * access_token接口(获取AccessToken)
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/OAuth2/access_token OAuth2/access_token}
	 *
	 * @param string $param['type'] 请求的类型,可以为:code, password, token
	 * @param array $param['keys'] 其他参数：
	 *  - 当$param['type']为code时： array('code'=>..., 'redirect_uri'=>...)
	 *  - 当$param['type']为password时： array('username'=>..., 'password'=>...)
	 *  - 当$param['type']为token时： array('refresh_token'=>...)
	 * @return array : array("access_token","expires_in","remind_in","uid",'refresh_token');
         * @throws EiException 如果出错,则抛出异常
	 */
        public function AccessToken(array &$param = NULL) {
        if (!isset($param) || !isset($param['keys']))
            throw new EiException("缺少必要的参数");
        if (isset($param['keys']['state'])) {
            if (!isset($_SESSION))
                session_start();
            if (!empty($_SESSION['state']) && $_SESSION['state'] != $param['keys']['state']) {
                throw new EiException('执行' . __FUNCTION__ . '时发现疑似CSRF攻击,请检查!');
            }
        }
        $params = array();
        $type = 'code';
        if (isset($param['type']))
            $type = $param['type'];
        $params['client_id'] = $this->appkey;
        $params['client_secret'] = $this->appsecret;
        if ($type === 'token') {
            $params['grant_type'] = 'refresh_token';
            $params['refresh_token'] = $param['keys']['refresh_token'];
        }
        elseif ($type === 'code') {
            $params['grant_type'] = 'authorization_code';
            $params['code'] = $param['keys']['code'];
            $params['redirect_uri'] = $param['keys']['redirect_uri'];
        }
        elseif ($type === 'password') {
            $params['grant_type'] = 'password';
            $params['username'] = $param['keys']['username'];
            $params['password'] = $param['keys']['password'];
        }
        else {
            throw new EiException("wrong auth type");
        }
        $response = $this->oAuthRequest($this->_accessTokenURL, 'POST', $params);
        $token = json_decode($response, true);
        if (is_array($token) && !isset($token['error_code'])) {
            $this->access_token = $token['access_token'];
            if (!empty($token['uid']))
                $this->openid = strval($token['uid']);
            if (!empty($token['refresh_token']))
                $this->refresh_token = $token['refresh_token'];
            if (!empty($token['expires_in']))
                $this->expired = intval($token['expires_in']);
        } else {
            throw new EiException("get access token failed." . $token['error'], $token['error_code']);
        }
        return $token;
    }

    /**
	 * 返回指定access_token取消授权的URL
	 * @param array $params 参数，必须包含access_token键
	 */
	public function RevokeoauthURL(array &$params=NULL){
		if(isset($params['access_token'])==FALSE)
			return $this->_revokeoauthUrl;
		else
			return $this->_revokeoauthUrl. ((strpos($this->_revokeoauthUrl,'?')===FALSE)?'?':'&') . http_build_query($params);
	}
	/**
	 * 取消指定access_token的授权(会从本地数据库中删除指定授权)
	 * @param array $params 参数，CheckUser键(在执行之后会被清除)表示是否检测为当前用户,否则不执行解除
	 */
	public function Revokeoauth(array $params = NULL) {
        if (empty($params))
            $params = array();
        if(isset($this->_tranSrv)){
                 $params = $this->GetTranSrv()->ConvertParam($params,__FUNCTION__);
             }	
        $isme = FALSE;
        if (empty($params['access_token'])) {
            $params['access_token'] = $this->access_token;
            $isme = TRUE;
        }
        else {
            if ($this->access_token == $params['access_token'])
                $isme = TRUE;
        }
        if (empty($params['access_token']))
            return FALSE;
        if (isset($params['CheckUser']) && ((bool)$params['CheckUser']) && $this->userid) {
            $guser = getcvar('mluserid', 0, TRUE);
            if ($guser != $this->userid)
                return FALSE; //不是当前用户,不允许解除                   
        }
        if (isset($params['CheckUser']))
            unset($params['CheckUser']);
        $pdata = http_build_query($params);
        $headers = array();
        $headers[] = "Authorization: OAuth2 " . $params['access_token'];
        $response = EiService::http($this->_revokeoauthUrl, 'POST', $pdata, $headers,$this->_EiService);
        $r = json_decode($response, true);
        if (is_array($r) || is_object($r)) {
            if (isset($r['result'])) {
                if ($isme)
                    $this->DeleteTokenOnData();
            }
            return (bool)$r['result'];
        }
        return FALSE;
    }

    /**
	 * 获取OpenID(包括access_token)的信息
	 * @param array $params 参数,必须包含access_token键
	 * @return array :array("openid","appkey","scope","create_at","expire_in","access_token")
	 */
	public function GetOpenIdInfo(array &$params=NULL) {
		if(isset($params)===FALSE) return null;
		if(empty($params['access_token'])) return NULL;
		$pdata = http_build_query($params);
		$headers = array();
		$headers[] = "Authorization: OAuth2 ".$params['access_token'];
		$response = EiService::http(self::GET_TOKEN_INF_URL, 'POST',$pdata,$headers,$this->_EiService);
		$info = json_decode($response, true);
		if(!empty($info) && !isset($info['error']))
		{
			$info['access_token']=$params['access_token'];
                        $info['openid']=$info['uid'];
                        unset($info['uid']);
                        if($params['access_token']==$this->access_token)
                        {
                            if(empty($info['expire_in'])) 
                                $this->expired =intval($info['expire_in'])+intval($info['create_at']); 
                            $this->openid = strval($info['openid']);
                            if(isset($info['scope'])) $this->scope = $info['scope'];                            
                        }
		}
		return $info;
	}
	/**
	 * 获取第三方交互平台用户的用户信息
	 * @param string $access_token 访问令牌
	 * @param int $openid 第三方用户ID
	 * @return array|NULL ,包含[用户名]
	 */
	public function GetEiUserInfo($access_token=NULL,$openid=0)
	{
		if(empty($access_token))$access_token=$this->access_token;
		if(empty($openid)) $openid=$this->GetOpenID();
		$pui = array('access_token'=>$access_token,'uid'=>$openid);
		$uinfo = $this->get('users/show', $pui);
		if((isset($uinfo)) && (is_array($uinfo)) && !isset($uinfo['error_code']))
                {
                   $uinfo['用户名'] = $uinfo['name'];
                   return $uinfo;
                }
		return NULL;
	}

/**
* 取得本授权的第三方平台的ID(用户的OPENID)
*/
public function GetOpenID()  {
        if (!empty($this->openid))
            return $this->openid;
        else
        {
            if (!empty($this->access_token))
            {
                $pv = EIService::IndexTokenInfo($this->GetAID(), $this->appkey, $this->access_token);
                if (!empty($pv))
                {
                    $this->openid = strval($pv['openid']);
                    if(empty($this->refresh_token))$this->refresh_token= $pv['refresh_token'];
                    if(empty($this->scope))$this->scope= $pv['scope'];
                    if(empty($this->expired))$this->expired= $pv['expired'];
                    if(empty($this->id))$this->id= $pv['id'];
                    if(empty($this->aid))$this->aid= $pv['aid'];
                    if(empty($this->apptype))$this->apptype= $pv['apptype'];
                    return $this->openid;
                } else //如果找不到,就从远程平台获取
                {
                    $params = array('access_token' => $this->access_token);
                    $rd =  $this->GetOpenIdInfo($params);                   
                    return $rd['openid'];
                }
            }
            return NULL;
        }
    }

    /**
* 解析 signed_request
*
* @param string $signed_request 应用框架在加载iframe时会通过向Canvas URL post的参数signed_request
*
* @return array
*/
function parseSignedRequest($signed_request) {
list($encoded_sig, $payload) = explode('.', $signed_request, 2);
$sig = self::base64decode($encoded_sig) ;
	$data = json_decode(self::base64decode($payload), true);
	if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') return '-1';
	$expected_sig = hash_hmac('sha256', $payload, $this->appsecret, true);
		return ($sig !== $expected_sig)? '-2':$data;
}

/**
* @ignore
*/
	function base64decode($str) {
	return base64_decode(strtr($str.str_repeat('=', (4 - strlen($str) % 4)), '-_', '+/'));
}
	
	/**
	 * 读取jssdk授权信息，用于和jssdk的同步登录
	 *
	 * @return array 成功返回array('access_token'=>'value', 'refresh_token'=>'value'); 失败返回false
	 *        
	 */
	function getTokenFromJSSDK() {
		$key = "EI" . $this->GetOpenID ();
		if (isset ( $_COOKIE [$key] ) && $cookie = $_COOKIE [$key]) {
                        $v = MsgCore::Decrypt($cookie);//解密
                        $token = NULL;
			parse_str ( $v, $token );
			if (isset ( $token ['access_token'] ) && isset ( $token ['refresh_token'] )) {
				$this->access_token = $token ['access_token'];
				$this->refresh_token = $token ['refresh_token'];
				return $token;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * 从数组中读取access_token和refresh_token
	 * 常用于从Session或Cookie中读取token，或通过Session/Cookie中是否存有token判断登录状态。
	 *
	 * @param array $arr
	 *        	存有access_token和secret_token的数组
	 * @return array 成功返回array('access_token'=>'value', 'refresh_token'=>'value'); 失败返回false
	 */
	function getTokenFromArray($arr) {
		if (isset ( $arr ['access_token'] ) && $arr ['access_token']) {
			$token = array ();
			$this->access_token = $token ['access_token'] = $arr ['access_token'];
			if (isset ( $arr ['refresh_token'] ) && $arr ['refresh_token']) {
				$this->refresh_token = $token ['refresh_token'] = $arr ['refresh_token'];
			}
			
			return $token;
		} else {
			return false;
		}
	}
	
	/**
	 * GET wrappwer for oAuthRequest.
	 *
	 * @return mixed
	 */
	function get($url, $parameters = array()) {
		$response = $this->oAuthRequest ( $url, 'GET', $parameters );
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode ( $response, true );
		}
		return $response;
	}
	
	/**
	 * POST wreapper for oAuthRequest.
	 *
	 * @return mixed
	 */
	function post($url, $parameters = array(), $multi = false) {
		$response = $this->oAuthRequest ( $url, 'POST', $parameters, $multi );
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode ( $response, true );
		}
		return $response;
	}
	
	/**
	 * DELTE wrapper for oAuthReqeust.
	 *
	 * @return mixed
	 */
	function delete($url, $parameters = array()) {
		$response = $this->oAuthRequest ( $url, 'DELETE', $parameters );
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode ( $response, true );
		}
		return $response;
	}
	
	/**
	 * Format and sign an OAuth / API request
	 *
	 * @return string
	 * @ignore
	 *
	 */
	public function oAuthRequest($url, $method, $parameters, $multi = false) {
		if (strrpos ( $url, 'http://' ) !== 0 && strrpos ( $url, 'https://' ) !== 0) {
			$url = "{$this->host}{$url}.{$this->format}";
		}
		switch ($method) {
			case 'GET' :
				$url = $url . ((strpos($url,'?')===FALSE)?'?':'&') . http_build_query ( $parameters );
                                $hearr = array();
				return EiService::http ( $url, 'GET',NULL,$hearr,$this->_EiService );
			default :
				$headers = array ();
				if (isset ( $this->access_token ) && $this->access_token)
					$headers [] = "Authorization: OAuth2 " . $this->access_token;
                                        $rip = $this->GetIP();
				if (! empty ( $rip )) {
					if (defined ( 'SAE_ACCESSKEY' )) {
						$headers [] = "SaeRemoteIP: " . $rip;
					} else {
						$headers [] = "API-RemoteIP: " . $rip;
					}
				} else {
					if (! defined ( 'SAE_ACCESSKEY' )) {
						$headers [] = "API-RemoteIP: " . $_SERVER ['REMOTE_ADDR'];
					}
				}
				if (! $multi && (is_array ( $parameters ) || is_object ( $parameters ))) {
					$body = http_build_query ( $parameters );
				} else {
					$body = EIService::build_http_query_multi ( $parameters );
					$headers [] = "Content-Type: multipart/form-data; boundary=" . EIService::$boundary;
				}
				return EiService::http ( $url, $method, $body, $headers ,$this->_EiService);
		}
	}


}


/**
 * 错误输出类
 */
class SinaError implements IEIError{
    public function __construct(){

    }

    /**
     * showError
     * 显示错误信息
     * @param int $code  错误代码
     * @param string $description 描述信息（可选）
     * @param int $mode 输出模式,0:为强制弹出对话框,1为强制输出字符串,2为throw Exption,NULL为执行系统的printerror
     */
    public static function showError($code, $description = NULL,$mode=NULL){     
        if($code!==NULL)
        {
            $mcode = NULL;
            if(is_array($code) && !empty($code['error']))
            {
                $mcode = intval($code['error']);
                if($mcode==0) return;
            }
            else
                $mcode = $code;
            $description .= ' '.self::$errorMsg[$mcode];
            if(empty($description) && is_string($code)) $description = $code;
        }
        if($mode===NULL)
           return  printerror($description, '', 1);
        elseif($mode==1)
            return $description;
        elseif($mode==0){
            $pa = array('ScreenSilent'=>FALSE);
            return printerror($description, '', 1,$pa);
        }
         elseif($mode==2)
        {
            throw new EiException($description, intval($code));
        }
        return NULL;
    }
    public static function showTips($code, $description = NULL,$mode=NULL){
    }
    
   /**
     *错误信息汇总
     * @var array 
  */
    private static $errorMsg = array(
//<editor-fold defaultstate="collapsed" desc="错误信息列表">  
'10001'=>'系统错误',
'10002'=>'服务端资源不可用',
'10003'=>'远程服务出错',
'10005'=>'该资源需要appkey拥有更高级的授权',
'10006'=>'缺少 source参数(appkey)',
'10007'=>'不支持的 MediaType (%s)',
'10008'=>'错误:参数错误，请参考API文档',
'10009'=>'任务过多，系统繁忙',
'10010'=>'任务超时',
'10011'=>'RPC错误',
'10012'=>'非法请求',
'10013'=>'不合法的微博用户',
'10014'=>'第三方应用访问api接口权限受限制',
'10016'=>'错误:缺失必选参数:%s，请参考API文档',
'10017'=>'错误:参数值非法,希望得到 (%s),实际得到 (%s)，请参考API文档',
'10018'=>'请求长度超过限制',
'10020'=>'接口不存在',
'10021'=>'请求的HTTP METHOD不支持',
'10022'=>'IP请求超过上限',
'10023'=>'用户请求超过上限',
'10024'=>'用户请求接口%s超过上限',
'10025'=>'内部接口参数错误',
'10026'=>'该接口已经废弃',
'20001'=>'IDS参数为空',
'20002'=>'uid参数为空',
'20003'=>'用户不存在',
'20005'=>'不支持的图片类型,仅仅支持JPG,GIF,PNG',
'20006'=>'图片太大',
'20007'=>'请确保使用multpart上传了图片',
'20008'=>'内容为空',
'20009'=>'id列表太长了',
'20012'=>'输入文字太长，请确认不超过140个字符',
'20013'=>'输入文字太长，请确认不超过300个字符',
'20014'=>'传入参数有误，请再调用一次',
'20016'=>'发微博太多啦，休息一会儿吧',
'20017'=>'你刚刚已经发送过相似内容了哦，先休息一会吧',
'20019'=>'不要太贪心哦，发一次就够啦',
'20023'=>'很抱歉，此功能暂时无法使用，如需帮助请联系@微博客服 或者致电客服电话400 690 0000',
'20031'=>'需要弹出验证码',
'20032'=>'微博发布成功。目前服务器数据同步可能会有延迟，请耐心等待1-2分钟。谢谢',
'20033'=>'登陆状态异常',
'20038'=>'您刚才已经发过相似的内容啦，建议您第二天再尝试！',
'20044'=>'可发表，但是需要弹出蒙层',
'20045'=>'不可发表，需要弹出蒙层',
'20101'=>'不存在的微博',
'20102'=>'不是你发布的微博',
'20103'=>'不能转发自己的微博',
'20109'=>'微博 id为空',
'20111'=>'不能发布相同的微博',
'20112'=>'由于作者隐私设置，你没有权限查看此微博',
'20114'=>'标签名太长',
'20115'=>'标签不存在',
'20116'=>'标签已存在',
'20117'=>'最多200个标签',
'20118'=>'最多5个标签',
'20119'=>'标签搜索失败',
'20120'=>'由于作者设置了可见性，你没有权限转发此微博',
'20121'=>'visible参数非法',
'20122'=>'应用不存在',
'20123'=>'最多屏蔽200个应用',
'20124'=>'最多屏蔽500条微博',
'20125'=>'没有屏蔽过此应用',
'20126'=>'不能屏蔽新浪应用',
'20127'=>'已添加了此屏蔽',
'20128'=>'删除屏蔽失败',
'20129'=>'没有屏蔽任何应用',
'20130'=>'由于作者隐私设置，你没有权限评论此微博',
'20132'=>'抱歉，该内容暂时无法查看。如需帮助，请联系客服',
'20133'=>'您不是会员，或者已过期，只有会员才能屏蔽应用',
'20134'=>'分组不存在',
'20135'=>'源微博已被删除',
'20136'=>'非会员发表定向微博，分组成员数最多200',
'20201'=>'不存在的微博评论',
'20203'=>'不是你发布的评论',
'20204'=>'评论ID为空',
'20206'=>'作者只允许关注用户评论',
'20207'=>'作者只允许可信用户评论',
'20401'=>'域名不存在',
'20402'=>'verifier错误',
'20403'=>'屏蔽用户列表中存在此uid',
'20404'=>'屏蔽用户列表中不存在此uid',
'20405'=>'uid对应用户不是登录用户的好友',
'20406'=>'屏蔽用户个数超出上限',
'20407'=>'没有合适的uid',
'20408'=>'从feed屏蔽列表中，处理用户失败',
'20409'=>'当前用户不存在置顶微博',
'20410'=>'设置置顶微博失败',
'20411'=>'该微博不是你的微博',
'20412'=>'当前用户已经试用微博置顶功能，不能再试用',
'20413'=>'此微博不是置顶微博',
'20414'=>'此微博是当前置顶微博',
'20501'=>'错误:source_user 或者target_user用户不存在',
'20502'=>'必须输入目标用户id或者 screen_name',
'20503'=>'关系错误，user_id必须是你关注的用户',
'20504'=>'你不能关注自己',
'20505'=>'加关注请求超过上限',
'20506'=>'已经关注此用户',
'20507'=>'需要输入验证码',
'20508'=>'根据对方的设置，你不能进行此操作',
'20509'=>'悄悄关注个数到达上限',
'20510'=>'不是悄悄关注人',
'20511'=>'已经悄悄关注此用户',
'20512'=>'你已经把此用户加入黑名单，加关注前请先解除',
'20513'=>'你的关注人数已达上限',
'20521'=>'hi超人，你今天已经关注很多喽，接下来的时间想想如何让大家都来关注你吧！',
'20522'=>'还未关注此用户',
'20523'=>'还不是粉丝',
'20524'=>'hi超人，你今天已经取消关注很多喽，接下来的时间想想如何让大家都来关注你吧！',
'20525'=>'已经是密友了',
'20526'=>'已经发送过密友邀请',
'20527'=>'密友数到达上限',
'20528'=>'不是密友',
'20601'=>'列表名太长，请确保输入的文本不超过10个字符',
'20602'=>'列表描叙太长，请确保输入的文本不超过70个字符',
'20603'=>'列表不存在',
'20604'=>'不是对象所属者',
'20606'=>'记录已存在',
'20607'=>'错误:数据库错误，请联系系统管理员',
'20608'=>'列表名冲突',
'20610'=>'目前不支持私有分组',
'20611'=>'创建list失败',
'20612'=>'目前只支持私有分组',
'20613'=>'错误:不能创建更多的列表',
'20614'=>'已拥有列表上下，请参考API文档',
'20615'=>'成员上线，请参考API文档',
'20616'=>'不支持的分组类型',
'20617'=>'最大返回300条',
'20618'=>'uid 不在列表中',
'20701'=>'不能提交相同的标签',
'20702'=>'最多两个标签',
'20704'=>'您已经收藏了此微博',
'20705'=>'此微博不是您的收藏',
'20706'=>'操作失败',
'20801'=>'trend_name是空值',
'20802'=>'trend_id是空值',
'21001'=>'标签参数为空',
'21002'=>'标签名太长，请确保每个标签名不超过14个字符',
'21101'=>'参数domain错误',
'21102'=>'该手机号已经被使用',
'21103'=>'该用户已经绑定手机',
'21104'=>'verifier错误',
'21105'=>'你的手机号近期频繁绑定过多个帐号，如果想要继续绑定此帐号，请拨打客服电话400 690 0000申请绑定',
'21108'=>'原始密码错误',
'21109'=>'新密码错误',
'21110'=>'此用户暂时没有绑定手机',
'21111'=>'教育信息过多',
'21112'=>'学校不存在',
'21113'=>'教育信息不存在',
'21114'=>'没有用户有教育信息',
'21115'=>'职业信息不存在',
'21116'=>'没有用户有职业信息',
'21117'=>'此用户没有qq信息',
'21118'=>'学校已存在',
'21119'=>'没有合法的uid',
'21120'=>'此用户没有微号信息',
'21121'=>'此微号已经存在',
'21122'=>'用户手机绑定状态为待绑定',
'21123'=>'用户未绑定手机',
'21124'=>'邮箱错误',
'21125'=>'注册邮箱禁止使用新浪邮箱',
'21128'=>'昵称已存在或非法(昵称只能支持中英文、数字、下划线或减号;昵称禁止为全数字)',
'21129'=>'密码长度应为6到16位',
'21130'=>'密码只允许字母，数字，键盘半角字符',
'21131'=>'发送激活邮件失败',
'21132'=>'注册邮箱已被占用',
'21133'=>'注册后激活失败',
'21134'=>'更改用户type失败',
'21135'=>'昵称长度应为4到30位',
'21136'=>'gender参数可选值，m表示男性，f表示女性',
'21137'=>'参数ip无效',
'21138'=>'参数key不是有效的无线号段',
'21140'=>'此用户不是会员用户',
'21141'=>'有重复的屏蔽词',
'21142'=>'屏蔽词个数达到当前会员类型上限',
'21301'=>'认证失败',
'21302'=>'用户名或密码不正确',
'21303'=>'用户名密码认证超过请求限制',
'21304'=>'版本号错误',
'21305'=>'缺少必要的参数',
'21306'=>'Oauth参数被拒绝',
'21307'=>'时间戳不正确',
'21308'=>'nonce参数已经被使用',
'21309'=>'签名算法不支持',
'21310'=>'签名值不合法',
'21311'=>'consumer_key不存在',
'21312'=>'consumer_key不合法',
'21313'=>'consumer_key缺失',
'21314'=>'Token已经被使用',
'21315'=>'Token已经过期',
'21316'=>'Token不合法',
'21317'=>'Token不合法',
'21318'=>'Pin码认证失败',
'21319'=>'授权关系已经被解除',
'21320'=>'不支持的协议',
'21321'=>'未审核的应用使用人数超过限制',
'21322'=>'重定向地址不匹配',
'21323'=>'请求不合法',
'21324'=>'client_id或client_secret参数无效',
'21325'=>'提供的Access Grant是无效的、过期的或已撤销的',
'21326'=>'客户端没有权限',
'21327'=>'token过期',
'21328'=>'不支持的 GrantType',
'21329'=>'不支持的 ResponseType',
'21330'=>'用户或授权服务器拒绝授予数据访问权限',
'21331'=>'服务暂时无法访问',
'21332'=>'access_token 无效',
'21333'=>'禁止使用此认证方式',
'21334'=>'帐号状态不正常',
'21501'=>'access_token 无效',
'21502'=>'禁止使用此认证方式',
'21503'=>'IP是空值',
'21504'=>'参数url是空值',
'21601'=>'系统繁忙请重试',
'21602'=>'找不到模板ID XXX',
'21603'=>'修改数据报错',
'21604'=>'已有通知的appkey校验失败',
'21605'=>'创建模板超过最大限制',
'21610'=>'授权失败',
'21611'=>'非法appkey',
'21612'=>'无效IP',
'21613'=>'参数错误，需要通知id或者标题内容参数',
'21620'=>'参数错误，uid参数必须与登录用户一致，授权用户uid %s 参数uid %s',
'21621'=>'appkey62无效',
'21631'=>'添加屏蔽达到上限',
'21632'=>'禁止添加未授权的应用',
'21633'=>'此应用没有发通知权限，不许屏蔽',
'21634'=>'已经屏蔽过了',
'21650'=>'未找到通知id',
'21651'=>'只有appkey所有人能发通知',
'21652'=>'通知模板状态不对，不能发送',
'21653'=>'通知模板与发送通知appkey不一致',
'21654'=>'通知发送失败，请重试',
'21655'=>'通知请求非法',
'21656'=>'通知模板与发送通知变量不匹配',
'21701'=>'提醒失败，需要权限',
'21702'=>'无效分类',
'21703'=>'无效状态码',
'21901'=>'地理信息接口系统错误',
'21902'=>'地理信息接口缺少source (ip) 参数',
'21903'=>'地理信息接口不返回任何数据',
'21904'=>'地理信息接口ip所对应的城市不存在',
'21905'=>'地理信息接口ip地址非法',
'21906'=>'地理信息接口经纬度坐标非法',
'21907'=>'地理信息接口坐标超出范围',
'21908'=>'地理信息接口超过最大请求数',
'21909'=>'地理信息接口远程服务错误',
'21910'=>'地理信息接口需至少提交一个城市或中心坐标参数',
'21911'=>'地理信息接口需至少提交一个起点id或起点坐标参数',
'21912'=>'地理信息接口需至少提交一个终点id或终点坐标参数',
'21913'=>'地理信息接口起点坐标非法',
'21914'=>'地理信息接口终点坐标非法',
'21915'=>'地理信息接口起点id非法',
'21916'=>'地理信息接口终点id非法',
'21917'=>'地理信息接口起点和终点在不同的城市',
'21918'=>'地理信息接口城市代码非法',
'21920'=>'地理信息接口创建日志目录失败',
'21921'=>'地理信息接口查询数据不能为空',
'21922'=>'地理信息接口提交的数据格式不正确',
'21923'=>'地理信息接口返回结果为空，没有查到相关数据',
'21940'=>'地理信息接口所有的字段校验不能为空',
'21941'=>'地理信息接口表单不是post方式提交',
'21942'=>'地理信息接口数据库出错级别',
'21943'=>'地理信息接口scrid不存在',
'21944'=>'地理信息接口参数超出最大小值',
'21945'=>'地理信息接口参数不是指定类型',
'21951'=>'地理信息接口图片尺寸超出范围',
'21952'=>'地理信息接口图片尺寸非法',
'21953'=>'地理信息接口中心坐标非法',
'21954'=>'地理信息接口点的名称必须存在',
'21955'=>'地理信息接口点的图标必须存在',
'21956'=>'地理信息接口点的名称非法',
'21957'=>'地理信息接口点的图标非法',
'21958'=>'地理信息接口点的坐标非法',
'21959'=>'地理信息接口点的图标非法',
'21960'=>'地理信息接口需至少提交一个关键字或位置分类参数',
'21961'=>'地理信息接口页码超出范围',
'21962'=>'地理信息接口每页的结果数超出范围',
'21963'=>'地理信息接口需至少提交一个坐标ID或坐标经纬度参数',
'21964'=>'地理信息接口查询半径超出范围',
'21965'=>'地理信息接口位置ID非法',
'21966'=>'地理信息接口缺少参数coordinates',
'21971'=>'地理信息接口中心坐标超出范围',
'49999' =>'参数不完整' ,       
'50000' =>'不支持的操作'
//</editor-fold>
            );
}

