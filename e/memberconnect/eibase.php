<?php
if (!function_exists('curl_init')) {
	throw new Exception('EIService needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
	throw new Exception('EIService needs the JSON PHP extension.');
}

define ( "SUBSTR_MODE_BYTE", 0 );
define ( "SUBSTR_MODE_COUNT", 1 );
define ( "SUBSTR_MODE_SCOMT", 2 );//短评
define ( "SUBSTR_MODE_SUMY", 3 );//摘要
include_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'MsgCore.php' );//加入消息处理类
class EiException extends Exception {
    // 重定义构造器使 message 变为必须被指定的属性
    public function __construct($message, $code = 0,Exception $previous = NULL) {
        // 自定义的代码

        // 确保所有变量都被正确赋值
        parent::__construct($message, $code,$previous);
    }
}
/**
 * 
 * 外部交互服务类
 *
 */
class EIService{
	/**
	 * 验证类的类名枚举
	 * @var array
	 */
	private static $OAuthClasses= array("sina"=>"SinaOAuth","qq"=>"QQOAuth");
	/**
	 * 操作类的类名枚举
	 * @var array
	 */
	private static $ActionClasses= array("sina"=>"SinaAction","qq"=>"QQAction");
        /**
         *错误类类名枚举
         * @var array 
         */
	private static $ErrorClasses= array("sina"=>"SinaError","qq"=>"QQError");   
        /**
         * 参数转换与翻译类类名枚举
         * @var array 
         */
        private static $TranSrvClasses= array("sina"=>"SinaTranSrv","qq"=>"QQTranSrv");  
	/**
	 * Set timeout default.
	 *
	 * @ignore
	 */
	public $timeout = 30;
	/**
	 * Set connect timeout.
	 *
	 * @ignore
	 */
	public $connecttimeout = 30;
	/**
	 * Verify SSL Cert.
	 *
	 * @ignore
	 */
	public $ssl_verifypeer = FALSE;

	/**
	 * Contains the last HTTP headers returned.
	 *
	 * @ignore
	 */
	public $http_info;
	/**
	 * Set the useragnet.
	 *
	 * @ignore
	 */
	public $useragent;
	
	/**
	 * boundary of multipart
	 * @ignore
	 */
	public static $boundary = '';	
	/**
	 * Contains the last HTTP status code returned.
	 *
	 * @ignore
	 */
	public $http_code;
	/**
	 * Contains the last API call.
	 *
	 * @ignore
	 */
	public $lastUrl;
	/**
	 * 是否打印调试信息
	 */
	public $debug = FALSE;
	
	protected $http_header = array();
        /**
         * 声明数据库操作类
         * @var mysqlquery 
         */
	public $empire; 


        /**
     * 如果没有加载应用列表则产生缓存变量，从数据库中取出
     * @param mysqlquery $dbobj 数据库连接
     * @param bool $reread 是否强制重新读取
     * @return array array(appname=>array(id,type,name,appkey,appsecret,callbackurl,callbackurl2,info,trankeywordslist,tranregexlist))
     */
    public static function GenerateApps($reread = FALSE) {
        global $dbtbpre, $public_r, $empire, $link;
        $vn = 'add_' . PV_EINAME;
        if (empty($public_r))
            include_once ('/e/class/connect.php');
        if (!class_exists('mysqlquery'))
            include_once (ECMS_PATH . 'e' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'db_sql.php');
        $dataini = TRUE;
        if (empty($empire))
        {
            $dataini = FALSE;
            $link = db_connect();
            $empire = new mysqlquery ();
        }
        $r = NULL;
        if (empty($public_r[$vn]) == FALSE && !is_array($public_r[$vn]) && !$reread)
        {
            $ttc = $public_r[$vn]; //stripslashes	
            $vv = @unserialize($ttc); //转换成Array	
            if ($vv == FALSE)
                $vv = @unserialize(stripslashes($ttc));
            if (is_array($vv))
            {
                $public_r[$vn] = $vv;
                $r = $vv;
            }
        }
        if ($r == NULL && (empty($public_r[$vn]) || !is_array($public_r[$vn]) || $reread))
        {
            $data = array();
            $cmd = "select id,apptype,appid,appkey,callbackurl,callbackurl2,info,appname,tranregexlist,trankeywordslist from {$dbtbpre}enewsmember_connect_app where isclose = 0 order by id";
            $sql = $empire->query($cmd);
            while ($r = $empire->fetch($sql, MYSQL_ASSOC))
            {
                $mycdata = self::ParseEIInfo($r['info']);
                $md = array('id' => $r['id'], 'type' => $r['apptype'], 'name' => $r['appname'], 'appkey' => $r['appid'], 'appsecret' => $r['appkey'], 'callbackurl' => $r['callbackurl'], 'callbackurl2' => $r['callbackurl2'], 'info' => $mycdata,'tranregexlist'=>$r['tranregexlist'],'trankeywordslist'=>$r['trankeywordslist']);
                $data[$r['appname']] = $md; //KEY					
            }
            $s = serialize($data);
            $ddnew = array('myvar' => PV_EINAME, 'varname' => '外部交互应用', 'varvalue' => $s, 'varsay' => '网站已经注册的有效的第三方交互账号列表');
            AddToPubVar($ddnew);
            $public_r[$vn] = $data;
            $r = $data;
        }
        if (!$dataini)
        {
            db_close();
            $empire = null;
        }
        if ($r != NULL)
            return $r;
        if (is_array($public_r[$vn]))
            return $public_r[$vn];
        return NULL;
    }

    /**
	 * 如果是数字，化为整数，否则去除两边的空格
	 * @param string $id 要处理的字符或者数字
	 * @return string
	 */
	public static function numb_format($id) {
		
		if ( is_float($id) ) {
			return  number_format($id, 0, '', '');
		} elseif ( is_string($id) ) {
			return   trim($id);
		}
		return '';
	}
	/**
	 * 检测是否全为数字，如是返回原数字，否则返回空
	 * @param string $d
	 * @return string|NULL
	 */
	public static function TryToNumber($d)
	{
		$pattern = '/[^\d]/';
		$d=trim($d);
		if(preg_match($pattern, $d)==0)
			return $d;
		return NULL;
	}
	/**
	 * 把标量转换成整数，如果失败，则用默认值代替
	 * @param mixed  $d
	 * @param number $defaultValue
	 * @return number
	 */
	public static function ToInt($d,$defaultValue=0)
	{
		$v= intval($d);
		if($v==0){
			$g=strval($d);
			if($g{0}!='0'){
				$v=$defaultValue;
			}
		}
		return $v;
		
	}
	/**
	 * 解析enewseitype表中的info字段
	 * @param string $str 要解析的字符串,一般是 enewseitype表中的cdata字段
	 *        每行一个设置，行上以:分隔,前为名称，后为值
	 * @return array KEY=>Value
	 */
	public static function ParseEIInfo($str){
		$vars = preg_split("/[\r\n]+/", $str, -1, PREG_SPLIT_NO_EMPTY);
		$r= array();
		foreach ($vars as $a){
			$p2= preg_split("/\s*:\s*/", $a, 2);
			if(count($p2)==2){
				$r[$p2[0]]=$p2[1];				
			}			
		}
		return $r;
		
	}

	
	/**
	 * 查找第三方交互的环境变量
	 * @param array $params，可包含的键有：id/cappid,appname,[appkey,appsecret],apptype(具有不确定性)
	 * @return NULL|array array('id','type','name','appkey','appsecret','callbackurl','callbackurl2','info','tranregexlist','trankeywordslist')
	 */
public static function IndexENV(array &$params)
    {
        if (empty($params))
            return NULL;
        $ca_data = self::GenerateApps();
        if (empty($ca_data))
            return NULL;
        $fid = 0;
        if (isset($params['id']) && is_numeric($params['id']))
            $fid = intval($params['id']);
        else
        if (isset($params['cappid']) && is_numeric($params['cappid']))
            $fid = intval($params['cappid']);
        if ($fid != 0)
        {
            foreach ($ca_data as $k => $v)
            {
                if ($fid == $v['id'])
                {
                    return $ca_data[$k];
                }
            }
            return NULL;
        } elseif (!empty($params['appname']))
        {
            $ftype = $params['appname'];
            return $ca_data[$ftype];
        }
        elseif (!empty($params['appkey']) && !empty($params['appsecret']))
        {
            foreach ($ca_data as $k => $v)
            {
                if ($v['appkey'] == $params['appkey'] && $v['appsecret'] == $params['appsecret'])
                {
                    return $ca_data[$k];
                }
            }
            return NULL;
        } elseif (!empty($params['appkey']))
        {
            foreach ($ca_data as $k => $v)
            {
                if ($v['appkey'] == $params['appkey'])
                {
                    return $ca_data[$k];
                }
            }
            return NULL;
        } elseif (!empty($params['appsecret']))
        {
            foreach ($ca_data as $k => $v)
            {
                if ($v['appsecret'] == $params['appsecret'])
                {
                    return $ca_data[$k];
                }
            }
            return NULL;
        }
        elseif (!empty($params['apptype'])){
            foreach ($ca_data as $k => $v)
            {
                if ($v['type'] == $params['apptype'])
                {
                    return $ca_data[$k];
                }
            }
        }
        return NULL;
    }
    /**
     * 获取第三方交互应用信息
     * @param mixed $appobj 可以为ID(enewsmember_connect_app.id)与可以为appname
     * @return array('id','type','name','appkey','appsecret','callbackurl','callbackurl2','info','tranregexlist','trankeywordslist')
     */
    public static function GetENV($appobj)
    {
        $find = array();
        if (is_numeric($appobj))
            $find['id'] = intval($appobj);
        else
            $find['appname'] = $appobj;
        $fv = self::IndexENV($find);
        return $fv;
    }
    /**
     * 根据应用的数据库id获取第三方交互应用的类型apptype
     * @param int $cappid 应用的数据库id,enewsmember_connect_app.id
     * @return null|string apptype
     */
    public static function GetAppType($cappid)
    {
        if(empty($cappid)) return NULL;
        $d = self::GetENV(intval($cappid));
        if(empty($d)) return NULL;
        return $d['type'];
    }

