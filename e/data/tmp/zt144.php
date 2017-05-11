<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>《红歌会周刊》2016年12月第2期 - <?=$public_r['sitename']?></title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link rel="shortcut icon" href="http://www.szhgh.com/skin/default/images/favicon.ico" /> 
    <link href="http://www.szhgh.com/skin/default/css/weekly-1.css" rel="stylesheet" type="text/css" />
    <script src="http://www.szhgh.com/skin/default/js/jquery-1.8.2.min.js" type="text/javascript"></script>
    <script src="http://www.szhgh.com/e/data/js/ajax.js"></script>
    <script src="http://www.szhgh.com/skin/default/js/custom.js" type="text/javascript"></script>
</head>
<?
        $ztid=144;  //取得当前专题id并赋给变量$ztid，以供SQL查询使用；
        $zt_r = $empire->fetch1("select * from {$dbtbpre}enewszt where ztid=" . $ztid);
        
        $special_r = $empire->fetch1("select id,classid,title,onclick,plnum from {$dbtbpre}ecms_special where specid=" . $ztid);
        $week_total_r = $empire->fetch1("select COUNT(*) AS total from {$dbtbpre}enewszt where zcid=1 and ztid<=". $ztid);
        $dedup = array();
        $dedup_i=0;
?>
<body closepl="<?=$zt_r['closepl']?>">
  
    <!--头部开始-->
    <div class="header">
        <div class="hea_1 clearfix">
            <div class="pleft">
                <div class="hea_logo pleft"><a href="http://www.szhgh.com/" target="_blank" title="红歌会网首页"><img src="http://www.szhgh.com/skin/default/images/topic_images/smalllogo.jpg" height="45" /></a></div>
                <ul class="pleft">
                    <li><a href="http://www.szhgh.com/" title="红歌会网首页" target="_blank">红歌会网首页</a>&nbsp;&nbsp;|</li>
                    <li><a href="http://hao.szhgh.com/" title="点此进入红歌会网址导航" target="_blank">网址导航</a>&nbsp;&nbsp;|</li>
                    <li><a href="http://www.szhgh.com/special/" title="专题中心" target="_blank">&nbsp;&nbsp;专题中心 </a>|</li>
                    <li><a href="http://www.szhgh.com/xuezhe/" title="学者专栏" target="_blank">&nbsp;&nbsp;学者专栏 </a></li>
                </ul>                
            </div>
            <div class="account pright">
                <script>
                    document.write('<script src="http://www.szhgh.com/e/member/login/loginjs.php?t='+Math.random()+'"><'+'/script>');
                </script>
            </div>
        </div>
    </div>
    
    <div class="g-fwrap g-weekly">
        <div class="g-wrap m-weekly f-cb">
            <img class="u-smalllogo f-fl" src="http://www.szhgh.com/skin/default/images/topic_images/weeklylogo.jpg" />
            <div class="u-towardlink f-fr">
                <a href="http://www.szhgh.com/weekly" title="点此返回周刊列表" target="_blank">返回周刊列表</a>
                | <a id="js-showtoward" class="u-btn" href="#" title="点此往期周刊">往期周刊</a>
                <div id="js-towardbox" class="u-towardbox">
                    <ul>
                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq("select * from {$dbtbpre}enewszt where zcid=1 and showzt=0 and ztid<$ztid order by addtime desc limit 4",4,24,0,'','addtime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                            <?
                            $nodata=1;
                            $week_tt1_r = $empire->fetch1("select b.titleurl,b.title from {$dbtbpre}enewsztinfo a inner join {$dbtbpre}ecms_article b on a.id=b.id where a.ztid=$bqr[ztid] and a.isgood=9 order by b.newstime desc");
                            ?>
                            <li>
                                <a class="u-ztname" href="http://www.szhgh.com/<?=$bqr['ztpath']?>" title="<?=$bqr['ztname']?>" target="_blank"><?=$bqr['ztname']?></a>
                                <a class="u-tttitle" href="<?=$week_tt1_r['titleurl']?>" title="<?=$week_tt1_r['title']?>" target="_blank"><?=$week_tt1_r['title']?></a>
                            </li>
                            <?
                            unset($week_tt1_r);
                            ?>
                        <?php
}
}
?>
                        <?
                            if($nodata!=1){
                                echo "无数据";
                            }
                        ?>
                    </ul>
                </div>
            </div>            
        </div>
    </div>
	<?
		$path_r = explode("/",$zt_r['ztpath']);
		if(!empty($path_r)){
			$year = substr($path_r[1],0,4);
			$month = (int)substr($path_r[1],4,2);
			$qishu = (int)substr($path_r[1],-2);
		}
	?>
    <div class="hea_2">
        <div class="g-wrap g-guide f-cb">       
            <div class="m-guide">                
				<div class="section sectionA">
					<div class="section_header">
						<strong class="f-fl">本期头条</strong>
						<div class="u-issue f-fl"><span><?=$year?>年<?=$month?>月第<?=$qishu?>期 总第<?=$week_total_r[total]?>期</span></div>
						<div class="u-share f-fr">
							<!-- JiaThis Button BEGIN -->
							<div class="jiathis_style_32x32">
								<a class="f-fl">分享到</a>
								<a class="jiathis_button_tsina"></a>
								<a class="jiathis_button_tqq"></a>
								<a class="jiathis_button_qzone"></a>
							</div>
							<script type="text/javascript" >
								var jiathis_config={
									data_track_clickback:true,
									summary:"",
									shortUrl:false,
									hideMore:true
								}
							</script>
							<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jia.js?uid=1746769" charset="utf-8"></script>
							<style>

								.jiathis_style_32x32 .jiathis_txt {
									border-radius: 16px;
									-moz-border-radius: 16px;
									-webkit-border-radius: 16px;
									box-shadow: 0 -2px 0 #afafaf inset;
									-moz-box-shadow: 0 -2px 0 #afafaf inset;
									-webkit-box-shadow: 0 -2px 0 #afafaf inset;
								}
								.jiathis_style_32x32 .jtico_tsina {
									background: url("http://www.szhgh.com/skin/default/images/bg_sinaweibo.png") no-repeat scroll 0 0 #bfbfbf;
								}
								.jiathis_style_32x32 .jiathis_button_tsina:hover .jtico_tsina {
									background: url("http://www.szhgh.com/skin/default/images/bg_sinaweibo.png") no-repeat scroll 0 0 #a6a6a6;
								}
								.jiathis_style_32x32 .jtico_tqq {
									background: url("http://www.szhgh.com/skin/default/images/bg_qweibo.png") no-repeat scroll 0 0 #bfbfbf;
								}
								.jiathis_style_32x32 .jiathis_button_tqq:hover .jtico_tqq {
									background: url("http://www.szhgh.com/skin/default/images/bg_qweibo.png") no-repeat scroll 0 0 #a6a6a6;
								}
								.jiathis_style_32x32 .jtico_qzone {
									background: url("http://www.szhgh.com/skin/default/images/bg_qzone.png") no-repeat scroll 0 0 #bfbfbf;
								}
								.jiathis_style_32x32 .jiathis_button_qzone:hover .jtico_qzone {
									background: url("http://www.szhgh.com/skin/default/images/bg_qzone.png") no-repeat scroll 0 0 #a6a6a6;
								}
								.jiathis_style_32x32 .jtico {
									padding-left: 32px !important;
									margin-left: 15px !important;
								}
								.jiathis_style_32x32 .jtico:hover {
									opacity: 1.0;
								}                        

							</style>

							<!-- JiaThis Button END -->
						</div>
					</div>
					<div class="section_content">
						<div class="m-firsttitle">
							<?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsIndexLoopBq("select * from {$dbtbpre}enewsztinfo a inner join {$dbtbpre}ecms_article b on a.id=b.id where a.ztid=$ztid and a.isgood=9 and ispic=1 order by b.newstime desc limit 1",1,11,'','','');
