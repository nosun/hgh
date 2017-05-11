<?php
require("../class/connect.php");
if(!defined('InEmpireCMS'))
{
	exit();
}
require("../class/db_sql.php");
require("../data/dbcache/class.php");
require LoadLang("pub/fun.php");
require("../class/schallfun.php");
$link=db_connect();
$empire=new mysqlquery();
eCheckCloseMods('sch');//关闭模块
$searchtime=time();
$totalnum=(int)$_GET['totalnum'];
$firstsearch=0;
if($totalnum<1)
{
	$firstsearch=1;
	//搜索间隔
	$lastsearchtime=(int)getcvar('lastschalltime');
	if($lastsearchtime)
	{
		if($searchtime-$lastsearchtime<$public_r[schalltime])
		{
			printerror('SchallOutTime','',1);
		}
	}
	//设置最后搜索时间
	esetcookie('lastschalltime',$searchtime,$searchtime+3600*24);
}
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$page_line=$public_r['schallpagenum'];//每页显示链接数
$line=$public_r['schallnum'];//每页显示记录数
$offset=$start+$page*$line;//总偏移量
//编码
$iconv='';
$char='';
$targetchar='';
if($ecms_config['sets']['pagechar']!='gb2312')
{
	include_once(ECMS_PATH.'e/class/doiconv.php');
	$iconv=new Chinese('');
	$char=$ecms_config['sets']['pagechar']=='big5'?'BIG5':'UTF8';
	$targetchar='GB2312';
}
$schallr=ReturnSearchAllSql($_GET);
require("../data/dbcache/SearchAllTb.php");
$keyboard=$schallr['keyboard'];
$query="select id,classid from {$dbtbpre}enewssearchall where ".$schallr['where'];
if($totalnum<1)
{
	$totalquery="select count(*) as total from {$dbtbpre}enewssearchall where ".$schallr['where'];
	$num=$empire->gettotal($totalquery);
	if(empty($num))
	{
		printerror('SchallNotRecord','',1);
	}
}
else
{
	$num=$totalnum;
}
$search=$schallr['search']."&totalnum=".$num;
$query.=" order by infotime desc limit $offset,$line";
$sql=$empire->query($query);
$listpage=page1($num,$line,$page_line,$start,$page,$search);
$url="<a href='".ReturnSiteIndexUrl()."'>".$fun_r['index']."</a>&nbsp;>&nbsp;".$fun_r['SearchAllNav'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>搜索 - Powered by EmpireCMS</title>
<link href="http://www.szhgh.com/skin/default/css/common.css" rel="stylesheet" type="text/css" />
<link href="http://www.szhgh.com/skin/default/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://www.szhgh.com/skin/default/js/tabs.js"></script>
<style type="text/css">
<!--
.r {
display:inline;
font-weight:normal;
margin:0;
font-size:16px;
margin-top:10px;
}
.a{color:green}
.fl{color:#77c}
-->
</style>
</head>
<body class="listpage">
<div class="header">
    <div class="toolbar">
        <div class="login">
            <script>
                document.write('<script src="http://www.szhgh.com/e/member/login/loginjs.php?t='+Math.random()+'"><'+'/script>');
            </script>
        </div>
        <div class="search"><script src="http://www.szhgh.com/d/js/js/search_news1.js" type="text/javascript"></script></div>
        <div class="top_menu"><a onclick="window.external.addFavorite(location.href,document.title)" href="#ecms">加入收藏</a> | <a onclick="this.style.behavior='url(#default#homepage)';this.setHomePage('http://www.szhgh.com/')" href="#ecms">设为首页</a> | <a href="http://www.szhgh.com/e/member/cp/">会员中心</a> | <a href="http://www.szhgh.com/e/DoInfo/">我要投稿</a> | <a href="http://www.szhgh.com/e/web/?type=rss2&classid=0" target="_blank">RSS<img src="http://www.szhgh.com/skin/default/images/rss.gif" border="0" hspace="2" /></a></div>  
    </div>
    <div class="redlogo">
        <div class="logo"><a href="http://www.szhgh.com/" title="红歌会网首页"><img src="http://www.szhgh.com/skin/default/images/logo.jpg" /></a></div>
        <div class="siteintro_left"></div>
        <div class="siteintro_right"></div>
    </div>
    <div class="menu">
        <span class="firstsub"><a href="http://www.szhgh.com/">首页</a></span><span class="sub"><a href="http://www.szhgh.com/html/mao.html">毛泽东</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=6">红色中国</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=7">唱读讲传</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=31">时政纵横</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=9">网友杂谈</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=10">聚焦转基因</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=11">人民健康</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=12">历史视野</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=32">工农之声</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=14">人民文艺</a></span><span class="sub"><a href="http://www.szhgh.com/e/action/ListInfo/?classid=15">读书交流</a></span><span class="sub"><a href="http://www.szhgh.com/html/rank.php">排行榜</a></span><span class="sub"><a href="http://www.szhgh.com/bbs/forum.php">红歌会论坛</a></span><span class="sub"><a href="http://www.szhgh.com/bbs/home.php">红歌会圈子</a></span>
    </div>
</div>
<table width="100%" border="0" cellspacing="10" cellpadding="0">
	<tr valign="top">
		<td class="list_content"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="position">
				<tr>
					<td>现在的位置：<a href="http://www.szhgh.com/">首页</a>&nbsp;>&nbsp;搜索</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="box">
				<tr>
					<td><form action='index.php' method="GET" name="search_news" id="search_news">
							<table width="100%" border="0" cellspacing="6" cellpadding="0">
								<tr>
									<td height="32">关键字：
										<input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>" size="42" />
                    <select name="field" id="field">
                      <option value="1">全文</option>
                      <option value="2">标题</option>
                      <option value="3">内容</option>
                    </select> 
                    <input type="submit" name="Submit22" value="搜索" />
                    <font color="#666666">(多个关键字请用&quot;空格&quot;隔开)</font> </td>
								</tr>
							</table>
						</form>
						<table width="100%" border="0" cellpadding="0" cellspacing="6">
							<tr>
								<td>系统搜索到约有<strong><?=$num?></strong>项符合<strong><?=$keyboard?></strong>的查询结果</td>
							</tr>
						</table>
						
<?php
$no=$offset;
$subnum=120;
$formatdate="Y-m-d H:i:s";
while($r=$empire->fetch($sql))
{
	$tbname=$class_r[$r[classid]]['tbname'];
	if(empty($tbname))
	{
		continue;
	}
	$titlefield=$schalltb_r[$tbname]['titlefield'];
	$smalltextfield=$schalltb_r[$tbname]['smalltextfield'];
	$infor=$empire->fetch1("select id,classid,titlepic,newstime,isurl,titleurl,".$titlefield.",".$smalltextfield." from {$dbtbpre}ecms_".$tbname." where id='$r[id]' limit 1");
	if(empty($infor['id']))
	{
		continue;
	}
	$no++;
	$titleurl=sys_ReturnBqTitleLink($infor);
	$titlepic=$infor['titlepic']?$infor['titlepic']:$public_r['newsurl']."e/data/images/notimg.gif";
	$smalltext=SubSchallSmalltext($infor[$smalltextfield],$subnum);
	$title=DoReplaceFontRed($infor[$titlefield],$keyboard);
	$smalltext=DoReplaceFontRed($smalltext,$keyboard);
	$newstime=date($formatdate,$infor['newstime']);
?>

						<h2 class="r"><span><?=$no?>.</span> <a class="l" href="<?=$titleurl?>" target="_blank"><?=$title?></a></h2>
						<table width="80%" border="0" cellpadding="0" cellspacing="0">
							<tbody>
							<tr>
								<td><?=$smalltext?></td>
							</tr>
							<tr>
								<td><span class="a"><?=$titleurl?> - <?=$newstime?></span></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
							</tbody>
						</table>
						
<?php
}
db_close();
$empire=null;
?>

						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list_page">
							<tr>
								<td><?=$listpage?></td>
							</tr>
						</table></td>
				</tr>
			</table></td>
	</tr>
</table>
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

</body>
</html>