<?php
/* QQ互联操作类
 * @version 2.0.0
 * @author LGM
 * @copyright © 2013, SZHGH Corporation. All rights reserved.
 */
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'eibase.php' );
include_once(QQXCLASS_PATH.'QQTranSrv.class.php');
require_once(QQXCLASS_PATH.'QQOauth.class.php');

/*
 * @brief QQAction类，api外部对象，调用接口全部依赖于此对象
 * */
class QQAction extends EAction  implements IBaseInfoAction, AdvancedAction {
    /**
     *调用 API时的公共参数
     * @var array 
     */
    protected $kesArr;    
        /**
         * 初始化APIMap
         * 加#表示非必须，无则不传入url(url中不会出现该参数)， "key" => "val" 表示key如果没有定义则使用默认值val
         * 规则 array( baseUrl, argListArr, method)
         */
    protected static $APIMap =  array(  
//<editor-fold defaultstate="collapsed" desc="APIMap代码折叠">           
            /* qzone  */
            'add_blog' => array(
                'https://graph.qq.com/blog/add_one_blog',
                array('title', 'format' => 'json', 'content' => null),
                'POST'
            ),
            'add_topic' => array(
                'https://graph.qq.com/shuoshuo/add_topic',
                array('richtype','richval','con',"#lbs_nm","#lbs_x","#lbs_y",'format' => 'json', "#third_source"),
                'POST'
            ),
            'get_user_info' => array(
                'https://graph.qq.com/user/get_user_info',
                array('format' => 'json'),
                'GET'
            ),
            'add_one_blog' => array(
                'https://graph.qq.com/blog/add_one_blog',
                array('title', 'content', 'format' => 'json'),
                'GET'
            ),
            'add_album' => array(
                'https://graph.qq.com/photo/add_album',
                array('albumname', "#albumdesc", "#priv", 'format' => 'json'),
                'POST'
            ),
            'upload_pic' => array(
                'https://graph.qq.com/photo/upload_pic',
                array('picture', "#photodesc", "#title", "#albumid", "#mobile", "#x", "#y", "#needfeed", "#successnum", "#picnum", 'format' => 'json'),
                'POST'
            ),
            'list_album' => array(
                'https://graph.qq.com/photo/list_album',
                array('format' => 'json')
            ),
            'add_share' => array(
                'https://graph.qq.com/share/add_share',
                array('title', 'url', "#comment","#summary","#images",'format' => 'json',"#type"=>'4',"#playurl","#nswb",'site','fromurl'),
                'POST'
            ),
            'check_page_fans' => array(
                'https://graph.qq.com/user/check_page_fans',
                array('page_id' => '314416946','format' => 'json')
            ),
            /*                    wblog                             */

            'add_t' => array(
                'https://graph.qq.com/t/add_t',
                array('format' => 'json', 'content',"#clientip","#longitude","#compatibleflag"),
                'POST'
            ),
            'add_pic_t' => array(
                'https://graph.qq.com/t/add_pic_t',
                array('content', 'pic', 'format' => 'json', "#clientip", "#longitude", "#latitude", "#syncflag", "#compatibleflag"),
                'POST'
            ),
            'del_t' => array(
                'https://graph.qq.com/t/del_t',
                array('id', 'format' => 'json'),
                'POST'
            ),
            'get_repost_list' => array(
                'https://graph.qq.com/t/get_repost_list',
                array('flag'=>'1', 'rootid', 'pageflag', 'pagetime', 'reqnum'=>'10', 'twitterid', 'format' => 'json')
            ),
            'get_info' => array(
                'https://graph.qq.com/user/get_info',
                array('format' => 'json')
            ),
            'get_other_info' => array(
                'https://graph.qq.com/user/get_other_info',
                array('format' => 'json', "#name", '#fopenid')
            ),
            'get_fanslist' => array(
                'https://graph.qq.com/relation/get_fanslist',
                array('format' => 'json', 'reqnum', 'startindex', "#mode", "#install", "#sex")
            ),
            'get_idollist' => array(
                'https://graph.qq.com/relation/get_idollist',
                array('format' => 'json', 'reqnum', 'startindex', "#mode", "#install")
            ),
            'add_idol' => array(
                'https://graph.qq.com/relation/add_idol',
                array('format' => 'json', "#name-1", "#fopenids-1"),
                'POST'
            ),
            'del_idol' => array(
                'https://graph.qq.com/relation/del_idol',
                array('format' => 'json', "#name-1", "#fopenid-1"),
                'POST'
            ),
            /*                           pay                          */

            'get_tenpay_addr' => array(
                'https://graph.qq.com/cft_info/get_tenpay_addr',
                array('ver' => 1,'limit' => 5,'offset' => 0,'format' => 'json')
            )
//</editor-fold> 
        );
        /**
         * 从$_SESSION获取调用API的共用参数
         * @param string $access_token
         * @param int $app_id 应用ID,enewsmember_connect_app.id
         * @return array('oauth_consumer_key','access_token','openid')
         */
   public static function GetCommInvokeParamFromSession($access_token, $app_id=NULL) {
        if (!$app_id && empty($access_token))
            return array();
        if (!isset($_SESSION)) session_start();
        $ubeis = EIService::GetCUBEISForSession();
        if (empty($ubeis))  return array();
        $kd = NULL;
        foreach ($ubeis as $d)
        {
            if ($access_token == $d['token'])
            {
                if ($app_id)
                {
                    if ($app_id == $d['aid'])
                    {
                        $kd = &$d;
                        break;
                    }
                } else
                {
                    $kd = &$d;
                    break;
                }
            }
        }
        if ($kd !== NULL)
        {
            return array(
                'oauth_consumer_key' => $ubeis[$kd]['appkey'], //申请QQ登录成功后，分配给应用的appid
                'access_token' => $access_token, //access_token有3个月有效期。
                'openid' => $ubeis[$kd]['openid']//用户的ID，与QQ号码一一对应。
            );
        }
        return NULL;
        }
        /**
         * 输出错误信息
         * @param array $params array('返回码','消息','模式')
         * 模式=>0:为强制弹出对话框,1为强制输出字符串,2为throw Exption,NULL为执行系统的printerror
         * @return boolean | String
         */
        public function PutError(array &$params) {
             $myps= $this->GetTranSrv()->ConvertParam($params,__FUNCTION__);
            if(empty($myps) || (empty($myps['ret']) && empty($myps['msg']))) return FALSE;
            $code = $myps['ret'];
            $description = $myps['msg'];
            $mode = $myps['mode'];
            if(is_array($code) && isset($code['ret']))
            {
                $code = intval($code['ret']);
                if(empty($description)) $description = $code['msg'];
            }
            return QQError::showError($code,$description,$mode);
        }
       /**
	 * 重载的构造函数
	 * @param array $params :  array('client_id','client_secret','TranSrv','access_token','redirect_uri','oauth') | array("应用号","应用密钥","访问令牌")|array(chr(1)."client_id",chr(1)."client_secret",chr(1)."access_token")|array('id')
	 */
	private function construct2(array $params) {
          $myps = $params;
          $this->_tranSrv =TranSrv::CreateFromParams($params);
          if(!empty($this->_tranSrv)){
              $myps = $this->_tranSrv->ConvertParam($params, __FUNCTION__); 
              $myps['TranSrv'] = &$this->_tranSrv;
          }
                
          if(empty($myps ['oauth'])){
               $appsum =EIService::GetAppSum(0,$myps['client_id'],$myps['client_secret']);
               $myps ['oauth'] = EIService::GetOAuth($appsum['appid'], $myps);
          }
        if (!empty($myps)) {
            if (isset($myps['client_id']) && isset($myps ['client_secret'])) {
                $oautho = NULL;
                if(isset($myps ['oauth'])){
                    $oautho = $myps ['oauth'];
                }
                $tranSrvr = NULL;
                if(isset($myps ['TranSrv']))
                    $tranSrvr = &$myps ['TranSrv'];
                else {
                    if(isset($this->_tranSrv))
                       $tranSrvr =&$this->_tranSrv;
                }
                return $this->__construct(
                        $myps ['client_id'],
                        $myps ['client_secret'],
                        $tranSrvr,
                        (isset($myps ['access_token']) ? $myps ['access_token'] : NULL),
                        (isset($myps ['redirect_uri']) ? $myps ['redirect_uri'] : NULL), 
                        $oautho
                );
            }
            elseif (isset($myps ['id'])) {
                return $this->__construct((int)$myps ['id']);
            }
        }
    }

