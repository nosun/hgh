<?php
// 创键空白图像并添加一些文本
$im = imagecreatetruecolor(120, 20);
$text_color = imagecolorallocate($im, 233, 14, 91);
imagestring($im, 1, 5, 5,  'A Simple Text String', $text_color);

// 设置内容类型标头 —— 这个例子里是 image/jpeg
header('Content-Type: image/jpeg');

// 输出图像
imagejpeg($im);

// 释放内存
imagedestroy($im);
?>
