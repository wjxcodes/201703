<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="user-scalable=no,maximum-scale=1.0, minmum-scale=1.0"/>
<title>蜻蜓白卡</title>
<script type="text/javascript" language="javascript" src="/free/Public/mobile/js/jquery.js"></script>
<script type="text/javascript" language="javascript">
$(document).ready(function()
{
	$("#firstpane p.menu_head").click(function()
    {
		$(this).css({backgroundImage:"url(/free/Public/mobile/images/jian_06.png)"}).next("div.menu_body").slideToggle(300).siblings("div.menu_body").slideUp("slow");
       	$(this).siblings().css({backgroundImage:"url(/free/Public/mobile/images/jian_03.png)"});
	});
	
	});

</script>

<link href="/free/Public/mobile/css/question_new.css" rel="stylesheet" type="text/css" />
</head>
<body>

  
<div id="firstpane" class="menu_list">
  
<p class="menu_head">1、收不到验证码怎么办？</p>
<div class="menu_body"> 
<a href="###">（1）重启手机或清理手机缓存；<br/> 
（2）检查一下是否被手机安全软件拦截了；<br/>  
（3）检查一下是否退订过短信，如果退订过短信，联系客服重新接受短信。</a> 
</div>
    
<p class="menu_head">2、如何更改手机号？</p>
<div class="menu_body"> <a href="###">目前不支持用户自行更改注册手机号，请联系客服，我们将在核实身份后为您更改。 </a> 
</div>
    
<!-- <p class="menu_head">3、是否可以注销账户？</p>
<div class="menu_body"> <a href="###">注销账户后，将无法再次注册和使用现金卡，请您谨慎处理。目前不支持用户自行注销账户，
请联系客服进行相关操作。</a> 
</div> -->
    
<p class="menu_head">3、可以通过哪些方式联系到客服？</p>
<div class="menu_body"> <a href="###">客服电话：0371-58576913&nbsp;&nbsp; 邮箱地址：service@qingtingkeji.com.cn&nbsp;&nbsp;服务时间：周一至周日，每天的8:30--22:00 </a> 
</div> 
    


    
</div>

</body>
</html>