<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
//位置
$url="$spacename &gt; 个人介绍";
include("header.temp.php");
if($user_register)
{
	$registertime=date("Y-m-d H:i:s",$ur[$user_registertime]);
}
else
{
	$registertime=$ur[$user_registertime];
}
//oicq
if($addur['oicq'])
{
	$addur['oicq']="<a href='http://wpa.qq.com/msgrd?V=1&amp;Uin=".$addur['oicq']."&amp;Site=".$public_r['sitename']."&amp;Menu=yes' target='_blank'><img src='http://wpa.qq.com/pa?p=1:".$addur['oicq'].":4'  border='0' alt='QQ' />".$addur['oicq']."</a>";
}
//简介
$usersay=$addur['saytext']?$addur['saytext']:'暂无简介';
$usersay=RepFieldtextNbsp($usersay);
?>
<?=$spacegg?>
<div class="east">
    <dl class="border">

      <dt class="caption"><strong>基本资料</strong></dt>
      <dd class="body pB10">
        <table width="700" align="center" class="tList">
          <tr>
            <td>昵称：</td>
            <td><?=$username?></td>
          </tr>

          <tr>
            <td width="101">性别： </td>
            <td width="593"><?=$addur[sex]?></td>
          </tr>
          <tr>
            <td>会员等级：</td>
            <td> <?=$level_r[$ur[$user_group]]['groupname']?>
              </td>

          </tr>
          <tr>
            <td>联系电话：</td>
            <td> <?=$addur[call]?>
              </td>
          </tr>
          <tr>
            <td>手机：</td>
            <td><?=$addur[phone]?></td>
          </tr>

          <tr>
            <td>qq：</td>
            <td><?=$addur[oicq]?></td>
          </tr>
          <tr>
            <td>星座：</td>
            <td>白羊座</td>

          </tr>
          <tr>
            <td>血型：</td>
            <td>保密</td>
          </tr>
          <tr>
            <td>身高：</td>

            <td>>160 厘米</td>
          </tr>
          <tr>
            <td>体型：</td>
            <td>保密</td>
          </tr>
        </table>

      </dd>
    </dl>
    <dl class="border mT10">
      <dt class="caption"><strong>经济状况</strong></dt>
      <dd class="body pB10">
        <table width="700" align="center" class="tList">
          <tr>
            <td width="101">收入情况：</td>

            <td width="593">保密</td>
          </tr>
          <tr>
            <td>教育程度：</td>
            <td>保密</td>
          </tr>
          <tr>

            <td>住房情况：</td>
            <td>保密</td>
          </tr>
          <tr>
            <td>所属行业：</td>
            <td>保密</td>
          </tr>

          <tr>
            <td>掌握语言：</td>
            <td>
              </td>
          </tr>
        </table>
      </dd>
    </dl>

    <dl class="border mT10">
      <dt class="caption"><strong>兴趣爱好</strong></dt>
      <dd class="body pB10">
        <table width="700" align="center" class="tList">
          <tr>
            <td width="101">是否喝酒：</td>
            <td width="593">保密</td>

          </tr>
          <tr>
            <td>是否抽烟：</td>
            <td>保密</td>
          </tr>
          <tr>
            <td>兴趣爱好：</td>

            <td> </td>
          </tr>
        </table>
      </dd>
    </dl>
  </div>
<?php
include("footer.temp.php");
?>