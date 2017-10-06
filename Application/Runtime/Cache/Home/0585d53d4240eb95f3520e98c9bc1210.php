<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <title>主页</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="/free/Public/css/page.css?v=3.3.6" rel="stylesheet">
    <link href="/free/Public/css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="/free/Public/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="/free/Public/css/animate.min.css" rel="stylesheet">
    <link href="/free/Public/css/style.min862f.css?v=4.1.0" rel="stylesheet">
    <link href="/free/Public/layui/src/css/layui.css">
    <script src="/free/Public/layui/src/layui.js"></script>
</head>
<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
    <div id="wrapper">
        <!--左侧导航开始-->
            <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i>
        </div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <span><img alt="image" class="img-circle" src="/free/Public/img/free.jpg" /></span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                               <span class="block m-t-xs"><strong class="font-bold">蜻蜓科技</strong></span>
                                <span class="text-muted text-xs block"><?php  echo session('aname');?></span>
                                </span>
                        </a>
                    </div>
                    <div class="logo-element">蜻蜓
                    </div>
                </li>
                
                <?php if(is_array($base_auth)): $i = 0; $__LIST__ = $base_auth;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; switch($vo): case "Manager": ?><li>
                                <a href="<?php echo U("Home/$vo/index");?>">
                                    <i class="fa fa-home"></i>
                                    <span class="nav-label">管理员</span>
                                </a>
                            </li><?php break;?>

                        <?php case "Register": ?><li>
                                <a href="<?php echo U("Home/$vo/index");?>">
                                    <i class="fa fa-home"></i>
                                    <span class="nav-label">注册用户</span>
                                </a>
                            </li><?php break;?>

                        <?php case "Infoaudit": ?><li>
                                <a href="<?php echo U("Home/$vo/index");?>">
                                    <i class="fa fa-home"></i>
                                    <span class="nav-label">用户审核</span>
                                </a>
                            </li><?php break;?>

                        <?php case "Credit": ?><li>
                                <a href="<?php echo U("Home/$vo/credit");?>">
                                    <i class="fa fa-home"></i>
                                    <span class="nav-label">芝麻信息</span>
                                </a>
                            </li><?php break;?>

                        <?php case "Examine": ?><li>
                                <a href="<?php echo U("Home/$vo/index");?>">
                                    <i class="fa fa-home"></i>
                                    <span class="nav-label">打款审核</span>
                                </a>
                            </li><?php break;?>

                        <?php case "Pending": ?><li>
                                <a href="<?php echo U("Home/$vo/index");?>">
                                    <i class="fa fa-home"></i>
                                    <span class="nav-label">待放款</span>
                                </a>
                            </li><?php break;?>

                        <?php case "Already": ?><li>
                                <a href="<?php echo U("Home/$vo/index");?>">
                                    <i class="fa fa-home"></i>
                                    <span class="nav-label">已放款</span>
                                </a>
                            </li><?php break;?>
                        
                        <?php case "Search": ?><li>
                                <a href="<?php echo U("Home/$vo/index");?>">
                                    <i class="fa fa-home"></i>
                                    <span class="nav-label">用户搜索</span>
                                </a>
                            </li><?php break;?>

                        <?php case "Renewal": ?><li>
                                <a href="#">
                                    <i class="fa fa-home"></i>
                                    <span class="nav-label">续期还款</span>
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U('Home/Renewal/renewal');?>" data-index="0">快钱续期</a>
                                    </li>
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U('Home/Renewal/index');?>" data-index="0">支付宝续期</a>
                                    </li>
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U('Home/Renewal/ll_pay');?>" data-index="0">连连续期</a>
                                    </li>
                                </ul>
                            </li><?php break;?>
                        
                        


                        <?php case "Repayment": ?><li>
                                <a href="#">
                                    <i class="fa fa-home"></i>
                                    <span class="nav-label">还款记录</span>
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U('Home/Repayment/refund');?>" data-index="0">快钱还款</a>
                                    </li>
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U('Home/Repayment/zfb');?>" data-index="0">支付宝还款</a>
                                    </li>
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U('Home/Repayment/ll_pay');?>" data-index="0">连连还款</a>
                                    </li>
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U('Home/Repayment/countnum');?>" data-index="0">催收回款</a>
                                    </li>
                                </ul>
                            </li><?php break;?>

                        <?php case "Pre": ?><li>
                                <a href="#">
                                    <i class="fa fa-home"></i>
                                    <span class="nav-label">逾期未还</span>
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U('Home/Pre/index');?>" data-index="0">预催收</a>
                                    </li>
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U('Home/rong/index');?>" data-index="0">容时期</a>
                                    </li>
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U('Home/Overdue/day_To_day',array('day1'=>4,'day2'=>10000000));?>" data-index="0">逾期4天以上</a>
                                    </li>
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U('Home/Overdue/blackList');?>" data-index="0">添加黑名单</a>
                                    </li>
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U('Home/Overdue/area');?>" data-index="0">地区信息</a>
                                    </li>
                                </ul>
                            </li><?php break;?>


                        <?php case "Withhold": ?><li>
                                <a href="#">
                                    <i class="fa fa-home"></i>
                                    <span class="nav-label">连连代扣</span>
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U('Home/Withhold/index');?>" data-index="0">预约代扣</a>
                                    </li>
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U('Home/Withhold/implement');?>" data-index="0">执行代扣</a>
                                    </li>
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U('Home/Withhold/querySignHtml');?>" data-index="0">绑卡查询</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-home"></i>
                                    <span class="nav-label">连连代付</span>
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U('Home/Withhold/viwedf');?>" data-index="0">执行代付</a>
                                    </li>
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U('Home/Withhold/queryviwedf');?>" data-index="0">代付查询</a>
                                    </li>
                                </ul>
                            </li><?php break;?>


                        <?php case "Statis": ?><li>
                                <a href="<?php echo U("Home/$vo/index");?>"><i class="fa fa-picture-o"></i> <span class="nav-label">统计信息</span></a>
                            </li><?php break;?>

                        <?php case "Mention": ?><li>
                                <a href="<?php echo U("Home/$vo/index");?>"><i class="fa fa-picture-o"></i> <span class="nav-label">配置中心</span></a>
                            </li><?php break;?>

                        <?php case "Interest": ?><li>
                                <a href="<?php echo U("Home/$vo/index");?>"><i class="fa fa-picture-o"></i> <span class="nav-label">降息统计</span></a>
                            </li><?php break;?>
                        <?php case "Bad": ?><li>
                                <a href="#">
                                    <i class="fa fa-home"></i>
                                    <span class="nav-label">催收部</span>
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U("Home/$vo/group");?>" data-index="0">我的任务</a>
                                    </li>
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U("Home/$vo/loanrecord");?>" data-index="0">放款记录</a>
                                    </li>
                                    <li>
                                        <a class="J_menuItem" href="<?php echo U("Home/$vo/publics");?>" data-index="0">公共区域</a>
                                    </li>
                                </ul>
                            </li><?php break; endswitch; endforeach; endif; else: echo "" ;endif; ?>
            

            </ul>
        </div>
    </nav>
        <!--左侧导航结束-->
        </html>
 
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i></a>
                </div>
            </nav>
        </div>
        <div class="row content-tabs">
            <a href="<?php echo U('Home/index/logout');?>" class="roll-nav roll-right J_tabExit"><i class="fa fa fa-sign-out"></i> 退出</a>
        </div>
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span style="color:red;Font-size:32px;"><?php echo ($subscribe); ?></span>
                    <span style="color:red;Font-size:32px;"><?php echo ($nit); ?></span>
                    <input type="button" value="处理操作" onclick="Ceng()">

                    <input type="button" value="紧急联系人" id="link">
                    
                    <a href="/free/index.php/Home/Survey/to_excel?id=<?php echo $_GET['id'];?>"><input type="button" value="导出"></a>
                    <h5 style="margin-left:1000px;color:red;Font-size:32px;"><?php echo ($hint); ?></h5>
                    <h5 style="float:right;color:red;Font-size:32px;"><?php echo ($t_hint); ?></h5>
                </div>
                <div class="ibox-content">



