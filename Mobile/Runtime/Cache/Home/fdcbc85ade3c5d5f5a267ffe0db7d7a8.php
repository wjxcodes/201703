<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="user-scalable=no,maximum-scale=1.0, minmum-scale=1.0"/>
<title>修改密码</title>
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
                var url = "<?php echo U('Home/User/sendCode_forget');?>";
                $.ajax({
                    url:url,
                    type:'POST',
                    dataType:'json',
                    data:{tel:phoneCode},
                    success:function(data){
                        if(data=="该手机号未注册！"){
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
            })
        });
   </script>
</head>

<body>

<div class="logo"><img src="/free/Public/mobile/images/dl_03.jpg"/></div>
<form action="<?php echo U('Home/User/forget');?>" method="post">
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
<li class="sr-kuang"><input type="password" name="pwd" placeholder="请输入新密码"/></li>
</ul>
<div class="qing"></div>
<div class="anjian"><input type="submit" value="确定"/></div>
</form>
<div class="di-wen"  style=" margin-top:404px;">小额贷款找<span>蜻蜓</span>，放款<span>迅速</span>我最行</div>
</body>
</html>