    /**
 * QQAction,操作类
 * @param string $client_id_obj string|array|int 腾讯AppId,可以为array,但后面的参数留空. 如果只具有一个参数,而且为整数时,表示为授权表的ID(enewsmember_connect.id),如果只具有一个参数,而且为字符串时,表示为应用表的名称(enewsmember_connect_app.appname)
 * @param string $client_secret 腾讯AppSecret
 * @param QQTranSrv  $tranSrvRef 转换翻译对象引用 
 * @param string $access_token
 * @param string $redirect_uri
 * @param QQOAuth $oauth 注意:请传入引用
 * @return QQAction
 */
  public function __construct($client_id_obj, $client_secret = NULL,QQTranSrv $tranSrvRef=NULL, $access_token = NULL, $redirect_uri = NULL, QQOAuth $oauth = NULL){
        if (func_num_args() == 1 && is_array($client_id_obj))
            return $this->construct2($client_id_obj);
        parent::__construct($oauth);
        if(empty($this->_tranSrv) && !empty($this->oauth)) $this->_tranSrv = &$this->oauth->GetTranSrv();
        if($tranSrvRef != $this->_tranSrv)
           $this->_tranSrv = $tranSrvRef;        
        if (empty($this->oauth)) {
            if (func_num_args() == 1) {
                if (is_int($client_id_obj)) {
                    $this->oauth = new QQOAuth($client_id_obj);
                }
                elseif (empty($this->_tranSrv) && is_string($client_id_obj)) {//没有EOauth对象的情况
                    $pp1 = array('AppName'=>$client_id_obj);
                    $this->_tranSrv = new QQTranSrv($pp1);                    
                }
            }
            else{
               $this->oauth = new QQOAuth($client_id_obj, $client_secret,$tranSrvRef, $access_token, $redirect_uri); 
            }                
        }
        if(empty($this->_tranSrv) && !empty($this->oauth)){
              $this->_tranSrv = &$this->oauth->GetTranSrv();
        }       
        $this->keysArr = array(
            'oauth_consumer_key' => (int) $this->oauth->AppKey(),
            'access_token' => $this->oauth->GetAccessToken(),
            'openid' => $this->oauth->GetOpenID()
        );
    }


