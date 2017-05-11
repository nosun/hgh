<?php
require("../../class/connect.php");
require("../../class/q_functions.php");
require("../../class/db_sql.php");
require("../../data/dbcache/class.php");
require("../../member/class/user.php");
require('../class/ShopSysFun.php');
eCheckCloseMods('shop');//关闭模块
$link=db_connect();
$empire=new mysqlquery();

$shoppr=ShopSys_ReturnSet();
//验证权限
ShopCheckAddDdGroup($shoppr);

$r=$_POST;
if(!getcvar('mybuycar'))
{
	printerror('你的购物车没有商品','',1,0,1);
}
//变量处理
$r['truename']=ehtmlspecialchars($r['truename']);
$r['mycall']=ehtmlspecialchars($r['mycall']);
$r['phone']=ehtmlspecialchars($r['phone']);
$r['email']=ehtmlspecialchars($r['email']);
$r['oicq']=ehtmlspecialchars($r['oicq']);
$r['msn']=ehtmlspecialchars($r['msn']);
$r['address']=ehtmlspecialchars($r['address']);
$r['zip']=ehtmlspecialchars($r['zip']);
$r['signbuild']=ehtmlspecialchars($r['signbuild']);
$r['besttime']=ehtmlspecialchars($r['besttime']);
$r['bz']=ehtmlspecialchars($r['bz']);
$r['fptt']=ehtmlspecialchars($r['fptt']);
$r['fpname']=ehtmlspecialchars($r['fpname']);
$r['fp']=(int)$r['fp'];
$r['psid']=(int)$r['psid'];
$r['payfsid']=(int)$r['payfsid'];
//必填项
ShopSys_CheckDdMust($r,$shoppr);

$ddno=ShopSys_ReturnDdNo();//订单ID
$classids='';
$price=0;

//取得用户信息
$user=array();
$userid=(int)getcvar('mluserid',0,TRUE);//LGM修改,把用户ID加密[2014年3月16日21:43]
$username=RepPostVar(getcvar('mlusername'));
if($userid)
{
	$rnd=RepPostVar(getcvar('mlrnd'));
	$user=$empire->fetch1("select ".eReturnSelectMemberF('userid,money,userfen')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$userid' and ".egetmf('rnd')."='$rnd' limit 1");
	if(!$user['userid'])
	{
		printerror("MustSingleUser","history.go(-1)",1);
	}
}
//导入模板
require(ECMS_PATH.'e/template/ShopSys/SubmitOrder.php');
db_close();
$empire=null;
?>