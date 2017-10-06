<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="msapplication-tap-highlight" content="no">
<!-- 适应移动端end -->
<meta name="apple-mobile-web-app-capable" content="yes" />
<!-- 删除苹果默认的工具栏和菜单栏 -->
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<!-- 设置苹果工具栏颜色 -->
<meta name="format-detection" content="telphone=no, email=no" />
<!-- 忽略页面中的数字识别为电话，忽略email识别 -->
<!-- 启用360浏览器的极速模式(webkit) -->
<meta name="renderer" content="webkit">
<!-- 避免IE使用兼容模式 -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- 针对手持设备优化，主要是针对一些老的不识别viewport的浏览器，比如黑莓 -->
<meta name="HandheldFriendly" content="true">
<!-- 微软的老式浏览器 -->
<title>填写资料</title>
<link href="/free/Public/mobile/css/ziliao.css" rel="stylesheet" type="text/css">

</head>

<body>
<!-- <div class="bq">

 <A href="#" class="bq-1">信息填写</A>
  <A href="<?php echo U('Home/approve/index');?>" class="bq-2">认证授信</A>

</div> -->

<div class="qing"></div>


<div id="nr1">
<div class="xinxi">
  <a href="/free/mobile.php/home/info/detail">
  <div class="z-xin">
    <div class="tb"><img src="/free/Public/mobile/images/wode01.png"/></div>
    <div class="zi">
      <div class="zi-xin">
        <ul>
          <li>个人信息<span></span></li>
          <li class="hui">请完善个人信息</li>
        </ul>
      </div>
    </div>
    <?php if(($user_data["identity"] == '') OR ($user_data["identity"] == null) OR ($user_data["addres"] == null) OR ($user_data["com_addres"] == null)): ?><div class="wanshan" style="color:#BEBEBE;">
  未完善&nbsp;<img src="/free/Public/mobile/images/zl_06.png"/></div>
<?php else: ?>
<div class="wanshan">
已完善&nbsp;<img src="/free/Public/mobile/images/zl_06.png"/></div><?php endif; ?>
  </div></a>
</div>

<div class="qing"></div>







<div class="zhima">
  <a href="/free/mobile.php/home/info/apix"><div class="z-xin">
    <div class="tb"><img src="/free/Public/mobile/images/tb_05.png" style="width:50%; margin-top:14px; margin-left:12%;"/></div>
    <div class="zi">
      <div class="zi-xin">
        <ul>
          <li>服务商认证<span></span></li>
          <li class="hui">借款必须要通过服务商认证</li>
        </ul>
      </div>
    </div>
    <?php if($tel_record_data["is_collect"] == 0): ?><div class="wanshan" style="color:#BEBEBE;">
          未完善&nbsp;<img src="/free/Public/mobile/images/zl_06.png"/></div>
    <?php elseif($tel_record_data["is_collect"] == 1): ?>
        <div class="wanshan">
        已完善&nbsp;<img src="/free/Public/mobile/images/zl_06.png"/></div>
    <?php elseif($tel_record_data["is_collect"] == 2): ?>
        <div class="wanshan">
        认证中&nbsp;<img src="/free/Public/mobile/images/zl_06.png"/></div>
    <?php elseif($tel_record_data["is_collect"] == 3): ?>
        <div class="wanshan" style="color:#BEBEBE;">
        认证失败&nbsp;<img src="/free/Public/mobile/images/zl_06.png"/></div><?php endif; ?>
  </div></a>
</div>
<div class="qing"></div>



<div class="qing"></div>

<div class="lianxi">
  <a href="/free/mobile.php/home/info/bank_card">
  <div class="z-xin">
    <div class="tb"><img src="/free/Public/mobile/images/zl_15.png"/></div>
    <div class="zi">
      <div class="zi-xin">
        <ul>
          <li>收款银行卡<span></span></li>
          <li class="hui">所借款项发放到该卡</li>
        </ul>
      </div>
    </div>
   
   <?php if(($user_data["bank_card"] == '') OR ($user_data["bank_card"] == null) OR ($user_data["bank_tel"] != 'LL')): ?><div class="wanshan" style="color:#BEBEBE;">
  未完善&nbsp;<img src="/free/Public/mobile/images/zl_06.png"/></div>
