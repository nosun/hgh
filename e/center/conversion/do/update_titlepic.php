<?php

require("../lib/connect.php");
require("../lib/db_sql.php");
require("../lib/fun.php");

$link=db_connect();
$empire=new mysqlquery();
//--------------------- 更新信息表 ---------------------


        $sql=$empire->query("select * from hgh_ecms_article where id>26887 and titlepic>0 order by id");
        
	while($r=$empire->fetch($sql))
	{
 
            $pic_id        =   (int)$r['titlepic'];     

            $selectSQL  =   'SELECT filepath FROM file_key where aid='.$pic_id;
            var_dump($selectSQL);exit;
            $result  =  mysql_query($selectSQL);
            while($r_message=  mysql_fetch_assoc($result)){
                $filepath    =   '/d/file/p/'.$r_message['filepath'];
                $update =   'UPDATE hgh_ecms_article SET titlepic="'.(string)$filepath.'" where id='.(int)$r['id'];
                if (mysql_query($update)) {
                    echo '<p style="color:green;">本条信息标题图片更新成功！</p>';
                }  else {
                    echo '<p style="color:red;">标题图片更新失败！id为'.$r['id'].'</p>';
                    exit;
                }
                echo 'id为'.$r['id'];
            }
            unset($r_message);

	}

        echo "全部图片更新完毕！";
        exit();

         

?>