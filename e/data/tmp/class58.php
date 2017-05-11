<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wb="http://open.weibo.com/wb">
<head>
    <title>毛泽东 - <?=$public_r['sitename']?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link rel="shortcut icon" href="http://www.szhgh.com/skin/default/images/favicon.ico" />
    <link href="http://www.szhgh.com/skin/default/css/channelmao.css" type="text/css" media="all" rel="stylesheet"/>
    <script src="http://www.szhgh.com/skin/default/js/jquery-1.8.2.min.js" type="text/javascript"></script>
    <script src="http://www.szhgh.com/skin/default/js/myfocus-2.0.4.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="http://www.szhgh.com/skin/default/js/mF_tbhuabao.js"></script>
    <script src="http://www.szhgh.com/skin/default/js/jquery.SuperSlide.js" type="text/javascript"></script>
</head>
<body>
<div class="g-hd">
    <ul class="m-nav-1">
        <li><a href="http://hao.szhgh.com/" title="点此进入红歌会网址导航" target="_blank">网址导航</a></li>
        <li><a href="http://www.szhgh.com/">首页</a></li>
        <li><a target="_blank" title="资讯中心" href="http://www.szhgh.com/Article/news/">资讯中心</a></li>
        <li><a target="_blank" title="纵论天下" href="http://www.szhgh.com/Article/opinion/">纵论天下</a></li>
        <li><a target="_blank" title="红色中国" href="http://www.szhgh.com/Article/red-china/">红色中国</a></li>
        <li><a target="_blank" title="点此进入专题中心" href="http://www.szhgh.com/special">专题中心</a></li>
        <li><a target="_blank" title="点此进入学者专栏" href="http://www.szhgh.com/xuezhe/">学者专栏</a></li>
        <li><a href="http://www.szhgh.com/e/member/cp/">会员中心</a></li>
        <li><a href="http://www.szhgh.com/e/DoInfo/AddInfo.php?mid=10&amp;enews=MAddInfo&amp;classid=29">我要投稿</a></li>
    </ul>
    <div class="account m-log">
        <script>
            document.write('<script src="http://www.szhgh.com/e/member/login/loginjs.php?t=' + Math.random() + '"><' + '/script>');
        </script>
    </div>
    <h1><a href="http://mzd.szhgh.com/">毛泽东频道</a></h1>
    <ul class="m-nav-2">
        <?php
        $strClass = $class_r[58]['sonclass'];
        $strClass = str_replace('|',',',$strClass);
        $strClass = substr($strClass,1,strlen($strClass)-2);
        $arrClass = explode(',',$strClass);
        $strHtml='';
        foreach($arrClass as $classid){
            $subClass = $class_r[$classid];
            if(empty($subClass['classurl'])){
                $url = $subClass['classpath'];
            }else{
                $url = $subClass['classurl'];
            }
            $strHtml .='
            <li><a title="'.$subClass['classname'].'" href="'.$url.'">'.$subClass['classname'].'</a></li>
            ';
        }
        echo $strHtml;
        ?>
        <li><a href="http://www.szhgh.com/html/pic/"  title="图片文章">图片文章</a></li>
    </ul>
    <div class="m-crm">
        <strong>当前位置：<a href="http://www.szhgh.com/index.html">首页</a>&nbsp;>&nbsp;<a href="http://www.szhgh.com/Article/">文章中心</a>&nbsp;>&nbsp;<a href="http://www.szhgh.com/Article/red-china/">红色中国</a>&nbsp;>&nbsp;<a href="http://mzd.szhgh.com">毛泽东</a></strong>
    </div>