    /**
     * 调用相应api
     * @param array $arr $arr['_发送方式_'] == -1:用EIService::CurlRequest发送
     * @param type $argsList
     * @param type $baseUrl
     * @param type $method
     * @return type
     */
    private function _applyAPI($arr, $argsList, $baseUrl, $method){
        $pre = "#";
        $keysArr = $this->keysArr;
        $send_type = 0;
        if(isset($arr['_发送方式_'])){
           $send_type = (int)$arr['_发送方式_']; 
           unset($arr['_发送方式_']);
        } 
        $filearr =  array('image','pic');
        $haveFile = FALSE;
        $optionArgList = array();//一些多项选填参数必选一的情形
        foreach($argsList as $key => $val){
            $tmpKey = $key;
            $tmpVal = $val;            
            if(!is_string($key)){
                $tmpKey = $val;
                $xpi = strpos($val,$pre);
                if($xpi === 0){
                    $gtmpval = substr($val, strlen($pre));
                    if(empty($arr[$gtmpval])) $tmpVal = $pre;
                    $tmpKey = $gtmpval;
                    if(preg_match("/-(\d$)/", $tmpKey, $res)){
                        $tmpKey = str_replace($res[0], "", $tmpKey);
                        $optionArgList[$res[1]][] = $tmpKey;
                    }
                }else{
                    $tmpVal = null;
                }
            }
            $gtmpval = $tmpKey;
            if(strlen($gtmpval)> 1 &&  $gtmpval{0} == '#') $gtmpval = substr($gtmpval, 1);
            if(in_array($gtmpval,$filearr) && !empty($arr[$gtmpval]) && $arr[$gtmpval]{0} == '@' )
               $haveFile = TRUE;
            
            //-----如果没有设置相应的参数
            if(empty($arr[$tmpKey])){

                if($tmpVal == $pre){//则使用默认的值
                    continue;
                }else if($tmpVal){                    
                    if($tmpKey{0}=='#')
                    $tmpKey = substr($tmpKey,1);
                    $arr[$tmpKey] = $tmpVal;                   
                }else{
                    if($v = $_FILES[$tmpKey]){

                        $filename = dirname($v['tmp_name'])."/".$v['name'];
                        move_uploaded_file($v['tmp_name'], $filename);
                        $arr[$tmpKey] = "@$filename";
                        $haveFile = TRUE;
                    }else{
                        $ep = array('ForbidConvertIn'=>TRUE,'msg'=>"api调用参数错误:未传入参数$tmpKey",'mode'=>2);//ForbidConvert为跳过参数转换
                        $this->PutError($ep);
                    }
                }
            }
            $keysArr[$tmpKey] = $arr[$tmpKey];
        }
        //检查选填参数必填一的情形
        foreach($optionArgList as $val){
            $n = 0;
            foreach($val as $v){
                if(in_array($v, array_keys($keysArr))){
                    $n ++;
                }
            }

            if(! $n){
                $str = implode(",",$val);
                 $ep = array('ForbidConvertIn'=>TRUE,'msg'=>"api调用参数错误",$str."必填一个",'mode'=>2);//ForbidConvert为跳过参数转换
                 $this->PutError($ep);                
            }
        }
        $response=NULL;        
        if($send_type==0){            
            if($method == 'POST'){
                if($baseUrl == "https://graph.qq.com/blog/add_one_blog") $response = EIService::httppost($baseUrl, $keysArr, 1);
                                else $response = EIService::httppost($baseUrl, $keysArr, 0);
            }else if($method == 'GET'){
                $response = EIService::httpget($baseUrl, $keysArr);
            }
        }
        elseif($send_type==-1){  
              $eiobj = $this->oauth->GetEiService($this->oauth);
              $response =  EiService::CurlRequest($baseUrl, $method, $keysArr,$haveFile,$eiobj);
        }
        return $response;

    }

    /**
     * _call
     * 魔术方法，做api调用转发
     * @param string $name    调用的方法名称
     * @param array $arg      参数列表数组,$arg['_发送方式_'] == -1:用EIService::CurlRequest发送
     * @since 5.0
     * @return array          返加调用结果数组
     */
    public function __call($name,$arg){
        //如果APIMap不存在相应的api
        if(empty(self::$APIMap[$name])){
              $ep = array('msg'=>"api调用名称错误,不存在的API: <span style='color:red;'>$name</span>", 'ForbidConvertIn'=>TRUE);//ForbidConvert为跳过参数转换
              $this->PutError($ep);   
        }
        
        //从APIMap获取api相应参数
        $baseUrl = self::$APIMap[$name][0];
        $argsList = self::$APIMap[$name][1];
        $method = isset(self::$APIMap[$name][2]) ? self::$APIMap[$name][2] : 'GET';
        //对于get_tenpay_addr，特殊处理，php json_decode对\xA312此类字符支持不好
        if($name != 'get_tenpay_addr'){
            $response = json_decode($this->_applyAPI($arg, $argsList, $baseUrl, $method));
            $responseArr = $this->objToArr($response);
        }else{
            $responseArr = $this->simple_json_parser($this->_applyAPI($arg, $argsList, $baseUrl, $method));
        }
       return $responseArr;
    }

    //php 对象到数组转换
    private function objToArr($obj){
        if(!is_object($obj) && !is_array($obj)) {
            return $obj;
        }
        $arr = array();
        foreach($obj as $k => $v){
            $arr[$k] = $this->objToArr($v);
        }
        return $arr;
    }

   
    /**
     * get_access_token
     * 获得access_token
     * @param void
     * @since 5.0
     * @return string 返加access_token
     */
    public function get_access_token(){
        return $this->oauth->GetAccessToken();
    }

