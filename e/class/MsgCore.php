<?php
/**
 * @author LGM (LGM增加2014年2月24日9:30)
 * 静默模式的消息输出异常处理类
 */
class PutInfoException extends Exception
{
	/**
	 * 静默模式的消息输出异常处理类
	 * @param string $message 必须,消息内容，包含消息头
	 * @param number $code
	 */
	public function __construct($message, $code = 0) {
		// 确保所有变量都被正确赋值
		parent::__construct(ltrim($message), $code);
	}

	//自定义字符串输出的样式
	public function __toString() {
		return __CLASS__ . "-[{$this->code}]: {$this->getMsgBody()}\n";
	}
	/**
	 * 取得消息的内容，不含消息头
	 * @see Exception::getMessage()
	 * @return string
	 */
	public function getMsgBody()
	{
		if($this->message{0}=='{' && $this->message{1}==':')
		{
			$mbpos= strpos($this->message,':}',2);
			if($mbpos>2)
			{
				return substr($this->message,$mbpos+2);
			}
		}
		return $this->message;
	}
	/**
	 * 取得消息头
	 * @return array | NULL array('T','L','D'=>object)
	 */
	public function getMeta() {
		$ce= MsgCore::ParseInfoHeader($this->message);
		return $ce;
	}
}
/**
 * 加密的向量数据提供(在部署到新网站时,必须重构!)
 */
class EncryptIV
{
    public static $B64TABLE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
    private static $cfgpath;
    const BASE64TRATABLE = 'BASE64KEYIVTABLE'; 
    /**
     * 取向量表文件路径
     * @return string
     */
    public static function GetCfgFilePath()
    { 
        if(empty(self::$cfgpath))
        self::$cfgpath = ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'base64keyiv.php';
        return self::$cfgpath;
    }
    /**
     * 生成向量数组到文件(扩展变量:BASE64TRATABLE)
     */
    public static function GenerateIV()
    {
        global $public_r;
        $xc = array();    
        //生成128组:
        for($i=0;$i<128;$i++)
        {
            $a1 = range(0,63,1);
            shuffle($a1);
            $add = array();
            for($d=0;$d<64;$d++)
            {
                $add[self::$B64TABLE{$d}] = self::$B64TABLE{$a1[$d]};   
            }
            $xc[] = $add;        
        }
        $s =serialize($xc);
        $pl_filename=self::GetCfgFilePath(); 
	WriteFiletext_n($pl_filename,$s);//LGM 增加 ：生成代码文件 2014年2月19日9:41
	$public_r[self::BASE64TRATABLE]=$xc;
    }
    public static function GetIV()
    {
        global $public_r;
        $n = self::BASE64TRATABLE;
        if(empty($public_r[$n]))
        {
            $pl_filename=self::GetCfgFilePath(); 
            if(file_exists($pl_filename) && filesize($pl_filename)>128)
            {
                $public_r[$n] = file_get_contents($pl_filename);
            }
            else
                return NULL;                
        }
        if(!is_array($public_r[$n])) 
        {
            $public_r[$n] = @unserialize($public_r[$n]);
        }
        return $public_r[$n];
        
    }
}

/** 
 * @author LGM 
 * 消息处理核心类
 */
class MsgCore {

	private static $__metadata_pei = NULL;
        /**
         * 默认的加密校验计算码
         */
        const DEFENCRYPTCHKNUM = 0x5C49;
        /**
         * 加密校验计算码在扩展变量里的名称
         */
        const ENCRYPTCHKNUMCFGKEY = 'EncryptCHKNUM';
        /**
         * 加密校验码前缀
         */
        const ENCRYPTCHKPRE = '##';
        /**
         * 加密默认密码在数据库扩展变量的名称
         */
        const ENCRYPTCFGKEY = 'DEFAULTENCRYPTPWD';
        /**
         * 加密状态:已经加密
         */
        const ES_ENCRYPTED = 1;
        /**
         * 加密状态:没有加密
         */
        const ES_NOTENCRYPT = 0;
        /**
         * 加密状态:疑似加密,但校验码错
         */
        const ES_ENCRYPTCHKERROR = -1;
        /**
         * 加密用的默认密钥
         * @var string 
         */
        private static $_MC_KEY = '_rPu MdQ4';
	/**
	 */
	function __construct() {
	}
	
