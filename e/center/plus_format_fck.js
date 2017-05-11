/*******************************************************************************
* KindEditor 3.2 增强版 for DedeCMS V5.3.1 / V5.5 
* 
* 整合：小迷糊
* 演示地址：http://hezu.lanrentuku.com/kindeditor-demo/  

* 本程序原始代码由 网络编辑之家(www.eastit.cn)提供 
*
* 尊重作者劳动，转载请保留以上信息
*******************************************************************************/


//设置编辑器中内容
function SetContents(codeStr){
   var oEditor = FCKeditorAPI.GetInstance(content) ;
   oEditor.SetHTML(codeStr) ;
}
 
// 获取编辑器中HTML内容
function getEditorHTMLContents(EditorName) { 
    var oEditor = FCKeditorAPI.GetInstance(EditorName); 
    return(oEditor.GetXHTML(true)); 
}
// 获取编辑器中文字内容
function getEditorTextContents(EditorName) { 
    var oEditor = FCKeditorAPI.GetInstance(EditorName); 
    return(oEditor.EditorDocument.body.innerText); 
}
// 设置编辑器中内容
function SetEditorContents(EditorName, ContentStr) { 
    var oEditor = FCKeditorAPI.GetInstance(EditorName) ; 
    oEditor.SetHTML(ContentStr) ; 
}

//向编辑器插入指定代码
function insertHTMLToEditor(content,codeStr){
   var oEditor = FCKeditorAPI.GetInstance(content);
   if (oEditor.EditMode==FCK_EDITMODE_WYSIWYG){
     oEditor.InsertHtml(codeStr);
   }else{
     return false;
   }
}



//格式化
function FormatText(bodyname) {
   var myeditor = FCKeditorAPI.GetInstance(bodyname);
   if (myeditor.EditMode==FCK_EDITMODE_WYSIWYG){
        var temps = new Array();

        isPart = false; //暂时无法实现局部格式化
        if (!isPart) {
            var imgs = FCKeditorAPI.GetInstance(bodyname).EditorDocument.images;
            if (imgs != null && imgs.length > 0) {
                for (j = 0; j < imgs.length; j++) {
                    var t = document.createElement("IMG");
                    t.alt = imgs[j].alt;
                    t.src = imgs[j].src;
                    t.width = imgs[j].width;
                    t.height = imgs[j].height;
                    t.align = imgs[j].align;
                    temps[temps.length] = t;
                }
                var formatImgCount = 0;
                for (j = 0; j < imgs.length;) {
                    imgs[j].outerHTML = "#FormatImgID_" + formatImgCount + "#";
                    formatImgCount++;
                }
            }
			var strongarray	= new Array();
			var strongcount = 0;
			for(var i=0;i<myeditor.EditorDocument.body.getElementsByTagName('b').length;i++){
				strongarray[strongcount]	= myeditor.EditorDocument.body.getElementsByTagName('b')[i].innerText.trim();
				myeditor.EditorDocument.body.getElementsByTagName('b')[i].innerHTML	= "#FormatStrongID_" + strongcount + "#";
				strongcount++;
			}

			for(var i=0;i<myeditor.EditorDocument.body.getElementsByTagName('strong').length;i++){
				strongarray[strongcount]	= myeditor.EditorDocument.body.getElementsByTagName('strong')[i].innerText.trim();
				myeditor.EditorDocument.body.getElementsByTagName('strong')[i].innerHTML	= "#FormatStrongID_" + strongcount + "#";
				strongcount++;
			}
                        
                        var a_array =new Array();
                        var a_href_array = new Array();
                        var a_name_array = new Array();
                        var a_count = 0;
                       for(var i=0;i<myeditor.EditorDocument.body.getElementsByTagName('a').length;i++){
				a_array[a_count]	= myeditor.EditorDocument.body.getElementsByTagName('a')[i].innerText.trim();
                                a_href_array[a_count]   = myeditor.EditorDocument.body.getElementsByTagName('a')[i].href;
                                a_name_array[a_count]   = myeditor.EditorDocument.body.getElementsByTagName('a')[i].name;
				myeditor.EditorDocument.body.getElementsByTagName('a')[i].innerHTML	= "#FormataID_" + a_count + "#";
				a_count++;
			}

            var html = processFormatText(myeditor.EditorDocument.body.innerText);
            if (temps != null && temps.length > 0) {
                for (j = 0; j < temps.length; j++) {
                    var imghtml = "<p align=\"center\"><img src=\"" + temps[j].src + "\" alt=\"" + temps[j].alt + "\" width=\"" + temps[j].width + "\" height=\"" + temps[j].height + "\" align=\"" + temps[j].align + "\" border=\"0\"></p>";
                    html = html.replace("#FormatImgID_" + j + "#", imghtml);
                }
            }

			for(var i=0;i<strongcount;i++){
				html = html.replace("#FormatStrongID_" + i + "#", "<strong>"+strongarray[i]+"</strong>");
			}
                        
                        for(var i=0;i<a_count;i++){
                                if(a_name_array[i] != ""){
                                    html = html.replace("#FormataID_" + i + "#", "<a href='"+a_href_array[i]+"' name='"+a_name_array[i]+"'>"+a_array[i]+"</a>");
                                }
                                else{
                                    html = html.replace("#FormataID_" + i + "#", "<a href='"+a_href_array[i]+"'>"+a_array[i]+"</a>");    
                                }
			}
			
			while(html.indexOf("</p></p>")!=-1)	html=html.replace("</p></p>","</p>");
			while(html.indexOf('<p><p align="center">')!=-1)	html=html.replace('<p><p align="center">','<p align="center">');

			SetEditorContents(bodyname, html);
			
        } else {

        }
   }else{
		alert('必须在设计模式下操作！');
   }
}

