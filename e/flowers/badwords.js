$(document).ready(function() {
	var arr_title = new Array();
	var arr_style = new Array();


	if(navigator.cookieEnabled == true){
		$.ajax({
			url:"badwords.xml",
			type:"GET", 
			dataType:"xml", 
			timeout:1000, 
			error:function(xml){ 
				$("#Message_info").text("Error loading XML document (加载XML文件出错)"+xml);
			},
			success:function(xml){
				var i=0;
				$(xml).find("word").each(function(){
					arr_title[i] = $(this).find("title").text(); 
					arr_style[i] = $(this).find("style").text(); 
					i++;
				});
			},
			complete:function(){
				$("div.gbooktext").each(function(){
					
					var str = $(this).html();
					var title;
					for (w=0;w<arr_title.length;w++) 
					{
						title = new RegExp("("+arr_title[w]+")","g");
						str 	= 	str.replace(title,"<span class=\""+arr_style[w]+"\">"+arr_title[w]+"</span>");			
					} 
					$(this).html(str);
					
				});
				
			}
		});
	}
	

});
