
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


//********************* tbhuabao ******************
myFocus.pattern.extend({
    'mF_tbhuabao':function(settings,$){
        var $focus=$(settings);
        var $picBox=$focus.find('.pic');
        var $picUl=$picBox.find('ul');
        $picUl[0].innerHTML+=$picUl[0].innerHTML;//无缝复制
        var $txtList=$focus.addListTxt().find('li');
        var $dotList=$focus.addList('dot').find('li');
        $dotList.each(function(){
            this.innerHTML='<a href="javascript:;">&bull;</a>'
            });//小点
        var $prevBtn=$focus.addHtml('<div class="prev"><a href="javascript:;">&#8249;</a></div>');
        var $nextBtn=$focus.addHtml('<div class="next"><a href="javascript:;">&#8250;</a></div>');
        //CSS
        var w=settings.width,h=settings.height,dotH=240,arrTop=h/2-32,n=$txtList.length;
        $focus[0].style.cssText='width:315px;height:293px;';
        $picBox[0].style.cssText='width:'+w+'px;height:'+h+'px;';
        $picUl[0].style.width=w*2*n+'px';
        $txtList.each(function(){
            this.style.top=dotH+'px'
            });
        $picUl.find('li').each(function(){
            this.style.cssText='width:'+w+'px;height:'+h+'px;'
            });//滑动固定其大�?
        $prevBtn[0].style.cssText=$nextBtn[0].style.cssText='top:'+arrTop+'px;';
        //PLAY
        $focus.play(function(i){
            var index=i>=n?(i-n):i;
            $txtList[index].style.display='none';
            $dotList[index].className = '';
        },function(i){
            var index=i>=n?(i-n):i;
            $picUl.slide({
                left:-w*i
                });
            $txtList[index].style.display='block';
            $dotList[index].className = 'current';
        },settings.seamless);
        //Control
        $focus.bindControl($dotList);
        //Prev & Next
        $prevBtn.bind('click',function(){
            $focus.run('-=1')
            });
        $nextBtn.bind('click',function(){
            $focus.run('+=1')
            });
    }
});
myFocus.config.extend({
    'mF_tbhuabao':{
        seamless:true
    }
});//支持无缝设置
//********************* tbhuabao ******************


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
        var s=1.70158;
        var p=0;
        var a=c;
        if (t==0) return b;
        if ((t/=d)==1) return b+c;
        if (!p) p=d*.3;
        if (a < Math.abs(c)) {
            a=c;
            var s=p/4;
        }
        else var s = p/(2*Math.PI) * Math.asin (c/a);
        return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
    },
    easeOutElastic: function (x, t, b, c, d) {
        var s=1.70158;
        var p=0;
        var a=c;
        if (t==0) return b;
        if ((t/=d)==1) return b+c;
        if (!p) p=d*.3;
        if (a < Math.abs(c)) {
            a=c;
            var s=p/4;
        }
        else var s = p/(2*Math.PI) * Math.asin (c/a);
        return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
    },
    easeInOutElastic: function (x, t, b, c, d) {
        var s=1.70158;
        var p=0;
        var a=c;
        if (t==0) return b;
        if ((t/=d/2)==2) return b+c;
        if (!p) p=d*(.3*1.5);
        if (a < Math.abs(c)) {
            a=c;
            var s=p/4;
        }
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
        function i(){
            var a=d("script:first"),b=a.css("color"),c=false;
            if(/^rgba/.test(b))c=true;else try{
                c=b!=a.css("color","rgba(0, 0, 0, 0.5)").css("color");
                a.css("color",b)
                }catch(e){}
                return c
            }
            function g(a,b,c){
            var e="rgb"+(d.support.rgba?"a":"")+"("+parseInt(a[0]+c*(b[0]-a[0]),10)+","+parseInt(a[1]+c*(b[1]-a[1]),10)+","+parseInt(a[2]+c*(b[2]-a[2]),10);
            if(d.support.rgba)e+=","+(a&&b?parseFloat(a[3]+c*(b[3]-a[3])):1);
            e+=")";
            return e
            }
            function f(a){
            var b,c;
            if(b=/#([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/.exec(a))c=
                [parseInt(b[1],16),parseInt(b[2],16),parseInt(b[3],16),1];
            else if(b=/#([0-9a-fA-F])([0-9a-fA-F])([0-9a-fA-F])/.exec(a))c=[parseInt(b[1],16)*17,parseInt(b[2],16)*17,parseInt(b[3],16)*17,1];
            else if(b=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(a))c=[parseInt(b[1]),parseInt(b[2]),parseInt(b[3]),1];
            else if(b=/rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9\.]*)\s*\)/.exec(a))c=[parseInt(b[1],10),parseInt(b[2],10),parseInt(b[3],10),parseFloat(b[4])];
            return c
            }
        d.extend(true,d,{
            support:{
                rgba:i()
                }
            });
    var h=["color","backgroundColor","borderBottomColor","borderLeftColor","borderRightColor","borderTopColor","outlineColor"];
    d.each(h,function(a,b){
        d.Tween.propHooks[b]={
            get:function(c){
                return d(c.elem).css(b)
                },
            set:function(c){
                if(!c.b){
                    c.e=f(d(c.elem).css(b));
                    c.d=f(c.end);
                    c.a=0;
                    c.b=true
                    }
                    c.elem.style[b]=g(c.e,c.d,c.a*c.init.interval/c.options.duration);
                c.a++
            }
        }
    });
