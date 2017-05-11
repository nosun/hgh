/**
 * Created by Jing on 2015/7/26.
 */
$(function() {
    var $articleContent = $('.article_content');
    var $showBTN = $('.show_all');

    if ($articleContent.length = 1) {     //加载文章内容页时执行长文章显示控制函数
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

        function myTrim(strObj, strParObj) {
            var str;
            $(strObj, strParObj).each(function () {
                str = $(this).text();
                str = str.replace(/\u3000/g, '');       //全角空格
                $(this).html(str);
            });
        }

        $showBTN.click(function () {       //函数内事件：点击查看剩余文章按钮
            $articleContent.empty();
            $articleContent.append(html);
            $(this).css('display', 'none');
            $("#j-content img").zoomMedia();
            myTrim('h2', '#j-content');
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


    function docMinWidth () {     //函数：文档最小宽度
        if ($(window).width() <= 320) {
            $('body').width('320px');
        } else {
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

/********************************** 追加js **********************************/

// 隐藏浏览器地址栏   红星  2015-8-2 22:33:40
/*! Normalized address bar hiding for iOS & Android (c) @scottjehl MIT License */
(function( win ){
    var doc = win.document;

    // If there's a hash, or addEventListener is undefined, stop here
    if(!win.navigator.standalone && !location.hash && win.addEventListener ){

        //scroll to 1
        win.scrollTo( 0, 1 );
        var scrollTop = 1,
            getScrollTop = function(){
                return win.pageYOffset || doc.compatMode === "CSS1Compat" && doc.documentElement.scrollTop || doc.body.scrollTop || 0;
            },

            //reset to 0 on bodyready, if needed
            bodycheck = setInterval(function(){
                if( doc.body ){
                    clearInterval( bodycheck );
                    scrollTop = getScrollTop();
                    win.scrollTo( 0, scrollTop === 1 ? 0 : 1 );
                }
            }, 15 );

        win.addEventListener( "load", function(){
            setTimeout(function(){
                //at load, if user hasn't scrolled more than 20 or so...
                if( getScrollTop() < 20 ){
                    //reset to hide addr bar at onload
                    win.scrollTo( 0, scrollTop === 1 ? 0 : 1 );
                }
            }, 0);
        }, false );
    }
})( this );



/**********************************
 * Author:  红星                  *
 * Function： ajax加载更多插件    *
 * For: 网刊手机版                *
 * CreateTime： 2015-8-4 21:49:30 *
 **********************************/


(function($) {
    /**
     * Function： 数据接口参数
     *
     * @parameter act string 操作对象
     * @parameter id int 操作类型
     * @parameter notIn string 排除id集
     * @parameter max int 最小文章id
     *
     * @return data string 接口参数字符串
     */
    var query = function(act,id,notIn,max){
        var data,timestamp;
        timestamp = new Date().getTime();
        data = "act="+act+"&id="+id+"&notin="+notIn+"&max="+max+"&timestamp="+timestamp;
        return data;
    };

    /**
     * Function： 数据接口
     *
     * @parameter postdata string POST参数数据
     *
     * @return jsonData 返回结果集JSON数组
     */
    var getData = function(postdata){
        var jsonData;
        $.ajax({
            async: false,       //同步请求
            type: "POST",
            url: "http://m2.szhgh.com/action.php",
            data: postdata,
            success: function(data){
                alert(222);
                // jsonData = (new Function("", "return " + data))();
                jsonData = (new Function("return " + data))();
                alert(333);
            }
        });
        return jsonData;
    };


    /**
     * Function： 绘制列表
     *
     * @parameter box object 列表容器
     * @parameter arr array 取得的文章列表数据集合
     *
     * @return minid int 本页最小文章id（最小id作为下一次加载的上限）
     */

    var drawMoreList = function(box,arr,imgLock){
        var collection = [],
            max,
            leng;
        leng = arr.length;
        for(i=0;i<leng;i++){
            var $section,tempdata,titlepic;
            tempdata = arr[i];
            collection[i] = tempdata['id'];
            titlepic = tempdata['titlepic'];
            imgAbstract = tempdata['titlepic'] + '|||' + tempdata['ftitle'];
            if(tempdata['id']){
                if(imgLock){
                    $section = $(
                        "<section class='article_list'>" +
                        "<a href='"+ tempdata['titleurl'] +"' title='"+ tempdata['title'] +"'>" +
                        "<div data='" + imgAbstract + "'>" +
                        "<h2>" + tempdata['title'] + "</h2>" +
                        "<p>"+ tempdata['ftitle'] +"</p>" +
                        "<span>" + tempdata['newstime'] + "</span>" + "<span>作者：" + tempdata['author'] + "</span>" +
                        "</div>" +
                        "</a>" +
                        "</section>"
                    );
                } else {
                    if(titlepic){
                        $section = $(
                            "<section class='article_list'>" +
                            "<a href='"+ tempdata['titleurl'] +"' title='"+ tempdata['title'] +"'>" +
                            "<img src='"+ tempdata['titlepic'] +"' title='' />" +
                            "<div data='" + imgAbstract + "'>" +
                            "<h2>" + tempdata['title'] + "</h2>" +
                            "<span>" + tempdata['newstime'] + "</span>" + "<span>作者：" + tempdata['author'] + "</span>" +
                            "</div>" +
                            "</a>" +
                            "</section>"
                        );
                    } else {
                        $section = $(
                            "<section class='article_list'>" +
                            "<a href='"+ tempdata['titleurl'] +"' title='"+ tempdata['title'] +"'>" +
                            "<div data='" + imgAbstract + "'>" +
                            "<h2>" + tempdata['title'] + "</h2>" +
                            "<p>"+ tempdata['ftitle'] +"</p>" +
                            "<span>" + tempdata['newstime'] + "</span>" + "<span>作者：" + tempdata['author'] + "</span>" +
                            "</div>" +
                            "</a>" +
                            "</section>"
                        );
                    }
                }
            }

            $(box).append($section);
        }

        collection.sort(function(a,b){
            return a-b;
        });
        max = collection[0];
        return max;
    };

    $.fn.extend({
        /**
         * Function： 加载更多插件主函数
         *
         * @parameter options object 插件参数选项对象上一页最小文章id
         *      box         上一页最小文章id
         *      id          列表容器
         *      notIn       排除id集
         *
         * @return none
         */
        getMore: function (options) {
            var defaults = {box:null,act:null,id: null,notIn:null,imgLock:null};
            options = $.extend(defaults, options);
            return this.each(function() {
                var o = options;

                $(this).click( function () {
                    var max,
                        remax,
                        arr = [],
                        parterner = '',
                        leng,
                        lock;
                    alert(111);
                    lock = Number($.cookie('wyzxwkMobileImgLock'));
                    max = $(this).attr("data");
                    //console.log(max);
                    parterner = query(o.act, o.id, o.notIn, max);
                    //console.log(parterner);
                    arr = getData(parterner);
                    console.log(arr);
                    remax = drawMoreList(o.box, arr, lock);

                    $(this).attr("data", remax);
                    leng = arr.length;
                    if(leng < 20){
                        $(this).off().html("没有更多文章").fadeOut(2000);
                    }
                });

            });
        }
    });
})(jQuery);

/**
 * Function： 点赞
 *
 * @parameter url string 请求链接
 * @parameter functionName function 回调函数
 * @parameter httpType string 数据传输类型（GET或POST）
 * @parameter sendData string 要发送的数据
 * @return none
 */


$(function () {
    $.fn.extend({
        Request: function (url, httpType) {
            return this.each(function() {
                $(this).click( function () {
                    $.ajax({
                        type: httpType,
                        url : url,
                        dataType : 'jsonp',
                        jsonp:"jsoncallback",
                        success  : function(data) {
                            alert("结果："+ data);
                        },
                        error : function() {
                            alert('fail');
                        }
                    });
                });

            });
        }
    });
});

var http_request = false;
function makeRequest(url, functionName, httpType, sendData) {

    http_request = false;
    if (!httpType) httpType = "GET";

    if (window.XMLHttpRequest) {    // Non-IE...
        http_request = new XMLHttpRequest();
        if (http_request.overrideMimeType) {
            http_request.overrideMimeType('text/plain');
        }
    } else if (window.ActiveXObject) {      // IE
        try {
            http_request = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                http_request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}
        }
    }

    if (!http_request) {
        alert('Cannot send an XMLHTTP request');
        return false;
    }

    var changefunc = "http_request.onreadystatechange = " + functionName;
    eval (changefunc);
    //http_request.onreadystatechange = alertContents;
    http_request.open(httpType, url, true);
    http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    http_request.send(sendData);
}

function getReturnedText () {
    if (http_request.readyState == 4) {
        if (http_request.status == 200) {
            var messagereturn = http_request.responseText;
            return messagereturn;
        } else {
            alert('There was a problem with the request.');
        }
    }
}

function EchoReturnedText () {
    if (http_request.readyState == 4) {
        if (http_request.status == 200) {
            var messagereturn = http_request.responseText;
            if(messagereturn!='isfail'){
                var r;
                r=messagereturn.split('|');
                if(r.length!=1){
                    if(r[0]!=''){
                        document.getElementById(r[1]).innerHTML = r[0];
                    }
                    if(r[2]!=''){
                        alert(r[2]);
                    }
                } else {
                    document.getElementById('ajaxarea').innerHTML = messagereturn;
                }
            }
        } else {
            alert('There was a problem with the request.');
        }
    }
}


/**
 * Function： 文章内容图片尺寸缩放
 *
 * @parameter url string 请求链接
 * @parameter functionName function 回调函数
 * @parameter httpType string 数据传输类型（GET或POST）
 * @parameter sendData string 要发送的数据
 * @return none
 */

$(function () {
    $.fn.extend({
        zoomMedia: function () {
            return this.each(function() {
                var url,screemWidth,width,trueWidth,trueHeight,height,toWidth,toHeight,ratios;

                url = $(this).attr("src");
                width  = $(this).width();
                height  = $(this).height();

                if(width && height){
                    ratios = height/width;
                }
                screemWidth = $(window).width();
                toWidth = screemWidth - 60;

                //console.log(width+' and '+height);

                if(width > screemWidth){
                    $(this).width(toWidth);
                    if(ratios){
                        toHeight = toWidth * ratios;
                        $(this).height(toHeight);
                    } else {
                        $(this).removeAttr("height");
                    }
                }
            });
        }
    });
});


/**
 * Function： 清除样式和HTML代码
 *
 * @parameter strObj object 目标对象
 * @parameter strParObj object 目标对象父容器
 * @return none
 */

$(function () {
    function myTrim(strObj, strParObj) {
        var str;
        $(strObj, strParObj).each(function () {
            str = $(this).text();
            str = str.replace(/\u3000/g, '');       //全角空格
            $(this).html(str);
        });
    }
    myTrim('h2', '#j-content');
});