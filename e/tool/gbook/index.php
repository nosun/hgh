<?php
require("../../class/connect.php");
if(!defined('InEmpireCMS'))
{
	exit();
}
require("../../class/db_sql.php");
require("../../class/q_functions.php");
require "../".LoadLang("pub/fun.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
//分类id
$bid=(int)$_GET['bid'];
$gbr=$empire->fetch1("select bid,bname,groupid from {$dbtbpre}enewsgbookclass where bid='$bid'");
if(empty($gbr['bid']))
{
	printerror("EmptyGbook","",1);
}
//权限
if($gbr['groupid'])
{
	include("../../member/class/user.php");
	$user=islogin();
	include("../../data/dbcache/MemberLevel.php");
	if($level_r[$gbr[groupid]][level]>$level_r[$user[groupid]][level])
	{
		echo"<script>alert('您的会员级别不足(".$level_r[$gbr[groupid]][groupname].")，没有权限提交信息!');history.go(-1);</script>";
		exit();
	}
}
esetcookie("gbookbid",$bid,0);
$bname=$gbr['bname'];
$search="&bid=$bid";
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=$public_r['gb_num'];//每页显示条数
$page_line=12;//每页显示链接数
$offset=$start+$page*$line;//总偏移量
$totalnum=(int)$_GET['totalnum'];
if($totalnum>0)
{
	$num=$totalnum;
}
else
{
	$totalquery="select count(*) as total from {$dbtbpre}enewsgbook where bid='$bid' and checked=0";
	$num=$empire->gettotal($totalquery);//取得总条数
}
$search.="&totalnum=$num";
$query="select lyid,name,email,`mycall`,qq,lytime,lytext,retext from {$dbtbpre}enewsgbook where bid='$bid' and checked=0";
$query=$query." order by lyid desc limit $offset,$line";
$sql=$empire->query($query);
$listpage=page1($num,$line,$page_line,$start,$page,$search);
$url="<a href='".ReturnSiteIndexUrl()."'>".$fun_r['index']."</a>&nbsp;>&nbsp;".$fun_r['saygbook'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>留言板 -- 红歌会网</title>
            <meta name="description" content="<?=$bname?>">
            <meta name="keywords" content="<?=$bname?>">
            <link rel="shortcut icon" href="http://www.szhgh.com/skin/default/images/favicon.ico" /> 
            <link href="http://www.szhgh.com/skin/default/css/base.css" rel="stylesheet" type="text/css" />
            <link rel="stylesheet" href="http://www.szhgh.com/skin/default/css/message_board.css" type="text/css" media="screen, projection" />
            <link href="http://www.szhgh.com/skin/default/css/nav.css" rel="stylesheet" type="text/css" />
            <script type="text/javascript" src="http://www.szhgh.com/skin/default/js/jquery-1.8.2.min.js"></script>
            <script src="http://www.szhgh.com/skin/default/js/custom.js" type="text/javascript"></script>
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
            
            <div id="main">

                <div class="position_cur">
                    <div class="subnav">现在的位置：<a href="http://www.szhgh.com/">红歌会网</a>&nbsp;&gt;&nbsp;留言板</div>
                </div>
                <div class="page_mark"><strong>红歌会网址导航意见簿</strong></div>
                
                <div class="words">
                    <p>目前红歌会网址大全(测试版)虽已发布，但仍有很多网站尚未收录，欢迎您向我们推荐您喜欢的网站、微博、博客。也欢迎您提出您的宝贵意见和建议，以帮助我们不断完善和改进。谢谢您的参与！</p>
                </div>
                
                <div class="form_module">
                    <form action="../../enews/index.php" method="post" name="form1" id="form1">
                        <div class="form_clue"><strong>请您留言:</strong></div>
                        <div class="form_item">
                            <label>昵称 :</label>
                            <div class="form_input"><input name="name" type="text" id="name" />&nbsp;（必填）</div>
                        </div>
                        <div class="form_item">
                            <label>留言内容（必填） :</label>
                            <div class="form_input"><textarea name="lytext" cols="60" rows="12" id="lytext"></textarea></div>
                        </div> 
                        
                        <div class="form_clue"><strong>如果您愿意今后方便我们联系，也可以留下您的联系方式:</strong></div>
                        
                        <div class="form_item">
                            <label>联系邮箱 :</label>
                            <div class="form_input"><input name="email" type="text" id="email" /></div>
                        </div>                                                                                            
                        <div class="form_item">
                            <label>联系电话 :</label>
                            <div class="form_input"><input name="call" type="text" id="call" /></div>
                        </div>
                        <div class="form_item">
                            <label>QQ :</label>
                            <div class="form_input"><input name="qq" type="text" id="qq" /></div>
                        </div>
                        <?
                            if($public_r['gbkey_ok']){
                        ?>
                        <div class="form_item">
                            <label>验证码：</label>
                            <div class="form_input">
                                <input onfocus="javascript:document.getElementById('gbookey').src='/e/ShowKey/?v=gbook&amp;n='+Math.random()" name="key" type="text" id="key" size="6"> 
                                <img id="gbookey" onclick="javascript:this.src='/e/ShowKey/?v=gbook&amp;n='+Math.random()" src="../../ShowKey/?v=gbook" />
                            </div>
                        </div> 
                        <?
                            }	
                        ?>
                        <div class="form_button">
                            <label>&nbsp;</label>
                            <div class="form_input">
                                <input type="submit" name="Submit3" value="提交" />
                                <input type="reset" name="Submit22" value="重置" />
                                <input name="enews" type="hidden" id="enews" value="AddGbook" />
                            </div>
                        </div>                                                                                               
                    </form>                        
                </div>
                
                <div id="message" class="message">
                    <ul class="list">
                    
<?
while($r=$empire->fetch($sql))
{
	$r['retext']=nl2br($r[retext]);
	$r['lytext']=nl2br($r[lytext]);
?>

                        <li>
                            <div class="message_header">
                                <span class="name">网友: <strong><?=$r[name]?></strong> </span>
                                <span class="lytime">留言时间: <?=$r[lytime]?> </span>
                                <div class="clear_float"></div>
                            </div>
                            <div class="content">
                                <div class="lytext"> <?=$r[lytext]?></div>
                                
<?
if($r[retext])
{
?>

                                <div class="reply"><img src="../../data/images/regb.gif" width="18" height="18" />&nbsp;&nbsp;<strong><font color="#FF0000">回复:</font><br /></strong> <?=$r[retext]?></div> 
                                
<?
}
?>
                            </div>
                        </li>
                    
<?
}
?>
                        
                    </ul>  
                    <center style="display: none;" id="paging" class="paging">分页: <?=$listpage?></center>
                </div>
                <script type="text/javascript">
                    $(function(){
                        var $count=$("#message ul li").size()
                            $a_count=$("#paging a").size(),
                            nomessage="<p class='nomessage'>暂无留言</p>";
                        if($count==0){
                            $("#paging").hide();
                            $("#message ul").append(nomessage);
                        }else{
                            $("#message ul li:last").addClass("last");
                            if($a_count>0){
                                $("#paging").show();
                            }
                        }
                    });
                </script>

            </div>

            <div>
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

            </div>
	</body>
</html>
<?php
db_close();
$empire=null;
?>