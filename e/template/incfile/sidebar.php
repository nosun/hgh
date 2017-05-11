<?php
if (!defined('InEmpireCMS')) {
    exit();
}
?>

<div class="sidebar">
    <?php
    if ($tmgetuserid) { //已登录
        ?>
        <div class="menuheader"><strong>帐号</strong></div>
        <div class="menu">
            <ul>
                <li><a class="infomation" href="<?= $public_r['newsurl'] ?>e/member/EditInfo/">修改资料</a></li>
                <li><a class="password" href="<?= $public_r['newsurl'] ?>e/member/EditInfo/EditSafeInfo.php">修改安全信息</a></li>                    
                <li><a class="fav" href="<?= $public_r['newsurl'] ?>e/member/fava/">收藏夹</a></li>                      
                <li><a class="friend" href="<?= $public_r['newsurl'] ?>e/member/friend/">好友列表</a></li>                    
                <li><a class="bind" href="<?= $public_r['newsurl'] ?>e/memberconnect/ListBind.php">应用管理</a></li>
            </ul>
        </div>

        <div class="menuheader"><strong>站内消息</strong></div>
        <div class="menu">
            <ul>
                <li><a class="msg" href="<?= $public_r['newsurl'] ?>e/member/msg/AddMsg/?enews=AddMsg">发送消息</a></li>
                <li><a class="msglist" href="<?= $public_r['newsurl'] ?>e/member/msg/">消息列表</a></li>                    
            </ul>
        </div>            

        <div class="menuheader"><strong>投稿</strong></div>
        <div class="menu">
            <ul>
                <?php
                //输出可管理的模型
                $tmodsql = $empire->query("select mid,qmname from {$dbtbpre}enewsmod where usemod=0 and showmod=0 and qenter<>'' order by myorder,mid");
                while ($tmodr = $empire->fetch($tmodsql)) {
                    $fontb = "";
                    $fontb1 = "";
                    if ($tmodr['mid'] == $tgetmid) {
                        $fontb = "<b>";
                        $fontb1 = "</b>";
                    }
                    ?>                    
                    <li><a class="mngarticle" href="<?= $public_r['newsurl'] ?>e/DoInfo/ListInfo.php?mid=<?= $tmodr['mid'] ?>">管理文章</a></li>
                    <li><a class="subarticle" href="<?= $public_r['newsurl'] ?>e/DoInfo/AddInfo.php?mid=10&enews=MAddInfo&classid=29">发布文章</a></li>
                    <?php
                }
                ?>                  
            </ul>
        </div>  

    <?php
} else { //游客
    ?>

        <div class="menuheader"><strong>帐号</strong></div>
        <div class="menu">
            <ul>
                <li><a href="<?= $public_r['newsurl'] ?>e/member/login/">会员登录</a></li>
                <li><a href="<?= $public_r['newsurl'] ?>e/member/register/">注册帐号</a></li>                    
            </ul>
        </div>                   
    <?php
}
?>

</div>