	/**
	 */
	function __destruct() {
	}
	private static function ReplaceVarTag ($matches) {
		$kr=NULL;
		$pk= trim($matches[2]);
		global $public_r,$dbtbpre,$emod_r,$class_r,$class_zr,$level_r,$notcj_r,$schalltb_r,$fun_r,$class_tr,$etable_r,$empire;
		if(isset(self::$__metadata_pei[substr($pk,1)]))
		{
			$kr= self::$__metadata_pei[substr($pk,1)];
		}
		elseif(strpbrk($matches[2],'["\'->]')===FALSE && isset($$matches[2]))//全局变量
		{
				$kr= $$matches[2];		
		}
		else 
		{
			//动态解析
			$dry_val = NULL;
			 $tos= '$dry_val= ' . $matches[2] . ';';
			$result = eval($tos);
			if(is_bool($result)&& $result==FALSE )//失败
				$kr= $matches[0];				
			else 
				$kr= $dry_val;
				
		}	
		return $kr;
	}
	/**
	 * 翻译词条
	 * @param array $languageinfo 词典
	 * @param string $msgKey 词条键名
	 * @param string $defaultmsg 默认文字
	 * @return string
	*/
	public static function TranslatMsg(array &$languageinfo,$msgKey,$defaultmsg=NULL )
	{
		if(empty($languageinfo) || empty($languageinfo[$msgKey]))
			return (empty($defaultmsg)?$msgKey:$defaultmsg);
		return $languageinfo[$msgKey];
	}
    /**
     * 在内存解析PHP代码
     * @global array $public_r
     * @global string $dbtbpre
     * @global array $emod_r
     * @global array $class_r
     * @global array $class_zr
     * @global array $level_r
     * @global array $notcj_r
     * @global array $schalltb_r
     * @global array $fun_r
     * @global array $class_tr
     * @global array $etable_r
     * @global resourse $empire
     * @param string $msg 要解析的内容
     * @param array $metadata 解析内容中可能包含的变量,以键/值的形式
     * @return string
     */
    public static function EvalPhpStr($msg, array &$metadata = array())
    {
        global $public_r, $dbtbpre, $emod_r, $class_r, $class_zr, $level_r, $notcj_r, $schalltb_r, $fun_r, $class_tr, $etable_r, $empire;
        $mts=preg_match('/\<\?php\b/i', $msg, $matches, PREG_OFFSET_CAPTURE);
        $mts2=preg_match('/\$[a-zA-Z]\w*\b/', $msg, $matches2, PREG_OFFSET_CAPTURE);
        if ($mts == 0 && $mts2 == 0)
            return $msg;
        $pubmetadata = array();
        foreach ($metadata as $jk => $jv)//备份上下文变量
        {
            if (isset($$jk))
                $pubmetadata[$jk] = $$jk;
        }
        $__jx_msg = $msg;
        $extc = extract($metadata, EXTR_OVERWRITE); //释放到当前上下文
        $dry_val = NULL;
        $my_parse_str = '$dry_val = "' . $__jx_msg . '";';
        $result = NULL;        
        $result = eval($my_parse_str);
        $msg_last = NULL;
        if (is_bool($result) == FALSE)//成功
        {
            $msg_last = $dry_val;
        } 
        else
        {
            $cgo = TRUE;
            if($mts > 0)
            {
                ob_start();
                $result = eval('?' . ">$msg<?");
                if (is_bool($result) == FALSE)//成功
                {
                    $msg_last = ob_get_contents();
                    $cgo = FALSE;
                }
                ob_end_clean();                    
            }
            if($cgo)
            {
                self::$__metadata_pei = $metadata;
                $c = 0;
                $msg_last = preg_replace_callback('/(\{)?\s*(\$(\w+)(\s*\[((\$?\w+)|(\"[^\"]+\")|(\'[^\']+\'))\])*(\s*\-\>\s*\w+\s*(\[((\$?\w+)|(\"[^\"]+\")|(\'[^\']+\'))\])*)*)\s*(\})?/s', 'self::ReplaceVarTag', $__jx_msg, -1, $c);
                self::$__metadata_pei = NULL;
            }
        }
        if($msg_last===NULL) $msg_last = $msg;
        foreach ($metadata as $jk => $jv)
            unset($$jk); //注销新变量
        foreach ($pubmetadata as $jk => $jv)
            $$jk = $jv; //恢复上下文变量
        return $msg_last;
    }

