<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="user-scalable=no,maximum-scale=1.0, minmum-scale=1.0"/>
<title>支付宝还款</title>
<link href="/free/Public/mobile/css/zfb-huan.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/free/Public/mobile/js/jq-5.js"></script>
<script type="text/javascript" src="/free/Public/mobile/js/jquery-1.11.1.js"></script>
<script type="text/javascript"> 
    function jsCopy(){ 
        var e=document.getElementById("content");//对象是content 
        e.select(); //选择对象 
        document.execCommand("Copy"); //执行浏览器复制命令
       alert("已复制好，可贴粘。"); 
    } 
</script> 

<script type="text/javascript">
function fuzhi()
{
var Url2=document.getElementById("biao1");
Url2.select(); // 选择对象
document.execCommand("Copy"); // 执行浏览器复制命令
alert("已复制好，可贴粘。");
}
</script>


</head>

<body>
<div class="ding"></div>
<div class="nei" >
  <p class="shang"><span>请根据以下步骤进行支付宝还款</span></p>
  <ul>
    <li class="zuo"><div><span>1</span></div></li>
    <li class="you" style=" line-height:1.7;">进入支付宝首页，点击【转账】，选择【转到支付宝账户】，输入支付宝账户：<span><textarea  name="content"  id="content" style=" width:650px; height:40px; border:none; color:#ff8003; font-size:32px;">zhifubao@qingtingkeji.com.cn</textarea></span><br/>可通过账户全名“河南自由蜻蜓网络科技有限公司”进行校验<br/><a href="###" class="dian"><input type="button"  onclick="jsCopy()" value="复制账户"/></a></li>
  </ul>
  <div class="qing"></div>
  <ul>
    <li class="zuo"><div><span>2</span></div></li>
    <li class="you" style=" line-height:1.7;">点击【下一步】，输入转账金额，并添加备注：<span>  <textarea  id="biao1" style=" width:600px; height:40px; border:none; color:#ff8003; font-size:36px;"><?php echo ($user_data['u_name']); ?> <?php echo (session('name')); ?>还款</textarea></span><br/><a href="###" class="dian"><input type="button"  value="复制备注" onClick="fuzhi()"/></a></li>
  </ul>
  <div class="qing"></div>
  <ul>
    <li class="zuo"><div><span>3</span></div></li>
    <li class="you" style=" line-height:1.7;">点击【确认转账】，输入支付密码即可完成还款</li>
  </ul>
  <div class="qing"></div>
  <!-- <ul>
    <li class="zuo"><div><span>4</span></div></li>
    <li class="you">输入您还款的支付宝账户，以进行验证：</li>
  </ul> -->
  <div class="qing"></div>
</div>

<!-- <form action="/free/mobile.php/home/repay/zfb_pay" method="post" enctype="multipart/form-data" onSubmit="return login();" id="loginForm">
 <div class="tian"><input type="text" name="zhifubao" id="zhifubao" value="<?php echo ($user_data["zfbuser"]); ?>" placeholder="输入您还款的支付宝账户"/></div>
<div class="tijiao"><input type="submit" value="提交"/></div> 
</form> -->
<div class="ts-zi" style="color:#F00; text-align:left; margin-top:20px; font-size:3em; margin-left:5%; margin-right:5%; line-height:1.7;">
<div>提示：</div>
<div style="text-indent:2em;">1.请务必备注您的姓名、手机号，以免我们无法确定您的身份。</div>
<div style="text-indent:2em;">2.支付宝还款系统更新有延迟，(如若您在18点之后还款，我们将于第二天9点之前为您更新状态，其余时间正常更新状态。)请您耐心等待。</div>
</div>
<a href="zfb_img">
<div class="dibu">查看图文说明</div></a>

<script type="text/javascript">
  function login() {
      var zhifubao=$("#zhifubao").val();
      if(zhifubao == "" || zhifubao == null) {
         alert("支付宝账户不能为空");
         return false;
      }else{
        return true;
      }
    }
</script>


</body>
</html>