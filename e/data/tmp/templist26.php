<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>[!--pagetitle--] - <?=$public_r['sitename']?></title>
    <meta name="keywords" content="[!--pagekey--]" />
    <meta name="description" content="[!--pagedes--]" />
    <link rel="shortcut icon" href="[!--news.url--]skin/default/images/favicon.ico" /> 
    <link href="[!--news.url--]skin/default/css/weekly.css" rel="stylesheet" type="text/css" />
    <script src="[!--news.url--]skin/default/js/jquery-1.8.2.min.js" type="text/javascript"></script>
    <script src="[!--news.url--]e/data/js/ajax.js"></script>
    <script src="[!--news.url--]skin/default/js/custom.js" type="text/javascript"></script>
</head>
<?
        $news_zt_r = $empire->fetch1("select * from {$dbtbpre}enewszt where zcid=1 order by addtime DESC limit 1");
        $cnews_r = $empire->fetch1("select * from {$dbtbpre}enewsztinfo a inner join {$dbtbpre}ecms_article b on a.id=b.id where a.ztid=$news_zt_r[ztid] and a.isgood=9 order by b.newstime DESC limit 1");
        $special_r = $empire->fetch1("select id,classid,title,onclick,plnum from {$dbtbpre}ecms_special where specid=" . $news_zt_r[ztid]);
        $week_total_r = $empire->fetch1("select COUNT(*) AS total from {$dbtbpre}enewszt where zcid=1 and ztid<=". $news_zt_r[ztid]);
        $dedup = array();
        $dedup_i=0;
?>
<body>
  
    <!--头部开始-->
    <div class="header">
        <div class="hea_1 clearfix">
            <div class="pleft">
                <div class="hea_logo pleft"><a href="[!--news.url--]" target="_blank" title="红歌会网首页"><img src="[!--news.url--]skin/default/images/topic_images/smalllogo.jpg" height="45" /></a></div>
                <ul class="pleft">
                    <li><a href="[!--news.url--]" title="红歌会网首页" target="_blank">红歌会网首页</a>&nbsp;&nbsp;|</li>
                    <li><a href="http://hao.szhgh.com/" title="点此进入红歌会网址导航" target="_blank">网址导航</a>&nbsp;&nbsp;|</li>
                    <li><a href="[!--news.url--]special/" title="专题中心" target="_blank">&nbsp;&nbsp;专题中心 </a>|</li>
                    <li><a href="[!--news.url--]xuezhe/" title="学者专栏" target="_blank">&nbsp;&nbsp;学者专栏 </a></li>
                </ul>                
            </div>
            <div class="account pright">
                <script>
                    document.write('<script src="[!--news.url--]e/member/login/loginjs.php?t='+Math.random()+'"><'+'/script>');
                </script>
            </div>
        </div>
    </div>
    <div class="g-fwrap g-weekly">
        <div class="g-wrap m-weekly f-cb">
            <img class="u-smalllogo f-fl" src="[!--news.url--]skin/default/images/topic_images/weeklylogo.jpg" />
            <div class="u-towardlink f-fr">
                <a class="u-rss" href="[!--news.url--]e/web/?rss2&classid=103" title="订阅周刊" target="_blank">+订阅周刊</a>
            </div>            
        </div>
    </div>
    <div class="hea_2">
        <div class="g-wrap g-guide g-guide-1 f-cb">
            <a href="<?=$public_r[newsurl]?><?=$news_zt_r[ztpath]?>" title="<?=$news_zt_r[ztname]?>" target="_blank"><img class="f-fl" src="<?=$news_zt_r[ztimg]?>" /></a>
            <div class="m-guide f-fr">
                <div class="u-issue"><span><?=$news_zt_r[ztname]?> 总第<?=$week_total_r[total]?>期</span></div>
                <div class="u-smallheader"><strong>/封面文章/</strong></div>
                <h2 class="u-title"><a href="<?=$cnews_r[titleurl]?>" title="<?=$cnews_r[title]?>" target="_blank"><?=$cnews_r[title]?></a></h2>
                <div class="u-intro"><?=$news_zt_r[intro]?></div>
                <div class="u-smallheader"><strong>/编辑推荐/</strong></div>
                <div class="m-list">
                    <ul>
                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsIndexLoopBq("select * from {$dbtbpre}enewsztinfo a inner join {$dbtbpre}ecms_article b on a.id=b.id where a.ztid=$news_zt_r[ztid] and a.isgood=8 and ispic=1 order by b.newstime desc limit 4",4,11,'','','');