</div>
<div class="g-mn">
<div class="g-mnc">
<div class="g-box-1">


    <div class="m-sld" id="_m-sld">
        <h2>图片文章</h2>

        <p class="u-more"><a href="http://www.szhgh.com/html/pic/">更多</a></p>
        <div class="hd">
            <a class="next"></a>
            <ul></ul>
            <a class="prev"></a>
            <span class="pageState"></span>
        </div>
        <div class="bd">
            <ul class="picList">
                <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(58,5,0,1,'isgood>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                <li>
                    <div class="pic"> <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>"><img src="<?=$bqr[titlepic]?>" alt="<?=$bqr['title']?>" /></a></div>
                    <div class="title"><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>"><?=esub($bqr['title'],40)?></a></div>
                </li>
                <?php
}
}
?>

            </ul>
        </div>
    </div>
    <script type="text/javascript">
        jQuery("#_m-sld").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"leftLoop",autoPlay:true});
    </script>


    <div class="m-topic">
        <h2>头条文章</h2>

        <dl>
            <?php
            $strClass = $class_r[58]['sonclass'];
            $strClass = str_replace('|',',',$strClass);
            $strClass = substr($strClass,1,strlen($strClass)-2);
            $strHtml = '';
            $cs = 0;

            $sql = $empire->query("select title,titleurl,smalltext from {$dbtbpre}ecms_article where classid in ($strClass) and firsttitle>=1 order by newstime desc limit 0,3 ");
            while ($r = $empire->fetch($sql, MYSQL_ASSOC)) {
                $smalltext= usr_esub($r['smalltext'],160);
                $strHtml.='
                <dt> <a href="'.$r['titleurl'].'" title="'.$r['title'].'" target="_blank" >'.esub($r['title'],40).'</a></dt>
                <dd> '.$smalltext.'<span><a href="'.$r['titleurl'].' title="'.$r['title'].'" target="_blank" >[全文]</a></span> </dd>
                ';
            }
            echo $strHtml;
            ?>
        </dl>
    </div>
    <div class="m-vd">
        <h2>推荐视频</h2>


        <div>
            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(58,2,0,1,'ttid=1 and firsttitle>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
            <div class="m-pt">
                <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank" ><img src="<?=sys_ResizeImg($bqr[titlepic],226,169,1,'')?>" /></a>
                <h3><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank" ><?=esub($bqr['title'],24)?></a></h3>
            </div>
            <?php
}
}
?>
        </div>
        <ul>
            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(65,7,0,0,'ttid=1 and (isgood>0 or firsttitle>0)','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
            <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank" ><?=esub($bqr['title'],40)?></a></li>
            <?php
}
}
?>
        </ul>
    </div>
    <div class="m-new">
        <h2>最新文章</h2>

        <ul>
            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(58,12,0,0,'isgood>=1','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
            <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank" ><?=esub($bqr['title'],52)?></a></li>
            <?php
}
}
?>
        </ul>
    </div>
</div>
<div class="m-bn">
    <img src="http://www.szhgh.com/skin/default/images/topic_images/left_banner01.jpg" title="马克思列宁主义、毛泽东思想一定不能丢，丢了就丧失根本！" />