if($ecms_bq_sql){
while($indexbqr=$empire->fetch($ecms_bq_sql)){
if(empty($class_r[$indexbqr['classid']]['tbname'])){continue;}
$bqr=$empire->fetch1("select * from {$dbtbpre}ecms_".$class_r[$indexbqr['classid']]['tbname']." where id='$indexbqr[id]'");
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
								<h1 class="f-tac"><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h1>
								<div class="u-pic f-tac"><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><img src="<?=$bqr[titlepic]?>" title="<?=$bqr['title']?>" /></a></div>
								<div class="m-summary">
									<div class="u-smalltext"><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),300))?></div>
									<div class="u-ainfo f-tar"><?=date('Y-m-d H:i:s',$bqr['newstime'])?>&nbsp;&nbsp;&nbsp;<span class="u-tocomment">点击：<?=$bqr['onclick']?>&nbsp;&nbsp;&nbsp;评论：<span id = "sourceId::<?=basename($bqsr['titleurl'],'.html')?>" class = "cy_cmt_count" ></span><script id="cy_cmt_num" 
			src="http://changyan.sohu.com/upload/plugins/plugins.list.count.js?clientId=cysG16cIx">
</script><span class="u-tocomment"><a href="<?=$bqsr['titleurl']?>#SOHUCS" target="_blank">（查看）</a></span></div>
								</div>

							<?
								if($dedup_i===0){
									$dedup[0] = (int)$bqr['id'];
								} else {
									$dedup[$dedup_i] = (int)$bqr['id'];
								};
								
								$dedup_i++;
							?>
							
							<?php
}
}
?>
							
							<?
								$dedup_id = (string)implode(",",$dedup);
								$notin=$dedup_id ? ' and b.id not in('.$dedup_id.')' : '';
							?>
							
						</div>
					</div>                
				</div>
            </div>
        </div>        
    </div>

    <!--头部结束-->
    
    <div class="s-wrap">
    
    <!--中间开始-->

    <div class="wrap wrap-1 width clearfix margin-t">
        <div class="mainbox">
            <div class="section sectionB">
                <div class="section_header"><strong>编辑推荐</strong></div>
                <div class="section_content">
                    <ul class="m-list">
                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsIndexLoopBq("select * from {$dbtbpre}enewsztinfo a inner join {$dbtbpre}ecms_article b on a.id=b.id where a.ztid=$ztid and a.isgood=8$notin order by b.newstime desc limit 9",9,11,'','','');
