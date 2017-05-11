<?php

/*
 * 
 *用途：测试函数功能； 
 * 
 */

$a='您好';
$b='您好,您好';

$counts_a=substr_count($a,',');
$counts_b=substr_count($b,',');
echo $counts_a.'<br />';
echo $counts_b;
?>
