<?php
/* 新浪微博操作类
 * @version 2.0.0
 * @author LGM
 * @copyright © 2013, SZHGH Corporation. All rights reserved.
 */
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'eibase.php' );
include_once(SINACLASS_PATH.'SinaTranSrv.class.php');
require_once(SINACLASS_PATH.'SinaOauth.class.php');

/**
 * 新浪微博操作类V2
 * 
 * @package sae
 * @author LGM
 * @version 1.0
 *         
 */
class SinaAction extends EAction implements IBaseInfoAction, AdvancedAction {
	
	/**
	 * 重载的构造函数
	 * @param array $params : array("应用号","应用密钥","访问令牌","更新的令牌")|array(chr(1)."appkey",chr(1)."appsecret",chr(1)."access_token",chr(1)."refresh_token")|array("id")
	 */
private function construct2(array $params) {
          $myps = $params;
          $this->_tranSrv =TranSrv::CreateFromParams($params);
          if(!empty($this->_tranSrv)){
               $myps = $this->_tranSrv->ConvertParam($params, __FUNCTION__);  
               $myps['TranSrv'] = &$this->_tranSrv;
          }              
          if(empty($myps ['oauth'])){
               $appsum =EIService::GetAppSum(0,$myps['appkey'],$myps['appsecret']);
               $myps['oauth'] = EIService::GetOAuth($appsum['appid'], $myps);
          }
        if (!empty($myps)) {
            if (isset($myps['appkey']) && isset($myps['appsecret'])) {
                $oautho = NULL;
                if(isset($myps ['oauth'])){
                    $oautho = &$myps ['oauth'];
                }
                $tranSrvr = NULL;
                if(isset($myps ['TranSrv']))
                    $tranSrvr = &$myps ['TranSrv'];
                else {
                    if(isset($this->_tranSrv))
                       $tranSrvr =&$this->_tranSrv;
                }
                return $this->__construct(
                         $myps['appkey'], $myps['appsecret'],
                        $tranSrvr,
                        (isset($myps['access_token']) ? $myps['access_token'] : NULL),
                        (isset($myps['refresh_token']) ? $myps['refresh_token'] : NULL),
                        $oautho
                );
            }
            elseif (isset($myps['id'])) {
                return $this->__construct((int)$myps['id']);
            }
        }
    }

    /**
 * SinaAction构造函数
 * @param type $akey string|array|int 如果只具有一个参数,而且为整数时,表示为授权表的ID(enewsmember_connect.id),如果只具有一个参数,而且为字符串时,表示为应用表的名称(enewsmember_connect_app.appname)
 * @param string $skey
 * @param SinaTranSrv  $tranSrvRef 转换翻译对象引用 
 * @param string $access_token
 * @param string $refresh_token
 * @param SinaOAuth $oauth 请传入引用
 * @return SinaOAuth
 */
function __construct($akey, $skey = NULL,SinaTranSrv $tranSrvRef=NULL, $access_token = NULL, $refresh_token = NULL, SinaOAuth $oauth = NULL) {
        if (func_num_args() == 1 && is_array($akey))
            return $this->construct2($akey);
           parent::__construct($oauth);
           if(empty($this->_tranSrv) && !empty($this->oauth)) $this->_tranSrv = &$this->oauth->GetTranSrv();
          if($tranSrvRef != $this->_tranSrv)
           $this->_tranSrv = $tranSrvRef;
     if (empty($this->oauth)) {
            if (func_num_args() == 1) {
                if (is_int($akey)) {
                    $this->oauth = new SinaOAuth($akey);
                }
                elseif (empty($this->_tranSrv) && is_string($akey)) {//没有EOauth对象的情况
                    $pp1 = array('AppName'=>$akey);
                    $this->_tranSrv = new SinaTranSrv($pp1);                    
                }
            }
            else{
               $this->oauth = new SinaOAuth($akey, $skey,$tranSrvRef, $access_token, $refresh_token); 
            }                
        }
        if(empty($this->_tranSrv) && !empty($this->oauth)){
              $this->_tranSrv = &$this->oauth->GetTranSrv();
        }      
         
    }

    /**
	 * 开启调试信息
	 *
	 * 开启调试信息后，SDK会将每次请求微博API所发送的POST Data、Headers以及请求信息、返回内容输出出来。
	 *
	 * @access public
	 * @param bool $enable
	 *        	是否开启调试信息
	 * @return void
	 */
	function set_debug($enable) {
		$this->oauth->debug = $enable;
	}
	
	/**
	 * 设置用户IP
	 * SDK默认将会通过$_SERVER['REMOTE_ADDR']获取用户IP，在请求微博API时将用户IP附加到Request Header中。但某些情况下$_SERVER['REMOTE_ADDR']取到的IP并非用户IP，而是一个固定的IP（例如使用SAE的Cron或TaskQueue服务时），此时就有可能会造成该固定IP达到微博API调用频率限额，导致API调用失败。此时可使用本方法设置用户IP，以避免此问题。
	 * 
	 * @param string $ip
	 *        	用户IP
	 * @return bool IP为非法IP字符串时，返回false，否则返回true
	 */
	function set_remote_ip($ip) {
		if (ip2long ( $ip ) !== false) {
			$this->oauth->remote_ip = $ip;
			return true;
		} else {
			return false;
		}
	}
	
        /**
         * 输出错误信息
         * @param array $params array('返回码','消息','模式')
         * 模式=>0:为强制弹出对话框,1为强制输出字符串,2为throw Exption,NULL为执行系统的printerror
         * @return boolean | String
         */
        public function PutError(array &$params)
        {
            $myps= $this->_tranSrv->ConvertParam($params,__FUNCTION__);
            if(empty($myps) || (empty($myps['code']) && empty($myps['msg']))) return FALSE;
            $code = $myps['error'];            
            $description = $myps['msg'];
            $mode = $myps['mode'];
            if(is_array($code) && isset($code['error']))
            {
                $code = intval($code['error']);
                if(empty($description)) $description = $code['error'];
            }
            return SinaError::showError($code,$description,$mode);
        }
	/**
	 * 获取用户发布的信息列表(接口IBaseInfoAction)
	 * 
	 * @param array $myps
	 *        	参数
	 * @param array $params[访问令牌]|$params[#access_token]
	 *        	: 授权密钥,可省略
	 * @param array $params[user]
	 *        	： 用户的ID或者用户名称,也可以明确指定$params[#uid], $params[#screen_name]
	 * @param array $params[开始ID] | $params[#since_id]
	 *        	: 若指定此参数，则返回ID比since_id大的微博（即比since_id时间晚的微博），默认为0。
	 * @param array $params[最大ID] | $params[max_id]
	 *        	: 若指定此参数，则返回ID小于或等于max_id的微博，默认为0。
	 * @param array $params[count]
	 *        	: 单页返回的记录条数，最大不超过100，超过100以100处理，默认为20。
	 * @param array $params[page]
	 *        	: 返回结果的页码，默认为1。
	 * @param array $params[仅当前应用] | $params[#base_app]
	 *        	: 是否只获取当前应用的数据。0为否（所有数据），1为是（仅当前应用），默认为0。
	 * @param array $params[元数据] | $params[#feature]
	 *        	: 过滤类型ID，0：全部、1：原创、2：图片、3：视频、4：音乐，默认为0。
	 * @param array $params[返回简单用户] | $params[#trim_user]  
	 *        	: 返回值中user字段开关，0：返回完整user字段、1：user字段仅返回user_id，默认为1。
	 */
	public function UserTimeline(array &$params) {
		$myps= $this->_tranSrv->ConvertParam($params,__FUNCTION__);
		if (isset ( $myps ) && ! empty ( $myps )) {
			// 参数处理
			if (! isset ( $myps['access_token'] ))
				$myps['access_token'] = $this->oauth->GetAccessToken ();
			else
				$myps['access_token'] = trim ( $myps['access_token'] );
			$ks = array_keys ( $myps );
			$si = array_search ( $ks, 'user' );
			if ($si !== NULL && $si !== FALSE) {
				$kv = EIService::TryToNumber ( $myps['user'] );
				if ($kv == NULL)
					array_splice ( $myps, $si, 1, array (
							"screen_name" => $myps['user'] 
					) );
				else
					array_splice ( $myps, $si, 1, array (
							"uid" => $kv 
					) );
			}
			if (empty ( $myps['trim_user'] ))
				$myps['trim_user'] = 1; // 默认为1。
			if (isset ( $myps['trim_user'] ))
				$myps['trim_user'] = EIService::ToInt ( $myps['trim_user'], 1 );
			if (isset ( $myps['count'] ))
				$myps['count'] = EIService::ToInt ( $myps['count'], 20 );
			if (isset ( $myps['page'] ))
				$myps['page'] = EIService::ToInt ( $myps['page'], 1 );
			if (isset ( $myps['base_app'] ))
				$myps['base_app'] = intval ( $myps['base_app'] );
			if (isset ( $myps['feature'] ))
				$myps['feature'] = intval ( $myps['feature'] );
				// 发送指令
			return  $this->_tranSrv->ConvertParam($this->oauth->get ( 'statuses/user_timeline', $myps ),__FUNCTION__,($params['ForbidConvertOut']?-1:1));
		} else
			{
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        } 
	}
	
