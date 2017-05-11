<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>沉痛哀悼李成瑞同志 - <?=$public_r['sitename']?></title>
    <meta name="keywords" content="" />
    <meta name="description" content="坚定的马列毛主义者，忠诚的共产主义战士，久经考验的毛主席的好学生和好战士，原国家统计局局长李成瑞同志于2017年2月11日在北京病逝，享年95岁。" />
    <link rel="shortcut icon" href="http://www.szhgh.com/skin/default/images/favicon.ico" /> 
    <link href="http://www.szhgh.com/skin/default/css/zt_common.css" rel="stylesheet" type="text/css" />
    <script src="http://www.szhgh.com/skin/default/js/jquery-1.8.2.min.js" type="text/javascript"></script>
    <script src="http://www.szhgh.com/e/data/js/ajax.js"></script>
    <script src="http://www.szhgh.com/skin/default/js/custom.js" type="text/javascript"></script>
</head>
<?
        $ztid=155;  //取得当前专题id并赋给变量$ztid，以供SQL查询使用；
        $zt_r = $empire->fetch1("select * from {$dbtbpre}enewszt a INNER JOIN {$dbtbpre}enewsztadd b ON a.ztid=b.ztid where a.ztid=" . $ztid);
        
        $special_r = $empire->fetch1("select id,classid,title from {$dbtbpre}ecms_special where specid=" . $ztid);
        $dedup = array();
        $dedup_i=0;
        
        if($zt_r['common_parameter']){
            $parameter_r = explode("|",$zt_r['common_parameter']);
            if($parameter_r[2]){
                $vote_r = explode(",",$parameter_r[2]);
                $vote_num = count($vote_r);
            }
            
            
        }
        
?>
<body closepl="<?=$zt_r['closepl']?>">

    <!--头部开始-->
    <div class="header">
        <div class="hea_1 clearfix">
            <div class="pleft">
                <div class="hea_logo pleft"><a href="http://www.szhgh.com/" target="_blank" title="红歌会网首页"><img src="http://www.szhgh.com/skin/default/images/topic_images/logo.jpg" width="163" height="45" /></a></div>
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
    <div class="hea_2" style='background: url("<?=$zt_r[ztbg]?>") no-repeat scroll center center transparent;'>
        <div class="g-wrap f-cb">
            <div class="m-guide f-fl">
                <h1 style="color:<?=$parameter_r[0]?>;"><?=$zt_r['ztname']?></h1>
                <div  style="color:<?=$parameter_r[0]?>;" class="u-intro">坚定的马列毛主义者，忠诚的共产主义战士，久经考验的毛主席的好学生和好战士，原国家统计局局长李成瑞同志于2017年2月11日在北京病逝，享年95岁。</div>
            </div>
            <img class="f-fr" src="<?=$zt_r['ztimg']?>" />
        </div>        
    </div>

    <!--头部结束-->
    
    <div class="s-wrap">
    
    <!--中间开始-->

    <div class="wrap wrap-1 width clearfix margin-t">
        <div class="mainbox left">
            <div class="section sectionB">
                <div class="section_header">专题头条</div>
                <div class="section_content">
                    <ul class="m-list">
                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsIndexLoopBq("select * from {$dbtbpre}enewsztinfo a inner join {$dbtbpre}ecms_article b on a.id=b.id where a.ztid=$ztid and b.firsttitle>=1 order by b.newstime desc limit 3",3,11,'','','');
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
                        <li class="s-normal <?if($bqno==3){echo 's-last';}?>">
                            <h4><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h4>
                            <div class="m-summary f-cb">
                                <div class="u-info">
                                    <time><?=date('Y-m-d H:i:s',$bqr['newstime'])?></time>
                                    <div class="u-smalltext"><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),180))?></div>
                                    <div class="u-ainfo f-tar">点击：<?=$bqr['onclick']?>&nbsp;&nbsp;&nbsp;评论：<span id = "sourceId:: <?=basename($bqsr['titleurl'],'.html')?>" class = "cy_cmt_count" ></span><script id="cy_cmt_num" 
			src="http://changyan.sohu.com/upload/plugins/plugins.list.count.js?clientId=cysG16cIx">
