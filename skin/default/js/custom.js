
//ajax 选项�?
$('#ajaxtab .tabbtn li a').click(function(){
	var thiscity = $(this).attr("href");
	$("#ajaxtab .loading").ajaxStart(function(){
		$(this).show();
	}); 
	$("#ajaxtab .loading").ajaxStop(function(){
		$(this).hide();
	}); 
	$('#ajaxtab .tabcon').load(thiscity);
	$('#ajaxtab .tabbtn li a').parents().removeClass("current");
	$(this).parents().addClass("current");
	return false;
});
$('#ajaxtab .tabbtn li a').eq(0).trigger("click");

//tab plugins 插件
$(function(){
	
	//选项卡鼠标滑过事�?
	$('#statetab .tabbtn li').mouseover(function(){
		TabSelect("#statetab .tabbtn li", "#statetab .tabcon", "current", $(this))
	});
	$('#statetab .tabbtn li').eq(0).trigger("mouseover");
	
	//选项卡鼠标滑过事�?
	$('#clicktab .tabbtn li').click(function(){
		TabSelect("#clicktab .tabbtn li", "#clicktab .tabcon", "current", $(this))
	});
	$('#clicktab .tabbtn li').eq(0).trigger("click");

	function TabSelect(tab,con,addClass,obj){
		var $_self = obj;
		var $_nav = $(tab);
		$_nav.removeClass(addClass),
		$_self.addClass(addClass);
		var $_index = $_nav.index($_self);
		var $_con = $(con);
		$_con.hide(),
		$_con.eq($_index).show();
	}
	
});

//********************* skitter ******************
//本插件有skitter提供支持 了解文档下载资源：http://www.skitter-slider.net
//jquery.easing.1.3.js
jQuery.easing['jswing'] = jQuery.easing['swing'];

jQuery.extend( jQuery.easing,
{
	def: 'easeOutQuad',
	swing: function (x, t, b, c, d) {
		//alert(jQuery.easing.default);
		return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
	},
	easeInQuad: function (x, t, b, c, d) {
		return c*(t/=d)*t + b;
	},
	easeOutQuad: function (x, t, b, c, d) {
		return -c *(t/=d)*(t-2) + b;
	},
	easeInOutQuad: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t + b;
		return -c/2 * ((--t)*(t-2) - 1) + b;
	},
	easeInCubic: function (x, t, b, c, d) {
		return c*(t/=d)*t*t + b;
	},
	easeOutCubic: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t + 1) + b;
	},
	easeInOutCubic: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t + b;
		return c/2*((t-=2)*t*t + 2) + b;
	},
	easeInQuart: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t + b;
	},
	easeOutQuart: function (x, t, b, c, d) {
		return -c * ((t=t/d-1)*t*t*t - 1) + b;
	},
	easeInOutQuart: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
		return -c/2 * ((t-=2)*t*t*t - 2) + b;
	},
	easeInQuint: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t*t + b;
	},
	easeOutQuint: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t*t*t + 1) + b;
	},
	easeInOutQuint: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
		return c/2*((t-=2)*t*t*t*t + 2) + b;
	},
	easeInSine: function (x, t, b, c, d) {
		return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
	},
	easeOutSine: function (x, t, b, c, d) {
		return c * Math.sin(t/d * (Math.PI/2)) + b;
	},
	easeInOutSine: function (x, t, b, c, d) {
		return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
	},
	easeInExpo: function (x, t, b, c, d) {
		return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
	},
	easeOutExpo: function (x, t, b, c, d) {
		return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
	},
	easeInOutExpo: function (x, t, b, c, d) {
		if (t==0) return b;
		if (t==d) return b+c;
		if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
		return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
	},
	easeInCirc: function (x, t, b, c, d) {
		return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
	},
	easeOutCirc: function (x, t, b, c, d) {
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	},
	easeInOutCirc: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
		return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
	},
	easeInElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;if ((t/=d)==1) return b+c;if (!p) p=d*.3;
		if (a < Math.abs(c)) {a=c;var s=p/4;}
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
	},
	easeOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;if ((t/=d)==1) return b+c;if (!p) p=d*.3;
		if (a < Math.abs(c)) {a=c;var s=p/4;}
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
	},
	easeInOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;if ((t/=d/2)==2) return b+c;if (!p) p=d*(.3*1.5);
		if (a < Math.abs(c)) {a=c;var s=p/4;}
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
		return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
	},
	easeInBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*(t/=d)*t*((s+1)*t - s) + b;
	},
	easeOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
	},
	easeInOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158; 
		if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
		return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
	},
	easeInBounce: function (x, t, b, c, d) {
		return c - jQuery.easing.easeOutBounce (x, d-t, 0, c, d) + b;
	},
	easeOutBounce: function (x, t, b, c, d) {
		if ((t/=d) < (1/2.75)) {
			return c*(7.5625*t*t) + b;
		} else if (t < (2/2.75)) {
			return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
		} else if (t < (2.5/2.75)) {
			return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
		} else {
			return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
		}
	},
	easeInOutBounce: function (x, t, b, c, d) {
		if (t < d/2) return jQuery.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
		return jQuery.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
	}
});

