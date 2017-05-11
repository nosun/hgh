<?php
function gbk2utf8($data){
	if(is_array($data))
	{
		return array_map('gbk2utf8', $data);
	}
	return iconv('gbk','utf-8',$data);
}

function utf82gbk($data){
	if(is_array($data))
	{
		return array_map('utf82gbk', $data);
	}
	return iconv('utf-8','gbk',$data);
}

function JSON($array)
{
	return json_encode(gbk2utf8($array));
}
?>