if($ecms_bq_sql){
while($indexbqr=$empire->fetch($ecms_bq_sql)){
if(empty($class_r[$indexbqr['classid']]['tbname'])){continue;}
$bqr=$empire->fetch1("select * from {$dbtbpre}ecms_".$class_r[$indexbqr['classid']]['tbname']." where id='$indexbqr[id]'");
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                        <li><span class="u-newstime f-fr"><?=date('Y-m-d H:i:s',$bqr['newstime'])?></span><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></li>
                        <?php
}
}
?>
                    </ul>
                </div>
            </div>
        </div>        
    </div>

    <!--头部结束-->
    
    <div class="s-wrap">
    
    <!--中间开始-->

    <div class="wrap wrap-2 width clearfix margin-t">
        <div class="mainbox f-fl">
            <div class="section sectionD">
                <div class="section_header"><strong>往期周刊</strong></div>
                <div class="section_content">
                    <ul class="m-list-1">
                        [!--empirenews.listtemp--]
                            <!--list.var1-->
                        [!--empirenews.listtemp--]
                        <div class="clear"></div>
                    </ul>
                </div> 
                <center class="page"><strong>[!--show.page--]</strong></center>
            </div>
        </div>
        <div class="g-sidebar f-fr">
            <div class="section sectionD">
                <div class="section_header"><strong>热点文章</strong></div>
                <div class="section_content">
                    <ul class="m-list-2">
                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsIndexLoopBq("select * from {$dbtbpre}enewsztinfo a inner join {$dbtbpre}ecms_article b on a.id=b.id where a.ztid=$news_zt_r[ztid] order by b.onclick desc limit 10",10,11,'','','');
if($ecms_bq_sql){
while($indexbqr=$empire->fetch($ecms_bq_sql)){
if(empty($class_r[$indexbqr['classid']]['tbname'])){continue;}
$bqr=$empire->fetch1("select * from {$dbtbpre}ecms_".$class_r[$indexbqr['classid']]['tbname']." where id='$indexbqr[id]'");
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <?
                                $hot_class = $bqno<=3?" s-hot":"";
                                $no_class =$bqno>=10?" s-no":"";
                            ?>
                            <li><span class="u-no<?=$hot_class?><?=$no_class?>"><?=$bqno?></span><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></li>
                        <?php
}
}
?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!--中间结束-->
        
    <!--底部开始-->
        <div class="footer"><a href="[!--news.url--]">红歌会网首页</a> |  <a href="[!--news.url--]special">专题中心</a> |  <a href="[!--news.url--]Article/opinion/zatan/13968.html">联系我们</a> </div>
        <div class="footer1"><font>红歌会网QQ群：35758473&nbsp;&nbsp;&nbsp;投稿邮箱：<a href="mailto:szhgh001@163.com" target="_blank">szhgh001@163.com</a>&nbsp;&nbsp;&nbsp;站长QQ: <a title="官方QQ" href="http://wpa.qq.com/msgrd?Uin=1737191719" target="_blank">1962727933</a>&nbsp;&nbsp;&nbsp; 备案号： <a href="http://www.miitbeian.gov.cn" target="_blank">粤ICP备12077717号-1</a>&nbsp;&nbsp;&nbsp;<script src="http://s20.cnzz.com/stat.php?id=3051861&web_id=3051861&show=pic1" language="JavaScript"></script></font></div>
    
    <!--底部结束-->
    </div>
    
    <script src="[!--news.url--]skin/default/js/jquery.leanModal.min.js" type="text/javascript"></script>
    <div id="loginmodal" class="loginmodal" style="display:none;">
        <div class="modaletools"><a class="hidemodal" title="点此关闭">×</a></div>
        <form class="clearfix" name=login method=post action="[!--news.url--]e/member/doaction.php">
            <div class="login pleft">
                <strong>会员登录</strong>
                <input type=hidden name=enews value=login />
                <input type=hidden name=ecmsfrom value=9 />
                <div id="username" class="txtfield username"><input name="username" type="text" size="16" /></div>
                <div id="password" class="txtfield password"><input name="password" type="password" size="16" /></div>
                <div class="forgetmsg"><a href="/e/member/GetPassword/" title="点此取回密码" target="_blank">忘记密码？</a></div>
                <input type="submit" name="Submit" value="登陆" class="inputSub flatbtn-blu" />
            </div>
            <div class="reg pright">
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
          
            $('#js-showtoward').click( function () {
                $('#js-towardbox').show();
            });
            $("#js-showtoward").hover(
                function () {
                  $("#js-towardbox").addClass("z-hover");
                },
                function () {
                  $("#js-towardbox").removeClass("z-hover");
                }
            );
            $("#js-towardbox").hover(
                function () {
                  $(this).addClass("z-hover");
                },
                function () {
                  $(this).removeClass("z-hover");
                }
            );

        });
    </script>
    <!--底部结束-->
    [!--page.stats--]
</body>
</html>