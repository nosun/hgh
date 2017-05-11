<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:wb="http://open.weibo.com/wb" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>红歌会网址导航 - 唱响红歌，弘扬正气！</title>
        <meta name="keywords" content="[!--pagekeywords--]" />
        <meta name="description" content="[!--pagedescription--]" />
        <link rel="shortcut icon" href="http://www.szhgh.com/skin/default/images/favicon.ico" /> 
        <link href="http://www.szhgh.com/skin/default/css/nav.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="http://www.szhgh.com/skin/default/js/jquery-1.8.2.min.js"></script>
        <script type="text/javascript" src="http://www.szhgh.com/skin/default/js/custom.js"></script>
    </head>
    <body>

            <!-- header -->
            <div class="wrap wrap-1">
                <div class="header width clearfix">
                    <a href="http://hao.szhgh.com/" title="红歌会网址导航" target="_blank"><img src="http://www.szhgh.com/skin/default/images/navlogo.jpg" /></a>
                    <div class="box right">
                        <div class="account left">
                            <a class='first' href="http://www.szhgh.com/" title="红歌会网首页" target="_blank">红歌会网首页</a>
                            <script>
                                document.write('<script src="http://www.szhgh.com/e/member/login/loginjs.php?t=' + Math.random() + '"><' + '/script>');
                            </script>
                        </div>
                        <div class="date right">
                            <script type="text/javascript">
                                //============================日期

                                var sWeek = new Array("星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六");
                                var dNow = new Date();
                                var CalendarData = new Array(100);
                                var madd = new Array(12);
                                var tgString = "甲乙丙丁戊己庚辛壬癸";
                                var dzString = "子丑寅卯辰巳午未申酉戌亥";
                                var numString = "一二三四五六七八九十";
                                var monString = "正二三四五六七八九十冬腊";
                                var weekString = "日一二三四五六";
                                var sx = "鼠牛虎兔龙蛇马羊猴鸡狗猪";
                                var cYear, cMonth, cDay, TheDate;
                                CalendarData = new Array(
                                0xA4B, 0x5164B, 0x6A5, 0x6D4, 0x415B5, 0x2B6, 0x957, 0x2092F, 0x497, 0x60C96,
                                0xD4A, 0xEA5, 0x50DA9, 0x5AD, 0x2B6, 0x3126E, 0x92E, 0x7192D, 0xC95, 0xD4A,
                                0x61B4A, 0xB55, 0x56A, 0x4155B, 0x25D, 0x92D, 0x2192B, 0xA95, 0x71695, 0x6CA,
                                0xB55, 0x50AB5, 0x4DA, 0xA5B, 0x30A57, 0x52B, 0x8152A, 0xE95, 0x6AA, 0x615AA,
                                0xAB5, 0x4B6, 0x414AE, 0xA57, 0x526, 0x31D26, 0xD95, 0x70B55, 0x56A, 0x96D,
                                0x5095D, 0x4AD, 0xA4D, 0x41A4D, 0xD25, 0x81AA5, 0xB54, 0xB6A, 0x612DA, 0x95B,
                                0x49B, 0x41497, 0xA4B, 0xA164B, 0x6A5, 0x6D4, 0x615B4, 0xAB6, 0x957, 0x5092F,
                                0x497, 0x64B, 0x30D4A, 0xEA5, 0x80D65, 0x5AC, 0xAB6, 0x5126D, 0x92E, 0xC96,
                                0x41A95, 0xD4A, 0xDA5, 0x20B55, 0x56A, 0x7155B, 0x25D, 0x92D, 0x5192B, 0xA95,
                                0xB4A, 0x416AA, 0xAD5, 0x90AB5, 0x4BA, 0xA5B, 0x60A57, 0x52B, 0xA93, 0x40E95);
                                madd[0] = 0; madd[1] = 31; madd[2] = 59; madd[3] = 90;
                                madd[4] = 120; madd[5] = 151; madd[6] = 181; madd[7] = 212;
                                madd[8] = 243; madd[9] = 273; madd[10] = 304; madd[11] = 334;
                                function GetBit(m, n) { return (m >> n) & 1; }
                                function e2c() {
                                    TheDate = (arguments.length != 3) ? new Date() : new Date(arguments[0], arguments[1], arguments[2]);
                                    var total, m, n, k;
                                    var isEnd = false;
                                    var tmp = TheDate.getFullYear();
                                    total = (tmp - 1921) * 365 + Math.floor((tmp - 1921) / 4) + madd[TheDate.getMonth()] + TheDate.getDate() - 38; if (TheDate.getYear() % 4 == 0 && TheDate.getMonth() > 1) { total++; } for (m = 0; ; m++) { k = (CalendarData[m] < 0xfff) ? 11 : 12; for (n = k; n >= 0; n--) { if (total <= 29 + GetBit(CalendarData[m], n)) { isEnd = true; break; } total = total - 29 - GetBit(CalendarData[m], n); } if (isEnd) break; } cYear = 1921 + m; cMonth = k - n + 1; cDay = total; if (k == 12) { if (cMonth == Math.floor(CalendarData[m] / 0x10000) + 1) { cMonth = 1 - cMonth; } if (cMonth > Math.floor(CalendarData[m] / 0x10000) + 1) { cMonth--; } }
                                }
                                function GetcDateString() {
                                    var tmp = ""; tmp += tgString.charAt((cYear - 4) % 10);
                                    tmp += dzString.charAt((cYear - 4) % 12);
                                    tmp += "年 ";
                                    if (cMonth < 1) { tmp += "(闰)"; tmp += monString.charAt(-cMonth - 1); } else { tmp += monString.charAt(cMonth - 1); } tmp += "月"; tmp += (cDay < 11) ? "初" : ((cDay < 20) ? "十" : ((cDay < 30) ? "廿" : "三十"));
                                    if (cDay % 10 != 0 || cDay == 10) { tmp += numString.charAt((cDay - 1) % 10); } return tmp;
                                }
                                function GetLunarDay(solarYear, solarMonth, solarDay) {
                                    if (solarYear < 1921 || solarYear > 2020) {
                                        return "";
                                    } else { solarMonth = (parseInt(solarMonth) > 0) ? (solarMonth - 1) : 11; e2c(solarYear, solarMonth, solarDay); return GetcDateString(); }
                                }
                                var D = new Date();
                                var yy = D.getFullYear();
                                var mm = D.getMonth() + 1;
                                var dd = D.getDate();
                                var ww = D.getDay();
                                var ss = parseInt(D.getTime() / 1000);
                                function getFullYear(d) {// 修正firefox下year错误   
                                    yr = d.getYear(); if (yr < 1000)
                                    yr += 1900; return yr;
                                }
                                function showDate() {
                                    timeString = new Date().toLocaleTimeString();
                                    var sValue = "<strong>" + dNow.getDate() + "</strong>" + getFullYear(dNow) + "年" + (dNow.getMonth() + 1) + "月" + dNow.getDate() + "日 " + sWeek[dNow.getDay()] + "  "; // + " " + timeString + " "
                                    sValue += GetLunarDay(yy, mm, dd);
                                    var svalue1 = getFullYear(dNow) + "年" + (dNow.getMonth() + 1) + "月" + dNow.getDate() + "日";
                                    var svalue2 = timeString;
                                    var svalue3 = GetLunarDay(yy, mm, dd);
                                    var sx2 = sx.substr(dzString.indexOf(svalue3.substr(1, 1)), 1);
                                    var svalue33 = svalue3.substr(0, 3)
                                    var svalue333 = svalue33.substr(0, 2) + "(" + sx2 + ")" + svalue33.substr(2, 1);
                                    var sx22 = "农历" + svalue3.substr(4, 6);
                                    document.write(sValue);
                                }
                            </script>
                            <script language="javascript">showDate()</script>
                        </div>                        
                    </div>

                </div>
            </div>
            <!-- header end -->
            
            <!-- wrap -->
            <div class="wrap wrap-2 width clearfix">
                <div class="sidebox left">
                    <div class="section sectionA">
                        <div class="section_header"><strong><a href="http://www.szhgh.com/bianmin" title="便民" target="_blank">便民</a></strong></div>
                        <div class="section_content">
                            <ul class="clearfix">
                                <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(97,16,0,0);
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
                            </ul>
                        </div>
                    </div>
                    <div class="section sectionB">
                        <div class="section_header">
                            <strong><a href="http://www.szhgh.com/Article/news/" title="资讯中心" target="_blank">资讯中心</a></strong>
                            <div class="clear"></div>
                        </div>
                        <div class="section_content">
                            <ul class="firsttitle">
                                <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(36,1,0,1,'firsttitle=1','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                <li>
                                    <h3><a href="<?=$bqsr[titleurl]?>" title="<?=$bqr[title]?>" target="_blank"><?=$bqr[title]?></a></h3>
                                    <div class='pictext clearfix'>
                                        <a class="pic left" href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr[titlepic],96,72,1,'')?>" /></a>
                                        <div class="text right">
                                            <p><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),70))?><a class="readall" href="<?=$bqsr['titleurl']?>" title="点击阅读全文" target="_blank">[全文]</a></p>
                                        </div>
                                    </div>                                  
                                </li>
                                <?php
}
}
?>
                            </ul>
                            <ul class="list">
                                <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(36,8,0,0,'','newstime DESC');
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
<div class="section" style="border: 0pt none; display: inline-block; width: 250px; height: 250px;">
<script type="text/javascript">
    /*250*250 创建于 2016/10/27*/
    var cpro_id = "u2800222";