<div id="ceng" style="position:absolute;z-index:2;left:0;top:0;right:0;background-color:#000;filter:alpha(opacity=50);margin:1px 1px;display:none;width:100%;height:100%;text-align:center;"> 
</div>
<div id="close" style="position:absolute !important;left:30%;top:0px;z-index:3;border:1px solid #b2b1b3;background-color:#fff;margin:100px auto;padding:0px;display:none;width:1000px;height:500px;">
<a href="#" onclick="closeCeng()" style="text-align:right; float:right; margin:20px; color:#b2b1b3">关闭</a>
    <div class="mycenter-right" id="mycenter-li">
       <form action="<?php echo U('Home/Infoaudit/change',array('id'=>$loan_data['user_id'],'status'=>'1'));?>" method="post"> 
            不通过原因<br /><br /> 
            <label><input name="weiname" type="checkbox" value="1" />微信昵称</label> 
            <label><input name="phone" type="checkbox" value="1" />前50个通话记录有效手机号</label> 
            <label><input name="inter" type="checkbox" value="1" />在网时长</label> 
            <label><input name="link" type="checkbox" value="1" />紧急联系人号码在通话记录内</label>
            <label><input name="bank" type="checkbox" value="1" />贷款被叫不超过两家，每家不超过两次</label> 
            <label><input name="stop" type="checkbox" value="1" />无澳门通话记录，且110通话次数不超过三次</label> 
            <label><input name="month" type="checkbox" value="1" />近两个月有消费记录</label> 
            <label><input name="year" type="checkbox" value="1" />一年消费超过500元</label>  
            <label><input name="agreement" type="checkbox" value="1" />注册、淘宝、支付宝三电话一致</label>  
            <label><input name="contain" type="checkbox" value="1" />家庭、工作至少有一条在淘宝收货地址中（具体到街道）</label> 
            <label><input name="idnumber" type="checkbox" value="2" />身份证号</label> 
            <label><input name="photo" type="checkbox" value="3" />照片</label> 
            <label><input name="sesame" type="checkbox" value="4" />芝麻认证</label> 
            <label><input name="bankcar" type="checkbox" value="5" />银行卡</label> 
            <br> 
            <label><input type="submit" value="提交" =""></label> 
        </form>     
    </div>
