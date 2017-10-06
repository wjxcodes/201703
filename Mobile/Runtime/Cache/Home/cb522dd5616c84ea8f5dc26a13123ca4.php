<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="user-scalable=no,maximum-scale=1.0, minmum-scale=1.0"/>
<title>确认申请</title>
<style>
li {
	list-style-type: none;
}
a {text-decoration:none;}
.qing {
	clear: both;
	height: 0px;
	width: 100%;
font- size:1px;
	line-height: 0px;
	visibility: hidden;
	overflow: hidden;
	
}

select{ float:right; text-indent:7em; color: #45b7fd;
  width:500px;
    height:80px;
    font-size:36px;
    appearance:none;
    -moz-appearance:none;
    -webkit-appearance:none;
    
  
  
  padding-right: 60px;
  background: url(/free/Public/mobile/images/xia-1-06.png) no-repeat scroll right 20px center transparent;
  background-color:#fff;
  border:none;
}
option{ width:auto; height:80px; background-color:#fff; font-size:36px; }



</style>
<script src="/free/Public/mobile/js/jquery.min.js"></script>
<script>  
$(function(){  
    $('#dian').click(function(){
        if($('#timo').is(':hidden')){
        $('#timo').show();
        }else{
        $('#timo').hide();
        }  
    })  
})  
</script>
</head>

<body style="background-color:#f2f2f2; margin:0; padding:0; color:#333;">
<div style="width:100%; height:auto; background-color:#fff;">
  <div style="width:100%; height:auto; border-bottom:1PX solid #999;">
    <p style="width:90%; height:120px; line-height:120px; margin:0 auto; font-size:46px; font-weight:600;">借款信息</p>
  </div>
  <ul style="width:90%; font-size:36px; color:#666; height:80px; padding:0; line-height:80px; margin:0 auto; margin-top:30px; border-bottom:1px solid #CCC;">
    <li style="float:left;">借款金额</li>
    <li style=" float:right;"><?php echo ($post_data["money"]); ?>元</li>
  </ul>
  <ul style="width:90%; font-size:36px; color:#666; height:80px; padding:0; line-height:80px; margin:0 auto; margin-top:30px; border-bottom:1px solid #CCC;">
    <li style="float:left;">借款期限</li>
    <li style=" float:right;"><?php echo ($post_data["time"]); ?>天</li>
  </ul>
  <ul style="width:90%; font-size:36px; color:#666; height:80px; padding:0; line-height:80px; margin:0 auto; margin-top:30px; border-bottom:1px solid #CCC;">
    <li style="float:left;">到帐金额</li>
    <li style=" float:right;" id="jine"><?php echo ($data["should_money"]); ?>元</li>
  </ul>
  <ul style="width:90%; font-size:36px; color:#666; height:80px; padding:0; line-height:80px; margin:0 auto; margin-top:30px; border-bottom:1px solid #CCC;">
    <li style="float:left;">到帐银行卡</li>
    <li style=" float:right;"><?php echo ($data["user_bank_card"]); ?></li>
  </ul>
    <ul style="width:90%; font-size:36px; color:#666; height:80px; padding:0; line-height:80px; margin:0 auto; margin-top:30px; border-bottom:1px solid #CCC;">
    <li style="float:left;">年化利率</li>
    <li style=" float:right;">预计&nbsp; 5.21%</li>
  </ul>
  <div class="qing"></div>
  <div style="width:100%; height:30px; background-color:#f2f2f2; margin-top:40px;"></div>
  <a id="dian">
  <div style="width:100%; height:auto; border-bottom:1PX solid #999;">
    <ul style="width:90%; padding:0; height:120px; line-height:120px; margin:0 auto; font-size:46px; font-weight:600;">
      <li style="float:left;">综合费用</li>
      <li style=" display:block; float:right;"><img src="/free/Public/mobile/images/e1_03.png" height="30"/></li>
      <li style="float:right; font-weight:500;margin-right:30px;" id="feiyong"><?php echo ($data['sum_cost']['shenji']+$data['sum_cost']['lixi']+$data['sum_cost']['guanli']); ?>元</li>
    </ul>
  </div>
  </a>
  <div id="timo" style="display:none;">
    <ul style="width:90%; font-size:36px; color:#666; height:80px; padding:0; line-height:80px; margin:0 auto; margin-top:30px; border-bottom:1px solid #CCC;">
      <li style="float:left;">快速信审费</li>
      <li style=" float:right;"><?php echo ($data['sum_cost']['shenji']); ?>元</li>
    </ul>
    <ul style="width:90%; font-size:36px; color:#666; height:80px; padding:0; line-height:80px; margin:0 auto; margin-top:30px; border-bottom:1px solid #CCC;">
      <li style="float:left;">利息</li>
      <li style=" float:right;"><?php echo ($data['sum_cost']['lixi']); ?>元</li>
    </ul>
    <ul style="width:90%; font-size:36px; color:#666; height:80px; padding:0; line-height:80px; margin:0 auto; margin-top:30px; border-bottom:1px solid #CCC;">
      <li style="float:left;">账户管理费</li>
      <li style=" float:right;"><?php echo ($data['sum_cost']['guanli']); ?>元</li>
    </ul>
  </div>

<form action="<?php echo U('Home/Borrow/refer');?>" method="post"  onsubmit="return submitTest();">

<?php if($coupons_data[0] != ''): ?><div style="width:100%; height:auto; border-bottom:1PX solid #999;">
  <ul style="width:90%; padding:0; height:120px; line-height:120px; margin:0 auto; font-size:46px; font-weight:600;">
    <li style="float:left;">优惠劵</li>
  </ul>
</div>

<div>

    <?php if($coupons_lines[0] != ''): ?><ul style="width:90%; font-size:36px; color:#666; height:80px; padding:0; line-height:80px; margin:0 auto; margin-top:30px; border-bottom:1px solid #CCC;">
          <li style="float:left;">提额劵</li>
          <li style=" float:right;color:#45b7fd;">本次提升额度<?php echo ($coupons_lines[0]['lines']); ?>元</li>
          <input type="hidden" id="coupons_lines" name="coupons_lines" value="<?php echo ($coupons_lines[0]['id']); ?>" />
        </ul><?php endif; ?>


<!-- <?php if(is_array($coupons_interest)): $i = 0; $__LIST__ = $coupons_interest;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label><ul style="width:90%; font-size:36px; color:#666; height:80px; padding:0; line-height:80px; margin:0 auto; margin-top:30px; border-bottom:1px solid #CCC;">
          <li style="float:left;">抵扣劵</li>
          <input type="radio" value="<?php echo ($vo['id']); ?>" name="interest_id" <?php if($i == 1): ?>checked="checked"<?php endif; ?>>
          <?php if($i == 1): ?><li id="li<?php echo ($vo['id']); ?>" class="li_all" style="float:right;color:#45b7fd;"><a class="check_all" id="check<?php echo ($vo['id']); ?>" style="color:#45b7fd;">√</a><?php echo ($vo['interest']); ?>元</li>
          <?php else: ?>
              <li id="li<?php echo ($vo['id']); ?>" class="li_all" style="float:right;color:#666;"><a class="check_all" id="check<?php echo ($vo['id']); ?>" style="color:#45b7fd;display:none">√</a><?php echo ($vo['interest']); ?>元</li><?php endif; ?>
      </ul></label><?php endforeach; endif; else: echo "" ;endif; ?> -->
<!-- <?php if($coupons_interest[0] != ''): ?><ul style="width:90%; font-size:36px; color:#666; height:80px; padding:0; line-height:80px; margin:0 auto; margin-top:30px; border-bottom:1px solid #CCC;">
      <li style="float:left;">抵扣劵</li>
      <li style=" float:right;color:#45b7fd;"><?php echo ($coupons_interest[0]['interest']); ?>元</li>
      <input type="hidden" id="coupons_interest" name="coupons_interest" value="<?php echo ($coupons_interest[0]['id']); ?>" />
  </ul><?php endif; ?> -->


    <?php if($coupons_interest[0] != ''): ?><ul style="width:90%; font-size:36px; color:#666; height:80px; padding:0; line-height:80px; margin:0 auto; margin-top:30px; border-bottom:1px solid #CCC;">
              <li style="float:left;">抵扣劵</li>
              <li   style="float:right;">
                  <select style="" name="coupons_interest" id="select_interest">
                    <?php if(is_array($coupons_interest)): $i = 0; $__LIST__ = $coupons_interest;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo['id']); ?>"><?php echo ($vo['interest']); ?>元</option><?php endforeach; endif; else: echo "" ;endif; ?>
                  </select>
              </li>
          </ul><?php endif; ?> 


