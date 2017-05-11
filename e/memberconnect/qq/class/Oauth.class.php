<?php
/* PHP SDK
 * @version 2.0.0
 * @author connect@qq.com
 * @copyright © 2013, Tencent Corporation. All rights reserved.
 */
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'eibase.php' );
include_once(QQXCLASS_PATH.'Oauth.class.php');
include_once(QQXCLASS_PATH.'Action.class.php');

class QQOAuth extends EOAuth{

    const VERSION = "2.0";   

    /**
     * 腾讯AppKey
     * @var string
     */
    protected $client_id;
    /**
     * 腾讯AppSecret
     * @var string 
     */
    protected $client_secret;
    /**
     * 登录的回调地址
     * @var string 
     */
    protected $redirect_uri;
    /**
	 * 重载构造函数 
	 * @param array $params  : array("client_id","client_secret","access_token","refresh_token","redirect_uri"),array("应用号","应用密钥","访问令牌","更新的令牌")|array('id')  	
	 */
	private function construct2(array &$params) {
        if (empty($params)) {
            parent::__construct();
            return;
        }
         $appis = NULL;
        if($params['id'])
            $appis =EIService::GetAppSum($params['id']);
        elseif($params['应用密钥'])
        {
           $appis =EIService::GetAppSum(0,$params['应用号'], $params['应用密钥']); 
        }
        elseif($params[chr(1).'appsecret'])
        {
            $appis =EIService::GetAppSum(0,$params[chr(1).'appkey'], $params[chr(1).'appsecret']); 
        }
        elseif($params['appsecret'])
        {
              $appis =EIService::GetAppSum(0,$params['appkey'], $params['appsecret']); 
        }
        $params2 = $params;
         if(!empty($appis)) 
         {
             $this->_appname = $appis['appname'];
                     $atn = new QQAction($appis['appkey'],$appis['appsecret'],NULL,NULL,&$this);
             $params2 = $atn->ConvertParam($params,__FUNCTION__); //参数转换
         }
        
        if (count($params2) != 1) {
            if (isset($params2 ['client_id']) && isset($params2 ['client_secret'])) {
                return $this->__construct(
                                $params2 ['client_id'], $params2 ['client_secret'], (isset($params2 ['access_token']) ? $params2 ['access_token'] : NULL), (isset($params2 ['redirect_uri']) ? $params2 ['redirect_uri'] : NULL), (isset($params2 ['refresh_token']) ? $params2 ['refresh_token'] : NULL)
                );
            }
        }
        elseif (isset($params2['id'])) {
            return $this->__construct((int)$params2['id']);
        }
    }