</div>


<?php if($borrow == ''): ?><h5>第一次借款</h5>
<?php else: ?>
    <h5>借款<?php echo ($borrow); ?>次</h5><?php endif; ?>

<?php if($frist_overdue == '最后一次逾期'): ?><h5 style="color:red"><?php echo ($frist_overdue); ?></h5>
<?php else: ?>
    <h5><?php echo ($frist_overdue); ?></h5><?php endif; ?>

<?php if($new_record_data == ''): else: ?>
<table border="1px" id="mytable" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
    <tr>
        <td>应还时间时间</td>
        <td>还款时间</td>
        <td>逾期天数</td>
        <td>逾期费用</td>
    </tr>
<?php if(is_array($new_record_data)): $i = 0; $__LIST__ = $new_record_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
        <td><?php echo (date("Y-m-d H:i:s",$vo["should_time"])); ?></td>
        <td><?php echo (date("Y-m-d H:i:s",$vo["repayment_time"])); ?></td>
        <td><?php echo ($vo["day"]); ?></td>
        <td><?php echo ($vo["overdue_money"]); ?></td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table><?php endif; ?>
<!-- 芝麻信息开始  -->
<span style="Font-size:32px;">查看信息   </span><span style="margin-left:100px;Font-size:32px;">单号：<?php echo ($loan_data["loan_order"]); ?></span>
<table border="1px" id="mytable" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
    <tr>
        
        <td>手机号</td>
        <td>姓名</td>
        <td>身份证</td>
        <td>银行卡</td>
        <td>紧急联系人姓名</td>
        <td>紧急联系人手机号</td>
        <td rowspan="4" width="100px" id="confirm"  ><img id="img_i" src="/free/Public/mobile/images/color.png" width="100%" height="100%" style="display:none"></td>
    </tr>
    <tr>
        
        <td style="color:#000000"><?php echo ($user_data['user_name']); ?></td>
        <td style="color:#000000"><?php echo ($user_data['u_name']); ?></td>
        <td  style="color:#000000"><?php echo ($user_data['identity']); ?></td>
        <td  style="color:#000000"><?php echo ($user_data['bank_card']); ?></td>
        
        <td  style="color:#000000"><?php echo ($user_data['linkman_name']); ?></td>
        <td  style="color:#000000"><?php echo ($user_data['linkman_tel']); ?></td>
    </tr>
    <tr>
        <td>芝麻分</td>
        <td>微信昵称</td>
        <td>网龄</td>
           <td>京东消费</td>
           <td>京东真实姓名</td>
        <td>京东登录名</td>
    </tr>
    <tr>
        <td style="color:#000000"><?php echo ($credit_data['zm_score']); ?></td>
        <td><?php echo ($weixin['nickname']); ?></td>
        <td ><?php echo ($apix_data["phoneInfo"]["netAge"]); ?></td>
        <td><?php echo ($response["consumeHistroy"]["aYearTotalConsumeMoney"]); ?></td>
        <td><?php echo ($response["baseInfo"]["realName"]); ?></td>
        <td><?php echo ($response["jd_login_name"]); ?></td>
    </tr>
    <tr>
        <td>微信地址</td>
        <td>前十通话平均值</td>
        <td>微信地理位置</td>
        <td>紧急联系人姓名</td>
        <td>紧急联系人手机号</td>
        <td>通话30次以上</td>
    </tr>
    <tr>
        <td><?php echo ($weixin['country']); echo ($weixin['province']); echo ($weixin['city']); ?></td>
        <td><?php echo ($tel_valid_time); ?></td>
        <td><?php echo ($location_data); ?></td>
        <td><?php echo ($user_data['clan_name']); ?></td>
        <td><?php echo ($user_data['clan_tel']); ?></td>
        <td <?php if($apix_return['tel_num'] <= 5): ?>style="color:red;Font-size:32px;"<?php endif; ?>>
            <?php echo ($apix_return['tel_num']); ?>
        </td>
    </tr>
    <tr>
        <td>联系人一</td>
        <td>联系人二</td>
    </tr>
    <tr>
        <?php if(($people1 == '未找到此人')): ?><td><?php echo ($people1); ?></td>
        <?php else: ?>
            <td style="color:#FF0000"><?php echo ($people1); ?></td><?php endif; ?>
        <?php if(($people2 == '未找到此人')): ?><td><?php echo ($people2); ?></td>
        <?php else: ?>
            <td style="color:#FF0000"><?php echo ($people2); ?></td><?php endif; ?>
    </tr>
