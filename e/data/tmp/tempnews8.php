<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:wb="http://open.weibo.com/wb" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?=$grpagetitle?> - <?=$class_r[$ecms_gr[classid]][classname]?> - <?=$public_r['sitename']?></title>
        <meta name="keywords" content="<?=$ecms_gr[keyboard]?>" />
        <meta name="description" content="<?=$grpagetitle?>" />
        <link rel="shortcut icon" href="http://www.szhgh.com/skin/default/images/favicon.ico" /> 
        <link href="http://www.szhgh.com/skin/default/css/base.css" rel="stylesheet" type="text/css" />
        <script src="http://www.szhgh.com/skin/default/js/jquery-1.8.2.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="http://www.szhgh.com/e/data/js/ajax.js"></script>
        <script src="http://www.szhgh.com/skin/default/js/custom.js" type="text/javascript"></script>
        <script src="http://www.szhgh.com/skin/default/js/jquery.cookie.js" type="text/javascript"></script>
    </head>
    <body class="article_content" closepl="<?=$navinfor['closepl']?>" havelogin="<?=$mhavelogin?>">

            <!-- header -->
            <div class="header">
    <div class="toolbar">
        <div class="right">
            <div class="account left">
                <script>
                    document.write('<script src="http://www.szhgh.com/e/member/login/loginjs.php?t='+Math.random()+'"><'+'/script>');
                </script>
            </div>
            <!--<script src="http://www.szhgh.com/d/js/js/search_news1.js" type="text/javascript"></script>           --> 
        </div>
        <div class="top_menu"><a href="http://hao.szhgh.com/" title="点此进入红歌会网址导航" target="_blank">网址导航</a><a class='first' href="http://www.szhgh.com/" title="红歌会网首页" target="_blank">首页</a><a target="_blank" title="资讯中心" href="http://www.szhgh.com/Article/news/">资讯中心</a><a target="_blank" title="纵论天下" href="http://www.szhgh.com/Article/opinion/">纵论天下</a><a target="_blank" title="红色中国" href="http://www.szhgh.com/Article/red-china/">红色中国</a><a href="http://www.szhgh.com/special" title="点此进入专题中心" target="_blank">专题中心</a><a href="http://www.szhgh.com/xuezhe/" title="点此进入学者专栏" target="_blank">学者专栏</a><a href="http://www.szhgh.com/e/member/cp/">会员中心</a><a href="http://www.szhgh.com/e/DoInfo/AddInfo.php?mid=10&enews=MAddInfo&classid=29">我要投稿</a></div>  
        <div class="clear"></div>
    </div>
    <div class="redlogo">
        <div class="logo"><a href="http://www.szhgh.com/" title="红歌会网首页"><img src="http://www.szhgh.com/skin/default/images/logo.png" /></a></div>
    </div>
</div>

            <!-- header end -->

            <!-- class navigation -->
            <div class="navwrap">
                <div id='js_controlclassid' classid='<?=$ecms_gr[classid]?>' class="navigation">
                    <? @sys_ShowClassByTemp('selfinfo',23,0,0);?>
                </div>
            </div>
            <!-- class navigation end -->
            
            <!-- main -->
            <div class="container">
                <div id="sidebar" class="sidebar">
                    <!-- 推荐文章 -->
                    <div class="section isgood">
                        <div class="section_header">
                            <strong>推荐文章</strong>
                        </div>
                        <ul class="section_content ">
                            <script src="http://www.szhgh.com/d/js/js/firsttitlePicNews.js"></script>
                        </ul>
                        <ul class="list">
                            <script src="http://www.szhgh.com/d/js/js/isgoodandfirsttitle.js"></script>
                        </ul>
                    </div>
                    
                    <div class="section">

                        <!-- 文章中心最新文章 -->
                        <div class="section_header">
                            <strong>最新文章</strong>
                        </div>
                        <ul class="section_content">
                            <? @sys_GetEcmsInfo(1,10,40,0,0,2,0,'newstime > UNIX_TIMESTAMP()-86400*7','newstime DESC');?> 
                        </ul>                            
                        
                         <div class="section" style="border: 0pt none; display: inline-block; width: 300px; height: 250px;">