if($ecms_bq_sql){
while($indexbqr=$empire->fetch($ecms_bq_sql)){
if(empty($class_r[$indexbqr['classid']]['tbname'])){continue;}
$bqr=$empire->fetch1("select * from {$dbtbpre}ecms_".$class_r[$indexbqr['classid']]['tbname']." where id='$indexbqr[id]'");
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
                        <?
                            if($bqr[ispic]==0){
                        ?>
                        <li class="s-normal<?if($bqno==9){echo ' s-last';}?>">
                            <h4><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h4>
                            <div class="m-summary f-cb">
                                <div class="u-info">
                                    <time><?=date('Y-m-d H:i:s',$bqr['newstime'])?></time>
                                    <div class="u-smalltext"><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),300))?></div>
                                    <div class="u-ainfo f-tar"><span class="u-tocomment">点击：<?=$bqr['onclick']?>&nbsp;&nbsp;&nbsp;评论：<span id = "sourceId::<?=basename($bqsr['titleurl'],'.html')?>" class = "cy_cmt_count" ></span><script id="cy_cmt_num" 
			src="http://changyan.sohu.com/upload/plugins/plugins.list.count.js?clientId=cysG16cIx">
</script><a href="<?=$bqsr['titleurl']?>#SOHUCS" target="_blank">（查看）</a></span></div>
                                </div>
                            </div>
                        </li>
                        <?
                            }else{
                        ?>
                        <li class="s-ispic<?if($bqno==9){echo ' s-last';}?>">
                            <h4><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h4>
                            <div class="m-summary f-cb">
                                <a class="u-pic f-fl" href="<?=$bqsr['titleurl']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr[titlepic],199,151,1,'')?>" title="<?=$bqr['title']?>" /></a>
                                <div class="u-info">
                                    <time><?=date('Y-m-d H:i:s',$bqr['newstime'])?></time>
                                    <div class="u-smalltext"><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),250))?></div>
                                    <div class="u-ainfo f-tar"><span class="u-tocomment">点击：<?=$bqr['onclick']?>&nbsp;&nbsp;&nbsp;评论：<span id = "sourceId::<?=basename($bqsr['titleurl'],'.html')?>" class = "cy_cmt_count" ></span><script id="cy_cmt_num" 
			src="http://changyan.sohu.com/upload/plugins/plugins.list.count.js?clientId=cysG16cIx">
</script><a href="<?=$bqsr['titleurl']?>#SOHUCS" target="_blank">（查看）</a></span></div>
                                </div>
                            </div>
                        </li>
                        <?
                            }
                            $dedup[$dedup_i] = (int)$bqr['id'];
                            $dedup_i++;
                        ?>
                        
                        <?php
}
}
?>
                        
                        <?
                            $dedup_id = (string)implode(",",$dedup);
                            $notin=$dedup_id ? ' and b.id not in('.$dedup_id.')' : '';
                        ?>
                    </ul>
                </div>                
            </div>
            <div class="section sectionC">
                <div class="section_header"><strong>一周精选</strong></div>
                <div class="section_content">
                    <ul class="m-list">
                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsIndexLoopBq("select * from {$dbtbpre}enewsztinfo a inner join {$dbtbpre}ecms_article b on a.id=b.id where a.ztid=$ztid and a.isgood<=3$notin order by b.newstime desc limit 20",20,11,'','','');