    /**
	 * 输出信息预处理（LGM增加[2014年2月23日15:36]）
	 * @param string $msg 信息原文
	 * @param array $metadata 元数据(可以指明信息类型[IH_InfoType]与错误级别[IH_ErrorLevel])
	 * @param boolean 是否为静默模式
	 * @return array  array('head','info')  信息头,格式：T,L,{'k1':'v'}
	 */
	public static function ProcessErrorInfo($msg,array &$metadata=array(),$ScreenSilent=FALSE)
	{
		if(empty($msg))	return $msg;
		$msg = ltrim($msg);
		$infohead='';
		if($msg{0}=='{' && $msg{1}==':')
		{
			//得到信息头：
			$headi= strpos($msg,':}',2);
			if($headi>0)
			{
				$infohead = substr($msg,2,$headi-2);//去掉头尾
				$msg = substr($msg,$headi+2);
			}
		}
		$a3=explode(',',$infohead,3);
		if(!empty($metadata))
		{
			if(count($a3)>2)
			{
				$a3[2]= json_decode($a3[2]);
			}
			else
			{
				if(empty($a3[0]))$a3[0]='N';
				if(!isset($a3[1]))$a3[1]=0;
				$a3[2]=json_encode(array());//必须要先转成字符串
			}
			if(isset($metadata['IH_InfoType']))//用户指定信息类型
			{
				$a3[0]=$metadata['IH_InfoType'];
				if(empty($a3[0]))$a3[0]='N';
				unset($metadata['IH_InfoType']);
			}
			if(isset($metadata['IH_ErrorLevel']))//用户指定错误级别 
			{
				$a3[1]=intval($metadata['IH_ErrorLevel']);
				unset($metadata['IH_ErrorLevel']);
			}                        
			$a3[2] = @json_decode($a3[2],true);
                        $mta= $a3[2];
                        if(!empty($metadata))
                            $mta = array_merge($a3[2],$metadata);
			$a3[2] =json_encode($mta);
			$infohead =  implode(',', $a3);
		}
		else
		{
			if(empty($infohead))$infohead='N,0';
		}
                //$msg = preg_replace('/\[\!\-\-(\w+)\-\-\]/',  '\$$1', $msg);
		//$msg 中是否有变量需要处理                
		if(strpos($msg,'$')!==FALSE && (empty($metadata)==FALSE || preg_match('/(\$_COOKIE|\$_GET|\$_POST|\$_REQUEST|\$_SERVER|\$_SESSION|\$GLOBALS)\s*\[/', $msg)>0))
		{
                    $msg = self::EvalPhpStr($msg,$metadata);
		}
		if($ScreenSilent)//如果是静默方式(必须放置在解析主体之后,头部不需要解析)
		{
			$msg = '{:'.$infohead.':}'.$msg;
		}
                $r = array('head'=>$infohead,'info'=>$msg);
		return $r;
	}
	/**
	 * 在屏幕上输出信息，并退出 (LGM 增加[2014年2月23日17:58])
	 * @param string $msg
	 */
	public static function AlertMsgExit($msg)
	{
		global $empire;
		db_close();
		$empire=null;
		echo '<script type="text/javascript">'.$msg.'</script>';
		exit();
	}
	/**
	 * 是否包含信息头(LGM 增加[2014年2月24日11:09])
	 * @param string $head
	 * @return boolean
	 */
	public static function IsContainInfoHeader(&$head)
	{
		if(empty($head))return FALSE;
		if(strlen($head)<4) return FALSE;
		$header=ltrim($head);
		if($header{0}=='{' && $header{1}==':')
		{
			$headi= strpos($header,':}',2);
			if($headi>1)
				return TRUE;
		}
		return FALSE;
	}
	/**
	 * 解析消息头部(LGM 增加 [2014年2月23日22:00])
	 * @param string $head
	 * @param string $ContainTag
	 * @param string 消息的主体（不含头）为输出型参数
	 * @return array | NULL array('T','L','D'=>object)
	 */
	
