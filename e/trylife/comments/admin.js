function admin_reply(plid,restb,d)
{
	if(d==1)
	{
		$("#respond").replaceWith($("#saytext").val());
		$("#respond").remove();
		$.ajax({
			type: "post",
			url: "admin-reply-form.php",
			data:"enews=adminReplyComment&restb="+restb+"&plid="+plid,
			dataType: "data",
			success: function (data)
			{
				$("#reply-form-"+plid).html(data);								
			}
		})	
	}
	else
	{
		$("#reply-form-"+plid).html('');
	}
}

function admin_edit_comment(plid,restb,d)
{
	if(d==1)
	{
		$("#respond").replaceWith($("#saytext").val());
		$("#respond").remove();
		$.ajax({
			type: "post",
			url: "admin-reply-form.php",
			data:"enews=adminEditComment&plid="+plid+"&restb="+restb,
			dataType: "data",
			success: function (data)
			{
				$("#saytext-"+plid).html(data);								
			}
		})	
	}
	else
	{
		$("#respond").replaceWith($("#saytext").val());
	}
}

function admin_add_comment(plid)
{
	var postdata=$("#commentform").serialize();
	$.ajax({
		type: "post",
		url: "ecmspl.php",
		data:postdata,
		dataType: "json",
		success: function (json)
		{
			if(json.msgid==0)
			{
				if(json.enews=='adminReplyComment')
				{
					$("#reply-content-"+plid).html(json.code);
					$("#reply-form-"+plid).html('');					
				}
				else if(json.enews=='adminEditComment')
				{
					$("#saytext-"+plid).html(json.code);
				}
			}
			else
			{
				alert(json.msg);
			}
									
		}
	})	
}



function admin_func(plid,id,bclassid,classid,restb,method,value){
	if(method=="delete"){
		var r=confirm("确定删除？");
		if (r==true)
		  {
		  
		  }
		else
		  {
		  return false;
		  }
	}
	
	$.ajax({
		type: "post",
		url: "ecmspl.php",
		data:"enews=DoFuncPl_one&plid="+plid+"&id="+id+"&bclassid="+bclassid+"&classid="+classid+"&restb="+restb+"&method="+method+"&value="+value,
		dataType: "data",
		success: function (data)
		{
			$("#info-"+plid).html(data);	
			if(method=="isgood"){
				var msg	=	value	?	"[取消推荐]"	:	"[推荐]"	;
			}else if(method=="uncontent"){
				var msg	=	value	?	"[取消屏蔽]"	:	"[屏蔽]"	;
			}else if(method=="checked"){
				var msg	=	"";
			}

			if(method=="checked"){
				$("#checked-"+plid).html("");
				$("#checked-"+plid).remove();
			}else if(method=="delete"){
				$("#comment-"+plid).html("");
				$("#comment-"+plid).remove();
			}else{
				$("#"+method+"-"+plid).attr("onclick","");
				$("#"+method+"-"+plid).unbind();
				if(value){
					$("#"+method+"-"+plid).html(msg);
					$("#"+method+"-"+plid).bind("click",function(){admin_func(plid,id,bclassid,classid,restb,method,0);});
					
				}else{
					$("#"+method+"-"+plid).html(msg);
					$("#"+method+"-"+plid).bind("click",function(){admin_func(plid,id,bclassid,classid,restb,method,1);});
				}
			}
			
			
		}
	})	
}


