 /**
 * Created by Jing on 2015/7/26.
 */
jQuery(function() {
    var $articleContent = jQuery('.textcontent');
    var $showBTN = jQuery('.show_all');

    if ($articleContent.length = 1) {                                    //加载文章内容页时执行长文章显示控制函数
        ctrlShowText();
    }

    function ctrlShowText() {
        var text = text1 = text2 = '';
        var html = html1 ='';
        var showTextCount = 1000;
        var $element, $articleShow, $articleNotShow;
        html = $articleContent.html();
        $element = jQuery('.textcontent>*');
        //console.log($element[2].toString());
        if (html != undefined) {
            if (html.length > 2200) {
                for (var i = 0; i < $element.length; i++) {
                    if (text1.length < showTextCount) {
                        text1 += jQuery($element[i]).text();
                        html1 += $element[i].outerHTML;
                    } else {
                        text2 += jQuery($element[i]).text();
                    }
                    text += jQuery($element[i]).text();
                }
                $articleContent.empty();
                $articleContent.append("<div class='articleShow' style='display:block'></div>");
                $articleShow = jQuery('.articleShow');
                $articleShow.append(html1);
                var percent = Math.round(text2.length / text.length * 100);
                if (percent > 0) {
                    $showBTN.css('display', 'block').html('<i class="icon"></i>' + '\u67e5\u770b\u4f59\u4e0b' + percent + '%');
                }else {
                    $showBTN.css('display', 'none');
                }
            }
        }

        $showBTN.click(function () {       //函数内事件：点击查看剩余文章按钮
            $articleContent.empty();
            $articleContent.append(html);
            jQuery(this).css('display', 'none');
        })
    }
});


jQuery(function () {
    jQuery('.to_top').hide();
    footerBottom();                                                 //处理页脚
    docMinWidth();                                                  //文档最小宽度



    jQuery(window).scroll(function(){                                  //事件：显示/隐藏返回顶部按钮
        var bodyTop=jQuery('body').scrollTop();
        if(bodyTop>=300){
            jQuery('.to_top').fadeIn(500);
        }else if(bodyTop<300){
            jQuery('.to_top').fadeOut(500);
        }
    });

    jQuery(window).resize(function(){                                //事件：响应窗口尺寸变化。
        footerBottom();
        docMinWidth();
    });

    jQuery('.img').click(function(){                                    //事件：无图模式开关样式
        if(jQuery(this).hasClass('off')){
            jQuery(this).removeClass('off');
        }else{
            jQuery(this).addClass('off');
            jQuery('img').detach();
        }
    });

    jQuery('.search_input').keyup(function(){        //事件：搜索输入框。控制后方清除按钮显示
        if(jQuery(this).val() != ''){
            jQuery('.clear_btn').fadeIn();
        }else{
            jQuery('.clear_btn').fadeOut();
        }
    });

    jQuery('.clear_btn').click(function(){           //事件：搜索页清除值按钮
        jQuery('.search_input').val('');
        jQuery(this).fadeOut(300);
    });


    function docMinWidth () {                   //函数：文档最小宽度
        if (jQuery(window).width() <= 320) {
            jQuery('body').width('320px');
        } else{
            jQuery('body').width('100%');
        }
    }

    function footerBottom (){                   //函数：页脚置于窗口或页面底部
        var htmlH=jQuery('html').height(),
            footer=jQuery('footer'),
            footerH=footer.height(),
            winH=jQuery(window).height();
        var h=footer.hasClass('bottom')?htmlH+footerH+40:htmlH;
        if(h < winH){
            footer.addClass('bottom');
        }else{
            footer.removeClass('bottom');

        }
    }
});
 function GetQueryString(name)
 {
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
 }
 function jiazai() {
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