<script type="text/javascript">
    /*300*250 创建于 2016/10/21*/
    var cpro_id = "u2794786";
</script>
<script type="text/javascript" src="http://cpro.baidustatic.com/cpro/ui/c.js"></script>
</div>

                        <!-- 点击排行与热评文章周月列表切换使用的js是使用jQuery简单封装的函数，放在custom.js文件中，函数名称为hotturn(selecter) -->
                        <!-- 点击排行 -->
                        <div id="hotclick" class="section hot">
                            <div class="section_header">
                                <strong><a title="热点文章">点击排行</a></strong>
                                <em id="side_tab_button">
                                    <a class="onhover">24小时</a>
                                    <a>一周</a>
                                    <a>一月</a>
                                </em>
                            </div>
                            <div id="side_tab_content" class="section_content">
                                <ul>
                                    <? @sys_GetEcmsInfo("select * from {$dbtbpre}ecms_article where newstime > UNIX_TIMESTAMP()-86400*1 order by onclick desc limit 8",8,42,0,24,2,0);?>
                                </ul>
                                <ul style='display: none;'>
                                    <? @sys_GetEcmsInfo("select * from {$dbtbpre}ecms_article where newstime > UNIX_TIMESTAMP()-86400*7 order by onclick desc limit 8",8,42,0,24,2,0);?>
                                </ul>
                                <ul style="display: none;">
                                    <? @sys_GetEcmsInfo("select * from {$dbtbpre}ecms_article where newstime > UNIX_TIMESTAMP()-86400*30 order by onclick desc limit 8",8,42,0,24,2,0);?>
                                </ul>                            
                            </div>
                        </div>

                        <!-- 评论排行 -->
                        <div id="hotcomment" class="section hot">
                            <div class="section_header">
                                <strong><a title="热评文章">评论排行</a></strong>
                                <em id="side_tab_button">
                                    <a class="onhover">24小时</a>
                                    <a>一周</a>
                                    <a>一月</a>
                                </em>
                            </div>
                            <div id="side_tab_content" class="section_content">
                                <ul>
                                    <? @sys_GetEcmsInfo("select * from {$dbtbpre}ecms_article where newstime > UNIX_TIMESTAMP()-86400*1 order by plnum desc limit 8",8,42,0,24,2,0);?>
                                </ul>
                                <ul style='display: none;'>
                                    <? @sys_GetEcmsInfo("select * from {$dbtbpre}ecms_article where newstime > UNIX_TIMESTAMP()-86400*7 order by plnum desc limit 8",8,42,0,24,2,0);?>
                                </ul>
                                <ul style="display: none;">
                                    <? @sys_GetEcmsInfo("select * from {$dbtbpre}ecms_article where newstime > UNIX_TIMESTAMP()-86400*30 order by plnum desc limit 8",8,42,0,24,2,0);?>
                                </ul>   
                            </div>
                        </div>
                    </div>
					<!-- 页面广告 -->
					<div class="section" >
<script type="text/javascript">
        document.write('<a style="display:none!important" id="tanx-a-mm_58070248_16916068_64530496"></a>');
        tanx_s = document.createElement("script");
        tanx_s.type = "text/javascript";
        tanx_s.charset = "gbk";
        tanx_s.id = "tanx-s-mm_58070248_16916068_64530496";
        tanx_s.async = true;
        tanx_s.src = "http://p.tanx.com/ex?i=mm_58070248_16916068_64530496";
        tanx_h = document.getElementsByTagName("head")[0];
        if(tanx_h)tanx_h.insertBefore(tanx_s,tanx_h.firstChild);