	/**
	 * 根据微博ID获取单条微博内容(接口IBaseInfoAction)
	 * 
	 * @param array $myps
	 *        	参数
	 * @param array $params[访问令牌] | $params[#access_token]
	 *        	: 授权密钥,可省略
	 * @param array $params[id]
	 *        	： 需要获取的微博ID。
	 */
	public function GetSingleInfo(array &$params) {
		$myps= $this->_tranSrv->ConvertParam($params,__FUNCTION__);
		if (isset ( $myps ) && ! empty ( $myps ) && ! empty ( $myps['id'] )) {
			// 参数处理
			if (! isset ( $myps['access_token'] ))
				$myps['access_token'] = $this->oauth->GetAccessToken ();
			else
				$myps['access_token'] = trim ( $myps['access_token'] );
			$si = EIService::TryToNumber ( $myps['id'] );
			if ($si == NULL)
				throw new EiException ( 'GetSingleInfo函数参数params[id]只能为整数！' );
			$myps['id'] = $si;
			// 发送指令
			return $this->_tranSrv->ConvertParam($this->oauth->get ( 'statuses/show', $myps ),__FUNCTION__,($params['ForbidConvertOut']?-1:1));
		} else
			{
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        } 
	}
	/**
	 * 转发一条微博(接口IBaseInfoAction)
	 * 
	 * @param array $myps
	 *        	参数
	 * @param array $params[id]
	 *        	： 需要转发的微博ID。
	 * @param array $params[内容] |$params[#status] :添加的转发文本，必须做URLencode，内容不超过140个汉字，不填则默认为“转发微博”。        	
	 * @param array $params[转发时评论] | $params[#is_comment]:
	 *        	是否再转发的同时发表评论，0：否、1：评论给当前微博、2：评论给原微博、3：都评论，默认为0 。
	 * @param array $params[ip] | $params[#rip]:
	 *        	开发者上报的操作用户真实IP，形如：211.156.0.1。
	 */
	public function RepostInfo(array &$params) {
		$myps= $this->_tranSrv->ConvertParam($params,__FUNCTION__);
		if (isset ( $myps ) && ! empty ( $myps ) && ! empty ( $myps['id'] )) {
			// 参数处理
			$si = EIService::TryToNumber ( $myps['id'] );
			if ($si == NULL)
				throw new EiException ( 'RepostInfo函数参数params[id]只能为整数！' );
			$myps['id'] = $si;
			if (empty ( $myps['status'] )) {
				$myps['status'] = $this->TruncateContent($myps['status'],SUBSTR_MODE_COUNT); // 截取前N个字符
			}
			if (isset ( $myps['is_comment'] ))
				$myps['is_comment'] = intval ( $myps['is_comment'] );
				// 发送指令
			$r = $this->oauth->post ( 'statuses/repost', $myps );
			return $this->_tranSrv->ConvertParam($r,__FUNCTION__,($params['ForbidConvertOut']?-1:1));
		} else
			{
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        } 
	}
	
	/**
	 * 根据微博ID删除指定微博,只能删除自己发布的微博(接口IBaseInfoAction)
	 * 
	 * @param array $myps
	 *        	参数
	 * @param array $params[id]
	 *        	： 需要删除的微博ID。
	 */
	public function DestroyInfo(array &$params) {
        $myps = $this->_tranSrv->ConvertParam($params, __FUNCTION__);
        if (isset($myps) && !empty($myps) && !empty($myps['id'])) {
            // 参数处理
            $si = EIService::TryToNumber($myps['id']);
            if ($si == NULL)
                throw new EiException('DestroyInfo函数参数params[id]只能为整数！');
            $myps['id'] = $si;
            // 发送指令
            return $this->_tranSrv->ConvertParam($this->oauth->post('statuses/destroy', $myps), __FUNCTION__, ($params['ForbidConvertOut'] ? -1 : 1));
        } else {
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        }
    }
/**
 * 获取短网址
 * @param array $params,参数,必须包含[chr(1).'url_long']或 ['长网址']
 * @return  array(s=>短网址,l=>长网址)|boolean
 */
    public function ShortUrl(array &$params) {
        $myps = $this->_tranSrv->ConvertParam($params, __FUNCTION__);
        if (!empty($myps) && !empty($myps['url_long'])) {
            if(strlen($myps['url_long'])>2000)
            {
                $myps[chr(1).'url'] = $myps['url_long'];
                unset($myps['url_long']);
                return parent::ShortUrl($myps);
            }                
            if (!isset($myps['access_token']))
                $myps['access_token'] = $this->oauth->GetAccessToken();
            else
                $myps['access_token'] = trim($myps['access_token']);            
            if(empty($myps['access_token']))
            {
                $myps['appkey'] = $this->GetOAuth()->AppKey();
                unset($myps['access_token']);
            }
            //发送指令
            $r = $this->oauth->post('short_url/shorten', $myps);
            if (isset($r['urls'])) {
                $ra = array();
                foreach ($r['urls'] as $u) {
                    if ($u['result']) {
                        $ra[] = array('s' => $u['url_short'], 'l' => $u['url_long']);
                    }
                }
                if (!empty($ra))
                    return $ra;
            }
            return FALSE;
        }
        else {
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        }
    }

