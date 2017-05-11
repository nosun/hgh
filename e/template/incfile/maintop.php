<?php
if (!defined('InEmpireCMS')) {
    exit();
}
?>

<div class="member_info">
    <img class="avatar" src="<?= $userpic ?>" width="74" height="74" />
    <div class="textinfo">
        <div class="usertext">
            <strong><a><?= $user[username] ?></a></strong>
            <p><?= $saytext ?></p>                        
        </div>
    </div>
    <div>
        <div class="usermsg">
            <div class="msg"><a href="/e/member/fava/" title="点击查看收藏文章"><span class="countnum"><?= $favacounts ?></span>&nbsp;收藏文章</a>&nbsp;&nbsp;&nbsp;<a href="/e/DoInfo/AddInfo.php?mid=10&enews=MAddInfo&classid=29" title="点击查看发布文章"><span class="countnum"><?= $articlecounts ?></span>&nbsp;发布文章</a></div>
            <div class="notice"><?= $havemsg ?> <?= $havegbook ?> <span style='background:url(/e/data/images/modify.jpg) no-repeat scroll 0 0 transparent;'><a href="/e/member/EditInfo/" title="点击修改资料">修改资料</a></span></div>
        </div>
    </div>
    <div class="clear"></div>
</div>
