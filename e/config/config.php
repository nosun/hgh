﻿<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
define('EmpireCMSConfig',TRUE);
$ecms_config=array();
error_reporting(0);

//数据库设置
$ecms_config['db']['usedb']='mysql';	//数据库类型
$ecms_config['db']['dbver']='5.0';	//数据库版本
$ecms_config['db']['dbserver']='rm-2zennj8bw887q3n9f.mysql.rds.aliyuncs.com'; 
//$ecms_config['db']['dbserver']='localhost';	//数据库登录地址
$ecms_config['db']['dbport']='';	//端口，不填为按默认
$ecms_config['db']['dbusername']='szhghcms';	//数据库用户名
$ecms_config['db']['dbpassword']='Wrmfw3309';	//数据库密码
$ecms_config['db']['dbname']='hgh';	//数据库名
$ecms_config['db']['setchar']='utf8';	//设置默认编码
$ecms_config['db']['dbchar']='utf8';	//数据库默认编码
$ecms_config['db']['dbtbpre']='hgh_';	//数据表前缀
$dbtbpre=$ecms_config['db']['dbtbpre'];	//数据表前缀
$ecms_config['db']['showerror']=1;	//显示SQL错误提示(0为不显示,1为显示)


//页面编码设置
$ecms_config['sets']['pagechar']='utf-8';	//安装帝国CMS的编码版本
$ecms_config['sets']['setpagechar']=1;	//页面默认字符集,0=关闭 1=开启
$ecms_config['sets']['elang']='gb';	//语言包

//后台相关配置
$ecms_config['esafe']['openonlinesetting']=3;	//开启后台在线配置参数(0为关闭,1为开启防火墙配置,2为开启安全配置,3为全开启)
$ecms_config['esafe']['openeditdttemp']=1;	//开启后台在线修改动态模板(0为关闭,1为开启)

//易通行系统配置
$ecms_config['epassport']['open']=0;	//是否开启易通行系统(1为开启，0为关闭)

//其它配置
$ecms_config['sets']['txtpath']=ECMS_PATH.'d/txt/';	//文本型数据存放目录
$ecms_config['sets']['saveurlimgclearurl']=0;	//远程保存图片自动去除图片的链接(0为保留,1为去除)
$ecms_config['sets']['deftempid']=0;	//默认模板组ID



//-------EmpireCMS.Seting.member-------

//会员系统相关配置
$ecms_config['member']['tablename']="{$dbtbpre}enewsmember";	//会员表
$user_tablename=$ecms_config['member']['tablename'];	//会员表
$ecms_config['member']['changeregisterurl']="";    //多会员组中转注册地址
$ecms_config['member']['registerurl']="";							//会员注册地址
$ecms_config['member']['loginurl']="";								//会员登录地址
$ecms_config['member']['quiturl']="";								//会员退出地址
$ecms_config['member']['chmember']=0;//是否使用原版会员表信息,0为原版,1为非原版
$ecms_config['member']['pwtype']=2;//密码保存形式,0为md5,1为明码,2为双重加密,3为16位md5
$ecms_config['member']['regtimetype']=1;//注册时间保存格式,0为正常时间,1为数值型
$ecms_config['member']['regcookietime']=0;//注册后登录保存时间(秒)
$ecms_config['member']['defgroupid']=0;//注册时会员组ID(ecms的会员组,0为后台默认)
$ecms_config['member']['saltnum']=6;//SALT随机码字符数
$ecms_config['member']['utfdata']=0;//数据是否是GBK编码,0为正常数据,1为GBK编码

$ecms_config['memberf']['userid']='userid';//用户ID字段
$ecms_config['memberf']['username']='username';//用户名字段
$ecms_config['memberf']['password']='password';//密码字段
$ecms_config['memberf']['rnd']='rnd';//随机密码字段
$ecms_config['memberf']['email']='email';//邮箱字段
$ecms_config['memberf']['registertime']='registertime';//注册时间字段
$ecms_config['memberf']['groupid']='groupid';//会员组字段
$ecms_config['memberf']['userfen']='userfen';//积分字段
$ecms_config['memberf']['userdate']='userdate';//有效期字段
$ecms_config['memberf']['money']='money';//帐户余额字段
$ecms_config['memberf']['zgroupid']='zgroupid';//到期转向会员组字段
$ecms_config['memberf']['havemsg']='havemsg';//提示短消息字段
$ecms_config['memberf']['checked']='checked';//审核状态字段
$ecms_config['memberf']['salt']='salt';//SALT加密字段
$ecms_config['memberf']['userkey']='userkey';//用户密钥字段

//-------EmpireCMS.Seting.member-------




//-------EmpireCMS.Seting.area-------

//后台安全设置
$ecms_config['esafe']['loginauth']='taozi';	//登录认证码,如果设置登录需要输入此认证码才能通过
$ecms_config['esafe']['ecookiernd']='VqVZzCnMCuxcjPFGL4ge95qcebgkRZ';	//后台登录COOKIE认证码(填写10~50个任意字符，最好多种字符组合)
$ecms_config['esafe']['ckhloginfile']=0;	//后台是否启用文件验证,0为验证,1为不验证
$ecms_config['esafe']['ckhloginip']=0;	//后台是否验证登录IP,0为不验证,1为验证
$ecms_config['esafe']['ckhsession']=0;	//后台是否启用SESSION验证,0为不验证,1为验证
$ecms_config['esafe']['theloginlog']=0;	//是否记录登陆日志(0为记录,1为不记录)
$ecms_config['esafe']['thedolog']=0;		//是否记录操作日志(0为记录,1为不记录)
$ecms_config['esafe']['ckfromurl']=2;	//是否启用来源地址验证,0为不验证,1为全部验证,2为后台验证,3为前台验证