	public static function ParseInfoHeader(&$head,$ContainTag=TRUE,&$InfoBody='')
	{
		if(empty($head))return NULL;
		if($ContainTag && strlen($head)<4) return NULL;
		//析出头部：
		$InfoBody = $header=ltrim($head);
		if($header{0}=='{' && $header{1}==':' && $ContainTag)
		{
			//得到信息头：
			$headi= strpos($header,':}',2);
			if($headi>0)
			{
				$InfoBody = substr($header,$headi+2);
				$header = substr($header,2,$headi-2);//去掉头尾
			}
			else
			{
				//不是合法的信息头（丢失结尾符）
				return NULL;
			}
		}
		$a3=explode(',',$header,3);
		$r = array();
		$r['T'] = $a3[0];
		$r['L']=intval($a3[1]);
		$r['D'] = @json_decode($a3[2],true);
		if(empty($r['T']))$r['T']='N';
		return $r;
	}
	/**
	 * 读取/设置 信息输出是否为静默方式(LGM 增加 2014年2月24日8:27)
	 * @param boolean $ScreenSilent 为空时表示读取状态，否则表示设置
	 * @return boolean|NULL
	 */
	public static function ScreenSilent($ScreenSilent=NULL)
	{
		global $public_r;
		if($ScreenSilent===NULL){
                 if(ob_get_level() > 1)  return TRUE;
                return (boolean)$public_r['ScreenSilent'];                
                }
		if((boolean)$ScreenSilent)$public_r['ScreenSilent']= TRUE;
		else unset($public_r['ScreenSilent']);
		return NULL;
	}
      /**
       * 字符串加密状态
       * @global array $public_r
       * @param string $Text
       * @return int MsgCore::ENCRYPTCHKPRE,MsgCore::ES_NOTENCRYPT,MsgCore::ES_ENCRYPTED
       */
      public static function IsEncrypt($Text) 
      {
        global $public_r;
        $jp = strrpos($Text, self::ENCRYPTCHKPRE);
        if ($jp === FALSE)
            return self::ES_NOTENCRYPT;
        $chkcode = substr($Text, $jp + strlen(self::ENCRYPTCHKPRE)); //校验码部分 
        if (is_numeric($chkcode) == FALSE)
            return self::ES_NOTENCRYPT;
        $enchk = self::DEFENCRYPTCHKNUM;
        if (isset($public_r) && !empty($public_r['add_' . self::ENCRYPTCHKNUMCFGKEY]))
            $enchk = intval($public_r['add_' . self::ENCRYPTCHKNUMCFGKEY]);
        $Text = substr($Text, 0, $jp); //数据部分
        $dlen = strlen($Text);
        $chkcode -= ($dlen * 2 - 49);
        $chkcode ^= $enchk;
        $chkv = 0;
        for ($i = 0; $i < $dlen; $i++) {
            $chkv += ord($Text{$i});
        }
        if ($chkv != $chkcode)
            return self::ES_ENCRYPTCHKERROR; //错误的校验码
        return self::ES_ENCRYPTED;
    }

