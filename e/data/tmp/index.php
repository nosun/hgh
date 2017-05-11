<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:wb="http://open.weibo.com/wb" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>红歌会网 - 唱响红歌，弘扬正气！</title>
        <meta name="keywords" content="红歌会,红歌会网,红色文化,社会主义,毛泽东" />
        <meta name="description" content="红歌会网基于一贯的人民立场，高举爱国主义旗帜，唱响红歌，弘扬正气，宣传毛泽东思想和红色文化，反映老百姓的呼声，推动社会公平正义，维护国家和民族根本利益。" />
        <link rel="shortcut icon" href="http://www.szhgh.com/skin/default/images/favicon.ico" /> 
        <link href="http://www.szhgh.com/skin/default/css/base.css" rel="stylesheet" type="text/css" />
        <link type="text/css" href="http://www.szhgh.com/skin/default/css/skitter.styles.css" media="all" rel="stylesheet" />
        <script type="text/javascript" src="http://www.szhgh.com/skin/default/js/jquery-1.8.2.min.js"></script>
        <script type="text/javascript" src="http://www.szhgh.com/skin/default/js/myfocus-2.0.4.min.js"></script>
        <script type="text/javascript" src="http://www.szhgh.com/skin/default/js/mF_tbhuabao_forindex.js"></script>
        <script type="text/javascript" src="http://www.szhgh.com/skin/default/js/custom.js"></script>
        <script type="text/javascript" src="http://www.szhgh.com/skin/default/js/jquery.skitter.min.js"></script>
        <script src="http://www.szhgh.com/skin/default/js/jquery.cookie.js" type="text/javascript"></script>
        <meta name="baidu-site-verification" content="MmCIh7zIOS" />
        <meta name="keywords" content="keyword1,keyword2, _rVBxWaZ6RxiAd8o7-UR2M_eqhk" />
        <meta property="qc:admins" content="13773660666320706375" />
        <meta property="wb:webmaster" content="585a282dc9c3f9b7" />
    </head>
    <body class="index">

        <!-- header -->
        <div class="header">
    <div class="toolbar clearfix">
        <div class="top_menu left"><a id="j-2app" class='first' href="http://m.szhgh.com/" title="点此进入红歌会手机版">手机版</a><a class='first' href="http://hao.szhgh.com/" title="点此进入红歌会网址导航" target="_blank">网址导航</a><a class='first' href="http://www.szhgh.com/html/rank.html" title="点此进入排行榜" target="_blank">排行榜</a><a href="http://www.szhgh.com/special" title="点此进入专题中心" target="_blank">专题中心</a><a href="http://www.szhgh.com/xuezhe/" title="点此进入学者专栏" target="_blank">学者专栏</a><a href="http://www.szhgh.com/e/member/cp/">会员中心</a><a href="http://www.szhgh.com/e/DoInfo/AddInfo.php?mid=10&enews=MAddInfo&classid=29">我要投稿</a></div>  
        <div class="right">
            <script type="text/javascript">(function(){document.write(unescape('%3Cdiv id="bdcs"%3E%3C/div%3E'));var bdcs = document.createElement('script');bdcs.type = 'text/javascript';bdcs.async = true;bdcs.src = 'http://znsv.baidu.com/customer_search/api/js?sid=6166758973591541142' + '&plate_url=' + encodeURIComponent(window.location.href) + '&t=' + Math.ceil(new Date()/3600000);var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(bdcs, s);})();</script>
            <div class="account right">
                <script>
                    document.write('<script src="http://www.szhgh.com/e/member/login/loginjs.php?t=' + Math.random() + '"><' + '/script>');
                </script>
            </div>
        </div>
    </div>
    <div class="redlogo">
        <div class="logo"><a href="http://www.szhgh.com/" title="红歌会网首页"><img src="http://www.szhgh.com/skin/default/images/logo.png" /></a></div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function ($) {
        $.cookie('RSTestCookie', 1, {expires: 1, path: '/'});
        $opencookie = $.cookie('RSTestCookie');
        if ($opencookie) {
            $("#j-2app").on("click", function () {
                $.cookie('RSapp2pc', 0, {expires: 30, path: '/'});
                location.href = "<?=$public_r[newsurl]?>m";
            });
        } else {
            location.href = "<?=$public_r[newsurl]?>m";
        }
    });