//COOKIE设置
$ecms_config['cks']['ckdomain']='.szhgh.com';		//cookie作用域
$ecms_config['cks']['ckpath']='/';		//cookie作用路径
$ecms_config['cks']['ckvarpre']='nxags';		//前台cookie变量前缀
$ecms_config['cks']['ckadminvarpre']='qxqmh';		//后台cookie变量前缀
$ecms_config['cks']['ckrnd']='vXHMX6xPLUrN6HA5dSwERYH4RDxSms';	//COOKIE验证随机码(填写10~50个任意字符，最好多种字符组合)
$ecms_config['cks']['ckrndtwo']='P66XFf7aByufTNQqKykhWDAwWjY6xP';	//COOKIE验证随机码2(填写10~50个任意字符，最好多种字符组合)

//网站防火墙配置
$ecms_config['fw']['eopen']=1;	//开启防火墙(0为关闭,1为开启)
$ecms_config['fw']['epass']='EXcqMSjdmxwjv57Dgv8beufGa6JNA6xcwUiT';	//防火墙加密密钥(填写10~50个任意字符，最好多种字符组合)
$ecms_config['fw']['adminloginurl']='';	//允许后台登陆的域名,设置后必须通过这个域名才能访问后台
$ecms_config['fw']['adminhour']='';	//允许登陆后台的时间：0~23小时，多个时间点用半角逗号格开
$ecms_config['fw']['adminweek']='';	//允许登陆后台的星期：星期0~6，多个星期用半角逗号格开
$ecms_config['fw']['adminckpassvar']='szhghccc';	//后台预登陆验证变量名
$ecms_config['fw']['adminckpassval']='nh6SwCdhJruPYMFYXcJjmtdk7irXac7BZ4Yq';	//后台预登陆认证码
$ecms_config['fw']['cleargettext']='';	//屏蔽提交敏感字符，多个用半角逗号格开

//-------EmpireCMS.Seting.area-------


//文件类型
$ecms_config['sets']['tranpicturetype']=',.jpg,.gif,.png,.bmp,.jpeg,';	//图片
$ecms_config['sets']['tranflashtype']=',.swf,.flv,.dcr,';	//FLASH
$ecms_config['sets']['mediaplayertype']=',.wmv,.asf,.wma,.mp3,.asx,.mid,.midi,';	//mediaplayer
$ecms_config['sets']['realplayertype']=',.rm,.ra,.rmvb,.mp4,.mov,.avi,.wav,.ram,.mpg,.mpeg,';	//realplayer




//***************** 以下部分为缓存，不用设置 **************

//-------EmpireCMS.Public.Cache-------