    /**
         *  获取 Token 信息,先从$_SESSION['mlbindeis']中获取,如果无,就可能从数据库中获取
         * @param int $app_id ,应用ID,enewsmember_connect_app.id
         * @param string $appkey
         * @param string $token
         * @param bool $isnewtoken 是否为新的token,如果是,则不读数据库
         * @return null | array('id','aid','apptype','openid','ename','token','refresh_token','scope','appkey','appsecret','used','expired')
         */
  public static function IndexTokenInfo($app_id, $appkey, $token, $isnewtoken = FALSE)
    {
        global $dbtbpre, $empire;
        if (!isset($_SESSION))
            session_start();
        $da = self::GetCUBEISForSession();
        if (!empty($da))
        {
            foreach ($da as $d)
            {
                if ($d['token'] == $token && $d['appkey'] == $appkey && $d['aid'] == $app_id)
                    return $d;
            }
        }
        if ($isnewtoken)
            return NULL;
        $appkey2 = mysql_real_escape_string($appkey);
        $token2 = mysql_real_escape_string($token);
        $sql = "SELECT a.id,a.aid,a.apptype,a.openid,a.bindname as ename,a.token,a.rtoken as refresh_token,a.scope,b.appid as appkey,b.appkey as appsecret,unix_timestamp(a.expired) as expired,(a.expired > now()) as used FROM {$dbtbpre}enewsmember_connect as a inner join {$dbtbpre}enewsmember_connect_app as b ON a.aid=b.id WHERE a.aid={$app_id} and b.appid='{$appkey2}' AND a.token='{$token2}' limit 1";
        $sqlr = $empire->fetch1($sql,MYSQL_ASSOC);
        if (empty($sqlr))
            return NULL;
        $sqlr['id'] = (int)$sqlr['id'];
        $sqlr['aid'] = (int)$sqlr['aid'];
        $sqlr['expired'] = (int)$sqlr['expired'];
        return $sqlr;
    }

    function __construct($useragent='', $connecttimeout=30, $timeout=30, $ssl_verifypeer=FALSE)
	{
		global $link,$empire;
		if(empty($link)) $link = db_connect();     //连接MYSQL
		$this->empire = $empire; //声明数据库操作类
		$this->useragent=$useragent;
		$this->connecttimeout=$connecttimeout;
		$this->timeout=$timeout;
		$this->ssl_verifypeer=$ssl_verifypeer;
		self::GenerateApps();
	}
	function __destruct() {

	}
        public static function http($url, $method, $postfields = NULL, $headers = array(),EIService &$eisobj=NULL ) {
                if($eisobj==NULL) $eisobj = new EIService();
		$eisobj->http_info = array ();
		$ci = curl_init ();
		/* Curl settings */
		curl_setopt ( $ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0 );
		curl_setopt ( $ci, CURLOPT_USERAGENT, $eisobj->useragent);
		curl_setopt ( $ci, CURLOPT_CONNECTTIMEOUT,$eisobj->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $eisobj->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_ENCODING, "");
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $eisobj->ssl_verifypeer);
		if (version_compare(phpversion(), '5.4.0', '<')) {
			curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 1);
		} else {
			curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 2);
		}
		curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($eisobj, 'getHeader'));
		curl_setopt($ci, CURLOPT_HEADER, FALSE);

		switch ($method) {
                    case 'POST':
                    case 'post':
                        curl_setopt($ci, CURLOPT_POST, TRUE);
                        if (!empty($postfields)) {
                            curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                            $eisobj->postdata = $postfields;
                        }
                        break;
                    case 'DELETE':
                    case 'delete':
                        curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
                        if (!empty($postfields)) {
                            $url = $url . (strpos($url, '?') === FALSE ? '?' : '&') . $postfields;
                        }
                    default :
                        break;
        }

                curl_setopt($ci, CURLOPT_URL, $url );
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );
		$response = curl_exec($ci);
		$eisobj->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$eisobj->http_info = array_merge($eisobj->http_info, curl_getinfo($ci));
		$eisobj->lastUrl = $url;

		if ($eisobj->debug)
                    {
                        DebugVar($postfields, 'post data');
                        DebugVar($headers, 'headers');
                        DebugVar(curl_getinfo($ci), 'response');
                        DebugVar($response, 'request info');

		}
		curl_close ($ci);
		return $response;
	}
	/**
	 * Get the header info to store.
	 *
	 * @return int
	 * @ignore
	 */
	function getHeader($ch, $header) {
		$i = strpos($header, ':');
		if (!empty($i)) {
			$key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
			$value = trim(substr($header, $i + 2));
			$this->http_header[$key] = $value;
		}
		return strlen($header);
	}
	/**
         * 以CURL方式发送HTTP请求( 是 EOAuth->oAuthRequest 静态版)
         * @param string $url
         * @param string $method GET/POST
         * @param array $parameters
         * @param bool $multi 是否内有文件
         * @param EIService $eiobj 操作对象
         * @return string
         */
                public function CurlRequest($url, $method, array &$parameters, $multi = false,EIService &$eiobj=NULL) {
                if($eiobj==NULL)  $eiobj = new EIService();
		switch ($method) {
			case 'GET' :
                        case 'get' :
				$url = $url . ((strpos($url,'?')===FALSE)?'?':'&') . http_build_query ( $parameters );
                                $hearr = array();
				return self::http( $url, 'GET',$hearr,$eiobj);
			default :
				$headers = array ();
                                 $rip = $eiobj->GetIP();
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
					$body = self::build_http_query_multi ( $parameters );
					$headers [] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
				}
				return self::http( $url, $method, $body, $headers,$eiobj);
		}
	}
	public static function build_http_query_multi($params) {
		if (!$params) return '';
		uksort($params, 'strcmp');
		self::$boundary = $boundary = uniqid('------------------');
		$MPboundary = '--'.$boundary;
		$endMPboundary = $MPboundary. '--';
		$multipartbody = '';

		foreach ($params as $parameter => $value) {

			if( in_array($parameter, array('pic', 'image')) && $value{0} == '@' ) {
				$url = ltrim( $value, '@' );
				$content = file_get_contents( $url );
				$array = explode( '?', basename( $url ) );
				$filename = $array[0];
				$size = @getimagesize($url);//Array ( [0] => 361 [1] => 591 [2] => 2 [3] => width="361" height="591" [bits] => 8 [channels] => 3 [mime] => image/jpeg )
				$imgtype='image/unknown';
				if(!empty($size)) $imgtype=$size['mime'];

				$multipartbody .= $MPboundary . "\r\n";
				$multipartbody .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $filename . '"'. "\r\n";//multipart/form-data
				$multipartbody .= "Content-Type: {$imgtype}\r\n\r\n";
				$multipartbody .= $content. "\r\n";
			} else {
				$multipartbody .= $MPboundary . "\r\n";
				$multipartbody .= 'content-disposition: form-data; name="' . $parameter . "\"\r\n\r\n";
				$multipartbody .= $value."\r\n";
			}

		}

		$multipartbody .= $endMPboundary;
		return $multipartbody;
	}
   /**
     * get_contents
     * 服务器通过get请求获得内容
     * @param string $url       请求的url,拼接后的
     * @return string           请求返回的内容
     */
    public static function get_contents($url){
        if (ini_get("allow_url_fopen") == "1") {
            $response = file_get_contents($url);
        }else{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $url);
            $response =  curl_exec($ch);
            curl_close($ch);
        }
        return $response;
    }

    /**
     * get
     * get方式请求资源
     * @param string $url     基于的baseUrl
     * @param array $params  参数列表数组
     * @return string         返回的资源内容
     */
    public static function httpget($url, $params){
        $combined = $url.((strpos($url,'?')===FALSE)?'?':'&') . http_build_query($params);
        return self::get_contents($combined);
    }

    /**
     * post
     * post方式请求资源
     * @param string $url       基于的baseUrl
     * @param array $params    请求的参数列表
     * @param int $flag         标志位
     * @return string           返回的资源内容
     */
    public static function httppost($url, $params, $flag = 0){
        $ch = curl_init();
        if(! $flag) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_URL, $url);
        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }

 /**
  * 把网址缩短
  * @param array $myps 必须包含url参数
  * @return array | null
  */
