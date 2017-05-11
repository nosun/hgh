<?php
/* 
 * 用于处理第三方交互的弹出功能页
 */
$pagetitle = '- 唱响红歌，弘扬正气！';
 $enews = explode('_', $_REQUEST['types']);
$code1 = '';
$code2 = '';
switch(intval($enews[0])){
    case 0:
    default:
        $pagetitle = '分享到QQ空间 - 唱响红歌，弘扬正气！';
        $code1=<<<__W_CB_mx
 <script src="http://qzonestyle.gtimg.cn/qzone/app/qzlike/qzopensl.js#jsdate=20111201" charset="utf-8"></script>
<script type="text/javascript">
(function(){
     var playurl = null;
     var saytext = null; 
     try{
     var pwi = (window.opener?window.opener:window.parent);
    var pwin  = pwi.document;  
    var imgs =  $("#main DIV.newstext img[src]", pwin);
    var pics = new Array();
    if(pwin.getElementById('titlepic').value.length>0)
        pics.push(encodeURIComponent(pwin.getElementById('titlepic').value));        
    if (imgs.length > 0) {
            var img1 = null;
            if (imgs.length > 0) {
                imgs.each(function(index, ele) {
                    if (ele.width > 100 && ele.height > 100)
                    {
                        pics.push(encodeURIComponent(ele.src));
                    }
                });
            }
        }                
saytext = pwin.getElementById('saytext').value;
var csummary = pwin.getElementById('csummary').value;
playurl = pwin.getElementById('playurl').value;
   }
 catch(pe){
   ;
     }
$('#saypl',pwin).submit();//提交父页面
var playurl2 = playurl;
if(playurl != null && playurl.length>255){
 $.ajax({
          type : "post", 
          url : "/e/memberconnect/GetShotUrl.php", 
          data : "url=" + playurl, 
          async : false, 
          success : function(data){ 
            if(data && data.hasOwnProperty('error')==false){
            playurl = data['s'].substring(7);
        }
          } 
          });    
}
if(playurl.length>0)
    saytext += '[视频:'+playurl+']';
if(saytext!=null && saytext.length>0){
var p = {
url:pwi.location.href,
showcount:'1',
desc:saytext,
summary:csummary,
title:pwin.title,
site:'红歌会网',
weibo:'0',
playurl:playurl2               
};
var s = [];
for(var i in p){
s.push(i + '=' + encodeURIComponent(p[i]||''));
}
if(pics.length>0){
    s.push('pics='+pics.join('|'));
   }          
var gourl = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?'+s.join('&');
window.location.href=gourl;
 }
})();
</script>
__W_CB_mx;
        break;
}

?><!DOCTYPE html>
<html xmlns:wb="http://open.weibo.com/wb" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?=$pagetitle?></title>
        <meta name="keywords" content="内容分享,红歌会,红歌会网,红色文化" />
        <meta name="description" content="红歌会网基于一贯的人民立场，高举爱国主义旗帜，坚守正义，弘扬正气，维护国家和民族根本利益，反映老百姓的呼声，宣传毛泽东思想和红色文化。" />
        <link rel="shortcut icon" href="/skin/default/images/favicon.ico" /> 
        <script type="text/javascript" src="/skin/default/js/jquery-1.8.2.min.js"></script>
        <meta property="qc:admins" content="13773660666320706375" />
        <meta property="wb:webmaster" content="585a282dc9c3f9b7" />
    </head>
    <body class="index">         
     <?php 
     echo $code1;     
     echo $code2;
     ?>
    </body>
</html>