    /**
	 * 发布一条新微博,包括图文(接口IBaseInfoAction)
	 * 
	 * @param array $myps
	 *        	参数
	 * @param array $params[内容] | $params[chr(1).status]
	 *        	： 要发布的微博文本内容，必须做URLencode，内容不超过140个汉字。
	 * @param array $params[可见] | $params[chr(1).visible] 
	 *        	： 微博的可见性，0：所有人能看，1：仅自己可见，2：密友可见，3：指定分组可见，默认为0。
	 * @param array $params[ID列表] | $params[chr(1).list_id]
	 *        	： 微博的保护投递指定分组ID，只有当visible参数为3时生效且必选。
	 * @param array $params[pic]
	 *        	：statuses/upload专用 ， 要上传的图片，仅支持JPEG、GIF、PNG格式，图片大小小于5M。如果是本地上传图片要$_FILES参数,请求必须用POST方式提交，并且注意采用multipart/form-data编码方式；
	 * @param array $params[url]
	 *        	：statuses/upload_url_text专用 ，图片的URL地址，必须以http开头。
	 * @param array $params[图片ID] | $params[chr(1).pic_id]
	 *        	：statuses/upload_url_text专用 ，已经上传的图片pid，多个时使用英文半角逗号符分隔，最多不超过9个。
	 * @param array $params[lat]
	 *        	： 纬度，有效范围：-90.0到+90.0，+表示北纬，默认为0.0。lat和long参数需配合使用，用于标记发表微博消息时所在的地理位置，只有用户设置中geo_enabled=true时候地理位置信息才有效
	 * @param array $params[long]
	 *        	：经度，有效范围：-180.0到+180.0，+表示东经，默认为0.0。
	 * @param array $params[元数据] |$params[chr(1).annotations]  
	 *        	：元数据，主要是为了方便第三方应用记录一些适合于自己使用的信息，每条微博可以包含一个或者多个元数据，必须以json字串的形式提交，字串长度不超过512个字符，具体内容可以自定。
	 * @param array $params[ip] | $params[chr(1).rip]:
	 *        	：开发者上报的操作用户真实IP，形如：211.156.0.1。
	 */
     public function CreateInfo(array &$params) {
        $myps = $this->_tranSrv->ConvertParam($params, __FUNCTION__);
        if (isset($myps) && !empty($myps)) {
            // 参数处理
            if (isset($myps['visible']))
                $myps['visible'] = intval($myps['visible']);
            if (isset($myps['list_id']))
                $myps['list_id'] = intval($myps['list_id']);
            if (isset($myps['lat']))
                $myps['lat'] = floatval($myps['lat']);
            if (isset($myps['long']))
                $myps['long'] = floatval($myps['long']);
            //组织微博的内容:
            $cmy = $myps['pageurl'] . ' ' . $myps['status'];
            if (!empty($myps['playurl'])) {
                $cmy .= '[视频]';
                if (strlen($myps['playurl']) > 255) {
                    $mmp = array('长网址' => $myps['playurl']);
                    $sr = $this->ShortUrl($mmp);
                    if (is_array($sr)) {
                        if(!isset($sr[0]))   $myps['playurl'] = $sr['s'];
                        else $myps['playurl'] = $sr[0]['s'];
                    }
                }
                $cmy .= $myps['playurl'];
            }
            $cmy .= '[评论:' . $myps['title'] . ']';
            if (!empty($cmy)) {
                $myps['status'] = $this->TruncateContent($cmy, SUBSTR_MODE_COUNT); // 截取前N个字符
            }
            // 发送指令
            if (!empty($myps['pic']) || !empty($myps['url'])) {
                // 带图片的微博
                $spic = (empty($myps['pic'])?$myps['url']:$myps['pic']);
                if ($spic{0} != '@')  $spic=  '@' .$spic;
                $myps['pic'] = $spic;
                $r = $this->oauth->post('statuses/upload', $myps, TRUE);
            } else {
                if (!empty($myps['url']) || !empty($myps['pic_id'])) {
                    if (!empty($myps['pic_id']) && is_array($myps['pic_id']))
                        $myps['pic_id'] = implode(',', $myps['pic_id']);
                    $r = $this->oauth->post('statuses/upload_url_text', $myps, TRUE); //高级接口（需要授权）
                    if (!empty($r) && in_array($r['error_code'], array(10014, 10005, 10007, 10022, 10023, 10024))) {
                        //处理参数
                        if(!empty($myps['playurl'])){
                            $myps['status'].= '[视频]'.$myps['playurl'];
                              $myps['status'] = $this->TruncateContent($cmy, SUBSTR_MODE_COUNT); // 截取前N个字符
                        }
                        $r = $this->oauth->post('statuses/update', $myps);
                    }
                }
                elseif (!empty($myps['status']))
                    $r = $this->oauth->post('statuses/update', $myps);
                else
                    throw new EiException('CreateInfo函数不支持这种调用方式！');
            }
            return $this->_tranSrv->ConvertParam($r, __FUNCTION__, ($params['ForbidConvertOut'] ? -1 : 1));
        } else {
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        }
    }