</div><?php endif; ?>


  <div class="qing"></div>
  <div style="width:100%; height:30px; background-color:#f2f2f2; margin-top:40px;"></div>
  <div style="width:100%; height:auto; border-bottom:1PX solid #999;">
    <p style="width:90%; height:120px; line-height:120px; margin:0 auto; font-size:46px; font-weight:600;">还款信息</p>
  </div>
  <ul style="width:90%; font-size:36px; color:#666; height:80px; padding:0; line-height:80px; margin:0 auto; margin-top:30px;">
    <li style="float:left;">实际应还金额</li>
    <li style=" float:right; font-size:42px;"><span style="color:#F90; " id="repay_money"><?php echo ($data['repay_money']-$coupons_interest[0]['interest']); ?></span>元</li>
  </ul>
  <div class="qing"></div>
  <div style="width:100%; height:30px; background-color:#f2f2f2; margin-top:20px;"></div>
  <div style="width:100%; height:auto; border-bottom:1PX solid #999;">
    <p style="width:90%; height:120px; line-height:120px; margin:0 auto; font-size:46px; font-weight:600;">交易协议</p>
  </div>
  <div style="width:90%; height:200px; margin:0 auto;">
    
    <div  style=" font-size:36px; color:#45b7fd; line-height:80px;">
      <input type="checkbox" id="loan_agreement" checked="checked" value="借款协议" style="float:left; width:36px; height:36px; background:#45b7fd; border:2px solid #45b7fd; margin-top:24px;"/>
      <span id="argee">&nbsp;<a style="color:#45b7fd;" href="/free/mobile.php/home/borrow/agreement">《借款服务与隐私协议》</a> &nbsp; <a style="color:#45b7fd;" href="/free/mobile.php/home/borrow/deduct">《授权扣款委托书》</a> &nbsp; <a style="margin-left:52px;color:#45b7fd;" href="/free/mobile.php/home/borrow/zhengxin">《征信授权书》</a> </span>   

    </div>

  </div>

