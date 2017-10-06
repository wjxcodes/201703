<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="user-scalable=no,maximum-scale=1.0, minmum-scale=1.0"/>
<title>蜻蜓白卡</title>
<link href="/free/Public/mobile/css/huan.css" rel="stylesheet" type="text/css">
<link href="/free/Public/mobile/css/huan-kuan.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/free/Public/mobile/js/jquery-1.11.1.js"></script>
</head>
<body>

<div class="top">
<?php if(($loan_data["is_loan"] == 0) OR ($loan_data["is_loan"] == '') OR ($user_data["audit"] == 1)): ?><div class="top01">
	<img src="/free/Public/mobile/images/huan_03.png" width="247" height="246" style="margin-top:60px">
	<p class="t-wo">您当前没有借款哦~</p>
	<a href="/free/mobile.php/home/borrow/index">
	<div class="jie">
	<p>马上借款</p>
	</div>
	</a>
	</div>

<?php else: ?>

	<?php if(($loan_data["is_pay"] == 0) OR ($loan_data["is_pay"] == '') OR ($loan_data["is_pay"] == null)): ?><div class="content">
		<ul class="con_list">
		<li class="con_list03">
		<div class="con_li03_l"><p>√</p></div>
		<div class="con_li03_r"><p>申请提交成功
		<span>申请借款<?php echo ($loan_data["loan_amount"]); ?>元，期限<?php echo ($loan_time); ?>天，手续费<?php echo ($shouxufei); ?>元</span></p></div>
		</li>
			<?php switch($loan_data["maudit"]): case "2": ?><li class="con_list02">
					<div class="con_li02_l"><p>√</p></div>
					<div class="con_li02_r"><p>审核通过
					<span>恭喜通过风控审核</span></p></div>
				</li>
					<li class="con_list01">
					<div class="con_li01_l"><p>√</p></div>
					<div class="con_li01_r"><p>打款中
					<span>已进入打款状态，请您耐心等待</span></p></div>
				</li><?php break;?>

			<?php default: ?>
					<li class="con_list02" style="background:url(/free/Public/mobile/images/jiek_05.png) no-repeat;
					background-position:23px 0px;">
						<div class="con_li02_l" style="background:#ddd;"><p>√</p></div>
						<div class="con_li02_r"><p style="color:#999">审核通过
						<span style="color:#999">恭喜通过风控审核</span></p></div>
					</li>

					<li class="con_list01" style="background:url(/free/Public/mobile/images/jiek_05.png) no-repeat;
					background-position:23px -100px;">
						<div class="con_li01_l" style="background:#ddd;"><p>√</p></div>
						<div class="con_li01_r"><p style="color:#999">打款中 
						<span style="color:#999">已进入打款状态，请您耐心等待</span></p></div>
					</li><?php endswitch;?>

		</ul>
		</div>
		<div style="height:172px;"></div>

	<?php else: ?>

		<div class="ding"><span>全部待还1笔</span></div>

		<a href="/free/mobile.php/home/record/detail">
		<div class="jilu">
		<ul>
		<li class="zuo"><div><span>还款时间</span><br/><span class="cu">
			<?php if(($dueday) == "1"): echo (date("Y-m-d",$overdue_show["time"])); ?>
			<?php else: ?>
				<?php echo (date("Y-m-d H:i:s",$overdue_show["time"])); endif; ?>
		</span></div></li>
		<li class="zhong"><div>
			<br/><span>￥<?php echo ($end_money); ?></span><br/><!-- 服务费用:<?php echo ($shouxufei); ?>元 --><br/>	
		</div></li>
		<li class="you">
			<?php if(($overdue_show["day"]) > "0"): ?><span style="color:red; font-size: 44px;">逾期<?php echo ($overdue_show["day"]); ?>天</span><?php endif; ?>
		<span></span><br/><img src="/free/Public/mobile/images/huan_06.png"/></li>
		</ul></div>
		</a>
			<a href="/free/mobile.php/home/repay/renewal" onClick="return confirm('您的本次借款只能续期三次，您确定要续期吗？');">
				<div class="but">
					<p>续 &nbsp;期</p>
				</div>		
			</a><?php endif; endif; ?>

</div>

<!-- top end -->


<div class="much">
<p>支持多种还款方式</p>
</div>
<!-- much end -->

<?php if(($loan_data["is_pay"] == 0) OR ($loan_data["is_pay"] == '') OR ($loan_data["is_pay"] == null)): ?><ul>
<div>
    <a class="act" href="/free/mobile.php/home/help/index" >
	      <input  type="button" value="主动还款（银行卡）" name="go_pay" >
	 </a>
</div>
</ul>

<?php else: ?>
<ul>
<div>
    <a class="act" href="/free/mobile.php/home/repay/llpay" target="view_window">
	      <input  type="button" value="主动还款（银行卡）" name="go_pay" >
	 </a>
</div>
</ul><?php endif; ?>

<ul class="mode">
<?php if(($loan_data["is_pay"] == 0) OR ($loan_data["is_pay"] == '') OR ($loan_data["is_pay"] == null)): ?><a href="/free/mobile.php/home/help/index"   id="zfb_pay">
	<li class="pay" style=" border-top:1px solid #999; border-bottom:1px solid #999;">
	<img src="/free/Public/mobile/images/huan_08.png" width="72" height="72" style="margin-left:38px;">
	<p style="margin-left:40px;">支付宝还款</p>
	<diV class="load" style="margin-right:40px;"></diV>
	</li>
	</a>
<?php else: ?>
	<a href="/free/mobile.php/home/repay/zfb_pay"  id="zfb_pay">
	<li class="pay" style=" border-top:1px solid #999; border-bottom:1px solid #999;">
	<img src="/free/Public/mobile/images/huan_08.png" width="72" height="72" style="margin-left:38px;">
	<p style="margin-left:40px;">支付宝还款</p>
	<diV class="load" style="margin-right:40px;"></diV>
	</li>
	</a><?php endif; ?>
</ul>







<!-- <A style=" display:block; width:100%; line-height:160px; height:160px;text-align:center; font-size:34px;color:#808080; background-color:#fff; border-top:1px solid #999; border-bottom:1px solid #999;" id="more_pay">查看其他还款方式 <img wight="10px" height="10px" src="/free/Public/mobile/images/ss1.png" style=" margin-bottom:5px; margin-left:10px;"></A> -->


<script type="text/javascript">
	$("#more_pay").click(function(){
		$("#more_pay").css('display','none');
		$("#zfb_pay").css('display','block');
	});
</script>
<!-- <br/> -->
<!-- <span style="font-size: 27px;">尊敬的用户，由于农业银行维护，银行卡还款暂不支持农业银行。</span><br/>
<span style="font-size: 27px;">您可以选择使用支付宝还款，给您带来的不便请您谅解。</span> -->


<!-- ul mode end -->

<div class="foot">
<div class="line"></div>
<a href="/free/mobile.php/home/borrow/index"><img class="icon1" src="/free/Public/mobile/images/huan_04.png" width="72" height="101"></a>
<a href="/free/mobile.php/home/repay/index"><img class="icon2" src="/free/Public/mobile/images/huan_09.png" width="71" height="101"></a>
<a href="/free/mobile.php/home/my/index"><img class="icon3" src="/free/Public/mobile/images/jie_33.png" width="64" height="101"> </a>
</div>

<div style="height:160px;"></div>
</body>
</script>
</html>