</script>
</div>
                </div>
                <div id="main" class="main">
                    <div class="position_cur">
                        <strong>当前位置：<? if (!$navinfor[parameter]){ ?><?=$grurl?><? } else { ?><a href="http://www.szhgh.com/index.html">首页</a>&nbsp;&gt;&nbsp;<a href="http://www.szhgh.com/Article/">文章中心</a>&nbsp;&gt;&nbsp;民意调查<? } ?></strong>
                    </div>
                    <div class="article_text">
                        <h1><?=$ecms_gr[title]?></h1>
                        <div class="info_text line1"><?=date('Y-m-d H:i:s',$ecms_gr[newstime])?>&nbsp;&nbsp;来源：<?if($navinfor['fromlink']){?><a href="<?=$ecms_gr[fromlink]?>" title="点击参考来源" target="_blank"><?=RepFieldtextNbsp(ehtmlspecialchars($ecms_gr[copyfrom]))?></a><?}else{?><?=RepFieldtextNbsp(ehtmlspecialchars($ecms_gr[copyfrom]))?><?}?>&nbsp;&nbsp;作者：<?=RepFieldtextNbsp(ehtmlspecialchars($ecms_gr[author]))?></div>
                        <div class="info_text line2">
                            <span class="left viewnum">点击：<script src="http://www.szhgh.com/e/public/ViewClick/?classid=<?=$ecms_gr[classid]?>&id=<?=$ecms_gr[id]?>&addclick=1"></script>&nbsp;&nbsp;&nbsp;评论：<b><script type="text/javascript" src="http://assets.changyan.sohu.com/upload/plugins/plugins.count.js">
</script>
			
<a href="#SOHUCS" id="changyan_count_unit"></a></b><a href="#SOHUCS" title="点击查看更多评论">（查看）</a></span>
                            <div class="right sharetools">
								<div class="bdsharebuttonbox" data-tag="share_1">
									<a href="#" class="bds_more" data-cmd="more"></a>
									<a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a>
									<a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a>
									<a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
									<a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a>
									<a href="#" class="bds_mshare" data-cmd="mshare" title="分享到一键分享"></a>
									<a href="#" class="bds_print" data-cmd="print" title="分享到打印"></a>
									<a class="bds_count" data-cmd="count"></a>
								</div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div id="js_newstext" class="newstext">
							<?=strstr($ecms_gr[newstext],'[!--empirenews.page--]')?'[!--newstext--]':$ecms_gr[newstext]?>
							<p style="text-align: center;">&nbsp;<a href="http://photo.weibo.com/2566813090/photos/detail/photo_id/4080908956211660/album_id/4080431183062515" style="text-decoration: none; outline: none; color: rgb(10, 140, 210); font-family: Tahoma, Helvetica, Arial, sans-serif; line-height: 18px;"><img src="http://wx2.sinaimg.cn/mw690/98fe75a2ly1fd8n14dcv8j209k09kdgs.jpg" style="border: 0px; vertical-align: bottom; background: url(http://img.t.sinajs.cn/t4/appstyle/photo/images/common/loading.gif?id=1386663527255) 50% 50% no-repeat;" width="250" height="250" alt="" data-bd-imgshare-binded="1"></a></p>
							<center class="page">[!--page.url--]</center>
						</div>

                        

                        <script type="text/javascript">
                            $(function(){
                                $("#js_newstext img").Resize({box:"#js_newstext"});
                                $("#js_newstext embed").Resize({box:"#js_newstext"});
                            });
                        </script>
                        
                        <?
                            if($navinfor[parameter]){
                                $vote_r = explode(",",$navinfor[parameter]);
                                $vote_num = count($vote_r);

                        ?>
                        <div class="section_header_articlecontent a_vote"><strong>民意投票：</strong></div>
                        <div class="article_vote">

                            <ul class="m-list">
                            <?
                                if($vote_num>0){
                                    foreach ($vote_r as $key => $value) {
                            ?>
                                <li>
                                    <script type="text/javascript" src="http://www.szhgh.com/d/js/vote/vote<?=$value?>.js"></script>
                                </li>
                            <?
                                    }
                                }
                            ?>
                            </ul>  
                        </div>
                        <?
                            }
                        ?>
                        
                        <!-- 分享按钮 -->
                        <div class="share">
                            <div class="sharebox right">
								<div class="bdsharebuttonbox" data-tag="share_2">
									<a href="#" class="bds_more" data-cmd="more"></a>
									<a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a>
									<a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a>
									<a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
									<a href="#" class="bds_mshare" data-cmd="mshare" title="分享到一键分享"></a>
									<a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a>
									<a href="#" class="bds_print" data-cmd="print" title="分享到打印"></a>
									<a class="bds_count" data-cmd="count"></a>
								</div>
								<script>
									window._bd_share_config={
										"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"0"},
										"share":[{"tag" : "share_1","bdSize" : 16},{"tag" : "share_2","bdSize" : 32}],
										"image":[{"viewList":["weixin","qzone","tsina","tqq","mshare","print"],"viewText":"分享到：","viewSize":"16"},{"viewList":["weixin","qzone","tsina","mshare","tqq","print"],"viewText":"分享到：","viewSize":"32"}],
									};
									with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
								</script>
                            </div>
                            <div class="clear"></div>
                        </div>
                        

                        <!-- 相关文章 -->
                        <div class="section_header_articlecontent"><strong>相关文章 </strong></div>
                        <ul class="section_content otherlink">
                            <?=GetKeyboard($ecms_gr[keyboard],$ecms_gr[keyid],$ecms_gr[classid],$ecms_gr[id],$class_r[$ecms_gr[classid]][link_num])?>
                        </ul>
                                    <?
                            if(!$navinfor[parameter]){
                        ?>
                            <!-- 心情 -->
                            <div class="section_content">
                                <iframe id="mood_frame" width="100%" height="212" src="http://www.szhgh.com/e/extend/mood/?classid=<?=$ecms_gr[classid]?>&id=<?=$ecms_gr[id]?>" marginWidth=0 marginHeight=0 frameborder="0" scrolling="no"></iframe>
                            </div>   
                            
                        <?
                            }
                        ?>



                        
