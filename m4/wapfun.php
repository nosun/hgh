<?php
if (!defined('InEmpireCMS')) {
    exit();
}

//-------- config
$class_setting = array(36, 52, 47, 53, 54, 55, 56, 57); //预定义栏目
//-------- 编码转换
function DoWapIconvVal($str)
{
    global $iconv, $pr, $ecms_config;
    $phome_ecms_charver = $ecms_config['sets']['pagechar'];
    if ($phome_ecms_charver != 'utf-8') {
        $char = $phome_ecms_charver == 'big5' ? 'BIG5' : 'GB2312';
        $targetchar = $pr['wapchar'] ? 'UTF8' : 'UNICODE';
        $str = $iconv->Convert($char, $targetchar, $str);
    }
    return $str;
}

//-------- 中文星期几

function cnWeek($date)
{
    $arr = array('天', '一', '二', '三', '四', '五', '六');
    return $arr[date('w', $date)];
}

//-------- 提示信息
function DoWapShowMsg($error, $returnurl = 'index.php')
{
    DoWapHeader('提示信息');
    ?>
    <p><?php echo $error; ?><br/><a href="<?php echo $returnurl; ?>">返回</a></p>
    <?php
    DoWapFooter();
    exit();
}

function GetPcLink($domain = 'www.szhgh.com')
{
    global $dbtbpre, $class_r, $empire, $pcdirurl;
    $p = '';
    if (empty($_REQUEST['id'])) {
        if (!empty($pcdirurl))
            $p = $pcdirurl;
        else
            $p = ReturnSiteIndexUrl();
    } else {
        $classid = intval($_REQUEST['classid']);
        $id = intval($_REQUEST['id']);
        //返回表
        $infotb = ReturnInfoMainTbname($class_r[$classid]['tbname'], 1);
        $infor = $empire->fetch1("select isurl,titleurl,classid,id,title from " . $infotb . " where id='{$id}' limit 1");
        $p = sys_ReturnBqTitleLink($infor);
    }
    if (strpos($p, '://') === false) {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $p = $sys_protocal . $domain . $p;
    }
    return $p;
}

