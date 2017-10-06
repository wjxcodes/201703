<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="user-scalable=no,maximum-scale=1.0, minmum-scale=1.0"/>
<title>注册</title>
<link href="/free/Public/mobile/css/qt.css" rel="stylesheet" type="text/css">
<script src="/free/Public/mobile/js/jq-5.js" type="text/javascript"></script>
    <script>
    window.onload = function() { 

        <?php if($now_time!=''){?>
            var count = <?php echo $now_time;?>;
            var countdown = setInterval(CountDown, 1000);
            function CountDown() {
                $("#dx-yzm").attr("disabled", true);
                $("#dx-yzm").val( + count + " 秒后可重新获取");
                if (count == 0) {
                    $("#dx-yzm").val("获取手机验证码").removeAttr("disabled");
                    clearInterval(countdown);
                }
                count--;
            }
        <?php }?>

    }; 

        $(function(){
            $('#dx-yzm').click(function(){
                $("#dx-yzm").attr("disabled", true);
                var phoneCode = $("#phoneCode").val();
                var url = "<?php echo U('Home/User/sendCode');?>";
                if(phoneCode){
                    if((/^1[34578]\d{9}$/.test(phoneCode))){
                        $.ajax({
                        url:url,
                        type:'POST',
                        dataType:'json',
                        data:{tel:phoneCode},
                        success:function(data){
                            if(data=="该手机号已经被注册！"){
                                $("#dx-yzm").removeAttr("disabled");
                                alert(data);
                            }else{
                                var count = 90;
                                var countdown = setInterval(CountDown, 1000);
                                function CountDown() {
                                    $("#dx-yzm").attr("disabled", true);
                                    $("#dx-yzm").val( + count + " 秒后可重新获取");
                                    if (count == 0) {
                                        $("#dx-yzm").val("获取手机验证码").removeAttr("disabled");
                                        clearInterval(countdown);
                                    }
                                    count--;
                                }
                            }
                        }
                        });
                    }else{
                        alert("请输入正确的手机号！");
                    }
                }else{
                    alert("请输入手机号！");
                }
                
            })
        });
   </script>
</head>

<body>

<div class="logo"><img src="/free/Public/mobile/images/dl_03.jpg"/></div>
<form action="<?php echo U('Home/User/register');?>" method="post" onsubmit="return submitTest();">
<ul class="sr">
<li class="sr-tu"><img src="/free/Public/mobile/images/mm_03.png"/></li>
<li class="sr-kuang"><input type="number" name="name" id="phoneCode" placeholder="请输入手机号码" value="" /></li>
</ul>
<div class="qing"></div>
<ul class="yzm">
<li class="zuo">
<div class="yzm-tu" ><img src="/free/Public/mobile/images/mm_06.png"/></div>
<div class="yzm-sr"><input type="number" name="code" placeholder="输入手机验证码"/></div>
<div class="qing"></div>
</li>
<li class="you"><input type="button" id="dx-yzm" value="获取短信验证码"></li>
</ul>
<div class="qing"></div>
<ul class="sr">
<li class="sr-tu"><img src="/free/Public/mobile/images/mm_08.png"/></li>
<li class="sr-kuang"><input type="password" name="pwd" placeholder="请输入登录密码"/></li>
</ul>
<!-- <ul class="sr">
<li class="sr-tu"><img src="/free/Public/mobile/images/yq_03.png"/></li>
<li class="sr-kuang"><input type="text" name="bonuscode" placeholder="请输入邀请码（选填）"/></li>
</ul> -->
<div class="qing"></div>
<div class="qing"></div>
<div class="anjian"><input type="submit" value="确定"/></div>

    <div style=" width:92%; height:100px; margin:0 auto; font-size:1.1em; color:#333; margin-top:10px; text-align:left; margin-top:50px; font-size:42px;"><div style="width:100%; text-align:left;"><input id="agreement" type="checkbox" id="xuan" checked="checked"   style=" width:30px; height:30px;"/>&nbsp;&nbsp;阅读并同意<a href="/free/mobile.php/home/user/agreement" style="color:#45b7fd;">《蜻蜓白卡注册服务协议》</a><a href="/free/mobile.php/home/user/message" style="color:#45b7fd;">《信息收集及使用规则》</a></div>

<script type="text/javascript">
    function submitTest(){
        var agreement=$("#agreement").is(':checked');
        if(!agreement){
          alert("请同意以下服务协议！");
          return false;
        }
    }
</script>


</form>
<div class="di-wen"  style=" margin-top:300px;">小额贷款找<span>蜻蜓</span>，放款<span>迅速</span>我最行</div>
</body>
</html>