<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:wb="http://open.weibo.com/wb" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>[!--pagetitle--] - <?=$public_r['sitename']?></title>
        <meta name="keywords" content="[!--pagekey--]" />
        <meta name="description" content="[!--pagedes--]" />
        <link rel="shortcut icon" href="[!--news.url--]skin/default/images/favicon.ico" /> 
        <link href="[!--news.url--]skin/default/css/base.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="[!--news.url--]skin/default/js/jquery-1.8.2.min.js"></script>
        <script type="text/javascript" src="[!--news.url--]skin/default/js/custom.js"></script>
    </head>
    <body class="special">

        <!-- header -->
        <div class="small header">
    <div class="redlogo">
        <div class="logo">
            <a href="[!--news.url--]" title="红歌会网首页"><img src="[!--news.url--]skin/default/images/logo.png" /></a>
            <div class="top_menu right">
                <a href="http://hao.szhgh.com/" title="点此进入红歌会网址导航" target="_blank">网址导航</a>
                <a class='first' href="[!--news.url--]special" title="点此进入专题中心" target="_blank">专题中心</a>
                <a href="[!--news.url--]xuezhe/" title="点此进入学者专栏" target="_blank">学者专栏</a>
                <a href="[!--news.url--]e/member/cp/">会员中心</a>
                <a href="[!--news.url--]e/DoInfo/AddInfo.php?mid=10&enews=MAddInfo&classid=29">我要投稿</a>
            </div> 
            <div class="clear"></div>
        </div>
    </div>
</div>

        <!-- header end -->


        <!-- channelnav -->
        <div class="wrap">
            <div class="channelnav clearfix">
                <div id="ztlogo" class="left"><a href="/special" target="_self"><img src="/skin/default/images/bg_channel89.png" /></a></div>
                <div class="right">
                    <script src="[!--news.url--]d/js/js/search_news1.js" type="text/javascript"></script>
                    <div class="account left">
                        <script>
                            document.write('<script src="[!--news.url--]e/member/login/loginjs.php?t=' + Math.random() + '"><' + '/script>');
                        </script>
                    </div>
                </div>
            </div>
        </div>  
        <!-- channelnav end -->

        <!-- main -->
        <div class="container">
            <div class="sidebar">
                <div class="section">
                    <div class="section_header">
                        <strong>热点专题</strong>
                    </div>
                    <ul class="section_content elite ">
                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(89,10,1,0,'','onclick DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                        <li><a href="<?=$bqsr['titleurl']?>" target="_blank"><?=$bqr['title']?></a> </li>
                        <?php
}
}
?>
                    </ul>                        
                </div>
                <div class="section">
                    <div class="section_header">
                        <strong>推荐学者</strong>
                    </div>
                    <ul class="section_content ">
                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(33,6,0,0,'','newstime desc');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                        <li class="imgxz clearfix">
                            <a href="<?=$bqsr[titleurl]?>" target="_blank"><img src="<?=$bqr['titlepic']?>" /></a>
                            <div class="intro">
                                <p class="xzname"><a href="<?=$bqsr[titleurl]?>" target="_blank"><?=$bqr['title']?></a></p>
                                <p><?=$bqr['ftitle']?></p>
                            </div>
                        </li>
                        <?php
}
}
?>
                    </ul>                        
                </div>			
            </div>
            <div class="main">
                <div class="zt_list">
                    [!--empirenews.listtemp--]
                    <!--list.var1-->
                    [!--empirenews.listtemp--]
                </div>
                <center class="page"><strong>[!--show.page--]</strong></center>                  
            </div>
            <div class="clear"></div>
        </div>
        <!-- main end -->

        <!-- footer -->
        <script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js" type="text/javascript" charset="utf-8"></script>