    /**
	 * 根据微博ID返回某条微博的评论列表(此接口最多只返回最新的2000条数据)
	 * 
	 * @param array $myps
	 *        	参数
	 * @param array $params[访问令牌] | $params[chr(1).access_token]
	 *        	: 授权密钥
	 * @param array $params[id]
	 *        	： 需要列出评论的微博ID。
	 * @param array $params[开始ID] | $params[chr(1).since_id]
	 *        	： 若指定此参数，则返回ID比since_id大的评论（即比since_id时间晚的评论），默认为0。
	 * @param array $params[最大ID] |  $params[chr(1).max_id]
	 *        	： 若指定此参数，则返回ID小于或等于max_id的评论，默认为0。
	 * @param array $params[count]
	 *        	： 单页返回的记录条数，默认为50
	 * @param array $params[page]
	 *        	： 返回结果的页码，默认为1。
	 * @param array $params[用户筛选选项] | $params[chr(1).filter_by_author]
	 *        	：作者筛选类型，0：全部、1：我关注的人、2：陌生人，默认为0。
	 */
	public function ListComments(array &$params) {
		$myps= $this->_tranSrv->ConvertParam($params,__FUNCTION__);
		if (isset ( $myps ) && ! empty ( $myps ) && ! empty ( $myps['id'] )) {
			// 参数处理
			if (! isset ( $myps['access_token'] ))
				$myps['access_token'] = $this->oauth->GetAccessToken ();
			else
				$myps['access_token'] = trim ( $myps['access_token'] );
			$si = EIService::TryToNumber ( $myps['id'] );
			if ($si == NULL)
				throw new EiException ( 'ListComments函数参数params[id]只能为整数！' );
			$myps['id'] = $si;
			if (! empty ( $myps['since_id'] )) {
				$si = EIService::TryToNumber ( $myps['since_id'] );
				if ($si !== NULL)
					$myps['since_id'] = $si;
				else
					$myps['since_id'] = 0;
			}
			if (! empty ( $myps['max_id'] )) {
				$si = EIService::TryToNumber ( $myps['max_id'] );
				if ($si !== NULL)
					$myps['max_id'] = $si;
				else
					$myps['max_id'] = 0;
			}
			if (isset ( $myps['count'] ))
				$myps['count'] = EIService::ToInt ( $myps['count'], 50 );
			if (isset ( $myps['page'] ))
				$myps['page'] = EIService::ToInt ( $myps['page'], 1 );
			if (isset ( $myps['filter_by_author'] ))
				$myps['filter_by_author'] = intval ( $myps['filter_by_author'] );
				// 发送指令
			return $this->_tranSrv->ConvertParam($this->oauth->get ( 'comments/show', $myps ),__FUNCTION__,($params['ForbidConvertOut']?-1:1));
		} else
			{
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        } 
	}
	/**
	 * 对一条微博进行评论
	 * 
	 * @param array $myps
	 *        	参数
	 * @param array $params[id]
	 *        	： 需要评论的微博ID。
	 * @param array $params[评论] | $params[chr(1).comment]
	 *        	：评论内容，必须做URLencode，内容不超过140个汉字。
	 * @param array $params[评论到原文] | $params[chr(1).comment_ori]
	 *        	：当评论转发微博时，是否评论给原微博，0：否、1：是，默认为0。
	 * @param array $params[ip] | $params[chr(1).rip]:
	 *        	：开发者上报的操作用户真实IP，形如：211.156.0.1。
	 */
	public function CreateComments(array &$params) {
		$myps= $this->_tranSrv->ConvertParam($params,__FUNCTION__);
		if (isset ( $myps ) && ! empty ( $myps ) && ! empty ( $myps['id'] ) && ! empty ( $myps['comment'] )) {
			// 参数处理
			$si = EIService::TryToNumber ( $myps['id'] );
			if ($si == NULL)
				throw new EiException ( 'CreateComments函数参数params[id]只能为整数！' );
			$myps['id'] = $si;
			$myps['comment'] =$this->TruncateContent($myps['comment'],SUBSTR_MODE_COUNT); // 截取前N个字符
			if (isset ( $myps['comment_ori'] ))
				$myps['comment_ori'] = intval ( $myps['comment_ori'] );
				// 发送指令
			$r = $this->oauth->post ( 'comments/create', $myps );
			return $this->_tranSrv->ConvertParam($r,__FUNCTION__,($params['ForbidConvertOut']?-1:1));
		} else
			{
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        } 
	}
	/**
	 * 删除一条自己的评论
	 * 
	 * @param array $myps
	 *        	参数
	 * @param array $params[评论ID] | $params[chr(1).cid]
	 *        	： 需要删除的微博ID。
	 */
	public function DestroyComments(array &$params) {
		$myps= $this->_tranSrv->ConvertParam($params,__FUNCTION__);
		if (isset ( $myps ) && ! empty ( $myps ) && (! empty ( $myps['cid'] )||! empty ( $myps['id'] ))) {
			// 参数处理
			if(empty ( $myps['id'] ))
			$si = EIService::TryToNumber ( $myps['cid'] );
			else $si = EIService::TryToNumber ( $myps['id'] );
			if ($si == NULL)
				throw new EiException ( 'DestroyComments函数参数params[cid]只能为整数！' );
			$p = array('cid'=>$si);
			// 发送指令
			$r = $this->oauth->post ( 'comments/destroy', $p );
			return $this->_tranSrv->ConvertParam($r,__FUNCTION__,($params['ForbidConvertOut']?-1:1));
		} else
			{
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        } 
	}
	/**
	 * 根据用户ID/名称获取用户信息
	 * 
	 * @param array $params[访问令牌] | $params[chr(1).access_token]
	 *        	: 授权密钥
	 * @param array $params[openid] | $params[chr(1).uid]
	 *        	： 指定用户的ID
	 * @param array $params[username] | $params[chr(1).screen_name]
	 *        	： 需要查询的用户昵称
	 */
	public function GetUserInfo(array &$params) {
		$myps= $this->_tranSrv->ConvertParam($params,__FUNCTION__);
		if (isset ( $myps ) && ! empty ( $myps ) && (! empty ( $myps['uid'] ) || ! empty ( $myps['screen_name'] ))) {
			// 参数处理
			if (! isset ( $myps['access_token'] ))
				$myps['access_token'] = $this->oauth->GetAccessToken ();
			else
				$myps['access_token'] = trim ( $myps['access_token'] );
			$si = EIService::TryToNumber ( $myps['uid'] );
			if ($si !== NULL)
				$myps['uid'] = $si;
				// 发送指令
			$r = $this->_tranSrv->ConvertParam($this->oauth->get ( 'users/show', $myps ),__FUNCTION__,1);
			return $this->_tranSrv->ConvertParam($r,__FUNCTION__,($params['ForbidConvertOut']?-1:1));
		} else
			{
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        } 
	}
	/**
	 * 获取用户的联系邮箱（高级接口需要授权）
	 * 
	 * @param mixed $params
	 *        	: 授权密钥
	 * @return array a['email']
	 */
	public function GetUserEmail($params) {
		if (! isset ( $params ) || empty ( $params ))
			$params = array ();
		if (is_scalar ( $params )) {
			$params = array (
					'access_token' => $params 
			);
		}
		// 参数处理
		if (empty( $params ['access_token'] ))
			$params ['access_token'] = $this->oauth->GetAccessToken ();
		else
			$params ['access_token'] = trim ( $params ['access_token'] );
			// 发送指令
		$r = $this->oauth->get ( 'account/profile/email', $params );
		return $this->_tranSrv->ConvertParam($r,__FUNCTION__,($params['ForbidConvertOut']?-1:1));
	}
	
	/**
	 * 获取用户的活跃粉丝列表,uid只能为当前授权用户
	 * 
	 * @param array $params[访问令牌] | $params[chr(1).access_token]
	 *        	: 授权密钥
	 * @param array $params[openid] | $params[chr(1).uid]
	 *        	： 指定用户的ID
	 * @param array $params[count]
	 *        	： 返回的记录条数，默认为20，最大不超过200。
	 */
	public function GetActiveFriends(array &$params) {
		$ca= $this->_tranSrv->ConvertParam($params,__FUNCTION__);
		// 参数处理
		if (empty ( $ca ['uid'] )) {
			$ca ['uid'] = $this->oauth->GetOpenID ();
		} else
			$ca ['uid'] = EIService::TryToNumber ( $ca ['uid'] );
		if (isset ( $ca ['count'] ))
			$ca ['count'] = EIService::ToInt ( $ca ['count'], 20 );
		if (! isset ( $ca ['access_token'] ))
			$ca ['access_token'] = $this->oauth->GetAccessToken ();
		else
			$ca ['access_token'] = trim ( $ca ['access_token'] );
			// 发送指令
		$r = $this->oauth->get ( 'friendships/followers/active', $ca );
		return $this->_tranSrv->ConvertParam($r,__FUNCTION__,($params['ForbidConvertOut']?-1:1));
	}
	/**
	 * 获取用户的双向关注列表，即互粉列表
	 * 
	 * @param array $params[访问令牌] | $params[chr(1).access_token]
	 *        	: 授权密钥
	 * @param array $params[openid] | $params[chr(1).uid] 
	 *        	： 指定用户的ID,uid只能为当前授权用户
	 * @param array $params[count]
	 *        	： 单页返回的记录条数，默认为50。
	 * @param array $params[page]
	 *        	： 返回结果的页码，默认为1。
	 * @param array $params[sort]
	 *        	： 排序类型，0：按关注时间最近排序，默认为0。
	 * @param array $params[仅ID] | $params[chr(1).onlyid]
	 *        	： 是否只获取ID列表，而不需要其它用户资料
	 */
	public function BilateralFriends(array &$params) {
		$myps= $this->_tranSrv->ConvertParam($params,__FUNCTION__);
		if (isset ( $myps ) && ! empty ( $myps )) {
			// 参数处理
			if (! isset ( $myps['access_token'] ))
				$myps['access_token'] = $this->oauth->GetAccessToken ();
			else
				$myps['access_token'] = trim ( $myps['access_token'] );
			if (empty ( $myps['uid'] ))
				$myps['uid'] = $this->oauth->GetOpenID ();
			$si = EIService::TryToNumber ( $myps['uid'] );
			if ($si !== NULL)
				$myps['uid'] = $si;
			if (isset ( $myps['count'] ))
				$myps['count'] = EIService::ToInt ( $myps['count'], 50 );
			if (isset ( $myps['page'] ))
				$myps['page'] = EIService::ToInt ( $myps['page'], 1 );
			if (isset ( $myps['sort'] ))
				$myps['sort'] = EIService::ToInt ( $myps['sort'], 0 );
				// 发送指令
			if (empty ( $myps['onlyid'] ))
				$r = $this->oauth->get ( 'friendships/friends/bilateral', $myps );
			else
				$r = $this->oauth->get ( 'friendships/friends/bilateral/ids', $myps );
			return $this->_tranSrv->ConvertParam($r,__FUNCTION__,($params['ForbidConvertOut']?-1:1));
		} else
			{
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        } 
	}
	
