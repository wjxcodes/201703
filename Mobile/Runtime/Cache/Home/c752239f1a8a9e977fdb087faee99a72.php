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
  
<p class="menu_head">1、借款费用如何收取？</p>
<div class="menu_body"> 
<a href="###">用户在蜻蜓卡借款，平台会收取一定的综合费用，综合费用会在借款时扣除。</a> 
</div>
    
<p class="menu_head">2、逾期费用如何收取？</p>
<div class="menu_body"> <a href="###">逾期还款，平台会收取逾期费用。白卡的逾
期费用为逾期本金的1%/天。 </a> 
</div>
    
<p class="menu_head">3、续期费用如何收取？</p>
<div class="menu_body"> <a href="###">续期费用有两部分组成：服务费、续期费。
服务费即为一笔借款延期一个借款周期所需的费用。
续期费用与原借款费用一致。</a> 
</div>



    
</div>

</body>
</html>