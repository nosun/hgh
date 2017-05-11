<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'eibase.php' );
/*
 * QQ互联转换翻译类(2014年4月2日21:06)
 */

/**
 * QQ互联转换翻译类
 *
 * @author LGM
 */
class QQTranSrv extends TranSrv {
    /**
     * QQTranSrv QQ互联转换翻译服务
     * @param array $params ,必须包含 $params['AppName']  或者  $params['AppID'],$params['appname']
     */
    public  function __construct(array $params){
        parent::__construct($params);        
    }
       /**
	 * 参数转换表
	 * @var array 将参数翻译成内部参数
	 */
	protected static $TRANSLATE_PARAMS_MAP = array(
            //<editor-fold defaultstate="collapsed" desc="传入参数转换表">  
                0 =>array(//公共/默认
			'应用号'=>'client_id','应用密钥'=>'client_secret','访问令牌'=>'access_token','更新的令牌'=>'refresh_token','授权回调地址'=>'redirect_uri','取消授权回调地址'=>'revokeoauthurl',
			'user'=>'user','openid'=>'uid','username'=>'nickname',
			'开始ID'=>'twitterid','最大ID'=>'max_id','count'=>'count','page'=>'pageflag','请求数量'=>'reqnum',
			'仅当前应用'=>'base_app','内容类型,'=>'feature','评论到原文'=>'comment_ori',
			'id'=>'id','ip'=>'clientip','内容'=>'content','评论'=>'comment','评论ID'=>'cid','sort'=>'sort',
			'可见'=>'visible','ID列表'=>'list_id','pic'=>'pic','url'=>'url','imgurl'=>'imgurl','图片ID'=>'pic_id','lat'=>'latitude','long'=>'longitude',
			'元数据'=>'annotations','用户筛选选项'=>'filter_by_author','仅ID'=>'onlyid','补充提及'=>'without_mention',
			'转发时评论'=>'is_comment','说明'=>'description','性别'=>'gender','地区'=>'location','介绍'=>'introduction','概述'=>'summary',
                        '头像'=>'figureurl_qq_1','大头像'=>'figureurl_qq_2','空间头像'=>'figureurl_1','空间大头像'=>'figureurl_2','空间小头像'=>'figureurl',
                        '返回码'=>'ret','消息'=>'msg','模式'=>'mode','容错标志'=>'compatibleflag','同步'=>'syncflag',
                        'rootid'=>'rootid','媒体网址'=>'playurl','标题'=>'title'
                     ),
            'ShortUrl'=>array('长网址'=>'url','别名'=>'alias'),
            'CreateInfo'=>array('图片'=>'images','网址'=>'url'),
          //</editor-fold>  
	);
        protected static $TRANSLATE_PARAMS_MAP_R = array(
           //<editor-fold defaultstate="collapsed" desc="传出参数转换表">  
           
           //</editor-fold>   
        );
	/**
	 * 内容正则翻译规则表
	 * @var 将网站内容翻译成第三方内容的正则式规则组
	 */
	static $TRANSLATE_REGEX_MAP = array(
			'/\&nbsp;/'=>' ','|\<br\s*/?\>|i'=>' ','/\[~e\.([\w\x{4e00}-\x{9fa5}]+?)~\]/u'=>'[\1]'
	);	
	/**
	 * 内容词组翻译表
	 * @var array 将网站内容关键字翻译成第三方内容
	 */
	static $TRANSLATE_KEYWORDS_MAP = array(
			'[~e.jy~]'=>'/惊讶','[~e.kq~]'=>'/流泪','[~e.se~]'=>'/色','[~e.sq~]'=>'/发怒','[~e.lh~]'=>'/冷汗','[~e.ka~]'=>'/可爱','[~e.hh~]'=>'/憨笑','[~e.ys~]'=>'/害羞','[~e.ng~]'=>'/快哭了','[~e.ot~]'=>'/吐',
       );

	private static $tra_kw_key = NULL;
	private static $tra_kw_val = NULL;
        /**
         * 某种信息最大的长度
         * @param int $kind 取某种信息的类型代号
         * @return int
         */
        public static function MaxInfoLen($kind=0)  {
            $r = 0;
            switch ((int)$kind)
            {
                case SUBSTR_MODE_SCOMT:
                    $r = 40;
                    break;
                case SUBSTR_MODE_SUMY:
                    $r = 80;
                    break;                
                case SUBSTR_MODE_BYTE:
                    $r = 420;
                    break;
                case SUBSTR_MODE_COUNT:    
                default:
                  $r = 140;
                    break;                    
            }  
            return $r;
        }
 /**
     * 参数转换表
     * @param string $funkey 入口键(每个函数的转换表可能不一致,0为默认/公用的转换表) 
     * @return array 将参数翻译成内部参数
     * @throws Exception
     */
    protected function &TranslateParamsMap($funkey = 0) {
        if (empty($funkey))
            $funkey = 0;
        if(!isset(self::$TRANSLATE_PARAMS_MAP[$funkey]))
            self::$TRANSLATE_PARAMS_MAP[$funkey] = array();
        return self::$TRANSLATE_PARAMS_MAP[$funkey];
    }

    /**
     * 参数转换表(反向)
     * @param string $funkey 入口键(每个函数的转换表可能不一致,0为默认/公用的转换表)
     * @return array 将内部参数翻译成外部可识别的参数
     * @throws Exception
     */
    protected function &TranslateParamsMapR($funkey = 0) {
        if (empty($funkey))
            $funkey = 0;
        if (!isset(self::$TRANSLATE_PARAMS_MAP_R[$funkey])) {
            $defd = self::TranslateParamsMap($funkey);
            if (!empty($defd)) {
                self::$TRANSLATE_PARAMS_MAP_R[$funkey] = array_flip($defd); //交换键与值
            }
            else 
                self::$TRANSLATE_PARAMS_MAP_R[$funkey] = array();
        }
        return self::$TRANSLATE_PARAMS_MAP_R[$funkey];
    }

    protected  function &TraKwKey()
        {
           if(self::$tra_kw_key!==NULL)  return self::$tra_kw_key;
           self::$tra_kw_key = array_keys(self::$TRANSLATE_KEYWORDS_MAP);
           return self::$tra_kw_key;
        }      
        protected  function &TraKwVal()
        {
           if(self::$tra_kw_val!==NULL) return self::$tra_kw_val;
           self::$tra_kw_val = array_values(self::$TRANSLATE_KEYWORDS_MAP);
           return self::$tra_kw_val ;
        }
        protected function &TranslateRegexMap()
        {
            return self::$TRANSLATE_REGEX_MAP;
        }
        protected function &TranslateKeyWordsMap()
        {
           return self::$TRANSLATE_KEYWORDS_MAP; 
        } 
}