	/**
	 * 回复一条评论
	 * 
	 * @param array $params[评论ID] |  $params[chr(1).cid]
	 *        	： 需要回复的评论ID。
	 * @param array $params[id]
	 *        	：需要评论的微博ID。
	 * @param array $params[评论] | $params[chr(1).comment]
	 *        	：回复评论内容，必须做URLencode，内容不超过140个汉字。
	 * @param array $params[补充提及] | $params[chr(1).without_mention]
	 *        	：回复中是否自动加入“回复@用户名”，0：是、1：否，默认为0。
	 * @param array $params[评论到原文] | $params[chr(1).comment_ori]
	 *        	：当评论转发微博时，是否评论给原微博，0：否、1：是，默认为0。
	 * @param array $params[ip] | $params[chr(1).rip]
	 *        	：开发者上报的操作用户真实IP，形如：211.156.0.1。
	 */
	public function ReplyComments(array &$params) {
		$myps= $this->_tranSrv->ConvertParam($params,__FUNCTION__);
		if (isset ( $myps ) && ! empty ( $myps ) && ! empty ( $myps['cid'] ) && ! empty ( $myps['id'] ) && ! empty ( $myps['comment'] )) {
			// 参数处理
			$myps['comment'] =$this->TruncateContent($myps['comment'],SUBSTR_MODE_COUNT); // 截取前N个字符
			if (isset ( $myps['without_mention'] ))
				$myps['without_mention'] = EIService::ToInt ( $myps['without_mention'], 0 );
			if (isset ( $myps['comment_ori'] ))
				$myps['comment_ori'] = EIService::ToInt ( $myps['comment_ori'], 0 );
				// 发送指令
			$r = $this->oauth->post ( 'comments/reply', $myps );
			return $this->_tranSrv->ConvertParam($r,__FUNCTION__,($params['ForbidConvertOut']?-1:1));
		} else
			{
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        } 
	}
	
	/**
	 * 关注一个用户(接口IBaseInfoAction)
	 * 
	 * @param array $myps
	 *        	参数
	 * @param array $params[user]
	 *        	： 用户的ID或者用户名称,也可以明确指定$params[openid] | $params[chr(1).uid], $params[username] | $params[chr(1).screen_name]
	 */
	public function CreateFriendships(array &$params) {
		$myps= $this->_tranSrv->ConvertParam($params,__FUNCTION__);
		if (isset ( $myps ) && ! empty ( $myps )) {
			// 参数处理
			$ks = array_keys ( $myps );
			$si = array_search ( $ks, 'user' );
			if ($si !== NULL && $si !== FALSE) {
				$kv = EIService::TryToNumber ( $myps['user'] );
				if ($kv == NULL)
					array_splice ( $myps, $si, 1, array (
							"screen_name" => $myps['user'] 
					) );
				else
					array_splice ( $myps, $si, 1, array (
							"uid" => $kv 
					) );
			}
			// 发送指令
			$r = $this->oauth->post ( 'friendships/create', $myps );
                        return $this->_tranSrv->ConvertParam($r,__FUNCTION__,($params['ForbidConvertOut']?-1:1));
		} else
			{
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        } 
	}
	/**
	 * 取消关注一个用户
	 * 
	 * @param array $myps
	 *        	参数
	 * @param array $params[user]
	 *        	： 用户的ID或者用户名称,也可以明确指定$params[openid] | $params[chr(1).uid], $params[username] | $params[chr(1).screen_name]
	 */
	public function DestroyFriendships(array &$params) {
		$myps= $this->_tranSrv->ConvertParam($params,__FUNCTION__);
		if (isset ( $myps ) && ! empty ( $myps )) {
			// 参数处理
			$ks = array_keys ( $myps );
			$si = array_search ( $ks, 'user' );
			if ($si !== NULL && $si !== FALSE) {
				$kv = EIService::TryToNumber ( $myps['user'] );
				if ($kv == NULL)
					array_splice ( $myps, $si, 1, array (
							"screen_name" => $myps['user'] 
					) );
				else
					array_splice ( $myps, $si, 1, array (
							"uid" => $kv 
					) );
			}
			// 发送指令
			$r = $this->oauth->post ( 'friendships/destroy', $myps );
                       return  $this->_tranSrv->ConvertParam($r,__FUNCTION__,($params['ForbidConvertOut']?-1:1));
		} else
			{
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        } 
	}
	/**
	 * 将内容截取为规定的长度，超过的将被截掉
	 * @param $str string 原始内容
	 * @param $submode int 截取的模式，SUBSTR_MODE_COUNT 或者 SUBSTR_MODE_BYTE
	 * @return string
	 */
	public function TruncateContent($str,$submode=SUBSTR_MODE_COUNT) {
		if (empty ( $str ))
			return '';
                $maxlen = SinaTranSrv::MaxInfoLen($submode);
		if (strlen ( $str ) < $maxlen)
			return $str;
		$pn = '/\b(http(s)?:\/\/\S+)/i';
		$pn2 = '/\b(http(s)?:\/\/\S+)$/i';
		$ps = 'http://t.cn/XXXXXXXX';
		$sa = array ();
		$rstr = $str;
		if(preg_match_all($pn, $str,$sa)>0)
		{
			$rstr = preg_replace($pn, $ps, $str);
		}
		$pstr = EIService::cn_substr ( $rstr, $maxlen, 0,  SUBSTR_MODE_COUNT); // 截取前N个字符
		if (preg_match ( $pn2, $pstr, $endurl )) {
			if ($endurl [0] !== $ps)// 最后一个链接不完整，删除：
			{
				$pstr = preg_replace ( $pn2, '', $pstr );
			}
		}
		$dd=explode($ps,$pstr);
		$joinr='';
		for ($ji=0;$ji<count($dd);$ji++)
		{
			$joinr.=$dd[$ji];
			if($ji<count($dd)-1 || $dd[$ji]=='')
				$joinr.=$sa[1][$ji];
		}
		return $joinr;
	}
	/**
	 * 通过微博（评论、私信）ID获取其MID
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/2/statuses/querymid statuses/querymid}
	 *
	 * @param int|string $id
	 *        	需要查询的微博（评论、私信）ID，批量模式下，用半角逗号分隔，最多不超过20个。
	 * @param int $type
	 *        	获取类型，1：微博、2：评论、3：私信，默认为1。
	 * @param int $is_batch
	 *        	是否使用批量模式，0：否、1：是，默认为0。
	 * @return array
	 */
	function querymid($id, $type = 1, $is_batch = 0) {
		$params = array ();
		$params ['id'] = $id;
		$params ['type'] = intval ( $type );
		$params ['is_batch'] = intval ( $is_batch );
		if (! isset ( $params ['access_token'] ))
			$params ['access_token'] = $this->oauth->GetAccessToken ();
		else
			$params ['access_token'] = trim ( $params ['access_token'] );
		return $this->oauth->get ( 'statuses/querymid', $params );
	}
	