//jquery.animate-colors-min.js
(function(d){

	var jquery_version = $().jquery;
	jquery_version = parseFloat(jquery_version.substr(0,3));

	if (jquery_version >= 1.8)
	{
		function i(){var a=d("script:first"),b=a.css("color"),c=false;if(/^rgba/.test(b))c=true;else try{c=b!=a.css("color","rgba(0, 0, 0, 0.5)").css("color");a.css("color",b)}catch(e){}return c}function g(a,b,c){var e="rgb"+(d.support.rgba?"a":"")+"("+parseInt(a[0]+c*(b[0]-a[0]),10)+","+parseInt(a[1]+c*(b[1]-a[1]),10)+","+parseInt(a[2]+c*(b[2]-a[2]),10);if(d.support.rgba)e+=","+(a&&b?parseFloat(a[3]+c*(b[3]-a[3])):1);e+=")";return e}function f(a){var b,c;if(b=/#([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/.exec(a))c=
	[parseInt(b[1],16),parseInt(b[2],16),parseInt(b[3],16),1];else if(b=/#([0-9a-fA-F])([0-9a-fA-F])([0-9a-fA-F])/.exec(a))c=[parseInt(b[1],16)*17,parseInt(b[2],16)*17,parseInt(b[3],16)*17,1];else if(b=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(a))c=[parseInt(b[1]),parseInt(b[2]),parseInt(b[3]),1];else if(b=/rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9\.]*)\s*\)/.exec(a))c=[parseInt(b[1],10),parseInt(b[2],10),parseInt(b[3],10),parseFloat(b[4])];return c}
	d.extend(true,d,{support:{rgba:i()}});var h=["color","backgroundColor","borderBottomColor","borderLeftColor","borderRightColor","borderTopColor","outlineColor"];d.each(h,function(a,b){d.Tween.propHooks[b]={get:function(c){return d(c.elem).css(b)},set:function(c){if(!c.b){c.e=f(d(c.elem).css(b));c.d=f(c.end);c.a=0;c.b=true}c.elem.style[b]=g(c.e,c.d,c.a*c.init.interval/c.options.duration);c.a++}}});d.Tween.propHooks.borderColor={set:function(a){if(!a.b)a.d=f(a.end);var b=h.slice(2,6);d.each(b,function(c,
	e){if(a.b)a.elem.style[e]=g(a.c[e],a.d,a.a*a.init.interval/a.options.duration);else{a.c=a.c||[];a.c[e]=f(d(a.elem).css(e));a.a=0}});a.b=true;a.a++}};
	}
	else
	{
		function i(){var b=d("script:first"),a=b.css("color"),c=false;if(/^rgba/.test(a))c=true;else try{c=a!=b.css("color","rgba(0, 0, 0, 0.5)").css("color");b.css("color",a)}catch(e){}return c}function g(b,a,c){var e="rgb"+(d.support.rgba?"a":"")+"("+parseInt(b[0]+c*(a[0]-b[0]),10)+","+parseInt(b[1]+c*(a[1]-b[1]),10)+","+parseInt(b[2]+c*(a[2]-b[2]),10);if(d.support.rgba)e+=","+(b&&a?parseFloat(b[3]+c*(a[3]-b[3])):1);e+=")";return e}function f(b){var a,c;if(a=/#([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/.exec(b))c=
	[parseInt(a[1],16),parseInt(a[2],16),parseInt(a[3],16),1];else if(a=/#([0-9a-fA-F])([0-9a-fA-F])([0-9a-fA-F])/.exec(b))c=[parseInt(a[1],16)*17,parseInt(a[2],16)*17,parseInt(a[3],16)*17,1];else if(a=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(b))c=[parseInt(a[1]),parseInt(a[2]),parseInt(a[3]),1];else if(a=/rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9\.]*)\s*\)/.exec(b))c=[parseInt(a[1],10),parseInt(a[2],10),parseInt(a[3],10),parseFloat(a[4])];return c}
	d.extend(true,d,{support:{rgba:i()}});var h=["color","backgroundColor","borderBottomColor","borderLeftColor","borderRightColor","borderTopColor","outlineColor"];d.each(h,function(b,a){d.fx.step[a]=function(c){if(!c.init){c.a=f(d(c.elem).css(a));c.end=f(c.end);c.init=true}c.elem.style[a]=g(c.a,c.end,c.pos)}});d.fx.step.borderColor=function(b){if(!b.init)b.end=f(b.end);var a=h.slice(2,6);d.each(a,function(c,e){b.init||(b[e]={a:f(d(b.elem).css(e))});b.elem.style[e]=g(b[e].a,b.end,b.pos)});b.init=true};
	}

})(jQuery);
//********************* skitter ******************

//********************* onclick show ********************

$(function(){ 
    var showbutton,showbox;
    var TurnBg=function(bgbox,bgname){
        var bgbox,bgname;
        bgbox ? bgbox=bgbox : bgbox="#showmessage";
        bgname ? bgname=bgname : bgname="down";
        $(bgbox).css("background", "url(/skin/default/images/bg_"+bgname+"arrow.png) no-repeat scroll 0 12px transparent");
        if(bgname=="down")$(bgbox).text("展开");
        if(bgname=="up")$(bgbox).text("收起");
    };
    var ClickShow=function(showbutton,showbox){
        $(showbox).hide();
        TurnBg();
        $(showbutton).toggle(
            function () {
                $(showbox).stop(true,true).slideDown(600);
                TurnBg("","up");
            },
            function () {
                $(showbox).stop(true,true).slideUp(600);
                TurnBg();
            }
        );
    }

}); 

//********************* onclick show ********************


//********************** custom Tab ************************
$(function(){
    
    //
    var selecter;
    var hotturn=function(selecter){
        $(selecter+" #side_tab_button a").hover(
            function(){
                var index=$(this).index();       //ݿ
                $(selecter+" #side_tab_button a").removeClass("onhover");  //
                $(this).addClass("onhover");                //
                $(selecter+" #side_tab_content > ul:visible").hide();       //Ԫ��
                $(selecter+" #side_tab_content > ul:eq("+index+")").show(); //�
            },function(){

            }
        );        
    }
    
    //
    hotturn("#chaos");
    hotturn("#hotclick");   //�
    hotturn("#hotcomment");     //�
    hotturn("#turncomment");
});


//********************** custom Tab ************************

//********************* for article_content pl_list section **************************

$(function(){
    var selecter,controlClassid;
    var CurrentClass=function(selecter,controlClassid){
        var currentclassid=$(controlClassid).attr("classid"),
        selector=selecter+" li[classid='"+currentclassid+"']";
        $(selector).addClass("current");
    }
    CurrentClass("#js_currentclass","#js_controlclassid");
}); 


//********************* for article_content pl_list section **************************

//********************** AddSameHeight ************************

    function AddSameHeight(selectorA, selectorB) {
        var a = document.getElementById(selectorA);
        var b = document.getElementById(selectorB);
        if(a && b){
            var aHeight=a.scrollHeight;
            var bHeight=b.scrollHeight;
            if (aHeight < bHeight) {
                a.style.height = bHeight + "px";
            } else {
                b.style.height = aHeight + "px";
            }
                    
        }
        setTimeout(AddSameHeight(), 5000);    
    }
    
//********************** AddSameHeight ************************   

    
//********************** resize ************************ 
//本插件用于解决文章内容中包含的图片或视频尺寸超出主体宽度问题，
(function($) {
    $.fn.extend({
        Resize: function(options) {
            var defaults = {box: null};
            options = $.extend(defaults, options);
            return this.each(function() {
                var o = options;
                box_width=$(o.box).width();
                this_width=$(this).width();
                this_height=$(this).height();
                this_percent=this_height/this_width;
                if(this_width>box_width){
                    $(this).width(box_width).height(box_width*this_percent);
                }
            });
        }
    });
})(jQuery);
//********************** resize ************************ 

//********************** OnFocus ************************ 
//本插件用于解决文章内容中包含的图片或视频尺寸超出主体宽度问题，
(function($) {
    $.fn.extend({
        OnFocus: function(options) {
            var defaults = {box: null};
            options = $.extend(defaults, options);
            return this.each(function() {
                var o = options;
                $(this).focus(function(){
                    $(o.box).addClass("oninput");
                }).blur(function(){
                    $(o.box).removeClass("oninput");
                });
            });
        }
    });
})(jQuery);
//********************** OnFocus ************************ 

//********************** TabTurn ************************

(function($) {
    $.fn.extend({
        TabTurn: function(options) {
            var defaults = {box:null,con: null,onClass:"onhover"};
            options = $.extend(defaults, options);
            return this.each(function() {
                var o = options;
                $(this).hover(
                    function(){
                        var index=$(this).index();       
                        $(o.box+" ."+o.onClass).removeClass("onhover");  
                        $(this).addClass("onhover");                
                        $(o.con+":visible").hide();       
                        $(o.con+":eq("+index+")").show(); 
                    },function(){

                    }
                );  
            });
        }
    });
})(jQuery);

//********************** TabTurn ************************
//********************** 第三方社交平台应用 **************


if(typeof(document.setCookie) == 'undefined')
{    
document.getCookie = function(sName)
{
  // cookies are separated by semicolons
  var aCookie = document.cookie.split("; ");
  for (var i=0; i < aCookie.length; i++)
  {
    // a name/value pair (a crumb) is separated by an equal sign
    var aCrumb = aCookie[i].split("=");
    if (sName == aCrumb[0])
      return decodeURIComponent(aCrumb[1]);
  }

  // a cookie with the requested name does not exist
  return null;
};

document.setCookie = function(sName, sValue, sExpires,path)
{
  var sCookie = sName + "=" + encodeURIComponent(sValue);
  if(!!path)
  {
      sCookie += "; path=" + path;
  }
  if (sExpires != null)
  {
    sCookie += "; expires=" + sExpires;
  }

  document.cookie = sCookie;
};

document.removeCookie = function(sName,sValue,path)
{
    if(!!path) path = ';path='+path;
  document.cookie = sName + "="+sValue+path+"; expires=Fri, 31 Dec 1999 23:59:59 GMT;";
};
}


function hiteiv(sender,value,elehander)
{
    if(typeof value === 'undefined' || value === null){
        return false;
    }
     if(elehander)
    {
        if(elehander instanceof Object)
        {
           elehander =  $(elehander);
        }
        else
        {
            elehander =  $('#'+elehander);
        }        
    }   
    var em = $('em',sender);
    if(em){
        var EiMClosed = document.getCookie('EiMClosed');
        if(!EiMClosed) EiMClosed = '';
        else
          EiMClosed = EiMClosed.replace(/(^[\|,; ]+)|([\|,; ]+$)/g, "");
        var EiMClosedArr =new Array();
        if(EiMClosed.length>0)
            EiMClosedArr =EiMClosed.split(/[\|,; ]+/);
        if(em.hasClass('isselect'))
        {
            em.removeClass('isselect');
            em.addClass('disable');
            em.parent().removeClass('isselect');
            em.parent().addClass('disable');
            if(elehander)
            {
                var ev = elehander.val();
                elehander.val(ev.replace(value+"|",""));
            }
            var finded= false;
            for(var fi=0;fi<EiMClosedArr.length;fi++){
                if(value == EiMClosedArr[fi]){
                    finded= true;
                    break;
                }
            }
            if(finded==false)
            {
               EiMClosedArr.push(value);
            }            
            
        }
        else
        {
           em.removeClass('disable');
           em.addClass('isselect');
           em.parent().removeClass('disable');
           em.parent().addClass('isselect');
           if(elehander)
            {
                var ev = elehander.val();
                elehander.val(ev+value+"|");
            }
           var fi=0;
            for(;fi<EiMClosedArr.length;fi++){
                if(value == EiMClosedArr[fi]){
                    EiMClosedArr.splice(fi,1);
                    break;
                }
            }

        }
        if(isNaN(value) == true){
            GreatePopShareLink($(sender).parent()[0].id,"imageField");
        }
        
        if(EiMClosedArr.length>0)
        {
            var exdate=new Date();
            exdate.setDate(exdate.getDate()+5);//保存5天
            document.setCookie('EiMClosed',EiMClosedArr.join(","),exdate.toGMTString(),'/');
         }
         else
             document.removeCookie('EiMClosed','','/');
    }

}
/**
 * 填充页面的数据
 * @param {object} sender
 * @returns {bool}
 */
function CFillPageHiddenValue(sender)
{
    var summ = $("#plpost #csummary");
    var ptitle = $("#plpost #pagetitle");
    var turl = $("#plpost #titleurl");
    var tpic = $("#plpost #titlepic");
    var pyurl = $("#plpost #playurl");
    if (summ.length > 0 && summ.val().trim().length === 0) {
        var m = $("head meta[name='description']");
        if (m.length > 0)
        {
            summ.val(m.attr('content'));
        }
    }
    if (ptitle.length > 0 && ptitle.val().trim().length === 0) {
        kdocTitle = document.title;//标题 
        if (kdocTitle == null) {
            var t_titles = document.getElementByTagName("title");
            if (t_titles && t_titles.length > 0)
            {
                kdocTitle = t_titles[0];
            } else {
                kdocTitle = "";
            }
        }
        ptitle.val(kdocTitle);
    }
    if (turl.length > 0 && turl.val().trim().length === 0) {
        turl.val(window.location.href.toString());
    }
    var mytitle = document.title;
    var parentitle = null;
    var pwi = (window.opener?window.opener:window.parent);
    if(!!pwi){
        try{
           parentitle = pwi.document.title; 
        }
        catch(ex){;}
        
    }
    var pwin = null;
    if (!!parentitle && !!mytitle) {
        var epos = mytitle.indexOf('信息评论');
        if (epos < 0)
            mytitle = null;
        else
            mytitle = mytitle.substring(0, epos).trim();
        if (!!mytitle && parentitle.indexOf(mytitle) > -1 && pwi != window) {
            pwin = pwi.document;
        }
    }
    if (tpic.length > 0 && tpic.val().trim().length === 0) {
        var imgs = $("#main DIV.newstext img[src]");
        if (imgs.length == 0 && pwin!=null) {
                imgs = $("#main DIV.newstext img[src]", pwin);
        }
        if (imgs.length > 0) {
            var img1 = null;
            if (imgs.length > 0) {
                imgs.each(function(index, ele) {
                    if (ele.width > 100 && ele.height > 100)
                    {
                        img1 = ele.src;
                        return;
                    }
                });
            }
            if (img1) {
                tpic.val(img1);
            }
        }
    }
    if (pyurl.length > 0 && pyurl.val().trim().length === 0) {
        var embeds = $("#main DIV.newstext embed:visible");
        if (embeds.length == 0 && pwin!=null) {
                embeds = $("#main DIV.newstext embed:visible", pwin);
        }
        var vurl = null;
        if (embeds.length > 0) {
                embeds.each(function(index, ele) {
                    var epe = $(ele);
                    vurl = epe.attr('src');
                    var flashvars = epe.attr('flashvars');
                    if (flashvars == null) {
                        var fvs = $('param[name="FlashVars"]', ele);
                        if (fvs.length > 0) {
                            flashvars = epe.attr('value');
                        }
                    }
                    if (flashvars && flashvars.length > 2)
                    {
                        vurl += (vurl.indexOf('?') > -1 ? '&' : '?') + flashvars;
                    }
                    if (!!vurl)
                        return;
                });
            }
            if (vurl == null) {
                objects = $("#main DIV.newstext object:visible");
                if (objects.length == 0 && pwin) {
                        objects = $("#main DIV.newstext object:visible",pwin);
                }
                if (objects.length > 0) {
                    objects.each(function(index, ele) {
                        var epe = $('param[name="SRC"],param[name="FileName"],param[name="movie"]', ele);
                        if (epe.length > 0) {
                            var tvurl = epe.attr('value');
                            if (tvurl.trim().length > 0) {
                                var flashvars = epe.attr('flashvars');
                                if (flashvars == null) {
                                    var fvs = $('param[name="FlashVars"]', ele);
                                    if (fvs.length > 0) {
                                        flashvars = epe.attr('value');
                                    }
                                }
                                if (!!flashvars) {
                                    flashvars = flashvars.trim();
                                    tvurl += (tvurl.indexOf('?') > -1 ? '&' : '?') + flashvars;
                                }

                                vurl = tvurl;
                                return;
                            }
                        }
                    });
                }
            }
            if (vurl) {
                pyurl.val(vurl);
            }
        }
    }
    /**
     * 产生分享链接的元素
     * @param {string} selsboxid 包含选中项的容器ID
     * @param {string} submitbid 提交按钮的ID
     */
    function GreatePopShareLink(selsboxid,submitbid){
        var selsboxid2 = (!selsboxid ? 'SPAN.distrlist' : '#'+selsboxid);
        var linkid = (!selsboxid ? 'lk_distrlist' : 'lk_'+selsboxid);
        submitbid = (!submitbid ? 'INPUT[type="submit"]': '#'+submitbid);
        var oex = $(selsboxid2);
        var fmt = $(submitbid);
        var mylink = $('#'+linkid);
       if(oex.length>0 && fmt.length>0 ){
           var popk = $("A[class^='tag_'] em.isselect[data-n]",oex);
           if(popk.length>0){
               var types = '';
               popk.each(function(index,ele){
                   types += '_'+$(ele).data('n');
               });
              types='/e/memberconnect/EiFun.php?types='+ types.substring(1);              
              if(mylink.length>0){//已经有了:
                  mylink[0].href = types;
                  if(mylink.is(":visible")===false)
                      mylink.show();
              }
              else
              {
                  fmt.after('<a href="'+types+'" target="_blank" id="'+linkid+'" class="pl2imageField">'+fmt.val()+'</a>');                  
              }              
              fmt.hide();
              
           }
           else
           {
               if(mylink.length>0) 
                   mylink.hide();
               fmt.show();
           }
       }
    }