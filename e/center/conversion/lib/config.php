<?php

//==================数据库配置

$config['db']['usedb']='mysql';	//数据库类型
$config['db']['dbver']='5.0';	//数据库版本
$config['db']['dbserver']='localhost';	//数据库登录地址
$config['db']['dbport']='3306';	//端口，不填为按默认
$config['db']['dbusername']='szhghcms';	//数据库用户名
$config['db']['dbpassword']='weirenminfuwu3309';	//数据库密码
$config['db']['dbname']='szhghcms';	//数据库名
$config['db']['setchar']='utf8';	//设置默认编码
$config['db']['dbchar']='utf8';	//数据库默认编码
$config['db']['showerror']=1;	//显示SQL错误提示(0为不显示,1为显示)

//===================每组转换数量与时间间隔配置

//TAG转换设置
$tag_doline=100;	//数据每组转换数量
$tag_doretime=0;	//数据每组转换间隔时间，单位：秒

//作者转换设置
$author_doline=100;	//数据每组转换数量
$author_doretime=0;	//数据每组转换间隔时间，单位：秒

//信息转换设置
$doline=100;	//数据每组转换数量
$doretime=0;	//数据每组转换间隔时间，单位：秒

//评论转换设置
$pl_doline=100;	//数据每组转换数量
$pl_doretime=0;	//数据每组转换间隔时间，单位：秒

//附件转换设置
$file_doline=100;	//数据每组转换数量
$file_doretime=0;	//数据每组转换间隔时间，单位：秒

////转换设置
//$_doline=100;	//数据每组转换数量
//$_doretime=0;	//数据每组转换间隔时间，单位：秒

?>