	/**
	 * 通过微博（评论、私信）MID获取其ID
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/2/statuses/queryid statuses/queryid}
	 *
	 * @param int|string $mid
	 *        	需要查询的微博（评论、私信）MID，批量模式下，用半角逗号分隔，最多不超过20个。
	 * @param int $type
	 *        	获取类型，1：微博、2：评论、3：私信，默认为1。
	 * @param int $is_batch
	 *        	是否使用批量模式，0：否、1：是，默认为0。
	 * @param int $inbox
	 *        	仅对私信有效，当MID类型为私信时用此参数，0：发件箱、1：收件箱，默认为0 。
	 * @param int $isBase62
	 *        	MID是否是base62编码，0：否、1：是，默认为0。
	 * @return array
	 */
	function queryid($mid, $type = 1, $is_batch = 0, $inbox = 0, $isBase62 = 0) {
		$params = array ();
		$params ['mid'] = $mid;
		$params ['type'] = intval ( $type );
		$params ['is_batch'] = intval ( $is_batch );
		$params ['inbox'] = intval ( $inbox );
		$params ['isBase62'] = intval ( $isBase62 );
		if (! isset ( $params ['access_token'] ))
			$params ['access_token'] = $this->oauth->GetAccessToken ();
		else
			$params ['access_token'] = trim ( $params ['access_token'] );
		return $this->oauth->get ( 'statuses/queryid', $params );
	}
	
	/**
	 * 获取表情列表
	 *
	 * 返回新浪微博官方所有表情、魔法表情的相关信息。包括短语、表情类型、表情分类，是否热门等。
	 * <br />对应API：{@link http://open.weibo.com/wiki/2/emotions emotions}
	 *
	 * @access public
	 * @param string $type
	 *        	表情类别。"face":普通表情，"ani"：魔法表情，"cartoon"：动漫表情。默认为"face"。可选。
	 * @param string $language
	 *        	语言类别，"cnname"简体，"twname"繁体。默认为"cnname"。可选
	 * @return array
	 */
	function emotions($type = "face", $language = "cnname") {
		$params = array ();
		$params ['type'] = $type;
		$params ['language'] = $language;
		if (! isset ( $params ['access_token'] ))
			$params ['access_token'] = $this->oauth->GetAccessToken ();
		else
			$params ['access_token'] = trim ( $params ['access_token'] );
		return $this->oauth->get ( 'emotions', $params );
	}
	
	/**
	 * 获取当前登录用户所发出的评论列表
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/2/comments/by_me comments/by_me}
	 *
	 * @param int $since_id
	 *        	若指定此参数，则返回ID比since_id大的评论（即比since_id时间晚的评论），默认为0。
	 * @param int $max_id
	 *        	若指定此参数，则返回ID小于或等于max_id的评论，默认为0。
	 * @param int $count
	 *        	单页返回的记录条数，默认为50。
	 * @param int $page
	 *        	返回结果的页码，默认为1。
	 * @param int $filter_by_source
	 *        	来源筛选类型，0：全部、1：来自微博的评论、2：来自微群的评论，默认为0。
	 * @return array
	 */
	function comments_by_me($page = 1, $count = 50, $since_id = 0, $max_id = 0, $filter_by_source = 0) {
		$params = array ();
		if ($since_id) {
			$this->id_format ( $since_id );
			$params ['since_id'] = $since_id;
		}
		if ($max_id) {
			$this->id_format ( $max_id );
			$params ['max_id'] = $max_id;
		}
		$params ['count'] = $count;
		$params ['page'] = $page;
		$params ['filter_by_source'] = $filter_by_source;
		if (! isset ( $params ['access_token'] ))
			$params ['access_token'] = $this->oauth->GetAccessToken ();
		else
			$params ['access_token'] = trim ( $params ['access_token'] );
		return $this->oauth->get ( 'comments/by_me', $params );
	}
	
	/**
	 * 获取当前登录用户所接收到的评论列表
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/2/comments/to_me comments/to_me}
	 *
	 * @param int $since_id
	 *        	若指定此参数，则返回ID比since_id大的评论（即比since_id时间晚的评论），默认为0。
	 * @param int $max_id
	 *        	若指定此参数，则返回ID小于或等于max_id的评论，默认为0。
	 * @param int $count
	 *        	单页返回的记录条数，默认为50。
	 * @param int $page
	 *        	返回结果的页码，默认为1。
	 * @param int $filter_by_author
	 *        	作者筛选类型，0：全部、1：我关注的人、2：陌生人，默认为0。
	 * @param int $filter_by_source
	 *        	来源筛选类型，0：全部、1：来自微博的评论、2：来自微群的评论，默认为0。
	 * @return array
	 */
	function comments_to_me($page = 1, $count = 50, $since_id = 0, $max_id = 0, $filter_by_author = 0, $filter_by_source = 0) {
		$params = array ();
		if ($since_id) {
			$this->id_format ( $since_id );
			$params ['since_id'] = $since_id;
		}
		if ($max_id) {
			$this->id_format ( $max_id );
			$params ['max_id'] = $max_id;
		}
		$params ['count'] = $count;
		$params ['page'] = $page;
		$params ['filter_by_author'] = $filter_by_author;
		$params ['filter_by_source'] = $filter_by_source;
		if (! isset ( $params ['access_token'] ))
			$params ['access_token'] = $this->oauth->GetAccessToken ();
		else
			$params ['access_token'] = trim ( $params ['access_token'] );
		return $this->oauth->get ( 'comments/to_me', $params );
	}
	
	/**
	 * 获取最新的提到当前登录用户的评论，即@我的评论
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/2/comments/mentions comments/mentions}
	 *
	 * @param int $since_id
	 *        	若指定此参数，则返回ID比since_id大的评论（即比since_id时间晚的评论），默认为0。
	 * @param int $max_id
	 *        	若指定此参数，则返回ID小于或等于max_id的评论，默认为0。
	 * @param int $count
	 *        	单页返回的记录条数，默认为50。
	 * @param int $page
	 *        	返回结果的页码，默认为1。
	 * @param int $filter_by_author
	 *        	作者筛选类型，0：全部、1：我关注的人、2：陌生人，默认为0。
	 * @param int $filter_by_source
	 *        	来源筛选类型，0：全部、1：来自微博的评论、2：来自微群的评论，默认为0。
	 * @return array
	 */
	function comments_mentions($page = 1, $count = 50, $since_id = 0, $max_id = 0, $filter_by_author = 0, $filter_by_source = 0) {
		$params = array ();
		$params ['since_id'] = $since_id;
		$params ['max_id'] = $max_id;
		$params ['count'] = $count;
		$params ['page'] = $page;
		$params ['filter_by_author'] = $filter_by_author;
		$params ['filter_by_source'] = $filter_by_source;
		if (! isset ( $params ['access_token'] ))
			$params ['access_token'] = $this->oauth->GetAccessToken ();
		else
			$params ['access_token'] = trim ( $params ['access_token'] );
		return $this->oauth->get ( 'comments/mentions', $params );
	}
	