    //简单实现json到php数组转换功能
    private function simple_json_parser($json){
        $json = str_replace("{","",str_replace("}","", $json));
        $jsonValue = explode(",", $json);
        $arr = array();
        foreach($jsonValue as $v){
            $jValue = explode(":", $v);
            $arr[str_replace('"',"", $jValue[0])] = (str_replace('"', "", $jValue[1]));
        }
        return $arr;
    }
 // ---------------------------------接口实现-------------------------------------------------- 
/**
 * QQ互联不支持
 * @param array $params
 */
   public function UserTimeline(array &$params)
   {
      $ep = array('ret'=>50000,'mode'=>2,'msg'=>'函数'.__FUNCTION__, 'ForbidConvertIn'=>TRUE);//不支持的操作
      $this->PutError($ep);  
   }
   /**
    * 取最近的一条微博
    * @param array $params
    * @return type
    */
   public function GetSingleInfo(array &$params)
   {
       $myps= $this->GetTranSrv()->ConvertParam($params,__FUNCTION__);
       if (isset ( $myps ) && ! empty ( $myps )) {
            $result = $this->__call('get_info',$myps);
            $r = $result['tweetinfo'];
            return  $this->GetTranSrv()->ConvertParam($r,__FUNCTION__,($params['ForbidConvertOut']?-1:1));
           }
           else
           {
            $ep = array('ret'=>49999,'mode'=>2,'msg'=>'函数'.__FUNCTION__,'ForbidConvertIn'=>TRUE);//参数不完整
            $this->PutError($ep);    
           }		
   }
   /**
    * 转发一条微博
    * @param array $params{
    * 与CreateInfo函数的说明一样
    * }
    * @return array
    */
    public function RepostInfo(array &$params)
   {
       return  $this->CreateInfo($params);
   }
   /**
    * 发表一条微博(可以带图片)
    * @param array $params {
    * @param $params['format'] string : 定义API返回的数据格式。取值说明：为xml时表示返回的格式是xml；为json时表示返回的格式是json。 注意：json、xml为小写，否则将不识别。format不传或非xml，则返回json格式数据。
    * @param $params['content'] string must : 表示要发表的微博内容。必须为UTF-8编码，最长为140个汉字，也就是420字节。如果微博内容中有URL，后台会自动将该URL转换为短URL，每个URL折算成11个字节。若在此处@好友，需正确填写好友的微博账号，而非昵称。
    * @param $params['clientip'] string : 用户ip。必须正确填写用户侧真实ip，不能为内网ip及以127或255开头的ip，以分析用户所在地。
    * @param $params['longitude'] string : 用户所在地理位置的经度。为实数，最多支持10位有效数字。有效范围：-180.0到+180.0，+表示东经，默认为0.0。
    * @param $params['latitude'] string : 用户所在地理位置的纬度。为实数，最多支持10位有效数字。有效范围：-90.0到+90.0，+表示北纬，默认为0.0。
    * @param $params['syncflag'] string : 标识是否将发布的微博同步到QQ空间（0：同步； 1：不同步；），默认为0。该参数只支持OAuth1.0，OAuth2.0暂不支持。
    * @param $params['compatibleflag'] string :容错标志，支持按位操作，默认为0。0x20：微博内容长度超过140字则报错；0：以上错误均做容错处理，即发表普通微博。 
    * }
    * @return array {
    * @link http://wiki.connect.qq.com/add_t
    * }
    */
   public function CreateWeibo(array &$params) {
        $myps = $this->GetTranSrv()->ConvertParam($params, __FUNCTION__);
        if (isset($myps) && !empty($myps)) {
            if (!isset($myps['clientip'])) {
                $myps['clientip'] = $this->oauth->GetIP();
            }
            if (!empty($myps['content']) && isset($myps['compatibleflag']) && $myps['compatibleflag'] != 0) {
                $myps['content'] = $this->TruncateContent($myps['content'], SUBSTR_MODE_COUNT); // 截取前N个字符
            }
            $r = NULL;
            if (isset($myps['pic']))
                $r = $this->__call('add_pic_t', $myps);
            else
                $r = $this->__call('add_t', $myps);
            return $this->GetTranSrv()->ConvertParam($r, __FUNCTION__, ($params['ForbidConvertOut'] ? -1 : 1));
        } else {
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        }
    }
/**
 * 发布一条动态（feeds）到QQ空间，此外还可在腾讯微博上发一条微博（用户可自己选择是否转发到微博）
 * @link http://wiki.open.qq.com/wiki/website/add_share
 * @global array $public_r
 * @param array $params 传入的参数
 * <br/> $params['title'] 必须,feeds的标题，对应上文接口说明中的2。 最长36个中文字，超出部分会被截断。
 * <br/> $params['url'] 必须,分享所在网页资源的链接，点击后跳转至第三方网页
 * <br/> $params['comment'] 用户评论内容，也叫发表分享时的分享理由，最长40个中文字，超出部分会被截断。
 * <br/> $params['summary'] 所分享的网页资源的摘要内容，或者是网页的概要描述，最长80个中文字，超出部分会被截断。
 * <br/> $params['images'] 所分享的网页资源的代表性图片链接"，请以http://开头，长度限制255字符。多张图片以竖线（|）分隔，目前只有第一张图片有效，图片规格100*100为佳。
 * <br/> $params['format'] 定义API返回的数据格式。取值说明：为xml时表示返回的格式是xml；为json时表示返回的格式是json。注意：json、xml为小写，否则将不识别。format不传或非xml，则返回json格式数据。
 * <br/> $params['type']  分享内容的类型。4表示网页；5表示视频（type=5时，必须传入playurl）。
 * <br/> $params['playurl'] 长度限制为256字节。仅在type=5的时候有效，表示视频的swf播放地址。
 * <br/> $params['site'] 必须,分享的来源网站名称，请填写网站申请接入时注册的网站名称
 * <br/> $params['fromurl'] 必须,分享的来源网站对应的网站地址url，请以http://开头。
 * <br/> $params['nswb'] 值为1时，表示分享不默认同步到微博，其他值或者不传此参数表示默认同步到微博。
 * @return array
 * @throw EiException
 */
    public function CreateInfo(array &$params) {
        global $public_r;
        $myps = $this->GetTranSrv()->ConvertParam($params, __FUNCTION__);
        $vlurl = NULL;
        $allowUpPic = TRUE;
        if (isset($myps) && !empty($myps)) {
            if (!empty($myps['comment'])) {//短评
                $myps['comment'] = $this->TruncateContent($myps['comment'], SUBSTR_MODE_SCOMT);
            }
            if (!empty($myps['summary'])) {//摘要
                $myps['summary'] = $this->TruncateContent($myps['summary'], SUBSTR_MODE_SUMY);
            }
            if (!empty($myps['images'])) {
                if (is_array($myps['images'])) {
                    foreach ($myps['images'] as $ik => $img) {
                        if (strlen($img) > 255) {
                            $mmp = array('长网址' => $img);
                            $sr = $this->ShortUrl($mmp);
                            if (is_array($sr)) {
                                if(!isset($sr[0]))  $myps['images'][$ik] = $sr['s'];
                                else   $myps['images'][$ik] = $sr[0]['s'];
                            }
                        }
                    }
                    $myps['images'] = implode('|', $myps['images']);
                }
                elseif (strlen($myps['images']) > 255) {
                    $mmp = array('长网址' => $myps['images']);
                    $sr = $this->ShortUrl($mmp);
                    if (is_array($sr)) {
                        if(!isset($sr[0]))   $myps['images'] = $sr['s'];
                        else   $myps['images'] = $sr[0]['s'];
                    }
                }
            }            
            if (!empty($myps['playurl']) && strlen($myps['playurl']) > 255) {
                $mmp = array('长网址' => $myps['playurl']);
                $sr = $this->ShortUrl($mmp);
                if (is_array($sr)) {
                   if(!isset($sr[0])) $vlurl = $myps['playurl'] = $sr['s'];
                   else   $vlurl = $sr[0]['s'];
                }
            }
                if(empty($myps['images']) || !$allowUpPic) {
                    $cmy = $myps['url'] . ' ' . $myps['comment'];
                    if (!empty($myps['playurl'])){
                        if(!empty($vlurl)) $myps['playurl'] = $vlurl;
                         $cmy .= '[视频]' . $myps['playurl'];    
                    }
                    $cmy .= '[评论:' . $myps['title'] . ']';
                    if (isset($myps['compatibleflag']) && $myps['compatibleflag'] != 0)
                        $myps['content'] = $this->TruncateContent($cmy, SUBSTR_MODE_COUNT); // 截取前N个字符
                    else
                        $myps['content'] = $cmy;
                    unset($myps['url']);
                    unset($myps['images']);
                    unset($myps['summary']);
                    unset($myps['playurl']);
                    unset($myps['fromurl']);
                    unset($myps['site']);
                    $myps['ForbidConvertIn'] = TRUE;                    
                    $r = $this->CreateWeibo($myps);
                    return $r;
                }
                else
                {
                    //发图片微博,先转换参数:
                    $txt .=$myps['url'].' '.$myps['comment']. '[评论:' . $myps['title'] . ']';                    
                    $txt = $this->TruncateContent($txt, SUBSTR_MODE_COUNT); // 截取前N个字符
                    $mypic = $myps['images'];
                    if (is_array($mypic))
                        $mypic = $mypic[0];
                    $rpos = stripos($mypic, $public_r['newsurl']);
                    $picfpath = NULL;
                    $newfpath = NULL;
                    if ($rpos !== FALSE) { //是当前域名下的文件:
                        $picfpath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, substr($mypic, $rpos + strlen($public_r['newsurl']) + $rpos));
                    }
                    else {
                        $picc = file_get_contents($mypic); 
                        $newfpath = $picfpath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'e'. DIRECTORY_SEPARATOR . 'data'. DIRECTORY_SEPARATOR . 'tmp'. DIRECTORY_SEPARATOR .uniqid().'.'.pathinfo(parse_url($mypic, PHP_URL_PATH), PATHINFO_EXTENSION);
                        $fp = fopen($picfpath, "w");
                        if (fwrite($fp, $picc)) {
                            fclose($fp);
                            sleep(1);                           
                        }
                    }
                    if (!empty($picfpath)) {
                        unset($myps['pic']);
                        $picp = array('content' => $txt, 'clientip' => $this->oauth->GetIP(),'pic'=> '@'.$picfpath ,'ForbidConvertIn' => TRUE,'_发送方式_'=>-1);
                        $r = $this->__call('add_pic_t', $picp);
                        if (!empty($newfpath))
                            unlink($newfpath);                        
                    }
                }
        
            return $this->GetTranSrv()->ConvertParam($r, __FUNCTION__, ($params['ForbidConvertOut'] ? -1 : 1));
        } else {
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        }
    }

    /**
    * 根据微博ID删除指定微博。
    * @param array $params 
    * {
    * @param $params['id']-微博消息的ID，用来唯一标识一条微博消息。
    * @param $params['format']-定义API返回的数据格式。取值说明：为xml时表示返回的格式是xml；为json时表示返回的格式是json。 注意：json、xml为小写，否则将不识别。format不传或非xml，则返回json格式数据。
    * }
    * @return array
    */
  public function DestroyInfo(array &$params)
    {
        $myps = $this->GetTranSrv()->ConvertParam($params,__FUNCTION__);      
        if (isset($myps) && !empty($myps) && !empty($myps['id']))
        {
            $r = $this->__call('del_t', $myps);
            return $this->GetTranSrv()->ConvertParam($r,__FUNCTION__, ($params['ForbidConvertOut'] ? -1 : 1));
        } else
        {
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        }
    }
    /**
     * 获取一条微博的转播或评论信息列表。
     * @param array $params {
     * @param $params['rootid'] string -转发或点评的源微博的ID。
     * @param $params['flag'] string  -标识获取的是转播列表还是点评列表。0：获取转播列表；1：获取点评列表；2：转播列表和点评列表都获取。
     * @param $params['pageflag'] string  -分页标识。 0：第一页；1：向下翻页；2：向上翻页。
     * @param $params['pagetime'] string  本页起始时间。 第一页：0；向下翻页：上一次请求返回的最后一条记录时间；向上翻页：上一次请求返回的第一条记录的时间。
     * @param $params['reqnum'] string  每次请求记录的条数。取值为1-100条。
     * @param $params['twitterid'] string  翻页时使用。 第1-100条：0；继续向下翻页：上一次请求返回的最后一条记录id。
     * }
     * @return array {@link http://wiki.connect.qq.com/get_repost_list}
     **/
    public function ListComments(array &$params)
    {
        $myps = $this->GetTranSrv()->ConvertParam($params,__FUNCTION__);
        if (isset($myps) && !empty($myps) && !empty($myps['rootid']))
        {
            $myps['flag'] = '1';
            $r = $this->__call('get_repost_list', $myps);
            return $this->GetTranSrv()->ConvertParam($r,__FUNCTION__, ($params['ForbidConvertOut'] ? -1 : 1));
        } else
        {
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        }
    }
