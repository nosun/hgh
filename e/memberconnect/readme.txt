/*
 * @author LGM
 * @version 1.0
 * @modifytime:2014年2月10日10:07
 
   第三方交互平台接入使用说明：
  (1) 修改的代码：
 1:  /e/center/member/MemberConnect.php - function EditMemberConnect($add,$userid,$username)  增加五个字段，生成代码文件
 2:  /e/center/member/SetMemberConnect.php - 增加五个字段 callbackurl,callbackurl2,info  LINE 83
 3:  /e/center/conversion/lib/db_sql.php  - 修改 函数 fetch，fetch1，加入$array_type参数
 4：  /e/memberconnect/memberconnectfun.php -修改函数： MemberConnect_DoLogin,MemberConnect_UpdateBindLogin,MemberConnect_InsertBind,MemberConnect_DelBind,MemberConnect_BindUser,MemberConnect_ResetVar 主要是新增三个字段(token,expired,scope)
 5：  /e/member/class/member_registerfun.php -修改：function register LINE 73,234
 6:  /e/member/class/member_loginfun.php  - 修改 ： function qlogin LINE 105，function qloginout
 7:  /e/member/class/user.php - 修改函数 DoEcmsMemberLogin,AddLoginCookie
 8:  /e/member/iframe/index.php - 加入第三方交互按钮，以及文章链接，标题等信息
 9： /e/member/register/index.php  增加eiusername
 10： /e/member/register/ChangeRegister.php 增加eiusername 和 tobind
 11:  /e/class/db_sql.php  - 修改 函数 fetch，fetch1，加入$array_type参数
 12： /e/class/connect.php - 加入第三方交互扩展变量键名常量
 13: /e/class/userfun.php - 加入函数 GetEIApps ,GetArcTitleAndUrl
 14: /e/template/memberconnect/tobind.php 增加eiusername
 15: /e/pl/plfun.php - 加入第三方交互平台信息提交代码 LINE188
 16: /e/center/conversion/lib/functions.php 增加DecodePHPTag函数，修改ReLoginIframe函数
 17: /e/class/functions.php 增加DecodePHPTag函数，修改ReLoginIframe函数
 18: /e/data/html/apprwcode.php 由/e/center/member/MemberConnect.php 的 EditMemberConnect 动态生成代码文件
 19: 在 评论列表模板 -> 默认评论列表模板 中 调用JS生成评论表单 的地方改成：
                <script type="text/javascript">
                document.write('<script  type="text/javascript" src="[!--news.url--]e/member/iframe/index.php?classid=[!--classid--]&id=[!--id--]<?php 
                if(!empty($n_r)){
                     if(!empty($n_r['title'])) echo '&pagetitle='.urlencode($n_r['title']);
                     if(!empty($n_r['titleurl'])) echo '&titleurl='.urlencode($n_r['titleurl']);
                }
                ?>&t='+Math.random()+'"><'+'/script>');
                </script>
 20： 在模板变量->评论表单[!--temp.pl--] 中调用JS生成评论表单的地方改成：
                <script type="text/javascript">
                    document.write('<script src="[!--news.url--]e/member/iframe/?classid=[!--classid--]&id=[!--id--]<?php 
                   if(!empty($navinfor)){
                     if(!empty($navinfor['title'])) echo '&pagetitle='.urlencode($navinfor['title']);
                     if(!empty($navinfor['titleurl'])) echo '&titleurl='.urlencode($navinfor['titleurl']);
                }
                ?>&t='+Math.random()+'"><'+'/script>');
                </script>

21: 在系统后台->公共模板管理->登陆状态模板 修改：

		登录块加入 ：
<span><script type="text/javascript">document.write('<script type="text/javascript" src="[!--news.url--]e/memberconnect/panjs.php?type=login&dclass=login<?php 
$op_pt='&pagetitle='.urlencode($_REQUEST['pagetitle']);
$op_url =$op_pt.'&titleurl='.urlencode($_REQUEST['titleurl']);
echo $op_url;
?>&t='+Math.random()+'"></'+'script>');</script></span>
		登录后块加入(注意：参数type=pl)：
<span><script type="text/javascript">document.write('<script  type="text/javascript" src="[!--news.url--]e/memberconnect/panjs.php?type=pl&dclass=pl<?php 
$op_pt='&pagetitle='.urlencode($_REQUEST['pagetitle']);
$op_url ='&classid='.$classid.'&id='.$id. $op_pt.'&titleurl='.urlencode($_REQUEST['titleurl']);
echo $op_url;
?>&t='+Math.random()+'"><'+'/script>');</script></span>

              注册模板E:\e\template\member\register.php LINE66:
   if($public_r['regkey_ok']) 替换成:    if($public_r['regkey_ok'] && !$tobind)
    
22: 在系统后台->公共模板管理->JS调用登陆状态模板 加入：    
   登录部分加入 ：
     <span class="toploginex"><script type="text/javascript">document.write('<script  type="text/javascript" src="[!--news.url--]e/memberconnect/panjs.php?type=login&dclass=login&t='+Math.random()+'"><'+'/script>');</script></span>
     22: 在系统后台-> 公共模板变量 > 管理模板变量 > 修改模板变量：footer
     在弹出登录框内的适当位置加入 ：
     <div class="poploginex"><script type="text/javascript">document.write('<script  type="text/javascript" src="[!--news.url--]e/memberconnect/panjs.php?type=login&dclass=login&t='+Math.random()+'"><'+'/script>');</script></div>
23: 在\skin\default\js\custom.js 加入函数: 
    function hiteiv(sender,value,elehander)
    {
         if(elehander)
        {
            if(elehander instanceof Object)
            {
               elehander =  $(elehander);
            }
            else
            {
                elehander =  $('#'+elehander);
            }        
        }   
        var em = $('em',sender);
        if(em){
            if(em.hasClass('isselect'))
            {
                em.removeClass('isselect');
                em.addClass('disable');
                if(elehander)
                {
                    var ev = elehander.val();
                    elehander.val(ev.replace(value+"|",""));
                }
            }
            else
            {
               em.removeClass('disable');
               em.addClass('isselect');  
               if(elehander)
                {
                    var ev = elehander.val();
                    elehander.val(ev+value+"|");
                }
            }
        }

    }



(2) 修改了表的结构：
ALTER TABLE `hgh_enewsmember_connect_app`
ADD COLUMN `callbackurl`  varchar(512) NULL AFTER `appsay`,
ADD COLUMN `callbackurl2`  varchar(512) NULL AFTER `callbackurl`,
ADD COLUMN `info`  varchar(1024) NULL AFTER `callbackurl2`,
ADD COLUMN `tranregexlist`  varchar(2048) NULL COMMENT '转换正则规则表' AFTER `info`,
ADD COLUMN `trankeywordslist`  varchar(2048) NULL COMMENT '转换关键词表' AFTER `tranregexlist`;

ALTER TABLE `hgh_enewsmember_connect`
ADD COLUMN `token`  varchar(128) CHARACTER SET ascii NULL AFTER `lasttime`,
ADD COLUMN `expired`  datetime NULL AFTER `token`,
ADD COLUMN `scope`  varchar(128) NULL AFTER `expired`,
ADD COLUMN `bindname`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '第三方交互平台用户名' AFTER `scope`,
DROP INDEX `bindkey` ,
ADD INDEX `bindkey` (`bindkey`, `token`) USING BTREE ;

变量说明：
  $_SESSION['sina_token']:存储当前已经登录的第三方交互的access_token,可能为qq_token
  $_SESSION['mlbindeis']：存储当前用户绑定的第三方交互的账号列表
  

(3) 后台设置：
          插件安装后，可登录后台>“用户”>“外部接口”>“管理外部登录接口”：里设置参数。
    appid（应用ID）、appkey（应用密钥）需要自己到新浪微博官网申请。 
    
(4) 前台代码：
    1：登录代码
      <script  type="text/javascript" src="/e/memberconnect/panjs.php?type=login&dclass=login"></script>
    2:显示评论时发布第三方交互平台账号列表:
      <script  type="text/javascript" src="/e/memberconnect/panjs.php?type=pl&dclass=pl"></script>
	
2：调取信息：
//------------------1 包含文件 -------------------
include_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'memberconnect'.DIRECTORY_SEPARATOR.'memberconnectfun.php' );//根据需要包含
include_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'memberconnect'.DIRECTORY_SEPARATOR.'eibase.php' );
//------------------2 实例化操作对象------------------
$p = array('access_token'=>$_SESSION[$apptype.'_token']['access_token']);//当前已经登录的第三方交互平台账号
//$p =NULL;//当前会员所绑定的第三方交互平台账号
//$p = array('access_token'=>EIService::GetAccessTokenForUID($userid,$apptype));//指定会员
$c = EIService::GetAction('sina',$p);
//或者采用如下方式：
//$c = new SinaAction($app_id , $app_secret , $_SESSION[$apptype.'_token']['access_token'] );

//------------------3 用操作对象读取信息--------------------
$params = array();
$params['内容']='评论内容....';
$params['元数据']='[{"arcid":'.$id.',"repid":'.$repid.'}]';
$r=$c->CreateInfo($params);//发布一条评论

$uid =$c->GetOAuth()->GetEID();//获取第三方平台的用户ID
$p = array('uid'=>$uid);
$ms  = $c->UserTimeline($p); //获取该用户所发的信息
if(is_array($ms)){
	$p2= array('id'=>$ms['statuses'][0]['id']);//获取第一条微博的ID
}
$listc=$c->ListComments($p2);//获取第一条微博的评论列表
$p2['comment']='评论内容 ...';
$xpp=$c->CreateComments($p2);//发一条评论
$user_message = $c->GetUserInfo($p);//根据ID获取用户等基本信息
$c->DestroyInfo($p2);//删除第一条微博

 ********************安装/卸载插件********************
1、将“sina”目录下的文件上传至帝国CMS系统 /e/memberconnect/目录；
2、将eibase.php拷贝到 /e/memberconnect/目录；
3、登录管理后台》系统》扩展菜单》管理菜单，增加菜单分类“第三方交互”，类型选“插件菜单”，然后进入“管理菜单”，添加菜单名称“新浪交互安装与卸载"，链接地址“../memberconnect/sina/install/index.php”
3、转到后台的扩展菜单“新浪交互安装与卸载",依提示进行安装/卸载；
********************插件使用     ********************

1、插件安装后，可登录后台>“用户”>“外部接口”>“管理外部登录接口”：里设置参数。
    appid（应用ID）、appkey（应用密钥）需要自己到新浪微博官网申请。


3、生成相应页面。

********************     插件目录说明     ********************

/sina/                     新浪微博交互插件目录
    ├install/              插件安装/卸载文件目录
    │├index.php            安装/卸载主文件
    │├install.php          安装插件文件
    │└uninstall.php        卸载插件文件
    ├images/               图片目录
    ├class.php             功能实现文件
    ├to_login.php          转向登录处理文件
    └loginend.php          返回登录处理文件
*/