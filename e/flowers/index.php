<?php
/**
 * Created by PhpStorm.
 * User: guoguo
 * Date: 14-12-25
 * Time: 上午10:45
 */

require("../class/connect.php");
if (!defined('InEmpireCMS')) {
    exit();
}

require("../class/db_sql.php");
require("../class/q_functions.php");
require("functions/functions.php");
require LoadLang("pub/fun.php");
$link = db_connect();
$empire = new mysqlquery();
$editor = 1;
//分类id
$bid = (int)$_GET['bid'];
if($bid){
    $gbr = $empire->fetch1("select bid,bname,groupid from {$dbtbpre}enewsgbookclass where bid='$bid'");
} else {
    $rname = $_GET['rname'];
    $gbr = $empire->fetch1("select bid,bname,rname,groupid from {$dbtbpre}enewsgbookclass where rname='$rname'");
    $bid = (int) $gbr['bid'];
}

if (empty($gbr['bid'])) {
    printerror("EmptyGbook", "", 1);
}

if ($_GET['enews'] == "addCounts") {
    $counts = addCounts(1);
} else {
    $strSql = "select counts from {$dbtbpre}flowers where id=1";
    $r = $empire->fetch1($strSql);
    $counts = $r['counts'];
}

////权限
//if ($gbr['groupid']) {
//    include("../member/class/user.php");
//    $user = islogin();
//    include("../data/dbcache/MemberLevel.php");
//    if ($level_r[$gbr['groupid']]['level'] > $level_r[$user['groupid']]['level']) {
//        echo "<script>alert('您的会员级别不足(" . $level_r[$gbr['groupid']]['groupname'] . ")，没有权限提交信息!');history.go(-1);</script>";
//        exit();
//    }
//}
////判断是否登录
$userid = (int)getcvar('mluserid',0,TRUE);
$username = getcvar('mlusername');
//
//if ($userid) {
//    $username = RepPostVar(getcvar('mlusername'));
//    $strSql = "select email from {$dbtbpre}enewsmember where userid=$userid";
//    $userR = $empire->fetch1($strSql);
//    $useremail = $userR['email'];
//}
//
////判断是否写入COOKIE
//$dock = 0;
//$dock = (int)$_GET['dock'];
//if ($dock == 1) {
//    setcookie("rsgbookbid", $bid, time() + 60);
//}


//用户IP验证
$ip = egetip();

