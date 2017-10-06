<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="user-scalable=no,maximum-scale=1.0, minmum-scale=1.0"/>
<title>蜻蜓白卡</title>
<link href="/free/Public/mobile/css/jie.css?v=1" rel="stylesheet" type="text/css">
<link href="/free/Public/mobile/css/rangeslider.css" rel="stylesheet" type="text/css">
<script src="/free/Public/mobile/js/jquery.min.js"></script>
<script src="/free/Public/mobile/js/rangeslider.min.js"></script>
<script src="/free/Public/mobile/js/borrow.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>
<body>
<div class="top" >

<div class="card" 
<?php if($loan_data['card_type'] == 1): ?>style="background:url(/free/Public/mobile/images/jk-1.jpg) no-repeat center center;"  
  <?php elseif($loan_data['card_type'] == 2): ?>
  style="background:url(/free/Public/mobile/images/yk-2.jpg) no-repeat center center;"  
  <?php else: ?>
  style="background:url(/free/Public/mobile/images/a1_03.png) no-repeat center center;"<?php endif; ?>
>

<div class="card_li" >资料真实即可申请 10分钟放款</div>
<div class="card_name">蜻蜓白卡</div>
<div class="card_edu" 
<?php if($loan_data['card_type'] == 1): ?>style="color:#FFF;"
<?php elseif($loan_data['card_type'] == 2): ?>
style="color:#FFF;text-shadow: 1px 1px 0px #333;"
<?php else: endif; ?>
>信用额度 (元)</div>
<div class="card_xpian">
<img src="/free/Public/mobile/images/jie_03.png" width="120" height="120"><p 
<?php if($loan_data['card_type'] == 2): ?>style="text-shadow: 1px 1px 0px #333"
<?php else: endif; ?>
>
 <?php echo ($data['limit_money']); ?>
</p></div>
<div class="qing"></div>
<ul class="card_num" 
<?php if($loan_data['card_type'] == 1): ?>style="color:#FFF;"
<?php elseif($loan_data['card_type'] == 2): ?>
style="color:#FFF;text-shadow: 1px 1px 0px #333"
<?php else: ?>
style="color:#177bbf;"<?php endif; ?>
>
<li>6216</li><li>8<?php if($data['card_1'] == ''): ?>954<?php else: echo ($data['card_1']); endif; ?></li><li>****</li><li><?php if($data['card_2'] == ''): ?>9786<?php else: echo ($data['card_2']); endif; ?></li>
</ul>
<div class="qing"></div>
<div class="card_valid" 
<?php if($loan_data['card_type'] == 1): ?>style="color:#FFF;"
<?php elseif($loan_data['card_type'] == 2): ?>
style="color:#FFF;text-shadow: 1px 1px 0px #333"
<?php else: endif; ?>
>VALID THRU <?php echo (date("Y/m/d",$user_data["create_time"])); ?></div>
</div>
<div class="qing"></div>

</div>
  <!--滚动条 -->
<div class="apple">
<ul>
<li><a href="###" target="_blank">尾号<?php echo rand(1000,9999);?>，成功借款1000元，申请至放款耗时30分钟</a></li>  
<li><a href="###" target="_blank">尾号<?php echo rand(1000,9999);?>，成功借款500元，申请至放款耗时10分钟</a></li> 
<li><a href="###" target="_blank">尾号<?php echo rand(1000,9999);?>，成功借款800元，申请至放款耗时20分钟 </a></li>
<li><a href="###" target="_blank">尾号<?php echo rand(1000,9999);?>，成功借款1200元，申请至放款耗时15分钟 </a></li>
<li><a href="###" target="_blank">尾号<?php echo rand(1000,9999);?>，成功借款2000元，申请至放款耗时20分钟 </a></li> 
</ul> 
</div>

<!-- 借款后显示状态 已申请 未打款 开始-->
<?php if((($loan_data["is_pay"] == '') OR ($loan_data["is_pay"] == null) OR ($loan_data["is_pay"] == 0)) AND ($loan_data["is_loan"] == 1) AND ($message["audit"] != 1)): ?><div class="content">
    <ul class="con_list">


    <li class="con_list03">
    <div class="con_li03_l"><p>√</p></div>
    <div class="con_li03_r">
      <p>申请提交成功
    <span>申请借款<?php echo ($loan_data["loan_amount"]); ?>元，期限<?php echo ($data['deadline']); ?>天，手续费<?php echo ($data['poundage']); ?>元</span>
    <span>交易号：<?php echo ($loan_data["loan_order"]); ?></span>
    </p></div>
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
    <!-- 借款后显示状态 已申请 未打款 结束-->