	/**
	 * 通过个性化域名获取用户资料以及用户最新的一条微博
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/2/users/domain_show users/domain_show}
	 *
	 * @access public
	 * @param mixed $domain
	 *        	用户个性域名。例如：lazypeople，而不是http://weibo.com/lazypeople
	 * @return array
	 */
	function domain_show($domain) {
		$params = array ();
		$params ['domain'] = $domain;
		return $this->oauth->get ( 'users/domain_show', $params );
	}
	
	/**
	 * 获取用户的关注列表
	 *
	 * 如果没有提供cursor参数，将只返回最前面的5000个关注id
	 * <br />对应API：{@link http://open.weibo.com/wiki/2/friendships/friends friendships/friends}
	 *
	 * @access public
	 * @param int $cursor
	 *        	返回结果的游标，下一页用返回值里的next_cursor，上一页用previous_cursor，默认为0。
	 * @param int $count
	 *        	单页返回的记录条数，默认为50，最大不超过200。
	 * @param int $uid
	 *        	要获取的用户的ID。
	 * @return array
	 */
	function friends_by_id($uid, $cursor = 0, $count = 50) {
		$params = array ();
		$params ['cursor'] = $cursor;
		$params ['count'] = $count;
		$params ['uid'] = $uid;
		if (! isset ( $params ['access_token'] ))
			$params ['access_token'] = $this->oauth->GetAccessToken ();
		else
			$params ['access_token'] = trim ( $params ['access_token'] );
		return $this->oauth->get ( 'friendships/friends', $params );
	}
	
	/**
	 * 获取用户的关注列表
	 *
	 * 如果没有提供cursor参数，将只返回最前面的5000个关注id
	 * <br />对应API：{@link http://open.weibo.com/wiki/2/friendships/friends friendships/friends}
	 *
	 * @access public
	 * @param int $cursor
	 *        	返回结果的游标，下一页用返回值里的next_cursor，上一页用previous_cursor，默认为0。
	 * @param int $count
	 *        	单页返回的记录条数，默认为50，最大不超过200。
	 * @param string $screen_name
	 *        	要获取的用户的 screen_name
	 * @return array
	 */
	function friends_by_name($screen_name, $cursor = 0, $count = 50) {
		$params = array ();
		$params ['cursor'] = $cursor;
		$params ['count'] = $count;
		$params ['screen_name'] = $screen_name;
		if (! isset ( $params ['access_token'] ))
			$params ['access_token'] = $this->oauth->GetAccessToken ();
		else
			$params ['access_token'] = trim ( $params ['access_token'] );
		return $this->oauth->get ( 'friendships/friends', $params );
	}
	
	/**
	 * 获取两个用户之间的共同关注人列表
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/2/friendships/friends/in_common friendships/friends/in_common}
	 *
	 * @param int $uid
	 *        	需要获取共同关注关系的用户UID
	 * @param int $suid
	 *        	需要获取共同关注关系的用户UID，默认为当前登录用户。
	 * @param int $count
	 *        	单页返回的记录条数，默认为50。
	 * @param int $page
	 *        	返回结果的页码，默认为1。
	 * @return array
	 */
	function friends_in_common($uid, $suid = NULL, $page = 1, $count = 50) {
		$params = array ();
		$params ['uid'] = $uid;
		$params ['suid'] = $suid;
		$params ['count'] = $count;
		$params ['page'] = $page;
		if (! isset ( $params ['access_token'] ))
			$params ['access_token'] = $this->oauth->GetAccessToken ();
		else
			$params ['access_token'] = trim ( $params ['access_token'] );
		return $this->oauth->get ( 'friendships/friends/in_common', $params );
	}
	
	/**
	 * 根据用户UID批量关注用户
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/2/friendships/create_batch friendships/create_batch}
	 *
	 * @param string $uids
	 *        	要关注的用户UID，用半角逗号分隔，最多不超过20个。
	 * @return array
	 */
	function follow_create_batch($uids) {
		$params = array ();
		if (is_array ( $uids ) && ! empty ( $uids )) {
			foreach ( $uids as $k => $v ) {
				$this->id_format ( $uids [$k] );
			}
			$params ['uids'] = join ( ',', $uids );
		} else {
			$params ['uids'] = $uids;
		}
		return $this->oauth->post ( 'friendships/create_batch', $params );
	}
	
	/**
	 * 发送私信
	 *
	 * 发送一条私信。成功将返回完整的发送消息。
	 * <br />对应API：{@link http://open.weibo.com/wiki/2/direct_messages/new direct_messages/new}
	 *
	 * @access public
	 * @param int $uid
	 *        	用户UID
	 * @param string $text
	 *        	要发生的消息内容，文本大小必须小于300个汉字。
	 * @param int $id
	 *        	需要发送的微博ID。
	 * @return array
	 */
	function send_dm_by_id($uid, $text, $id = NULL) {
		$params = array ();
		$this->id_format ( $uid );
		$params ['text'] = $text;
		$params ['uid'] = $uid;
		if ($id) {
			$this->id_format ( $id );
			$params ['id'] = $id;
		}
		return $this->oauth->post ( 'direct_messages/new', $params );
	}
	
	/**
	 * 发送私信
	 *
	 * 发送一条私信。成功将返回完整的发送消息。
	 * <br />对应API：{@link http://open.weibo.com/wiki/2/direct_messages/new direct_messages/new}
	 *
	 * @access public
	 * @param string $screen_name
	 *        	用户昵称
	 * @param string $text
	 *        	要发生的消息内容，文本大小必须小于300个汉字。
	 * @param int $id
	 *        	需要发送的微博ID。
	 * @return array
	 */
	function send_dm_by_name($screen_name, $text, $id = NULL) {
		$params = array ();
		$params ['text'] = $text;
		$params ['screen_name'] = $screen_name;
		if ($id) {
			$this->id_format ( $id );
			$params ['id'] = $id;
		}
		return $this->oauth->post ( 'direct_messages/new', $params );
	}
	
	/**
	 * 获取当前登录用户的API访问频率限制情况
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/2/account/rate_limit_status account/rate_limit_status}
	 *
	 * @access public
	 * @return array
	 */
	function rate_limit_status() {
		return $this->oauth->get ( 'account/rate_limit_status' );
	}
	
	/**
	 * OAuth授权之后，获取授权用户的UID
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/2/account/get_uid account/get_uid}
	 *
	 * @access public
	 * @return array
	 */
	function get_uid() {
		return $this->oauth->GetOpenID ();
	}
	