//------------e_public
$public_r=array('sitename'=>'红歌会网',
'newsurl'=>'http://www.szhgh.com/',
'filetype'=>'|.png|.gif|.jpg|.swf|.jpeg|.rar|.zip|.mp3|.wmv|.txt|.doc|',
'filesize'=>2048,
'relistnum'=>8,
'renewsnum'=>100,
'min_keyboard'=>3,
'max_keyboard'=>105,
'search_num'=>20,
'search_pagenum'=>16,
'newslink'=>0,
'checked'=>0,
'searchtime'=>0,
'loginnum'=>10,
'logintime'=>600,
'addnews_ok'=>0,
'register_ok'=>0,
'indextype'=>'.html',
'goodlencord'=>0,
'goodtype'=>'',
'searchtype'=>'.html',
'exittime'=>360,
'smalltextlen'=>200,
'defaultgroupid'=>1,
'fileurl'=>'http://img3.wyzxwk.com/',
'install'=>0,
'phpmode'=>0,
'dorepnum'=>300,
'loadtempnum'=>50,
'bakdbpath'=>'bdata',
'bakdbzip'=>'zip',
'downpass'=>'c7LxBBbUMm7D4Z48JWbj',
'filechmod'=>0,
'loginkey_ok'=>0,
'tbname'=>'article',
'limittype'=>0,
'redodown'=>1,
'downsofttemp'=>'[ <a href=\"#ecms\" onclick=\"window.open(\'[!--down.url--]\',\'\',\'width=300,height=300,resizable=yes\');\">[!--down.name--]</a> ]',
'onlinemovietemp'=>'[ <a href=\"#ecms\" onclick=\"window.open(\'[!--down.url--]\',\'\',\'width=300,height=300,resizable=yes\');\">[!--down.name--]</a> ]',
'lctime'=>1222406370,
'candocode'=>1,
'opennotcj'=>0,
'listpagetemp'=>'页次：[!--thispage--]/[!--pagenum--]&nbsp;每页[!--lencord--]&nbsp;总数[!--num--]&nbsp;&nbsp;&nbsp;&nbsp;[!--pagelink--]&nbsp;&nbsp;&nbsp;&nbsp;转到:[!--options--]',
'reuserpagenum'=>50,
'revotejsnum'=>100,
'readjsnum'=>100,
'qaddtran'=>1,
'qaddtransize'=>100,
'ebakthisdb'=>1,
'delnewsnum'=>300,
'markpos'=>9,
'markimg'=>'/e/data/mark/maskdef.gif',
'marktext'=>'',
'markfontsize'=>'5',
'markfontcolor'=>'',
'markfont'=>'/e/data/mark/cour.ttf',
'adminloginkey'=>1,
'php_outtime'=>90,
'listpagefun'=>'sys_ShowListPage',
'textpagefun'=>'sys_ShowTextPage',
'adfile'=>'thea',
'notsaveurl'=>'',
'rssnum'=>50,
'rsssub'=>300,
'savetxtf'=>',',
'dorepdlevelnum'=>300,
'listpagelistfun'=>'sys_ShowListMorePage',
'listpagelistnum'=>10,
'infolinknum'=>100,
'searchgroupid'=>0,
'opencopytext'=>0,
'reuserjsnum'=>100,
'reuserlistnum'=>8,
'opentitleurl'=>1,
'searchtempvar'=>1,
'showinfolevel'=>1,
'navfh'=>'>',
'spicwidth'=>320,
'spicheight'=>240,
'spickill'=>1,
'jpgquality'=>80,
'markpct'=>65,
'redoview'=>24,
'reggetfen'=>0,
'regbooktime'=>100,
'revotetime'=>10,
'fpath'=>1,
'filepath'=>'Y/m',
'nreclass'=>',',
'nreinfo'=>',',
'nrejs'=>',',
'nottobq'=>',',
'defspacestyleid'=>1,
'canposturl'=>'',
'openspace'=>0,
'defadminstyle'=>1,
'realltime'=>0,
'closeip'=>'',
'openip'=>'',
'hopenip'=>'',
'textpagelistnum'=>6,
'memberlistlevel'=>1,
'ebakcanlistdb'=>0,
'keytog'=>2,
'keytime'=>60,
'keyrnd'=>'y7vWPKVAc8GGCmywkdvpG9U98MDUr2',
'checkdorepstr'=>',0,0,0,0,',
'regkey_ok'=>1,
'opengetdown'=>0,
'gbkey_ok'=>0,
'fbkey_ok'=>0,
'newaddinfotime'=>0,
'classnavs'=>'<a href=\"http://www.szhgh.com/Article/\">文章中心</a>&nbsp;|&nbsp;<a href=\"http://www.szhgh.com/netizens/\">网友文集</a>&nbsp;|&nbsp;<a href=\"http://www.szhgh.com/xuezhe/\">学者专栏</a>&nbsp;|&nbsp;<a href=\"http://hao.szhgh.com\">红歌会网址导航</a>&nbsp;|&nbsp;<a href=\"http://www.szhgh.com/special/\">专题中心</a>&nbsp;|&nbsp;<a href=\"http://www.szhgh.com/video/\">视频中心</a>&nbsp;|&nbsp;<a href=\"http://www.szhgh.com/weekly/\">周刊</a>',
'adminstyle'=>',1,2,',
'docnewsnum'=>300,
'openschall'=>1,
'schallfield'=>1,
'schallminlen'=>3,
'schallmaxlen'=>20,
'schallnum'=>20,
'schallpagenum'=>16,
'dtcanbq'=>1,
'dtcachetime'=>43200,
'repkeynum'=>0,
'regacttype'=>0,
'opengetpass'=>1,
'hlistinfonum'=>50,
'qlistinfonum'=>25,
'dtncanbq'=>1,
'dtncachetime'=>43200,
'readdinfotime'=>0,
'qeditinfotime'=>0,
'onclicktype'=>0,
'onclickfilesize'=>10,
'onclickfiletime'=>60,
'schalltime'=>0,
'defprinttempid'=>1,
'opentags'=>1,
'tagstempid'=>14,
'usetags'=>',8,9,10,11,13,14,',
'chtags'=>',10,14,15,',
'tagslistnum'=>30,
'closeqdt'=>0,
'settop'=>0,
'qlistinfomod'=>0,
'gb_num'=>20,
'member_num'=>20,
'space_num'=>25,
'infolday'=>0,
'filelday'=>0,
'dorepkey'=>0,
'dorepword'=>0,
'onclickrnd'=>'',
'indexpagedt'=>0,
'keybgcolor'=>'',
'keyfontcolor'=>'',
'keydistcolor'=>'',
'indexpageid'=>1,
'closeqdtmsg'=>'',
'openfileserver'=>1,
'fs_purl'=>'http://img3.wyzxwk.com/',
'closemods'=>',down,movie,shop,pay,sch,',
'fieldandtop'=>0,
'fieldandclosetb'=>'',
'filedatatbs'=>',1,',
'filedeftb'=>1,
'pldeftb'=>1,
'plurl'=>'/e/pl/',
'plkey_ok'=>0,
'plface'=>'||[~e.jy~]##1.gif||[~e.kq~]##2.gif||[~e.se~]##3.gif||[~e.sq~]##4.gif||[~e.lh~]##5.gif||[~e.ka~]##6.gif||[~e.hh~]##7.gif||[~e.ys~]##8.gif||[~e.ng~]##9.gif||[~e.ot~]##10.gif||',
'plf'=>'',
'pldatatbs'=>',1,',
'defpltempid'=>1,
'pl_num'=>20,
'plgroupid'=>1,
'closelisttemp'=>'',
'chclasscolor'=>'#99C4E3',
'timeclose'=>'',
'timeclosedo'=>'',
'ipaddinfonum'=>10,
'ipaddinfotime'=>12,
'rewriteinfo'=>'',
'rewriteclass'=>'',
'rewriteinfotype'=>'',
'rewritetags'=>'',
'memberconnectnum'=>2,
'closehmenu'=>',shop,',
'deftempid'=>0,'add_comments_default_admin_pre'=>'<font color=\"red\"><b>[Admin]</b></font>','add_comments_default_admin_avatar'=>'e/trylife/common/images/admin_default_avatar.jpg','add_comments_default_tempid'=>'1','add_comments_default_avatar'=>'e/trylife/common/images/default_user_avatar.png','add_comments_default_cachemethod'=>'0','add_comments_default_uncontent'=>'<del>你的评论已经被屏蔽</del>','add_comments_max_face'=>'8','add_comments_max_char'=>'2000','add_EITypes'=>'a:2:{s:12:\"新浪微博\";a:10:{s:2:\"id\";s:1:\"1\";s:4:\"type\";s:4:\"sina\";s:4:\"name\";s:12:\"新浪微博\";s:6:\"appkey\";s:10:\"2200504722\";s:9:\"appsecret\";s:32:\"42675c5b0b121b056769607b6338cdf8\";s:11:\"callbackurl\";s:33:\"e/memberconnect/sina/loginend.php\";s:12:\"callbackurl2\";s:36:\"e/memberconnect/sina/revokeoauth.php\";s:4:\"info\";a:0:{}s:13:\"tranregexlist\";s:86:\"\'/\\&nbsp;/\'=>\' \',\'|\\<br\\s*/?\\>|i\'=>\' \',\'/\\[~e\\.([\\w\\x{4e00}-\\x{9fa5}]+?)~\\]/u\'=>\'[\\1]\'\";s:16:\"trankeywordslist\";s:0:\"\";}s:8:\"QQ互联\";a:10:{s:2:\"id\";s:1:\"2\";s:4:\"type\";s:2:\"qq\";s:4:\"name\";s:8:\"QQ互联\";s:6:\"appkey\";s:9:\"101062866\";s:9:\"appsecret\";s:32:\"c7fe0672809024f489f5c0a951fb3269\";s:11:\"callbackurl\";s:31:\"e/memberconnect/qq/loginend.php\";s:12:\"callbackurl2\";s:34:\"e/memberconnect/qq/revokeoauth.php\";s:4:\"info\";a:0:{}s:13:\"tranregexlist\";s:86:\"\'/\\&nbsp;/\'=>\' \',\'|\\<br\\s*/?\\>|i\'=>\' \',\'/\\[~e\\.([\\w\\x{4e00}-\\x{9fa5}]+?)~\\]/u\'=>\'[\\1]\'\";s:16:\"trankeywordslist\";s:0:\"\";}}','add_DEFAULTENCRYPTPWD'=>'e%','add_EncryptCHKNUM'=>'3744176938','add_EIQuickReg'=>'1');
//------------e_public