d.Tween.propHooks.borderColor={
    set:function(a){
        if(!a.b)a.d=f(a.end);
        var b=h.slice(2,6);
        d.each(b,function(c,
            e){
            if(a.b)a.elem.style[e]=g(a.c[e],a.d,a.a*a.init.interval/a.options.duration);
            else{
                a.c=a.c||[];
                a.c[e]=f(d(a.elem).css(e));
                a.a=0
                }
            });
    a.b=true;
    a.a++
}
};
}
else
{
    function i(){
        var b=d("script:first"),a=b.css("color"),c=false;
        if(/^rgba/.test(a))c=true;else try{
            c=a!=b.css("color","rgba(0, 0, 0, 0.5)").css("color");
            b.css("color",a)
            }catch(e){}
            return c
        }
        function g(b,a,c){
        var e="rgb"+(d.support.rgba?"a":"")+"("+parseInt(b[0]+c*(a[0]-b[0]),10)+","+parseInt(b[1]+c*(a[1]-b[1]),10)+","+parseInt(b[2]+c*(a[2]-b[2]),10);
        if(d.support.rgba)e+=","+(b&&a?parseFloat(b[3]+c*(a[3]-b[3])):1);
        e+=")";
        return e
        }
        function f(b){
        var a,c;
        if(a=/#([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/.exec(b))c=
            [parseInt(a[1],16),parseInt(a[2],16),parseInt(a[3],16),1];
        else if(a=/#([0-9a-fA-F])([0-9a-fA-F])([0-9a-fA-F])/.exec(b))c=[parseInt(a[1],16)*17,parseInt(a[2],16)*17,parseInt(a[3],16)*17,1];
        else if(a=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(b))c=[parseInt(a[1]),parseInt(a[2]),parseInt(a[3]),1];
        else if(a=/rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9\.]*)\s*\)/.exec(b))c=[parseInt(a[1],10),parseInt(a[2],10),parseInt(a[3],10),parseFloat(a[4])];
        return c
        }
    d.extend(true,d,{
        support:{
            rgba:i()
            }
        });
var h=["color","backgroundColor","borderBottomColor","borderLeftColor","borderRightColor","borderTopColor","outlineColor"];
d.each(h,function(b,a){
    d.fx.step[a]=function(c){
        if(!c.init){
            c.a=f(d(c.elem).css(a));
            c.end=f(c.end);
            c.init=true
            }
            c.elem.style[a]=g(c.a,c.end,c.pos)
        }
    });
d.fx.step.borderColor=function(b){
    if(!b.init)b.end=f(b.end);
    var a=h.slice(2,6);
    d.each(a,function(c,e){
        b.init||(b[e]={
            a:f(d(b.elem).css(e))
            });
        b.elem.style[e]=g(b[e].a,b.end,b.pos)
        });
    b.init=true
    };
}

})(jQuery);
//********************* skitter ******************


//********************** custom Tab ************************
$(function(){ 
    
    //������壬���������ܡ�����������tab�л�
    var selecter;
    var hotturn=function(selecter){
        $(selecter+" #side_tab_button a").hover(
            function(){
                var index=$(this).index();       //ȡ�õ�ǰ�˵�a��ǩ������ֵ,�Թ���ʾtab��Ӧ���ݿ�ʹ��
                $(selecter+" #side_tab_button a").removeClass("onhover");  //ɾ������˵�a��ǩ��class
                $(this).addClass("onhover");                //��ǰ�������Ĳ˵�׷��class
                $(selecter+" #side_tab_content > ul:visible").hide();       //���ز˵����ݿ�tabox�����пɼ�Ԫ��
                $(selecter+" #side_tab_content > ul:eq("+index+")").show(); //��ʾtab�˵���Ӧ���ݿ�
            },function(){

            }
            );        
    }
    
    //���Ӧ��
    hotturn("#hotclick");   //��ҳ�ȵ�����
    hotturn("#hotcomment");     //��ҳ��������

});


//********************** custom Tab ************************