</div>
<div class="g-box-2">
    <div class="m-lst">
        <h2><a href="http://www.szhgh.com/e/action/ListInfo/?classid=59" title="纪念动态">纪念动态</a></h2>

        <p class="u-more"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=59" title="纪念动态">更多</a></p>

        <div class="m-pt">
            <?php
            $strHtml='';
            $sql = $empire->query("select title,titleurl,smalltext,titlepic from {$dbtbpre}ecms_article where classid=59 and ispic=1 and firsttitle>=1 order by newstime desc limit 0,1 ");
            while ($r = $empire->fetch($sql, MYSQL_ASSOC)) {
                $smalltext= usr_esub($r['smalltext'],72);
                $strHtml.='
                    <a href="'.$r['titleurl'].'" title="'.$r['title'].'" target="_blank" ><img src="'.sys_ResizeImg($r[titlepic],226,169,1,'').'" /></a>
                       <h3><a href="'.$r['titleurl'].'" title="'.$r['title'].'" target="_blank" >'.esub($r['title'],30).'</a></h3>
                       <p>'.$smalltext.'</p>
                    ';
            }
            echo $strHtml;
            ?>
        </div>
        <ul>
            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(59,10,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
            <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank" ><?=esub($bqr['title'],42)?></a></li>
            <?php
}
}
?>
        </ul>
    </div>
    <div class="m-lst f-r">
        <h2><a href="http://www.szhgh.com/e/action/ListInfo/?classid=60" title="怀念追思">怀念追思</a></h2>

        <p class="u-more"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=60" title="怀念追思">更多</a></p>

        <div class="m-pt">
            <?php
            $strHtml='';
            $sql = $empire->query("select title,titleurl,smalltext,titlepic from {$dbtbpre}ecms_article where classid=60 and ispic=1 and firsttitle>=1 order by newstime desc limit 0,1 ");
            while ($r = $empire->fetch($sql, MYSQL_ASSOC)) {
                $smalltext= usr_esub($r['smalltext'],72);
                $strHtml.='
                    <a href="'.$r['titleurl'].'" title="'.$r['title'].'" target="_blank" ><img src="'.sys_ResizeImg($r[titlepic],226,169,1,'').'" /></a>
                       <h3><a href="'.$r['titleurl'].'" title="'.$r['title'].'" target="_blank" >'.esub($r['title'],30).'</a></h3>
                       <p>'.$smalltext.'</p>
                    ';
            }
            echo $strHtml;
            ?>
        </div>
        <ul>
            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(60,10,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
            <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank" ><?=esub($bqr['title'],42)?></a></li>
            <?php
}
}
?>
        </ul>
    </div>
    <div class="m-lst">
        <h2><a href="http://www.szhgh.com/e/action/ListInfo/?classid=61" title="主席后代">主席后代</a></h2>

        <p class="u-more"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=61" title="主席后代">更多</a></p>

        <div class="m-pt">

            <?php
            $strHtml='';
            $sql = $empire->query("select title,titleurl,smalltext,titlepic from {$dbtbpre}ecms_article where classid=61 and ispic=1 and firsttitle>=1 order by newstime desc limit 0,1 ");
            while ($r = $empire->fetch($sql, MYSQL_ASSOC)) {
                $smalltext= usr_esub($r['smalltext'],72);
                $strHtml.='
                    <a href="'.$r['titleurl'].'" title="'.$r['title'].'" target="_blank" ><img src="'.sys_ResizeImg($r[titlepic],226,169,1,'').'" /></a>
                       <h3><a href="'.$r['titleurl'].'" title="'.$r['title'].'" target="_blank" >'.esub($r['title'],30).'</a></h3>
                       <p>'.$smalltext.'</p>
                    ';
            }
            echo $strHtml;
            ?>

        </div>
        <ul>
            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(61,10,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
            <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank" ><?=esub($bqr['title'],42)?></a></li>
            <?php
}
}
?>
        </ul>
    </div>
    <div class="m-lst f-r">
        <h2><a href="http://www.szhgh.com/e/action/ListInfo/?classid=62" title="毛泽东时代">毛泽东时代</a></h2>

        <p class="u-more"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=62" title="毛泽东时代">更多</a></p>

        <div class="m-pt">

            <?php
            $strHtml='';
            $sql = $empire->query("select title,titleurl,smalltext,titlepic from {$dbtbpre}ecms_article where classid=62 and ispic=1 and firsttitle>=1 order by newstime desc limit 0,1 ");
            while ($r = $empire->fetch($sql, MYSQL_ASSOC)) {
                $smalltext= usr_esub($r['smalltext'],72);
                $strHtml.='
                    <a href="'.$r['titleurl'].'" title="'.$r['title'].'" target="_blank" ><img src="'.sys_ResizeImg($r[titlepic],226,169,1,'').'" /></a>
                       <h3><a href="'.$r['titleurl'].'" title="'.$r['title'].'" target="_blank" >'.esub($r['title'],30).'</a></h3>
                       <p>'.$smalltext.'</p>
                    ';
            }
            echo $strHtml;
            ?>

        </div>
        <ul>
            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(62,10,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
            <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank" ><?=esub($bqr['title'],42)?></a></li>
            <?php
}
}
?>
        </ul>
    </div>
</div>
<div class="m-bn">
    <img src="http://www.szhgh.com/skin/default/images/topic_images/left_banner02.jpg" title="一个没有英雄的民族是不幸的，一个有英雄却不知敬重爱惜的民族是不可救药的；一个有了伟大的人物，而不知拥护，爱戴，崇仰的国家是没有希望的民族之邦！" />
