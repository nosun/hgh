<?php
if(!defined('InEmpireCMS'))
{
    exit();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
    <title>删除静态文件</title>
    <style>
        .main {padding:10px;margin:0 auto}
        .main h4 {margin:15px auto}
        .main .url { width: 40%; display:inline-block;height:40px}
        .main .form button {
            margin: 15px auto;
            padding:5px;
            background-color:#c0c0c0;
            height:40px;
            width:100px;
            font-size:14px;
        }
        .small {font-size:12px;color:red}
    </style>
</head>
<body>
<div class="main">
    <form action="" method="post" role="form" class="form">
    	<h4>请在下面的输入框中输入要删除的url</h4>
    	<input type="text" class="form-control url" name="url" value ="<?php echo $url ?>" placeholder="输入静态文件url">
    	<button type="submit" class="btn btn-primary">提交</button>
    </form>
    <div class="message">
        <?php if($url){ ?>
            <p>您要删除的url:
                <span class="small">
                    <a href="<?php echo $url ?>" target="_blank">
                        <?php echo $url ?>
                    </a>
                </span>
                <?php echo  $message ?>
            </p>
        <?php }?>
    </div>
</div>
</body>
</html>