</table>
<h1>验证信息(APIX)</h1>
<table border="1px" id="mytable" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">

</table>
<table border="1px" id="mytable" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
<tr>
    <td>淘宝绑定手机号</td>
    <td>支付宝手机绑定</td>
    <td>花呗总额</td>
    <td><?php echo ($time_year); ?>淘宝消费</td>
    <td>话费当前余额</td>
    <td>互通电话量</td>
    <td>有效号码（50个内）</td>
    
    <td rowspan="6" width="100px" id="confirm1"  ><img id="img_i1" src="/free/Public/mobile/images/color.png" width="100%" height="100%" style="display:none"></td>
</tr>
<tr>
    
    <td 
        <?php $taobao_tel=substr($accountSafeInfo['bindMobile'],0,3).substr($accountSafeInfo['bindMobile'],-4); $user_name_tel=substr($user_name,0,3).substr($user_name,-4); if($taobao_tel==$user_name_tel){ }else{ echo "style='color:red;Font-size:32px;'"; } ?>
    > <?php echo ($accountSafeInfo["bindMobile"]); ?></td>
    <td 
        <?php $zfb_tel=substr($bindAccountInfo['bindMobile'],0,3).substr($bindAccountInfo['bindMobile'],-3); $user_name_tel=substr($user_name,0,3).substr($user_name,-3); if($zfb_tel==$user_name_tel){ }else{ echo "style='color:red;Font-size:32px;'"; } ?>

    ><?php echo ($bindAccountInfo["bindMobile"]); ?></td>
    <td <?php if($personalInfo['huabeiTotalAmount'] <= 0): ?>style="color:red;Font-size:32px;"<?php endif; ?>
       ><?php echo ($personalInfo["huabeiTotalAmount"]); ?></td>

    <td <?php if($lost_money <= 500): ?>style="color:red;Font-size:32px;"<?php endif; ?>
        ><?php echo ($lost_money); ?></td>
    

    <td 
    <?php if($apix_data['phoneInfo']['balance'] <= 0): ?>style="color:red;Font-size:32px;"<?php endif; ?>
    ><?php echo ($apix_data["phoneInfo"]["balance"]); ?></td>
    <td 
    <?php if($apix_return['interflow'] <= 10): ?>style="color:red;Font-size:32px;"<?php endif; ?>
    ><?php echo ($apix_return["interflow"]); ?></td>
    <td 
    <?php if($apix_return['valid'] < 30): ?>style="color:red;Font-size:32px;"<?php endif; ?>
    ><?php echo ($apix_return["valid"]); ?></td>