//-------EmpireCMS.Public.Cache-------

$emod_pubr=Array('linkfields'=>'|photo.copyfrom,copyfrom.title|video.copyfrom,copyfrom.title|article.copyfrom,copyfrom.title|article.author,author.title|topic.tagid,enewstags.tagid|topic.author,author.title|topic.copyfrom,copyfrom.title|');

$etable_r=array();
$etable_r['news']=Array('deftb'=>'1',
'yhid'=>0,
'intb'=>0,
'mid'=>1);
$etable_r['download']=Array('deftb'=>'1',
'yhid'=>0,
'intb'=>0,
'mid'=>2);
$etable_r['photo']=Array('deftb'=>'1',
'yhid'=>0,
'intb'=>0,
'mid'=>14);
$etable_r['video']=Array('deftb'=>'1',
'yhid'=>0,
'intb'=>0,
'mid'=>15);
$etable_r['shop']=Array('deftb'=>'1',
'yhid'=>0,
'intb'=>0,
'mid'=>6);
$etable_r['article']=Array('deftb'=>'1',
'yhid'=>0,
'intb'=>0,
'mid'=>10);
$etable_r['info']=Array('deftb'=>'1',
'yhid'=>0,
'intb'=>0,
'mid'=>8);
$etable_r['author']=Array('deftb'=>'1',
'yhid'=>0,
'intb'=>0,
'mid'=>9);
$etable_r['copyfrom']=Array('deftb'=>'1',
'yhid'=>0,
'intb'=>0,
'mid'=>11);
$etable_r['topic']=Array('deftb'=>'1',
'yhid'=>0,
'intb'=>0,
'mid'=>12);
$etable_r['special']=Array('deftb'=>'1',
'yhid'=>0,
'intb'=>0,
'mid'=>13);


