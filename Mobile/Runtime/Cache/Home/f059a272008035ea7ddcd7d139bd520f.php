<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="user-scalable=no,maximum-scale=1.0, minmum-scale=1.0"/>
<title>优惠劵</title>
<style>
* {
	margin: 0;
	padding: 0;
}
body {
	color: #000;
	background: #fafafa;
	font-family: Verdana, Arial, sans-serif;
	font-size: 12px;
	text-align: center;
	font-family: "Microsoft YaHei", 微软雅黑
}
textarea, input {
	word-wrap: break-word;
	word-break: break-all;
	padding: 0px;
}
li {
	list-style-type: none;
}
img {
	border: 0 none;
}
a {
	color: #333;
	text-decoration: none;
}
.qing {
	clear: both;
	height: 0px;
	width: 100%;
font- size:1px;
	line-height: 0px;
	visibility: hidden;
	overflow: hidden;
}
ul li {
	float: left;
}
.quan{width:100%; height:240px; background-color:#fff; margin-top:40px; border-radius:10px; box-shadow: 2px 2px 2px #CCC; }
.quan-zuo{ width:30%; height:240px; background: #45b7fd url(/free/Public/mobile/images/quan_1.png) left  no-repeat; font-size:48px; line-height:240px; color:#fff;}
.quan-you{ width:70%; height:240px; background-color:#fff; border-radius:0 10px 10px 0; text-align:left; text-indent:2em;}

.quan-zuo1{ width:30%; height:240px; background: #c0c0c0 url(/free/Public/mobile/images/quan_2.png) left  no-repeat; font-size:48px; line-height:240px; color:#fff;}
.quan-you1{ width:70%; height:240px; background-color:#fff; border-radius:0 10px 10px 0; text-align:left; text-indent:2em; background:url(/free/Public/mobile/images/quan_3.png) right top  no-repeat;}
</style>
</head>

<body>



<?php if($coupons_data == null): ?><div><img src="/free/Public/mobile/images/wu-quan_02.png" width="100%" style=" margin-top:80px;"/></div>
<?php else: ?>
	<div style="width:90%; height:auto; margin:0 auto;">
		<?php if(is_array($coupons_data)): $i = 0; $__LIST__ = $coupons_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><ul class="quan">
		    	<li 
					<?php if(($vo['overdue_time'] < $time) OR ($vo['is_use'] == 1)): ?>class="quan-zuo1"
					<?php else: ?>
			    		class="quan-zuo"<?php endif; ?>
		    	>
			    	<?php if($vo['coupons_type'] == 1): ?>¥<span style="font-size:120px;"><?php echo ($vo['interest']); ?></span>
			    	<?php elseif($vo['coupons_type'] == 2): ?>
			    		¥<span style="font-size:120px;"><?php echo ($vo['lines']); ?></span><?php endif; ?>
		    	</li>

		    	<li <?php if($vo['is_use']==1){ echo "class='quan-you' style='background:url(/free/Public/mobile/images/quan_6.png) right top no-repeat;'"; }else{ if($vo['overdue_time']<$time){ echo "class='quan-you1'"; }else{ if($vo['coupons_type']==1){ echo "class='quan-you'"; }else if($vo['coupons_type']==2){ echo "class='quan-you' style='background:url(/free/Public/mobile/images/quan_5.png) right top no-repeat;'"; } } } ?>
				>
		    	  	<p style="margin-top:20px; font-size:34px; font-weight:bold;">
		    	  		<?php if($vo['coupons_type'] == 1): ?>抵扣券
		    	  		<?php elseif($vo['coupons_type'] == 2): ?>
		    	  			额度券<?php endif; ?>
		    	  	</p>
		    	  	<p style="margin-top:30px; font-size:34px;">
						<?php if($vo['coupons_type'] == 1): ?>抵扣费用￥<?php echo ($vo['interest']); ?>
		    	  		<?php elseif($vo['coupons_type'] == 2): ?>
		    	  			提升额度￥<?php echo ($vo['lines']); endif; ?>
		    	  	</p>
		    	  	<p style="margin-top:30px; font-size:34px;">有效期：<?php echo (date("Y.m.d",$vo['create_time'])); ?>-<?php echo (date("Y.m.d",$vo['overdue_time'])); ?></p>
		    	</li>

		  	</ul><?php endforeach; endif; else: echo "" ;endif; ?>
	</div><?php endif; ?>




</body>
</html>