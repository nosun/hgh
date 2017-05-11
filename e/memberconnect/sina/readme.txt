
 
 ********************安装/卸载插件********************
1、将“sina”目录下的文件上传至帝国CMS系统 /e/memberconnect/目录；
2、将eibase.php拷贝到 /e/memberconnect/目录；
3、登录管理后台》系统》扩展菜单》管理菜单，增加菜单分类“第三方交互”，类型选“插件菜单”，然后进入“管理菜单”，添加菜单名称“新浪交互安装与卸载"，链接地址“../memberconnect/sina/install/index.php”
3、转到后台的扩展菜单“新浪交互安装与卸载",依提示进行安装/卸载；
********************插件使用     ********************

1、插件安装后，可登录后台>“用户”>“外部接口”>“管理外部登录接口”：里设置参数。
    appid（应用ID）、appkey（应用密钥）需要自己到新浪微博官网申请。

2、前台在要显示新浪微博登录链接的页面加上如下代码：
　　<a href="[!--news.url--]e/memberconnect/?apptype=sina" target="_blank"><img src="[!--news.url--]e/memberconnect/sina/images/login.png" border="0"></a>

3、生成相应页面。

********************     插件目录说明     ********************

/sina/                     新浪微博交互插件目录
    ├install/              插件安装/卸载文件目录
    │├index.php            安装/卸载主文件
    │├install.php          安装插件文件
    │└uninstall.php        卸载插件文件
    ├images/               图片目录
    ├class.php             功能实现文件
    ├to_login.php          转向登录处理文件
    └loginend.php          返回登录处理文件