$emod_r=array();
$emod_r[1]=Array('mid'=>1,
'mname'=>'新闻系统模型',
'qmname'=>'新闻',
'defaulttb'=>0,
'datatbs'=>',1,',
'deftb'=>'1',
'enter'=>',title,ftitle,special.field,newstime,titlepic,smalltext,writer,befrom,newstext,',
'qenter'=>',',
'listtempf'=>',title,ftitle,newstime,titlepic,smalltext,diggtop,',
'tempf'=>',title,ftitle,newstime,titlepic,smalltext,writer,befrom,newstext,diggtop,',
'mustqenterf'=>',title,newstext,',
'listandf'=>'',
'setandf'=>0,
'searchvar'=>',title,smalltext,',
'cj'=>',title,ftitle,newstime,titlepic,smalltext,writer,befrom,newstext,',
'canaddf'=>',title,ftitle,newstime,titlepic,smalltext,writer,befrom,newstext,',
'caneditf'=>',title,ftitle,newstime,titlepic,smalltext,writer,befrom,newstext,',
'tbmainf'=>',title,titlepic,newstime,ftitle,smalltext,diggtop,',
'tbdataf'=>',writer,befrom,newstext,',
'tobrf'=>',smalltext,newstext,',
'dohtmlf'=>',ftitle,smalltext,writer,befrom,newstext,diggtop,',
'checkboxf'=>',',
'savetxtf'=>'',
'editorf'=>',newstext,',
'ubbeditorf'=>',',
'pagef'=>'newstext',
'smalltextf'=>',smalltext,',
'filef'=>',',
'imgf'=>',titlepic,',
'flashf'=>',',
'linkfields'=>'|',
'morevaluef'=>'|',
'onlyf'=>',',
'adddofunf'=>'||',
'editdofunf'=>'||',
'qadddofunf'=>'||',
'qeditdofunf'=>'||',
'definfovoteid'=>0,
'orderf'=>'',
'sonclass'=>'|34|35|36|37|',
'tid'=>1,
'tbname'=>'news');
$emod_r[2]=Array('mid'=>2,
'mname'=>'下载系统模型',
'qmname'=>'软件',
'defaulttb'=>0,
'datatbs'=>',1,',
'deftb'=>'1',
'enter'=>',title,special.field,newstime,titlepic,softwriter,homepage,demo,softfj,language,softtype,softsq,star,filetype,filesize,downpath,softsay,',
'qenter'=>',softsay,',
'listtempf'=>',title,newstime,titlepic,softfj,language,softtype,softsq,star,filetype,filesize,softsay,',
'tempf'=>',title,newstime,titlepic,softwriter,homepage,demo,softfj,language,softtype,softsq,star,filetype,filesize,downpath,softsay,',
'mustqenterf'=>',title,downpath,softsay,',
'listandf'=>'',
'setandf'=>0,
'searchvar'=>',title,softsay,',
'cj'=>',title,newstime,titlepic,softwriter,homepage,demo,softfj,language,softtype,softsq,star,filetype,filesize,downpath,softsay,',
'canaddf'=>',title,newstime,titlepic,softwriter,homepage,demo,softfj,language,softtype,softsq,star,filetype,filesize,downpath,softsay,',
'caneditf'=>',title,newstime,titlepic,softwriter,homepage,demo,softfj,language,softtype,softsq,star,filetype,filesize,downpath,softsay,',
'tbmainf'=>',title,titlepic,newstime,softfj,language,softtype,softsq,star,filetype,filesize,softsay,',
'tbdataf'=>',softwriter,homepage,demo,downpath,',
'tobrf'=>',softsay,',
'dohtmlf'=>',softwriter,homepage,demo,softfj,language,softtype,softsq,star,filetype,filesize,downpath,softsay,',
'checkboxf'=>',',
'savetxtf'=>'',
'editorf'=>',',
'ubbeditorf'=>',',
'pagef'=>'',
'smalltextf'=>',softsay,',
'filef'=>',',
'imgf'=>',titlepic,',
'flashf'=>',',
'linkfields'=>'|',
'morevaluef'=>'|',
'onlyf'=>',',
'adddofunf'=>'||',
'editdofunf'=>'||',
'qadddofunf'=>'||',
'qeditdofunf'=>'||',
'definfovoteid'=>0,
'orderf'=>'',
'sonclass'=>'|38|39|40|41|',
'tid'=>2,
'tbname'=>'download');
$emod_r[14]=Array('mid'=>14,
'mname'=>'图片模型',
'qmname'=>'图片',
'defaulttb'=>0,
'datatbs'=>',1,',
'deftb'=>'1',
'enter'=>',title,special.field,newstime,copyfrom,fromlink,titlepic,picurl,sameinfo,smalltext,morepic,',
'qenter'=>',',
'listtempf'=>',title,newstime,titlepic,picurl,sameinfo,smalltext,',
'tempf'=>',title,newstime,copyfrom,fromlink,titlepic,picurl,sameinfo,smalltext,morepic,',
'mustqenterf'=>',title,titlepic,picurl,smalltext,morepic,',
'listandf'=>'',
'setandf'=>0,
'searchvar'=>',title,smalltext,',
'cj'=>',title,newstime,copyfrom,fromlink,titlepic,picurl,smalltext,morepic,',
'canaddf'=>',title,newstime,copyfrom,fromlink,titlepic,picurl,sameinfo,smalltext,morepic,',
'caneditf'=>',title,newstime,copyfrom,fromlink,titlepic,picurl,sameinfo,smalltext,morepic,',
'tbmainf'=>',smalltext,newstime,picurl,titlepic,title,sameinfo,',
'tbdataf'=>',fromlink,copyfrom,morepic,',
'tobrf'=>',smalltext,',
'dohtmlf'=>',smalltext,copyfrom,picurl,morepic,',
'checkboxf'=>',',
'savetxtf'=>'',
'editorf'=>',',
'ubbeditorf'=>',',
'pagef'=>'',
'smalltextf'=>',smalltext,',
'filef'=>',',
'imgf'=>',picurl,titlepic,',
'flashf'=>',',
'linkfields'=>'|copyfrom,copyfrom.title|',
'morevaluef'=>'|',
'onlyf'=>',',
'adddofunf'=>'||',
'editdofunf'=>'||',
'qadddofunf'=>'||',
'qeditdofunf'=>'||',
'definfovoteid'=>0,
'orderf'=>'',
'sonclass'=>'|101|',
'tid'=>14,
'tbname'=>'photo');
$emod_r[15]=Array('mid'=>15,
'mname'=>'视频模型',
'qmname'=>'视频',
'defaulttb'=>0,
'datatbs'=>',1,',
'deftb'=>'1',
'enter'=>',title,special.field,newstime,videourl,titlepic,copyfrom,fromlink,smalltext,videotime,maker,downpath,',
'qenter'=>',',
'listtempf'=>',title,newstime,videourl,titlepic,copyfrom,smalltext,videotime,maker,',
'tempf'=>',title,newstime,videourl,titlepic,copyfrom,fromlink,smalltext,videotime,maker,downpath,',
'mustqenterf'=>',title,newstime,videourl,titlepic,smalltext,',
'listandf'=>'',
'setandf'=>0,
'searchvar'=>',title,copyfrom,smalltext,maker,',
'cj'=>',title,newstime,videourl,titlepic,copyfrom,fromlink,smalltext,videotime,maker,downpath,',
'canaddf'=>',title,newstime,videourl,titlepic,copyfrom,fromlink,smalltext,videotime,maker,downpath,',
'caneditf'=>',title,newstime,videourl,titlepic,copyfrom,fromlink,smalltext,videotime,maker,downpath,',
'tbmainf'=>',diggdown,diggtop,videourl,smalltext,maker,titlepic,newstime,videotime,copyfrom,title,',
'tbdataf'=>',fromlink,downpath,',
'tobrf'=>',smalltext,',
'dohtmlf'=>',fromlink,videourl,smalltext,maker,downpath,videotime,copyfrom,',
'checkboxf'=>',',
'savetxtf'=>'',
'editorf'=>',',
'ubbeditorf'=>',',
'pagef'=>'',
'smalltextf'=>',smalltext,',
'filef'=>',',
'imgf'=>',titlepic,',
'flashf'=>',',
'linkfields'=>'|copyfrom,copyfrom.title|',
'morevaluef'=>'|',
'onlyf'=>',',
'adddofunf'=>'||',
'editdofunf'=>'||',
'qadddofunf'=>'||',
'qeditdofunf'=>'||',
'definfovoteid'=>0,
'orderf'=>'',
'sonclass'=>'',
'tid'=>15,
'tbname'=>'video');
$emod_r[6]=Array('mid'=>6,
'mname'=>'商城系统模型',
'qmname'=>'商品',
'defaulttb'=>0,
'datatbs'=>',1,',
'deftb'=>'1',
'enter'=>',title,special.field,newstime,productno,pbrand,intro,unit,weight,tprice,price,buyfen,pmaxnum,titlepic,productpic,newstext,',
'qenter'=>',',
'listtempf'=>',title,newstime,productno,pbrand,intro,unit,weight,tprice,price,buyfen,pmaxnum,titlepic,productpic,newstext,psalenum,',
'tempf'=>',title,newstime,productno,pbrand,intro,unit,weight,tprice,price,buyfen,pmaxnum,titlepic,productpic,newstext,psalenum,',
'mustqenterf'=>',title,newstext,',
'listandf'=>'',
'setandf'=>0,
'searchvar'=>',title,productno,pbrand,intro,price,newstext,',
'cj'=>',title,newstime,productno,pbrand,intro,unit,weight,tprice,price,buyfen,pmaxnum,titlepic,productpic,newstext,',
'canaddf'=>',title,newstime,productno,pbrand,intro,unit,weight,tprice,price,buyfen,pmaxnum,titlepic,productpic,newstext,',
'caneditf'=>',title,newstime,productno,pbrand,intro,unit,weight,tprice,price,buyfen,pmaxnum,titlepic,productpic,newstext,',
'tbmainf'=>',title,titlepic,newstime,productno,pbrand,intro,unit,weight,tprice,price,buyfen,pmaxnum,productpic,newstext,psalenum,',
'tbdataf'=>',',
'tobrf'=>',intro,newstext,',
'dohtmlf'=>',productno,pbrand,intro,unit,weight,tprice,price,buyfen,pmaxnum,productpic,newstext,psalenum,',
'checkboxf'=>',',
'savetxtf'=>'',
'editorf'=>',newstext,',
'ubbeditorf'=>',',
'pagef'=>'newstext',
'smalltextf'=>',',
'filef'=>',',
'imgf'=>',titlepic,productpic,',
'flashf'=>',',
'linkfields'=>'|',
'morevaluef'=>'|',
'onlyf'=>',',
'adddofunf'=>'||',
'editdofunf'=>'||',
'qadddofunf'=>'||',
'qeditdofunf'=>'||',
'definfovoteid'=>0,
'orderf'=>'',
'sonclass'=>'|46|47|48|49|',
'tid'=>6,
'tbname'=>'shop');
$emod_r[10]=Array('mid'=>10,
'mname'=>'文章中心',
'qmname'=>'文章中心',
'defaulttb'=>1,
'datatbs'=>',1,',
'deftb'=>'1',
'enter'=>',title,wztitle,parameter,series,ftitle,author,authorsft,special.field,newstime,copyfrom,fromlink,titlepic,smalltext,bigpic,sbanner,newstext,editor,userip,lteditor,conclusion,editnotes,',
'qenter'=>',title,wztitle,ftitle,author,copyfrom,fromlink,smalltext,newstext,userip,conclusion,',
'listtempf'=>',title,wztitle,parameter,series,ftitle,author,authorsft,newstime,copyfrom,fromlink,titlepic,smalltext,diggtop,editor,lteditor,',
'tempf'=>',title,wztitle,parameter,series,ftitle,author,authorsft,newstime,copyfrom,fromlink,titlepic,smalltext,bigpic,sbanner,newstext,diggtop,editor,lteditor,conclusion,editnotes,',
'mustqenterf'=>',title,author,copyfrom,newstext,',
'listandf'=>',title,author,authorsft,copyfrom,editor,',
'setandf'=>0,
'searchvar'=>',title,wztitle,author,copyfrom,smalltext,editor,',
'cj'=>',title,ftitle,author,newstime,copyfrom,fromlink,titlepic,smalltext,newstext,editor,conclusion,',
'canaddf'=>',title,wztitle,parameter,series,ftitle,author,authorsft,newstime,copyfrom,fromlink,titlepic,smalltext,bigpic,sbanner,newstext,diggtop,editor,userip,lteditor,conclusion,editnotes,',
'caneditf'=>',title,wztitle,parameter,series,ftitle,author,authorsft,newstime,copyfrom,fromlink,titlepic,smalltext,bigpic,sbanner,newstext,diggtop,editor,userip,lteditor,conclusion,editnotes,',
'tbmainf'=>',sbanner,series,parameter,editor,copyfrom,author,diggtop,smalltext,ftitle,newstime,titlepic,title,userip,authorsft,lteditor,wztitle,infopfen,infopfennum,specialid,diggdown,fromlink,',
'tbdataf'=>',conclusion,bigpic,editnotes,newstext,',
'tobrf'=>',editnotes,newstext,diggtop,smalltext,ftitle,newstime,special.field,titlepic,title,diggdown,',
'dohtmlf'=>',sbanner,conclusion,series,bigpic,parameter,editnotes,newstext,diggtop,smalltext,ftitle,newstime,special.field,titlepic,title,lteditor,wztitle,infopfen,infopfennum,specialid,diggdown,fromlink,',
'checkboxf'=>',',
'savetxtf'=>'',
'editorf'=>',newstext,smalltext,',
'ubbeditorf'=>',',
'pagef'=>'newstext',
'smalltextf'=>',conclusion,smalltext,',
'filef'=>',',
'imgf'=>',sbanner,bigpic,titlepic,',
'flashf'=>',',
'linkfields'=>'|copyfrom,copyfrom.title|author,author.title|',
'morevaluef'=>'|',
'onlyf'=>',',
'adddofunf'=>'||',
'editdofunf'=>'||',
'qadddofunf'=>'||',
'qeditdofunf'=>'||',
'definfovoteid'=>0,
'orderf'=>',newstime,diggtop,',
'sonclass'=>'|3|7|104|29|37|38|39|40|41|42|43|44|45|46|48|49|50|51|59|60|61|62|63|64|65|66|67|68|69|70|71|72|73|74|75|76|77|78|79|80|81|82|83|84|85|86|87|88|',
'tid'=>10,
'tbname'=>'article');
$emod_r[8]=Array('mid'=>8,
'mname'=>'分类信息系统模型',
'qmname'=>'分类信息',
'defaulttb'=>0,
'datatbs'=>',1,',
'deftb'=>'1',
'enter'=>',title,special.field,newstime,smalltext,titlepic,myarea,email,mycontact,address,',
'qenter'=>',title,smalltext,titlepic,myarea,email,mycontact,address,',
'listtempf'=>',title,newstime,smalltext,titlepic,myarea,',
'tempf'=>',title,newstime,smalltext,titlepic,myarea,email,mycontact,address,',
'mustqenterf'=>',title,smalltext,myarea,email,',
'listandf'=>',myarea,',
'setandf'=>0,
'searchvar'=>',title,smalltext,myarea,',
'cj'=>',title,newstime,smalltext,titlepic,myarea,email,mycontact,address,',
'canaddf'=>',title,newstime,smalltext,titlepic,myarea,email,mycontact,address,',
'caneditf'=>',title,newstime,smalltext,titlepic,myarea,email,mycontact,address,',
'tbmainf'=>',title,titlepic,newstime,smalltext,myarea,',
'tbdataf'=>',email,mycontact,address,',
'tobrf'=>',smalltext,',
'dohtmlf'=>',smalltext,myarea,email,mycontact,address,',
'checkboxf'=>',',
'savetxtf'=>'',
'editorf'=>',',
'ubbeditorf'=>',',
'pagef'=>'',
'smalltextf'=>',smalltext,',
'filef'=>',',
'imgf'=>',titlepic,',
'flashf'=>',',
'linkfields'=>'|',
'morevaluef'=>'|',
'onlyf'=>',',
'adddofunf'=>'||',
'editdofunf'=>'||',
'qadddofunf'=>'||',
'qeditdofunf'=>'||',
'definfovoteid'=>0,
'orderf'=>'',
'sonclass'=>'|11|12|13|14|15|16|18|19|20|21|23|24|25|26|28|29|30|31|',
'tid'=>8,
'tbname'=>'info');
$emod_r[9]=Array('mid'=>9,
'mname'=>'作者模型',
'qmname'=>'作者文集',
'defaulttb'=>0,
'datatbs'=>',1,',
'deftb'=>'1',
'enter'=>',title,ftitle,smalltext,special.field,titlepic,uid,verifier,blogurl,weibourl,lastudtime,hitsum,newhits,commentssum,newcomments,diggsum,newdigg,description,newstime,infozm,infoip,',
'qenter'=>',title,ftitle,smalltext,titlepic,uid,verifier,blogurl,weibourl,description,infoip,',
'listtempf'=>',title,ftitle,smalltext,titlepic,lastudtime,hitsum,newhits,commentssum,newcomments,diggsum,newdigg,newstime,infozm,',
'tempf'=>',title,ftitle,smalltext,titlepic,uid,verifier,blogurl,weibourl,lastudtime,hitsum,newhits,commentssum,newcomments,diggsum,newdigg,description,newstime,infozm,',
'mustqenterf'=>',title,',
'listandf'=>',title,infozm,',
'setandf'=>0,
'searchvar'=>',title,',
'cj'=>',title,',
'canaddf'=>',title,ftitle,smalltext,titlepic,uid,verifier,blogurl,weibourl,lastudtime,hitsum,newhits,commentssum,newcomments,diggsum,newdigg,description,newstime,infozm,',
'caneditf'=>',title,ftitle,smalltext,titlepic,uid,verifier,blogurl,weibourl,lastudtime,hitsum,newhits,commentssum,newcomments,diggsum,newdigg,description,newstime,infozm,',
'tbmainf'=>',title,titlepic,newstime,smalltext,lastudtime,hitsum,newhits,newcomments,commentssum,newdigg,diggsum,ftitle,infozm,blogurl,weibourl,uid,verifier,infoip,',
'tbdataf'=>',description,',
'tobrf'=>',',
'dohtmlf'=>',hitsum,commentssum,diggsum,description,ftitle,blogurl,weibourl,uid,verifier,infoip,',
'checkboxf'=>',',
'savetxtf'=>'',
'editorf'=>',smalltext,description,',
'ubbeditorf'=>',',
'pagef'=>'',
'smalltextf'=>',',
'filef'=>',',
'imgf'=>',titlepic,',
'flashf'=>',',
'linkfields'=>'|',
'morevaluef'=>'|',
'onlyf'=>',',
'adddofunf'=>'||lastudtime!#!author_wz_lastpublic_time||hitsum!#!author_wz_total##1,0||newhits!#!author_wz_total##0,0||newcomments!#!author_wz_total##0,1||commentssum!#!author_wz_total##1,1||newdigg!#!author_wz_total##0,2||diggsum!#!author_wz_total##1,2||',
'editdofunf'=>'||lastudtime!#!author_wz_lastpublic_time||hitsum!#!author_wz_total##1,0||newhits!#!author_wz_total##0,0||newcomments!#!author_wz_total##0,1||commentssum!#!author_wz_total##1,1||newdigg!#!author_wz_total##0,2||diggsum!#!author_wz_total##1,2||',
'qadddofunf'=>'||',
'qeditdofunf'=>'||',
'definfovoteid'=>0,
'orderf'=>',lastudtime,',
'sonclass'=>'|2|33|',
'tid'=>9,
'tbname'=>'author');
$emod_r[11]=Array('mid'=>11,
'mname'=>'来源模型',
'qmname'=>'来源链接',
'defaulttb'=>0,
'datatbs'=>',1,',
'deftb'=>'1',
'enter'=>',special.field,title,webaddr,blogurl,weibourl,titlepic,icon,ftitle,smalltext,description,newstime,infoip,',
'qenter'=>',',
'listtempf'=>',title,webaddr,blogurl,weibourl,titlepic,icon,ftitle,smalltext,description,newstime,',
'tempf'=>',title,webaddr,blogurl,weibourl,titlepic,icon,ftitle,smalltext,description,newstime,',
'mustqenterf'=>',title,',
'listandf'=>'',
'setandf'=>0,
'searchvar'=>',title,',
'cj'=>',title,webaddr,',
'canaddf'=>',title,webaddr,blogurl,weibourl,titlepic,icon,ftitle,smalltext,description,newstime,',
'caneditf'=>',title,webaddr,blogurl,weibourl,titlepic,icon,ftitle,smalltext,description,newstime,',
'tbmainf'=>',weibourl,blogurl,title,titlepic,newstime,ftitle,description,icon,smalltext,infoip,webaddr,',
'tbdataf'=>',',
'tobrf'=>',ftitle,',
'dohtmlf'=>',weibourl,blogurl,ftitle,smalltext,infoip,webaddr,',
'checkboxf'=>',',
'savetxtf'=>'',
'editorf'=>',smalltext,',
'ubbeditorf'=>',',
'pagef'=>'',
'smalltextf'=>',',
'filef'=>',',
'imgf'=>',titlepic,',
'flashf'=>',',
'linkfields'=>'|',
'morevaluef'=>'|',
'onlyf'=>',title,',
'adddofunf'=>'||',
'editdofunf'=>'||',
'qadddofunf'=>'||',
'qeditdofunf'=>'||',
'definfovoteid'=>0,
'orderf'=>'',
'sonclass'=>'|92|93|96|97|98|102|90|91|',
'tid'=>11,
'tbname'=>'copyfrom');
$emod_r[12]=Array('mid'=>12,
'mname'=>'话题系统模型',
'qmname'=>'话题',
'defaulttb'=>0,
'datatbs'=>',1,',
'deftb'=>'1',
'enter'=>',title,special.field,titlepic,banner,linetitle,sbanner,newstime,ftitle,voteoption,tagid,specid,author,copyfrom,smalltext,newstext,information,editor,lteditor,',
'qenter'=>',',
'listtempf'=>',title,titlepic,newstime,ftitle,tagid,specid,author,copyfrom,smalltext,editor,lteditor,',
'tempf'=>',title,titlepic,banner,linetitle,sbanner,newstime,ftitle,voteoption,tagid,specid,author,copyfrom,smalltext,newstext,information,editor,lteditor,',
'mustqenterf'=>',title,',
'listandf'=>'',
'setandf'=>0,
'searchvar'=>',title,tagid,author,copyfrom,editor,',
'cj'=>',title,',
'canaddf'=>',title,titlepic,banner,linetitle,sbanner,newstime,ftitle,voteoption,tagid,specid,author,copyfrom,smalltext,newstext,information,editor,lteditor,',
'caneditf'=>',title,titlepic,banner,linetitle,sbanner,newstime,ftitle,voteoption,tagid,specid,author,copyfrom,smalltext,newstext,information,lteditor,',
'tbmainf'=>',sbanner,title,titlepic,newstime,ftitle,smalltext,banner,tagid,specid,author,copyfrom,editor,diggtop,lteditor,diggdown,voteoption,linetitle,',
'tbdataf'=>',newstext,information,',
'tobrf'=>',smalltext,newstext,diggtop,',
'dohtmlf'=>',sbanner,ftitle,smalltext,banner,tagid,specid,copyfrom,newstext,diggtop,information,lteditor,diggdown,voteoption,linetitle,',
'checkboxf'=>',',
'savetxtf'=>'',
'editorf'=>',smalltext,newstext,information,',
'ubbeditorf'=>',',
'pagef'=>'',
'smalltextf'=>',smalltext,',
'filef'=>',',
'imgf'=>',sbanner,titlepic,banner,',
'flashf'=>',',
'linkfields'=>'|tagid,enewstags.tagid|author,author.title|copyfrom,copyfrom.title|',
'morevaluef'=>'|',
'onlyf'=>',title,',
'adddofunf'=>'||',
'editdofunf'=>'||',
'qadddofunf'=>'||',
'qeditdofunf'=>'||',
'definfovoteid'=>0,
'orderf'=>',newstime,',
'sonclass'=>'',
'tid'=>12,
'tbname'=>'topic');
$emod_r[13]=Array('mid'=>13,
'mname'=>'专题系统模型',
'qmname'=>'专题',
'defaulttb'=>0,
'datatbs'=>',1,',
'deftb'=>'1',
'enter'=>',title,special.field,titlepic,newstime,specid,ftitle,',
'qenter'=>',',
'listtempf'=>',title,titlepic,newstime,specid,ftitle,',
'tempf'=>',title,titlepic,newstime,specid,ftitle,',
'mustqenterf'=>',title,',
'listandf'=>'',
'setandf'=>0,
'searchvar'=>',title,',
'cj'=>',title,',
'canaddf'=>',title,titlepic,newstime,specid,ftitle,',
'caneditf'=>',title,titlepic,newstime,specid,ftitle,',
'tbmainf'=>',title,titlepic,newstime,ftitle,specid,',
'tbdataf'=>',',
'tobrf'=>',',
'dohtmlf'=>',ftitle,specid,',
'checkboxf'=>',',
'savetxtf'=>'',
'editorf'=>',',
'ubbeditorf'=>',',
'pagef'=>'',
'smalltextf'=>',',
'filef'=>',',
'imgf'=>',titlepic,',
'flashf'=>',',
'linkfields'=>'|',
'morevaluef'=>'|',
'onlyf'=>',title,',
'adddofunf'=>'||',
'editdofunf'=>'||',
'qadddofunf'=>'||',
'qeditdofunf'=>'||',
'definfovoteid'=>0,
'orderf'=>',newstime,',
'sonclass'=>'|103|89|',
'tid'=>13,
'tbname'=>'special');


//-------EmpireCMS.Public.Cache-------

?>
