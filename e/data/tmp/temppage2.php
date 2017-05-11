<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wb="http://open.weibo.com/wb">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>[!--pagetitle--] - 唱响红歌，弘扬正气！</title>
        <meta name="keywords" content="[!--pagekeywords--]" />
        <meta name="description" content="[!--pagedescription--]" />
        <link rel="shortcut icon" href="[!--news.url--]skin/default/images/favicon.ico" /> 
        <link href="[!--news.url--]skin/default/css/anniversaries_1.css" media="all" rel="stylesheet" type="text/css" />
        <script src="[!--news.url--]skin/default/js/jquery-1.8.2.min.js" type="text/javascript"></script>
        <script src="[!--news.url--]skin/default/js/myfocus-2.0.4.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="[!--news.url--]skin/default/js/mF_tbhuabao.js"></script>
    </head>
    <body>
        <div class="header">
            <div class="nav">
                <ul>
                    <li><a href="[!--news.url--]">首页</a></li>
                    <li><a target="_blank" title="点此进入排行榜" href="http://www.szhgh.com/html/rank.html" class="first">排行榜</a></li>
                    <li><a target="_blank" title="点此进入专题中心" href="http://www.szhgh.com/special">专题中心</a></li>
                    <li><a target="_blank" title="点此进入学者专栏" href="http://www.szhgh.com/columnist/">学者专栏</a></li>                    
                    <li><a target="_blank" title="资讯中心" href="http://www.szhgh.com/Article/news/">资讯中心</a></li>
                    <li><a target="_blank" title="纵论天下" href="http://www.szhgh.com/Article/opinion/">纵论天下</a></li>
                    <li><a target="_blank" title="红色中国" href="http://www.szhgh.com/Article/red-china/">红色中国</a></li>
                    <li><a target="_blank" title="唱读讲传" href="http://www.szhgh.com/Article/cdjc/">唱读讲传</a></li>
                    <li><a target="_blank" title="人民健康" href="http://www.szhgh.com/Article/health/">人民健康</a></li>
                    <li><a target="_blank" title="工农之声" href="http://www.szhgh.com/Article/gnzs/">工农之声</a></li>
                    <li><a target="_blank" title="文史·读书" href="http://www.szhgh.com/Article/wsds/">文史读书</a></li>
                    <li><a target="_blank" title="第三世界" href="http://www.szhgh.com/Article/thirdworld/">第三世界</a></li>
                </ul>
            </div>
            <div class="banner"><img src="[!--news.url--]skin/default/images/topic_images/banner.jpg" alt="深情纪念毛主席诞辰120周年" /></div>
            <div class="message">
                <h1>希望在纪念毛主席的时候，大家能更好地用毛泽东思想来分析现在的社会，能够把毛泽东时代的共产党再找回来。<span>——毛主席秘书胡乔木之女胡木英</span></h1>
            </div>	
            <div class="columns">
                <script type="text/javascript">
                    myFocus.set({
                        id:'myFocus',//焦点图盒子ID
                        pattern:'mF_tbhuabao',//风格应用的名称
                        time:3,//切换时间间隔(秒)
                        trigger:'click',//触发切换模式:'click'(点击)/'mouseover'(悬停)
                        width:388,//设置图片区域宽度(像素)
                        height:270,//设置图片区域高度(像素)
                        txtHeight:'default'//文字层高度设置(像素),'default'为默认高度，0为隐藏
                    });
                </script>
                <div id="myFocus" class="scroll">
                    <div class="loading"></div><!--载入画面(可删除)-->
                    <div class="pic"><!--图片列表-->
                        <ul class="scroll_list">
                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(58,5,0,1,'isgood>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <li>
                                <a href="<?=$bqsr['titleurl']?>"><img src="<?=sys_ResizeImg($bqr[titlepic],384,270,1,'')?>" alt="<?=esub($bqr['title'],40)?>" /></a>
                            </li>
                            <?php
}
}
?>
                        </ul>
                    </div>
                </div>
                <div class="news">
                    <h2>最新文章</h2>
                    <ul class="news_list">

                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(58,1,0,0,'isgood>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <li class="topic_firstitle"><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" class="a3" target="_blank" ><?=esub($bqr['title'],42)?></a></li>
                            <?$id_temp=$bqr['id']?>
                        <?php
}
}
?>					

                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(58,10,0,0,"id!=$id_temp",'newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                        <li><span class="time_span"><?=date('m-d',$bqr['newstime'])?></span><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" class="a3" target="_blank" ><?=esub($bqr['title'],42)?></a></li>
                        <?php
}
}
?>

                    </ul>
                </div>
                <div class="flowers">
                    <a href="http://jidian.china.com/usermemorial.jsp?urlcode=maozedong" title="点此为毛主席献花" target="_blank"><img src="[!--news.url--]skin/default/images/topic_images/flowers.jpg" /></a>
                    <h2><a href="http://cpc.people.com.cn/GB/69112/113427/" title="毛主席纪念堂（人民网）" target="_blank">毛主席纪念堂（人民网）</a><a href="http://www.mzdsx.net/" title="毛主席纪念堂（乌有之乡）" target="_blank">毛主席纪念堂（乌有之乡）</a></h2>
                </div>
            </div>


        </div>