$bname = $gbr['bname'];
$page = (int)$_GET['page'];
$page = RepPIntvar($page);
$start = 0;
$line = $public_r['gb_num']; //每页显示条数
$page_line = 12; //每页显示链接数
$offset = $start + $page * $line; //总偏移量
$totalnum = (int)$_GET['totalnum'];
if ($totalnum > 0) {
    $num = $totalnum;
} else {
    $totalquery = "select count(*) as total from {$dbtbpre}enewsgbook  where bid = 2 AND checked=0";
    $num = $empire->gettotal($totalquery); //取得总条数
}
$search .= "&bid=2&totalnum=$num";
$query = "select lyid,name,email,`mycall`,lytime,lytext,retext from {$dbtbpre}enewsgbook  where bid=2 AND checked=0";
$query = $query . " order by lyid desc limit $offset,$line";
$sql = $empire->query($query);
$listpage = page1($num, $line, $page_line, $start, $page, $search);
$url = "<a href='" . ReturnSiteIndexUrl() . "'>" . $fun_r['index'] . "</a>&nbsp;>&nbsp;" . $fun_r['saygbook'];
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xmlns:wb="http://open.weibo.com/wb">
    <head>
        <title>给毛主席献花 - <?= $public_r['sitename'] ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="keywords" content="[!--pagekey--]"/>
        <meta name="description" content="[!--pagedes--]"/>

        <link rel="shortcut icon" href="<?= $public_r['newsurl'] ?>skin/default/images/favicon.ico"/>
        <link href="<?= $public_r['newsurl'] ?>skin/default/css/channelmao_flowers.css" type="text/css" media="all"
              rel="stylesheet"/>

        <script src="<?= $public_r['newsurl'] ?>skin/default/js/jquery-1.8.2.min.js" type="text/javascript"></script>
        <script src="<?= $public_r['newsurl'] ?>skin/default/js/myfocus-2.0.4.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?= $public_r['newsurl'] ?>skin/default/js/mF_tbhuabao.js"></script>
        <script type="text/javascript" src="<?= $public_r['newsurl'] ?>skin/default/js/custom.js"></script>
    </head>
    <body>

    <!-- header -->
    <div class="g-hd">
        <div class="m-nav">
            <div class="navigation f-cb">
                <ul class="u-list f-fl">
                    <li><a href="http://hao.szhgh.com/" title="点此进入红歌会网址导航" target="_blank">网址导航</a></li>
                    <li><a href="http://mzd.szhgh.com/" target="_blank" title="毛泽东">毛泽东频道首页</a></li>
                    <li><a href="<?= $public_r['newsurl'] ?>" target="_blank" title="红歌会首页">红歌会首页</a></li>
                    <li><a target="_blank" title="资讯中心" href="<?= $public_r['newsurl'] ?>Article/news/">资讯中心</a></li>
                    <li><a target="_blank" title="纵论天下" href="<?= $public_r['newsurl'] ?>Article/opinion/">纵论天下</a></li>
                    <li><a target="_blank" title="红色中国" href="<?= $public_r['newsurl'] ?>Article/red-china/">红色中国</a>
                    </li>
                    <li><a target="_blank" title="点此进入专题中心" href="<?= $public_r['newsurl'] ?>special">专题中心</a></li>
                    <li><a target="_blank" title="点此进入学者专栏" href="<?= $public_r['newsurl'] ?>xuezhe/">学者专栏</a></li>
                    <li><a href="<?= $public_r['newsurl'] ?>e/member/cp/" target="_blank">会员中心</a></li>
                    <li><a href="<?= $public_r['newsurl'] ?>e/DoInfo/AddInfo.php?mid=10&enews=MAddInfo&classid=29"
                           target="_blank">我要投稿</a></li>
                </ul>
                <div class="u-account f-fr">
                    <script>
                        document.write('<script src="<?= $public_r['newsurl'] ?>e/member/login/loginjs.php?t=' + Math.random() + '"><' + '/script>');
                    </script>
                </div>
            </div>
        </div>
        <div class="m-banner"></div>
        <div class="m-fwrap s-fwrap-1">
            <div class="m-wrap m-wrap-1">
                <div class="m-box m-box-1 f-cb">
                    <div class="u-box u-box-1 f-fl">
                        <a class="u-btn u-btn-1 u-flower" id="j-add"></a>
                        <strong class="u-msg">献花</strong>
                    </div>
                    <div class="u-box u-box-2 f-fr">
                        <a class="u-btn u-btn-2 u-flower" href="#01"></a>
                        <strong class="u-msg">留言</strong>
                    </div>
                </div>
                <div class="m-box m-box-2">
                    <div class="u-showcounts">献花总<strong id="j-counts" class="u-counts"><?= $counts ?></strong>朵</div>
                </div>

            </div>
        </div>
        <script type="text/javascript" src="<?= $public_r['newsurl'] ?>skin/default/js/jquery.cookie.js"></script>
        <script type="text/javascript">
            var c,
                ck = "";
            $("#j-add").on("click", function () {
                ck = $.cookie('rsgbookbid');
                if (ck) {
                    alert("一分钟内不可重复献花，请稍后再献！");
                } else {
                    $.get(
                        "index.php",
                        {bid: "2", enews: "addCounts", dock: "1"}
                    );
                    c = Number($("#j-counts").text());
                    c++;
                    c = String(c);
                    $("#j-counts").text(c);
                }

            })
        </script>
    </div>

    <!-- header end -->
    <div class="g-mn">

        <form action="<?=$public_r['newsurl']?>e/enews/index.php" method="post" name="form1" id="form1">
            <h2 class="m-msg" name="01" id="01">给主席留言:</h2>

            <div class="m-gbook">
                <script type="text/javascript">
                    document.write('<script src="<?=$public_r['newsurl']?>e/member/iframe/gbooklogin.php?t=' + Math.random() + '"><' + '/script>');
                </script>
                <script type="text/javascript">
                    $(function () {
                        var $closepl = parseInt($("body").attr("closepl"));
                        var $havelogin = parseInt($("#plpost").attr("havelogin"));
                        if ($havelogin === 1) {
                            if ($closepl === 1) {
                                $("#saytext").hide();
                                $("#statebox").show();
                                $("#imageField").addClass("dissubmitbutton").attr("disabled", "true");
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
                            $("#imageField").addClass("dissubmitbutton").attr("disabled", "true");
                        }
                    });
                </script>
            </div>
            <input name="enews" type="hidden" id="enews" value="AddGbook" />
            <input name="bid" type="hidden" id="bid" value="<?= $bid ?>" />
            <input name="userid" type="hidden" id="userid" value="<?= $userid ?>" />
            <input name="name" type="hidden" id="name" value="<?= $username ?>" />


        </form>

        <div id="message" class="message">
            <ul class="list">

                <?
                while ($r = $empire->fetch($sql)) {
                    $r['retext'] = nl2br($r[retext]);
                    $r['lytext'] = nl2br($r[lytext]);
                    ?>
                    <li>
                        <div class="message_header">
                            <span class="name">网友: <strong><?= $r[name] ?></strong> </span>
                            <span class="lytime">留言时间: <?= $r[lytime] ?> </span>

                            <div class="clear_float"></div>
                        </div>
                        <div class="content">
                            <div class="lytext"> <?= $r[lytext] ?></div>
                            <?
                            if ($r[retext]) {
                                ?>
                                <div class="reply"><img src="../data/images/regb.gif" width="18" height="18"/>&nbsp;&nbsp;<strong><font
                                            color="#FF0000">回复:</font><br/></strong> <?= $r[retext] ?></div>
                                <?
                            }
                            ?>
                        </div>
                    </li>
                    <?
                }
                ?>

            </ul>
            <center id="paging" class="paging">分页: <?= $listpage ?></center>
        </div>
        <script type="text/javascript">
            $(function () {
                var $count = $("#message ul li").size()
                $a_count = $("#paging a").size(),
                    nomessage = "<p class='nomessage'>暂无留言</p>";
                if ($count == 0) {
                    $("#paging").hide();
                    $("#message ul").append(nomessage);
                } else {
                    $("#message ul li:last").addClass("last");
                    if ($a_count > 0) {
                        $("#paging").show();
                    }
                }
            });
        </script>

    </div>


    <div class="footer">
        <div class="copyright">
            <ul>
                <li class='copy_left'>
                    <div>
                        <a href="<?= $public_r['newsurl'] ?>" title="红歌会网" target="_blank">红歌会网</a>
                        | <a href="<?= $public_r['newsurl'] ?>" title="网址导航" target="_blank">网址导航</a>
                        | <a href="<?= $public_r['newsurl'] ?>html/rank.html" title="排行榜" target="_blank">排行榜</a>
                        | <a href="<?= $public_r['newsurl'] ?>Article/opinion/wp/20257.html" title="联系我们"
                             target="_blank">联系我们</a>
                        | <a href="<?= $public_r['newsurl'] ?>Article/opinion/zatan/13968.html" title="在线提意见"
                             target="_blank">在线提意见</a>
                    </div>
                    <div>
                        <a href="http://www.miitbeian.gov.cn" target="_blank">粤ICP备12077717号-1</a>
                        |
                        <script src="http://s20.cnzz.com/stat.php?id=3051861&web_id=3051861&show=pic1"
                                language="JavaScript"></script>

                        <script type="text/javascript">
                            var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
                            document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F2e62d7088e3926a4639571ba4c25de10' type='text/javascript'%3E%3C/script%3E"));
                        </script>


                    </div>
                </li>
                <li class="focusbutton">
                    <a class="rss" href="<?= $public_r['newsurl'] ?>e/web/?type=rss2" title="欢迎订阅红歌会网"
                       target="_blank"></a>
                    <a class="sinaweibo" href="http://weibo.com/szhgh?topnav=1&wvr=5&topsug=1" title="欢迎关注红歌会网新浪微博"
                       target="_blank"></a>
                    <a class="qqweibo"
                       href="http://follow.v.t.qq.com/index.php?c=follow&amp;a=quick&amp;name=szhgh001&amp;style=5&amp;t=1737191719&amp;f=1"
                       title="欢迎关注红歌会网腾讯微博" target="_blank"></a>
                    <a class="qqmsg" href="http://wpa.qq.com/msgrd?Uin=1737191719" title="欢迎通过QQ联系我们"
                       target="_blank"></a>
                    <a class="email" href="mailto:szhgh001@163.com" title="欢迎投稿或反映问题" target="_blank"></a>
                </li>
                <li class="focusmsg">
                    <div>网站QQ：<a href="http://wpa.qq.com/msgrd?Uin=1737191719" title="欢迎通过QQ联系我们" target="_blank">1737191719</a>&nbsp;&nbsp;红歌会网粉丝QQ群：35758473</div>
                    <div>(投稿)邮箱：<a href="mailto:szhgh001@163.com" title="欢迎投稿或反映问题" target="_blank">szhgh001@163.com</a></div>
                </li>
                <div class="clear"></div>
            </ul>
        </div>
    </div>

    <script src="<?= $public_r['newsurl'] ?>skin/default/js/jquery.leanModal.min.js" type="text/javascript"></script>
    <div id="loginmodal" class="loginmodal" style="display:none;">
        <div class="modaletools"><a class="hidemodal" title="点此关闭">×</a></div>
        <form class="clearfix" name=login method=post action="<?= $public_r['newsurl'] ?>e/member/doaction.php">
            <div class="login left">
                <strong>会员登录</strong>
                <input type=hidden name=enews value=login>
                <input type=hidden name=ecmsfrom value=9>

                <div id="username" class="txtfield username"><input name="username" type="text" size="16"/></div>
                <div id="password" class="txtfield password"><input name="password" type="password" size="16"/></div>
                <div class="forgetmsg"><a href="/e/member/GetPassword/" title="点此取回密码" target="_blank">忘记密码？</a></div>
                <input type="submit" name="Submit" value="登陆" class="inputSub flatbtn-blu"/>
            </div>
            <div class="reg right">
                <div class="regmsg"><span>还不是会员？</span></div>
                <input type="button" name="Submit2" value="立即注册" class="regbutton"
                       onclick="window.open('<?= $public_r['newsurl'] ?>e/member/register/');"/>
            </div>
        </form>
    </div>
    <script type="text/javascript">
        $(function () {
            $('#loginform').submit(function (e) {
                return false;
            });

            $('#modaltrigger').leanModal({top: 110, overlay: 0.45, closeButton: ".hidemodal"});
            $('#modaltrigger_plinput').leanModal({top: 110, overlay: 0.45, closeButton: ".hidemodal"});

            $('#username input').OnFocus({box: "#username"});
            $('#password input').OnFocus({box: "#password"});
        });
    </script>
    </body>
    </html>
<?php
db_close();
$empire = null;
?>