</div>
<div style="width:90%; height:100px; line-height:100px; font-size:36px; margin:0 auto; color:#666; margin-bottom:100px;">
您需要在<span style="color:#F90;"><?php echo ($data["weekLater"]); ?></span>前还款<span style="color:#F90;" id="repay_money1"><?php echo ($data['repay_money']-$coupons_interest[0]['interest']); ?></span>元

</div>

    <input type="hidden" id="money" name="money" value="<?php echo ($post_data["money"]); ?>" />
    <input type="hidden"  id="time" name="time" value="<?php echo ($post_data["time"]); ?>" />
    <input class="confirm" type="submit" value="确定申请" style=" border: none; position:fixed; bottom:0; width:100%; height:100px; font-size:40px; background:#45b7fd; color:#fff; text-align:center; line-height:100px;"/>
</form>
</body>
<script>
    /*$(":input[name=interest_id]").click(function(){
        var num=$(this).val();
        $(this).attr('checked','checked');

        $(".check_all").css('display','none');
        $("#check"+num+"").css('display','block');

        $(".li_all").css('color','#666');
        $("#li"+num+"").css('color','#45b7fd');
    });*/
 
 

    $("#select_interest").change(function(){
        var jine=$("#jine").html();
        var feiyong=$("#feiyong").html();
        var jine=jine.slice(0,-1);
        var feiyong=feiyong.slice(0,-1);
        var select_interest=$("#select_interest").find("option:selected").text();
        var str=select_interest.slice(0,-1);
        var end_money=Number(jine)+Number(feiyong)-str;

        $("#repay_money").html(end_money);
        $("#repay_money1").html(end_money);

    });

    function submitTest(){
        var loan_agreement=$("#loan_agreement").is(':checked');
        if(!loan_agreement){
          alert("请同意以下服务协议！");
          return false;
        }
    }
    
    /*$("#argee").click(function(){
        var money=$("#money").val();
        var time=$("#time").val();
        window.location.href="/free/mobile.php/home/borrow/agreement?time="+time+"&money="+money+"";
     });*/
</script>
</html>