</script>


        <!-- header end -->
        
        <!-- navigation -->
        <div class="menu">
            <ul class="menuul">
                <li class="menuli">
                    <strong><a href="http://www.szhgh.com/Article/red-china/" title="红色中国" target="_blank">红色中国</a></strong>
                    <ul>
                        <li><a href="http://mzd.szhgh.com" title=" 毛泽东" target="_blank"> 毛泽东</a></li>
                        <li><a href="http://www.szhgh.com/Article/red-china/ideal/" title=" 理想园地" target="_blank"> 理想园地</a></li>
                        <li><a href="http://www.szhgh.com/Article/red-china/redman/" title="红色人物" target="_blank">红色人物</a></li>
                        <li><a href="http://www.szhgh.com/Article/red-china/tour/" title="红色旅游" target="_blank">红色旅游</a></li>
                    </ul>
                    <div class="clear"></div>
                </li>
                 <li class="menuli">
                    <strong><a href="http://www.szhgh.com/Article/opinion/" title="纵论天下" target="_blank">纵论天下</a></strong>
                    <ul>
                        <li><a href="http://www.szhgh.com/Article/opinion/wp/" title="红歌会网评" target="_blank">红歌会网评</a></li>
                        <li><a href="http://www.szhgh.com/Article/opinion/xuezhe/" title="学者观点" target="_blank">学者观点</a></li>
                        <li><a href="http://www.szhgh.com/Article/opinion/zatan" title=" 网友杂谈" target="_blank"> 网友杂谈</a></li>
                        <li><a href="http://www.szhgh.com/Article/opinion/weibo/" title="微博天下" target="_blank">微博天下</a></li>
                    </ul>
                    <div class="clear"></div>
                </li>
                <li class="menuli">
                    <strong><a href="http://www.szhgh.com/Article/news/" title="资讯中心" target="_blank">资讯中心</a></strong>
                    <ul class="specialstyle clearfix">
                        <li><a href="http://www.szhgh.com/gundong/" title="滚动" target="_blank">滚动</a></li>
                        <li><a href="http://www.szhgh.com/Article/news/society/" title="社会" target="_blank">社会</a></li>
                        <li><a href="http://www.szhgh.com/Article/news/comments/" title="时政" target="_blank">时评</a></li>
                        <li><a href="http://www.szhgh.com/Article/news/finance/" title="财经" target="_blank">财经</a></li>
                        <li><a href="http://www.szhgh.com/Article/news/world/" title="国际" target="_blank">国际</a></li>
                        <li><a href="http://www.szhgh.com/Article/news/fanfu/" title="反腐" target="_blank">反腐</a></li>
                        <li><a href="http://www.szhgh.com/Article/news/leaders/" title="高层" target="_blank">高层</a></li>
                        <li><a href="http://www.szhgh.com/Article/news/photo/" title="图说" target="_blank">图说</a></li>
                    </ul>
                    <div class="clear"></div>
                </li>
                <li class="menuli">
                    <strong><a href="http://www.szhgh.com/Article/cdjc/" title="唱读讲传" target="_blank">唱读讲传</a></strong>
                    <ul>
                        <li><a href="http://www.szhgh.com/Article/cdjc/hongge/" title="唱红歌" target="_blank">唱红歌</a></li>
                        <li><a href="http://www.szhgh.com/Article/cdjc/jingdian/" title="读经典" target="_blank">读经典</a></li>
                        <li><a href="http://www.szhgh.com/Article/cdjc/gushi/" title="讲故事" target="_blank">讲故事</a></li>
                        <li><a href="http://www.szhgh.com/Article/cdjc/zhengqi/" title="传正气" target="_blank">传正气</a></li>
                    </ul>
                    <div class="clear"></div>
                </li>                 
                <li class="menuli">
                    <strong><a href="http://www.szhgh.com/Article/health/" title="人民健康" target="_blank">人民健康</a></strong>
                    <ul>
                        <li><a href="http://www.szhgh.com/Article/health/zjy/" title="转基因" target="_blank">转基因</a></li>
                        <li><a href="http://www.szhgh.com/Article/health/zhongyi/" title="中医" target="_blank">中医</a></li>
                        <li><a href="http://www.szhgh.com/Article/health/baojian/" title="保健" target="_blank">保健</a></li>
                        <li><a href="http://www.szhgh.com/Article/health/food/" title="食品安全" target="_blank">食品安全</a></li>
                    </ul>
                    <div class="clear"></div>
                </li>
                 <li class="menuli">
                    <strong><a href="http://www.szhgh.com/Article/gnzs/" title="工农家园" target="_blank">工农家园</a></strong>
                    <ul>
                        <li><a href="http://www.szhgh.com/Article/gnzs/farmer/" title="农民关注" target="_blank">农民关注</a></li>
                        <li class="center"><a href="http://www.szhgh.com/Article/gnzs/worker/" title="工友之家" target="_blank">工友之家</a></li>
                        <li><a href="http://www.szhgh.com/Article/gnzs/gongyi/" title="公益慈善" target="_blank">公益慈善</a></li>
                    </ul>
                    <div class="clear"></div>
                </li>               
                <li class="menuli">
                    <strong><a href="http://www.szhgh.com/Article/wsds/" title="文史·读书" target="_blank">文史读书</a></strong>
                    <ul>
                        <li><a href="http://www.szhgh.com/Article/wsds/wenyi/" title="文艺" target="_blank">文艺</a></li>
                        <li><a href="http://www.szhgh.com/Article/wsds/culture/" title="文化" target="_blank">文化</a></li>
                        <li><a href="http://www.szhgh.com/Article/wsds/history/" title="历史" target="_blank">历史</a></li>
                        <li><a href="http://www.szhgh.com/Article/wsds/read/" title=" 读书" target="_blank"> 读书</a></li>
                    </ul>
                    <div class="clear"></div>
                </li>
                 <li class="menuli last">
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
        <!-- navigation end -->
        
        <!-- recommends -->
        <div class="recommends">
            <ul>
                <li class="pleft left">


                   <a class="left" href="http://www.szhgh.com/Article/opinion/xuezhe/2017-05-03/136925.html" title="不同寻常的站队，诡异的信号"" target="_blank"><img src="http://wx2.sinaimg.cn/mw690/98fe75a2ly1ff87pwbtofj20n20eotq7.jpg" /></a>
                    <div class="right">
                        <h2><a href="http://www.szhgh.com/Article/opinion/xuezhe/2017-05-03/136925.html" title="“不同寻常的站队，诡异的信号””" target="_blank">不同寻常的站队，诡异的信号</a></h2>
                        <p><a href="http://www.szhgh.com/Article/opinion/xuezhe/2017-05-03/136925.html" title="点击阅读全文" target="_blank">方方这部问题严重的小说《软埋》，却引来官媒站台，莫名其妙</a></p>


                    </div>
                    <div class="clear"></div>
                </li> 
                
                <li class="pcenter left">

                    <a class="left" href="http://www.szhgh.com/Article/opinion/xuezhe/2017-05-06/137188.html" title="“何新：对朝政策似有必要反思"" target="_blank"><img src="http://wx3.sinaimg.cn/mw690/98fe75a2gy1ff3lgutgmoj20hq0blt9o.jpg" /></a>
                    <div class="right">
                        <h2><a href="http://www.szhgh.com/Article/opinion/xuezhe/2017-05-06/137188.html" title="“何新：对朝政策似有必要反思" target="_blank">何新：对朝政策该反思了</a></h2>
                        <p><a href="http://www.szhgh.com/Article/opinion/xuezhe/2017-05-06/137188.html" title="点击阅读全文" target="_blank">朝鲜半岛风云迭起，中朝关系面临巨大挑战，形势十分不利。根源何在？</a></p>


                    </div>
                    <div class="clear"></div>
                </li>  
                
                    <li class="pright right">

                <a class="left" href="http://www.szhgh.com/Article/wsds/history/201411/68962.html" title="真相：当年的大飞机怎么下马的？" target="_blank"><img src="http://img3.wyzxwk.com/p/2014/11/fc9cab39e2dae1112ea63f9e24148974.jpg" /></a>
                    <div class="right">
                        <h2><a href="http://www.szhgh.com/Article/wsds/history/201411/68962.html" title="真相：当年的大飞机怎么下马的？" target="_blank">真相：当年的大飞机怎么下马的？</a></h2>
                        <p><a href="http://www.szhgh.com/Article/wsds/history/201411/68962.html" title="点击阅读全文" target="_blank">运十老工人在高校演讲中披露内情，当年“运十”下马成了永远的痛。</a></p>
   


                    </div>
                    <div class="clear"></div>
                    </li>

                <div class="clear"></div>
            </ul>
        </div>
        <!-- recommends end -->
        
        <!-- main -->
        <div class="container">
            <div class="sidebar">

                <!-- 侧边广告4 -->
                <div class="section" style="margin-bottom:10px">
                    <script src="http://www.szhgh.com/d/js/acmsd/thea10.js"></script>
                </div>
                
                <!-- 视频 -->
                <div class="section">
                    <div class="section_header">
                        <strong><a title="视频文章" href="http://www.szhgh.com/html/video" target="_blank">视频</a></strong>
                        <div class="clear"></div>
                    </div>
                    <div class="section_content">
                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(1,1,0,1,'ttid=1','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <h3><a href="<?=$bqsr[titleurl]?>" title="<?=$bqr[title]?>" target="_blank"><?=$bqr[title]?></a></h3>
                            <div class='summary'>
                                <a class="left" href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr[titlepic],83,62,1,'')?>" /></a>
                                <div class="right">
                                    <p><a href="<?=$bqsr['titleurl']?>" title="点击阅读全文" target="_blank"><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),54))?></a></p>
                                </div>
                                <div class="clear"></div>
                            </div>                        
                        <?php
}
}
?>
                        <ul>
                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(1,8,0,0,'ttid=1','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <li><a href="<?=$bqsr[titleurl]?>" title="<?=$bqr[title]?>" target="_blank"><?=esub($bqr[title],34)?></a></li>
                            <?php
}
}
?>
                        </ul>
                    </div>
                </div>

                <!-- 侧边广告1 -->
                <div class="section" style="margin-bottom:10px">
                    <script src="http://www.szhgh.com/d/js/acmsd/thea3.js"></script>
                </div>
                
                <!-- 周刊 -->
                <div class="section">
                    <div class="section_header">
                        <strong><a title="红歌会网周刊" href="http://www.szhgh.com//weekly" target="_blank">周刊</a></strong>
                        <div class="clear"></div>
                    </div>
                    <div class="section_content">
                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq("select * from {$dbtbpre}enewszt where zcid=1 and showzt=0 order by addtime DESC limit 1",1,24,0,'','addtime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <?
                            $newss_ztid=$bqr[ztid];
							$weekly_url = $public_r['newsurl'].$bqr['ztpath'];
							$weekly_ztname = $bqr['ztname'];
                            ?>
                        <?php
}
}
?>
						
						<?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsIndexLoopBq("select * from {$dbtbpre}enewsztinfo a inner join {$dbtbpre}ecms_article b on a.id=b.id where a.ztid=$newss_ztid and a.isgood=9 and ispic=1 order by b.newstime desc limit 1",1,11,'','','');