<?php elseif(($is_pay != null) AND ($is_pay != '') AND ($is_pay != 0)): ?>


<!--      我要还款 借款提示   寻求帮助          -->
<div class="cont" >
  <div style="height:50px;"></div>
  <div style=" width:100%; font-size:52px; color:#45b7fd; padding-bottom:30px; border-bottom:1px solid #CCC;">
    <p><?php echo ($wenmoney); ?>.00元</p >
    <p style="font-size:40px; margin-top:5px;">待还款金额</p >
  </div>
  <div style="width:90%; height:auto; margin:0 auto; margin-top:40px; font-size:40px;">
    <ul style=" margin-top:15px;">
      <li style="float:left;">还款日期</li>
      <li style="float:right;"><?php echo date('Y年m月d日',$day_time);?></li>
    </ul>
    <div class="qing"></div>
    <ul style=" margin-top:15px;">
      <li style="float:left;">借款金额</li>
      <li style="float:right;"><?php echo ($loan_amount); ?>.00元</li>
    </ul>
    <div class="qing"></div>
    <ul style=" margin-top:15px;">
      <li style="float:left;">申请日期</li>
      <li style="float:right;"><?php echo date('Y年m月d日',$is_pay);?></li>
    </ul>
    <div class="qing"></div>
  </div>
  <a href="/free/mobile.php/home/repay/index">
  <div style=" float:left; width:90%; margin-left:5%; height:100px; border-radius:50px;   background-color:#45b7fd; text-align:center; line-height:100px; font-size:42px; color:#FFF; margin-top:40px;">我要还款</div>
  </a>  
</div>

<?php else: ?>


<form action="/free/mobile.php/home/borrow/confirm" method="post" enctype="multipart/form-data">
<!-- 默认状态 -->

<div class="cont">

<!-- 滑动条 --> 

<div class="m-xin">
<section id="examples">
    <div id="js-example-destroy">
        <br>
        <output style="display:block; font-size:36px; text-align:right; color:#666; margin-bottom:40px; margin-top:8px; margin-right:-30px;"></output>
        <input type="range" min="500" max="<?php echo ($data['limit_money']); ?>" step="100" value="<?php echo ($data['limit_money']); ?>" name="money" id="jiekuanjine" data-rangeslider>
            <div style="margin-top:10px;"> 
                <div style="float:left; margin-left:-42px; font-size:36px; margin-top:40px; color:#a4a4a4;">
                  500
                </div>
                <div style="float:right; margin-right:-42px; font-size:36px; margin-top:40px; color:#a4a4a4;">
                  <?php echo ($data['limit_money']); ?>
                </div>
            </div>
    </div>
</section>
</div>

<div class="qing"></div>

<!-- 借款时间 -->
<div class="time">
<p>借款时间</p>
<div class="shijian">
<div>
    <label>
        <input type="radio" style="display:none" name="time" value="14"/>
        <div  id="time_14"  class="white" onclick="time_switch_1()">
            <p>14天</p>
        </div>
    </label>
    <label>
        <input type="radio" style="display:none" name="time"  checked="checked" value="7"/>
        <div  id="time_7"  class="blue" onclick="time_switch_2()">
            <p>7天</p>
        </div>
    </label>
</div>
</div>
</div>

<!-- 借款费用 -->

<div style=" width:auto; height:60px; margin:0 auto;  margin-top:40px; font-size:34px; color:#F90;"><div>不向学生提供服务</div></div>