/**
 * QQ互联不支持
 * @param array $params
 */
    public function CreateComments(array &$params)
    {
      $ep = array('ret'=>50000,'mode'=>2,'msg'=>'函数'.__FUNCTION__, 'ForbidConvertIn'=>TRUE);//不支持的操作
      $this->PutError($ep);   
    }
    public function DestroyComments(array &$params)
    {
        return $this->DestroyInfo($params);
    }
    /**
     * 获取腾讯微博用户详细信息。
     * @param array $params {
     * @param $params['name'] - 其他用户的账户名。可选，name和fopenid至少选一个，若同时存在则以name值为主。
     * @param $params['fopenid'] - 其他用户的openid。可选，name和fopenid至少选一个，若同时存在则以name值为主。
     * @param $params[format] - 定义API返回的数据格式。取值说明：为xml时表示返回的格式是xml；为json时表示返回的格式是json。注意：json、xml为小写，否则将不识别。format不传或非xml，则返回json格式数据。
     * }
     * @return array {
     * @link http://wiki.connect.qq.com/get_user_info 当前用户;
     * @link http://wiki.connect.qq.com/get_other_info 其它用户
     * }
     */
    public function GetUserInfo(array &$params) {
        $myps = $this->GetTranSrv()->ConvertParam($params,__FUNCTION__);
        if (isset($myps) && !empty($myps)) {
            $r = NULL;
            if (isset($myps['fopenid']) || isset($myps['name'])) {
                $r = $this->__call('get_other_info', $myps); //array('data'=>array(),'ret'=>0,'msg'=>'ok')
                if ($r && !$r['ret'] && isset($r['data'])) {
                    $rd = $r['data'];
                    $rd['nickname'] = $rd['nick']; //手动翻译
                    $rd['figureurl_qq_1'] = $rd['head'];
                    $rd['gender'] = $rd['sex'];//性别
                    unset($rd['nick']);
                    unset($r['data']);
                    unset($r['head']);
                    unset($r['sex']);
                    $r = array_merge($r, $rd);
                }
            }
            if (empty($r) || $r['ret']) {
                $r = $this->__call('get_user_info', $myps); //array('nickname'=>'昵称','ret'=>0,'msg'=>'ok')
            }
            return $this->GetTranSrv()->ConvertParam($r,__FUNCTION__, ($params['ForbidConvertOut'] ? -1 : 1));
        }
        else {
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        }
    }

    /**
 * QQ互联不支持
 * @param array $params
 */
    public function GetUserEmail($params)
    {
      $ep = array('ret'=>50000,'mode'=>2,'msg'=>'函数'.__FUNCTION__, 'ForbidConvertIn'=>TRUE);//不支持的操作
      $this->PutError($ep);        
    }
    /**
     * 获取登录用户的听众列表。(别人收听此用户)
     * @param array $params {
     * @param $params[format] string : 定义API返回的数据格式。取值说明：为xml时表示返回的格式是xml；为json时表示返回的格式是json。 注意：json、xml为小写，否则将不识别。format不传或非xml，则返回json格式数据。
     * @param $params[reqnum] string must: 请求获取的听众个数。取值范围为1-30。
     * @param $params[startindex] string must: 请求获取听众列表的起始位置。 第一页：0；继续向下翻页：reqnum*（page-1）。
     * @param $params[mode] string : 获取听众信息的模式，默认值为0。 0：旧模式，新添加的听众信息排在前面，最多只能拉取1000个听众的信息。1：新模式，可以拉取所有听众的信息，暂时不支持排序。
     * @param $params[install] string : 判断获取的是安装应用的听众，还是未安装应用的听众。 0：不考虑该参数；1：获取已安装应用的听众信息；2：获取未安装应用的听众信息。
     * @param $params[sex] string : 按性别过滤标识，默认为0。此参数当mode=0时使用，支持排序。 1：获取的是男性听众信息；2：获取的是女性听众信息；0：不进行性别过滤，获取所有听众信息。
     * }
     * @return array {
     * @link http://wiki.connect.qq.com/get_fanslist
     * }
     */
    public function GetActiveFriends(array &$params)
    {
        $myps = $this->GetTranSrv()->ConvertParam($params,__FUNCTION__);
        if (isset($myps) && !empty($myps))
        {
            $r = $this->__call('get_fanslist', $myps);
            return $this->GetTranSrv()->ConvertParam($r,__FUNCTION__, ($params['ForbidConvertOut'] ? -1 : 1));
        } else
        {
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        }        
    }

    
    /**
     * 收听腾讯微博上的一个或者多个用户。
     * @param array $params {
     * @param $params[name] - 其他用户的账户名。要收听的用户的账户名列表。多个账户名之间用“,”隔开，例如：abc,bcde,cde。最多30个。 可选，name和fopenids至少选一个，若同时存在则以name值为主。
     * @param $params['fopenids'] - 要收听的用户的openid列表。多个openid之间用“_”隔开，例如：B624064BA065E01CB73F835017FE96FA_B624064BA065E01CB73F835017FE96FB。最多30个。 可选，name和fopenids至少选一个，若同时存在则以name值为主。
     * @param $params[format] - 定义API返回的数据格式。取值说明：为xml时表示返回的格式是xml；为json时表示返回的格式是json。注意：json、xml为小写，否则将不识别。format不传或非xml，则返回json格式数据。
     * }
     * @return array {
     * @link http://wiki.connect.qq.com/add_idol
     * }
     */
