<?php
$ip = $_SERVER["REMOTE_ADDR"];
file_put_contents('ip.txt',$ip);