if($ecms_bq_sql){
while($indexbqr=$empire->fetch($ecms_bq_sql)){
if(empty($class_r[$indexbqr['classid']]['tbname'])){continue;}
$bqr=$empire->fetch1("select * from {$dbtbpre}ecms_".$class_r[$indexbqr['classid']]['tbname']." where id='$indexbqr[id]'");
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>

                        <li class="normal f-cb"><span class="u-newstime f-fr"><?=date('Y-m-d H:i:s',$bqr['newstime'])?></span><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></li>
                        <?
                            if($bqno==10){
                        ?>
                        <li class="s-line"></li>
                        <?
                            }
                            $dedup[$dedup_i] = (int)$bqr['id'];
                            $dedup_i++;
                        ?>
                        
                        <?php
}
}
?>
                        
                        <?
                            unset($dedup);
                        ?>
                    </ul>
                </div>                
            </div>            
        </div>
    </div>
    
    <div class="g-towardweekly">
        <div class="m-head"><strong>往期周刊</strong></div>
        <ul class="m-ct f-cb">
            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsLoopBq("select * from {$dbtbpre}enewszt where zcid=1 and showzt=0 and ztid<$ztid order by addtime desc limit 4",4,24,0,'','addtime DESC');
if($ecms_bq_sql){
while($bqr=$empire->fetch($ecms_bq_sql)){
$bqsr=sys_ReturnEcmsLoopStext($bqr);
$bqno++;
?>
            <?
                $nodata = 1;
            ?>
            <li class="f-fl<?if($bqno==4){echo ' s-last';}?>"><a href="http://www.szhgh.com/<?=$bqr['ztpath']?>" title="<?=$bqr['ztname']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr[ztimg],208,269,1,'')?>" /></a></li>
            <?php
}
}
?>
            <?
                if($nodata!=1){
                    echo '暂无数据';
                }
            ?>
        </ul>
    </div>
    
    <div class="cont_pl">
        <div id="plpost" class="pl_list">
            <!-- 评论 -->
           <!--PC版-->
<div id="SOHUCS" sid="《红歌会周刊》2016年12月第2期" ></div>
<script charset="utf-8" type="text/javascript" src="http://changyan.sohu.com/upload/changyan.js" ></script>
<script type="text/javascript">
window.changyan.api.config({
appid: 'cysG16cIx',
conf: 'prod_eb841ff6ba533cd3f322c05e9252c602'
});
</script>
        </div>
    </div>
    
    <!--中间结束-->
        
    <!--底部开始-->
        <div class="footer"><a href="http://www.szhgh.com/">红歌会网首页</a> |  <a href="http://www.szhgh.com/special">专题中心</a> |  <a href="http://www.szhgh.com/Article/opinion/zatan/13968.html">联系我们</a> </div>
        <div class="footer1"><font>红歌会网QQ群：35758473&nbsp;&nbsp;&nbsp;投稿邮箱：<a href="mailto:szhgh001@163.com" target="_blank">szhgh001@163.com</a>&nbsp;&nbsp;&nbsp;站长QQ: <a title="官方QQ" href="http://wpa.qq.com/msgrd?Uin=1737191719" target="_blank">1962727933</a>&nbsp;&nbsp;&nbsp; 备案号： <a href="http://www.miitbeian.gov.cn" target="_blank">粤ICP备12077717号-1</a>&nbsp;&nbsp;&nbsp;<script src="http://s20.cnzz.com/stat.php?id=3051861&web_id=3051861&show=pic1" language="JavaScript"></script></font></div>
    
    <!--底部结束-->
    </div>
    
    <script src="http://www.szhgh.com/skin/default/js/jquery.leanModal.min.js" type="text/javascript"></script>
    <div id="loginmodal" class="loginmodal" style="display:none;">
        <div class="modaletools"><a class="hidemodal" title="点此关闭">×</a></div>
        <form class="clearfix" name=login method=post action="http://www.szhgh.com/e/member/doaction.php">
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
    <script src=http://www.szhgh.com/e/public/onclick/?enews=dozt&ztid=144></script>
</body>
</html>