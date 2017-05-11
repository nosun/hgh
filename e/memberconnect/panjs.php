<?php
/**
 * 产生第三方交互平台登录/评论发布列表的HTML CODE
 */
require_once('../class/connect.php');
if(!defined('InEmpireCMS'))
{
	exit();
}
eCheckCloseMods('member');//关闭模块
$myuserid=(int)getcvar('mluserid',0,TRUE);
$mhavelogin=(bool)$myuserid;//是否登录
//获取参数--------------
$id=(int)$_GET['id'];
$classid=(int)$_GET['classid'];
$pagetitle = $_REQUEST['pagetitle'];//评论显示所在页面的标题
$titleurl = $_REQUEST['titleurl'];//评论显示所在页面的网址
$titlepic = $_REQUEST['titlepic'];//评论显示所在页面的标题图片
$playurl = $_REQUEST['playurl'];//评论显示所在页面的媒体地址
if(isset($GLOBALS['__playurl'][$id]))
{
    if(empty($playurl))
        $playurl=$GLOBALS['__playurl'][$id];
    unset($GLOBALS['__playurl'][$id]);
}
$type=$_GET['type'];
$class=$_GET['dclass'];//显示的classname
$etlistid = 'etag'.$id;
//参数预处理-------------------------
if(empty($type))$type='login';
$type=strtolower($type);
if(empty($class)) $class = $type;
//---------------------------------
$DistrCode='';
$appendcode = '';//附加代码
if ($mhavelogin) {
    if ($type == 'pl') {
        if (!isset($_SESSION))
            session_start();
        $bindeis = $_SESSION['mlbindeis']; // array(0=>array("id","aid","apptype","openid","ename","token",'refresh_token',"scope","appkey","appsecret","used")) 当前用户绑定的第三方交互账号
        if (!empty($bindeis)) {
            $applist = '';
            $eiManualClosedstr = getcvar('EiMClosed', -1); //读取已经被用户手动关闭的:                
            $eiManualClosed = array();
            if (!empty($eiManualClosedstr))
                $eiManualClosed = preg_split('/[^\d\-\+]+/', $eiManualClosedstr, -1, PREG_SPLIT_NO_EMPTY);
            $DistrCode .= '<span class="eidistrtitle">同步到</span><span class="distrlist dr_' . $class . '" id="'.$etlistid.'">';
            $haveQQ= FALSE;
            foreach ($bindeis as $a) {
                if($a['apptype'] == 'qq') 
                    $haveQQ = TRUE;
                $isused = FALSE;
                if ($a['used'] == '1' && (in_array($a['id'], $eiManualClosed) == FALSE)) {
                    $applist .= $a['id'] . ',';
                    $isused = TRUE;
                }                
                $DistrCode .= '<a href="javascript:void(0);" onclick="javascript:hiteiv(this,\'' . $a['id'] . '\',\'selecteiv\')" class="tag_' . $a['apptype'] . ($isused ? ' isselect' : ' disable') . ($a['used'] == '0' ? ' invalid' : '') . '" title="' . addslashes($a ['ename']) . (($a['used'] == 1) ? '' : '&#10;(已经过期,请用此账号重新登录)') . '"><em class="' . ($isused ? 'isselect' : 'disable') . '">' . $a['ename'] . '</em></a>';
            }
            if($haveQQ){
               $isvd = !in_array('qz', $eiManualClosed);
               $DistrCode.= '<a href="javascript:void(0);" onclick="javascript:hiteiv(this,\'qz\',\'selecteiv\')" class="tag_qz '. ($isvd ? ' isselect' : ' disable') .'" title="分享到QQ空间"><em class="' . ($isvd ? 'isselect' : 'disable') . '" data-n="0">QQ空间</em></a>';
            }
            $DistrCode .= '</span><input name="selecteiv" type="hidden" id="selecteiv" value="' . $applist . '" />';
        }
        if (empty($titleurl) && ($classid || $id)) {//没有包含在URL参数内的情况下，到数据库中提取（会影响效率！）  
            include_once(ECMS_PATH . 'e' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'userfun.php');
            $mr = GetArcTitleAndUrl($classid, $id);
            if ($mr != NULL) {
                $pagetitle = $mr['title'];
                $titleurl = $mr['titleurl'];
            }
        }
        if (!empty($pagetitle))
            $appendcode.='<input name="pagetitle" type="hidden" id="pagetitle" value="' . htmlspecialchars($pagetitle) . '" />';
        if (!empty($titleurl))
            $appendcode.='<input name="titleurl" type="hidden" id="titleurl" value="' .htmlspecialchars($titleurl) . '" />';
        if (!empty($titlepic))
            $titlepic = ' value="' . htmlspecialchars($titlepic) . '"';
        else
            $titlepic = '';
        if (!empty($playurl))
            $playurl = ' value="' . htmlspecialchars($playurl) . '"';
        else
            $playurl = '';
        $appendcode.='<input name="titlepic" type="hidden" id="titlepic"' . $titlepic . ' /><input name="playurl" type="hidden" id="playurl"' . $playurl . ' /><input name="csummary" type="hidden" id="csummary" />'; //文章图片与媒体网址
        $appendcode.='<script type="text/javascript">jQuery(function($) {  if(typeof(CFillPageHiddenValue)!="undefined") CFillPageHiddenValue();/*填充数据*/  if(typeof(GreatePopShareLink)!="undefined") GreatePopShareLink("'.$etlistid.'","imageField");/*生成第三方弹出分享链接*/ });</script>'; //添加脚本
    }
}
else
{
    if($type=='login')
        {
            //构造第三方登录按钮列表  LGM 添加,2014年2月11日22:50
            include_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'memberconnect'.DIRECTORY_SEPARATOR.'eibase.php' );
            include_once(ECMS_PATH.'e'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'userfun.php');
            $vo=GetEIApps();
            if(!empty($vo))
            {
                    $DistrCode.='<span class="eilogintitle">一键登录</span><span class="disloginlist dll_' . $class . '" id="'.$etlistid.'">';
                    foreach ($vo as $k=>$v)
                    {
                        $eitype = $v['type'];
                        $DistrCode.="<a href='".$public_r['newsurl']."e/memberconnect/index.php?id=".$v['id']."&apptype={$eitype}' class='login_{$eitype}' title='".$v['name']."'>&nbsp;</a>";
                    }                    
                    $DistrCode.='</span><div class="eiloginfix"></div>';
            }
        }
}
$DistrCode.=$appendcode;//添加脚本
if(!empty($DistrCode))
{
    $DistrCodeR=str_replace('"','\\"',$DistrCode);
    echo 'document.write("'.$DistrCodeR.'");';
}