<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']='第三方应用列表';
$url="<a href='/'>首页</a>&nbsp;>&nbsp;<a href='/e/member/cp/'>会员中心</a>&nbsp;>&nbsp;交互应用绑定管理";
if(empty($thispagetitle)) $thispagetitle = $public_diyr['pagetitle'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=$thispagetitle?></title>
    <link href="/skin/default/css/member.css" rel="stylesheet" type="text/css" />
    <script src="/skin/default/js/jquery-1.8.2.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/skin/default/js/custom.js"></script>
    <script type="text/javascript">
       function ChangeNewEiUrl(sender,elink,einfo)
       {
           var sel = $(sender);
           var selv = sel.val();
           var jopt = $('option:selected',sel);
           $("#"+einfo).text(jopt.data("title"));
           var mlink = $("#"+elink);
           if(!!mlink && mlink.length>0)
           {
               if(selv.length>0)
                  mlink.attr("href","index.php?id="+jopt.data("id")+"&apptype="+selv+"&ecms=1");
              else
                  mlink.attr("href","javascript:alert('请先选择一种应用类型');return false;");
           }
       }
    </script>
</head>
<body class="member_cp ListBind">
<?php
require(ECMS_PATH.'e/template/incfile/header.php');
?>  
 <div class="container">
 <?php
  require(ECMS_PATH . 'e/template/incfile/maintop.php');                
?>
  <div class="main">
<?php
   require(ECMS_PATH.'e/template/incfile/sidebar.php');
  ?> 
 <div class="member_main">
     <div class="main_message"><strong><?=$public_diyr['pagetitle']?></strong></div>
       <div class="section_content">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header">
    <td width="36%"><div align="center">平台</div></td>
    <td width="20%" height="30"><div align="center">绑定时间</div></td>
    <td width="20%" height="30"><div align="center">上次登录</div></td>
    <td width="9%" height="30"><div align="center">登录次数</div></td>
    <td width="15%"><div align="center">操作</div></td>
  </tr>
  <?php
  $htmlc='';
  $eis = array();
  while($rw=$empire->fetch($sql))
  {
      $eis[$rw['appname']]=array($rw['apptype'],$rw['appsay'],$rw['id']);
	  $binds=$empire->query("select id,bindtime,loginnum,lasttime,bindname from {$dbtbpre}enewsmember_connect where userid='$user[userid]' and aid='$rw[id]'");
          if(!empty($binds))
          {
              while($rv=$empire->fetch($binds))
              {
                  $dt1 =date('Y-m-d H:i:s',$rv['bindtime']);
                  $dt2 =date('Y-m-d H:i:s',$rv['lasttime']);
                  $dourl='<a href="/e/memberconnect/doaction.php?enews=DelBind&id='.$rv['id'].'" onclick="return confirm(\'确认要解除绑定?\');">解除绑定</a>';
                  $einame ='<span class="appname">'. $rw['appname'].'</span>';
                  if(!empty($rv['bindname'])) $einame.= '<span class="eusername">('.$rv['bindname'].')</span>';
                  $htmlc .= <<<MYOELK
  <tr bgcolor="#FFFFFF">
    <td><div align="center">
     {$einame}
    </div>
    </td>
    <td height="30"><div align="center">
     {$dt1}
    </div></td>
    <td height="30"><div align="center">
      {$dt2}
    </div></td>
    <td height="30"><div align="center">
      {$rv['loginnum']}
    </div></td>
    <td><div align="center">{$dourl}</div></td>
  </tr>
MYOELK;
              }
          }
  }
  echo $htmlc;
  $htmlc = '';
 ?>
 </table>   
<?php 
//最后一行,为绑定新的账号:
 if(count($eis)>0)
 {
     $selectcode = '<select onchange="javascript:ChangeNewEiUrl(this,\'neweilink\',\'seleinfo\');" name="selei" id="selei">';
     $seleinfo = '';
     $dourl=NULL;
     if(count($eis)>1)
     {
         $selectcode.='<option value="" selected="selected" data-title="请从下拉式列表中选择一种应用类型">请选择类型</option>';
         $seleinfo = '请从下拉式列表中选择一种应用类型';
     }
     foreach($eis as  $k=>$v)
     {
         $selectcode.='<option value="'.$v[0].'" data-title="'.$v[1].'" data-id="'.$v[2].'">'.$k.'</option>';
         if(empty($seleinfo)) $seleinfo = $v[1];
         if(empty($dourl))  $dourl='<a href="'.$public_r['newsurl'].'e/memberconnect/index.php?id='.$v[2].'&apptype='.$v[0].'&ecms=1&forcelogin=1" id="neweilink">立即绑定</a>';
     }     
     $selectcode.='</select>';
     $htmlc .= <<<MYOELK
  <div id='addnewappbox'>
    <div class='atitle'>
       添加新应用
    </div>
    <div class='aselect'>
     {$selectcode} <span id='seleinfo'>{$seleinfo}</span>
    </div>
    <div class='aenter'>{$dourl}</div>
  </div>             
MYOELK;
 }
echo $htmlc;
?>
 </div>
 </div>
  </div>
 <div class="clear"></div>   
 </div> 
    
<?php
require(ECMS_PATH.'e/template/incfile/footer.php');
?>