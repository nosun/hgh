<?php if($title == '首页'){
    echo  '<nav class="top_nav">
        <ul>
            <li>
                <a href="02栏目列表页.html" title="观点评论"><b>推荐</b></a>
            </li>
            <li>
                <a href="02栏目列表页.html" title="思想学术"><b>资讯中心</b></a>
            </li>
            <li>
                <a href="02栏目列表页.html" title="社情舆情"><b>纵论天下</b></a>
            </li>
            <li>
                <a href="03推荐专题页.html" title="推荐专题"><b>红色中国</b></a>
            </li>
            <li id="arrow-down">
                <img onclick="rmhidden();" src="hgh/images/search_03.png">
            </li>
            <li class="noshow" hidden="hidden">
                <a href="03推荐专题页.html"  title="推荐专题"><b>唱读讲传</b></a>
            </li>
            <li class="noshow" hidden="hidden" id="second">
                <a href="03推荐专题页.html" title="推荐专题"><b>人民健康</b></a>
            </li>
            <li class="noshow" hidden="hidden">
                <a href="03推荐专题页.html" title="推荐专题"><b>工农家园</b></a>
            </li>
            <li class="noshow" hidden="hidden">
                <a href="03推荐专题页.html" title="推荐专题"><b>文史读书</b></a>
            </li>
            <li class="noshow" hidden="hidden">
                <a href="03推荐专题页.html" title="推荐专题"><b>第三世界</b></a>
            </li>
            <li class="noshow" hidden="hidden" >
                <img id="arrow-up" onclick="addhidden()" style="height: 10px" src="hgh/images/arrow-up_03.png">
            </li>
        </ul>
    </nav>
    <script>function rmhidden() {
      var noshow = document.body.getElementsByClassName("noshow");
      for (var i=0;i<noshow.length;i++)
        {
        noshow[i].removeAttribute("hidden");
        }
      document.getElementById("arrow-down").hidden = "hidden";
      document.getElementById("second").style.paddingLeft = "47px";
//      document.getElementsByClassName("top_nav").style.height="100px";
      document.getElementsByClassName("top_nav").style.backgroundColor=rgb(243,243,243);

//      document.getElementById("arrow-up").style.paddingLeft = "300px";
    }
    function addhidden() {
      var noshow = document.body.getElementsByClassName("noshow");
      for (var i=0;i<noshow.length;i++)
        {
        noshow[i].hidden = "hidden";
        }
        document.getElementById("arrow-down").removeAttribute("hidden");
    }</script>';

    echo '<div id="slide"><img src="hgh/images/1.png"><img src="hgh/images/33.png"><img src="hgh/images/1.png"><img src="hgh/images/33.png"><img src="hgh/images/1.png"> </div>';}

?>
$mhtml .=<<<VX_DEND
<section class="li{$vi1}">
    <div class="img-text">
        <h3><a href="show.php?classid={$r['classid']}&id={$r['id']}&style={$wapstyle}&cpage={$page}&cid={$classid}&bclassid={$bclassid}">
                {$r['title']}</a></h3>
        <a href="show.php?classid={$r['classid']}&id={$r['id']}&style={$wapstyle}&cpage={$page}&cid={$classid}&bclassid={$bclassid}""> <img style="float:right;" src='{$r[titlepic]}' /></a>
    </div>
</section>
VX_DEND;
&nbsp;&nbsp;&nbsp;<span style="float: right"><?=$i?>/<?=$headlinenum?></span>
<ul> <li><a id="j-2pc-1" class="u-2pc" href="#" style="color: white">电脑版</a></li>

    <script>
        window.onload=redtitle();
        function redtitle(){
            var navlist = document.getElementsByClassName("navlist");
            alert(navlist.length);return;
            for(var i=0;i<navlist.length;i++){
                if(navlist[i].innerHTML==<?=$cstr1?>||navlist[i].innerHTML==<?=$cstr2?>){
                    navlist[i].style.color = rgb(168,0,0);
                }
            }
        }
    </script>