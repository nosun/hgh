<?php

  /**
   * 
   *		EmpireCMS repeatedly development
   *		EmpireCMS version 6.5 
   *		lastupdate 20101227 
   * 
   *		author	: trylife 
   *		website	: www.trylifecn 
   *		Email	: trylife@qq.com 
   * 
   */

require_once("php-dev-common-functions.php");
$phome_db_dbchar = 'utf8';
/**
 * 查询模型主表单行返回数组
 * 参数：栏目ID，信息ID，0返回数组R 1返回JSON，字段可选（为空则查询所有字段），条件语句可选（不填写则条件为空)条件之间使用AND连接 条件前不用AND
 */
function ecms_r($classid,$id,$type=0,$field=" * ",$where="")
{
	global $empire,$dbtbpre,$class_r;
	$field?'':$field=" * ";
	$where?$where='and '.$where:'';
	$r=$empire->fetch1("select ".$field." from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where id='$id' ".$where." limit 1");
	$type?$re=ecms_json($r):$re=$r;
	return $re;
}

function ecms_table_r($table,$type=0,$field="",$where="")
{
	global $empire,$dbtbpre,$class_r;
	$field?'':$field=" * ";
	$where?$where='where '.$where:'';
	$r=$empire->fetch1("select ".$field." from {$dbtbpre}".$table."  ".$where." limit 1");
	$type?$re=ecms_json($r):$re=$r;
	return $re;
}

function ecms_table_r2($table,$type=0,$field="",$where="")
{
	global $empire,$dbtbpre,$class_r;
	$field?'':$field=" * ";
	$where?$where='where '.$where:'';
	$r=$empire->fetch1("select ".$field." from ".$table."  ".$where." limit 1");
	$type?$re=ecms_json($r):$re=$r;
	return $re;
}

function ecms_table_sql($field,$tbname,$add='')
{
	global $empire,$dbtbpre;
	$field?'':$field=" * ";
	$sql=$empire->query("select ".$field." from {$dbtbpre}".$tbname." ".$add);	
	return $sql;
}

function ecms_table_sql2($field,$tbname,$add='')
{
	global $empire,$dbtbpre;
	$field?'':$field=" * ";
	$sql=$empire->query("select ".$field." from ".$tbname." ".$add);	
	return $sql;
}

function ecms_table_total($tbname,$add)
{
	global $empire,$dbtbpre;
	$total=$empire->gettotal("select count(*) as total from {$dbtbpre}".$tbname." ".$add);
	return $total;
}

function ecms_table_total2($tbname,$add)
{
	global $empire,$dbtbpre;
	$total=$empire->gettotal("select count(*) as total from ".$tbname." ".$add);
	return $total;
}

function ecms_table_insert($tbname,$field,$values)
{
	global $empire,$dbtbpre;
	$sql=$empire->query("insert into {$dbtbpre}".$tbname." (".$field.") values(".$values.")");
	return $sql;
}

function ecms_table_update($tbname,$set,$add)
{
	global $empire,$dbtbpre;
	$sql=$empire->query("update {$dbtbpre}".$tbname." set ".$set." ".$add);
	return $sql;	
}

function ecms_table_delete($tbname,$add)
{
	global $empire,$dbtbpre;
	$sql=$empire->query("delete from {$dbtbpre}".$tbname." ".$add);
	return $sql;	
}

function ecms_json($array)
{
	global $phome_db_dbchar;
	$phome_db_dbchar=='utf8'?$re=json_encode($array):$re=json_encode(gbk2utf8($array));
	return $re;
}

function ecms_utf82gbk($array)
{
	global $phome_db_dbchar;
	$phome_db_dbchar=='utf8'?$re=$array:$re=utf82gbk($array);
	return $re;
}   

function ecms_msg($msgid,$msg,$code,$gotourl,$method='printerror2')
{
	if($method=='printerror2')
	{
		printerror2($msg,$gotourl);
	}
	elseif($method=='echo')
	{
		echo $msg;
		exit();
	}
	elseif($method=='json')
	{
		ecms_json(array('msgid'=>$msgid,'msg'=>$msg,'code'=>$code,));
		exit();
	}
}

function ecms_memberlogin($userid)
{
	global $empire,$user_tablename,$public_r,$user_groupid,$user_username,$user_userid,$user_email,$user_password,$user_dopass,$user_rnd,$user_registertime,$user_register,$user_group,$user_saltnum,$user_salt,$user_seting,$forumgroupid,$registerurl,$dbtbpre,$user_regcookietime,$user_userfen,$user_checked,$level_r;
	$r=$empire->fetch1("select * from ".$user_tablename." where ".$user_userid."='$userid' limit 1");
	$rnd=make_password(12);
	$sql=$empire->query("update ".$user_tablename." set ".$user_rnd."='$rnd' where ".$user_userid."='$userid' ");
	$logincookie=0;
        esetcookie('lastlogin',time().'@'.$r[$user_userid],time()+7776000,0,TRUE);//网站登录的最后时间,以标识已经注册了账户.保存三个月(LGM添加[2014年3月18日9:31])
	$set1=esetcookie("mlusername",$r[$user_username],$logincookie);
	$set2=esetcookie("mluserid",$r[$user_userid],$logincookie,0,TRUE);//LGM修改,把用户ID加密[2014年3月16日21:43]
	$set3=esetcookie("mlgroupid",$r[$user_group],$logincookie);
	$set4=esetcookie("mlrnd",$rnd,$logincookie);
	
	if( $set1 && $set2 && $set3 && $set4 )
	{
		$re=1;	
	}
	else
	{
		$re=0;
	}
	
	return $re;
}

function ecms_SendEmail($email,$subject,$content)
{
	@include(ECMS_PATH.'e/class/SendEmail.inc.php');  
	$re=EcmsToSendMail($email,$subject,$content);  
	return $re;
}  
?>