<div class="button">
<div class="submit">
   <?php if(($loan_data["is_loan"]) == "1"): ?><input type="button"  value="马上借款" onClick="show()" style="margin-top: 30px;" />
      <div class="yincang">
          <p>提示</p>
          <div class="ent">您已有借款，还完才能继续申请借款</div>
          <div class="anjian">
            <input type="button" value="我知道了" onClick="closeDiv()"/>
          </div>
      </div>
  <?php else: ?>
    <?php switch($user_data["message"]): case "1": if(($user_data["past"]) > "2"): ?><input type="button"  value="马上借款" id="past" />
             <?php else: ?>
                <?php if(($tel_record_data["is_collect"] == 1) AND (($taobao_data["is_collect"] == 1) OR ($jingdong_data["is_collect"] == 1)) AND ($user_data["self_portrait"] != '') AND ($user_data["identity_front"] != '') AND ($user_data["identity_reverse"] != '') AND ($user_data["linkman_name"] != '') AND ($user_data["conven"] == 1) AND ($user_data["addres"] != '') AND ($user_data["com_addres"] != '') AND ($user_data["bank_tel"] == 'LL') AND ($user_data["clan_name"] != '')): ?><!--   正常放款：  -->    
      <input type="submit"  value="马上借款"/>
           <!-- 弹窗阻止 -->
      <!-- <input type="button"  value="马上借款" id="stop"/> -->
                <?php else: ?>
                  <input type="button"  value="马上借款" onClick="show()"/>
                  <div class="yincang">
                    <p>提示</p>
                    <div class="ent">
                          完善个人信息即可申请借款
                    </div>
                    <div class="anjian">
                      <a href='/free/mobile.php/home/borrow/../info/index' ><input type="button" value="我知道了" onClick="closeDiv()"/></a>
                    </div>
                  </div><?php endif; endif; break;?>

    <?php default: ?>
        <input type="button"  value="马上借款" onClick="show()"/>
        <div class="yincang">
          <p>提示</p>
          <div class="ent">
              <?php switch($_SESSION['name']): case "": ?>请登录<?php break;?>
              <?php default: ?>
                完善个人信息即可申请借款<?php endswitch;?>
          </div>
          <div class="anjian">
            <a href="/free/mobile.php/home/borrow/../info/index"><input type="button" value="我知道了" onClick="closeDiv()"/></a>
          </div>
        </div><?php endswitch; endif; ?>
</form>
</div>
</div><?php endif; ?>
</div>
<div class="foot">
<div class="line"></div>
<a href="/free/mobile.php/home/borrow/index"><img class="icon1" src="/free/Public/mobile/images/jie_29.png" width="72" height="101"></a>
<a href="/free/mobile.php/home/repay/index"><img class="icon2" src="/free/Public/mobile/images/jie_31.png" width="72" height="101"></a>
<a href="/free/mobile.php/home/my/index"><img class="icon3" src="/free/Public/mobile/images/jie_33.png" width="64" height="101"> </a>
</div>
<script type="text/javascript">
    $("#opencont").click(function () {
        alert("请您联系客服人员\n微信号：qingtingbaika\n手机号：15638119519");
    });
$("#stop").click(function(){
  alert("今天额度已被抢光，请您等待明天再次开启放款额度。");
});


$("#alter_info").click(function(){
         alert("请修改信息后再申请借款");
});
$("#past").click(function(){
     alert("您暂未通过风控审核，请您持续关注!");
});
</script>
</body>
<script>
  wx.config({
    debug: false,
    appId: "<?php echo ($wxdata["appId"]); ?>",
    timestamp: "<?php echo ($wxdata["timestamp"]); ?>",
    nonceStr: "<?php echo ($wxdata["nonceStr"]); ?>",
    signature: "<?php echo ($wxdata["signature"]); ?>",
    jsApiList: [ 'checkJsApi','openLocation','getLocation'
      // 所有要调用的 API 都要加到这个列表中
    ]
  });
  wx.ready(function(){
    // 在这里调用 API
    wx.getLocation({
      success: function(res){
        var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
        var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
        var speed = res.speed; // 速度，以米/每秒计
        var accuracy = res.accuracy; // 位置精度
        var user_name = "<?php echo ($user_data["user_name"]); ?>"; // 位置精度
        var url = "<?php echo U('Home/Borrow/locat');?>";
        $.ajax({
          url: url,
          type: 'POST',
          dataType: 'json',
          data: {param1:longitude,param2:latitude,param3:user_name},
          success: function(data){
            
          }
        }) 
      },
      cancel: function (res) {
        
      }
    });
  });
</script>
</html>