//-------- 头部
function DoWapHeader($title)
{
    global $phome_ecms_charver, $class_setting, $class_setting1, $class_setting2, $class_r;
    $classid = $_GET['classid'];
    $cid = $_GET['cid'];
    if(is_null($cid)){$cstr = $title;}else{
        $cstr= $class_r[$cid]['classname'];
    }
//    $cstr1 = $class_r[$classid]['classname'];
//    $cstr2 = $class_r[$cid]['classname'];
    ?>
    <!DOCTYPE HTML>
    <html>
    <head>
        <meta charset="utf-8">
        <meta content="no-cache" name="Cache-Control">
        <meta content="width=device-width, minimum-scale=1.0, maximum-scale=2.0" name="viewport">
        <link rel="apple-touch-icon-precomposed" href="logo.png"/>
        <link href="<?= $public_r[newsurl] ?>" rel="canonical">
        <title><?=$title?> - 红歌会网 - 手机版 </title>
        <meta name="keywords" content="红歌会,红歌会网,红色文化,社会主义,毛泽东"/>
        <meta name="description" content="红歌会网基于一贯的人民立场，高举爱国主义旗帜，唱响红歌，弘扬正气，宣传毛泽东思想和红色文化，反映老百姓的呼声，推动社会公平正义，维护国家和民族根本利益。"/>
        <link rel="stylesheet" href="hgh/css/base.css"/>
        <script src="hgh/js/jquery-1.9.1.min.js"></script>
        <script src="http://www.szhgh.com/skin/default/js/jquery-1.8.2.min.js" type="text/javascript"></script>
        <script src="http://www.szhgh.com/skin/default/js/jquery.cookie.js" type="text/javascript"></script>
        <script src="hgh/js/TouchSlide.1.1.js"></script>
        <script src="hgh/js/wyzxwk-m-js.js"></script>
        <?php if($title == '首页'){?>
        <script type="text/javascript">
            window.onload=document.cookie="page="+0;
        </script>
            <?php }else{?>
        <script type="text/javascript">
            window.onload=document.cookie="page="+0;
            $(document).ready(function () {
                alert(111);
                var navlist = document.getElementsByClassName("navlist");
                for(var i=0;i<navlist.length;i++){
                    console.log(eval(navlist[i].innerHTML));
                    if(eval(navlist[i].innerHTML)==<?=$cstr?>){
                        navlist[i].style.color = rgb(168,0,0);
                    }
                }
            })
        </script>
        <?php }?>
    </head>
    <body>
    <!-- 头部 -->
    <header>
        <section>
            <div class="head2"><a href="index.php"><img src="hgh/images/logo_02.png" style="height:45px;float: left"></a>
                <?php if($title == '首页'){
                    echo '<a href="index.php"><img src="hgh/images/red3.png" style="height:25px;padding-top: 15px;float: left"></a><table border="0" style="float:left;height:45px;">
                        <tr>
                            <td style="vertical-align:bottom;"><b style="font-size: 10px;color:white">手机版</b></td>
                        </tr>
                        </table><a href="search/result.php" style="float: right;padding-right: 15px" title="搜索"><img style="height: 18px;padding-top:16px;" src="hgh/images/search.png"></i></a></div>
        </section>';
                }else{ ?>
                    <table border="0" style="float:left;height:45px;">
<tr>
    <td style="vertical-align:bottom;"><b style="font-size: 18px;color:white"><?if(!empty($_GET['cid'])){echo $class_r[$_GET['cid']]['classname'];}else{echo $class_r[$_GET['classid']]['classname'];}?></b></td>
</tr>
</table><p style="float: right;font-size: 18px;color: white"><img onclick="lanmu()" style="height: 18px;margin-top:11px;margin-right:16px;float:right;" src="hgh/images/lanmu_03.png"></p> </div>
        </section>
        <?php }?>
    </header>
    <nav class="top_nav" id="lanmu" hidden style="position:absolute;z-index:1;">
        <table style="height:110px;width: 100%;background-color:rgb(243,243,243);font-family: 黑体"><tr><td>
            <a href="index.php">推荐</a></td>
                    <td><a href="list.php?classid=36" class="navlist">资讯中心</a></td>
                    <td><a href="list.php?classid=52" class="navlist">红色中国</a></td>
                    <td><a href="list.php?classid=47" class="navlist">纵论天下</a></td>
                    <td><a href="list.php?classid=53" class="navlist">唱读讲传</a></td></tr><tr>
                    <td></td><td><a href="list.php?classid=54" class="navlist">人民健康</a></td>
                    <td><a href="list.php?classid=55" class="navlist">工农家园</a></td>
                    <td><a href="list.php?classid=56" class="navlist">文史·读书</a></td>
                    <td><a href="list.php?classid=57" class="navlist">第三世界</a></td></tr><tr>
                <td></td><td></td><td></td><td></td>
                    <td align="center">
                <img id="arrow-up" onclick="lamuaddhidden()" style="height: 10px" src="hgh/images/arrow-up_03.png">
            </td></tr>
            </table>
    </nav>
    <script>function lanmu(){
            document.getElementById("lanmu").removeAttribute("hidden");
        }
        function lamuaddhidden() {
            document.getElementById("lanmu").hidden = "hidden";
        }
    </script>
    <!-- end 头部 -->
    <?php    if($title == '首页') { ?>
        <nav class="top_nav" id="lanmu" hidden style="position:absolute;z-index:1;">
        <table style="width: 100%;background-color:rgb(243,243,243);font-size: 15px;font-family: 黑体"><tr><td>
                    <a href="index.php" style="color: rgb(168,0,0)">推荐</a></td>
                <td><a href="list.php?classid=36">资讯中心</a></td>
                <td><a href="list.php?classid=52">红色中国</a></td>
                <td><a href="list.php?classid=47">纵论天下</a></td>
                <td><a href="list.php?classid=53">唱读讲传</a></td></tr><tr>
                <td></td><td><a href="list.php?classid=54">人民健康</a></td>
                <td><a href="list.php?classid=55">工农家园</a></td>
                <td><a href="list.php?classid=56">文史·读书</a></td>
                <td><a href="list.php?classid=57">第三世界</a></td></tr><tr>
                <td></td><td></td><td></td><td></td>
                <td align="center">
                    <img id="arrow-up" onclick="lamuaddhidden()" style="height: 10px" src="hgh/images/arrow-up_03.png">
                </td></tr>
        </table>
    </nav>

    <nav class="top_nav" id="shouye">
        <table style="width: 100%;background-color:rgb(243,243,243);font-family: 黑体"><tr><td>
                    <a href="index.php" style="color: rgb(168,0,0)">推荐</a></td>
                <td><a href="list.php?classid=36">资讯中心</a></td>
                <td><a href="list.php?classid=52">红色中国</a></td>
                <td><a href="list.php?classid=47">纵论天下</a></td>
                <td><img style="height: 10px" onclick="lanmu()" src="hgh/images/search_03.png"></td></tr></table>
    </nav><script>function lanmu(){
                document.getElementById("lanmu").removeAttribute("hidden");
            }
            function lamuaddhidden() {
                document.getElementById("lanmu").hidden = "hidden";
            }
        </script>
        <?php }?>
<?php

}

//-------- 尾部   
function DoWapFooter()
{
    ?>
    </article>
    <a href="#top" class="to_top icon" title="返回顶部"></a>
    <footer style="background-color: rgb(64,64,64);">
        <ul> <li><a href="#" style="color: white">电脑版</a></li>
<!--            <li style="color: rgb(183,183,183)">Copyright &copy; 2014 粤ICP备12077717号-1</li>-->
            <li style="color: rgb(183,183,183)">&copy;copyright  红歌会网 2016</li>
            <li style="display: none;">
                <script src="http://s20.cnzz.com/stat.php?id=3051861&web_id=3051861&show=pic1"  language="JavaScript"></script>
            </li>
        </ul>
        <script type="text/javascript">
            $(document).ready(function($){
                $.cookie('RSTestCookie', 1, { expires: 1,path: '/' ,domain: '.szhgh.com' });
                $opencookie = $.cookie('RSTestCookie');
                if($opencookie){
                    $("#j-2pc,#j-2pc-1,#j-2pc-2").on("click",function(){
                        $.cookie('RSapp2pc', 1, { expires: 30,path: '/',domain: '.szhgh.com'});
                        location.href = "<?= GetPcLink()?>";
                    });
                }else{
                    location.href = "<?= GetPcLink()?>";
                }
            });
        </script>
    </footer>
    <script src="hgh/js/wyzxwk-m-js.js"></script>
    <script src="hgh/js/global.js"></script>
    </body>
    </html>
    <?php
    $str = ob_get_contents();
    ob_end_clean();
    echo DoWapIconvVal($str);
}

