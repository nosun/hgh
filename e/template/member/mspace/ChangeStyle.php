<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?=$thispagetitle?></title>
    <link href="/skin/default/css/member.css" rel="stylesheet" type="text/css" />

</head>

<body class="member_cp qlistinfo">


<?php
$temp="<tr><td width='25%' bgcolor='ffffff' align=center><!--list.var1--></td><td width='25%' bgcolor='ffffff' align=center><!--list.var2--></td><td width='25%' bgcolor='ffffff' align=center><!--list.var3--></td><td width='25%' bgcolor='ffffff' align=center><!--list.var4--></td></tr>";
$header="<table width='100%' border='0' cellpadding='3' cellspacing='1' bgcolor='#DBEAF5' align='center'>";
$footer="<tr><td colspan='4' align=center>".$returnpage."</td></tr></table>";

$templist="";
$sql=$empire->query($query);
$b=0;
$ti=0;
$tlistvar=$temp;
while($r=$empire->fetch($sql))
{
	$b=1;
	$ti++;
	if(empty($r[stylepic]))
	{
		$r[stylepic]="../../data/images/notemp.gif";
	}
	//当前模板
	if($r['styleid']==$addr[spacestyleid])
	{
		$r[stylename]='<b>'.$r[stylename].'</b>';
	}
	$var="<a title=\"".$r[stylesay]."\"><img src='$r[stylepic]' width=92 height=100 border=0></a><br><span style='line-height=15pt'>".$r[stylename]."</span><br><span style='line-height=15pt'>[<a href='index.php?enews=ChangeSpaceStyle&styleid=".$r[styleid]."'>选定</a>]</span>";
	$tlistvar=str_replace("<!--list.var".$ti."-->",$var,$tlistvar);
	if($ti>=4)
	{
		$templist.=$tlistvar;
		$tlistvar=$temp;
		$ti=0;
	}
}
//模板
if($ti!=0&&$ti<4)
{
	$templist.=$tlistvar;
}
$templist=$header.$templist.$footer;

$public_diyr['pagetitle']='选择空间模板';
$url="<a href='../../../'>首页</a>&nbsp;>&nbsp;<a href='../cp/'>会员中心</a>&nbsp;>&nbsp;选择空间模板";
require(ECMS_PATH.'e/template/incfile/header.php');
?>
    
    <div class="container">
        <div class="main">
            <?php
                require(ECMS_PATH.'e/template/incfile/maintop.php');
                require(ECMS_PATH.'e/template/incfile/sidebar.php');
            ?> 
            <div class="member_main">
                <div class="main_message"><strong>选择模板</strong></div>
                <div class="section_content">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top"><?=$templist?></td>
                    </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="clear"></div>        
    </div>
<?php
require(ECMS_PATH.'e/template/incfile/footer.php');
?>