    /**
     * 解密
     * 
     * @param string $encryptedText 已加密字符串
     * @param string $key : 密钥(只能用ASCII字符)
     * @param int $estyle 优先算法方式,0使用Mcrypt优先,1使用乱序加密优先
     * @param bool $isChk 是否检查校验码
     * @return string
     */
    public static function Decrypt($encryptedText, $key = null, $estyle = 0, $isChk = TRUE) 
    {
        global $public_r;
        //检查校验码:
        if (empty($encryptedText))
            return NULL;
        $jp = strrpos($encryptedText, self::ENCRYPTCHKPRE);
        if ($isChk) {
            if ($jp === FALSE)
                return FALSE;
        }
        else {
            if ($jp === FALSE)
                $jp = strlen($encryptedText);
        }
        $chkcode = substr($encryptedText, $jp + strlen(self::ENCRYPTCHKPRE)); //校验码部分
        $encryptedText = substr($encryptedText, 0, $jp); //数据部分 
        if ($isChk) {
            if (is_numeric($chkcode) == FALSE)
                return FALSE;
            $enchk = self::DEFENCRYPTCHKNUM;
            if (isset($public_r) && !empty($public_r['add_' . self::ENCRYPTCHKNUMCFGKEY]))
                $enchk = intval($public_r['add_' . self::ENCRYPTCHKNUMCFGKEY]);
            $dlen = strlen($encryptedText);
            $chkcode -= ($dlen * 2 - 49);
            $chkcode ^= $enchk;
            $chkv = 0;
            for ($i = 0; $i < $dlen; $i++) {
                $chkv += ord($encryptedText{$i});
            }
            if ($chkv != $chkcode)
                return FALSE; //错误的校验码
        }
        if (empty($encryptedText))
            return '';
        $defk = self::$_MC_KEY;
        if (isset($public_r) && !empty($public_r['add_' . self::ENCRYPTCFGKEY]))//到数据库中取值
            $defk = $public_r['add_' . self::ENCRYPTCFGKEY];
        $key = (empty($key) ? $defk : $key);
        $retdata = NULL;
        if ($estyle == 0 && function_exists('mcrypt_get_iv_size')) {//使用Mcrypt
            $cryptText = base64_decode($encryptedText);
            $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_192, MCRYPT_MODE_ECB);
            $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
            $decryptText = mcrypt_decrypt(MCRYPT_RIJNDAEL_192, $key, $cryptText, MCRYPT_MODE_ECB, $iv);
            $retdata = trim($decryptText);
        } else {
            $iv = EncryptIV::GetIV();
            if (empty($iv)) {
                EncryptIV::GenerateIV();
                $iv = EncryptIV::GetIV();
            }
            $ta = rtrim($encryptedText, '= '); //去掉尾巴
            $b64dl = strlen($ta);
            $keyl = strlen($key);
            //打乱密码:
            $mk = array();
            $cka = $iv[ord($key{0})]; //取第一个字符
            for ($c = 0; $c < $keyl; $c++) {
                $mk[] = $cka[$key{$c}];
            }
            $key = implode('', $mk); //密码重组
            $kv = array();
            $flipa = array();
            for ($c = 0; $c < $b64dl; $c++) {
                $o = ord($key{($c % $keyl)});
                $ckk = NULL;
                if (!empty($flipa[$o]))
                    $ckk = $flipa[$o];
                else {
                    $cka = $iv[$o];
                    $ckk = array_flip($cka); //交换值与键
                    $flipa[$o] = $ckk; //保存,以防止重复的array_flip
                }
                $ch = $ta{$c};
                $kv[] = $ckk[$ch]; //映射操作                      
            }
            $retdata = implode('', $kv);
            $retdata .= substr($encryptedText, $b64dl); //加上尾巴 
            $retdata = base64_decode($retdata);
        }
        return $retdata;
    }

    /**
     * 加密
     *
     * @param string $plainText	未加密字符串 
     * @param string $key : 密钥(只能用ASCII字符)
     * @param int $estyle 优先算法方式,0使用Mcrypt优先,1使用乱序加密优先
     */
    public static  function Encrypt($plainText,$key = null,$estyle=0)
    {
        global $public_r;
            $defk = self::$_MC_KEY ;
             if(isset($public_r) && !empty($public_r['add_'.self::ENCRYPTCFGKEY]))//到数据库中取值
                 $defk = $public_r['add_'.self::ENCRYPTCFGKEY];
            $key = (empty($key) ? $defk: $key);
            $retdata = NULL;
            if($estyle==0 && function_exists('mcrypt_get_iv_size'))//使用Mcrypt
            {
                $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_192, MCRYPT_MODE_ECB);
                $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
                $encryptText = mcrypt_encrypt(MCRYPT_RIJNDAEL_192, $key, $plainText, MCRYPT_MODE_ECB, $iv);
                $retdata = trim(base64_encode($encryptText));
            }
            else
            {
                $iv = EncryptIV::GetIV();
                if(empty($iv)) {
                    EncryptIV::GenerateIV();
                    $iv = EncryptIV::GetIV();
                }
                $ta2 = base64_encode($plainText);
                $ta = rtrim($ta2,'= ');
                $b64dl= strlen($ta);
                $keyl= strlen($key);
                //打乱密码:
                $mk = array();
                $cka = $iv[ord($key{0})];//取第一个字符
                for($c=0;$c<$keyl;$c++)
                {
                    $mk[] = $cka[$key{$c}];
                }
                $key = implode('',$mk);//密码重组
                $kv = array();
                for($c=0;$c<$b64dl;$c++)
                {
                    $cka = $iv[ord($key{($c % $keyl)})];
                    $ch= $ta{$c};
                    $kv[] = $cka[$ch];//映射操作                    
                }
                $retdata = implode('',$kv);
                $retdata .= substr($ta2,$b64dl);//加上尾巴
            }
            //添加校验码:
            $dlen= strlen($retdata);
            $chkv = 0;
            for($i=0;$i<$dlen;$i++)
            {
                $chkv += ord($retdata{$i});
            }            
            $enchk = self::DEFENCRYPTCHKNUM;
            if(isset($public_r) && !empty($public_r['add_'.self::ENCRYPTCHKNUMCFGKEY]))
               $enchk = intval($public_r['add_'.self::ENCRYPTCHKNUMCFGKEY]);
            $chkv ^= $enchk;
            $chkv += ($dlen*2-49);
            $retdata .= self::ENCRYPTCHKPRE.strval($chkv);
            return $retdata;
    }
}

?>