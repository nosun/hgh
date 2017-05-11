<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$add='';
$bclassid=(int)$_GET['bclassid'];
if($pr['wapshowmid'])
{
	$add=" and modid in (".$pr['wapshowmid'].")";
}
DoWapHeader($pagetitle);
?>
<?php
if($bclassid)
{
	$returnurl="index.php?style=$wapstyle&amp;bclassid=".($class_r[$bclassid]['bclassid']?$class_r[$bclassid]['bclassid']:0);
?>
<p><b>子栏目列表：</b><?=$class_r[$bclassid]['classname']?></p>
<?php
}
else
{
	$returnurl="index.php?style=$wapstyle";
?>
<p><b>网站栏目:</b><?=$pagetitle?></p>
<?php
}
?>


<article id='Wapper'>
	<section class='column'>
		<h2><a href="/home-news">要闻版</a> <div class='jian'></div></h2>		
		<div class='list'>
		<article class="pages">	
		<?php
		$news=$empire->query("select classid,id,title,titlepic,ftitle from {$dbtbpre}ecms_article where ttid in (7,21,23) and isgood>2  order by newstime desc limit 9");
		$r_no=0;
		while($r=$empire->fetch($news))
		{
		$r_no++;
		if($r_no==4 or $r_no==7)
		echo '			</article>
			<article class="pages">	';
		?>
		
				<section><a href='show?classid=<?=$r[classid]?>&id=<?=$r[id]?>&style=0&cpage=0&cid=<?=$r[classid]?>&bclassid=4'><h3><?=$r[title]?></h3><div class='img-text'><img src='<?=$r[titlepic]?>' width="110" /><p><?=esub($r[ftitle],52,'...')?></p></div></a></section>			
		<?php
		}
		?>
		</article>
		</div>
		<div class='scroll'>
			<div class='more'><a href='/home-news'>更多要闻</a></div>
			<div class='left'></div>
			<div class='right'></div>
			<ul class='menu'>
				<li class='current'>●</li>
				<li>●</li>
				<li>●</li>
			</ul>
		</div>

		<h2><a href="/home-news">最新推荐</a> <div class='jian'></div></h2>	
		<div id="content">	
		  <section>
			<a href="/europe/2012_10_27_106268">
			  <h3>贝卢斯科尼刑期改为1年 将上诉</h3>
			  <div class="img-text">
				<img src="/thumbnail/list/2012/10/27/634869679983916710.jpg" width="110">
				<p>意大利米兰一家法院26日对前总理贝卢斯科尼行贿律师作伪证案作出...</p>
			  </div>
			</a>
		  </section>
		  <section>
			<a href="/europe/2012_10_27_106222">
			  <h3>英海军陆战队“太太团”为鼓舞士气</h3>
			  <div class="img-text">
				<img src="/thumbnail/list/2012/10/27/634869343381033499.jpg" width="110">
				<p>英海军陆战队“太太团”的性感照片将被制作成新年挂历，一方面用...</p>
			  </div>
			</a>
		  </section>
		  <section>
			<a href="/europe/2012_10_26_106162">
			  <h3>贝卢斯科尼获刑4年 禁止从政5年</h3>
			  <div class="img-text">
				<img src="/thumbnail/list/2012/10/26/634868894373244858.jpg" width="110">
				<p>意大利前总理、AC米兰主席贝卢斯科尼26日被判逃税罪成立，入狱4...</p>
			  </div>
			</a>
		  </section>
		  <section>
			<a href="/Education/2011_04_29_56595">
			  <h3>贝卢斯科尼三部曲</h3>
			  <div class="img-text">
				<p>第一步成为大企业家，第二步组建政党，第三步步入政坛，这是贝卢斯科尼成功问鼎总理位置的三部曲。</p>
			  </div>
			</a>
		  </section>
		  <section>
			<a href="/america/2011_11_14_61843">
			  <h3>旧时代的终结：意大利的贝卢斯科尼</h3>
			  <div class="img-text">
				<p>贝卢斯科尼是意大利战后执政时间最长的总理，但腐败案和对于他利用政治影响力服务于商业利益的指控贯穿于他的三届任期之中...</p>
			  </div>
			</a>
		  </section>
		  <section>
			<a href="/europe/2012_10_26_106124">
			  <h3>BBC头牌的身后事</h3>
			  <div class="img-text">
				<p>任何大媒体都免不了丑闻：这是处理棘手新闻的负面代价。可是，BBC繁复的层级结构与内部斗争现在暴露出了大问题。一位新闻...</p>
			  </div>
			</a>
		  </section>
		  <section>
			<a href="/europe/2012_10_26_106123">
			  <h3>BBC金字招牌轰然倒塌</h3>
			  <div class="img-text">
				<img src="/thumbnail/list/2012/10/26/634868677150507327.jpg" width="110">
				<p>截至今日，遭受BBC主持人萨维尔性侵的受害者已超过300人，BBC内...</p>
			  </div>
			</a>
		  </section>
		  <section>
			<a href="/europe/2012_10_26_106122">
			  <h3>西班牙王储被批不懂乞丐伸手意义 </h3>
			  <div class="img-text">
				<img src="/thumbnail/list/2012/10/26/634868675017203580.jpg" width="110">
				<p>西班牙王储在遇到一位乞丐向之伸手时，以为是要和他握手。这一幕...</p>
			  </div>
			</a>
		  </section>
		  <section>
			<a href="/europe/2012_10_26_106120">
			  <h3>英励志傲娇哥为当兵减126斤 减到皮</h3>
			  <div class="img-text">
				<img src="/thumbnail/list/2012/10/26/634868665910375584.jpg" width="110">
				<p>英国18岁小伙儿安东尼为当兵猛减肥，10个月减了63公斤多赘肉，终...</p>
			  </div>
			</a>
		  </section>
		  <section>
			<a href="/europe/2012_10_26_106101">
			  <h3>英一6岁女孩为母接生 只因爱看医疗</h3>
			  <div class="img-text">
				<img src="/thumbnail/list/2012/10/26/634868629245631186.jpg" width="110">
				<p>6岁还是问妈妈自己是从哪里来的年纪吧，但英国一名6岁的小女孩却...</p>
			  </div>
			</a>
		  </section>
		 </div>	
		  <div class="list" data-name="60260">
					点击载入更多
				</div>
	</section>

<p>
<?php
$sql=$empire->query("select classid,classname,islast from {$dbtbpre}enewsclass where bclassid='$bclassid' and wburl=''".$add." order by myorder,classid");
while($r=$empire->fetch($sql))
{
	$classurl="list.php?classid=".$r[classid]."&amp;style=".$wapstyle."&amp;bclassid=".$bclassid;
	$indexurl="index.php?bclassid=".$r[classid]."&amp;style=".$wapstyle;
	if($r['islast'])
	{
		$showsonclass="";
	}
	else
	{
		$showsonclass=" <small>(<a href=\"$indexurl\">下级栏目</a>)</small>";
	}
?>
<a href="<?=$classurl?>"><?=DoWapClearHtml($r[classname])?></a><?=$showsonclass?><br />
<?php
}
?>
</p>


<?php
DoWapFooter();
?>