</script>
<script type="text/javascript" src="http://cpro.baidustatic.com/cpro/ui/c.js"></script>
</div>
                    <div class="section sectionB">
                        <div class="section_header">
                            <strong><a href="http://www.szhgh.com/Article/opinion/" title="纵论天下" target="_blank">纵论天下</a></strong>
                            <div class="clear"></div>
                        </div>
                        <div class="section_content">
                            <ul class="firsttitle">
                                <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(47,1,0,1,'firsttitle=1','newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                <li>
                                    <h3><a href="<?=$bqsr[titleurl]?>" title="<?=$bqr[title]?>" target="_blank"><?=$bqr[title]?></a></h3>
                                    <div class='pictext clearfix'>
                                        <a class="pic left" href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr[titlepic],96,72,1,'')?>" /></a>
                                        <div class="text right">
                                            <p><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),70))?><a class="readall" href="<?=$bqsr['titleurl']?>" title="点击阅读全文" target="_blank">[全文]</a></p>
                                        </div>
                                    </div>                                  
                                </li>
                                <?php
}
}
?>
                            </ul>
                            <ul class="list">
                                <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(47,8,0,0,'','newstime DESC');
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
                </div>
                <div class="mainbox right">
                    <div class="main_header">
                        <strong class="recommends right"><a href="http://www.szhgh.com/e/tool/gbook/?bid=1" title="欢迎推荐网址" target="_blank">欢迎推荐网址</a></strong>
                        <strong><a title="推荐网站">推荐网站</a></strong>
                    </div>
                    <div class="container">
                        <div class="list1">
                            <ul class="clearfix">
                                <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq('copyfrom',18,18,0,'istop=1','newstime DESC');
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
                            </ul>
                        </div>
                        <div class="list2">
                            <ul class="clearfix">
                                <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq('copyfrom',24,18,0,'firsttitle=1','newstime DESC');
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
                            </ul>
                        </div>                        
                    </div>
                    <!--<div class="tagbox">
                        <strong>热门标签</strong>
                    </div>-->
                    <div class="section sectionD clearfix">
                        <div class="section_header colorbg1 left"><strong><a href="http://hao.szhgh.com/red" title="红色" target="_blank">红色</a></strong></div>
                        <div class="section_content right">
                            <ul class="clearfix">
                                <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(90,30,0,0);
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
                            </ul>
                        </div>
            
                    </div>
                    <div class="section sectionD clearfix">
                        <div class="section_header colorbg3 left"><strong><a href="http://www.szhgh.com/html/blog/" title="博客" target="_blank">博客</a></strong></div>
                        <div class="section_content right">
                            <ul class="clearfix">
                                <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq('copyfrom',30,18,0,"classid=98 and blogurl>'0'",'newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                <?php
                                    if($bqr[blogurl]){
                                ?>
                                <li><a href="<?=$bqr['blogurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></li>
                                <?php
                                    }
                                ?>
                                <?php
}
}
?>
                            </ul>
                        </div>
                    </div>
                    <div class="section sectionD clearfix">
                        <div class="section_header colorbg4 left"><strong><a href="http://www.szhgh.com/html/weibo/" title="微博" target="_blank">微博</a></strong></div>
                        <div class="section_content right">
                            <ul class="clearfix">
                                <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq('copyfrom',30,18,0,"classid=98 and weibourl>'0'",'newstime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                                <?php
                                    if($bqr[weibourl]){
                                ?>
                                <li><a href="<?=$bqr['weibourl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></li>
                                <?php
                                    }
                                ?>
                                <?php
}
}
?>
                            </ul>
                        </div>
                    </div> 
                    <div class="section sectionD clearfix">
                        <div class="section_header colorbg5 left"><strong><a href="http://hao.szhgh.com/guanwang" title="官网" target="_blank">官网</a></strong></div>
                        <div class="section_content right">
                            <ul class="clearfix">
                                <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(92,30,0,0);
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
                            </ul>
                        </div>
                    </div> 
                    <div class="section sectionD clearfix">
                        <div class="section_header colorbg6 left"><strong><a href="http://hao.szhgh.com/menhu" title="门户" target="_blank">门户</a></strong></div>
                        <div class="section_content right">
                            <ul class="clearfix">
                                <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(93,30,0,0);
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
                            </ul>
                        </div>
                    </div>
                    <div class="section sectionD clearfix">
                        <div class="section_header colorbg7 left"><strong><a href="http://hao.szhgh.com/hongsejingji" title="经济" target="_blank">经济</a></strong></div>
                        <div class="section_content right">
                            <ul class="clearfix">
                                <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(96,30,0,0);
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
                            </ul>
                        </div>
                    </div>
                    <div class="section sectionD clearfix">
                        <div class="section_header colorbg1 left"><strong><a href="http://hao.szhgh.com/news" title="新闻" target="_blank">新闻</a></strong></div>
                        <div class="section_content right">
                            <ul class="clearfix">
                                <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq(102,30,0,0);
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
                            </ul>
                        </div>
                    </div>
                    
                </div>                
            </div>
            
            
            <!-- wrap -->

            <!-- footer -->
            <script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js" type="text/javascript" charset="utf-8"></script>
<div class="footer">
    <div class="copyright">
        <ul class="clearfix">
            <li class='copy_left'>
                <div>
                    <a href="http://www.szhgh.com/" title="红歌会网" target="_blank">红歌会网</a>
                    | <a href="http://www.szhgh.com/html/sitemap.html" title="网站地图" target="_blank">网站地图</a>
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
            
            <script src=http://www.szhgh.com/e/public/onclick/?enews=doclass&classid=34></script>
            
    </body>
</html>