	/**
	 * 更改用户资料
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/2/account/profile/basic_update account/profile/basic_update}
	 *
	 * @access public
	 * @param array $profile
	 *        	要修改的资料。格式：array('key1'=>'value1', 'key2'=>'value2', .....)。
	 *        	支持修改的项：
	 *        	- screen_name		string	用户昵称，不可为空。
	 *        	- gender	i		string	用户性别，m：男、f：女，不可为空。
	 *        	- real_name			string	用户真实姓名。
	 *        	- real_name_visible	int		真实姓名可见范围，0：自己可见、1：关注人可见、2：所有人可见。
	 *        	- province	true	int		省份代码ID，不可为空。
	 *        	- city	true		int		城市代码ID，不可为空。
	 *        	- birthday			string	用户生日，格式：yyyy-mm-dd。
	 *        	- birthday_visible	int		生日可见范围，0：保密、1：只显示月日、2：只显示星座、3：所有人可见。
	 *        	- qq				string	用户QQ号码。
	 *        	- qq_visible		int		用户QQ可见范围，0：自己可见、1：关注人可见、2：所有人可见。
	 *        	- msn				string	用户MSN。
	 *        	- msn_visible		int		用户MSN可见范围，0：自己可见、1：关注人可见、2：所有人可见。
	 *        	- url				string	用户博客地址。
	 *        	- url_visible		int		用户博客地址可见范围，0：自己可见、1：关注人可见、2：所有人可见。
	 *        	- credentials_type	int		证件类型，1：身份证、2：学生证、3：军官证、4：护照。
	 *        	- credentials_num	string	证件号码。
	 *        	- email				string	用户常用邮箱地址。
	 *        	- email_visible		int		用户常用邮箱地址可见范围，0：自己可见、1：关注人可见、2：所有人可见。
	 *        	- lang				string	语言版本，zh_cn：简体中文、zh_tw：繁体中文。
	 *        	- description		string	用户描述，最长不超过70个汉字。
	 *        	填写birthday参数时，做如下约定：
	 *        	- 只填年份时，采用1986-00-00格式；
	 *        	- 只填月份时，采用0000-08-00格式；
	 *        	- 只填某日时，采用0000-00-28格式。
	 * @return array
	 */
	function update_profile($profile) {
		return $this->oauth->post ( 'account/profile/basic_update', $profile );
	}
	
	/**
	 * 更改头像
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/2/account/avatar/upload account/avatar/upload}
	 *
	 * @param string $image_path
	 *        	要上传的头像路径, 支持url。[只支持png/jpg/gif三种格式, 增加格式请修改get_image_mime方法] 必须为小于700K的有效的GIF, JPG图片. 如果图片大于500像素将按比例缩放。
	 * @return array
	 */
	function update_profile_image($image_path) {
		$params = array ();
		$params ['image'] = "@{$image_path}";
		
		return $this->oauth->post ( 'account/avatar/upload', $params );
	}
	
	/**
	 * 设置隐私信息
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/2/account/update_privacy account/update_privacy}
	 *
	 * @param array $privacy_settings
	 *        	要修改的隐私设置。格式：array('key1'=>'value1', 'key2'=>'value2', .....)。
	 *        	支持设置的项：
	 *        	- comment	int	是否可以评论我的微博，0：所有人、1：关注的人，默认为0。
	 *        	- geo		int	是否开启地理信息，0：不开启、1：开启，默认为1。
	 *        	- message	int	是否可以给我发私信，0：所有人、1：关注的人，默认为0。
	 *        	- realname	int	是否可以通过真名搜索到我，0：不可以、1：可以，默认为0。
	 *        	- badge		int	勋章是否可见，0：不可见、1：可见，默认为1。
	 *        	- mobile	int	是否可以通过手机号码搜索到我，0：不可以、1：可以，默认为0。
	 *        	以上参数全部选填
	 * @return array
	 */
	function update_privacy($privacy_settings) {
		return $this->oauth->post ( 'account/update_privacy', $privacy_settings );
	}
	
	/**
	 * 验证昵称是否可用，并给予建议昵称
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/2/register/verify_nickname register/verify_nickname}
	 *
	 * @param string $nickname
	 *        	需要验证的昵称。4-20个字符，支持中英文、数字、"_"或减号。必填
	 * @return array
	 */
	function verify_nickname($nickname) {
		$params = array ();
		$params ['nickname'] = $nickname;
		return $this->oauth->get ( 'register/verify_nickname', $params );
	}
	
	/**
	 * 搜索与指定的一个或多个条件相匹配的微博
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/2/search/statuses search/statuses}
	 *
	 * @param array $query
	 *        	搜索选项。格式：array('key0'=>'value0', 'key1'=>'value1', ....)。支持的key:
	 *        	- q				string	搜索的关键字，必须进行URLencode。
	 *        	- filter_ori	int		过滤器，是否为原创，0：全部、1：原创、2：转发，默认为0。
	 *        	- filter_pic	int		过滤器。是否包含图片，0：全部、1：包含、2：不包含，默认为0。
	 *        	- fuid			int		搜索的微博作者的用户UID。
	 *        	- province		int		搜索的省份范围，省份ID。
	 *        	- city			int		搜索的城市范围，城市ID。
	 *        	- starttime		int		开始时间，Unix时间戳。
	 *        	- endtime		int		结束时间，Unix时间戳。
	 *        	- count			int		单页返回的记录条数，默认为10。
	 *        	- page			int		返回结果的页码，默认为1。
	 *        	- needcount		boolean	返回结果中是否包含返回记录数，true：返回、false：不返回，默认为false。
	 *        	- base_app		int		是否只获取当前应用的数据。0为否（所有数据），1为是（仅当前应用），默认为0。
	 *        	needcount参数不同，会导致相应的返回值结构不同
	 *        	以上参数全部选填
	 * @return array
	 */
	function search_statuses_high($query) {
		return $this->oauth->get ( 'search/statuses', $query );
	}
	
	/**
	 * 通过关键词搜索用户
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/2/search/users search/users}
	 *
	 * @param array $query
	 *        	搜索选项。格式：array('key0'=>'value0', 'key1'=>'value1', ....)。支持的key:
	 *        	- q			string	搜索的关键字，必须进行URLencode。
	 *        	- snick		int		搜索范围是否包含昵称，0：不包含、1：包含。
	 *        	- sdomain	int		搜索范围是否包含个性域名，0：不包含、1：包含。
	 *        	- sintro	int		搜索范围是否包含简介，0：不包含、1：包含。
	 *        	- stag		int		搜索范围是否包含标签，0：不包含、1：包含。
	 *        	- province	int		搜索的省份范围，省份ID。
	 *        	- city		int		搜索的城市范围，城市ID。
	 *        	- gender	string	搜索的性别范围，m：男、f：女。
	 *        	- comorsch	string	搜索的公司学校名称。
	 *        	- sort		int		排序方式，1：按更新时间、2：按粉丝数，默认为1。
	 *        	- count		int		单页返回的记录条数，默认为10。
	 *        	- page		int		返回结果的页码，默认为1。
	 *        	- base_app	int		是否只获取当前应用的数据。0为否（所有数据），1为是（仅当前应用），默认为0。
	 *        	以上所有参数全部选填
	 * @return array
	 */
	function search_users_keywords($query) {
		return $this->oauth->get ( 'search/users', $query );
	}
	
	/**
	 *
	 * @ignore
	 *
	 */
	protected function request_with_pager($url, $page = false, $count = false, $params = array()) {
		if ($page)
			$params ['page'] = $page;
		if ($count)
			$params ['count'] = $count;
		
		return $this->oauth->get ( $url, $params );
	}
	
	/**
	 *
	 * @ignore
	 *
	 */
	protected function request_with_uid($url, $uid_or_name, $page = false, $count = false, $cursor = false, $post = false, $params = array()) {
		if ($page)
			$params ['page'] = $page;
		if ($count)
			$params ['count'] = $count;
		if ($cursor)
			$params ['cursor'] = $cursor;
		
		if ($post)
			$method = 'post';
		else
			$method = 'get';
		
		if ($uid_or_name !== NULL) {
			$this->id_format ( $uid_or_name );
			$params ['id'] = $uid_or_name;
		}
		
		return $this->oauth->$method ( $url, $params );
	}
	
	/**
	 *
	 * @ignore
	 *
	 */
	protected function id_format(&$id) {
		if (is_float ( $id )) {
			$id = number_format ( $id, 0, '', '' );
		} elseif (is_string ( $id )) {
			$id = trim ( $id );
		}
	}
}