//格式化图片格式
function FormatImages(bodyname) {
   var myeditor = FCKeditorAPI.GetInstance(bodyname);
   if (myeditor.EditMode==FCK_EDITMODE_WYSIWYG){
        var temps = new Array();

        isPart = false; //暂时无法实现局部格式化
        if (!isPart) {
            var imgs = FCKeditorAPI.GetInstance(bodyname).EditorDocument.images;
            if (imgs != null && imgs.length > 0) {
                for (j = 0; j < imgs.length; j++) {
                    var t = document.createElement("IMG");
                    t.alt = imgs[j].alt;
                    t.src = imgs[j].src;
                    t.width = imgs[j].width;
                    t.height = imgs[j].height;
                    t.align = imgs[j].align;
                    temps[temps.length] = t;
                }
                var formatImgCount = 0;
                for (j = 0; j < imgs.length;) {
                    imgs[j].outerHTML = "#FormatImgID_" + formatImgCount + "#";
                    formatImgCount++;
                }
            }

            var html = processFormatText(myeditor.EditorDocument.body.innerHTML);
            if (temps != null && temps.length > 0) {
                for (j = 0; j < temps.length; j++) {
                    var imghtml = "<p align=\"center\"><img src=\"" + temps[j].src + "\" alt=\"" + temps[j].alt + "\" width=\"" + temps[j].width + "\" height=\"" + temps[j].height + "\" align=\"" + temps[j].align + "\" border=\"0\"></p>";
                    html = html.replace("#FormatImgID_" + j + "#", imghtml);
                }
            }

			while(html.indexOf("</p></p>")!=-1)	html=html.replace("</p></p>","</p>");
			while(html.indexOf('<p><p align="center">')!=-1)	html=html.replace('<p><p align="center">','<p align="center">');

			SetEditorContents(bodyname, html);
			
        } else {
            

        }
   }else{
		alert('必须在设计模式下操作！');
   }
}



function processFormatText(textContext) {
    var text = DBC2SBC(textContext);
    var prefix = "　　";
    var tmps = text.split("\n");
    var html = "";
	var ifblank = document.getElementById("ifblank");
    for (i = 0; i < tmps.length; i++) {
      var tmp = tmps[i].trim();
      if (tmp.length > 0) {
	  if (ifblank)
	  {
		  if (ifblank.checked)
			html += "<p>　　" + tmp + "</p>\n";
			else
			html += "<p>" + tmp + "</p>\n";
		  }
	  }else{
			html += "<p>" + tmp + "</p>\n";
	  }
    }
  return html;
}


function DBC2SBC(str) {
  var result = '';
  for (var i = 0; i < str.length; i++) {
    code = str.charCodeAt(i);
    // “65281”是“！”，“65373”是“｝”，“65292”是“，”。不转换"，"

    if (code >= 65281 && code < 65373 && code != 65292 && code != 65306){
    //  “65248”是转换码距
      result += String.fromCharCode(str.charCodeAt(i) - 65248);
    } else {
      result += str.charAt(i);
    }
  }
  return result;
}


String.prototype.trim = function()
{
  return this.replace(/(^[\s　]*)|([\s　]*$)/g, "");
};

String.prototype.leftTrim = function()
{
  return this.replace(/(^\s*)/g, "");
};

String.prototype.rightTrim = function()
{
  return this.replace(/(\s*$)/g, "");
};