</script></div>
                                </div>
                            </div>
                        </li>
                        <?
                            }else{
                        ?>
                        <li class="s-ispic <?if($bqno==3){echo 's-last';}?>">
                            <h4><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h4>
                            <div class="m-summary f-cb">
                                <a class="u-pic f-fl" href="<?=$bqsr['titleurl']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr[titlepic],199,151,1,'')?>" title="<?=$bqr['title']?>" /></a>
                                <div class="u-info f-fr">
                                    <time><?=date('Y-m-d H:i:s',$bqr['newstime'])?></time>
                                    <div class="u-smalltext"><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),180))?></div>
                                    <div class="u-ainfo f-tar">点击：<?=$bqr['onclick']?>&nbsp;&nbsp;&nbsp;评论：<span id = "sourceId::<?=basename($bqsr['titleurl'],'.html')?>" class = "cy_cmt_count" ></span><script id="cy_cmt_num" 
			src="http://changyan.sohu.com/upload/plugins/plugins.list.count.js?clientId=cysG16cIx">
</script></div>
                                </div>
                            </div>
                        </li>
                        <?
                            }
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
                        ?>
                        
                    </ul>
                </div>                
            </div>
            <div class="section sectionB">
                <div class="section_header">编辑推荐</div>
                <div class="section_content">
                    <ul class="m-list">
                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsIndexLoopBq("select * from {$dbtbpre}enewsztinfo a inner join {$dbtbpre}ecms_article b on a.id=b.id where a.ztid=$ztid and a.isgood>=1 and b.id not in($dedup_id) order by b.newstime desc limit 3",3,11,'','','');
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
                        <li class="s-normal <?if($bqno==3){echo 's-last';}?>">
                            <h4><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h4>
                            <div class="m-summary f-cb">
                                <div class="u-info">
                                    <time><?=date('Y-m-d H:i:s',$bqr['newstime'])?></time>
                                    <div class="u-smalltext"><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),180))?></div>
                                    <div class="u-ainfo f-tar">点击：<?=$bqr['onclick']?>&nbsp;&nbsp;&nbsp;评论：<span id = "sourceId::<?=basename($bqsr['titleurl'],'.html')?>" class = "cy_cmt_count" ></span><script id="cy_cmt_num" 
			src="http://changyan.sohu.com/upload/plugins/plugins.list.count.js?clientId=cysG16cIx">
</script></div>
                                </div>
                            </div>
                        </li>
                        <?
                            }else{
                        ?>
                        <li class="s-ispic <?if($bqno==3){echo 's-last';}?>">
                            <h4><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h4>
                            <div class="m-summary f-cb">
                                <a class="u-pic f-fl" href="<?=$bqsr['titleurl']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr[titlepic],199,151,1,'')?>" title="<?=$bqr['title']?>" /></a>
                                <div class="u-info f-fr">
                                    <time><?=date('Y-m-d H:i:s',$bqr['newstime'])?></time>
                                    <div class="u-smalltext"><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),180))?></div>
                                    <div class="u-ainfo f-tar">点击：<?=$bqr['onclick']?>&nbsp;&nbsp;&nbsp;评论：<span id = "sourceId::<?=basename($bqsr['titleurl'],'.html')?>" class = "cy_cmt_count" ></span><script id="cy_cmt_num" 
			src="http://changyan.sohu.com/upload/plugins/plugins.list.count.js?clientId=cysG16cIx">