<!--PC版-->
<div id="SOHUCS" sid="<?=$ecms_gr[id]?>" ></div>
<script charset="utf-8" type="text/javascript" src="http://changyan.sohu.com/upload/changyan.js" ></script>
<script type="text/javascript">
window.changyan.api.config({
appid: 'cysG16cIx',
conf: 'prod_eb841ff6ba533cd3f322c05e9252c602'
});
</script>


                    </div>
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
                <strong><a href="http://www.szhgh.com/Article/news/" title="资讯中心" target="_blank">资讯中心</a></strong>
                <ul class='specialstyle'>
                    <li><a href="http://www.szhgh.com/gundong/" title="滚动" target="_blank">滚动</a></li>
                    <li><a href="http://www.szhgh.com/Article/news/politics/" title="时政" target="_blank">时政</a></li>
                    <li><a href="http://www.szhgh.com/Article/news/world/" title="国际" target="_blank">国际</a></li>
                    <li><a href="http://www.szhgh.com/Article/news/leaders/" title="高层" target="_blank">高层</a></li>
                    <li><a href="http://www.szhgh.com/Article/news/gangaotai/" title="港澳台" target="_blank">港澳台</a></li>
                    <li><a href="http://www.szhgh.com/Article/news/society/" title="社会" target="_blank">社会</a></li>
                    <li><a href="http://www.szhgh.com/Article/news/fanfu/" title="反腐" target="_blank">反腐</a></li>
                    <li><a href="http://www.szhgh.com/Article/news/chujian/" title="除奸" target="_blank">除奸</a></li>  
                    <div class="clear"></div>
                </ul>
                <div class="clear"></div>
            </li>
            <li class="mapli">
                <strong><a href="http://www.szhgh.com/Article/opinion/" title="纵论天下" target="_blank">纵论天下</a></strong>
                <ul>
                    <li><a href="http://www.szhgh.com/Article/opinion/wp/" title="红歌会网评" target="_blank">红歌会网评</a></li>
                    <li><a href="http://www.szhgh.com/Article/opinion/xuezhe/" title="学者观点" target="_blank">学者观点</a></li>
                    <li><a href="http://www.szhgh.com/Article/opinion/zatan/" title=" 网友杂谈" target="_blank"> 网友杂谈</a></li>
                    <li><a href="http://www.szhgh.com/Article/opinion/weibo/" title="微博天下" target="_blank">微博天下</a></li>
                </ul>
                <div class="clear"></div>
            </li>               
            <li class="mapli">
                <strong><a href="http://www.szhgh.com/Article/red-china/" title="红色中国" target="_blank">红色中国</a></strong>
                <ul>
                    <li><a href="http://www.szhgh.com/Article/red-china/mzd/" title=" 毛泽东" target="_blank"> 毛泽东</a></li>
                    <li><a href="http://www.szhgh.com/Article/red-china/ideal/" title=" 理想园地" target="_blank"> 理想园地</a></li>
                    <li><a href="http://www.szhgh.com/Article/red-china/redman/" title="红色人物" target="_blank">红色人物</a></li>
                    <li><a href="http://www.szhgh.com/Article/red-china/tour/" title="红色旅游" target="_blank">红色旅游</a></li>
                </ul>
                <div class="clear"></div>
            </li>
            <li class="mapli">
                <strong><a href="http://www.szhgh.com/Article/cdjc/" title="唱读讲传" target="_blank">唱读讲传</a></strong>
                <ul>
                    <li><a href="http://www.szhgh.com/Article/cdjc/hongge/" title="唱红歌" target="_blank">唱红歌</a></li>
                    <li><a href="http://www.szhgh.com/Article/cdjc/jingdian/" title="读经典" target="_blank">读经典</a></li>
                    <li><a href="http://www.szhgh.com/Article/cdjc/gushi/" title="讲故事" target="_blank">讲故事</a></li>
                    <li><a href="http://www.szhgh.com/Article/cdjc/zhengqi/" title="传正气" target="_blank">传正气</a></li>
                </ul>
                <div class="clear"></div>
            </li>                 
            <li class="mapli">
                <strong><a href="http://www.szhgh.com/Article/health/" title="人民健康" target="_blank">人民健康</a></strong>
                <ul>
                    <li><a href="http://www.szhgh.com/Article/health/zjy/" title="转基因" target="_blank">转基因</a></li>
                    <li><a href="http://www.szhgh.com/Article/health/zhongyi/" title="中医" target="_blank">中医</a></li>
                    <li><a href="http://www.szhgh.com/Article/health/baojian/" title="保健" target="_blank">保健</a></li>
                    <li><a href="http://www.szhgh.com/Article/health/food/" title="食品安全" target="_blank">食品安全</a></li>
                </ul>
                <div class="clear"></div>
            </li>
             <li class="mapli">
                <strong><a href="http://www.szhgh.com/Article/gnzs/" title="工农之声" target="_blank">工农之声</a></strong>
                <ul>
                    <li><a href="http://www.szhgh.com/Article/gnzs/farmer/" title="农民之声" target="_blank">农民之声</a></li>
                    <li><a href="http://www.szhgh.com/Article/gnzs/worker/" title="工友之家" target="_blank">工友之家</a></li>
                    <li><a href="http://www.szhgh.com/Article/gnzs/gongyi/" title="公益行动" target="_blank">公益行动</a></li>
                </ul>
                <div class="clear"></div>
            </li>               
            <li class="mapli">
                <strong><a href="http://www.szhgh.com/Article/wsds/" title="文史·读书" target="_blank">文史读书</a></strong>
                <ul class="specialstyle">
                    <li><a href="http://www.szhgh.com/Article/wsds/wenyi/" title="文艺" target="_blank">文艺</a></li>
                    <li><a href="http://www.szhgh.com/Article/wsds/culture/" title="文化" target="_blank">文化</a></li>
                    <li><a href="http://www.szhgh.com/Article/wsds/history/" title="历史" target="_blank">历史</a></li>
                    <li><a href="http://www.szhgh.com/Article/wsds/read/" title=" 读书" target="_blank"> 读书</a></li>
                    <li><a href="http://www.szhgh.com/Article/wsds/youth/" title="青年" target="_blank">青年</a></li>
                    <div class="clear"></div>
                </ul>
                <div class="clear"></div>
            </li>
            <li class="mapli">
                <strong><a href="http://www.szhgh.com/Article/thirdworld/" title="第三世界" target="_blank">第三世界</a></strong>
                <ul>
                    <li><a href="http://www.szhgh.com/Article/thirdworld/korea/" title="朝鲜" target="_blank">朝鲜</a></li>
                    <li><a href="http://www.szhgh.com/Article/thirdworld/cuba/" title="古巴" target="_blank">古巴</a></li>
                    <li><a href="http://www.szhgh.com/Article/thirdworld/latin-america/" title="拉美" target="_blank">拉美</a></li>
                    <li><a href="http://www.szhgh.com/Article/thirdworld/africa/" title="非洲" target="_blank">非洲</a></li>
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
                    <a href="http://www.szhgh.com/" title="红歌会网" target="_blank">红歌会网</a>
                    | <a href="http://www.szhgh.com/html/sitemap.html" title="网站地图" target="_blank">网站地图</a>
                    | <a href="http://www.szhgh.com/html/rank.html" title="排行榜" target="_blank">排行榜</a>
                    | <a href="http://www.szhgh.com/Article/notice/20257.html" title="联系我们" target="_blank">联系我们</a>
                    | <a href="http://www.szhgh.com/Article/opinion/zatan/13968.html" title="在线提意见" target="_blank">在线提意见</a>
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
                <a class="rss" href="http://www.szhgh.com/e/web/?type=rss2" title="欢迎订阅红歌会网" target="_blank"></a>
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