</div>
<div class="g-box-3">
    <div class="m-lst">
        <h2><a href="http://www.szhgh.com/e/action/ListInfo/?classid=63" title="评述毛泽东">评述毛泽东</a></h2>

        <p class="u-more"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=63" title="评述毛泽东">更多</a></p>

        <div class="m-pt">

            <?php
            $strHtml='';
            $sql = $empire->query("select title,titleurl,smalltext,titlepic from {$dbtbpre}ecms_article where classid=63 and ispic=1 and firsttitle>=1 order by newstime desc limit 0,1 ");
            while ($r = $empire->fetch($sql, MYSQL_ASSOC)) {
                $smalltext= usr_esub($r['smalltext'],72);
                $strHtml.='
                    <a href="'.$r['titleurl'].'" title="'.$r['title'].'" target="_blank" ><img src="'.sys_ResizeImg($r[titlepic],226,169,1,'').'" /></a>
                       <h3><a href="'.$r['titleurl'].'" title="'.$r['title'].'" target="_blank" >'.esub($r['title'],30).'</a></h3>
                       <p>'.$smalltext.'</p>
                    ';
            }
            echo $strHtml;
            ?>

        </div>
        <ul>
            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(63,10,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
            <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank" ><?=esub($bqr['title'],42)?></a></li>
            <?php
}
}
?>
        </ul>
    </div>
    <div class="m-lst f-r">
        <h2><a href="http://www.szhgh.com/e/action/ListInfo/?classid=64" title="学习毛泽东">学习毛泽东</a></h2>

        <p class="u-more"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=64" title="学习毛泽东">更多</a></p>

        <div class="m-pt">

            <?php
            $strHtml='';
            $sql = $empire->query("select title,titleurl,smalltext,titlepic from {$dbtbpre}ecms_article where classid=64 and ispic=1 and firsttitle>=1 order by newstime desc limit 0,1 ");
            while ($r = $empire->fetch($sql, MYSQL_ASSOC)) {
                $smalltext= usr_esub($r['smalltext'],72);
                $strHtml.='
                    <a href="'.$r['titleurl'].'" title="'.$r['title'].'" target="_blank" ><img src="'.sys_ResizeImg($r[titlepic],226,169,1,'').'" /></a>
                       <h3><a href="'.$r['titleurl'].'" title="'.$r['title'].'" target="_blank" >'.esub($r['title'],30).'</a></h3>
                       <p>'.$smalltext.'</p>
                    ';
            }
            echo $strHtml;
            ?>

        </div>
        <ul>
            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(64,10,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
            <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank" ><?=esub($bqr['title'],42)?></a></li>
            <?php
}
}
?>
        </ul>
    </div>
</div>
</div>

