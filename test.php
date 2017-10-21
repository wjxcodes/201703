<?php 
	function add_user_info(){  
		 //检查是否需要补充用户信息  
		 var addUserInfo = $('.xxws');  
		 $.post(U('AddUserInfo/check'),{'times':Math.random()},function(e){  
		 if(e.data!='success'){  
		 //需要补充的字段写入data属性  
		 addUserInfo.attr('data', e.data);  
		 //需要补充用户信息  
		 $.post(U('AddUserInfo/index'),function(html){  
		 addUserInfo.html(html);  
	 });  

 ?>