<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
//位置
$url="$spacename &gt; $mr[qmname]";
include("header.temp.php");
?>
<?=$spacegg?>



  <div class="east">
    <dl class="border">
      <dt class="caption"><strong><?=$mr['qmname']?></strong></dt>
      <dd class="body">
        <div class="mp10 dashed">
		
			<?php
	while($r=$empire->fetch($sql))
	{
		$titleurl=sys_ReturnBqTitleLink($r);//链接
	?>
	
	          <div class="mB10">
          <h3 class="fLeft"><a href="<?=$titleurl?>"></a><?=$r[title]?></h3>
          <span class="mL5 aGray">(<?=date("Y-m-d H:i:s",$r[newstime])?>)</span>
          </div>
          <p class="f14 aBlack lh22"><?=$r[smalltext]?></p>
          <div id="artInfo">
            <strong class="fLeft"><a href="<?=$titleurl?>" target="_blank" title="点击查看全文">点击查看全文</a></strong>
          </div><div class="clearfix pB10"></div>
        
	<?php
	}
	?>

</div>
          
                <div class="fRight mB10"><?=$returnpage?></div>
        <div class="clearfix"></div>
      </dd>
    </dl>

  </div>

<?php
include("footer.temp.php");
?>