</script></div>
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
                        ?>
                    </ul>
                </div>                
            </div>
            <div class="section sectionB">
                <div class="section_header">最新文章</div>
                <div class="section_content">
                    <ul id="lastCtrl" class="m-list">
                        <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsIndexLoopBq("select * from {$dbtbpre}enewsztinfo a inner join {$dbtbpre}ecms_article b on a.id=b.id where a.ztid=$ztid and b.id not in($dedup_id) order by b.newstime desc limit 0,5",5,11,'','','');
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
                        <li class="s-normal <?if($bqno%5===0){echo 's-last';}?>">
                            <h4><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h4>
                            <div class="m-summary f-cb">
                                <div class="u-info">
                                    <time><?=date('Y-m-d H:i:s',$bqr['newstime'])?></time>
                                    <div class="u-smalltext"><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),180))?></div>
                                    <div class="u-ainfo f-tar">点击：<?=$bqr['onclick']?>&nbsp;&nbsp;&nbsp;评论：<span id = "sourceId::<?=basename($bqsr['titleurl'],'.html')?>" class = "cy_cmt_count" ></span><script id="cy_cmt_num" 
			src="http://changyan.sohu.com/upload/plugins/plugins.list.count.js?clientId=cysG16cIx">
</script></div>
                                </div>
                            </div>
                        </li>
                        <?
                            }else{
                        ?>
                        <li class="s-ispic <?if($bqno%5===0){echo 's-last';}?>">
                            <h4><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h4>
                            <div class="m-summary f-cb">
                                <a class="u-pic f-fl" href="<?=$bqsr['titleurl']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr[titlepic],199,151,1,'')?>" title="<?=$bqr['title']?>" /></a>
                                <div class="u-info f-fr">
                                    <time><?=date('Y-m-d H:i:s',$bqr['newstime'])?></time>
                                    <div class="u-smalltext"><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),180))?></div>
                                    <div class="u-ainfo f-tar">点击：<?=$bqr['onclick']?>&nbsp;&nbsp;&nbsp;评论：<span id = "sourceId::<?=basename($bqsr['titleurl'],'.html')?>" class = "cy_cmt_count" ></span><script id="cy_cmt_num" 
			src="http://changyan.sohu.com/upload/plugins/plugins.list.count.js?clientId=cysG16cIx">
</script></div>
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
                            unset($dedup);

                        ?>
                    </ul>
                    <a id='morebutton' class="morebutton" title="点击查看更多文章">更多文章↓</a>
                    <div id='morebox' class="morebox" style="display: none;">
                        <ul class="m-list">
                            <?php
$bqno=0;
$ecms_bq_sql=sys_ReturnEcmsIndexLoopBq("select * from {$dbtbpre}enewsztinfo a inner join {$dbtbpre}ecms_article b on a.id=b.id where a.ztid=$ztid and b.id not in($dedup_id) order by b.newstime desc limit 5,5",5,11,'','','');
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
                                <li class="s-normal <?if($bqno%5===0){echo 's-last';}?>">
                                    <h4><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h4>
                                    <div class="m-summary f-cb">
                                        <div class="u-info">
                                            <time><?=date('Y-m-d H:i:s',$bqr['newstime'])?></time>
                                            <div class="u-smalltext"><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),180))?></div>
                                            <div class="u-ainfo f-tar">点击：<?=$bqr['onclick']?>&nbsp;&nbsp;&nbsp;评论：<span id = "sourceId::<?=basename($bqsr['titleurl'],'.html')?>" class = "cy_cmt_count" ></span><script id="cy_cmt_num" 
			src="http://changyan.sohu.com/upload/plugins/plugins.list.count.js?clientId=cysG16cIx">
