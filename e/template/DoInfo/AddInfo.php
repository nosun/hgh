<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$public_diyr['pagetitle']=$word;
$url="<a href='../../'>首页</a>&nbsp;>&nbsp;<a href='../member/cp/'>会员中心</a>&nbsp;>&nbsp;<a href='ListInfo.php?mid=".$mid."'>管理信息</a>&nbsp;>&nbsp;".$word."&nbsp;(".$mr[qmname].")";
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
    <script src="../data/html/setday.js"></script>
    <script>
        function bs(){
                var f=document.add
                if(f.title.value.length==0){alert("标题还没写");f.title.focus();return false;}
                if(f.classid.value==0){alert("请选择栏目");f.classid.focus();return false;}
        }
        function foreColor(){
        if(!Error())	return;
        var arr = showModalDialog("../data/html/selcolor.html", "", "dialogWidth:18.5em; dialogHeight:17.5em; status:0");
        if (arr != null) document.add.titlecolor.value=arr;
        else document.add.titlecolor.focus();
        }
        function FieldChangeColor(obj){
        if(!Error())	return;
        var arr = showModalDialog("../data/html/selcolor.html", "", "dialogWidth:18.5em; dialogHeight:17.5em; status:0");
        if (arr != null) obj.value=arr;
        else obj.focus();
        }
    </script>
    <script src="../data/html/postinfo.js"></script>
</head>
<body class="member_cp subarticle">
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
                <div class="main_message">
                    <strong><?=$word?></strong>

                </div>
                <div class="section_content">
                    <form name="add" method="POST" enctype="multipart/form-data" action="ecms.php" onsubmit="return EmpireCMSQInfoPostFun(document.add,'<?=$mid?>');">
                        
                        <input type=hidden value=<?=$enews?> name=enews />
                        <input type=hidden value=<?=$classid?> name=classid /> 
                        <input name=id type=hidden id="id" value=<?=$id?> />
                        <input type=hidden value="<?=$filepass?>" name=filepass /> 
                        <input name=mid type=hidden id="mid" value=<?=$mid?> />                         
                        
                        <div class="formitem">
                            <label class="inputtext">提交者</label>
                            <div class="input">
                                <?=$musername?>
                            </div>
                        </div>
                        <div class="formitem">
                            <label class="inputtext">栏目</label>
                            <div class="input">
                                <?=$postclass?>
                            </div>
                        </div>                       

                        <?php
                        @include($modfile);
                        ?>
                        <div class="formitem">
                            <div class="input">
                                <?=$showkey?>
                            </div>
                        </div>                        
                        <div class="formitem">
                            <div>
                                <input class="button buttoninput button2" type="submit" name="addnews" value="提交" />
                                <input class="button buttoninput button1" type="reset" name="Submit2" value="重置" />
                            </div>
                        </div>                         
                    </form>
                </div>
                
                <script type="text/javascript">
                    $(function(){
                      $('#title').OnFocus({ box: "#title" });
                      $('#author').OnFocus({ box: "#author" });
                      $('#copyfrom').OnFocus({ box: "#copyfrom" });
                      $('#fromlink').OnFocus({ box: "#fromlink" });
                    });
                </script>
            </div>
        </div>
        <div class="clear"></div>        
    </div>
    <?php
    require(ECMS_PATH.'e/template/incfile/footer.php');
    ?>