<div class="footer">
    <div class="sitemap">
        <ul class="mapul">
            <li class="mapli first">
                <strong><a href="[!--news.url--]Article/news/" title="资讯中心" target="_blank">资讯中心</a></strong>
                <ul class='specialstyle'>
                    <li><a href="[!--news.url--]gundong/" title="滚动" target="_blank">滚动</a></li>
                    <li><a href="[!--news.url--]Article/news/politics/" title="时政" target="_blank">时政</a></li>
                    <li><a href="[!--news.url--]Article/news/world/" title="国际" target="_blank">国际</a></li>
                    <li><a href="[!--news.url--]Article/news/leaders/" title="高层" target="_blank">高层</a></li>
                    <li><a href="[!--news.url--]Article/news/gangaotai/" title="港澳台" target="_blank">港澳台</a></li>
                    <li><a href="[!--news.url--]Article/news/society/" title="社会" target="_blank">社会</a></li>
                    <li><a href="[!--news.url--]Article/news/fanfu/" title="反腐" target="_blank">反腐</a></li>
                    <li><a href="[!--news.url--]Article/news/chujian/" title="除奸" target="_blank">除奸</a></li>  
                    <div class="clear"></div>
                </ul>
                <div class="clear"></div>
            </li>
            <li class="mapli">
                <strong><a href="[!--news.url--]Article/opinion/" title="纵论天下" target="_blank">纵论天下</a></strong>
                <ul>
                    <li><a href="[!--news.url--]Article/opinion/wp/" title="红歌会网评" target="_blank">红歌会网评</a></li>
                    <li><a href="[!--news.url--]Article/opinion/xuezhe/" title="学者观点" target="_blank">学者观点</a></li>
                    <li><a href="[!--news.url--]Article/opinion/zatan/" title=" 网友杂谈" target="_blank"> 网友杂谈</a></li>
                    <li><a href="[!--news.url--]Article/opinion/weibo/" title="微博天下" target="_blank">微博天下</a></li>
                </ul>
                <div class="clear"></div>
            </li>               
            <li class="mapli">
                <strong><a href="[!--news.url--]Article/red-china/" title="红色中国" target="_blank">红色中国</a></strong>
                <ul>
                    <li><a href="[!--news.url--]Article/red-china/mzd/" title=" 毛泽东" target="_blank"> 毛泽东</a></li>
                    <li><a href="[!--news.url--]Article/red-china/ideal/" title=" 理想园地" target="_blank"> 理想园地</a></li>
                    <li><a href="[!--news.url--]Article/red-china/redman/" title="红色人物" target="_blank">红色人物</a></li>
                    <li><a href="[!--news.url--]Article/red-china/tour/" title="红色旅游" target="_blank">红色旅游</a></li>
                </ul>
                <div class="clear"></div>
            </li>
            <li class="mapli">
                <strong><a href="[!--news.url--]Article/cdjc/" title="唱读讲传" target="_blank">唱读讲传</a></strong>
                <ul>
                    <li><a href="[!--news.url--]Article/cdjc/hongge/" title="唱红歌" target="_blank">唱红歌</a></li>
                    <li><a href="[!--news.url--]Article/cdjc/jingdian/" title="读经典" target="_blank">读经典</a></li>
                    <li><a href="[!--news.url--]Article/cdjc/gushi/" title="讲故事" target="_blank">讲故事</a></li>
                    <li><a href="[!--news.url--]Article/cdjc/zhengqi/" title="传正气" target="_blank">传正气</a></li>
                </ul>
                <div class="clear"></div>
            </li>                 
            <li class="mapli">
                <strong><a href="[!--news.url--]Article/health/" title="人民健康" target="_blank">人民健康</a></strong>
                <ul>
                    <li><a href="[!--news.url--]Article/health/zjy/" title="转基因" target="_blank">转基因</a></li>
                    <li><a href="[!--news.url--]Article/health/zhongyi/" title="中医" target="_blank">中医</a></li>
                    <li><a href="[!--news.url--]Article/health/baojian/" title="保健" target="_blank">保健</a></li>
                    <li><a href="[!--news.url--]Article/health/food/" title="食品安全" target="_blank">食品安全</a></li>
                </ul>
                <div class="clear"></div>
            </li>
             <li class="mapli">
                <strong><a href="[!--news.url--]Article/gnzs/" title="工农之声" target="_blank">工农之声</a></strong>
                <ul>
                    <li><a href="[!--news.url--]Article/gnzs/farmer/" title="农民之声" target="_blank">农民之声</a></li>
                    <li><a href="[!--news.url--]Article/gnzs/worker/" title="工友之家" target="_blank">工友之家</a></li>
                    <li><a href="[!--news.url--]Article/gnzs/gongyi/" title="公益行动" target="_blank">公益行动</a></li>
                </ul>
                <div class="clear"></div>
            </li>               
            <li class="mapli">
                <strong><a href="[!--news.url--]Article/wsds/" title="文史·读书" target="_blank">文史读书</a></strong>
                <ul class="specialstyle">
                    <li><a href="[!--news.url--]Article/wsds/wenyi/" title="文艺" target="_blank">文艺</a></li>
                    <li><a href="[!--news.url--]Article/wsds/culture/" title="文化" target="_blank">文化</a></li>
                    <li><a href="[!--news.url--]Article/wsds/history/" title="历史" target="_blank">历史</a></li>
                    <li><a href="[!--news.url--]Article/wsds/read/" title=" 读书" target="_blank"> 读书</a></li>
                    <li><a href="[!--news.url--]Article/wsds/youth/" title="青年" target="_blank">青年</a></li>
                    <div class="clear"></div>
                </ul>
                <div class="clear"></div>
            </li>
            <li class="mapli">
                <strong><a href="[!--news.url--]Article/thirdworld/" title="第三世界" target="_blank">第三世界</a></strong>
                <ul>
                    <li><a href="[!--news.url--]Article/thirdworld/korea/" title="朝鲜" target="_blank">朝鲜</a></li>
                    <li><a href="[!--news.url--]Article/thirdworld/cuba/" title="古巴" target="_blank">古巴</a></li>
                    <li><a href="[!--news.url--]Article/thirdworld/latin-america/" title="拉美" target="_blank">拉美</a></li>
                    <li><a href="[!--news.url--]Article/thirdworld/africa/" title="非洲" target="_blank">非洲</a></li>
                </ul>
                <div class="clear"></div>
            </li>                
            <div class="clear"></div>
        </ul>
    </div>
    <div class="copyright">
        <ul>
            <li class='copy_left'>
                <div>
                    <a href="[!--news.url--]" title="红歌会网" target="_blank">红歌会网</a>
                    | <a href="[!--news.url--]html/sitemap.html" title="网站地图" target="_blank">网站地图</a>
                    | <a href="[!--news.url--]html/rank.html" title="排行榜" target="_blank">排行榜</a>
                    | <a href="[!--news.url--]Article/notice/20257.html" title="联系我们" target="_blank">联系我们</a>
                    | <a href="[!--news.url--]Article/opinion/zatan/13968.html" title="在线提意见" target="_blank">在线提意见</a>
                </div>
                <div>
                    <a href="http://www.miitbeian.gov.cn" target="_blank">粤ICP备12077717号-1</a>
                    | <script src="http://s20.cnzz.com/stat.php?id=3051861&web_id=3051861&show=pic1" language="JavaScript"></script>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?2e62d7088e3926a4639571ba4c25de10";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>

