<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wb="http://open.weibo.com/wb">
<head>
    <title>[!--pagetitle--] - <?= $public_r['sitename'] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="keywords" content="[!--pagekey--]"/>
    <meta name="description" content="[!--pagedes--]"/>
    <link rel="shortcut icon" href="[!--news.url--]skin/default/images/favicon.ico"/>
    <link href="[!--news.url--]skin/default/css/channelmao.css" type="text/css" media="all" rel="stylesheet"/>
    <script src="[!--news.url--]skin/default/js/jquery-1.8.2.min.js" type="text/javascript"></script>
    <script src="[!--news.url--]skin/default/js/myfocus-2.0.4.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="[!--news.url--]skin/default/js/mF_tbhuabao.js"></script>
    <script src="[!--news.url--]skin/default/js/jquery.SuperSlide.js" type="text/javascript"></script>
    <script src="[!--news.url--]skin/default/js/custom.js" type="text/javascript"></script>
</head>
<body>
<div class="g-hd">
    <ul class="m-nav-1">

        <li><a href="http://hao.szhgh.com/" title="点此进入红歌会网址导航" target="_blank">网址导航</a></li>
        <li><a href="[!--news.url--]">首页</a></li>
        <li><a target="_blank" title="资讯中心" href="[!--news.url--]Article/news/">资讯中心</a></li>
        <li><a target="_blank" title="纵论天下" href="[!--news.url--]Article/opinion/">纵论天下</a></li>
        <li><a target="_blank" title="红色中国" href="[!--news.url--]Article/red-china/">红色中国</a></li>
        <li><a target="_blank" title="点此进入专题中心" href="[!--news.url--]special">专题中心</a></li>
        <li><a target="_blank" title="点此进入学者专栏" href="[!--news.url--]xuezhe/">学者专栏</a></li>
        <li><a href="[!--news.url--]e/member/cp/">会员中心</a></li>
        <li><a href="[!--news.url--]e/DoInfo/AddInfo.php?mid=10&amp;enews=MAddInfo&amp;classid=29">我要投稿</a></li>
    </ul>
    <div class="account m-log">
        <script>
            document.write('<script src="[!--news.url--]e/member/login/loginjs.php?t=' + Math.random() + '"><' + '/script>');
        </script>
    </div>
    <h1><a href="http://mzd.szhgh.com/">纪念毛泽东诞辰121周年</a></h1>
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
        <li><a href="[!--news.url--]html/pic/" target="_blank">图片文章</a></li>
    </ul>
    <div class="m-crm">
        <strong>当前位置：[!--newsnav--]</strong>
    </div>
</div>
<div class="g-mn">
<div class="g-mnc">

    <div class="g-box-4">
        <h2>头条文章</h2>

        <?php
        /*
        $strClass = $class_r[58]['sonclass'];
        $strClass = str_replace('|', ',', $strClass);
        $strClass = substr($strClass, 1, strlen($strClass) - 2);
        */
        $strHtml = '';
        $cs = 0;
        $sql = $empire->query("select title,titleurl,smalltext,titlepic,newstime,ispic from {$dbtbpre}ecms_article where classid =$navclassid and firsttitle>=1 order by newstime desc limit 0,6 ");
        while ($r = $empire->fetch($sql, MYSQL_ASSOC)) {
            $smalltext= usr_esub($r['smalltext'],120);
            if ($cs > 2)
                $strCss = "m-pt-1 z-hid";
            else
                $strCss = "m-pt-1";
            $strHtml .= '
                  <div class="' . $strCss . '">
                ';
            if ($r['ispic']) {
                $strHtml .= '
                    <a href="' . $r['titleurl'] . '" title="' . $r['title'] . '" target="_blank" ><img src="' . sys_ResizeImg($r['titlepic'], 226, 169, 1, '') . '" /></a>
                ';
            }
            $strHtml .= '
                    <h3><a href="' . $r['titleurl'] . '" title="' . $r['title'] . '" target="_blank" >' . esub($r['title'], 50) . '</a></h3>
                    <p>' . $smalltext . '</p>
                    <span class="f-r">' . date('Y-m-d', $r['newstime']) . '</span>
                </div>
                ';
            $cs++;
        }
        echo $strHtml;
        ?>

        <div class="u-showmore">展开更多</div>
    </div>
    <div class="g-box-4">
        <h2>推荐文章</h2>
        <?php
        $strHtml = '';
        $cs = 0;
        $sql = $empire->query("select title,titleurl,smalltext,titlepic,newstime,ispic from {$dbtbpre}ecms_article where classid =$navclassid and isgood>1 order by newstime desc limit 0,6 ");
        while ($r = $empire->fetch($sql, MYSQL_ASSOC)) {
            $smalltext = usr_esub($r['smalltext'],120);
            if ($cs > 2)
                $strCss = "m-pt-1 z-hid";
            else
                $strCss = "m-pt-1";
            $strHtml .= '
                  <div class="' . $strCss . '">
                ';
            if ($r['ispic']) {
                $strHtml .= '
                    <a href="' . $r['titleurl'] . '" title="' . $r['title'] . '" target="_blank" ><img src="' . sys_ResizeImg($r['titlepic'], 226, 169, 1, '') . '" /></a>
                ';
            }
            $strHtml .= '
                    <h3><a href="' . $r['titleurl'] . '" title="' . $r['title'] . '" target="_blank" >' . esub($r['title'], 50) . '</a></h3>
                    <p>' . $smalltext . '</p>
                    <span class="f-r">' . date('Y-m-d', $r['newstime']) . '</span>
                </div>
                ';
            $cs++;
        }
        echo $strHtml;
        ?>
        <div class="u-showmore">展开更多</div>
    </div>
    <script type="text/javascript">
        $(".u-showmore").on("click", function () {
            var c = 0;
            if ($(this).text().length > 2) {
                $(this).parent().children().fadeIn("slow");
                $(this).remove();
            }
        });
    </script>
    <div class="g-box-4">
        <h2>最新文章</h2>

        [!--empirenews.listtemp--]
        <!--list.var1-->
        [!--empirenews.listtemp--]

    </div>
    <div class="page"><strong>[!--show.page--]</strong></div>