<script src="http://www.szhgh.com/skin/default/js/jquery.leanModal.min.js" type="text/javascript"></script>
<div id="loginmodal" class="loginmodal" style="display:none;">
    <div class="modaletools"><a class="hidemodal" title="点此关闭">×</a></div>
    <form class="clearfix" name=login method=post action="http://www.szhgh.com/e/member/doaction.php">
        <div class="login left">
            <strong>会员登录</strong>
            <input type=hidden name=enews value=login>
            <input type=hidden name=ecmsfrom value=9>
            <div id="username" class="txtfield username"><input name="username" type="text" size="16" /></div>
            <div id="password" class="txtfield password"><input name="password" type="password" size="16" /></div>
            <div class="forgetmsg"><a href="/e/member/GetPassword/" title="点此取回密码" target="_blank">忘记密码？</a></div>
            <div class="poploginex"><script type="text/javascript">document.write('<script  type="text/javascript" src="http://www.szhgh.com/e/memberconnect/panjs.php?type=login&dclass=login&t='+Math.random()+'"><'+'/script>');</script></div>
            <input type="submit" name="Submit" value="登陆" class="inputSub flatbtn-blu" />
        </div>
        <div class="reg right">
            <div class="regmsg"><span>还不是会员？</span></div>
            <input type="button" name="Submit2" value="立即注册" class="regbutton" onclick="window.open('http://www.szhgh.com/e/member/register/');" />
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

        <!--浮动层 -->
                <script type="text/javascript">
                $(document).ready(function(){
                        $('#welcome').fadeTo(2000, 1).delay(2000).animate({
                            opacity: 0,
                            marginTop: '-=200'
                        },
                        1000,
                        function() {
                            $('#welcome').hide();
                        });
                        $(window).scroll(function() {
                            if ($(window).scrollTop() > 50) {
                                $('#jump li:eq(0)').fadeIn(800);
                            } else {
                                $('#jump li:eq(0)').fadeOut(800);
                            }
                        });
                        $("#totop").click(function() {
                            $('body,html').animate({
                                scrollTop: 0
                            },
                            1000);
                            return false;
                        });
                });
        </script>

        <ul id="jump">
            <li style="display:none;height:50px;padding-top:450px;"><a id="totop" title="返回顶部" href="#top"></a></li>
            <li>
                    <div id="EWM">
                        <script src="http://www.szhgh.com/d/js/acmsd/thea9.js"></script>
                        <div class="scanning"><a href="http://www.szhgh.com/e/public/ClickAd?adid=9" title="点击查看" target="_blank">扫码订阅红歌会网微信&nbsp;&nbsp;&nbsp;&nbsp;</a></div>
                    </div>
            </li>
            <script>
                function showEWM(){
                    document.getElementById("EWM").style.display = 'block';
                }
                function hideEWM(){
                    document.getElementById("EWM").style.display = 'none';
                }
            </script>
        </ul>
           
            <?='<script src="'.$public_r[newsurl].'e/public/onclick/?enews=donews&classid='.$ecms_gr[classid].'&id='.$ecms_gr[id].'"></script>'?>

    </body>
</html>