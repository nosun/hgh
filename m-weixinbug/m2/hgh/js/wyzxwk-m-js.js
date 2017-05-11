 /**
 * Created by Jing on 2015/7/26.
 */
 function GetQueryString(name)
 {
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
 }
 function hahh() {
     var xmlhttp;
     if (window.XMLHttpRequest)
     {// code for IE7+, Firefox, Chrome, Opera, Safari
         xmlhttp=new XMLHttpRequest();
     }
     else
     {// code for IE6, IE5
         xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
     }
     xmlhttp.onreadystatechange=function()
     {
         if (xmlhttp.readyState==4 && xmlhttp.status==200)
         {
             alert(xmlhttp.responseText);
//                    document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
         }
     }
     xmlhttp.open("GET","aa.php",true);
     xmlhttp.send();
 }
 function jiazai() {
//            document.cookie="page="+0;return;
     var xmlhttp;
     var page;
     var classid = GetQueryString("classid");
     if (document.cookie.length>0)
     {
         c_start=document.cookie.indexOf("page" + "=")
         if (c_start!=-1)
         {
             c_start=c_start + 5;
             c_end=document.cookie.indexOf(";",c_start);
             if (c_end==-1) c_end=document.cookie.length;
             page = unescape(document.cookie.substring(c_start,c_end));
             var num = Number(page)+1;
             document.cookie="page="+num;
         }else {
             document.cookie="page="+1;
         }
     }
     if (window.XMLHttpRequest)
     {// code for IE7+, Firefox, Chrome, Opera, Safari
         xmlhttp=new XMLHttpRequest();
     }
     else
     {// code for IE6, IE5
         xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
     }
     xmlhttp.onreadystatechange=function()
     {
         if (xmlhttp.readyState==4 && xmlhttp.status==200)
         {
//                    alert(xmlhttp.readyState);
             document.getElementById("content").innerHTML=document.getElementById("content").innerHTML+xmlhttp.responseText;
         }
     }
     xmlhttp.open("GET","list.php?load=1&classid="+classid,true);
     xmlhttp.send();
 }
$(function() {
    var $articleContent = $('.article_content');
    var $showBTN = $('.show_all');

    if ($articleContent.length = 1) {                                    //加载文章内容页时执行长文章显示控制函数
        ctrlShowText();
    }

    function ctrlShowText() {
        var text = text1 = text2 = '';
        var html = html1 ='';
        var showTextCount = 1000;
        var $element, $articleShow, $articleNotShow;
        html = $articleContent.html();
        $element = $('.article_content>*');
        //console.log($element[2].toString());
        if (html != undefined) {
            if (html.length > 2200) {
                for (var i = 0; i < $element.length; i++) {
                    if (text1.length < showTextCount) {
                        text1 += $($element[i]).text();
                        html1 += $element[i].outerHTML;
                    } else {
                        text2 += $($element[i]).text();
                    }
                    text += $($element[i]).text();
                }
                $articleContent.empty();
                $articleContent.append("<div class='articleShow' style='display:block'></div>");
                $articleShow = $('.articleShow');
                $articleShow.append(html1);
                var percent = Math.round(text2.length / text.length * 100);
                if (percent > 0) {
                    $showBTN.css('display', 'block').html('<i class="icon"></i>' + '\u4f59\u4e0b' + percent + '%');
                }else {
                    $showBTN.css('display', 'none');
                }
            }
        }

        $showBTN.click(function () {       //函数内事件：点击查看剩余文章按钮
            $articleContent.empty();
            $articleContent.append(html);
            $(this).css('display', 'none');
        })
    }
});


$(function () {
    $('.to_top').hide();
    footerBottom();                                                 //处理页脚
    docMinWidth();                                                  //文档最小宽度



    $(window).scroll(function(){                                  //事件：显示/隐藏返回顶部按钮
        var bodyTop=$('body').scrollTop();
        if(bodyTop>=300){
            $('.to_top').fadeIn(500);
        }else if(bodyTop<300){
            $('.to_top').fadeOut(500);
        }
    });

    $(window).resize(function(){                                //事件：响应窗口尺寸变化。
        footerBottom();
        docMinWidth();
    });

    $('.img').click(function(){                                    //事件：无图模式开关样式
        if($(this).hasClass('off')){
            $(this).removeClass('off');
        }else{
            $(this).addClass('off');
            $('img').detach();
        }
    });

    $('.search_input').keyup(function(){        //事件：搜索输入框。控制后方清除按钮显示
        if($(this).val() != ''){
            $('.clear_btn').fadeIn();
        }else{
            $('.clear_btn').fadeOut();
        }
    });

    $('.clear_btn').click(function(){           //事件：搜索页清除值按钮
        $('.search_input').val('');
        $(this).fadeOut(300);
    });


    function docMinWidth () {                   //函数：文档最小宽度
        if ($(window).width() <= 320) {
            $('body').width('320px');
        } else{
            $('body').width('100%');
        }
    }

    function footerBottom (){                   //函数：页脚置于窗口或页面底部
        var htmlH=$('html').height(),
            footer=$('footer'),
            footerH=footer.height(),
            winH=$(window).height();
        var h=footer.hasClass('bottom')?htmlH+footerH+40:htmlH;
        if(h < winH){
            footer.addClass('bottom');
        }else{
            footer.removeClass('bottom');

        }
    }
});