    /**
	 * 构造新浪微博验证类
	 * @param object $client_id_aps 当只有$client_id_aps一个参数而且为int时，表示为此授权的ID,当只有$appkey_aps一个参数而且为array时此项为array("应用号","应用密钥","访问令牌","更新的令牌")
         * @param string $client_secret  分配给应用的APP KEY
	 * @param string $access_token  如果为空，又已经登录而且绑定了第三方交互的情况下，自动到数据库获取对应的access_token
	 * @param string $redirect_uri 授权回调地址
	 */
	function __construct($client_id_aps,$client_secret=NULL, $access_token = NULL,$redirect_uri=NULL,$refresh_token=NULL) {     
		if(func_num_args()==1 && is_array($client_id_aps))
			return $this->construct2($client_id_aps);                              
                $this->userid = intval(getcvar('mluserid',0,TRUE));
		$this->call_back_url = $redirect_uri;
                $this->appkey = $client_id_aps;
                $this->appsecret = $client_secret;
		$this->_authorizeUrl='https://graph.qq.com/oauth2.0/authorize';
		$this->_accessTokenURL='https://graph.qq.com/oauth2.0/token';
		$this->_revokeoauthUrl='https://graph.qq.com/oauth2.0/revoke_auth';
                $this->_openidURL='https://graph.qq.com/oauth2.0/me';                
		$this->_EiService = new EIService("QQ OAuth2 v0.1");
                //初始化别名
                $this->client_id = &$this->appkey;//引用变量appkey
                $this->client_secret = &$this->appsecret;                
                $this->redirect_uri = &$this->call_back_url;  
                
                $lparams = array();
                if(func_num_args()==1 && is_int($client_id_aps))
                {
                    $lparams['id'] = $client_id_aps;
                    $this->LoadData($lparams);
                }
		else
                {
                    if(!empty($client_id_aps)) $lparams['appkey'] = $client_id_aps;
                    if(!empty($client_secret)) $lparams['appsecret'] = $client_secret;
                    if(!empty($access_token)) $lparams['access_token'] = $access_token;
                    if(!empty($refresh_token)) $lparams['refresh_token'] = $refresh_token; 
                    if(!$this->LoadData($lparams))
                    {
                        $this->client_id = $client_id_aps;
                        $this->client_secret = $client_secret;
                    }
                    if(!empty($access_token))  $this->access_token = $access_token;
                    if(!empty($refresh_token))  $this->refresh_token = $refresh_token;
                }                
                $this->userid = intval(getcvar('mluserid',0,TRUE));                
		if(empty($this->access_token) && empty($this->refresh_token))
		{
			if($this->userid)//查找已经绑定access_token：				
                            $this->access_token = EIService::GetAccessTokenForUID($this->userid, $this->GetAID());		
		} 
                
		$this->remote_ip=EIService::GetIP();
	}  
        /**
	 * authorize接口,用于获取Authorization Code
	 *
	 * 对应API：{@link http://wiki.connect.qq.com/%E4%BD%BF%E7%94%A8authorization_code%E8%8E%B7%E5%8F%96access_token}
	 *
	 * @param string $param['redirect_uri'] 成功授权后的回调地址，必须是注册appid时填写的主域名下的地址，建议设置为网站首页或网站的用户中心。注意需要将url进行URLEncode。
         * @param string $param['client_id'] 申请QQ登录成功后，分配给应用的appid。
	 * @param string $param['response_type'] 支持的值包括 code 和token 默认值为code
	 * @param string $param['state'] 用于保持请求和回调的状态。在回调时,会在Query Parameter中回传该参数
	 * @param string $param['display'] 仅PC网站接入时使用。用于展示的样式。不传则默认展示为PC下的样式。如果传入“mobile”，则展示为mobile端下的样式。
         * @param bool $param['scope'] 请求用户授权时向用户显示的可进行授权的列表。如果要填写多个接口名称，请用逗号隔开。例如：scope=get_user_info,list_album,upload_pic,do_like 不传则默认请求对接口get_user_info进行授权。
         * @param string $param['state'] 自定义的验证码.(不提供则自动生成)client端的状态值。用于第三方应用防止CSRF攻击，成功授权后回调时会原样带回。请务必严格按照流程检查用户与state参数状态的绑定。
	 * @return string 此网址执行结果:{此code会在10分钟内过期。
         * 如果用户成功登录并授权，则会跳转到指定的回调地址，并在redirect_uri地址后带上Authorization Code和原始的state值。如：
         * PC网站：http://graph.qq.com/demo/index.jsp?code=9A5F************************06AF&state=test
         * 如果用户在登录授权过程中取消登录流程，对于PC网站，登录页面直接关闭
         * }
	 */
	public function AuthorizeURL(array &$param=NULL){
		if(isset($param))
		{
			$params = array();
			$params['response_type'] = $param['response_type'];
			$params['display'] = $param['display'];
			if(empty($param['response_type']))$params['response_type'] ='code';
			if(empty($param['client_id']))$params['client_id'] = $this->client_id;
			else $params['client_id'] = $param['client_id'];
			if(empty($param['redirect_uri'])==FALSE)$params['redirect_uri'] = $param['redirect_uri'];
			else  $params['redirect_uri'] =  $this->call_back_url;
			if(empty($param['scope'])==FALSE) $params['scope'] =  $param['scope'];
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
	 * 返回指定access_token取消授权的URL
	 * @param array $params 参数，必须包含access_token键
	 */
	public function RevokeoauthURL(array &$params=NULL){
		if(isset($params['access_token'])==FALSE)
			return $this->_revokeoauthUrl;
		else
			return $this->_revokeoauthUrl. "?" . http_build_query($params);
	}
	/**
	 * 取消指定access_token的授权(会从本地数据库中删除指定授权,QQ不支持平台解除授权)
	 * @param array $params 参数，CheckUser键表示是否检测为当前用户,否则不执行解除
	 */
	public function Revokeoauth(array $params = NULL) {
        if (isset($params['CheckUser']) && ((bool)$params['CheckUser']) && $this->userid) {
            $guser = getcvar('mluserid', 0, TRUE);
            if ($guser != $this->userid)
                return FALSE; //不是当前用户,不允许解除                   
        }        
        if (isset($params['CheckUser']))
            unset($params['CheckUser']);
        if(isset($params['access_token']) && $this->access_token!= $params['access_token']) //如果提供了参数而且不相同是不会删除的
            return FALSE;
        return $this->DeleteTokenOnData();
    }

    /**
     * 远程获取AccessToken信息(注:参数不转换)
     * @param array $param {
     * @param array $param['keys'] array('code','redirect_uri','refresh_token','username','password','state') 必须
     * @param array $param['type'] string 类型 token/code(默认)/password
     * }
     * @return array('access_token','expires_in','refresh_token')
     * @throws EiException
     */
         public function AccessToken(array &$param = NULL) {
        if (!isset($param) || !isset($param['keys']))
            QQError::showError(49999, '函数' . __FUNCTION__, 2);
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
        $tourl = $this->_accessTokenURL . (strpos($this->_accessTokenURL, '?') === FALSE ? '?' : '&') . http_build_query($params);
        $response = EIService::get_contents($tourl);
        $token = array();
        if (strpos($response, "callback") !== false) {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
            $token = json_decode($response);
            if (isset($token->error)) {
                QQError::showError($token->error, $token->error_description, 2);
            }
            if (is_object($token))
                $token = (array)$token;
        }
        else
            parse_str($response, $token);

        if (is_array($token) && !isset($token['code'])) {
            $this->access_token = $token['access_token'];
            if (!empty($token['refresh_token']))
                $this->refresh_token = $token['refresh_token'];
            if (empty($token['expires_in']))
                $this->expired = intval($token['expires_in']);
        } else {
            throw new EiException("get access token failed." . $token['code'], $token['msg']);
        }
        return $token;
    }

    public function DoRefreshToken($refresh_token=NULL)
    {
        if(empty($refresh_token)) $refresh_token = $this->refresh_token;
        if(empty($refresh_token)) return FALSE;
        $p = array('type'=>'token','keys'=>array('refresh_token'=>$refresh_token));
        $r = $this->AccessToken($p);
        if(!empty($r))
        {
          $this->SaveTokenInfo();
          return TRUE;    
        }
        return FALSE;
    }
    
    /**
	 * 获取OpenID的信息
	 * @param array $params 参数,必须包含access_token键(不进行转换)
	 * @return array :array("openid","client_id")
	 */
    public function GetOpenIdInfo(array &$params = NULL)    {
        if (isset($params) === FALSE)
            return null;
        if (empty($params['access_token']))
            return NULL;
        $pdata = http_build_query($params);
        $tourl = $this->_openidURL . (strpos($this->_openidURL, '?') === FALSE ? '?' : '&') . $pdata;
        $response = EIService::get_contents($tourl);
        //--------检测错误是否发生
        if (strpos($response, "callback") !== false)
        {

            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
        }

        $user = json_decode($response);
        if (isset($user->error))
        {
            QQError::showError($user->error, $user->error_description, 2);
        }
        if(is_object($user)) 
            $user = (array)$user;
        if (is_array($user) && !isset($user['code']))
        {
            if ($user['client_id'] == $this->client_id)//$params['access_token'] == $this->access_token
            {
                $this->openid = strval($user['openid']);
                if (isset($user['scope']))  $this->scope = $user['scope'];
                if(!$this->client_id) $this->client_id = $user['client_id'];
            }
        } 
        else
        {
            throw new EiException("get openid failed." . $user['code'], $user['msg']);
        }
        return $user;
    }

    /**
	 * 获取第三方交互平台用户的用户信息
	 * @param string $access_token 访问令牌
	 * @param string  $openidObj 第三方用户ID,也可以是用户名,空为自己
	 * @return array|NULL ,参数不会转换,包含[用户名]
	 */
	public function GetEiUserInfo($access_token=NULL,$openidObj=0)
	{
		if(empty($access_token))$access_token=$this->access_token;
                $qqact = new QQAction($this->client_id, $this->client_secret, $access_token,$this->redirect_uri,&$this);
                $params = array('ForbidConvertIn'=>TRUE,'ForbidConvertOut'=>TRUE);
                if(!empty($openidObj)){
                if(strlen($openidObj)>24) $params['fopenid']=$openidObj;
                else $params['name']=$openidObj;
                }
                $r = $qqact->GetUserInfo($params);
                if(isset($r['ret']) && $r['ret']>0) 
                    return NULL;
                if(!empty($r))   {
                    if(isset($r['nickname']))
                      $r['用户名'] = $r['nickname'];
                    elseif(isset($r['nick']))
                        $r['用户名'] = $r['nick'];
                    elseif(isset($r['username']))
                        $r['用户名'] = $r['username']; 
                    elseif(isset($r['name']))
                        $r['用户名'] = $r['name'];                     
                }
		return $r;
	} 
   /**
   * 支持手动续期Token的操作
   * @return TRUE
  */  
  public function SupportRefreshToken()
   {
      return TRUE;
   }
    /**
     * 格式化输出Token信息,以便外部使用
     * @param array $tokeninfo
     * @return array
     */
    public function FormatTokenInfo(array $tokeninfo)
    {
        if(empty($tokeninfo)) return $tokeninfo;
        if(isset($tokeninfo['client_id']))
        {
            $tokeninfo['appkey'] =  $tokeninfo['client_id'];
            unset($tokeninfo['client_id']);
        }
        if(isset($tokeninfo['client_secret']))
        {
            $tokeninfo['appsecret'] =  $tokeninfo['client_secret'];
            unset($tokeninfo['client_secret']);
        }
        if(isset($tokeninfo['redirect_uri']))
        {
            $tokeninfo['call_back_url'] =  $tokeninfo['redirect_uri'];
            unset($tokeninfo['redirect_uri']);
        }        
        return $tokeninfo;
    }   
   /**
* 取得本授权的第三方平台的ID(用户的OPENID)
*/
public function GetOpenID()    {
        if (!empty($this->openid))
            return $this->openid;
        else
        {
            if (!empty($this->access_token))
            {
                $pv = EIService::IndexTokenInfo($this->aid, $this->appkey, $this->access_token);
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

}