if($ecms_bq_sql){
while($indexbqr=$empire->fetch($ecms_bq_sql)){
if(empty($class_r[$indexbqr['classid']]['tbname'])){continue;}
$bqr=$empire->fetch1("select * from {$dbtbpre}ecms_".$class_r[$indexbqr['classid']]['tbname']." where id='$indexbqr[id]'");
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
							
                            <h3><a href="<?=$weekly_url?>" title="<?=$weekly_ztname?>" target="_blank"><?=$weekly_ztname?></a></h3>
                            <div class='summary'>
                                <a class="left" href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><img class="weekly_pic" src="<?=$bqr[titlepic]?>" /></a>
                                <div class="right">
                                    <p><a href="<?=$bqsr['titleurl']?>" title="点击阅读全文" target="_blank"><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),50))?></a></p>
                                </div>
                                <div class="clear"></div>
                            </div>			
						<?php
}
}
?>
						
                        <ul>
                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq("select * from {$dbtbpre}enewszt where ztid<$newss_ztid and zcid=1 and showzt=0 order by addtime DESC limit 3",3,24,0,'','addtime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <li><a href="http://www.szhgh.com/<?=$bqr['ztpath']?>" title="<?=$bqr['ztname']?>" target="_blank"><?=esub($bqr['ztname'],34)?></a></li>
                            <?php
}
}
?>
                        </ul>
                    </div>
                </div>

                <!-- 微博天下 -->
                <div class="section">
                    <div class="section_header">
                        <strong><a href="http://www.szhgh.com/Article/opinion/weibo/" title="微博看天下" target="_blank">微博天下</a></strong>
                        <div class="clear"></div>
                    </div>
                    <div class="section_content">
                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(51,1,0,1,'istop>0 or firsttitle>0 or isgood>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <h3><a href="<?=$bqsr[titleurl]?>" title="<?=$bqr[title]?>" target="_blank"><?=$bqr[title]?></a></h3>
                            <div class='summary'>
                                <a class="left" href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr[titlepic],83,62,1,'')?>" /></a>
                                <div class="right">
                                    <p><a href="<?=$bqsr['titleurl']?>" title="点击阅读全文" target="_blank"><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),54))?></a></p>
                                </div>
                                <div class="clear"></div>
                            </div>                        
                        <?php
}
}
?>
                        <ul>
                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(51,10,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <li><a href="<?=$bqsr[titleurl]?>" title="<?=$bqr[title]?>" target="_blank"><?=esub($bqr[title],34)?></a></li>
                            <?php
}
}
?>
                        </ul>
                    </div>
                </div>
                

                <!-- 反腐除奸 -->
                <div id='chaos' class="section classtab">
                    <div class="section_header">
                        <strong id="side_tab_button">
                            <a class="onhover" href="http://www.szhgh.com/Article/news/fanfu/" title="反腐" target="_blank">反腐</a>
                            <a href="http://www.szhgh.com/Article/news/chujian/" title="除奸" target="_blank">除奸</a>
                        </strong>
                        <div class="clear"></div>
                    </div>
                    <div id="side_tab_content" class="section_content">
                        <ul>
                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(44,1,0,1,'istop>0 or firsttitle>0 or isgood>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <li class="pictext">
                                <h3><a href="<?=$bqsr[titleurl]?>" title="<?=$bqr[title]?>" target="_blank"><?=$bqr[title]?></a></h3>
                                <div class='summary'>
                                    <a class="left" href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr[titlepic],83,62,1,'')?>" /></a>
                                    <div class="right">
                                        <p><a href="<?=$bqsr['titleurl']?>" title="点击阅读全文" target="_blank"><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),54))?></a></p>
                                    </div>
                                    <div class="clear"></div>
                                </div>                                 
                            </li>
                            <?php
}
}
?>
                            
                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(44,9,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <li><a href="<?=$bqsr[titleurl]?>" title="<?=$bqr[title]?>" target="_blank"><?=esub($bqr[title],34)?></a></li>
                            <?php
}
}
?>
                        </ul>
                        <ul style='display: none;'>
                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(45,1,0,1,'istop>0 or firsttitle>0 or isgood>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <li class="pictext">
                                <h3><a href="<?=$bqsr[titleurl]?>" title="<?=$bqr[title]?>" target="_blank"><?=$bqr[title]?></a></h3>
                                <div class='summary'>
                                    <a class="left" href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr[titlepic],83,62,1,'')?>" /></a>
                                    <div class="right">
                                        <p><a href="<?=$bqsr['titleurl']?>" title="点击阅读全文" target="_blank"><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),54))?></a></p>
                                    </div>
                                    <div class="clear"></div>
                                </div>                                 
                            </li>
                            <?php
}
}
?>
                            
                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(45,10,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <li><a href="<?=$bqsr[titleurl]?>" title="<?=$bqr[title]?>" target="_blank"><?=esub($bqr[title],34)?></a></li>
                            <?php
}
}
?>
                        </ul>                        
                    </div>
                </div>

                <!-- 侧边广告2 -->
                <div class="section" style="margin-bottom:10px">
                    <script src="http://www.szhgh.com/d/js/acmsd/thea4.js"></script>
                </div>
                
                <!-- 学者观点 -->
                <div class="section">
                    <div class="section_header">
                        <strong><a href="http://www.szhgh.com/Article/opinion/xuezhe/" title="学者观点" target="_blank">学者观点</a></strong>
                        <div class="clear"></div>
                    </div>
                    <div class="author_section_content">
                        <ul>
                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(49,2,0,0,'firsttitle=3','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>

                            <li>
                                    <h3><a href="<?=$bqsr[titleurl]?>" title="<?=$bqr[title]?>" target="_blank"><?=$bqr[title]?></a></h3>
                                    <?
                                        $author=(string)$bqr[author];
                                        $author_r=$empire->fetch1("select * from {$dbtbpre}ecms_author where title='".$author."'");
                                        if($author_r){
                                    ?>                                        
                                    <div class='summary'>
                                        <a class="left" href="<?=$author_r['titleurl']?>" title="<?=$author_r['title']?>" target="_blank"><img src="<?=sys_ResizeImg($author_r[titlepic],83,83,1,'')?>" /></a>
                                        <div class='right'>
                                            <div class="authorname"><a href="<?=$author_r['titleurl']?>" title="" target="_blank"><?=$author_r[title]?></a></div>
                                            <div class="smalltext"><p><a href="<?=$author_r['titleurl']?>" title="点击查看详细介绍" target="_blank"><?=htmlspecialchars_decode(trim(esub(strip_tags($author_r['smalltext']),56)))?></a></p></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div> 
                                    <?
                                        }
                                    ?>
                                    <div class='smalltext_summary'>
                                        <p><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),134))?><a class="readall" href="<?=$bqsr['titleurl']?>" title="点击阅读全文" target="_blank">[全文]</a></p>
                                    </div>                                   
                                </li>
                            <?php
}
}
?>
                        </ul>
                    </div>
                </div>

                <!-- 侧边广告3 -->
                <div class="section" style="margin-bottom:10px">
                    <script src="http://www.szhgh.com/d/js/acmsd/thea5.js"></script>
                </div>
                
                <!-- 网友妙论 -->
                <div class="section">
                    <div class="section_header">
                        <strong><a href="http://www.szhgh.com/Article/opinion/zatan/" title="网友妙论" target="_blank">网友妙论</a></strong>
                        <div class="clear"></div>
                    </div>
                    <div class="netizens_section_content">
                        <ul>
                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(50,2,0,1,'firsttitle=3','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                <li>
                                    <h3><a href="<?=$bqsr[titleurl]?>" title="<?=$bqr[title]?>" target="_blank"><?=$bqr[title]?></a></h3>
                                    <div class='summary'>
                                        <a class="left" href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr[titlepic],83,62,1,'')?>" /></a>
                                        <div class="netizens_smalltext">
                                            <p><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),130))?><a class="readall" href="<?=$bqsr['titleurl']?>" title="点击阅读全文" target="_blank">[全文]</a></p>
                                        </div>
                                        <div class="clear"></div>
                                    </div>                                  
                                </li>
                            <?php
}
}
?>
                        </ul>
                    </div>
                </div>
                   
            </div>
            <div class="main">
                <div class="top_module module">
                    <div id="statetab" class="right section tabbox row1">
                        <ul class="tabbtn">
                            <li class="current first clearfix">
                                <span class="left"><a href="http://www.szhgh.com/Article/news/" title="资讯中心" target="_blank">要闻</a>·<a href="http://www.szhgh.com/Article/opinion/" title="纵论天下" target="_blank">观点</a></span>
                                <span class="customlistnav right">
                                    <a target="_blank" title="点此查看最新文章" href="http://www.szhgh.com/html/news/">最新</a><a target="_blank" title="点此查看头条文章" href="http://www.szhgh.com/html/firsttitle/">头条</a><a target="_blank" title="点此查看推荐文章" href="http://www.szhgh.com/html/isgood/">推荐</a><a target="_blank" title="点此查看最新评论" href="http://www.szhgh.com/html/newcomment/">评论</a><a target="_blank" title="点此查看视频文章" href="http://www.szhgh.com/html/video/">视频</a>
                                </span>
                            </li>
                        </ul>
                        <div class="tabcon">
                            <ul>
                                <? @sys_GetEcmsInfo(1,1,44,1,12,19,0,'firsttitle=4','newstime DESC');?>
                                <? @sys_GetEcmsInfo(1,22,64,1,2,16,0,'isgood>0 and isgood<4','newstime DESC');?>
                            </ul>
                        </div> 
                    </div>
                    <div class="left section row2">
                        <script type="text/javascript">
                            myFocus.set({
                                id: 'myFocus', //焦点图盒子ID
                                pattern: 'mF_tbhuabao', //风格应用的名称
                                time: 3, //切换时间间隔(秒)
                                trigger: 'click', //触发切换模式:'click'(点击)/'mouseover'(悬停)
                                width: 320, //设置图片区域宽度(像素)
                                height: 240, //设置图片区域高度(像素)
                                txtHeight: 'default'//文字层高度设置(像素),'default'为默认高度，0为隐藏
                            });
                        </script>
                        <div id="myFocus" class="scroll">
                            <div class="loading"></div><!--载入画面(可删除)-->
                            <div class="pic"><!--图片列表-->
                                <ul class="scroll_list">
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(1,6,0,1,'isgood>1 and ttid!=1','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <li>
                                        <a href="<?=$bqsr['titleurl']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr[titlepic],315,240,1,'')?>" alt="<?=esub($bqr['title'],42)?>" /></a>
                                    </li>
                                    <?php
}
}
?>
                                </ul>
                            </div>
                            <div class="scroll_bg"></div>
                        </div>
                        <div class="topic_list">
                            
                            <!-- 站务公告 -->
                            <div class="section_header section_header_base1">
                                <strong><a href="http://www.szhgh.com/Article/notice/" title="站务公告" target="_blank">站务公告</a></strong>
                                <div class="clear"></div>
                            </div>
                            <div class="section_content">
                                <ul>
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(3,3,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <li><a href="<?=$bqsr[titleurl]?>" title="<?=$bqr[title]?>" target="_blank"><?=$bqr[title]?></a></li>
                                    <?php
}
}
?>
                                </ul>
                            </div>                       
                            
                            <!-- 红歌会网评 -->
                            <div class="section_header section_header_base1">
                                <strong><a href="http://www.szhgh.com/Article/opinion/wp/" title="红歌会网评" target="_blank">红歌会网评</a></strong>
                                <div class="clear"></div>
                            </div>
                            <div class="section_content">
                                <ul>
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(48,2,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <li><a href="<?=$bqsr[titleurl]?>" title="<?=$bqr[title]?>" target="_blank"><?=$bqr[title]?></a></li>
                                    <?php
}
}
?>
                                </ul>
                            </div>

                            <!-- 红色资讯 -->
                            <div class="section_header section_header_base1">
                                <strong><a href="http://www.szhgh.com/Article/red-china/rednews/" title="红色资讯" target="_blank">红色资讯</a></strong>
                                <div class="clear"></div>
                            </div>
                            <div class="section_content">
                                <ul>
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(107,4,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <li><a href="<?=$bqsr[titleurl]?>" title="<?=$bqr[title]?>" target="_blank"><?=$bqr[title]?></a></li>
                                    <?php
}
}
?>
                                </ul>
                            </div>                       
                        </div>
                    </div>
                    <div class="clear"></div>
                </div> 

                <!-- 广告一区 -->
                <div class="box_skitter ad_area_1">
                    <ul>
			<script src="http://www.szhgh.com/d/js/acmsd/thea6.js"></script>
                    </ul>
                </div>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('.ad_area_1').skitter({
                            theme: 'minimalist',
                            dots: false,
                            preview: false,
                            numbers: false,
                            navigation: false,
                            interval: 3000,
                            with_animations: ['swapBarsBack', 'cubeShow', 'swapBars', 'circlesRotate', 'hideBars', 'circlesInside', 'downBars', 'cut', 'upBars', 'swapBlocks']
                        });
                    });
                </script>

                <div class="base_module module">
                    <div class="left_section section">

                        <!-- 红色中国 -->
                        <? @sys_ShowClassByTemp(52,22,0,0);?>
                        <div class="section_content">

                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(52,1,0,0,'istop=1','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <h2><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></h2>
                            <?php
}
}
?>
                            <div class="middle">
                                <div class="left">
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(52,1,0,1,'istop>0 or firsttitle>0 or isgood>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr['titlepic'],120,90,1,'')?>" /></a>
                                    <h4><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h4><div class="transparent_bg"></div>
                                    <?php
}
}
?>
                                </div>
                                <div class="right">
                                    <ul>
                                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(52,4,0,0,'firsttitle>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                        <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></li>
                                        <?php
}
}
?>
                                    </ul>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="bottom">
                                <ul>
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(52,10,0,0,'isgood=0 and istop=0 and firsttitle=0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <li><span>[<a href="<?=$bqsr[classurl]?>" title="<?=$bqsr[classname]?>" target="_blank"><?=$bqsr[classname]?></a>]</span> <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></li>
                                    <?php
}
}
?>
                                </ul>                                    
                            </div>
                        </div>
                        
                        <!-- 资讯中心 -->
                        <? @sys_ShowClassByTemp(36,22,0,8);?>
                        <div class="section_content">

                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(36,1,0,0,'istop=1','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <h2><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></h2>
                            <?php
}
}
?>
                            <div class="middle">
                                <div class="left">
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(36,1,0,1,'istop>0 or firsttitle>0 or isgood>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr['titlepic'],120,90,1,'')?>" /></a>
                                    <h4><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h4><div class="transparent_bg"></div>
                                    <?php
}
}
?>
                                </div>
                                <div class="right">
                                    <ul>
                                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(36,4,0,0,'firsttitle>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                        <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></li>
                                        <?php
}
}
?>
                                    </ul>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="bottom">
                                <ul>
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(36,10,0,0,'isgood=0 and istop=0 and firsttitle=0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <li><span>[<a href="<?=$bqsr[classurl]?>" title="<?=$bqsr[classname]?>" target="_blank"><?=$bqsr[classname]?></a>]</span> <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></li>
                                    <?php
}
}
?>
                                </ul>                                    
                            </div>
                        </div>

                    </div>
                    <div class="right_section section">

                        <!-- 纵论天下 -->
                        <? @sys_ShowClassByTemp(47,22,0,0);?>
                        <div class="section_content">

                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(47,1,0,0,'istop=1','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <h2><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></h2>
                            <?php
}
}
?>
                            <div class="middle">
                                <div class="left">
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(47,1,0,1,'istop>0 or firsttitle>0 or isgood>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr['titlepic'],120,90,1,'')?>" /></a>
                                    <h4><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h4><div class="transparent_bg"></div>
                                    <?php
}
}
?>
                                </div>
                                <div class="right">
                                    <ul>
                                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(47,4,0,0,'firsttitle>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                        <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></li>
                                        <?php
}
}
?>
                                    </ul>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="bottom">
                                <ul>
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(47,10,0,0,'isgood=0 and istop=0 and firsttitle=0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <li><span>[<a href="<?=$bqsr[classurl]?>" title="<?=$bqsr[classname]?>" target="_blank"><?=$bqsr[classname]?></a>]</span> <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></li>
                                    <?php
}
}
?>
                                </ul>                                    
                            </div>
                        </div>

                        <!-- 唱读讲传 -->
                        <? @sys_ShowClassByTemp(53,22,0,0);?>
                        <div class="section_content">

                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(53,1,0,0,'istop=1','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <h2><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></h2>
                            <?php
}
}
?>
                            <div class="middle">
                                <div class="left">
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(53,1,0,1,'istop>0 or firsttitle>0 or isgood>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr['titlepic'],120,90,1,'')?>" /></a>
                                    <h4><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h4><div class="transparent_bg"></div>
                                    <div class="clear"></div>
                                    <?php
}
}
?>
                                </div>
                                <div class="right">
                                    <ul>
                                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(53,4,0,0,'firsttitle>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                        <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></li>
                                        <?php
}
}
?>
                                    </ul>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="bottom">
                                <ul>
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(53,10,0,0,'isgood=0 and istop=0 and firsttitle=0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <li><span>[<a href="<?=$bqsr[classurl]?>" title="<?=$bqsr[classname]?>" target="_blank"><?=$bqsr[classname]?></a>]</span> <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></li>
                                    <?php
}
}
?>
                                </ul>                                    
                            </div>
                        </div>                           

                    </div>   
                    <div class="clear"></div>
                </div>

                <!-- 广告二区 -->
                <div class="box_skitter ad_area_2">
                    <ul>
                        <script src="http://www.szhgh.com/d/js/acmsd/thea7.js"></script>
                    </ul>
                </div>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('.ad_area_2').skitter({
                            theme: 'minimalist',
                            dots: false,
                            preview: false,
                            numbers: false,
                            navigation: false,
                            interval: 3000,
                            with_animations: ['swapBarsBack', 'cubeShow', 'swapBars', 'circlesRotate', 'hideBars', 'circlesInside', 'downBars', 'cut', 'upBars', 'swapBlocks']
                        });
                    });
                </script>


                <div class="base_module module">
                    <div class="left_section section">

                        <!-- 人民健康 -->
                        <? @sys_ShowClassByTemp(54,22,0,0);?>
                        <div class="section_content">

                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(54,1,0,0,'istop=1','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <h2><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></h2>
                            <?php
}
}
?>
                            <div class="middle">
                                <div class="left">
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(54,1,0,1,'istop>0 or firsttitle>0 or isgood>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr['titlepic'],120,90,1,'')?>" /></a>
                                    <h4><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h4><div class="transparent_bg"></div>
                                    <?php
}
}
?>
                                </div>
                                <div class="right">
                                    <ul>
                                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(54,4,0,0,'firsttitle>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                        <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></li>
                                        <?php
}
}
?>
                                    </ul>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="bottom">
                                <ul>
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(54,10,0,0,'isgood=0 and istop=0 and firsttitle=0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <li><span>[<a href="<?=$bqsr[classurl]?>" title="<?=$bqsr[classname]?>" target="_blank"><?=$bqsr[classname]?></a>]</span> <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></li>
                                    <?php
}
}
?>
                                </ul>                                    
                            </div>
                        </div>

                        <!-- 文史读书 -->
                        <? @sys_ShowClassByTemp(56,22,0,0);?>
                        <div class="section_content">

                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(56,1,0,0,'istop=1','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <h2><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></h2>
                            <?php
}
}
?>
                            <div class="middle">
                                <div class="left">
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(56,1,0,1,'istop>0 or firsttitle>0 or isgood>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr['titlepic'],120,90,1,'')?>" /></a>
                                    <h4><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h4><div class="transparent_bg"></div>
                                    <?php
}
}
?>
                                </div>
                                <div class="right">
                                    <ul>
                                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(56,4,0,0,'firsttitle>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                        <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></li>
                                        <?php
}
}
?>
                                    </ul>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="bottom">
                                <ul>
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(56,10,0,0,'isgood=0 and istop=0 and firsttitle=0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <li><span>[<a href="<?=$bqsr[classurl]?>" title="<?=$bqsr[classname]?>" target="_blank"><?=$bqsr[classname]?></a>]</span> <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></li>
                                    <?php
}
}
?>
                                </ul>                                    
                            </div>
                        </div>

                    </div>
                    <div class="right_section section">

                        <!-- 工农之声 -->
                        <? @sys_ShowClassByTemp(55,22,0,0);?>
                        <div class="section_content">

                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(55,1,0,0,'istop=1','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <h2><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></h2>
                            <?php
}
}
?>
                            <div class="middle">
                                <div class="left">
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(55,1,0,1,'istop>0 or firsttitle>0 or isgood>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr['titlepic'],120,90,1,'')?>" /></a>
                                    <h4><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h4><div class="transparent_bg"></div>
                                    <?php
}
}
?>
                                </div>
                                <div class="right">
                                    <ul>
                                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(55,4,0,0,'firsttitle>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                        <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></li>
                                        <?php
}
}
?>
                                    </ul>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="bottom">
                                <ul>
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(55,10,0,0,'isgood=0 and istop=0 and firsttitle=0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <li><span>[<a href="<?=$bqsr[classurl]?>" title="<?=$bqsr[classname]?>" target="_blank"><?=$bqsr[classname]?></a>]</span> <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></li>
                                    <?php
}
}
?>
                                </ul>                                    
                            </div>
                        </div>

                        <!-- 第三世界 -->
                        <? @sys_ShowClassByTemp(57,22,0,0);?>
                        <div class="section_content">
                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(57,1,0,0,'istop=1','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <h2><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></h2>
                            <?php
}
}
?>
                            <div class="middle">
                                <div class="left">
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(57,1,0,1,'istop>0 or firsttitle>0 or isgood>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr['titlepic'],120,90,1,'')?>" /></a>
                                    <h4><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h4><div class="transparent_bg"></div>
                                    <?php
}
}
?>
                                </div>
                                <div class="right">
                                    <ul>
                                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(57,4,0,0,'firsttitle>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                        <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></li>
                                        <?php
}
}
?>
                                    </ul>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="bottom">
                                <ul>
                                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(57,10,0,0,'isgood=0 and istop=0 and firsttitle=0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                    <li><span>[<a href="<?=$bqsr[classurl]?>" title="<?=$bqsr[classname]?>" target="_blank"><?=$bqsr[classname]?></a>]</span> <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=$bqr['title']?></a></li>
                                    <?php
}
}
?>
                                </ul>                                    
                            </div>
                        </div>

                    </div>   
                    <div class="clear"></div>
                </div>
            </div>  
            <div class="clear"></div>
        </div>
        <!-- main end -->

        <!-- friendlink -->
        <div class="friendlink">
            <div class="friendlink_header"><strong>友情链接</strong></div>
            <div class="linkbox">
                <ul>
                    <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq('copyfrom',60,18,0,'isgood>0','isgood DESC, newstime desc ');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                    <li><a href="<?=$bqr['webaddr']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></li>
                    <?php
}
}
?>
                    <div class="clear"></div>
                </ul>
            </div>
        </div>

        <!-- friendlink -->


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

    </body>
</html>