</script></div>
                                        </div>
                                    </div>
                                </li>
                                <?
                                    }else{
                                ?>
                                <li class="s-ispic <?if($bqno%5===0){echo 's-last';}?>">
                                    <h4><a href="<?=$bqsr['titleurl']?>" title="<?=$bqr['title']?>" target="_blank"><?=$bqr['title']?></a></h4>
                                    <div class="m-summary f-cb">
                                        <a class="u-pic f-fl" href="<?=$bqsr['titleurl']?>" target="_blank"><img src="<?=sys_ResizeImg($bqr[titlepic],199,151,1,'')?>" title="<?=$bqr['title']?>" /></a>
                                        <div class="u-info f-fr">
                                            <time><?=date('Y-m-d H:i:s',$bqr['newstime'])?></time>
                                            <div class="u-smalltext"><?=htmlspecialchars_decode(esub(strip_tags($bqr['smalltext']),180))?></div>
                                            <div class="u-ainfo f-tar">点击：<?=$bqr['onclick']?>&nbsp;&nbsp;&nbsp;评论：<span id = "sourceId::<?=basename($bqsr['titleurl'],'.html')?>" class = "cy_cmt_count" ></span><script id="cy_cmt_num" 
			src="http://changyan.sohu.com/upload/plugins/plugins.list.count.js?clientId=cysG16cIx">
</script></div>
                                        </div>
                                    </div>
                                </li>
                                <?
                                    }
                                ?>
                            <?php
}
}
?>
                        </ul>
                        <?
                            if($parameter_r[1]){
                        ?>
                        <a class="morebutton" href='http://www.szhgh.com/<?=$zt_r[ztpath]?>/type<?=$parameter_r[1]?>.html' target="_blank" title="点击查看更多文章">更多文章→</a>    
                        <?
                            }
                        ?>
                    </div>

                </div>                
            </div>            
        </div>
        <div class="sidebox right">
            <div class="section share">
                <div class="section_head">分享到</div>
                <div class="section_content">
                    <!-- JiaThis Button BEGIN -->
                    <div class="jiathis_style_32x32">
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
                        }
                        .jiathis_style_32x32 .jtico_tsina {
                            background: url("http://www.szhgh.com/skin/default/images/bg_sinaweibo.png") no-repeat scroll 0 0 #4d576b;
                        }
                        .jiathis_style_32x32 .jiathis_button_tsina:hover .jtico_tsina {
                            background: url("http://www.szhgh.com/skin/default/images/bg_sinaweibo.png") no-repeat scroll 0 0 #283245;
                        }
                        .jiathis_style_32x32 .jtico_tqq {
                            background: url("http://www.szhgh.com/skin/default/images/bg_qqweibo.png") no-repeat scroll 0 0 #4d576b;
                        }
                        .jiathis_style_32x32 .jiathis_button_tqq:hover .jtico_tqq {
                            background: url("http://www.szhgh.com/skin/default/images/bg_qqweibo.png") no-repeat scroll 0 0 #283245;
                        }
                        .jiathis_style_32x32 .jtico_qzone {
                            background: url("http://www.szhgh.com/skin/default/images/bg_qzone.png") no-repeat scroll 0 0 #4d576b;
                        }
                        .jiathis_style_32x32 .jiathis_button_qzone:hover .jtico_qzone {
                            background: url("http://www.szhgh.com/skin/default/images/bg_qzone.png") no-repeat scroll 0 0 #283245;
                        }
                        .jiathis_style_32x32 .jtico {
                            padding-left: 32px !important;
                            margin-right: 15px !important;
                        }
                        .jiathis_style_32x32 .jtico:hover {
                            opacity: 1.0;
                        }                        
                        
                    </style>
                    
                    <!-- JiaThis Button END -->
                </div>                
            </div>
            <div class="section vote">
                <div class="section_head">调查</div>
                <div class="section_content">
                    <ul class="m-list">
                        <?
                            if($vote_num>0){
                                foreach ($vote_r as $key => $value) {
                        ?>
                        <li>
                            <script type="text/javascript" src="http://www.szhgh.com/d/js/vote/vote<?=$value?>.js"></script>
                        </li>
                        <?
                                }
                            }
                        ?>
                    </ul>                        
                </div>
            </div>             
        </div>
    </div>
    
<!--PC版-->
<div id="SOHUCS" sid="沉痛哀悼李成瑞同志" ></div>
<script charset="utf-8" type="text/javascript" src="http://changyan.sohu.com/upload/changyan.js" ></script>
<script type="text/javascript">
window.changyan.api.config({
appid: 'cysG16cIx',
conf: 'prod_eb841ff6ba533cd3f322c05e9252c602'
});
</script>
    
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
          
          $('#morebutton').click( function () {
              $(this).hide();
              $('#morebox').show();
              $('#lastCtrl .s-last').removeClass("s-last");
          });
          
          
        });
    </script>
    <!--底部结束-->
    <script src=http://www.szhgh.com/e/public/onclick/?enews=dozt&ztid=155></script>
</body>
</html>