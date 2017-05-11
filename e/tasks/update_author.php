<?php
	$str_sql	=	'select id,title from '.$dbtbpre.'ecms_author order by id desc';
	$ls			=	$empire	->query($str_sql);
	while($l	=	$empire->fetch($ls)){
//		最后更新时间		
		$r	=	$empire->fetch1('select max(newstime) as newstime from '.$dbtbpre.'ecms_article where isgood>2 and author=\''.$l['title'].'\'');
	    $lastudtime	= 	(int)$r['newstime'];
		$newstime	= 	(int)$r['newstime'];
		$hitsum		=	author_total($l['title'],'1,0');
		$newshits	=	author_total($l['title'],'0,0');
		$commentssum=	author_total($l['title'],'1,1');
		$newcomments=	author_total($l['title'],'0,1');
		$diggsum	=	author_total($l['title'],'1,2');
		$newdigg	=	author_total($l['title'],'0,2');
		
	    $str_update	=	'update '.$dbtbpre.'ecms_author set 
	    	lastudtime	=	'.$lastudtime.',
			newstime    =	'.$newstime.',
	    	hitsum		=	'.$hitsum.',
	    	newhits		=	'.$newshits.',
	    	commentssum	=	'.$commentssum.',
	    	newcomments	=	'.$newcomments.',
	    	diggsum		=	'.$diggsum.',
	    	newdigg		=	'.$newdigg.' 
	    	where id='.$l['id'];
	    if($empire->query($str_update)){
	    	echo $str_update;
	    	echo '<br/>';
	    	echo '更新'.$l['title'].'数据成功！<br/>';
	    }else{
	    	echo $str_update;
	    	echo '<br/>';
	    	echo '更新'.$l['title'].'数据<strong>失败</strong>！<br/>';
	    }
	   
	}
	 die();

function author_total($title,$cs){
	global $empire,$dbtbpre;
	$arr_cs	=	explode(',', $cs);
	$condition	=	$arr_cs[0]	?	''	:	' and newstime>'.(time()-86400*30);
	switch ((int)$arr_cs[1]){
		case 0:		
			$field	=	'onclick';
			break;
		case 1:
			$field	=	'plnum';
			break;
		case 2:
			$field	=	'diggtop';
			break;
	}
	
	$str_sql	=	'select sum('.$field.') as total from '.$dbtbpre.'ecms_article
		where author=\''.$title.'\''.$condition;
	$rs=$empire->fetch1($str_sql);
    return (int)$rs['total'];
}
?>