<!--header end-->

        <div class="container ">
            <div class="main">
                <div class="main_top">
                    <div class="main_left">
                        <h2><a href="[!--news.url--]e/action/ListInfo/?classid=59" title="最新动态">最新动态</a></h2>
                        <ul class="news_list">

                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(59,12,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <li><span class="time_span"><?=date('m-d',$bqr['newstime'])?></span><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" class="a3" target="_blank" ><?=esub($bqr['title'],42)?></a></li>
                            <?php
}
}
?>

                        </ul>
                    </div>
                    <div class="main_right">
                        <h2><a href="[!--news.url--]e/action/ListInfo/?classid=60" title="怀念追思">怀念追思</a></h2>
                        <ul class="news_list">
                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(60,12,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <li><span class="time_span"><?=date('m-d',$bqr['newstime'])?></span><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" class="a3" target="_blank" ><?=esub($bqr['title'],42)?></a></li>
                            <?php
}
}
?>
                        </ul>
                    </div>                    
                </div>
                <div class="left_banner"><img src="[!--news.url--]skin/default/images/topic_images/left_banner01.jpg" title="马克思列宁主义、毛泽东思想一定不能丢，丢了就丧失根本！" /></div>
                <div class="main_middle">
                    <div class="main_left">
                        <h2><a href="[!--news.url--]e/action/ListInfo/?classid=61" title="主席后代">主席后代</a></h2>
                        <ul class="news_list">
                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(61,12,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <li><span class="time_span"><?=date('m-d',$bqr['newstime'])?></span><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" class="a3" target="_blank" ><?=esub($bqr['title'],42)?></a></li>
                            <?php
}
}
?>
                        </ul>
                    </div>
                    <div class="main_right">
                        <h2><a href="[!--news.url--]e/action/ListInfo/?classid=62" title="毛泽东时代">毛泽东时代</a></h2>
                        <ul class="news_list">
                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(62,12,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <li><span class="time_span"><?=date('m-d',$bqr['newstime'])?></span><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" class="a3" target="_blank" ><?=esub($bqr['title'],42)?></a></li>
                            <?php
}
}
?>
                        </ul>
                    </div> 
                </div>
                <div class="left_banner"><img src="[!--news.url--]skin/default/images/topic_images/left_banner02.jpg" title="一个没有英雄的民族是不幸的，一个有英雄却不知敬重爱惜的民族是不可救药的；一个有了伟大的人物，而不知拥护，爱戴，崇仰的国家是没有希望的民族之邦！" /></div>
                <div class="main_bottom">
                    <div class="main_left">
                        <h2><a href="[!--news.url--]e/action/ListInfo/?classid=63" title="评述毛泽东">评述毛泽东</a></h2>
                        <ul class="news_list">
                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(63,12,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <li><span class="time_span"><?=date('m-d',$bqr['newstime'])?></span><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" class="a3" target="_blank" ><?=esub($bqr['title'],42)?></a></li>
                            <?php
}
}
?>
                        </ul>
                    </div>
                    <div class="main_right">
                        <h2><a href="[!--news.url--]e/action/ListInfo/?classid=64" title="学习毛泽东">学习毛泽东</a></h2>
                        <ul class="news_list">
                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(64,12,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <li><span class="time_span"><?=date('m-d',$bqr['newstime'])?></span><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" class="a3" target="_blank" ><?=esub($bqr['title'],42)?></a></li>
                            <?php
}
}
?>
                        </ul>
                    </div> 
                </div>                
            </div>
            <div class="sidebar">
                <div class="side_top">
                    <h2>视频推荐</h2>
                    <div class="first">
                        <embed src="http://www.tudou.com/v/AyaaAgwm8p8/&resourceId=0_05_05_99&bid=05/v.swf" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="opaque" width="252" height="190"></embed>
                    </div>
                    <ul class="side_list">
                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(65,8,0,0,'','isgood DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                        <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" class="a3" target="_blank" ><?=esub($bqr['title'],42)?></a></li>
                        <?php
}
}
?>
                    </ul>
                </div>
                <div class="side_middle">
                    <h2>影像资料</h2>
                    <div class="first">
                        <embed src="http://player.youku.com/player.php/sid/XMTcyMjkwNDAw/v.swf" quality="high" width="252" height="190" align="middle" allowScriptAccess="sameDomain" allowFullscreen="true" type="application/x-shockwave-flash"></embed>
                    </div>
                    <ul class="side_list">
                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(65,8,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                        <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" class="a3" target="_blank" ><?=esub($bqr['title'],42)?></a></li>
                        <?php
}
}
?>
                    </ul>
                </div>                
                <div class="side_bottom">
                    <h2>图片文章</h2>
                    <div class="first">
                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(58,1,0,1,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>                       
                        <a href="<?=$bqsr['titleurl']?>" target="_blank" title="<?=$bqr['oldtitle']?>"><img src="<?=$bqr['titlepic']?>" /></a>
                        <h3><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" target="_blank"><?=esub($bqr['title'],30)?></a></h3>
                        <?$id_temp=$bqr['id']?>
                        <?php
}
}
?>
                    </div>
                    <ul class="side_list">
                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(58,8,0,1,"id!=$id_temp",'newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?> 
                        <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['oldtitle']?>" class="a3" target="_blank" ><?=esub($bqr['title'],42)?></a></li>
                        <?php
}
}
?>
                    </ul>
                </div>                  

            </div>

            <div class="clear_float"></div>
        </div>


        <div class="footer">
            <div class="friendlink">
                <p><span>友情链接：</span></p>
                <div class="linkbox">
                    <div class="linklist">
                        <ul>
                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq('copyfrom',60,18,0,'','isgood DESC');
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
                            <div class="clear_float"></div>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p style="clear:both;">
                    <a href="[!--news.url--]">红歌会网</a>  
                    | <a href="[!--news.url--]Article/opinion/wp/20257.html">联系我们</a>
                    | <script src="http://s20.cnzz.com/stat.php?id=3051861&web_id=3051861" language="JavaScript"></script>
                </p>
                <p>
                    网站QQ：<a title="官方QQ" href="http://wpa.qq.com/msgrd?Uin=1737191719" target="_blank">1737191719</a>
                    | 邮箱：<a href="mailto:szhgh001@163.com" target="_blank">szhgh001@163.com</a>
                    | 粉丝QQ群：166238344
                    | <a href="http://weibo.com/szhgh" target="_blank">红歌会网微博</a>
                </p>
                <p><span>备案序号:粤ICP备12077717号 </span></p>
            </div>
        </div>
    </body>
</html>