public function CreateFriendships(array &$params)
{
        $myps = $this->GetTranSrv()->ConvertParam($params,__FUNCTION__);
        if (isset($myps) && !empty($myps))
        {
            $r = $this->__call('add_idol', $myps);
            return $this->GetTranSrv()->ConvertParam($r,__FUNCTION__, ($params['ForbidConvertOut'] ? -1 : 1));
        } else
        {
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        }      
}
    /**
     * 取消收听腾讯微博上的用户。
     * @param array $params {
     * @param $params[name] - 其他用户的账户名。要收听的用户的账户名 可选，name和fopenid至少选一个，若同时存在则以name值为主。
     * @param $params['fopenid'] - 要收听的用户的openid。可选，name和fopenid至少选一个，若同时存在则以name值为主。
     * @param $params[format] - 定义API返回的数据格式。取值说明：为xml时表示返回的格式是xml；为json时表示返回的格式是json。注意：json、xml为小写，否则将不识别。format不传或非xml，则返回json格式数据。
     * }
     * @return array {
     * @link http://wiki.connect.qq.com/del_idol
     * }
     */
public function DestroyFriendships(array &$params)
{
         $myps = $this->GetTranSrv()->ConvertParam($params,__FUNCTION__);
        if (isset($myps) && !empty($myps))
        {
            $r = $this->__call('del_idol', $myps);
            return $this->GetTranSrv()->ConvertParam($r,__FUNCTION__, ($params['ForbidConvertOut'] ? -1 : 1));
        } else
        {
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        }    
}
	/**
	 * 将内容截取为规定的长度，超过的将被截掉(每个URL折算成12个字节。官方为11个字节)
	 * @param $str string 原始内容
	 * @param $submode int 截取的模式，SUBSTR_MODE_COUNT 或者 SUBSTR_MODE_BYTE
	 * @return string
	 */
	public function TruncateContent($str,$submode=SUBSTR_MODE_COUNT) {
		if (empty ( $str ))
			return '';
                $maxlen = QQTranSrv::MaxInfoLen($submode);
                    if (strlen ( $str ) < $maxlen)
			return $str;
		$pn = '/\b(http(s)?:\/\/\S+)/i';
		$pn2 = '/\b(http(s)?:\/\/\S+)$/i';
		$ps = 'http://url/X';//如果微博内容中有URL，后台会自动将该URL转换为短URL，每个URL折算成12个字节。
		$sa = array ();
		$rstr = $str;
		if(preg_match_all($pn, $str,$sa)>0)
		{
			$rstr = preg_replace($pn, $ps, $str);
		}
		$pstr = EIService::cn_substr ( $rstr, $maxlen, 0,  SUBSTR_MODE_COUNT); // 截取前N个字符
		if (preg_match ( $pn2, $pstr, $endurl )) {
			if ($endurl[0] !== $ps)// 最后一个链接不完整，删除：
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
         * 获取当前用户双向收听的听众列表
         * @param array $params
         * @return type
       */
        public function BilateralFriends(array &$params)
        {
            $myps = $this->GetTranSrv()->ConvertParam($params,__FUNCTION__);
            $kpas = array('reqnum'=>30,'startindex'=>0,'mode'=>1,'ForbidConvertIn'=>TRUE,'ForbidConvertOut'=>TRUE,'format'=>'json');//禁止参数转换
            if(isset($myps['install']))$kpas['install']= $myps['install'];
            if(isset($myps['sex']))$kpas['sex']= $myps['sex'];
            $trs = array();
            $tr= $this->GetActiveFriends($kpas);
            while(!empty($tr))//取得我的听众列表。
            {
                if($tr['ret']!=0) break;
                $trs[$tr['info']['openid']] = $tr['info'];
                if($tr['hasnext']==1) break;
                $kpas['startindex'] =strval(intval($kpas['startindex'])+1);
                $tr= $this->GetActiveFriends($kpas);
            }
            $trs2 = array();
            if(!empty($trs))
            {
               $kpas['startindex'] =  '0';//恢复
               $tr= $this->GetMyFriends($kpas);
               while(!empty($tr))//取得我的收听列表。
                {
                    if($tr['ret']!=0) break;
                    $trs2[$tr['info']['openid']] = $tr['info'];
                    if($tr['hasnext']==1) break;
                    $kpas['startindex'] =strval(intval($kpas['startindex'])+1);
                    $tr= $this->GetMyFriends($kpas);
                }
            }
            //取交集:
           $rv = array_intersect_key($trs,$trs2);
           $r = array('info'=>$rv);
           $r['ret'] = 0;
           $r['msg'] = 'ok';
           $r['errcode'] = 0;
           return $this->GetTranSrv()->ConvertParam($r,__FUNCTION__, ($params['ForbidConvertOut'] ? -1 : 1));
        }
        /**
         * QQ互联不支持
         * @param array $params
         */
  public function ReplyComments(array &$params)
  {
      $ep = array('ret'=>50000,'mode'=>2,'msg'=>'函数'.__FUNCTION__, 'ForbidConvertIn'=>TRUE);//不支持的操作
      $this->PutError($ep);  
  }
  //-------------------------接口实现结束------------------------------------
  /**
     * 获取登录用户收听的人的列表。
     * @param array $params {
     * @param $params[format] string : 定义API返回的数据格式。取值说明：为xml时表示返回的格式是xml；为json时表示返回的格式是json。 注意：json、xml为小写，否则将不识别。format不传或非xml，则返回json格式数据。
     * @param $params[reqnum] string must: 请求获取的收听个数。取值范围为1-30。
     * @param $params[startindex] string must: 请求获取听众列表的起始位置。 第一页：0；继续向下翻页：reqnum*（page-1）。
     * @param $params[mode] string : 获取听众信息的模式，默认值为0。 0：旧模式，新添加的听众信息排在前面，最多只能拉取1000个听众的信息。1：新模式，可以拉取所有听众的信息，暂时不支持排序。
     * @param $params[install] string : 判断获取的是安装应用的听众，还是未安装应用的听众。 0：不考虑该参数；1：获取已安装应用的听众信息；2：获取未安装应用的听众信息。
     * @param $params[sex] string : 按性别过滤标识，默认为0。此参数当mode=0时使用，支持排序。 1：获取的是男性听众信息；2：获取的是女性听众信息；0：不进行性别过滤，获取所有听众信息。
     * }
     * @return array {
     * @link http://wiki.connect.qq.com/get_idollist
     * }
     */
    public function GetMyFriends(array &$params)
    {
        $myps = $this->GetTranSrv()->ConvertParam($params,__FUNCTION__);
        if (isset($myps) && !empty($myps))
        {
            $r = $this->__call('get_idollist', $myps);
            return $this->GetTranSrv()->ConvertParam($r,__FUNCTION__, ($params['ForbidConvertOut'] ? -1 : 1));
        } else
        {
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        }         
    }
    /**
     * 获取登录用户的相册列表。(需要申请权限)
     * @param array $params
     * @return array{
     * ret: 返回码
     * albumid: 相册ID
     * classid: 相册分类ID
     * createtime: 相册创建时间
     * desc: 相册描述
     * name: 相册名称
     * coverurl: 相册封面照片地址
     * picnum: 照片数
     * albumnum: 相册总数
     * msg: 错误消息
     * @link http://wiki.connect.qq.com/list_album
     * }
     */
     public function GetAlbumList(array &$params)
    {
        $myps = $this->GetTranSrv()->ConvertParam($params,__FUNCTION__);
        if (isset($myps) && !empty($myps))
        {
            $r = $this->__call('list_album', $myps);
            return $this->GetTranSrv()->ConvertParam($r,__FUNCTION__, ($params['ForbidConvertOut'] ? -1 : 1));
        } else
        {
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        }         
    } 
    /**
     * 登录用户上传照片，支持单张上传和批量上传。
     * @param array $params{
     * @param $params['picture'] string must : 上传照片的文件名以及图片的内容（在发送请求时，图片内容以二进制数据流的形式发送），注意照片名称不能超过30个字符。
     * @param $params['photodesc'] string : 照片描述，注意照片描述不能超过200个字符。
     * @param $params['albumid'] string : 相册id。可不填，不填时则根据“mobile”标识选择默认上传的相册。
     * @param $params['title'] string : 照片的命名，必须以.jpg, .gif, .png, .jpeg, .bmp此类后缀结尾。
     * @param $params['mobile'] int : 标志位，0表示PC，1表示手机。用于当不传相册id时（即albumid为空时）控制是否传到手机相册。 （1）如果传1，则当albumid为空时，图片会上传到手机相册；（2）如果不传或传0，则当albumid为空时，图片会上传到贴图相册；
     * @param $params['x'] string : 照片拍摄时的地理位置的经度。请使用原始数据（纯经纬度，0-360）。
     * @param $params['y'] string : 照片拍摄时的地理位置的纬度。请使用原始数据（纯经纬度，0-360）。
     * @param $params['format'] string : 定义API返回的数据格式。 取值说明：为xml时表示返回的格式是xml；为json时表示返回的格式是json。注意：json、xml为小写，否则将不识别。format不传或非xml，则返回json格式数据。
     * @param $params['needfeed'] int : 标识上传照片时是否要发feed（0：不发feed； 1：发feed）。 如果不填则默认为发feed。
     * @param $params['successnum'] int : 批量上传照片时，已成功上传的张数，指明上传完成情况。 单张上传时可以不填，不填则默认为0。
     * @param $params['picnum'] int : {批量上传照片的总张数，如果不填则默认为1。 
     * -如果picnum=1，为单张上传，发送单张上传feed；
     * -如果picnum>1，为批量上传，发送批量上传feed。
     * 批量上传方式：picnum为一次上传照片的张数，successnum初始值为0，每调用一次照片上传接口后递增其值。
     * 信息中心中的feed表现形式：批量上传时最新的7张在feed中展示。其中最新上传的一张图片展示为大图，剩下的六张按从新到旧的顺序展示为小图，其他图片不在feed中展示。}
     * }
     * @return array {
     * ret: 返回码
     * albumid: 相册ID
     * lloc: 大图ID
     * sloc: 小图ID
     * large_url: 大图地址
     * small_url: 小图地址
     * height: 图片高（单位：像素）
     * width: 图片宽（单位：像素）
     * msg: 错误消息
     * @link http://wiki.connect.qq.com/upload_pic
     * }
     */
     public function UploadPic(array &$params)
    {
        $myps = $this->GetTranSrv()->ConvertParam($params,__FUNCTION__);
        if (isset($myps) && !empty($myps))
        {
            $r = $this->__call('upload_pic', $myps);
            return $this->GetTranSrv()->ConvertParam($r,__FUNCTION__, ($params['ForbidConvertOut'] ? -1 : 1));
        } else
        {
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        }         
    }  
    /**
     * 登录用户创建相册。(需要申请权限)
     * @param array $params
     * @return array
     * @link http://wiki.connect.qq.com/add_album 
     */
     public function CreateAlbum(array &$params)
    {
        $myps = $this->GetTranSrv()->ConvertParam($params,__FUNCTION__);
        if (isset($myps) && !empty($myps))
        {
            $r = $this->__call('add_album', $myps);
            return $this->GetTranSrv()->ConvertParam($r,__FUNCTION__, ($params['ForbidConvertOut'] ? -1 : 1));
        } else
        {
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        }         
    }  
    /**
     * 获取登录用户的照片列表。(需要申请权限)
     * @param array $params {
     * @param $params['format'] string : 定义API返回的数据格式。 取值说明：为xml时表示返回的格式是xml；为json时表示返回的格式是json。注意：json、xml为小写，否则将不识别。format不传或非xml，则返回json格式数据。
     * @param $params['albumid'] string must: 表示要获取的照片列表所在的相册ID。}
     * @return array 
     * @link http://wiki.connect.qq.com/list_photo
     */
     public function ListPhoto(array &$params)
    {
        $myps = $this->GetTranSrv()->ConvertParam($params,__FUNCTION__);
        if (isset($myps) && !empty($myps))
        {
            $r = $this->__call('list_photo', $myps);
            return $this->GetTranSrv()->ConvertParam($r,__FUNCTION__, ($params['ForbidConvertOut'] ? -1 : 1));
        } else
        {
            $ep = array('ret' => 49999, 'mode' => 2, 'msg' => '函数' . __FUNCTION__, 'ForbidConvertIn' => TRUE); //参数不完整
            $this->PutError($ep);
        }         
    }     
//--------------------------------------------------------------

    
    
    

}
