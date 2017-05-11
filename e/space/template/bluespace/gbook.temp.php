<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
//位置
$url="$spacename &gt; 留言";
include("header.temp.php");
$viewuid=(int)getcvar('mluserid',0,TRUE);//LGM修改,把用户ID加密[2014年3月16日21:43]
$adminmenu='';
if($viewuid==$userid)
{
	$adminmenu="<a href='../member/mspace/gbook.php' target='_blank'>管理留言</a>";
}
?>
<?=$spacegg?>

 <div class="east">
    <dl class="border">

      <dt class="caption"><strong>留言</strong></dt>
	  
	  <?php
	while($r=$empire->fetch($sql))
	{
		if($r['uid'])
		{
			$r['uname']="<b><a href='../space/?userid=$r[uid]' target='_blank'>$r[uname]</a></b>";
		}
		//管理菜单
		$adminlink='';
		$ip='';
		if($adminmenu)
		{
			$ip=' IP: '.$r[ip];
			$adminlink="[<a href='#ecms' onclick=\"window.open('../member/mspace/ReGbook.php?gid=$r[gid]','','width=600,height=380,scrollbars=yes');\">回复</a>]&nbsp;&nbsp;[<a href='../member/mspace/?enews=DelMemberGbook&gid=$r[gid]' onclick=\"return confirm('确认要删除?');\">删除</a>]";
		}
		$gbuname=$r[uname].$ip;
		//私密
		if($r['isprivate'])
		{
			if($adminmenu||($r[uid]&&$viewuid==$r[uid]))
			{
				$r['gbtext']="<font color='blue'>[悄悄话] ".$r['gbtext']."</font>";
			}
			else
			{
				$r['gbtext']='[悄悄话隐藏]';
			}
		}
	?>
      <dd class="body pB10">         
	  <div class="allList pTB10 dashed">
          <div class="img"> 
		  <img src="<?=$userpic?>" width="50" height="50"  />
	  </div>
          <div class="txt">
            <p class="p1 mB5">

            <h5 class="fLeft"><?=$gbuname?>：</h5>
            <span class="aGray fRight"><?=$r[addtime]?></span>
            </p>
			<p class="p2 lh22 f14 aBlack clear">
			<?=nl2br($r['gbtext'])?>
			
			</p>
			<?
			if($r['retext'])
			{
			?>
            回复：<?=nl2br($r['retext'])?>
			<?
			}
			?>
          </div>
          <div class="clearfix"></div>
        </div>
		<?php
	}
	?>

                <div class="fRight mTB10 pd10"><span><?=$returnpage?></span></div>
        <div class="clearfix"></div>
      </dd>
    </dl>
    <dl class="border mT10">
      <dt class="caption"><strong>给我留言</strong></dt>
      <dd class="body pB10">
         <form name="addgbook" method="post" action="../member/mspace/index.php">

     	  <input type="hidden" name="userid" value="<?=$userid?>">
	  <input type="hidden" name="enews" value="AddMemberGbook">
	  
          <textarea name="gbtext" id="gbtext" style="width:98%; height:120px;" class="clear mB10 text"></textarea>
          <button class="fRight button buttonBlue2" type="submit">提交</button>
          <label>昵称：</label>
          <input name="uname" type="text" id="uname" value="<?=getcvar('mlusername')?>" class="text" style="width:100px; height:20px" />
		  <label>私密:</label>
              <input name="isprivate" type="checkbox" id="isprivate" value="1">

          <label>验证码：</label>
          <input name="key" type="text" class="text" id="vdcode" style="width:50px; height:20px;text-transform:uppercase;" />
          <img src='<?=$public_r[newsurl]?>e/ShowKey/?v=spacegb' width='50' height='20' align="absmiddle"  alt="验证码" title="看不清,点击换一个"  onclick="javascript:this.src='/e/ShowKey/?v=spacegb&n='+Math.random()"/>
		  
		  <a href="../member/GetPassword/" target="_blank" class="mL10 mR5">找回密码</a><a href="../member/register/"  target="_blank" title="注册">注册</a>
        </form>
        <p class="mp10 textCenter aGray">以上网友发言只代表其个人观点，不代表本站的观点或立场。</p>
      </dd>
    </dl>

  </div>





<?php
include("footer.temp.php");
?>