</tr>


<!-- 紧急联系人  开始-->
<?php if($apix_return['linkman_tel'] == ''): ?><tr>
        <td>紧急联系人通话</td>
        <td colspan="4" style="color:red">没有紧急联系人通话记录</td>
    </tr>
<?php else: ?>
    <tr>
        <td rowspan="2">紧急联系人通话</td>
        <td>号码</td>
        <td>通话时长</td>
        <td>通话次数</td>
        <td>号码归属地</td>
        <td>被叫次数</td>
        <td>主叫次数</td>

    </tr>
    <tr>
         <td><?php echo ($apix_return["linkman_tel"]["phoneNo"]); ?></td>
        <td><?php echo ($apix_return["linkman_tel"]["connTime"]); ?></td>
        <td><?php echo ($apix_return["linkman_tel"]["connTimes"]); ?></td>
        <td><?php echo ($apix_return["linkman_tel"]["belongArea"]); ?></td>
        <td><?php echo ($apix_return["linkman_tel"]["calledTimes"]); ?></td>
        <td><?php echo ($apix_return["linkman_tel"]["callTimes"]); ?></td>

    </tr><?php endif; ?>



<?php if($user_data['clan_tel'] == ''): ?><tr>
        <td>新紧急联系人通话</td>
        <td colspan="4" >未填写</td>
    </tr>
<?php else: ?>
    <?php if($apix_return['clan_tel'] == ''): ?><tr>
            <td>新紧急联系人通话</td>
            <td colspan="4" style="color:red">没有紧急联系人通话记录</td>
        </tr>
    <?php else: ?>
        <tr>
            <td rowspan="2">新紧急联系人通话</td>
            <td>号码</td>
            <td>通话时长</td>
            <td>通话次数</td>
            <td>号码归属地</td>
            <td>被叫次数</td>
            <td>主叫次数</td>

        </tr>
        <tr>
            <td><?php echo ($apix_return["clan_tel"]["phoneNo"]); ?></td>
            <td><?php echo ($apix_return["clan_tel"]["connTime"]); ?></td>
            <td><?php echo ($apix_return["clan_tel"]["connTimes"]); ?></td>
            <td><?php echo ($apix_return["clan_tel"]["belongArea"]); ?></td>
            <td><?php echo ($apix_return["clan_tel"]["calledTimes"]); ?></td>
            <td><?php echo ($apix_return["clan_tel"]["callTimes"]); ?></td>

        </tr><?php endif; endif; ?>





