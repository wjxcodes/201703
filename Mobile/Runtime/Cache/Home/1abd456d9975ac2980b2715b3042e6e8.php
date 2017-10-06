<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
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
<title>紧急联系人</title>
<link href="/free/Public/mobile/css/geren.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/free/Public/mobile/js/jq-5.js"></script>
<script type="text/javascript" src="/free/Public/mobile/js/jquery-1.11.1.js"></script>
<style>
  .xin .z1{ width:35%;}
  .xin .z2{ width:65%;}
</style>

</head>

<body style="background-color:#FFF;" >
<form action="/free/mobile.php/home/info/linkman" method="post" enctype="multipart/form-data" onSubmit="return login();" id="loginForm">

<div class="ps" style="background-color:#F2F2F2;" >
<div style="text-align:center; font-size:12px; color:#45B7FD;">请您务必保证姓名与电话一致，且有一定的通话时长</div>
</div>


<div class="xl">
  <ul class="xin">
    <li class="z1" >第一联系人：</li>
    <li class="z2">
      <select  id="select_k1" class="xla_k" name="clan_relation" style="background: none;">
        <option value="父亲" <?php if($user_data["clan_relation"] == '父亲'): ?>selected = "selected"<?php else: endif; ?>>父亲</option>
        <option value="母亲" <?php if($user_data["clan_relation"] == '母亲'): ?>selected = "selected"<?php else: endif; ?>>母亲</option>
        <option value="儿子" <?php if($user_data["clan_relation"] == '儿子'): ?>selected = "selected"<?php else: endif; ?>>儿子</option>
        <option value="女儿" <?php if($user_data["clan_relation"] == '女儿'): ?>selected = "selected"<?php else: endif; ?>>女儿</option>
        <option value="兄弟" <?php if($user_data["clan_relation"] == '兄弟'): ?>selected = "selected"<?php else: endif; ?>>兄弟</option>
        <option value="兄弟" <?php if($user_data["clan_relation"] == '姐妹'): ?>selected = "selected"<?php else: endif; ?>>姐妹</option>
        <option value="兄弟" <?php if($user_data["clan_relation"] == '配偶'): ?>selected = "selected"<?php else: endif; ?>>配偶</option>
        
      </select>
    </li>
  </ul>
</div>


<div class="qing"></div>


<div class="xm">
  <ul class="xin">
    <li class="z1">姓名：</li>
    <li class="z2" ><input type="text" placeholder="紧急联系人姓名"  <?php if($loan_data["is_loan"] == 1): ?>readonly="readonly"<?php else: endif; ?> name="clan_name" id="clan_name" value="<?php echo ($user_data["clan_name"]); ?>"/></li>
  </ul>
</div>

<div class="qing"></div>

<div class="sfz">
  <ul class="xin">
    <li class="z1">联系方式：</li>
    <li class="z2">
      <input type="text" placeholder="紧急联系人电话"  <?php if($loan_data["is_loan"] == 1): ?>readonly="readonly"<?php else: endif; ?> name="clan_tel" id="clan_tel" value="<?php echo ($user_data["clan_tel"]); ?>"/></li>
  </ul>
</div>
<div style=" width:100%; height:10px; background-color:#ccc;"></div>

<div class="xl">
  <ul class="xin">
    <li class="z1">第二联系人：</li>
    <li class="z2">
      <select  id="select_k1" class="xla_k" name="guanxi" style="background: none;">
        <option value="同学" <?php if($user_data["relation"] == '同学'): ?>selected = "selected"<?php else: endif; ?>>同学</option>
        <option value="亲戚" <?php if($user_data["relation"] == '亲戚'): ?>selected = "selected"<?php else: endif; ?>>亲戚</option>
        <option value="同事" <?php if($user_data["relation"] == '同事'): ?>selected = "selected"<?php else: endif; ?>>同事</option>
        <option value="朋友" <?php if($user_data["relation"] == '朋友'): ?>selected = "selected"<?php else: endif; ?>>朋友</option>
        <option value="其他" <?php if($user_data["relation"] == '其他'): ?>selected = "selected"<?php else: endif; ?>>其他</option>
      </select>
    </li>
  </ul>
</div>


<div class="qing"></div>


<div class="xm">
  <ul class="xin">
    <li class="z1">姓名：</li>
    <li class="z2" ><input type="text" placeholder="紧急联系人姓名"  <?php if($loan_data["is_loan"] == 1): ?>readonly="readonly"<?php else: endif; ?> name="lianxirenxingming" id="lianxirenxingming" value="<?php echo ($user_data["linkman_name"]); ?>"/></li>
  </ul>
</div>

<div class="qing"></div>

<div class="sfz">
  <ul class="xin">
    <li class="z1">联系方式：</li>
    <li class="z2">
      <input type="text" placeholder="紧急联系人电话"  <?php if($loan_data["is_loan"] == 1): ?>readonly="readonly"<?php else: endif; ?> name="lianxifangshi" id="lianxifangshi" value="<?php echo ($user_data["linkman_tel"]); ?>"/></li>
  </ul>
</div>


<div class="qing"></div>

<?php if($loan_data["is_loan"] == 1): else: ?>
<div class="bc">
  <input type="submit" value="提交信息">
</div><?php endif; ?>

<div class="dibu">
  <div class="db-tu"><img src="/free/Public/mobile/images/grxx_11.png"/></div>
  <div class="db-zi">银行级数据加密防护</div>
</div>

<p>&nbsp;</p>
</form>
<script type="text/javascript">
    function login() {
        var lianxirenxingming=$("#lianxirenxingming").val();
        var lianxifangshi=$("#lianxifangshi").val();
        var clan_tel=$("#clan_tel").val();
        var clan_name=$("#clan_name").val();
        if(lianxirenxingming=="" || lianxirenxingming==null){
            alert("第二紧急联系人姓名不能为空");
            return false;
        }else if(!(/^[\u4E00-\u9FA5A-Za-z]+$/.test(lianxirenxingming))){
             alert("第二紧急联系人姓名格式错误");
             return false;
        }else if(lianxifangshi==""||lianxifangshi==null){
            alert("第二紧急联系人联系方式不能为空");
            return false;
        }else if(!(/^1[34578]\d{9}$/.test(lianxifangshi))){
            alert("请输入正确的手机号");
            return false;
        }else if(clan_name=="" || clan_name==null){
            alert("第一紧急联系人姓名不能为空");
            return false;
        }else if(!(/^[\u4E00-\u9FA5A-Za-z]+$/.test(clan_name))){
             alert("第一紧急联系人姓名格式错误");
             return false;
        }else if(clan_tel==""||clan_tel==null){
            alert("第一紧急联系人联系方式不能为空");
            return false;
        }else if(!(/^1[34578]\d{9}$/.test(clan_tel))){
            alert("请输入正确的手机号");
            return false;
        }else if(clan_tel==lianxifangshi){
            alert("两个紧急联系人手机号不能相同");
            return false;
        }else if(clan_name==lianxirenxingming){
            alert("两个紧急联系人姓名不能相同");
            return false;
        }else{
          return true;
        }
    }
</script>
</body>
</html>