</div>
<script type="text/javascript">
    buildPageAnchor(".g-box-4 H2:contains('最新文章')",".g-mnc .page strong","panchortag")
    /**
     * 生成具有锚点标志的链接
     * @param anchorPath 锚点JQ表达式
     * @param pagerBoxPath 页码的外壳的JQ表达式
     * @param anchorName 锚点名
     * @returns {boolean}
     * @example buildPageAnchor(".g-box-4 H2:contains('最新文章')",".g-mnc .page strong","panchortag")
     */
    function buildPageAnchor(anchorPath,pagerBoxPath,anchorName){
        if(!anchorPath || !pagerBoxPath) return false;
        if(anchorPath instanceof jQuery == false)  anchorPath = $(anchorPath);
        if(pagerBoxPath instanceof jQuery == false)  pagerBoxPath = $(pagerBoxPath);
        if(anchorPath.length==0 || pagerBoxPath.length==0)return false;
        if(!anchorName) anchorName = 'panchortag';
        anchorPath.wrap("<a id='"+anchorName+"' name='"+anchorName+"'></a>");
        var np1 = $('A',pagerBoxPath);
        np1.each(function(index,value){
            var gx1 = value.href;
            if(gx1.indexOf('#')<0){
                value.href = gx1+'#'+anchorName;
            }
        });
        var np2 = $('select[name="select"] OPTION',pagerBoxPath);
        np2.each(function(index,v){
            var gx1 = v.value;
            if(gx1.indexOf('#')<0){
                v.value = gx1+'#'+anchorName;
            }
        });
        return true;
    }
