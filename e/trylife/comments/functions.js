//lastupdate 20120103
$(document).ready(function(){
  var saytext=$("#saytext");
  var comments_face=$("#comments_face");
  get_comments(0,0);
  $("#comment_parent").attr("value",0);
  saytext.attr("value",'');
  
  //字数计算 表情计算
  saytext.keyup(function(){
			comment_count_char();
			comment_count_face();
	  });
  comments_face.click (function(){
			comment_count_char();
			comment_count_face();
	  });	  
	  
});

function comment_count_face()
{
	var n=comment_max_face-$("#saytext").val().split("[~e.").length+1;
	if(n<0)
	{
		n='<font color="red">'+n+'</font>';
		$("#comments_submit").attr({"value":"无法提交","disabled":"disabled"});
	}
	else
	{
		$("#comments_submit").attr({"value":"提交"});
	}
	$("#comment_face_num").html(n);
}

function comment_count_char()
{
	var n=comment_max_char-strlen($("#saytext").val());
	if(n<0)
	{
		n='<font color="red">'+n+'</font>';
		$("#comments_submit").attr({"value":"无法提交","disabled":"disabled"});
	}
	else
	{
		$("#comments_submit").attr({"value":"提交"});
	}
	$("#comment_char_num").html(n);	
}

function strlen(str) {  //在IE8 兼容性模式下 不会报错  
	var s = 0;  
	for(var i = 0; i < str.length; i++) {  
		if(str.charAt(i).match(/[\u0391-\uFFE5]/)) {  
			s += 2;     
		} else {  
			s++;  
		}  
	}  
	return s;  
}


function refresh_keypic()
{
	var url=$("#keypic").attr("src");
	$("#keypic").attr("src",chgUrl(url));
}
//时间戳  
//为了使每次生成图片不一致，即不让浏览器读缓存，所以需要加上时间戳  
function chgUrl(url){  
    var timestamp = (new Date()).valueOf();  
    url = url.substring(0,17);  
    if((url.indexOf("&")>=0)){  
        url = url + "×tamp=" + timestamp;  
    }else{  
        url = url + "?timestamp=" + timestamp;  
    }  
    return url;  
}  
  
function isRightCode(){  
    var code = $("#veryCode").attr("value");  
    code = "c=" + code;  
    $.ajax({  
        type:"POST",  
        url:"resultServlet",  
        data:code,  
        success:callback  
    });  
}  
  
function callback(data){  
    $("#info").html(data);  
}  

//2011-12-29 JQUERY跨域使用新函数
/*
function get_comments(page,enews)
{	
	if(enews==1)
	{
		addComment.removeForm("respond");
	}
	$("#ecms_comments").html('<img src="'+newsurl+'e/trylife/common/images/loader.gif">');	
	$.ajax({
		type: "get",
		url: newsurl + "e/trylife/comments/enews.php",
		data:"enews=get_comments&classid="+classid+"&id="+id+"&page="+page+"&t="+Math.random(),
		dataType: "data",
		success: function (data)
		{
			//addComment.removeForm();
			$("#ecms_comments").html(data);	
			
			//手动翻页的情况下进行滑动，自动加载的情况下不滑动
			if(enews==1)
			{
				huadong.to("#thecomments");
			}				
		}
	})
}
*/

//trylife 2012-01-03 跨域输出
function get_comments(page,enews)
{	
	if(enews==1)
	{
		addComment.removeForm("respond");
	}
	
	$("#ecms_comments").html('<img src="'+newsurl+'e/trylife/common/images/loader.gif">');
	
	$.ajax({
		type: "get",
		url: newsurl + "e/trylife/comments/enews.php",
		data: "enews=get_comments&classid="+classid+"&id="+id+"&page="+page+"&format=json&jsoncallback=?",
		dataType:'json',
		success: function (json) 
		{
				//addComment.removeForm();
				$("#ecms_comments").html(json.content);
				
				//手动翻页的情况下进行滑动，自动加载的情况下不滑动
				if(enews==1)
				{
					huadong.to("#thecomments");
				}	
		}
	});	
	
}

