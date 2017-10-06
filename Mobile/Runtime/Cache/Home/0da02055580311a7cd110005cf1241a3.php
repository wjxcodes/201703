<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="user-scalable=no,maximum-scale=1.0,minmum-scale=1.0"/>
<title>身份证照片</title>
<style>
/*共同样式*/

* {
	margin: 0;
	padding: 0;
}
.bb {
	border: #F00 1px solid;
}
body {
	color: #000;
	background: #f8f8f8;
	font-family: Verdana, Arial, sans-serif;
	font-size: 24px;
	text-align: center;
	font-family: "Microsoft YaHei", 微软雅黑
}
textarea {
	word-wrap: break-word;
	word-break: break-all;
	padding: 0px;
}
li {
	list-style-type: none;
}
img {
	border: 0 none;
}
a {
	color: #333;
	text-decoration: none;
}
.qing {
	clear: both;
	height: 0px;
	width: 100%;
	font-size: 1px;
	line-height: 0px;
	visibility: hidden;
	overflow: hidden;
}
/*共同样式 end*/


/*a  upload */
.a-upload {
	background: url(/free/Public/mobile/images/ZP_03.png) center no-repeat;
	width: 160px;
	height: 160px;
	line-height: 160px;
	position: relative;
	cursor: pointer;
	color: #888;
	margin-top: 50px;
	border-radius: 80px;
	overflow: hidden;
	display: inline-block;
 *display: inline;
 *zoom: 1
}
.a-upload input {
	position: absolute;
	font-size: 160px;
	right: 0;
	top: 0;
	opacity: 0;
	filter: alpha(opacity=0);
	cursor: pointer
}
.ding {
	width: 100%;
	height: 48px;
	line-height: 48px;
	margin-bottom: 30px;
	font-size: 34px;
	background-color: #F2F2F2;
	color:#45B7FD;
}
.zi {
	width: 50%;
	height: auto;
	float: left;
  margin-top: 48px;
	font-size: 40px;
}
.zi1 {
	width: 50%;
	height: auto;
	float: left;
  margin-top: 48px;
	font-size: 40px;
}
.sf-bj {
	width: 400px;
	height: 260px;
	float: left;
	margin-left: 5%;
	margin-top: 30px;
	background: url(/free/Public/mobile/images/sfz_03.png) center no-repeat;
}
.sf-bj1 {
	width: 400px;
	height: 260px;
	float: right;
	margin-right: 5%;
	margin-top: 30px;
	background: url(/free/Public/mobile/images/sfz_06.png) center no-repeat;
}
.sc-zi {
	font-size: 40px;
	text-align: center;
	margin-top: 60px; margin-bottom: 30px;
    
}
.sc-pz {
	width: 90%;
	height: auto;
	margin: 0 auto;
	background-color: #fff;
	padding: 20px 0;
}
.sc-pz-dian {
	margin: 0 auto;
	margin-top: -30px;
}
.tijiao input{ width:80%; height:100px; margin-top:80px; border: none; background-color:#45b7fd; border-radius:10px; color:#fff; font-size:40px;}
.fanhui input{ width:80%; height:100px; margin-top:60px; border: none; background-color:#fff; border-radius:10px; color:#333; font-size:40px; border:2px solid #45b7fd;}



.tkdj{ position: fixed; left: 0; top: 0; width: 100%; height:100% ; zoom: 1;
background-color: #666; z-index: 999; filter: alpha(opacity=30); /*IE滤镜，透明度30%*/
-moz-opacity: 0.3; /*Firefox私有，透明度30%*/ opacity: 0.3;/*其他，透明度30%*/
}
.tkk{ background: #fff; margin:0 auto; margin-left:5%; margin-top:40%; width:90%; height:760px; border-radius:10px;
overflow: auto; position: absolute; top: 0; left: 0; z-index: 9999999;}

.tkk .ezi{ padding:0; font-size:34px; height:auto;}
.tkk .ezi .anjian-1{ margin-top:40px;}
.tkk .ezi .anjian-1 input{ float:left; margin-left:5%; width:40%; height:80px; border:none; border-radius:10px; background-color:#45b7fd; font-size:36px; color:#fff; box-shadow:0 10px 0px #48a7e2; /*底边阴影*/}
.tkk .ezi .anjian-1 .aj-12{ float:right; margin-right:5%;}
.ezi .ts{ font-size:36px; color:#45b7fd;}
.ezi .ts-tx{width:100%; height:50px; line-height:50px; font-size:36px; padding:5px; border:none; border:2px solid #45b7fd; border-radius:5px; text-align:center; margin-bottom:15px;}


.wb{ width:auto; height:40px; margin-left:10%;; margin-top:20px;}
.wb .wb-shang .wb-zi{ float:left; width:68px; height:68px; font-size:36px; text-align:center; line-height:68px; border:1px solid #45b7fd; color:#45b7fd; }
.wb .wb-xia .wb-t{ float:left; width:68px; height:68px; font-size:36px; text-align:center; line-height:68px; border:none; border:1px solid #45b7fd;}

</style>
<script src="/free/Public/mobile/js/jquery.min.js"></script>
</head>

<body >
<form action="<?php echo U('Home/info/upload');?>" method="post" enctype="multipart/form-data">
<div class="ding" style="font-size:34px; height:90px; line-height:90px;" >此照片仅用于蜻蜓白卡借款协议，不会泄露您的个人信息</div>
<div class="zi">身份证正面</div>
<div class="zi1">身份证反面</div>

<label>
<img class="sf-bj" id="img1"
<?php if($user_data["identity_front"] == ''): ?>src="/free/Public/mobile/images/s1_03.png"
<?php else: ?>
src="/free/Uploads/<?php echo ($user_data["identity_front"]); ?>"<?php endif; ?>
 /> 
<input type="file" id="photo1" name='identity_front' capture="camera" accept="image/*"  style="display:none"/>
</label>
<label>
<img class="sf-bj1" id="img2" 
<?php if($user_data["identity_reverse"] == ''): ?>src="/free/Public/mobile/images/s1_06.png"
<?php else: ?>
src="/free/Uploads/<?php echo ($user_data["identity_reverse"]); ?>"<?php endif; ?>
> 
<input type="file" id="photo2" name='identity_reverse' capture="camera" accept="image/*" style="display:none"/>
</label>
<div class="qing"></div>
<label>
<div class="sc-zi">手持身份证正面</div>
<div class="sc-pz"><img id="img3" 
<?php if($user_data["self_portrait"] == ''): ?>src="/free/Public/mobile/images/tp_10.jpg"
<?php else: ?>
src="/free/Uploads/<?php echo ($user_data["self_portrait"]); ?>"<?php endif; ?>
	 style="width: 90%;
	height: 416px;"/>
  <div class="sc-pz-dian"> <a href="javascript:;" class="a-upload">
    <input type="file" id="photo3" name='self_portrait' capture="camera" accept="image/*"/>
    </a> </div>
</div>
</label>

<?php if($loan_data["is_loan"] == 1): else: ?>
<div class="tijiao">
	<input type="submit" value="提交" />
</div><?php endif; ?>
</form>

<a href="/free/mobile.php/home/info/index"><div class="fanhui"><input type="button" value="返回" /></div></a>


<!--  照片判断 -->
<?php if(($user_data['self_portrait'] == '') OR ($user_data['identity_front'] == '') OR ($user_data['identity_reverse'] == '')): else: ?>
<!--  是否进行认证-->
 <?php if($user_data['conven'] == 1): else: ?>
<form action="/free/mobile.php/home/info/photo" method="post" enctype="multipart/form-data" onSubmit="return login();" id="loginForm">
<div class="tkdj" ></div>
<div class="tkk" >
  <div class="ezi">
    <div style="margin-top:20px; margin-bottom:40px; font-size:42px;">请将以下文字输入文本框内</div>
<!-- 一行 -->
<div style=" width:90%; margin:0 auto; font-size:36px; color:#F00;">本人自愿通过蜻蜓白卡，向其合作银行申请贷款，遵守合约，同意上报征信。</div><br/>
    <textarea placeholder="请在文本框内输入上方文字" style="width:90%; height:140px; font-size:36px; padding:10px; border:1px solid #45b7fd;border:1px solid #45b7fd; " name="conven"></textarea>


<!-- 三行 -->
  <!--   <div><span style="color:#45b7fd; font-size:42px;">本人自愿通过蜻蜓白卡</span><br/><input type="text"  style="width:90%; height:60px; margin:0 auto; font-size:42px; line-height:80px; border:1px solid #45b7fd; border-radius:5px; margin-bottom:20px; text-align:center;" name="conven1"/></div>
  <div><span style="color:#45b7fd; font-size:42px;">向其合作银行申请贷款</span><br/><input type="text"  style="width:90%; height:60px; margin:0 auto; font-size:42px; line-height:80px; border:1px solid #45b7fd; border-radius:5px; margin-bottom:20px; text-align:center;" name="conven2"/></div>
  <div><span style="color:#45b7fd; font-size:42px;">遵守合约，同意上报征信</span><br/><input type="text"  style="width:90%; height:60px; margin:0 auto; font-size:42px; line-height:80px; border:1px solid #45b7fd; border-radius:5px; text-align:center;" name="conven3"/></div> -->



<!-- 单字 -->

    <!-- <div class="wb">
      <ul class="wb-shang">
        <li class="wb-zi">本</li>
        <li class="wb-zi">人</li>
        <li class="wb-zi">自</li>
        <li class="wb-zi">愿</li>
        <li class="wb-zi">通</li>
        <li class="wb-zi">过</li>
        <li class="wb-zi">蜻</li>
        <li class="wb-zi">蜓</li>
        <li class="wb-zi">白</li>
        <li class="wb-zi">卡</li>
      </ul>
      <div class="qing"></div>
      <div class="wb-xia" style="margin-top:10px;">
    
    <input type="text"   class="wb-t" name="conven1"/>
    <input type="text"   class="wb-t" name="conven2"/>
    <input type="text"   class="wb-t" name="conven3"/>
    <input type="text"   class="wb-t" name="conven4"/>
    <input type="text"   class="wb-t" name="conven5"/>
    <input type="text"   class="wb-t" name="conven6"/>
    <input type="text"   class="wb-t" name="conven7"/>
    <input type="text"   class="wb-t" name="conven8"/>
    <input type="text"   class="wb-t" name="conven9"/>
    <input type="text"   class="wb-t" name="conven10"/>
    
    </div>
    </div>
    <div class="qing"></div>
    <div class="wb">
      <ul class="wb-shang">
        <li class="wb-zi">向</li>
        <li class="wb-zi">其</li>
        <li class="wb-zi">合</li>
        <li class="wb-zi">作</li>
        <li class="wb-zi">银</li>
        <li class="wb-zi">行</li>
        <li class="wb-zi">申</li>
        <li class="wb-zi">请</li>
        <li class="wb-zi">贷</li>
        <li class="wb-zi">款</li>
      </ul>
      <div class="qing"></div>
      <div class="wb-xia" style="margin-top:10px;">
    
    <input type="text"   class="wb-t" name="conven11"/>
    <input type="text"   class="wb-t" name="conven12"/>
    <input type="text"   class="wb-t" name="conven13"/>
    <input type="text"   class="wb-t" name="conven14"/>
    <input type="text"   class="wb-t" name="conven15"/>
    <input type="text"   class="wb-t" name="conven16"/>
    <input type="text"   class="wb-t" name="conven17"/>
    <input type="text"   class="wb-t" name="conven18"/>
    <input type="text"   class="wb-t" name="conven19"/>
    <input type="text"   class="wb-t" name="conven20"/>
       
    </div>
    </div>
    <div class="qing"></div>
    <div class="wb">
      <ul class="wb-shang">
        <li class="wb-zi">遵</li>
        <li class="wb-zi">守</li>
        <li class="wb-zi">合</li>
        <li class="wb-zi">约</li>
        <li class="wb-zi">同</li>
        <li class="wb-zi">意</li>
        <li class="wb-zi">上</li>
        <li class="wb-zi">报</li>
        <li class="wb-zi">征</li>
        <li class="wb-zi">信</li>
      </ul>
      <div class="qing"></div>
      <div class="wb-xia" style="margin-top:10px;">
        
          <input type="text"   class="wb-t" name="conven21"/>
          <input type="text"   class="wb-t" name="conven22"/>
          <input type="text"   class="wb-t" name="conven23"/>
          <input type="text"   class="wb-t" name="conven24"/>
          <input type="text"   class="wb-t" name="conven25"/>
          <input type="text"   class="wb-t" name="conven26"/>
          <input type="text"   class="wb-t" name="conven27"/>
          <input type="text"   class="wb-t" name="conven28"/>
          <input type="text"   class="wb-t" name="conven29"/>
          <input type="text"   class="wb-t" name="conven30"/>
       
      </div>
    </div> -->
    <div class="qing"></div>
    <div class="anjian-1">
      <input type="submit" value="确认"  class="aj-11"/>
      <A href="/free/mobile.php/home/info/index">
      <input type="button" value="取消" class="aj-12"/>
      </A> </div>
  </div>
</form><?php endif; endif; ?>


<script type="text/javascript">
$(document).keydown(function(event){
          var keyboard=event.which;
          if(keyboard==8){
            var dete=$("input:focus").val();
            if(dete==''){
                $("input:focus").prev().focus();
            }
            $("input:focus").val('');
          }
      });
    $(":text").bind('input propertychange',function(){
        var val=$(this).val();
        var pattern=/^[\u4E00-\u9FA5]{1,6}$/;
        var zifu=pattern.test(val);
        if(zifu){
          for (var i = 0; i < val.length; i++) {

             $(this).next().focus();
          };
           
        }
    });


function login(){
	var new_conven=conven.replace(/[&\|\\\*,，。.\-]/g,"");
	if(new_conven=="本人自愿通过蜻蜓白卡向其合作银行申请贷款遵守合约同意上报征信"){
		return true;
	}
	alert("输入错误!");
	return false;
}

$(function() {
   $("#photo2").click(function () {             //隐藏了input:file样式后，点击头像就可以本地上传
      $("#photo2").on("change",function(){
        var objUrl = getObjectURL(this.files[0]) ;  //获取图片的路径，该路径不是图片在本地的路径
        if (objUrl) {
          $("#img2").attr("src", objUrl) ;      //将图片路径存入src中，显示出图片
        }
     });
   });
 });

$(function() {
   $("#photo1").click(function () {             //隐藏了input:file样式后，点击头像就可以本地上传
      $("#photo1").on("change",function(){
        var objUrl = getObjectURL(this.files[0]) ;  //获取图片的路径，该路径不是图片在本地的路径
        if (objUrl) {
          $("#img1").attr("src", objUrl) ;      //将图片路径存入src中，显示出图片
        }
     });
   });
 });

$(function() {
   $("#photo3").click(function () {             //隐藏了input:file样式后，点击头像就可以本地上传
      $("#photo3").on("change",function(){
        var objUrl = getObjectURL(this.files[0]) ;  //获取图片的路径，该路径不是图片在本地的路径
        if (objUrl) {
          $("#img3").attr("src", objUrl) ;      //将图片路径存入src中，显示出图片
        }
     });
   });
 });

   function getObjectURL(file) {
   var url = null ;
   if (window.createObjectURL!=undefined) { // basic
     url = window.createObjectURL(file) ;
   } else if (window.URL!=undefined) { // mozilla(firefox)
     url = window.URL.createObjectURL(file) ;
   } else if (window.webkitURL!=undefined) { // webkit or chrome
     url = window.webkitURL.createObjectURL(file) ;
   }
   return url ;
 }

</script>
</body>
</html>