<?php
if (!defined('InEmpireCMS')) {
    exit();
}

//--------------- 界面参数 ---------------
//会员界面附件地址前缀
$memberskinurl = $public_r['newsurl'] . 'skin/member/images/';

//LOGO图片地址
$logoimgurl = $memberskinurl . 'logo.jpg';

//加减号图片地址
$addimgurl = $memberskinurl . 'add.gif';
$noaddimgurl = $memberskinurl . 'noadd.gif';

//其它色调可修改CSS部分
//--------------- 界面参数 ---------------
//识别并显示当前菜单
function EcmsShowThisMemberMenu() {
    global $memberskinurl, $noaddimgurl;
    $selffile = eReturnSelfPage(0);
    if (stristr($selffile, '/member/msg')) {
        $menuname = 'menumsg';
    } elseif (stristr($selffile, 'e/DoInfo')) {
        $menuname = 'menuinfo';
    } elseif (stristr($selffile, '/member/mspace')) {
        $menuname = 'menuspace';
    } elseif (stristr($selffile, 'e/ShopSys')) {
        $menuname = 'menushop';
    } elseif (stristr($selffile, 'e/payapi') || stristr($selffile, '/member/buygroup') || stristr($selffile, '/member/card') || stristr($selffile, '/member/buybak') || stristr($selffile, '/member/downbak')) {
        $menuname = 'menupay';
    } else {
        $menuname = 'menumember';
    }
    echo'<script>turnit(do' . $menuname . ',"' . $menuname . 'img");</script>';
}

//网页标题
$thispagetitle = $public_diyr['pagetitle'] ? $public_diyr['pagetitle'] : '会员中心';
//会员信息
$tmgetuserid = (int) getcvar('mluserid',0,TRUE); //用户ID LGM修改,把用户ID加密[2014年3月16日21:43]
$tmgetusername = RepPostVar(getcvar('mlusername')); //用户名
$tmgetgroupid = (int) getcvar('mlgroupid'); //用户组ID
$tmgetgroupname = '游客';
//会员组名称
if ($tmgetgroupid) {
    $tmgetgroupname = $level_r[$tmgetgroupid]['groupname'];
    if (!$tmgetgroupname) {
        include_once(ECMS_PATH . 'e/data/dbcache/MemberLevel.php');
        $tmgetgroupname = $level_r[$tmgetgroupid]['groupname'];
    }
}

//模型
$tgetmid = (int) $_GET['mid'];
?>

<div id="header">
    <div class="redlogo">
        <a title="红歌会网首页" href="<?= $public_r['newsurl'] ?>"><img src="<?= $public_r['newsurl'] ?>skin/default/images/logo.png"></a>
    </div>

</div>

<?php
if ($tmgetuserid) { //已登录
    ?>  

    <div class="position">
        <div class="right">
            <a href="<?= $public_r['newsurl'] ?>">网站首页</a>
            | <a href="<?= $public_r['newsurl'] ?>e/member/cp/">用户中心</a>
            | <a href="<?= $public_r['newsurl'] ?>e/DoInfo/AddInfo.php?mid=10&enews=MAddInfo&classid=29">投稿</a>
            | <a href="<?= $public_r['newsurl'] ?>e/member/doaction.php?enews=exit" onclick="return confirm('确认要退出?');">退出</a>
        </div>            
        <div class="location">当前位置：<?= $url ?></div>
    </div>  

    <?php
} else {   //游客
    ?>

    <div class="position">
        <div class="right">
            <a href="<?= $public_r['newsurl'] ?>">网站首页</a>
            | <a href="<?= $public_r['newsurl'] ?>e/member/cp/">用户中心</a>
            | <a href="<?= $public_r['newsurl'] ?>e/member/login/">登录</a>                
        </div>            
        <div class="location">当前位置：<?= $url ?></div>
    </div>      

    <?php
}
?>