<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F2e62d7088e3926a4639571ba4c25de10' type='text/javascript'%3E%3C/script%3E"));
</script>
                </div>
            </li>
            <li class="focusbutton">
                <a class="rss" href="[!--news.url--]e/web/?type=rss2" title="欢迎订阅红歌会网" target="_blank"></a>
                <a class="sinaweibo" href="http://weibo.com/szhgh?topnav=1&wvr=5&topsug=1" title="欢迎关注红歌会网新浪微博" target="_blank"></a>
                <a class="qqweibo" href="http://follow.v.t.qq.com/index.php?c=follow&amp;a=quick&amp;name=szhgh001&amp;style=5&amp;t=1737191719&amp;f=1" title="欢迎关注红歌会网腾讯微博" target="_blank"></a>
                <a class="qqmsg" href="http://wpa.qq.com/msgrd?Uin=1962727933" title="欢迎通过QQ联系我们" target="_blank"></a>
                <a class="email" href="mailto:szhgh001@163.com" title="欢迎投稿或反映问题" target="_blank"></a>
            </li>
            <li class="focusmsg">
                <div>网站QQ：<a href="http://wpa.qq.com/msgrd?Uin=1962727933" title="欢迎通过QQ联系我们" target="_blank">1962727933</a>&nbsp;&nbsp;红歌会网粉丝QQ群：368613859</div>
                <div>(投稿)邮箱：<a href="mailto:szhgh001@163.com" title="欢迎投稿或反映问题" target="_blank">szhgh001@163.com</a></div>
            </li>
            <div class="clear"></div>
        </ul>
    </div>
</div>

<script src="[!--news.url--]skin/default/js/jquery.leanModal.min.js" type="text/javascript"></script>
<div id="loginmodal" class="loginmodal" style="display:none;">
    <div class="modaletools"><a class="hidemodal" title="点此关闭">×</a></div>
    <form class="clearfix" name=login method=post action="[!--news.url--]e/member/doaction.php">
        <div class="login left">
            <strong>会员登录</strong>
            <input type=hidden name=enews value=login>
            <input type=hidden name=ecmsfrom value=9>
            <div id="username" class="txtfield username"><input name="username" type="text" size="16" /></div>
            <div id="password" class="txtfield password"><input name="password" type="password" size="16" /></div>
            <div class="forgetmsg"><a href="/e/member/GetPassword/" title="点此取回密码" target="_blank">忘记密码？</a></div>
            <div class="poploginex"><script type="text/javascript">document.write('<script  type="text/javascript" src="[!--news.url--]e/memberconnect/panjs.php?type=login&dclass=login&t='+Math.random()+'"><'+'/script>');</script></div>
            <input type="submit" name="Submit" value="登陆" class="inputSub flatbtn-blu" />
        </div>
        <div class="reg right">
            <div class="regmsg"><span>还不是会员？</span></div>
            <input type="button" name="Submit2" value="立即注册" class="regbutton" onclick="window.open('[!--news.url--]e/member/register/');" />
        </div>
    </form>
</div>
<script type="text/javascript">
    $(function(){
      $('#loginform').submit(function(e){
        return false;
      });

      $('#modaltrigger').leanModal({ top: 110, overlay: 0.45, closeButton: ".hidemodal" });
      $('#modaltrigger_plinput').leanModal({ top: 110, overlay: 0.45, closeButton: ".hidemodal" });
      
      $('#username input').OnFocus({ box: "#username" });
      $('#password input').OnFocus({ box: "#password" });

    });
</script>

        <!-- footer end -->            
        [!--page.stats--]
    </body>
</html>