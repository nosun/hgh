<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>test</title>
    <meta charset="utf-8">
    <link href="http://www.szhgh.com/skin/default/css/topic_common.css" rel="stylesheet" type="text/css" />

    <script src="http://www.szhgh.com/skin/default/js/jquery-1.8.2.min.js" type="text/javascript"></script>
    <script src="http://www.szhgh.com/e/data/js/ajax.js"></script>
    <script src="http://www.szhgh.com/skin/default/js/custom.js" type="text/javascript"></script>
</head>
<body>
<div class="digg_back">
    <div class="left digg top">
        <div class="num" id="agree">
            <strong><script type="text/javascript" src="http://www.szhgh.com/e/public/ViewClick/?classid=104&id=71653&down=5"></script></strong>
        </div>
        <div class="link">
            <a id="agree_btn" href="#">同意</a>
        </div>
    </div>

        <style>

        #rate_box {
            float: left;
            height: 15px;
            margin-left: 32px;
            margin-top: 29px;
            width: 200px;
        }
        .rate{
            float: left;
            height: 15px
        }

        .digg_back{
            padding:25px 140px;
        }

        #agree_rate{width:7%;background:#CD362B;}
        #disagree_rate{width:93%;background:#6BB8D6;}

    </style>


    <div id="rate_box">
        <div class="rate" id="agree_rate"></div>
        <div class="rate" id="disagree_rate"></div>
    </div>

    <div class="right digg back">
        <div class="num" id="disagree">
            <strong><script type="text/javascript" src="http://www.szhgh.com/e/public/ViewClick/?classid=104&id=71653&down=6"></script></strong>
        </div>
        <div class="link">
            <a id="disagree_btn" href="#">不同意</a>
        </div>
    </div>
    <div class="clear"></div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#agree_btn").click(function(){

            $.get("http://www.szhgh.com/e/public/digg/index2.php?classid=104&id=71653&dotop=1&doajax=1",function(data,status){
                //alert("Data: " + data + "nStatus: " + status);
                $.ajax(
                    {
                        url:"http://www.szhgh.com/e/public/ViewClick/dig.php?id=71653",
                        type:"get",
                        async:true,
                        dataType:"text",
                        success: function(data){
                            var arr=data.split('-');
                            var    up       =parseInt(arr[0]);
                            var    down     =parseInt(arr[1]);
                            var    rate_up  =percentNum(up,down);
                            var    rate_down=percentNum(down,up);
                            $("#agree_rate").css("width",rate_up).html(up);
                            $("#disagree_rate").css("width",rate_down).html(down);

                        }
                    });
            });

        });

        $("#disagree_btn").click(function(){
            $.get("http://www.szhgh.com/e/public/digg/index2.php?classid=104&id=71653&dotop=0&doajax=1",function(data,status){
                //alert("Data: " + data + "nStatus: " + status);
                $.ajax(
                    {
                        url:"http://www.szhgh.com/e/public/ViewClick/dig.php?id=71653",
                        type:"get",
                        async:true,
                        dataType:"text",
                        success: function(data){
                            var arr=data.split('-');
                            var    up       =parseInt(arr[0]);
                            var    down     =parseInt(arr[1]);
                            var    rate_up  =percentNum(up,down);
                            var    rate_down=percentNum(down,up);
                            $("#agree_rate").css("width",rate_up).html(up);
                            $("#disagree_rate").css("width",rate_down).html(down);

                        }
                    });
            });

        });

        function percentNum(num, num2) {
            return (Math.round(num / (num2+num) * 10000) / 100.00 + "%");
        }
    });
</script>
</body>
</html>