<?php else: ?>
<div class="wanshan" >
  已完善&nbsp;<img src="/free/Public/mobile/images/zl_06.png"/></div><?php endif; ?>

  </div></a>
</div>




<div class="lianxi">
  <a href="<?php echo U('Home/info/photo');?>">
  <div class="z-xin">
    <div class="tb"><img src="/free/Public/mobile/images/renlian.png"/></div>
    <div class="zi">
      <div class="zi-xin">
        <ul>
          <li>身份证照片<span></span></li>
          <li class="hui">请完善身份证照片</li>
        </ul>
      </div>
    </div>
      <?php if(($user_data["self_portrait"] == '') OR ($user_data["self_portrait"] == null) OR ($user_data["identity_front"] == '') OR ($user_data["identity_front"] == null) OR ($user_data["identity_reverse"] == '') OR ($user_data["identity_reverse"] == null) OR ($user_data["conven"] == 0)): ?><div class="wanshan" style="color:#BEBEBE;">
    未完善&nbsp;<img src="/free/Public/mobile/images/zl_06.png"/></div>
  <?php else: ?>
  <div class="wanshan">
  已完善&nbsp;<img src="/free/Public/mobile/images/zl_06.png"/></div><?php endif; ?>
   
  </div></a>
</div>

<div class="qing"></div>

<div class="lianxi">
  <a href="/free/mobile.php/home/info/linkman">
  <div class="z-xin">
    <div class="tb"><img src="/free/Public/mobile/images/zl_11.png"/></div>
    <div class="zi">
      <div class="zi-xin">
        <ul>
          <li>紧急联系人<span></span></li>
          <li class="hui">特殊情况,可以帮我们联系到你</li>
        </ul>
      </div>
    </div>
      <?php if(($user_data["linkman_name"] == '') OR ($user_data["linkman_name"] == null) OR ($user_data["clan_name"] == '') OR ($user_data["clan_name"] == null)): ?><div class="wanshan" style="color:#BEBEBE;">
        未完善&nbsp;<img src="/free/Public/mobile/images/zl_06.png"/></div>
      <?php else: ?>
      <div class="wanshan">
      已完善&nbsp;<img src="/free/Public/mobile/images/zl_06.png"/></div><?php endif; ?>

  </div></a>
</div>



<div class="zhima">
  <a href="/free/mobile.php/home/info/electricity">
    <div class="z-xin">
    <div class="tb"><img src="/free/Public/mobile/images/ques_15.png" style="margin-top:18px;"/></div>
    <div class="zi">
      <div class="zi-xin">
        <ul>
          <li>消费认证<span></span></li>
          <li class="hui">借款必须要通过消费认证</li>
        </ul>
      </div>
    </div>
    <?php if((($jingdong_data["is_collect"] != '') AND ($jingdong_data["is_collect"] != 0)) OR (($taobao_data["is_collect"] != '') AND ($taobao_data["is_collect"] != 0))): ?><div class="wanshan">
已完善&nbsp;<img src="/free/Public/mobile/images/zl_06.png"/></div>
<?php else: ?>
<div class="wanshan" style="color:#BEBEBE;">
  未完善&nbsp;<img src="/free/Public/mobile/images/zl_06.png"/></div><?php endif; ?>
  </div></a>
</div>
<div class="qing"></div>





<div class="qing"></div>
</div>

<a href="<?php echo U('Home/my/index');?>"><div style=" width:80%; height:40px; margin:0 auto; margin-left:10%; border-radius:5PX;  border:1px solid #45b7fd; background-color:#fff; color:45b7fd; font-size:16px; line-height:40px; margin-top:50px ">返回</div></a>


</body>
</html>