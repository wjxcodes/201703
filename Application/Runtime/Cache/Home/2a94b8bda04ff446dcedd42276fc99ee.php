<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录</title>
    <link rel="shortcut icon" href="favicon.ico"> <link href="/free/Public/css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="/free/Public/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="/free/Public/css/animate.min.css" rel="stylesheet">
    <link href="/free/Public/css/style.min862f.css?v=4.1.0" rel="stylesheet">
    <script>if(window.top !== window.self){ window.top.location = window.location;}</script>
</head>
<body class="gray-bg">
<div class="middle-box text-center loginscreen  animated fadeInDown">
</div>
<div class="middle-box text-center loginscreen  animated fadeInDown">
</div>
<div class="middle-box text-center loginscreen  animated fadeInDown">
</div>
<div class="middle-box text-center loginscreen  animated fadeInDown">
</div>
<div class="middle-box text-center loginscreen  animated fadeInDown">
</div>
    <div class="middle-box text-center loginscreen  animated fadeInDown">
        <div>
            <form class="m-t" role="form" action="<?php echo U('Home/Index/Index');?>" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="用户名" name="name" required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="密码" name="pwd" required="">
                </div>
                 <div class="form-group">
                    <input type="text" class="form-control" placeholder="验证码" name="pd" required="">
                </div>
                <img src="<?php echo U('Home/Index/verify');?>" alt="verify_code" onclick="this.src=this.src+'?'+Math.random();">
                <button type="submit" class="btn btn-primary block full-width m-b">登 录</button>
                </p>
            </form>
        </div>
    </div>
    <script src="/free/Public/js/jquery.min.js?v=2.1.4"></script>
    <script src="/free/Public/js/bootstrap.min.js?v=3.3.6"></script>
    <script type="text/javascript" src="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>
</body>
</html>