<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="user-scalable=no,maximum-scale=1.0, minmum-scale=1.0"/>
<title>借款详情</title>
<link href="/free/Public/mobile/css/jiexq.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="javascript" src="/free/Public/mobile/js/jquery.js"></script>
</head>

<body>
<div class="top">
<p>借款详情</p>
</div>

<div class="content">
<ul class="con_list">


<!-- 1.借款时间 -->

  <li class="con_list01" style="background:url(/free/Public/mobile/css/jiek_05.png) no-repeat;background-position:23px 100px;">
  <div class="con_li02_l"><p>×</p></div>
  <div class="con_li02_r">

  <p>
      <?php if(($overdue_show["day"] > 0) AND (($loan_data['is_pay'] == '0') OR ($loan_data['is_pay'] == null) OR ($loan_data['is_pay'] == ''))): ?><span style="color:red; font-size: 44px;">您已逾期<?php echo ($overdue_show["day"]); ?>天</span>
        <span style="color:red;" >请尽快还款，以免对您的信誉造成不好的影响</span>
      <?php else: ?>
        借款时间为<?php echo ($loan_time); ?>天。
        <span>请在借款期限之内还款</span><?php endif; ?>
  </p>
  </div>
  </li>



<!-- 打款状态 -->
<?php if(($loan_data['is_pay'] == '0') OR ($loan_data['is_pay'] == null) OR ($loan_data['is_pay'] == '')): ?><li class="con_list02">
  <div class="con_li02_l"><p>×</p></div>
  <div class="con_li02_r">
  <p>打款成功 
  <span>打至卡号为<?php echo ($loan_data['bank_card']); ?>中</span>
  </p>
<?php else: ?>
  <li class="con_list01" style="background:url(/free/Public/mobile/css/jiek_03.png) no-repeat;background-position:23px 0; ">
  <div class="con_li01_l"><p>√</p></div>
  <div class="con_li01_r">
  <p>
    
    <?php if(($dueday) == "1"): echo (date("Y-m-d",$loan_data['is_pay'])); ?>
    <?php else: ?>
      <?php echo (date("Y-m-d H:i:s",$loan_data['is_pay'])); endif; ?>
    打款成功 
  <span>打至卡号为<?php echo ($loan_data['bank_card']); ?>中</span>
  </p><?php endif; ?>
  </div>
  </li>




<!-- 审核状态 -->
<?php if(($loan_data['maudit'] != '2')): ?><li class="con_list02">
  <div class="con_li02_l"><p>×</p></div>
  <div class="con_li02_r">
<?php else: ?>
  <li class="con_list01" style="background:url(/free/Public/mobile/css/jiek_03.png) no-repeat;background-position:23px 0; ">
  <div class="con_li01_l"><p>√</p></div>
  <div class="con_li01_r"><?php endif; ?>

  <p>审核通过 
  <span>恭喜通过风控审核</span>
  </p>
  </div>
  </li>




<!-- 默认提交申请 -->
<li class="con_list03" style="background:url(/free/Public/mobile/css/jiek_03.png) no-repeat;background-position:23px -100px;">
<div class="con_li01_l"><p>√</p></div>
<div class="con_li01_r">
<p><?php echo (date("Y-m-d H:i:s",$loan_data['loan_request'])); ?>申请提交成功 
<span>申请借款<?php echo ($loan_data['loan_amount']); ?>元，期限<?php echo ($loan_time); ?>天。
</span>
<span>交易号：<?php echo ($loan_data['loan_order']); ?>
</span>
</p>
</div>
</li>
</ul>
</div>

<div class="qing"></div>



<?php if(($loan_data['is_pay'] == '0') OR ($loan_data['is_pay'] == '') OR ($loan_data['is_pay'] == null)): else: ?>
  <div class="cot">
  <ul class="cot_co">
      <li><p>还款日期截止
      <span><?php echo (date("Y-m-d",$overdue_show["time"])); ?></span>
      </p>
      </li>

        <?php if($overdue_show["day"] > 0): ?><li><p>您已逾期 <?php echo ($overdue_show["day"]); ?>天</p></li>
          <li><p>逾期费用为 <?php echo ($overdue_show["overdue_money"]); ?>元</p></li><?php endif; ?>     
      <li><p>借款金额金额
      <span><?php echo ($loan_data['loan_amount']); ?>元</span>
      </p>
      </li>

      <li><p>打款日期
      <span><?php echo (date("Y-m-d",$loan_data['is_pay'])); ?></span>
      </p>
      </li>

  </ul> 
  </div>

  <div class="qing" style="height: 150px;"></div>

  <div class="di" style="margin: 0 auto;">
    <?php if($overdue_show["day"] > 0): else: ?>
      <!--   <div class="di-zuo">
           <a  href="/free/mobile.php/home/repay/renewal">
            <input type="button" value="申请续期"/>
           </a>
      </div> --><?php endif; ?>
    <div  class="di-you" style=" width: 80%; margin-left: 10%;">
    <a href="/free/mobile.php/home/record/../repay/index">
      <input type="button" value="立即还款" />
    </a>
    </div>
  </div><?php endif; ?>
</body>
</html>