<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
<html>
<head>
<meta charset="utf-8">

<meta name="viewport" content="user-scalable=no,maximum-scale=1.0, minmum-scale=1.0"/>
<title>借款记录</title>
<link href="/free/Public/mobile/css/jiekuan-jilu.css" rel="stylesheet" type="text/css">
</head>


<style>
/*共同样式*/
* {margin:0; padding:0;}
.bb{ border:#F00 1px solid;}
body {color:#000; background:#FFF; font-family: Verdana, 
Arial, sans-serif; font-size:12px; text-align:center;
font-family:"Microsoft YaHei",微软雅黑
  }
textarea, input {word-wrap:break-word; word-break:break-all; padding:0px;}
li {list-style-type:none;}
img {border:0 none;}
a {color:#333; text-decoration:none;}

.qing { clear:both; height:0px; width:100%; font-
size:1px; line-height:0px; visibility:hidden; 
overflow:hidden; }
/*共同样式 end*/
body{ width:100%; height:auto; background-color:#f2f2f2;}
.bj{ width:100%; height:930px; background-color:#fff;}
.bj img{ margin:0 auto; margin-top:280px; margin-bottom:40px;}
.bj p{ font-size:36px; text-align:center;}
</style>

<body>
<?php if(($loan_data['is_loan'] == 0) AND ($loan_data['loan_num'] == 0)): ?><div class="bj"><img src="/free/Public/mobile/images/jie-jl.png"/><p>您还没有借款记录哦~</p></div>
<?php else: ?>
      <div class="jilu">
          <?php if(($loan_data['is_loan']) == "1"): ?><a href="/free/mobile.php/home/record/detail">
              <ul>
                <li class="zuo-zi"> 借款<?php echo ($loan_data['loan_amount']); ?>元<br/>
                <?php if(($loan_data['is_pay'] == '') OR ($loan_data['is_pay'] == 0) OR ($loan_data['is_pay'] == null)): else: ?>
                  <p>
                    <?php if(($dueday) == "1"): echo (date("Y-m-d",$loan_data['is_pay'])); ?>
                  <?php else: ?>
                    <?php echo (date("Y-m-d H:i:s",$loan_data['is_pay'])); endif; ?>
                  </p><?php endif; ?>
                </li>
                <li class="zhong-zi" style=" margin-left:0; float: right; margin-right:5%;">
                   <?php if(($loan_data['is_pay'] == '') OR ($loan_data['is_pay'] == 0) OR ($loan_data['is_pay'] == null)): ?><p>未打款</p>
                   <?php elseif(($overdue_show["day"] > 0)): ?>
                   <p style="color:red;">已逾期 &nbsp;&nbsp;<img src="/free/Public/mobile/images/huan_06.png" /></p>
                   <?php else: ?>
                   <p style="color:#2894FF">待还款 &nbsp;&nbsp;<img src="/free/Public/mobile/images/huan_06.png" /></p><?php endif; ?>
                </li>
                
              </ul>
            </a><?php endif; ?>
        <div class="qing"></div>
        

        <?php if(($record_data[0]['request_time'] == '') OR ($record_data[0]['request_time'] == null)): else: ?>
        <?php if(is_array($record_data)): $i = 0; $__LIST__ = $record_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><ul>
              <li class="zuo-zi"> 
                还款<?php echo ($vo["repayment_money"]); ?>元
                <br/>
                <p><?php echo (date("Y-m-d H:i:s",$vo["request_time"])); ?></p>
              </li>
              <li class="zhong-zi1" style=" margin-left:0; float: right; margin-right:5%;">已还款 &nbsp;&nbsp;<img src="/free/Public/mobile/images/huan_06.png" /></li>
              
          </ul><?php endforeach; endif; else: echo "" ;endif; endif; ?>
      </div><?php endif; ?>

</body>
</html>