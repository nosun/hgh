<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
//oicq
if($addr['oicq'])
{
	$addr['oicq']="<a href='http://wpa.qq.com/msgrd?V=1&amp;Uin=".$addr['oicq']."&amp;Site=".$public_r['sitename']."&amp;Menu=yes' target='_blank'><img src='http://wpa.qq.com/pa?p=1:".$addr['oicq'].":4'  border='0' alt='QQ' />".$addr['oicq']."</a>";
}
//表单
$record="<!--record-->";
$field="<!--field--->";
$er=explode($record,$formr['viewenter']);
$count=count($er);
for($i=0;$i<$count-1;$i++)
{
	$er1=explode($field,$er[$i]);
	if(strstr($formr['filef'],",".$er1[1].","))//附件
	{
		if($addr[$er1[1]])
		{
			$val="<a href='".$addr[$er1[1]]."' target='_blank'>".$addr[$er1[1]]."</a>";
		}
		else
		{
			$val="";
		}
	}
	elseif(strstr($formr['imgf'],",".$er1[1].","))//图片
	{
		if($addr[$er1[1]])
		{
			$val="<img src='".$addr[$er1[1]]."' border=0>";
		}
		else
		{
			$val="";
		}
	}
	elseif(strstr($formr['tobrf'],",".$er1[1].","))//多行文本框
	{
		$val=nl2br($addr[$er1[1]]);
	}
	else
	{
		$val=$addr[$er1[1]];
	}
	$memberinfo.="<tr bgcolor='#FFFFFF'><td height=25>".$er1[0].":</td><td>".$val."</td></tr>";
}
$public_diyr['pagetitle']='查看 '.$username.' 的会员资料';
$url="<a href='../../../'>首页</a>&nbsp;>&nbsp;<a href='../cp/'>会员中心</a>&nbsp;>&nbsp;查看会员资料";
if(empty($thispagetitle)) $thispagetitle = $public_diyr['pagetitle'];  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?=$thispagetitle?></title>
    <link href="/skin/default/css/member.css" rel="stylesheet" type="text/css" />
    <script src="/skin/default/js/jquery-1.8.2.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/skin/default/js/custom.js"></script>
</head>
<body class="member_cp Msg">
<?php
require(ECMS_PATH.'e/template/incfile/header.php');
?>    
    <div class="container">
        <div class="main">
            <?php
                require(ECMS_PATH.'e/template/incfile/maintop.php');
                require(ECMS_PATH.'e/template/incfile/sidebar.php');
            ?> 
            <div class="member_main">
                <div class="main_message"><strong>查看 <?=$username?> 的会员资料</strong></div>
                <div class="section_content">
                    
                    <div class="formitem">
                        <label class="inputtext">&nbsp;&nbsp;</label>
                        <div class="input">
                                [ <a href="../msg/AddMsg/?username=<?=$username?>" target="_blank">发短消息</a> ] 
                                [ <a href="../friend/add/?fname=<?=$username?>" target="_blank">加为好友</a> ]
                                [ <a href="../../space/?username=<?=$username?>" target="_blank">会员空间</a> ] 
                        </div>
                    </div>   
                    <div class="formitem">
                        <label class="inputtext">用户名：</label>
                        <div class="input"><?=$username?></div>
                    </div>                    
                    <div class="formitem">
                        <label class="inputtext">注册时间：</label>
                        <div class="input">
                            <?=$registertime?>
                        </div>
                    </div>                     
                    <div class="formitem">
                        <label class="inputtext">会员等级：</label>
                        <div class="input">
                            <?=$level_r[$r[groupid]][groupname]?>
                        </div>
                    </div>   
                    <div class="formitem">
                        <label class="inputtext">&nbsp;&nbsp;</label>
                        <div class="input">
                            <input type='button' name='Submit2' value='返回' onclick='history.go(-1)' />
                        </div>
                    </div> 

                </div>
            </div>
        </div>
        <div class="clear"></div>        
    </div>
<?php
require(ECMS_PATH.'e/template/incfile/footer.php');
?>