//-------- 分页
function DoWapListPage($num, $line, $page, $search)
{
    if (empty($num)) {
        return '';
    }
    $str = '';
    $pagenum = ceil($num / $line);
    $search = RepPostStr($search, 1);
    $phpself = eReturnSelfPage(0);
    if ($page) //首页
    {
        $str .= "<section><a href=\"" . $phpself . "?page=0" . $search . "\"><h2>首页</h2></a></section>";
    }
    if ($page) {
        $str .= "<section><a href=\"" . $phpself . "?page=" . ($page - 1) . $search . "\"><h2>上一页</h2></a></section>";
    }
    if ($page != $pagenum - 1) {
        $str .= "<section><a href=\"" . $phpself . "?page=" . ($page + 1) . $search . "\"><h2>下一页</h2></a></section>";
    }
    if ($page != $pagenum - 1) {
        $str .= "<section><a href=\"" . $phpself . "?page=" . ($pagenum - 1) . $search . "\"><h2>尾页</h2></a></section>";
    }
    return $str;
}

//-------- 替换<p> --------
function DoWapRepPtags($text)
{
    $text = str_replace(array('<p>', '<P>', '</p>', '</P>'), array('', '', '<br />', '<br />'), $text);
    $preg_str = "/<(p|P) (.+?)>/is";
    $text = preg_replace($preg_str, "", $text);
    return $text;
}

//-------- 字段属性 --------
function DoWapRepField($text, $f, $field)
{
    global $modid, $emod_r;
    if (strstr($emod_r[$modid]['tobrf'], ',' . $f . ',')) //加br
    {
        $text = nl2br($text);
    }
    if (!strstr($emod_r[$modid]['dohtmlf'], ',' . $f . ',')) //去除html
    {
        $text = htmlspecialchars($text);
    }
    return $text;
}

//-------- 去除html代码 --------
function DoWapClearHtml($text)
{
    $text = stripSlashes($text);
    $text = htmlspecialchars(strip_tags($text));
    return $text;
}

//-------- 替换字段内容
function DoWapRepF($text, $f, $field)
{
    $text = stripSlashes($text);
    $text = DoWapRepPtags($text);
    $text = DoWapRepField($text, $f, $field);
    return $text;
}

//-------- 栏目加载更多
function ListDoLoadMore(){

}
    //-------- 替换文章内容字段
function DoWapRepNewstext($text)
{
    $text = stripSlashes($text);
    //$text=DoWapRepPtags($text);  不要把p改掉
    return $text;
}

//-------- 特殊字符去除
function DoWapCode($string)
{
    $string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
    return $string;
}

//-------- 返回使用模板
function ReturnWapStyle($add, $style)
{
    global $empire, $dbtbpre, $pr, $class_r;
    $styleid = $pr['wapdefstyle'];
    $classid = 0;
    if (WapPage == 'index') {
        $classid = (int)$add['bclassid'];
    } elseif (WapPage == 'list') {
        $classid = (int)$add['classid'];
    } elseif (WapPage == 'show') {
        $classid = (int)$add['classid'];
    }
    if ($classid && $class_r[$classid]['tbname']) {
        $cr = $empire->fetch1("select wapstyleid from {$dbtbpre}enewsclass where classid='$classid'");
        if ($cr['wapstyleid']) {
            $styleid = $cr['wapstyleid'];
        }
    }
    if ($style && $styleid == $pr['wapdefstyle']) {
        $styleid = $style;
    }
    $sr = $empire->fetch1("select path from {$dbtbpre}enewswapstyle where styleid='$styleid'");
    $wapstyle = $sr['path'];
    if (empty($wapstyle)) {
        $wapstyle = 1;
    }
    return $wapstyle;
}


$pr = $empire->fetch1("select wapopen,wapdefstyle,wapshowmid,waplistnum,wapsubtitle,wapshowdate,wapchar from {$dbtbpre}enewspublic limit 1");

//导入编码文件
if ($phome_ecms_charver != 'utf-8') {
    @include_once("../e/class/doiconv.php");
    $iconv = new Chinese('');
}

if (empty($pr['wapopen'])) {
    DoWapShowMsg('网站没有开启WAP功能', 'index.php');
}

$wapstyle = intval($_GET['style']);
//返回使用模板
$usewapstyle = ReturnWapStyle($_GET, $wapstyle);
if (!file_exists('template/' . $usewapstyle)) {
    $usewapstyle = 1;
}
?>