<div class="g-sd">
    <div class="m-flowers">
        <h2><a href="http://www.szhgh.com/e/flowers/?bid=2" target="_blank" title="去献花">给毛主席献花</a></h2>

        <div>
            <a target="_blank" title="毛主席纪念堂（中华网）" href="http://jidian.china.com/usermemorial.jsp?urlcode=maozedong">（中华网）</a>
            <a target="_blank" title="毛主席纪念堂（人民网）" href="http://cpc.people.com.cn/GB/69112/113427/">（人民网）</a>
            <a target="_blank" title="毛主席纪念堂（乌有之乡）" href="http://www.mzdsx.net/">（乌有之乡）</a>
        </div>
    </div>
    <div class="m-gbk">
        <h2>献花留言</h2>
        <p class="u-btn-o"><a href="http://www.szhgh.com/e/flowers/?bid=2#01" target="_blank" title="去留言">去留言</a></p>
        <dl>
            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq("select * from {$dbtbpre}enewsgbook where bid=2 order by lyid DESC limit 5",5,24,0,'','addtime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
            <dt><?=esub($bqr['lytext'],100)?></dt>
            <dd>——<?=$bqr['name']?></dd>
            <?php
}
}
?>
        </dl>
    </div>
    <div class="m-lst">
        <h2>相关专题推荐</h2>

        <ul>
            <li><a href="http://www.szhgh.com/s/mzx122/" title="纪念毛主席诞辰122周年" target="_blank">纪念毛主席诞辰122周年</a></li>
            <li><a href="http://www.szhgh.com/s/mao121/" title="纪念毛主席诞辰121周年" target="_blank">纪念毛主席诞辰121周年</a></li>
            <li><a href="http://www.szhgh.com/s/mzx38/" title="纪念毛主席逝世38周年" target="_blank">纪念毛主席逝世38周年</a></li>
            <li><a href="http://www.szhgh.com/s/mzd120/" title="纪念毛主席诞辰120周年" target="_blank">纪念毛主席诞辰120周年</a></li>
            <li><a href="http://www.hxw.org.cn/html/article/info4368.html" title="对毛泽东和毛时代的权威评价" target="_blank">对毛泽东和毛时代的权威评价</a></li>
        </ul>
    </div>
    <div class="m-lst">
        <h2>影像资料</h2>

        <div class="m-pt">
            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(65,1,0,1,'isgood>0','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
            <a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank" ><img src="<?=sys_ResizeImg($bqr[titlepic],226,169,1,'')?>" /></a>
            <h3><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank" ><?=esub($bqr['title'],34)?></a></h3>
            <?php
}
}
?>
        </div>
        <ul>
            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(65,10,0,0,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
            <li><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['dtitle']?>" target="_blank" ><?=esub($bqr['title'],36)?></a></li>
            <?php
}
}
?>
        </ul>
    </div>
    <div class="m-top">
        <h2>点击排行</h2>
        <ul class="hd">
            <li class="z-crt">24小时</li>
            <li>一周</li>
            <li>一月</li>
        </ul>
        <div class="bd">
            <ol>
                <?php
                $strHtml='';
                $c=0;
                $sql = $empire->query("select title,titleurl from {$dbtbpre}ecms_article  where classid in ($strClass) and newstime > UNIX_TIMESTAMP()-86400*1 order by onclick desc limit 10");
                while ($r = $empire->fetch($sql, MYSQL_ASSOC)) {
                    $c++;
                    if($c<=3)
                        $strCss='s-o';
                    else
                        $strCss='';
                    $strHtml.='<li class="'.$strCss.'"><a href="'.$r['titleurl'].'" title="'.$r['title'].'" target="_blank" ><span>'.$c.'</span>'.esub($r['title'],34).'</a></li>';
                }
                echo $strHtml;
                ?>
            </ol>
            <ol>
                <?php
                $strHtml='';
                $c=0;
                $sql = $empire->query("select title,titleurl from {$dbtbpre}ecms_article  where classid in ($strClass) and newstime > UNIX_TIMESTAMP()-86400*7 order by onclick desc limit 10");
                while ($r = $empire->fetch($sql, MYSQL_ASSOC)) {
                    $c++;
                    if($c<=3)
                        $strCss='s-o';
                    else
                        $strCss='';
                    $strHtml.='<li class="'.$strCss.'"><a href="'.$r['titleurl'].'" title="'.$r['title'].'" target="_blank" ><span>'.$c.'</span>'.esub($r['title'],34).'</a></li>';
                }
                echo $strHtml;
                ?>
            </ol>
            <ol>
                <?php
                $strHtml='';
                $c=0;
                $sql = $empire->query("select title,titleurl from {$dbtbpre}ecms_article where classid in ($strClass) and newstime > UNIX_TIMESTAMP()-86400*30 order by onclick desc limit 10");
                while ($r = $empire->fetch($sql, MYSQL_ASSOC)) {
                    $c++;
                    if($c<=3)
                        $strCss='s-o';
                    else
                        $strCss='';
                    $strHtml.='<li class="'.$strCss.'"><a href="'.$r['titleurl'].'" title="'.$r['title'].'" target="_blank" ><span>'.$c.'</span>'.esub($r['title'],34).'</a></li>';
                }
                echo $strHtml;
                ?>
            </ol>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(".m-top").slide({trigger: "click"});
    </script>
</div>
</div>

<div class="footer">
    <div class="friendlink">
        <div class="linkbox">
            <div class="head"><strong>友情链接：</strong></div>
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
        <ul>
            <li class='copy_left'>
                <div>
                    <a href="http://www.szhgh.com/" title="红歌会网" target="_blank">红歌会网</a>
                    | <a href="http://www.szhgh.com/" title="网址导航" target="_blank">网址导航</a>
                    | <a href="http://www.szhgh.com/html/rank.html" title="排行榜" target="_blank">排行榜</a>
                    | <a href="http://www.szhgh.com/Article/opinion/wp/20257.html" title="联系我们" target="_blank">联系我们</a>
                    | <a href="http://www.szhgh.com/Article/opinion/zatan/13968.html" title="在线提意见" target="_blank">在线提意见</a>
                </div>
                <div>
                    <a href="http://www.miitbeian.gov.cn" target="_blank">粤ICP备12077717号-1</a>
                    | <script src="http://s20.cnzz.com/stat.php?id=3051861&web_id=3051861&show=pic1" language="JavaScript"></script>

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
                <div>网站QQ：<a href="http://wpa.qq.com/msgrd?Uin=1962727933" title="欢迎通过QQ联系我们" target="_blank">1962727933</a>&nbsp;&nbsp;红歌会网粉丝QQ群：35758473</div>
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

<script src=http://www.szhgh.com/e/public/onclick/?enews=doclass&classid=58></script>
</body>
</html>