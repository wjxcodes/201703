<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="user-scalable=no,maximum-scale=1.0, minmum-scale=1.0"/>
<title>征信授权书</title>
<style>
p{ padding:0; margin:0;}
h1{ text-align:center;}
.indent{ text-indent:2em;}
.xinxi{width:auto; height:auto; border-bottom:1px solid #000000; padding:0 20px; font-weight:bold;}
.bold{ font-weight:bold;}
.di{ width:auto; text-align:right;}
.tx{text-align:left; text-indent:2em;}
.wu-di{ padding:0;}
</style>
</head>

<body style="margin:0; padding:0; font-size:18px;">
<div style=" margin:30px;">
<h1 style="margin-bottom:40px;">征信授权书</h1>

<p class="indent">本人:<span class="xinxi"><?php echo ($user_data['u_name']); ?></span>身份证号:<span class="xinxi"><?php echo ($data['identity']); ?></span>知晓并同意<span class="bold">河南自由蜻蜓网络科技有限公司</span>委托第三方征信机构调查本人信息，包括但不限于个人基本信息、借贷交易信息、银行卡交易信息、电商交易信息、公用事业信息、央行征信报告。所获取的信息，仅在此笔借贷业务的贷前审批和贷后管理工作中使用。<span class="bold">河南自由蜻蜓网络科技有限公司</span>将对所获取的信息妥善进行保管，除为本人提供信审服务/借款资金的合作方外，未经本人授权，不得向其他机构或个人公开、编辑或透露信息内容。</p><br/>
<p class="indent">本人知晓并同意<span class="bold">河南自由蜻蜓网络科技有限公司</span>向第三方征信机构提交本人在此笔借贷业务中产生的相关信息，包括但不限于个人基本信息、借款申请信息、借款合同信息以及还款行为信息，并记录在征信机构的个人信用信息数据库中。</p><br/>
<p class="indent">本人同意若本人出现不良还款行为，<span class="bold">河南自由蜻蜓网络科技有限公司</span>按合同所留联系方式对本人进行提醒并告知，本人若仍未履行还款义务，<span class="bold">河南自由蜻蜓网络科技有限公司</span>可将本人的不良还款信息提交至第三方征信机构，记录在征信机构的个人信用信息库中。</p><br/>
<p class="indent">本人已被明确告知不良还款信息一旦记录在第三方征信机构的个人信用信息数据库中，在日后的经济活动中对本人可能产生的不良影响。</p><br/>
<p class="indent">若本人所约定的联络方式产生变化，本人将及时通知<span class="bold">河南自由蜻蜓网络科技有限公司</span>，若因未通知造成的相应损失，本人愿承担相应责任。</p><br/>
本人知晓第三方征信机构包含但不限于：北京安融惠众征信有限公司，鹏元征信有限公司，同盾科技有限公司。</p><br/>
<p class="indent">特别提示：</p>
<p class="indent">为了保障您的合法权益，您应当阅读并遵守本授权书。请您务必审慎阅读，并充分理解本授权书的全部内容。若您不接受本授权书的任何条款，请您立即停止授权。</p><br/>
<p class="indent">特别声明：</p>
<p class="indent">本授权书经接受后即时生效，且效力具有独立性，不因相关业务合同或条款无效或被撤销而无效或失效，本授权一经做出，便不可撤销。</p><br/>
<p class="indent">本人已知悉本授权书全部内容（特别是加粗字体内容）的含义及因此产生的法律效力，自愿作出以上授权。本授权书是本人真实意思表示，本人同意承担由此带来的一切法律后果。</p><br/>

<div class="di">
<p class="tx" style="margin-bottom:20px;">授权人：<span class="xinxi wu-di"><?php echo ($user_data['u_name']); ?></span></p>
<p class="tx">日&nbsp;&nbsp;&nbsp;期：<span class="xinxi wu-di"><?php echo ($data['time']); ?></span></p>
</div>

</div>
</body>
</html>