<!-- 紧急联系人  结束-->


<!-- 地址 -->
<tr>
    <td >
        <p>现居地址</p>
        <p>公司地址</p>
    </td>
    <td colspan="4" >
        <p><?php echo ($user_data["addres"]); ?></p>
        <p><?php echo ($user_data["com_addres"]); ?></p>
    </td>
</tr>
<!-- 地址 -->

<!--收货地址开始-->
<?php if($addrs == ''): ?><tr>
        <td >收货地址</td>
        <td colspan="4" style="color:red;">未找到匹配地址</td>
    </tr>
<?php else: ?>
    
        <tr>
            <td>收货地址</td>
            <td colspan="4" >
                <?php if(is_array($addrs)): $i = 0; $__LIST__ = $addrs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><p><?php echo ($vo); ?></p><?php endforeach; endif; else: echo "" ;endif; ?>
            </td>
        </tr><?php endif; ?>
<!--收货地址结束-->

<!-- 京东地址为空  -->

<?php if($response["orderAddress"] == ''): ?><tr>
        <td >京东收货地址</td>
        <td colspan="4" style="color:red;">未找到京东匹配地址</td>
    </tr>
<?php else: ?>
    
        <tr>
            <td>京东收货地址</td>
            <td colspan="4" >
                <?php if(is_array($response["orderAddress"])): $i = 0; $__LIST__ = $response["orderAddress"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><p><?php echo ($vo); ?></p><?php endforeach; endif; else: echo "" ;endif; ?>
            </td>
        </tr><?php endif; ?>
<tr>
        <td >京东绑定银行卡</td>
       
            <td>
                <?php if(is_array($response["bindBankCards"])): $i = 0; $__LIST__ = $response["bindBankCards"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><p><?php echo ($vo); ?></p><?php endforeach; endif; else: echo "" ;endif; ?>
            </td>
       
</tr>

<!-- 京东地址为空  -->


<tr>
    <td>欺诈分</td>
    <?php if(($credit_data['is_matched'] == 0)): else: ?>
    <td >行业关注名单</td><?php endif; ?>
    <td >四要素信息</td>
    <td >紧急联系人二要素</td>
    <td >新紧急联系人二要素</td>
    <?php if($three_ele != ''): ?><td >三要素信息</td><?php endif; ?>
    
    
    
</tr>

<tr>
    <td  <?php if($credit_data['ivs_score'] < 85): ?>style="color:red;Font-size:32px;"<?php endif; ?>><?php echo ($credit_data['ivs_score']); ?></td>
    <?php if(($credit_data['is_matched'] == 0)): else: ?>
    <td  style="color:red;Font-size:32px;">在</td><?php endif; ?>
<!-- 四要素信息 -->
    <td>
        <?php if($match == ''): ?><span style="color:blue;Font-size:32px;">匹配</span>
        <?php else: ?>
        <?php if(is_array($match)): $i = 0; $__LIST__ = $match;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><p style="color:red"><?php echo ($vo); ?></p><?php endforeach; endif; else: echo "" ;endif; endif; ?>
    </td>
<!--  二要素信息 -->
    <td>
        <?php if($ele == ''): ?><span style="color:blue;Font-size:32px;">匹配</span>
        <?php else: ?>
        <?php if(is_array($ele)): $i = 0; $__LIST__ = $ele;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><p style="color:red"><?php echo ($vo); ?></p><?php endforeach; endif; else: echo "" ;endif; endif; ?>
    </td>


    <!--  亲属联系人信息 -->
    <td>
        <?php if($clan == ''): ?><span style="color:blue;Font-size:32px;">匹配</span>
        <?php elseif($clan == 1): ?>
            <span>未填写</span>
        <?php else: ?>
        <?php if(is_array($clan)): $i = 0; $__LIST__ = $clan;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><p style="color:red"><?php echo ($vo); ?></p><?php endforeach; endif; else: echo "" ;endif; endif; ?>
    </td>
<!-- 三要素信息 -->
    <?php if($three_ele != ''): ?><td >
        <?php if(is_array($three_ele)): $i = 0; $__LIST__ = $three_ele;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><p style="color:red"><?php echo ($vo); ?></p><?php endforeach; endif; else: echo "" ;endif; ?>
    </td><?php endif; ?>
</tr>

<?php if(($credit_data['is_matched'] == 0)): else: ?>
    <tr>
        <td>行业关注名单详情</td>
        <td colspan="7" style="color:red"><?php echo ($credit_data['focus_detail']); ?></td>
    </tr><?php endif; ?>

<?php if(($hit_detail == '')): else: ?>
    <tr>
        <td>欺诈关注清单</td>
        <td colspan="7" style="color:red"><?php echo ($hit_detail); ?></td>
    </tr><?php endif; ?>
</table>

                       
<h1>人工审核信息   (身份号：<?php echo ($user_data['identity']); ?>)</h1>
<table>
    <tr>
        <td style="height:auto;width:33%; float: left;"><img id="target1" style=" width: 100%; height: auto; margin: 0 auto;" src="/free/Uploads/<?php echo ($user_data['identity_front']); ?>"></td>
        <td style="height:auto;width:33%; float: left; margin: 0 auto;"><img id="target2" style=" width: 100%; height: auto; margin: 0 auto;"  src="/free/Uploads/<?php echo ($user_data['identity_reverse']); ?>"></td>
        <td style="height:auto;width:33%; float: right;"><img id="target3" style=" width: 100%; height: auto; margin: 0 auto;"  src="/free/Uploads/<?php echo ($user_data['self_portrait']); ?>"></td>
    </tr>
    
</table>

<!--  Apix 通讯开始  -->

<h1>未知通话</h1>
<table id="mytable" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
    <tr>
        <td>号码</td>
        <td>通话时长</td>
        <td>通话次数</td>
        <td>号码归属地</td>
        <td>被叫次数</td>
        <td>主叫次数</td>
        <td>号码标识</td>
      </tr>
    <?php if(is_array($apix_return["special"])): $i = 0; $__LIST__ = $apix_return["special"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
        <td><?php echo ($vo["phoneNo"]); ?></td>
        <td><?php echo ($vo["connTime"]); ?></td>
        <td><?php echo ($vo["connTimes"]); ?></td>
        <td><?php echo ($vo["belongArea"]); ?></td>
        <td style="color:red"><?php echo ($vo["calledTimes"]); ?></td>
        <td ><?php echo ($vo["callTimes"]); ?></td>
        <td ><?php echo ($vo["identifyInfo"]); ?></td>
      </tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>


<h1>通话记录分析</h1>
    <table   id="mytable" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
      <tr>
        <td>号码</td>
        <td>通话时长</td>
        <td>通话次数</td>
        <td>号码归属地</td>
        <td>被叫次数</td>
        <td>主叫次数</td>
        <td>号码标识</td>
      </tr>
    <?php if(is_array($apix_data["callRecordsInfo"])): $i = 0; $__LIST__ = array_slice($apix_data["callRecordsInfo"],0,20,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
        <td 
        <?php preg_match('/^1[34578]\d{9}$/A',$vo['phoneNo'],$match); if(empty($match)){ echo "style='color:red'"; } ?>
            ><?php echo ($vo["phoneNo"]); ?></td>
        <td><?php echo ($vo["connTime"]); ?></td>

        <td <?php if($vo["connTimes"] >= 30): ?>style="color:blue"<?php endif; ?>>

            <?php echo ($vo["connTimes"]); ?>

        </td>
        <td><?php echo ($vo["belongArea"]); ?></td>
        <td 
        <?php if($vo["identifyInfo"] != ''): ?>style="color:red"<?php endif; ?>
        ><?php echo ($vo["calledTimes"]); ?></td>
        <td 
        <?php if($vo["identifyInfo"] != ''): ?>style="color:red"<?php endif; ?>
        ><?php echo ($vo["callTimes"]); ?></td>
        <td style="color:red"><?php echo ($vo["identifyInfo"]); ?></td>
      </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>
<!--  Apix 通讯结束  -->
      
<?php if(is_array($arr)): $i = 0; $__LIST__ = $arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><table id="mytable" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
    <?php if(is_array($vo['orderProducts'])): $i = 0; $__LIST__ = $vo['orderProducts'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
            <td style="color:#0000E3">产品名称:</td>
        <td><?php echo ($v["productName"]); ?></td>
            <td style="color:#0000E3">产品价格:</td>
        <td><?php echo ($v["productPrice"]); ?></td>
       </tr><?php endforeach; endif; else: echo "" ;endif; ?>
          <tr style="color:#0000E3">
            <td>订单日期</td>
            <td>订单状态</td>
            <td>订单总额</td>
            <td>订单号</td>
          </tr>
          <tr>
            <td><?php echo ($vo["businessDate"]); ?></td>
            <td><?php echo ($vo["orderStatus"]); ?></td>
            <td><?php echo ($vo["orderTotalPrice"]); ?></td>
            <td><?php echo ($vo["orderid"]); ?></td>
          </tr>
       </table><?php endforeach; endif; else: echo "" ;endif; ?>


                </div>
            </div>
        </div>
    </div>
    <!--右侧部分结束-->
</div>
<script src="/free/Public/js/jquery.min.js?v=2.1.4"></script>
<script src="/free/Public/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/free/Public/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/free/Public/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/free/Public/js/plugins/layer/layer.min.js"></script>
<script src="/free/Public/js/hplus.min.js?v=4.1.0"></script>
<script src="/free/Public/js/plugins/pace/pace.min.js"></script>


<script type="text/javascript">
    $("#link").click(function(){
        if(confirm("确认紧急联系人不在通讯录吗？")){
           window.location.href="/free/index.php/Home/Survey/linksend?id=<?php echo $_GET['id']; ?>";
        }
    });
</script>



<script>






    window.onload = function(){
      var current = 0;
      document.getElementById('target1').onclick = function(){
          current = (current+90)%360;
          this.style.transform = 'rotate('+current+'deg)';
      document.getElementById('target2').onclick = function(){
          current = (current+90)%360;
          this.style.transform = 'rotate('+current+'deg)';
          }
      document.getElementById('target3').onclick = function(){
          current = (current+90)%360;
          this.style.transform = 'rotate('+current+'deg)';
          }
      }
    };
</script>
<script type="text/javascript">
    $("#confirm").click(function(){
        var color=$("#img_i").css("display");
        if(color=="none"){
            $("#img_i").css("display","block");
        }else{
            $("#img_i").css("display","none");
        }
    });
    $("#confirm1").click(function(){
        var color=$("#img_i1").css("display");
        if(color=="none"){
            $("#img_i1").css("display","block");
        }else{
            $("#img_i1").css("display","none");
        }
    });
</script>
<script type="text/javascript">
    function Ceng() {
        document.getElementById('ceng').style.display = 'block';
        document.getElementById('close').style.display = 'block';
        return false;
    }
    function closeCeng() {
        document.getElementById('ceng').style.display = 'none';
        document.getElementById('close').style.display = 'none';
        return false;
         
    }
     
</script>
</body>
</html>