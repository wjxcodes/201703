<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="user-scalable=no,maximum-scale=1.0, minmum-scale=1.0"/>
<title>我的</title>
<link href="/free/Public/mobile/css/jquery.circliful.css" rel="stylesheet" type="text/css" />
	<script src="/free/Public/mobile/js/jquery.min.js"></script>
	<script src="/free/Public/mobile/js/jquery.circliful.min.js"></script>
</head>

<body>
	<a href="customer"><div style="position:fixed; top:30px; right:30px; margin:20px;"><img src="/free/Public/mobile/images/kf.png" width="80" height="80"/></div></a>
<div style="width:100%; height:70px; background:#fff;"></div>
<div class="top">
<center>
<div id="myStat2" data-dimension="412" 

<?php if(($xiane == '') OR ($xiane == null)): ?>data-text="<?php echo ($initial); ?>"
<?php else: ?>
data-text="<?php echo ($xiane); ?>"<?php endif; ?> 
	data-info="可借额度(元)" data-width="42" data-fontsize="72" 
	<?php if(($judge == 'ok')): ?>data-percent="100"
	<?php else: ?>
	   data-percent="<?php echo ($perc); ?>"<?php endif; ?>
	data-fgcolor="#45b7f5" data-bgcolor="#ededed">
	</div>
</center>
<p class="n-wo">
剩余可借:

	<?php if(($xiane == null)): echo ($initial); ?>
	<?php else: ?>
	      <?php if(($judge == 'ok')): echo ($xiane); ?>
	      	<?php else: ?>
	      	 <?php echo ($yue); endif; endif; ?> 

</p>
</div>
<script>
$(function(){
	$('#myStat2').circliful();
});
</script>


<ul class="mode">
<a href="/free/mobile.php/home/record/index"><li class="active">
<div class="qwe">
<img src="/free/Public/mobile/images/me_03.png" width="72" height="62"></div>
<p>借款记录</p>  
<diV class="load"></diV>
</li>
</a>

<a href="/free/mobile.php/home/info/index">
<li class="bank">
<div class="qwe">
  <img src="/free/Public/mobile/images/me_07.png" width="75" height="55"></div>
<p>完善资料</p>
<diV class="load"></diV>
</li>
</a>

<a href="/free/mobile.php/home/help/index">
<li class="news">
<div class="qwe">
  <img src="/free/Public/mobile/images/me_11.png" width="66" height="66"></div>
<p>帮助中心</p>
<diV class="load"></div>
</li>
</a>




<!-- <a href="/free/mobile.php/home/invite/index">
<li class="bank">
<div class="qwe">
  <img src="/free/Public/mobile/images/yq_03.png" style="margin-top:45px; margin-left:45px;"></div>
<p>我的邀请</p>
<diV class="load"></diV>
</li>
</a> -->


<a href="/free/mobile.php/home/coupons/index">
<li class="bank">
<div class="qwe">
  <img src="/free/Public/mobile/images/quan_4.png" width="75" height="55"></div>
<p>优惠劵</p>
<diV class="load"></diV>
</li>
</a>






</ul>



<a href="/free/mobile.php/home/User/logout"><div class="out">
<p> 退出登录</p>
</div></a>


<div class="foot">
<div class="line"></div>
<a href="/free/mobile.php/home/borrow/index">
<img class="icon1" src="/free/Public/mobile/images/huan_04.png" width="72" height="102">
</a>
<a href="/free/mobile.php/home/repay/index">
<img class="icon2" src="/free/Public/mobile/images/jie_31.png" width="71" height="101"> 
</a>
<a href="/free/mobile.php/home/my/index">
<img class="icon3" src="/free/Public/mobile/images/huan_05.png" width="72" height="102"> 
</a> 
</div>

<div style="height:240px;"></div>

</body>
</html>