function refresh_pltemp_option()
{
	$.ajax({
		type: "post",
		url: newsurl + "e/trylife/comments/enews.php",
		data:"enews=refresh_pltemp_option",
		dataType: "data",
		success: function (data)
		{
			$("#comments_default_template").html(data);								
		}
	})
}


function get_comments_form()
{
	$.ajax({
		type: "post",
		url: newsurl + "e/trylife/comments/comment_form.php",
		data:"enews=update_comments_set",
		dataType: "data",
		success: function (data)
		{
			$("#ecms_comments_form").html(data);					
		}
	})
}

function add_comment()
{
		var data=$("#commentform").serialize();//trylife 2011-09-01
		
		$("#comments_submit_msg").html('<img src="'+newsurl+'e/trylife/common/images/loading.gif">');
		
		$.ajax({
			type: "get",
			url: newsurl + "e/trylife/comments/enews.php",
			data:data+"&enews=ajax_AddPl&format=json&jsoncallback=?",
			dataType: "json",
			success: function (json)
			{
				$("#comments_submit_msg").html("");
				if(json.msgid==0)
				{
					if(json.parent==0)
					{
						$("#thecomments").append(json.code);
					}
					else 
					{
						//子评论在主评论下 所有自评论下插入新评论
						$("#children_"+json.parent).append(json.code);
					}
					$("#comment-"+json.plid).append('<span id="success_1"><img src="'+newsurl+'e/trylife/common/images/yes.png" style="vertical-align: middle;" alt="">'+json.msg+'</span>');
					addComment.removeForm("respond");
					huadong.to("#comment-"+json.plid);
				}
				else
				{
					$("#comments_submit_msg").html(json.msg);
				}
				//end success				
			}
		})
}

var huadong = {
       to:function(idd){
             $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
             $body.animate({
			scrollTop: $(idd).offset().top - 200
			}, 800);
       }
};

addComment = {
	//d commentID 评论ID
	//f parentId 父评论ID
	//i respondId 
	//c classid
	//  infoid
	moveForm: function( commentID, parentId, respondId, classid , infoid) {
		var m = this,
		a, h = m.I(commentID),
		b = m.I(respondId),
		cancel = m.I("cancel-comment-reply-link"),
		parent = m.I("comment_parent"),
		input_classid = m.I("comment_classid");
		input_id = m.I("comment_id");
		
		if (!h || !b || !cancel || !parent) 
		{
			return
		}
		m.respondId = respondId;
		classid = classid || false;
		id = id || false;
		
		if (!m.I("wp-temp-form-div")) {
			a = document.createElement("div");
			a.id = "wp-temp-form-div";
			a.style.display = "none";
			b.parentNode.insertBefore(a, b)
		}
		h.parentNode.insertBefore(b, h.nextSibling);
		if (input_classid && classid) {
			input_classid.value = classid
		}
		parent.value = parentId;
		cancel.style.display = "";
		cancel.onclick = function() {
			var n = addComment,
			e = n.I("wp-temp-form-div"),
			o = n.I(n.respondId);
			if (!e || !o) {
				return
			}
			n.I("comment_parent").value = "0";
			e.parentNode.insertBefore(o, e);
			e.parentNode.removeChild(e);
			this.style.display = "none";
			this.onclick = null;
			return false
		};
		try {
			m.I("saytext").focus()
		} catch(g) {}
		return false
	},
	I: function(a) {
		return document.getElementById(a)
	},
	removeForm: function(respondId) {
			var  m = this,
			n = addComment,
			cancel = m.I("cancel-comment-reply-link"),
			e = n.I("wp-temp-form-div"),
			o = n.I(n.respondId);
			
			if (!e || !o) {
				return
			}
			n.I("comment_parent").value = "0";
			cancel.style.display = "none";
			cancel.onclick = null;
			e.parentNode.insertBefore(o, e);
			e.parentNode.removeChild(e);			
			//return false
		}
};