</script>
<div class="g-sd">
    <div class="m-lst">
        <h2>图片文章</h2>

        <p class="u-more"><a href="[!--news.url--]html/pic/" target="_blank">更多</a></p>

        <div class="m-pt">
            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(58,1,0,1,' isgood>1 ','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
            <a href="<?= $bqsr['titleurl'] ?>" target="_blank" title="<?= $bqr['title'] ?>"><img
                    src="<?= sys_ResizeImg($bqr['titlepic'], 384, 270, 1, '') ?>" alt="<?= $bqr['title'] ?>"/></a>

            <h3><a href="<?= $bqsr['titleurl'] ?>" target="_blank"
                   title="<?= $bqr['title'] ?>"><?= esub($bqr['title'], 32) ?></a></h3>
            <?php
}
}
?>
        </div>
        <ul>
            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(58,10,0,1,'','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
            <li>
                <a href="<?= $bqsr['titleurl'] ?>" target="_blank"
                   title="<?= $bqr['title'] ?>"><?= esub($bqr['title'], 32) ?></a>
            </li>
            <?php
}
}
?>

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
            <a href="<?= $bqsr['titleurl'] ?>" title="<?= $bqr['title'] ?>" target="_blank"><img
                    src="<?= sys_ResizeImg($bqr[titlepic], 226, 169, 1, '') ?>"/></a>

            <h3><a href="<?= $bqsr['titleurl'] ?>" title="<?= $bqr['title'] ?>"
                   target="_blank"><?= esub($bqr['title'], 34) ?></a></h3>
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
            <li><a href="<?= $bqsr['titleurl'] ?>" title="<?= $bqr['dtitle'] ?>"
                   target="_blank"><?= esub($bqr['title'], 36) ?></a></li>
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
                $strHtml = '';
                $c = 0;
                $sql = $empire->query("select title,titleurl from {$dbtbpre}ecms_article  where classid in($strClass) and newstime > UNIX_TIMESTAMP()-86400*1 order by onclick desc limit 10");
                while ($r = $empire->fetch($sql, MYSQL_ASSOC)) {
                    $c++;
                    if ($c <= 3)
                        $strCss = 's-o';
                    else
                        $strCss = '';
                    $strHtml .= '<li class="' . $strCss . '"><a href="' . $r['titleurl'] . '" title="' . $r['title'] . '" target="_blank" ><span>' . $c . '</span>' . esub($r['title'], 34) . '</a></li>';
                }
                echo $strHtml;
                ?>
            </ol>
            <ol>
                <?php
                $strHtml = '';
                $c = 0;
                $sql = $empire->query("select title,titleurl from {$dbtbpre}ecms_article  where classid in($strClass) and newstime > UNIX_TIMESTAMP()-86400*7 order by onclick desc limit 10");
                while ($r = $empire->fetch($sql, MYSQL_ASSOC)) {
                    $c++;
                    if ($c <= 3)
                        $strCss = 's-o';
                    else
                        $strCss = '';
                    $strHtml .= '<li class="' . $strCss . '"><a href="' . $r['titleurl'] . '" title="' . $r['title'] . '" target="_blank" ><span>' . $c . '</span>' . esub($r['title'], 34) . '</a></li>';
                }
                echo $strHtml;
                ?>
            </ol>
            <ol>
                <?php
                $strHtml = '';
                $c = 0;
                $sql = $empire->query("select title,titleurl from {$dbtbpre}ecms_article where classid in($strClass) and newstime > UNIX_TIMESTAMP()-86400*30 order by onclick desc limit 10");
                while ($r = $empire->fetch($sql, MYSQL_ASSOC)) {
                    $c++;
                    if ($c <= 3)
                        $strCss = 's-o';
                    else
                        $strCss = '';
                    $strHtml .= '<li class="' . $strCss . '"><a href="' . $r['titleurl'] . '" title="' . $r['title'] . '" target="_blank" ><span>' . $c . '</span>' . esub($r['title'], 34) . '</a></li>';
                }
                echo $strHtml;
                ?>
            </ol>
        </div>
    </div>
    <div class="m-top">
        <h2>评论排行</h2>
        <ul class="hd">
            <li class="z-crt">24小时</li>
            <li>一周</li>
            <li>一月</li>
        </ul>
        <div class="bd">
            <ol>
                <?php
                $strHtml = '';
                $c = 0;
                $sql = $empire->query("select title,titleurl from {$dbtbpre}ecms_article  where classid in($strClass) and newstime > UNIX_TIMESTAMP()-86400*1 order by plnum  desc limit 10");
                while ($r = $empire->fetch($sql, MYSQL_ASSOC)) {
                    $c++;
                    if ($c <= 3)
                        $strCss = 's-o';
                    else
                        $strCss = '';
                    $strHtml .= '<li class="' . $strCss . '"><a href="' . $r['titleurl'] . '" title="' . $r['title'] . '" target="_blank" ><span>' . $c . '</span>' . esub($r['title'], 34) . '</a></li>';
                }
                echo $strHtml;
                ?>
            </ol>
            <ol>
                <?php
                $strHtml = '';
                $c = 0;
                $sql = $empire->query("select title,titleurl from {$dbtbpre}ecms_article  where classid in($strClass) and newstime > UNIX_TIMESTAMP()-86400*7 order by plnum  desc limit 10");
                while ($r = $empire->fetch($sql, MYSQL_ASSOC)) {
                    $c++;
                    if ($c <= 3)
                        $strCss = 's-o';
                    else
                        $strCss = '';
                    $strHtml .= '<li class="' . $strCss . '"><a href="' . $r['titleurl'] . '" title="' . $r['title'] . '" target="_blank" ><span>' . $c . '</span>' . esub($r['title'], 34) . '</a></li>';
                }
                echo $strHtml;
                ?>
            </ol>
            <ol>
                <?php
                $strHtml = '';
                $c = 0;
                $sql = $empire->query("select title,titleurl from {$dbtbpre}ecms_article where classid in($strClass) and newstime > UNIX_TIMESTAMP()-86400*30 order by plnum  desc limit 10");
                while ($r = $empire->fetch($sql, MYSQL_ASSOC)) {
                    $c++;
                    if ($c <= 3)
                        $strCss = 's-o';
                    else
                        $strCss = '';
                    $strHtml .= '<li class="' . $strCss . '"><a href="' . $r['titleurl'] . '" title="' . $r['title'] . '" target="_blank" ><span>' . $c . '</span>' . esub($r['title'], 34) . '</a></li>';
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
    <div class="copyright">
        <ul>
            <li class='copy_left'>
                <div>
                    <a href="[!--news.url--]" title="红歌会网" target="_blank">红歌会网</a>
                    | <a href="[!--news.url--]" title="网址导航" target="_blank">网址导航</a>
                    | <a href="[!--news.url--]html/rank.html" title="排行榜" target="_blank">排行榜</a>
                    | <a href="[!--news.url--]Article/opinion/wp/20257.html" title="联系我们" target="_blank">联系我们</a>
                    | <a href="[!--news.url--]Article/opinion/zatan/13968.html" title="在线提意见" target="_blank">在线提意见</a>
                </div>
                <div>
                    <a href="http://www.miitbeian.gov.cn" target="_blank">粤ICP备12077717号-1</a>
                    |
                    <script src="http://s20.cnzz.com/stat.php?id=3051861&web_id=3051861&show=pic1"
                            language="JavaScript"></script>

                    <script type="text/javascript">
                        var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
                        document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F2e62d7088e3926a4639571ba4c25de10' type='text/javascript'%3E%3C/script%3E"));
                    </script>


                </div>
            </li>
            <li class="focusbutton">
                <a class="rss" href="[!--news.url--]e/web/?type=rss2" title="欢迎订阅红歌会网" target="_blank"></a>
                <a class="sinaweibo" href="http://weibo.com/szhgh?topnav=1&wvr=5&topsug=1" title="欢迎关注红歌会网新浪微博"
                   target="_blank"></a>
                <a class="qqweibo"
                   href="http://follow.v.t.qq.com/index.php?c=follow&amp;a=quick&amp;name=szhgh001&amp;style=5&amp;t=1737191719&amp;f=1"
                   title="欢迎关注红歌会网腾讯微博" target="_blank"></a>
                <a class="qqmsg" href="http://wpa.qq.com/msgrd?Uin=1737191719" title="欢迎通过QQ联系我们" target="_blank"></a>
                <a class="email" href="mailto:szhgh001@163.com" title="欢迎投稿或反映问题" target="_blank"></a>
            </li>
            <li class="focusmsg">
                <div>网站QQ：<a href="http://wpa.qq.com/msgrd?Uin=1737191719" title="欢迎通过QQ联系我们"
                             target="_blank">1737191719</a>&nbsp;&nbsp;红歌会网粉丝QQ群：35758473
                </div>
                <div>(投稿)邮箱：<a href="mailto:szhgh001@163.com" title="欢迎投稿或反映问题" target="_blank">szhgh001@163.com</a>
                </div>
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

            <div id="username" class="txtfield username"><input name="username" type="text" size="16"/></div>
            <div id="password" class="txtfield password"><input name="password" type="password" size="16"/></div>
            <div class="forgetmsg"><a href="/e/member/GetPassword/" title="点此取回密码" target="_blank">忘记密码？</a></div>
            <input type="submit" name="Submit" value="登陆" class="inputSub flatbtn-blu"/>
        </div>
        <div class="reg right">
            <div class="regmsg"><span>还不是会员？</span></div>
            <input type="button" name="Submit2" value="立即注册" class="regbutton"
                   onclick="window.open('[!--news.url--]e/member/register/');"/>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(function () {
        $('#loginform').submit(function (e) {
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

[!--page.stats--]
</body>
</html>