public static function DoShortUrl(array &$myps){

         if (!empty($myps) && !empty($myps['url'])) {
            $strRes = EIService::httppost('http://dwz.cn/create.php', $myps);
            $arrResponse = json_decode($strRes, true);
            if ($arrResponse['status'] == 0) {
                return array('s' => $arrResponse['tinyurl'], 'l' => $myps['url']);
            }
            else {
                //支持超长网址,大于2048字节的URL
                $strRes = EIService::httppost('http://urlc.cn/', $myps);
                $html=new DOMDocument();
                $html->loadHTML($strRes);
                $xpath = new DOMXpath($html);
                $elements = $xpath->query("//p[@class='response']/a");
                $r = NULL;
                if(count($elements)>0){
                  $r =  $elements->item(0)->getAttribute('href');
                  if(empty($r)) $r = $elements->item(0)->nodeValue;
                }
               if(!empty($r)) {
                    return array('s' => $r, 'l' => $myps['url']);
                }
            }
        }
        return NULL;
 }
	public static function urlencode_rfc3986($input) {
		if (is_array($input)) {
			return array_map(array('EIService', 'urlencode_rfc3986'), $input);
		} else if (is_scalar($input)) {
			return str_replace(
					'+',
					' ',
					str_replace('%7E', '~', rawurlencode($input))
			);
		} else {
			return '';
		}
	}


	/**
	 * This decode function isn't taking into consideration the above,modifications to the encoding process. However, this method doesn't seem to be used anywhere so leaving it as is.
	 * @param string $string
	 * @return string
	 */
	public static function urldecode_rfc3986($string) {
		return urldecode($string);
	}

	/**
	 * Utility function for turning the Authorization: header into
	 * @param string $header
	 * @param bool $only_allow_oauth_parameters Can filter out any non-oauth parameters if needed (default behaviour)
	 * @return multitype:string
	 */
	public static function split_header($header, $only_allow_oauth_parameters = true) {
		$params = array();
		if (preg_match_all('/('.($only_allow_oauth_parameters ? 'oauth_' : '').'[a-z_-]*)=(:?"([^"]*)"|([^,]*))/', $header, $matches)) {
			foreach ($matches[1] as $i => $h) {
				$params[$h] = self::urldecode_rfc3986(empty($matches[3][$i]) ? $matches[4][$i] : $matches[3][$i]);
			}
			if (isset($params['realm'])) {
				unset($params['realm']);
			}
		}
		return $params;
	}

	/**
	 * This function takes a input like a=b&a=c&d=e and returns the parsed
	 * @param $input array('a' => array('b','c'), 'd' => 'e')
	 */
	public static function parse_parameters( $input ) {
		if (!isset($input) || !$input) return array();

		$pairs = explode('&', $input);

		$parsed_parameters = array();
		foreach ($pairs as $pair) {
			$split = explode('=', $pair, 2);
			$parameter = self::urldecode_rfc3986($split[0]);
			$value = isset($split[1]) ? self::urldecode_rfc3986($split[1]) : '';

			if (isset($parsed_parameters[$parameter])) {
				// We have already recieved parameter(s) with this name, so add to the list
				// of parameters with this name

				if (is_scalar($parsed_parameters[$parameter])) {
					// This is the first duplicate, so transform scalar (string) into an array
					// so we can add the duplicates
					$parsed_parameters[$parameter] = array($parsed_parameters[$parameter]);
				}

				$parsed_parameters[$parameter][] = $value;
			} else {
				$parsed_parameters[$parameter] = $value;
			}
		}
		return $parsed_parameters;
	}
	/**
	 * 将HTML代码转成文本
	 * @param string $str
	 * @return string
	 */
	public static function SpHtml2Text($str)
	{
		$str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU","",$str);
		$alltext = "";
		$start = 1;
		for($i=0;$i<strlen($str);$i++)
		{
			if($start==0 && $str[$i]==">")
			{
				$start = 1;
			}
			else if($start==1)
			{
				if($str[$i]=="<")
				{
					$start = 0;
					$alltext .= " ";
				}
				else if(ord($str[$i])>31)
				{
					$alltext .= $str[$i];
				}
			}
		}
		$alltext = str_replace("　"," ",$alltext);
		$alltext = preg_replace("/&([^;&]*)(;|&)/","",$alltext);
		$alltext = preg_replace("/[ ]+/s"," ",$alltext);
		return $alltext;
	}


	/**
	 * 中文截取2，单字节截取模式
	 * 如果是request的内容，必须使用这个函数
	 *
	 * @access public
	 * @param string $str	需要截取的字符串
	 * @param int $slen	截取的长度
	 * @param int $startdd	开始标记处
	 * @return string
	 */
	public static function cn_substrR($str, $slen, $startdd = 0) {
		$str = self::cn_substr ( stripslashes ( $str ), $slen, $startdd );
		return addslashes ( $str );
	}
	/**
	 * 中文截取
	 *
	 * @access public
	 * @param string $str  需要截取的字符串
	 * @param int $slen	截取的长度
	 * @param int $startdd	开始标记处
	 * @param int $SUBSTR_MODE 截取模式，0为按字节截取，1为按字数截取,默认为1
	 * @return string
	 */
	public static function cn_substr($str, $length, $start = 0,$SUBSTR_MODE=SUBSTR_MODE_COUNT)
	{
		if (strlen( $str ) < ($start + 1)) 	return '';
			preg_match_all ( "/./su", $str, $ar );
			$str = '';
			$tstr = '';
			if(is_array($ar)==false  || count($ar)==0)return '';
		if ($SUBSTR_MODE == SUBSTR_MODE_BYTE) {
			// 为了兼容mysql4.1以下版本,与数据库varchar一致,这里使用按字节截取
			for($i = 0; isset ( $ar [0] [$i] ); $i ++) {
				if (strlen ( $tstr ) < $start) {
					$tstr .= $ar [0] [$i];
				} else {
					if (strlen ( $str ) < $length + strlen ( $ar [0] [$i] )) {
						$str .= $ar [0] [$i];
					} else {
						break;
					}
				}
			}
		}
		elseif($SUBSTR_MODE == SUBSTR_MODE_COUNT)
		{
			$sa= array_slice($ar[0],$start,$length);
			$str=implode($sa);
		}
		return $str;

	}

	/**
	 * HTML转换为文本
	 *
	 * @param string $str 	需要转换的字符串
	 * @param string $r	如果$r=0直接返回内容,否则需要使用反斜线引用字符串
	 * @return string
	 */
	public static function Html2Text($str, $r = 0) {
		if ($r == 0) {
			return self::SpHtml2Text ( $str );
		} else {
			$str = self::SpHtml2Text ( stripslashes ( $str ) );
			return addslashes ( $str );
		}
	}

	/**
	 * 文本转HTML
	 *
	 * @param string $txt	需要转换的文本内容
	 * @return string
	 */
	public static function Text2Html($txt) {
		$txt = str_replace ( "  ", "　", $txt );
		$txt = str_replace ( "<", "&lt;", $txt );
		$txt = str_replace ( ">", "&gt;", $txt );
		$txt = preg_replace ( "/[\r\n]{1,}/isU", "<br/>\r\n", $txt );
		return $txt;
	}
	/**
	 *
	 * @return Ambigous <unknown, mixed>
	 */
	public static function GetIP() {
		$unknown = 'unknown';
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		/*
		处理多层代理的情况
		或者使用正则方式：$ip = preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : $unknown;
		*/
		if (false !== strpos($ip, ',')){ $ip = reset(explode(',', $ip)); }
			return $ip;
	}

	/**
	 * 从Session取得Access_Token数据
	 * @param int 应用的ID,enewsmember_connect_app.id
	 * @return array|NULL:array("uid","appkey","scope","create_at","expire_in","access_token")
	 */
	public static function GetTokenFromSession($app_id)
	{
        if(!isset($_SESSION)) session_start ();
        if(is_array($_SESSION['e_token']))
        return $_SESSION['e_token'][$app_id];
		return NULL;
	}

   /**
    * 获取交互转换类
    * @param mixed $app_obj,int|string
    * @param array $params 必须包含$params['AppName']/$params['appname'] 或者 $params['AppID']/$params['appid']
    * @return TranSrv
    */
   public static function GetTranSrv($app_obj, array $params = NULL) {
        $apptype = NULL;
        if(is_numeric($app_obj)){
           $app_obj = (int)$app_obj;
           $apptype = strtolower(self::GetAppType($app_obj));
        }
        else
        {
          $apptype =  strval($app_obj);
        }
        $cn = NULL;
        if (isset(self::$TranSrvClasses[$apptype])) {
            $cn = self::$TranSrvClasses[$apptype];
        }
        else
            $cn = ucfirst($apptype) . 'TranSrv';
        if (class_exists($cn) == FALSE) {
            $fn = ECMS_PATH . 'e' . DIRECTORY_SEPARATOR . 'memberconnect' . DIRECTORY_SEPARATOR . $apptype . DIRECTORY_SEPARATOR . 'class.php';
            if (file_exists($fn))
                include_once($fn);
        }
        $ret = NULL;
        if (class_exists($cn)) {
            $ret = new $cn($params);
        }
        return $ret;
    }

    /**
     * 获取交互验证类
     * @param int $app_id : 应用ID,enewsmember_connect_app.id
     * @param array $params : 必须为共用的参数名:array("应用号","应用密钥","访问令牌","授权回调地址","更新的令牌") =>  array("appkey","appsecret","access_token","callbackurl","refresh_token") 也可以在参数名前加chr(1)表示原样传递|array("id") id:授权表的ID,注意:$params元素只能有一个
     * @return EOAuth|NULL
     */
    public static function GetOAuth($app_id, array $params = NULL) {
        $app_id = (int)$app_id;
        $apptype = self::GetAppType($app_id);
        $apptype2 = strtolower($apptype);
        $cn = NULL;
        if (isset(self::$OAuthClasses[$apptype2])) {
            $cn = self::$OAuthClasses[$apptype2];
        }
        else
            $cn = ucfirst($apptype2) . 'OAuth';
        if (class_exists($cn) == FALSE) {
            $fn = ECMS_PATH . 'e' . DIRECTORY_SEPARATOR . 'memberconnect' . DIRECTORY_SEPARATOR . $apptype2 . DIRECTORY_SEPARATOR . 'class.php';
            if (file_exists($fn))
                include_once($fn);
        }
        $ret = NULL;
        if (class_exists($cn)) {
            $pvp = NULL;
            if (!is_int($params['id']) || count($params) > 1) {
                $env = EIService::GetENV($app_id);
                $appk = $env['appkey']; //应用的APPID
                $app_secret = $env['appsecret']; //应用的APPKEY
                $my_url = $env['callbackurl']; //成功授权后的回调地址
                $revokeoauth_url = $env['callbackurl2']; //取消授权时的回调地址
                $pvp = array('应用号' => $appk, '应用密钥' => $app_secret, '授权回调地址' => $my_url);
                if (!empty($params))
                    $pvp = array_merge($pvp, $params);
                if (empty($pvp['访问令牌'])) {
                    if (!empty($pvp['access_token']))
                        $pvp['访问令牌'] = $pvp['access_token'];
                    else {
                        $usid = getcvar('mluserid', 0, TRUE);
                        $pvp['访问令牌'] = self::GetAccessTokenForUID($usid, $app_id);
                    }
                }
            }
            else {
                $pvp =(int)$params['id'];
            }
            $ret = new $cn($pvp);
        }
        return $ret;
    }

    /**
     * 获取交互操作类
     * @param int $app_id : 应用ID,enewsmember_connect_app.id
     * @param array $params : array("appkey","appsecret","access_token")|array("id") id:授权表的ID,注意:$params元素只能有一个|array("appname") appname:应用的名称,注意:$params元素只能有一个,除了ConvertParam外不具备其它功能
     * @return EAction|NULL
     */
    public static function GetAction($app_id, array $params = NULL) {
        $app_id = (int)$app_id;
        $apptype = self::GetAppType($app_id);
        $apptype2 = strtolower($apptype);
        $cn = NULL;
        if (isset(self::$ActionClasses[$apptype2])) {
            $cn = self::$ActionClasses[$apptype2];
        }
        else
            $cn = ucfirst($apptype2) . 'Action';
        if (class_exists($cn) == FALSE) {
            $fn = ECMS_PATH . 'e' . DIRECTORY_SEPARATOR . 'memberconnect' . DIRECTORY_SEPARATOR . $apptype2 . DIRECTORY_SEPARATOR . 'class.php';
            if (file_exists($fn))
                include_once($fn);
        }
        $ret = NULL;
        if (class_exists($cn)) {
            $pvp = NULL;
            if ((!is_int($params['id']) && !is_string($params['appname'])) || count($params) > 1) {
                $env = EIService::GetENV($app_id);
                $appk =(empty($params['appkey'])?$env['appkey']:$params['appkey']); //应用的APPID
                $app_secret = (empty($params['appsecret'])?$env['appsecret']:$params['appsecret']); //应用的APPKEY
                $my_url = $env['callbackurl']; //成功授权后的回调地址
                $revokeoauth_url = $env['callbackurl2']; //取消授权时的回调地址
                $pvp = array('应用号' => $appk, '应用密钥' => $app_secret, '授权回调地址' => $my_url);
                if (!empty($params))
                    $pvp = array_merge($pvp, $params);
                if (empty($pvp['访问令牌'])) {
                    if (!empty($pvp['access_token']))
                        $pvp['访问令牌'] = $pvp['access_token'];
                    else {
                        $usid = getcvar('mluserid', 0, TRUE);
                        $pvp['访问令牌'] = self::GetAccessTokenForUID($usid, $app_id);
                    }
                }
            }
            else {
                if (isset($params['id']))
                    $pvp = (int)$params['id'];
                elseif (isset($params['appname']))
                    $pvp = $params['appname'];
            }
            $ret = new $cn($pvp);
        }
        return $ret;
    }

    /**
         * 输出错误信息
         * @param string $apptype 应用类型
         * @param object $codedata 调用函数附加的参数
         * @param int $mode 输出模式,0:为强制弹出对话框,1为强制输出字符串,NULL为执行系统的printerror
         */
	public static function PutError($apptype,$codedata,$mode=NULL)
        {
            	$apptype=strtolower($apptype);
		$cn=NULL;
		if(isset(self::$ErrorClasses[$apptype]))
		{
			$cn=self::$ErrorClasses[$apptype];
		}
		else
			$cn=ucfirst($apptype).'Error';
		if(class_exists($cn)==FALSE)
		{
			$fn=ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'memberconnect'.DIRECTORY_SEPARATOR.$apptype.DIRECTORY_SEPARATOR.'class.php';
			if(file_exists($fn))
				include_once($fn);
		}
		if(class_exists($cn))
                {
                    if(method_exists($cn, 'showError'))
                        {
                            call_user_func(array($cn, 'showError'),$codedata,$mode);
                        }
                }
        }
	/**
	 * 取消当前授权
	 * @param int $app_id
	 * @return mixed|boolean
	 */
