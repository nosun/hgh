<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?=$pagetitle?> 信息评论 - 唱响红歌，弘扬正气！</title>
        <meta name="keywords" content="<?=$pagetitle?> 信息评论" />
        <meta name="description" content="<?=$pagetitle?> 信息评论" />
        <link href="http://www.szhgh.com/skin/default/css/base.css" rel="stylesheet" type="text/css" />
        <script src="http://www.szhgh.com/skin/default/js/jquery-1.8.2.min.js" type="text/javascript"></script>
        <script src="http://www.szhgh.com/e/data/js/ajax.js"></script>
        <script src="http://www.szhgh.com/skin/default/js/custom.js" type="text/javascript"></script>
    </head>
    <body class="comment_page">

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
            <div id='js_controlclassid' classid='<?=$classid?>' class="navigation">
                
            </div>
        </div>
        <!-- class navigation end -->

        <!-- main -->
        <div class="container">
            <div class="main left">
                <div class="position_cur">
                    <strong>当前位置：<a href='http://www.szhgh.com/index.html'>首页</a>&nbsp;>&nbsp;<?=$title?>&nbsp;>&nbsp;信息评论&nbsp;></strong>
                </div>
                <div class="title_info">
                    <h1><a href="<?=$titleurl?>" target="_self" title="<?=$title?>"><?=$title?></a></h1>
                    <form class="score_container" action="../enews/index.php" method="post" name="infopfenform" style="display: none;">
                        <input type="hidden" name="enews" value="AddInfoPfen" />
                        <input type="hidden" name="classid" value="<?=$classid?>" />
                        <input type="hidden" name="id" value="<?=$id?>" />
                        <span class="grade">
                            评分:
                            <input type="radio" name="fen" value="1" />
                            1分 
                            <input type="radio" name="fen" value="2" />
                            2分 
                            <input name="fen" type="radio" value="3" checked="checked" />
                            3分 
                            <input type="radio" name="fen" value="4" />
                            4分 
                            <input type="radio" name="fen" value="5" />
                            5分 
                            <input type="submit" name="Submit" value="提交" />                        
                        </span>
                        <span class="score_result">
                            平均得分:<strong> <?=$pinfopfen?></strong> 分，共有 <strong><?=$infopfennum?></strong> 人参与评分
                        </span>
                        <div class="clear"></div>
                    </form>                    
                </div>
                <div class="pl_list">
                    <script>
                        function CheckPl(obj) {
                            if (obj.saytext.value == "") {
                                alert("您没什么话要说吗？");
                                obj.saytext.focus();
                                return false;
                            }
                            return true;
                        }
                    </script>
                <script type="text/javascript">
                document.write('<script  type="text/javascript" src="http://www.szhgh.com/e/member/iframe/index.php?classid=<?=$classid?>&id=<?=$id?><?php 
                if(!empty($n_r)){
                    $gch = '';
                     if(!empty($n_r['title'])) $gch .= '&pagetitle='.urlencode($n_r['title']);
                     if(!empty($n_r['titleurl'])) $gch .=  '&titleurl='.urlencode($n_r['titleurl']);
                     if(!empty($n_r['titlepic'])) $gch .=  '&titlepic='.urlencode($n_r['titlepic']);
                     if(!empty($n_r['videourl'])){
                                                if(isset($GLOBALS['__playurl']))$GLOBALS['__playurl'] = array();
                                                $GLOBALS['__playurl'][$n_r['id']]=$n_r['videourl'];
                                          } 
                     echo $gch;
                }
                ?>&t='+Math.random()+'"><'+'/script>');
                </script>
                    <script type="text/javascript">
                        $(function(){
                            var $closepl = parseInt($("body").attr("closepl"));
                            var $havelogin = parseInt($("#plpost").attr("havelogin"));
                            if($havelogin===1){
                                if($closepl===1){
                                    $("#saytext").hide();
                                    $("#statebox").show();
                                    $("#imageField").addClass("dissubmitbutton").attr("disabled","true");
                                } else {
                                    $("#statebox").hide();
                                    $("#imageField").removeAttr("disabled");
                                    $("#face .facebutton").toggle(
                                        function () {
                                          $("#face .facebox").show();
                                        },
                                        function () {
                                          $("#face .facebox").hide();
                                        }
                                    );
                                }
                            } else {
                              $("#statebox").show();
                              $("#imageField").addClass("dissubmitbutton").attr("disabled","true");
                            }
                        });
                    </script>
                    <div id="turncomment">
                        <div class="comment_header">
                            <strong id="side_tab_button" class="left">
                                <?php
                                    if(isset($_GET['page']) ){  
                                        $haveclass='';
                                        $noclass=' class="onhover"';  
                                        $display=' style="display: none;"';
                                        $nodisplay='';
                                    }else{
                                        $haveclass=' class="onhover"';
                                        $noclass='';
                                        $display='';
                                        $nodisplay=' style="display: none;"';
                                    }                                        
                                ?>
                                <a<?=$haveclass?>>最热评论</a>
                                <a<?=$noclass?>>最新评论</a>
                            </strong>
                            <em class="right">共有<b><script type="text/javascript" src="http://www.szhgh.com/e/public/ViewClick/?classid=<?=$classid?>&id=<?=$id?>&down=2"></script></b>条评论</em>
                            <div class="clear"></div>
                        </div>
                        <div id="side_tab_content" class="commentlist_wrap">
                            <ul class="comment_list latest"<?=$display?>>
                                
<?php
while($r=$empire->fetch($hotsql))
{
	$plusername=$r[username];
	if(empty($r[username]))
	{
		$plusername=$fun_r['nomember'];
	}
	if($r[userid])
	{
            $showpic_r=$empire->fetch1("select userpic from {$dbtbpre}enewsmemberadd where userid=".(int)$r[userid]." limit 1");
            if(empty($showpic_r[userpic])){
                $userpic="/e/data/images/nouserpic.jpg";             
            } else {
                $userpic=sys_ResizeImg($showpic_r[userpic],64,64,1,'');
            }
            $plusername="$r[username]";
	}
	$saytime=date('Y-m-d H:i:s',$r['saytime']);
	//ip
	$sayip=ToReturnXhIp($r[sayip]);
	$saytext=RepPltextFace(stripSlashes($r['saytext']));//替换表情
	$includelink=" onclick=\"javascript:document.saypl.repid.value='".$r[plid]."';document.saypl.saytext.focus();\"";
?>
 
                                    <li>
                                        <div class="userpic left"><img src="<?=$userpic?>" /></div>
                                        <div class="comment right">
                                            <div class="property">
                                                <strong class="left"><?=$plusername?></strong>
                                                <em class="right"><?=$saytime?></em>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="pltext"><?=$saytext?></div>
                                            <div class="interaction">
                                                <a onclick="javascript:document.saypl.repid.value='<?=$r[plid]?>';document.saypl.saytext.focus();return false;" href="#tosaypl">回复</a>&nbsp;
                                                <a href="JavaScript:makeRequest('http://www.szhgh.com/e/pl/doaction.php?enews=DoForPl&plid=<?=$r[plid]?>&classid=<?=$classid?>&id=<?=$id?>&dopl=1&doajax=1&ajaxarea=zcpldiv<?=$r[plid]?>','EchoReturnedText','GET','');">支持</a>[<span id="zcpldiv<?=$r[plid]?>"><?=$r[zcnum]?></span>]&nbsp; 
                                                <a href="JavaScript:makeRequest('http://www.szhgh.com/e/pl/doaction.php?enews=DoForPl&plid=<?=$r[plid]?>&classid=<?=$classid?>&id=<?=$id?>&dopl=0&doajax=1&ajaxarea=fdpldiv<?=$r[plid]?>','EchoReturnedText','GET','');">反对</a>[<span id="fdpldiv<?=$r[plid]?>"><?=$r[fdnum]?></span>]
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </li>
                                
<?
}
?>
 
                            </ul>  
                            <ul class="comment_list hotest"<?=$nodisplay?>>
                                
<?php
while($r=$empire->fetch($sql))
{
	$plusername=$r[username];
	if(empty($r[username]))
	{
		$plusername=$fun_r['nomember'];
	}
	if($r[userid])
	{
            $showpic_r=$empire->fetch1("select userpic from {$dbtbpre}enewsmemberadd where userid=".(int)$r[userid]." limit 1");
            if(empty($showpic_r[userpic])){
                $userpic="/e/data/images/nouserpic.jpg";             
            } else {
                $userpic=sys_ResizeImg($showpic_r[userpic],64,64,1,'');
            }
            $plusername="$r[username]";
	}
	$saytime=date('Y-m-d H:i:s',$r['saytime']);
	//ip
	$sayip=ToReturnXhIp($r[sayip]);
	$saytext=RepPltextFace(stripSlashes($r['saytext']));//替换表情
	$includelink=" onclick=\"javascript:document.saypl.repid.value='".$r[plid]."';document.saypl.saytext.focus();\"";
?>
 
                                    <li>
                                        <div class="userpic left"><img src="<?=$userpic?>" /></div>
                                        <div class="comment right">
                                            <div class="property">
                                                <strong class="left"><?=$plusername?></strong>
                                                <em class="right"><?=$saytime?></em>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="pltext"><?=$saytext?></div>
                                            <div class="interaction">
                                                <a onclick="javascript:document.saypl.repid.value='<?=$r[plid]?>';document.saypl.saytext.focus();return false;" href="#tosaypl">回复</a>&nbsp;
                                                <a href="JavaScript:makeRequest('http://www.szhgh.com/e/pl/doaction.php?enews=DoForPl&plid=<?=$r[plid]?>&classid=<?=$classid?>&id=<?=$id?>&dopl=1&doajax=1&ajaxarea=zcpldiv<?=$r[plid]?>','EchoReturnedText','GET','');">支持</a>[<span id="zcpldiv<?=$r[plid]?>"><?=$r[zcnum]?></span>]&nbsp; 
                                                <a href="JavaScript:makeRequest('http://www.szhgh.com/e/pl/doaction.php?enews=DoForPl&plid=<?=$r[plid]?>&classid=<?=$classid?>&id=<?=$id?>&dopl=0&doajax=1&ajaxarea=fdpldiv<?=$r[plid]?>','EchoReturnedText','GET','');">反对</a>[<span id="fdpldiv<?=$r[plid]?>"><?=$r[fdnum]?></span>]
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </li>
                                
<?
}
?>
 
                                <center class="listpage"><?=$listpage?></center>
                            </ul>
                        </div>
                        <p class="proclaim">声明：网友评论仅供网友表达个人看法，并不表明本站同意其观点或证实其描述</p>                        
                    </div>

                </div>
            </div>
            <div class="sidebar right">

                <!-- 推荐文章 -->
                <div class="section">
                    <div class="section_header">
                        <strong>头条文章</strong>
                    </div>
                    <ul class="section_content ">
                        <script src='http://www.szhgh.com/d/js/class/class<?=$classid?>_firstnews.js'></script>
                    </ul>                        
                </div>

                <div class="section">

                    <!-- 相关文章 -->
                    <div class="section_header">
                        <strong>热门评论</strong>
                    </div>
                    <ul class="section_content">
                        <script src='http://www.szhgh.com/d/js/class/class<?=$classid?>_hotplnews.js'></script>
                    </ul> 

                    <!-- 栏目更新 -->
                    <div class="section_header">
                        <strong>栏目更新</strong>
                    </div>
                    <ul class="section_content">
                        <script src='http://www.szhgh.com/d/js/class/class<?=$classid?>_newnews.js'></script>
                    </ul>                         

                    <!-- 栏目热门 -->
                    <div class="section_header">
                        <strong>栏目热门</strong>
                    </div>
                    <ul class="section_content">
                        <script src='http://www.szhgh.com/d/js/class/class<?=$classid?>_hotnews.js'></script>
                    </ul>                          
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

        <!-- footer end -->
    </body>
</html>