public static function RevokeCurrentOauth($app_id)
    {
        global $dbtbpre,$empire;
        if (empty($app_id) && isset($_SESSION['cappid']))
            $app_id =$_SESSION['cappid'];
        $app_id = (int)$app_id;
        $o = self::GetOAuth($app_id);
        $pti = NULL;
        $tv = self::GetTokenFromSession($app_id);
        if (!empty($tv))
        {
            $pti = array('access_token' => $tv['access_token']);
        } else
        {
            $tk = $o->GetOAuthFromCookie();
            if (!empty($tk) && isset($tk['access_token']))
                $pti = array('access_token' => $tk['access_token']);
        }
        $r = $o->Revokeoauth($pti);
        if($r)
        {
            $moid = mysql_real_escape_string($tv['openid']);
            $maccess_token = mysql_real_escape_string($tv['access_token']);
            $cmd = "DELETE FROM {$dbtbpre}enewsmember_connect WHERE openid='{$moid}' AND aid={$app_id} AND token='{$maccess_token}'";
            $empire->query($cmd);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 根据会员ID获取绑定的第三方交互平台的AccessToken
     * @param int $uid
     * @param int $app_id,在数据库是 enewsmember_connect_app.id
     * @param string $openid,(可以为空,取最近一个)
     * @return string|NULL
     */
    public static function GetAccessTokenForUID($uid, $app_id, $openid='')
    {
        if (empty($uid))
            return NULL;
        $app_id = (int)$app_id;
        global $dbtbpre, $empire;
        $appname = mysql_real_escape_string($appname);
        if(!empty($openid))
        {
           $openid =' AND openid=\''.mysql_real_escape_string($openid).'\' AND expired > now()';
        }
        else {$openid='';}
        $etoken = $empire->fetch1("SELECT token FROM {$dbtbpre}enewsmember_connect WHERE userid={$uid} AND aid={$app_id}{$openid} order by id DESC limit 1");
        if (is_array($etoken) || is_object($etoken))
        {
            return $etoken['token'];
        }
        return NULL;
    }
    /**
     * 根据会员ID获取绑定的第三方交互平台的RefreshToken
     * @param int $uid
     * @param string $appname,在数据库是apptype
     * @param string $openid(可以为空,取最近一个)
     * @return string|NULL
     */
    public static function GetRefreshTokenForUID($uid, $appname, $openid='')
    {
        if (empty($uid))
            return NULL;
        global $dbtbpre, $empire;
        $appname = mysql_real_escape_string($appname);
                if(!empty($openid))
        {
           $openid =' AND openid=\''.mysql_real_escape_string($openid).'\' AND expired > now()';
        }
        else {$openid='';}
        $etoken = $empire->fetch1("SELECT rtoken FROM {$dbtbpre}enewsmember_connect WHERE userid={$uid} AND apptype='{$appname}'{$openid} order by id DESC limit 1");
        if (is_array($etoken) || is_object($etoken))
        {
            return $etoken['rtoken'];
        }
        return NULL;
    }


    /**
	 * 根据第三方交互平台的AccessToken获取绑定的会员ID
	 * @param string $appname
	 * @param string $accesstoken
	 * @return int 没有则返回0
	 */
	public static function GetUIDForAccessToken($appname,$accesstoken)
	{
		global $dbtbpre,$empire;
		$du=$empire->fetch1("SELECT userid FROM {$dbtbpre}enewsmember_connect WHERE token='{$accesstoken}' AND apptype='{$appname}'");
		if(is_array($du) || is_object($du))
		{
			return $du['userid'];
		}
		return 0;
	}
	/**
	 * 获取绑定在会员上的第三方交互账号列表
	 * @param int $uid  会员ID
	 * @return array|NULL array(0=>array("id","aid","apptype","openid","ename","token",'refresh_token',"scope","appkey","appsecret","used"))
	 */
	public static function GetBindEISForUID($uid)
	{
		global $dbtbpre,$empire;
		$sql="SELECT a.id,a.aid,a.apptype,a.openid,a.bindname as ename,a.token,a.rtoken as refresh_token,a.scope,b.appid as appkey,b.appkey as appsecret,unix_timestamp(expired) as expired ,(a.expired > now()) as used FROM {$dbtbpre}enewsmember_connect as a inner join {$dbtbpre}enewsmember_connect_app as b ON a.aid=b.id WHERE a.userid={$uid} and b.isclose=0 ORDER BY used DESC";
		$ret = array();
		$qobj=$empire->query($sql);
		while($r=$empire->fetch($qobj,MYSQL_ASSOC))
		{
                    $r['id'] = (int)$r['id'];
                    $r['aid'] = (int)$r['aid'];
                    $r['expired'] = (int)$r['expired'];
                    $ret[]=$r;
		}
		if(count($ret)<1)return NULL;
		return $ret;
	}
        /**
         * 取当前用户所绑定的第三方应用数组
         * @return array array("id","aid","apptype","openid","ename","token",'refresh_token',"scope","appkey","appsecret","used")
         */
       public static function GetCUBEISForSession()
       {
              return $_SESSION['mlbindeis'];
       }
       /**
        * 设置当前用户所绑定的第三方应用数组
        * @param array $data array("id","aid","apptype","openid","ename","token",'refresh_token',"scope","appkey","appsecret","used")
        */
       public static function SetCUBEISForSession(&$data) {
        if ($data === NULL) {
            unset($_SESSION['mlbindeis']);
            return TRUE;
        }
        else
        {
            $_SESSION['mlbindeis'] = NULL;
            foreach ($data as $d) {
                if (isset($d['id']) && isset($d['aid']) && isset($d['token'])) {
                    if (!is_int($d['id'])) {
                        $d['id'] = (int)$d['id'];
                        $d['aid'] = (int)$d['aid'];
                        $d['expired'] = (int)$d['expired'];
                    }
                }
                $_SESSION['mlbindeis'] = $data;
            }
        }
    }
        /**
     * 取应用的概要(appid,apptype,appname,appkey,appsecret)
     * @global string $dbtbpre
     * @global mysqlquery $empire
     * @param mixed $app_obj
     * @param string $appkey
     * @param string $appsecret
     * @return array | null :array(appid,apptype,appname,appkey,appsecret)
     */
    public static function GetAppSum($app_obj = 0, $appkey = NULL, $appsecret = NULL) {
        if (func_num_args() == 0 || (empty($app_obj) && (empty($appkey) || empty($appsecret))))
            return NULL;
        $find = NULL;
        if (!empty($app_obj)){
             if(is_numeric($app_obj))
                 $find = array('id' => (int)$app_obj);
             else
                 $find = array('appname' => $app_obj);
        }
        else
             $find = array('appkey' => $appkey, 'appsecret' => $appsecret);
        $fv = self::IndexENV($find);
        if (!empty($fv)) {
            $qr = array();
            $qr['appid'] = (int)$fv['id'];
            $qr['appname'] = $fv['name'];
            $qr['apptype'] = $fv['type'];
            $qr['appkey'] = $fv['appkey'];
            $qr['appsecret'] = $fv['appsecret'];
            return $qr;
        }
        else {
            global $dbtbpre,$empire;
            $appkey = mysql_real_escape_string($appkey);
            $appsecret = mysql_real_escape_string($appsecret);
            $sbm = '';
            if(is_numeric($app_obj))
            {
                $sbm ='id='.strval((int)$app_obj);
            }
            else
                $sbm ='appname=\''.mysql_real_escape_string(strval($app_obj)).'\'';
            if (!empty($app_obj))
                $sql = "select id as appid,apptype,appname,appid as appkey,appkey as appsecret FROM {$dbtbpre}enewsmember_connect_app WHERE {$sbm} limit 1";
            else
                $sql = "select id as appid,apptype,appname,appid as appkey,appkey as appsecret FROM {$dbtbpre}enewsmember_connect_app WHERE (appid='{$appkey}' AND appkey='{$appsecret}') limit 1";
            $qr = $empire->fetch1($sql, MYSQL_ASSOC);
            if (is_array($qr)) {
                return $qr;
            }
        }
        return NULL;
    }

}
/**
 * 参数转换,翻译基类
 */
abstract class TranSrv {
    /**
     *应用名称
     * @var string
     */
    protected  $appname = NULL;
    /**
     *应用ID
     * @var int
     */
    protected $appid = NULL;
        /**
         * 参数转换,翻译基类的初始化,不可直接调用
         * @param array $params ,必须包含 $params['AppName']/$params['appname'] 或者  $params['AppID']/$params['appid']
         */
        protected function __construct(array $params) {
        if (isset($params['AppName']))
            $this->appname = $params['AppName'];
        elseif (isset($params['appname']))
            $this->appname = $params['appname'];

        if (isset($params['AppID']))
            $this->appid = $params['AppID'];
        elseif (isset($params['appid']))
            $this->appid = $params['appid'];

        if (!empty($this->appname)) {
            $pl_filename = ECMS_PATH . 'e' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'apprwcode.php';
            if (is_file($pl_filename)) {
                $cax = @include($pl_filename);
                unset($apprw__arr);
                if (!empty($cax) && is_array($cax) && !empty($cax[$this->appname])) {
                    $rreg = &$this->TranslateRegexMap();
                    $rkeyw = &$this->TranslateKeyWordsMap();
                    if (!empty($cax[$this->appname]['tranregexlist']))
                        $rreg = $cax[$this->appname]['tranregexlist'];
                    if (!empty($cax[$this->appname]['trankeywordslist']))
                        $rkeyw = $cax[$this->appname]['trankeywordslist'];
                }
            }
        }
        else {
            throw new EiException('初始化QQTranSrv时没有传入应用名称相关的参数');
        }
    }

    /**
	 * 单条信息最大的长度
	 * @param mixed $kind 信息类型
         * @return int 长度
	 */
        public static function MaxInfoLen($kind=0){
            return 140;
        }

        /**
         * 参数转换表
         * @param string $funkey 入口键(每个函数的转换表可能不一致,0为默认/公用的转换表)
         * @return array 将参数翻译成内部参数
         * @throws EiException
         */
        abstract protected  function &TranslateParamsMap($funkey=0);
        /**
         * 参数转换表(反向)
         * @param string $funkey 入口键(每个函数的转换表可能不一致,0为默认/公用的转换表)
         * @return array 将内部参数翻译成外部可识别的参数
         * @throws EiException
         */
        abstract protected  function &TranslateParamsMapR($funkey=0);
        abstract protected  function &TraKwKey();
        abstract protected  function &TraKwVal();
        abstract protected  function &TranslateRegexMap();
        abstract protected  function &TranslateKeyWordsMap();
    /**
     * 转换参数，
     * @param array $params,前导为chr(1)表示原样传递,$params['ForbidConvertIn']表示禁止转换输入参数,$params['ForbidConvertOut']表示禁止转换输出参数
     * @param string $funkey 入口键(每个函数的转换表可能不一致,0为默认/公用的转换表,NULL为函数名)
     * @param int $direction 转换方向,0为从公共参数(外)=>私有参数(内),1为 私有参数(内)=>公共参数(外),-1为不转换
     */
    public function ConvertParam(&$params, $funkey = 0, $direction = 0) {
        if (empty($params) || $direction == -1)
            return $params;
        if (isset($params['ForbidConvertIn']) && $params['ForbidConvertIn'] && $direction == 0) {
            $ca = array();
            $ca = array_merge($ca, $params);
            unset($ca['ForbidConvertIn']);
            return $ca;
        }
        elseif (isset($params['ForbidConvertOut']) && $params['ForbidConvertOut'] && $direction == 1) {
            $ca = array();
            $ca = array_merge($ca, $params);
            unset($ca['ForbidConvertOut']);
            return $ca;
        }
        $newp = array();
        $opdata = array();//私有的
        $pubmd = NULL;//公共的
        if(!empty($funkey)) {
           if ($direction == 1)  $pubmd = &$this->TranslateParamsMapR(0);
           else  $pubmd = &$this->TranslateParamsMap(0);
        }
        if ($direction == 1) {
            $opdata = &$this->TranslateParamsMapR($funkey);
        }
        elseif ($direction == 0)
            $opdata = &$this->TranslateParamsMap($funkey);
        foreach ($params as $k => $v) {
            if ($k{0} == chr(1)) {
                $k = substr($k, 1);
                $newp[$k] = $v;
            }
            else {
                if (!empty($opdata[$k])) {//私有转换优先
                    if (is_null($params[$opdata[$k]]) == TRUE)
                        $newp[$opdata[$k]] = $v;
                    else
                        $newp[$opdata[$k]] = $params[$opdata[$k]];
                }
                elseif(!empty($pubmd) && !empty($pubmd[$k]))//公有转换
                {
                    if (is_null($params[$pubmd[$k]]) == TRUE)
                        $newp[$pubmd[$k]] = $v;
                    else
                        $newp[$pubmd[$k]] = $params[$pubmd[$k]];
                }
                else//原样复制
                    $newp[$k] = $v;
            }
        }
        return $newp;
    }

    /**
	 * 替换内容中的关键词，以及应用正则式规则
	 * @param string $content
	 * @return string|NULL
	 */
   public function ReplaceKeyWords(&$content){
        $tpk = &$this->TraKwKey();//正则式替换
        $tpv = &$this->TraKwVal();
        $tmap = $this->TranslateRegexMap();
        if (empty($tpk))
        {
            $tpk = array_keys($tmap);
            $tpv = array_values($tmap);
        }
        if (empty($content))
            return $content;
        $content1 = str_replace($tpk, $tpv, $content);
        if (!empty($tmap))
        {
            foreach ($tmap as $k => $v)
            {
                $t1 = @preg_replace($k, $v, $content1);
                if (!empty($t1))
                    $content1 = $t1;
            }
        }
        return $content1;
    }
   public function GetAppName(){
       if(empty($this->appname)){
           if($this->appid!=NULL){
                $sum = EIService::GetAppSum($this->appid);
                if(!empty($sum))
                $this->appname = $sum['appname'];
           }
       }
       return $this->appname;
   }
   public function GetAppID() {
        if (!isset($this->appid) || $this->appid === NULL) {
            $this->appid = 0;
            if (!empty($this->appname)) {
                $sum = EIService::GetAppSum($this->appname);
                if (!empty($sum))
                    $this->appid = (int)$sum['appid'];
            }
        }
        return $this->appid;
    }

    /**
    * 从参数创建一个TranSrv对象
    * @param array $params
    * @return TranSrv
    */
    public static function CreateFromParams(array &$params){
       $ret = NULL;
               if (isset($params['TranSrv']) && !empty($params['TranSrv']) && ($params['TranSrv'] instanceof TranSrv)) {
            $ret = $params['TranSrv'];
        }
        else {
            $appobj = NULL;
            if (isset($params['AppName']) && !empty($params['AppName'])){
                $appobj =  $params['AppName'];
            }
            elseif (isset($params['appid']) && !empty($params['appid'])){
                $appobj =  $params['appid'];
            }
            elseif(isset($params['appname']) && !empty($params['appname'])){
                $appobj =  $params['appname'];
            }
            elseif(isset($params['AppID']) && !empty($params['AppID'])){
                $appobj =  $params['AppID'];
            }
            if($appobj!=NULL){
                $cps = array();
                if(is_numeric($appobj))   $cps['AppID'] = (int)$appobj;
                 else $cps['AppName'] = $appobj;
                 $ret =  EIService::GetTranSrv($appobj,$cps);
            }
            else{
                $appis = NULL;
                if ($params['id'])
                    $appis = EIService::GetAppSum($params['id']);
                elseif ($params['应用密钥']) {
                    $appis = EIService::GetAppSum(0, $params['应用号'], $params['应用密钥']);
                }
                elseif ($params[chr(1) . 'appsecret']) {
                    $appis = EIService::GetAppSum(0, $params[chr(1) . 'appkey'], $params[chr(1) . 'appsecret']);
                }
                elseif ($params['appsecret']) {
                    $appis = EIService::GetAppSum(0, $params['appkey'], $params['appsecret']);
                }
                if (!empty($appis)) {
                    $ret =  EIService::GetTranSrv($appis['apptype'],$appis);
                }
            }
        }
        return $ret;
   }
}
/**
 * 验证类的基类
 */
class EOAuth{
	/**
	 * App Key
	 */
	protected $appkey;
	/**
	 * App Secret
	 */
	protected $appsecret;
	/**
	 * 用户令牌
	 */
	protected $access_token;
        /**
        * refresh_token
        */
        protected $refresh_token;
	/**
	 * 在第三方账号的ID号(用户的OpenID)
	 * @var string
	 */
	protected $openid;
        /**
         *权限列表
         * @var string
         */
        protected $scope;
        /**
         *有效期
         * @var int
         */
        protected $expired;
        /**
         *access_token生成时间值
         * @var int
         */
        protected $bindtime;
        /**
         *在数据库中的ID
         * @var int
         */
        protected $id;
        /**
         * 所属用户
         * @var int
         */
        protected $userid;
        /**
         * 在第三方应用的用户名字
         * @var string
         */
        protected $bindname;
        /**
         *应用类型
         * @var string
         */
        protected $apptype;
        /**
         *应用ID
         * @var int
         */
        protected $aid;
	/**
	 * 是否打印调试信息
	 */
	protected $debug = FALSE;
	/**
	 * 授权URL基址
	 */
	protected $_authorizeUrl = '';
	/**
	 * 请求令牌URL基址
	 */
	protected $_accessTokenURL = '';
        /**
         *请求OpenID URL基址
         * @var string
         */
        protected $_openidURL = '';
	/**
	 * 取消授权的基址
	 */
	protected $_revokeoauthUrl = '';
	/**
	 * 外部交互类型名称(enewseitype.name)
	 */
	protected $_appname;

	/**
	 * EIService ,外部交互服务类
	 */
	protected $_EiService;
	/**
         *用户的IP
         * @var string
         */
	protected $_clip;
        /**
         *转换翻译服务
         * @var TranSrv
         */
        protected  $_tranSrv;
       function __construct(TranSrv &$transrvObj=NULL)	{
           $this->userid = intval(getcvar('mluserid',0,TRUE));
	   $this->_EiService= new EIService();
           $this->_tranSrv = $transrvObj;
	}
	function __destruct(){
		$this->_EiService = NULL;
	}
	function __toString() {
		return "OAuth2[key=$this->appkey,secret=$this->appsecret]";
	}
	/**
	 * generates the basic string serialization of a token that a server
	 * would respond to request_token and access_token calls with
	 */
	function to_string() {
		return "oauth_token=" .
				EIService::urlencode_rfc3986($this->appkey) .
				"&oauth_token_secret=" .
				EIService::urlencode_rfc3986($this->appsecret);
	}
	public function AccessTokenURL(array &$param=NULL)  { return $this->_accessTokenURL; }
	public function AuthorizeURL(array &$param=NULL)    { $this->_authorizeUrl; }
	/**
	 * 返回指定access_token取消授权的URL
	 * @param array $params 参数，必须包含access_token键
	 */
	public function RevokeoauthURL(array &$param=NULL){return NULL;}
	/**
	 * 取消指定access_token的授权(会从本地数据库中删除指定授权)
	 * @param array $params 参数，必须包含access_token键,CheckUser键(在执行之后会被清除)表示是否检测为当前用户,否则不执行解除
	 */
	public function Revokeoauth(array $params=NULL){return FALSE;}
	public function AccessToken(array &$param=NULL ){throw new EiException("没有实现请求令牌的方法".__FUNCTION__);}
        /**
         * 获取第三方用户的信息
         * @param type $access_token
         * @param type $openid
         * @throws EiException
         * @return array 可自由格式 , 但必须具有字段 ['用户名']
         */
        public function GetEiUserInfo($access_token=NULL,$openid=0){throw new EiException("没有实现请求令牌的方法".__FUNCTION__);}
	public function AppKey(){return $this->appkey;}
	public function AppSecret(){return $this->appsecret;}
	public function GetAccessToken(){return $this->access_token;}
        public function GetRefreshToken(){return $this->refresh_token;}
	public function GetOpenID(){return $this->openid;}
        public function GetIP() {
        if (empty($this->_clip))
            $this->_clip = $this->_EiService->GetIP();
        return $this->_clip;
    }

    /**
     * 设置客户端IP
     * @param string $rip
     */
    public function SetIP($rip) {
        $this->_clip = $rip;
    }

    /**
     * 获取apptype,应用类型(一种类型可包含多个应用)
     * @return string
     */
    public function GetAppType(){
        if (empty($this->apptype))
        {
            $myaid = $this->GetAID();
            if(!empty($myaid)){
                $this->apptype = EIService::GetAppType($myaid);
             }
            elseif (!empty($this->appkey) && !empty($this->appsecret)) //从数据库读取
            {
                $find = array('appkey' => $this->appkey, 'appsecret' => $this->appsecret);
                $fv = EIService::IndexENV($find);
                if (!empty($fv))
                {
                    $this->aid = $fv['id'];
                    $this->_appname = $fv['name'];
                    $this->apptype = $fv['type'];
                } else
                {
                    global $dbtbpre;
                    $sql = "select id,apptype,appname FROM {$dbtbpre}enewsmember_connect_app WHERE appid='{$this->appkey}' AND appkey='{$this->appsecret}'";
                    $qr = $this->_EiService->empire->fetch1($sql,MYSQL_ASSOC);
                    if (is_array($qr) || is_object($qr))
                    {
                        $this->aid = $qr['id'];
                        $this->_appname = $qr['appname'];
                        $this->apptype = $fv['apptype'];
                    }
                }
            }
        }
        return $this->apptype;
    }

        /**
         * 取应用的ID(enewsmember_connect_app.id)
         * @return int
         */
        public function GetAID() {
        if (!$this->aid)
        {
            if (!empty($this->_tranSrv)) {
                $this->aid = $this->_tranSrv->GetAppID();
            }
            elseif (!empty($this->appkey) && !empty($this->appsecret))//从数据库读取
            {
                $find = array('appkey' => $this->appkey, 'appsecret' => $this->appsecret);
                $fv = EIService::IndexENV($find);
                if (!empty($fv))
                {
                    $this->aid = $fv['id'];
                    $this->_appname = $fv['name'];
                    $this->apptype = $fv['type'];
                }
                else
                {
                    global $dbtbpre;
                    $sql = "select id,apptype,appname FROM {$dbtbpre}enewsmember_connect_app WHERE appid='{$this->appkey}' AND appkey='{$this->appsecret}'";
                    $qr = $this->_EiService->empire->fetch1($sql,MYSQL_ASSOC);
                    if (is_array($qr) || is_object($qr))
                    {
                        $this->aid = $qr['id'];
                        $this->_appname = $qr['appname'];
                        $this->apptype = $fv['apptype'];
                    } else
                        $this->aid = 1;
                }
            }
        }
        return $this->aid;
    }
        /**
         * 获取应用的名称(enewsmember_connect_app.appname)
         * @global string $dbtbpre
         * @return string
         */
  public function GetAppName() {
        global $dbtbpre;
        if (!empty($this->_appname))
            return $this->_appname;
        else {
            if (!empty($this->_tranSrv)) {
                $this->_appname = $this->_tranSrv->GetAppName();
            }
            elseif ((!empty($this->appkey) && !empty($this->appsecret)) || $this->aid) {//从数据库读取
                $find = NULL;
                if ($this->aid)
                    $find = array('id' => $this->aid);
                else
                    $find = array('appkey' => $this->appkey, 'appsecret' => $this->appsecret);
                $fv = EIService::IndexENV($find);
                if (!empty($fv)) {
                    $this->aid = (int)$fv['id'];
                    $this->_appname = $fv['name'];
                    $this->apptype = $fv['type'];
                }
                else {
                    $sql = "select id,apptype,appname FROM {$dbtbpre}enewsmember_connect_app WHERE appid='{$this->appkey}' AND appkey='{$this->appsecret}'";
                    $qr = $this->_EiService->empire->fetch1($sql, MYSQL_ASSOC);
                    if (is_array($qr) || is_object($qr)) {
                        $this->aid = (int)$qr['id'];
                        $this->_appname = $qr['appname'];
                        $this->apptype = $fv['apptype'];
                    }
                }
            }
        }
        return $this->_appname;
    }

    /**
	 * 设置第三方应用的信息，重置etype,etypeID,appkey,appsecret
	 * @param object $t 第三方应用的ID或名称(enewsmember_connect_app.id,enewsmember_connect_app.appname)
	 * @return boolean
	 */
    public function SetAID($t) {
        $r = FALSE;
        $find = array();
        if (!empty($t) && (is_numeric($t) || is_string($t)))
        {
            if (is_numeric($t))
                $find['id'] = intval($t);
            else
                $find['appname'] = $t;
            $fv = EIService::IndexENV($find);
            if ($fv)
            {
                $this->aid = $fv['id'];
                $this->_appname = $fv['name'];
                $this->apptype = $fv['type'];
                $this->appkey = $fv['appkey'];
                $this->appsecret = $fv['appsecret'];
                $r = TRUE;
            }
        } else
        {
            if ($this->aid != 0 || !empty($this->_appname))
            {
                if ($this->aid != 0)
                    $find['id'] = intval($this->aid);
                else
                    $find['appname'] = strval($this->_appname);
                $fv = EIService::IndexENV($find);
                if ($fv)
                {
                    $this->aid = $fv['id'];
                    $this->_appname = $fv['name'];
                    $this->apptype = $fv['type'];
                    $this->appkey = $fv['appkey'];
                    $this->appsecret = $fv['appsecret'];
                    $r = TRUE;
                }
            } elseif (!empty($this->appkey) && !empty($this->appsecret))
            {
                $find['appkey'] = $this->appkey;
                $find['appsecret'] = $this->appsecret;
                $fv = EIService::IndexENV($find);
                if ($fv)
                {
                    $this->aid = $fv['id'];
                    $this->_appname = $fv['name'];
                    $this->apptype = $fv['type'];
                    $r = TRUE;
                }
            }
        }



        return $r;
    }
/**
 * 取OpenID信息
 * @param array $params
 * @return array 允许返回附加信息,但必须包含openid键
 * @throws EiException
 */
    public function GetOpenIdInfo(array &$params=NULL){throw new EiException("没有实现获取授权信息的方法");}
    /**
     * 保存已经生成完整的access_token 到 enewsmember_connect表中
     * 如果没有指定userid则不会保存
     * @global string $dbtbpre
     * @return boolean
     */
    public function SaveTokenInfo(){
        global $dbtbpre;
        if (empty($this->access_token))//如果没有指定用户就不保存
            return FALSE;

        if (empty($this->openid))
        {
            $pti = array('access_token' => $this->access_token);
            $this->GetOpenIdInfo($pti);
        }
        if (empty($this->_appname))
        {
            $this->_appname = $this->GetAppName();
        }
        $tinfo = $this->GetData();
        if (is_array($tinfo) || is_object($tinfo))
        {
            //保存到数据库：
            //先查找是否存在记录：
            $exid = NULL;
            if ($tinfo['id'])
                $exid = $this->_EiService->empire->fetch1("SELECT id,token,unix_timestamp(expired) as expired,scope,userid,bindname FROM {$dbtbpre}enewsmember_connect WHERE id=" . $tinfo['id'], MYSQL_ASSOC);
            elseif ($tinfo['aid'])
                $exid = $this->_EiService->empire->fetch1("SELECT id,token,unix_timestamp(expired) as expired,scope,userid,bindname FROM {$dbtbpre}enewsmember_connect WHERE aid=" . $tinfo['aid'] . " AND openid='{$tinfo['openid']}'", MYSQL_ASSOC);
            else
                $exid = $this->_EiService->empire->fetch1("SELECT id,token,unix_timestamp(expired) as expired,scope,userid,bindname FROM {$dbtbpre}enewsmember_connect WHERE openid='{$tinfo['openid']}' AND apptype='" . $this->GetAppName() . '\' order by id DESC limit 1', MYSQL_ASSOC);
        //修正字段名:
            $tinfo['token'] = $tinfo['access_token'];
            unset($tinfo['access_token']);
            $tinfo['rtoken'] = $tinfo['refresh_token'];
            unset($tinfo['refresh_token']);
            if (!empty($tinfo['expired']))
                $tinfo['expired'] = chr(1) . 'from_unixtime(' . $tinfo['expired'] . ')'; //有效期特殊处理
            $ExcludeFields = array('id','appkey','appsecret');
            $sql = NULL;
            $isadd = FALSE;
            if (is_array($exid) || is_object($exid))//已经存在：
            {
                if(!$this->userid && $exid['userid'])
                    $tinfo['userid']=$this->userid= intval($exid['userid']);
                if (empty($tinfo['rtoken']))
                    $ExcludeFields[] = 'rtoken';
                if (($exid['userid'] == $tinfo['userid']) || !$tinfo['userid'])
                    $ExcludeFields[] = 'userid';
                if (!$exid['userid'] || !$tinfo['userid'])
                    $tinfo['userid'] = intval(getcvar('mluserid', 0, TRUE));
                if($exid['bindname'])  $ExcludeFields[] = 'bindname';
                $ExcludeFields[] = 'bindtime';
                $ExcludeFields[] = 'aid';
                $ExcludeFields[] = 'apptype';
                $sql = BuildSingleTableUpdateSqlExp("UPDATE {$dbtbpre}enewsmember_connect SET", $tinfo, 'WHERE id=' . $exid['id'], $ExcludeFields); //更新
            }
            else //新增：
            {
                $isadd = TRUE;
                if (empty($this->bindname))
                {
                    $uinfo = $this->GetEiUserInfo($this->access_token, $this->openid);
                    if (isset($uinfo['用户名']))
                    {
                        $tinfo['bindname'] = $this->bindname = $uinfo['用户名'];
                    }
                }
                $sql = BuildSingleTableInsertSqlExp("{$dbtbpre}enewsmember_connect", $tinfo, NULL, $ExcludeFields);
            }
            if (empty($sql) == false)
            {
                $r = $this->_EiService->empire->query($sql);
                if (empty($r) == false && intval($r) > 0)
                {
                    if ($isadd)
                        $this->id = intval($this->_EiService->empire->lastid());
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 取得当前的access_token数据
     * @return array('id','aid','appkey','appsecret','access_token','refresh_token','scope','openid','expired','bindtime','bindname','apptype','userid')
     */
    public function &GetData(){
        $a = array('id'=>$this->id,'aid'=>$this->aid, 'appkey'=>$this->appkey,'appsecret'=>$this->appsecret,'access_token'=>$this->access_token,'refresh_token'=>$this->refresh_token,'scope'=>$this->scope,'openid'=>$this->openid,'expired'=>$this->expired,'bindtime'=>$this->bindtime,'userid'=>$this->userid,'apptype'=>$this->apptype,'bindname'=>$this->bindname);
        return $a;
    }
    /**
     * 从数据库加载信息
     * @global type $dbtbpre
     * @param int $id 数据库中的id
     * @return boolean 是否成功
     */
    public function LoadDataForID($id){
        if(!$id) return FALSE;
        $params = array('id'=>$id);
        return $this->LoadData($params);
    }
/**
 * 从数据库加载信息
 * @param array $params ([id],[aid,[openid|access_token|refresh_token|bindname|userid]],[appkey,appsecret,[openid|access_token|refresh_token|bindname|userid]])
 * @return boolean
 */
 public function LoadData(array $params){
      global $dbtbpre;
     if(empty($params)) return FALSE;
     $sqlcmd = '';
     if(isset($params['id']) && $params['id'])
     {
        $sqlcmd .= 'c.id='.intval($params['id']);
     }
     else
     {
         if(isset($params['aid']) && $params['aid'])
         {
             if(!empty($params['openid']))
             {
                    $sqlcmd .= 'c.aid='.intval($params['aid']).' AND c.openid=\''.mysql_real_escape_string($params['openid']).'\'';
             }
             else
             {
                 if(!empty($params['access_token']))
                     $sqlcmd .= 'c.aid='.intval($params['aid']).' AND c.token=\''.mysql_real_escape_string($params['access_token']).'\'';
                 elseif(!empty($params['refresh_token']))
                     $sqlcmd .= 'c.aid='.intval($params['aid']).' AND c.rtoken=\''.mysql_real_escape_string($params['refresh_token']).'\'';
                 elseif(!empty($params['userid']) || !empty($params['bindname']))
                 {
                     $sqlcmd .= 'c.aid='.intval($params['aid']).' AND ';
                     if(!empty($params['bindname']))
                         $sqlcmd .= 'c.bindname=\''.mysql_real_escape_string($params['bindname']).'\'';
                     if($params['userid'])
                         $sqlcmd .= 'c.userid='.intval($params['userid']);
                 }
             }
         }
         elseif(!empty($params['appkey']) && !empty($params['appsecret']))
         {
             if(!empty($params['openid']))
             {
                    $sqlcmd .= 'a.appid=\''.mysql_real_escape_string($params['appkey']).'\' AND a.appkey=\''.mysql_real_escape_string($params['appsecret']).'\' AND c.openid=\''.mysql_real_escape_string($params['openid']).'\'';
             }
             else
             {
                 if(!empty($params['access_token']))
                     $sqlcmd .= 'a.appid=\''.mysql_real_escape_string($params['appkey']).'\' AND a.appkey=\''.mysql_real_escape_string($params['appsecret']).'\' AND c.token=\''.mysql_real_escape_string($params['access_token']).'\'';
                 elseif(!empty($params['refresh_token']))
                     $sqlcmd .= 'a.appid=\''.mysql_real_escape_string($params['appkey']).'\' AND a.appkey=\''.mysql_real_escape_string($params['appsecret']).'\'  AND c.rtoken=\''.mysql_real_escape_string($params['refresh_token']).'\'';
                elseif(!empty($params['userid']) || !empty($params['bindname']))
                {
                    $sqlcmd .= 'a.appid=\''.mysql_real_escape_string($params['appkey']).'\' AND a.appkey=\''.mysql_real_escape_string($params['appsecret']).'\'  AND ';
                    if(!empty($params['userid'])) $sqlcmd .= 'c.userid='.intval($params['userid']);
                    if(!empty($params['bindname']))  $sqlcmd .= 'c.bindname=\''.mysql_real_escape_string($params['bindname']).'\'';
                }
                else
                     $sqlcmd .= 'a.appid=\''.mysql_real_escape_string($params['appkey']).'\' AND a.appkey=\''.mysql_real_escape_string($params['appsecret']).'\' ';

             }
         }
         if(empty($sqlcmd)) return FALSE;
         else $sqlcmd .= ' order by c.id DESC limit 1;';
     }
    if(empty($sqlcmd)) return FALSE;
    $sqlcmd = "SELECT c.id,a.id as aid,c.userid,c.openid,c.bindtime,c.token,unix_timestamp(c.expired) as expired,c.scope,c.bindname,c.rtoken,a.apptype,a.appid as appkey,a.appkey as appsecret,a.appname FROM  {$dbtbpre}enewsmember_connect_app a LEFT JOIN {$dbtbpre}enewsmember_connect c ON a.id = c.aid  WHERE ".$sqlcmd;
     $d=$this->_EiService->empire->fetch1($sqlcmd,MYSQL_ASSOC);
        if(!empty($d))
        {
            $this->id = intval($d['id']);
            $this->aid = intval($d['aid']);
            $this->userid = intval($d['userid']);
            $this->openid = $d['openid'];
            $this->bindtime = (int)$d['bindtime'];
            $this->access_token = $d['token'];
            $this->expired = (int)$d['expired'];
            $this->scope = $d['scope'];
            $this->bindname = $d['bindname'];
            $this->refresh_token = $d['rtoken'];
            $this->apptype = $d['apptype'];
            $this->appkey = $d['appkey'];
            $this->appsecret = $d['appsecret'];
            $this->_appname = $d['appname'];
            return TRUE;
        }
        return FALSE;
 }

   	/**
	 * 把已经取得的授权信息保存到Cookie
	 * @param array $TokenInfo array('appkey','appsecret','access_token','refresh_token','scope','openid','expired','bindtime')
	 *
	 */
   public function SaveOAuthToCookie(array &$TokenInfo){
        if (empty($TokenInfo))
            $TokenInfo = $this->GetData();
        if (empty($TokenInfo) == FALSE)
        {
            $v = http_build_query($TokenInfo);
            $v = MsgCore::Encrypt($v); //加密
            setcookie(strval($this->GetAID()) . 'EI' . $this->GetOpenID(), $v,0,GetEPath());
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 从Cookie中取得授权信息
     * @return array('appkey','appsecret','access_token','refresh_token','scope','openid','expired','bindtime')
     */
    public function GetOAuthFromCookie(){
        $key = strval($this->GetAID()) . "EI" . $this->GetOpenID();
        $token = array();
        if (isset($_COOKIE [$key]) && $cookie = $_COOKIE [$key])
        {
            $v = MsgCore::Decrypt($cookie); //解密
            parse_str($v, $token);
        }
        if (!empty($token))
        {
            return $token;
        }
        return NULL;
    }
  /**
   * 是否支持手动续期Token的操作
   * @return boolean
   */
  public function SupportRefreshToken(){
      return FALSE;
   }
   /**
    * 手动续期令牌
    * @param string $refresh_token
    * @throws EiException
    */
    public function DoRefreshToken($refresh_token = NULL) {
        throw new EiException("没有实现令牌续期的方法" . __FUNCTION__);
    }
    /**
     * 格式化输出Token信息,以便外部使用
     * @param array $tokeninfo
     * @return array
     */
    public function FormatTokenInfo(array $tokeninfo)
    {
        return $tokeninfo;
    }
    /**
     * 从数据库删除此授权
     */
    public function DeleteTokenOnData()
    {
        global $dbtbpre;
        $wcmd = array();
                if($this->id) $wcmd[] ='id = '.strval($this->id);
       elseif($this->aid && (!empty($this->openid) || !empty($this->access_token) || !empty($this->refresh_token)))
        {
             if($this->aid) $wcmd[] = 'aid='.$this->aid;
            if(!empty($this->openid)) $wcmd[]='openid=\''.mysql_real_escape_string($this->openid).'\'';
            else {
                if(!empty($this->access_token)) $wcmd[] ='token=\''.mysql_real_escape_string($this->access_token).'\'';
                else{ $wcmd[] ='rtoken=\''.mysql_real_escape_string($this->refresh_token).'\'';}
            }
        }
        if(count($wcmd)==0) return FALSE;
        $cmd = "DELETE FROM {$dbtbpre}enewsmember_connect WHERE ".implode(' AND ',$wcmd);
        $r = $this->_EiService->empire->query($cmd);
        if($r && $this->_EiService->empire->affectnum()<1) $r = FALSE;
        return $r;
    }
    /**
     * 从外部获取EiService对象
     * @param EOAuth $oauther
     * @return EIService | NULL
     */
    public function &GetEiService(EOAuth &$oauther) {
        if ($oauther->access_token == $this->access_token)
            return $this->_EiService;
        return NULL;
    }
    /**
     * 取当前所用的转换翻译对象
     * @return TranSrv
     */
    public function &GetTranSrv(){
        return $this->_tranSrv;
    }
    /**
     * 设置当前所用的转换翻译对象
     * @param TranSrv $transrvObj
     */
 public function SetTranSrv(TranSrv $transrvObj){
        $this->_tranSrv = $transrvObj;
   }

}

/**
 * 操作类的基类
 */
abstract class EAction
{
    	/**
	 * 存储授权信息
	 *
	 * @var EOAuth
	 */
	protected $oauth;
        /**
         *转换翻译服务(引用,与其它对象共用)
         * @var TranSrv
         */
        protected  $_tranSrv;
   /**
    * 默认的授权类构造函数
    * @param EOAuth $oauth
    */
   protected  function __construct(EOAuth &$oauth=NULL) {
       $this->oauth = $oauth;
    }
    /**
     * 手动续期令牌
     * @return Bool 操作是否成功
     */
   public function RefreshToken() {
        if (!empty($this->oauth) && $this->oauth->SupportRefreshToken() && $this->oauth->GetRefreshToken() != NULL) {
            return $this->oauth->DoRefreshToken();
        }
        return FALSE;
    }


/**
 * 获取短网址(默认调用百度接口)
 * @param array $params,参数,必须包含[chr(1).'url']或 ['长网址'],可选参数:[chr(1).'alias']或['别名']-自定义短网址
 * @return  array(s=>短网址,l=>长网址)|boolean
 */
public function ShortUrl(array &$params) {
       $myps = $this->GetTranSrv()->ConvertParam($params, __FUNCTION__);
        if (!empty($myps) && !empty($myps['url'])) {
            return EIService::DoShortUrl($myps);
        }
        else {
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        }
        return FALSE;
    }
    /**
     * 取当前所用的转换翻译对象
     * @return TranSrv
     */
    public function &GetTranSrv(){
        return $this->_tranSrv;
    }
    /**
     * 设置当前所用的转换翻译对象(注意:传入的是引用)
     * @param TranSrv $transrvObj
     */
 public function SetTranSrv(TranSrv &$transrvObj){
        $this->_tranSrv = $transrvObj;
   }
 	/**
	 * 获取验证对象
	 *
	 * @return EOAuth
	 */
   public function &GetOAuth() {
	return $this->oauth;
    }
}
/**
 *
 * 基本的操作接口
 *
 */
interface IBaseInfoAction{
	/**
	 * 获取用户发布的信息列表
	 * @param array $params 参数
	 * @return array
	 */
	function UserTimeline(array &$params);
	/**
	 * 获取一条用户发布的信息
	 * @param array $params 参数
	 * @return array
	 */
	function GetSingleInfo(array &$params);
	/**
	 * 转发一条信息
	 * @param array $params 参数
	 * @return array
	 */
	function RepostInfo(array &$params);
	/**
	 * 删除一条信息
	 * @param array $params 参数
	 * @return array
	 */
	function DestroyInfo(array &$params);
	/**
	 * 发布一条信息(包括图文)
	 * @param array $params 参数
	 * @return array
	 */
	function CreateInfo(array &$params);

	/**
	 * 获取一条信息的评论列表
	 * @param array $params 参数
	 * @return array
	 */
	function ListComments(array &$params);
	/**
	 * 发布一条评论
	 * @param array $params 参数
	 */
	function CreateComments(array &$params);
	/**
	 * 删除一条评论
	 * @param array $params 参数
	 */
	function DestroyComments(array &$params);
	/**
         * 输出错误信息
         * @param array $params 参数  array('返回码','消息','模式')
         */
        function PutError(array &$params);
        /**
	 * 根据用户ID/名称获取用户信息
	 * @param array $params
	 * @return array 用户信息
	 */
	function GetUserInfo(array &$params);
	/**
	 * 获取用户的联系邮箱
	 * @param array $params 参数
	 * @return string
	 */
	function GetUserEmail($params);
	/**
	 * 获取朋友列表
	 * @param array $params 参数
	 * @return array
	 */
	function GetActiveFriends(array &$params);
	/**
	 * 关注一个用户
	 * @param array $params 参数
	 */
	function CreateFriendships(array &$params);
	/**
	 * 取消关注一个用户
	 * @param array $params  参数
	 */
	function DestroyFriendships(array &$params);
	/**
	 * 截短内容
	 * @param $str string 原始内容
	 * @param $submode int 截取的模式，SUBSTR_MODE_COUNT 或者 SUBSTR_MODE_BYTE
	 */
	function TruncateContent($str,$submode);


}
/**
 *  高级的操作接口
 *
 */
interface AdvancedAction{
	/**
	 * 获取用户的双向关注列表，即互粉列表
	 * @param array $params 参数
	 */
	function BilateralFriends(array &$params);
	/**
	 * 回复一条评论
	 * @param array $params 参数
	 */
	function ReplyComments(array &$params);

	
	
}
/**
 * 错误类接口
 */
interface IEIError
{
    static function showError($code, $description = NULL,$mode=0);
}
//初始化变量
EIService::GenerateApps();