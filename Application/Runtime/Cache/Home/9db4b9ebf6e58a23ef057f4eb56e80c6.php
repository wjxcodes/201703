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
                    <h5>信息审核列表</h5>
                    <h5 style="margin-left:1000px;"><?php echo ($hint); ?></h5>
                </div>
                <div class="ibox-content">
    <table id="mytable" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">

           <tr style="color:#0000E3">
               <td>绑定手机号</td>
               <td>实名认证</td>
               <td>绑定邮箱</td>
               <td>密码认证</td>
               <td>手机绑定</td>
               <td>密保问题</td>
               <td>安全级别</td>
               <td>用户名</td>
           </tr>
           <tr style="color:#00BB00">
               <td style="color:red"><?php echo ($accountSafeInfo["bindMobile"]); ?></td>
               <td><?php echo ($accountSafeInfo["identityVerified"]); ?></td>
               <td><?php echo ($accountSafeInfo["loginEmail"]); ?></td>
               <td><?php echo ($accountSafeInfo["loginPasswdVerify"]); ?></td>
               <td><?php echo ($accountSafeInfo["mobileVerified"]); ?></td>
               <td><?php echo ($accountSafeInfo["pwdProtectedQuestion"]); ?></td>
               <td><?php echo ($accountSafeInfo["safeLevel"]); ?></td>
               <td><?php echo ($accountSafeInfo["username"]); ?></td>
          </tr>
          <tr style="color:#0000E3">
               <td>账户类型</td>
               <td>支付宝账户</td>
               <td>绑定邮箱</td>
               <td>绑定手机</td>
               <td>实名信息</td>
           </tr>
           <tr style="color:#00BB00">
               <td><?php echo ($bindAccountInfo["accountType"]); ?></td>
               <td><?php echo ($bindAccountInfo["alipatAccount"]); ?></td>
               <td><?php echo ($bindAccountInfo["bindEmail"]); ?></td>
               <td style="color:red"><?php echo ($bindAccountInfo["bindMobile"]); ?></td>
               <td style="color:red"><?php echo ($bindAccountInfo["identity"]); ?></td>
          </tr> 
          <tr style="color:#0000E3">
               <td>支付宝余额</td>
               <td>余额宝金额</td>
               <td>余额宝收益</td>
               <td>买家信用分</td>
               <td>信用等级</td>
               <td>成长值</td>
               <td>花呗余额</td>
               <td>花呗总额</td>
               <td>淘宝快捷支付限额</td>
               <td>淘宝等级</td>
               <td>淘宝账户名称</td>
               <td>天猫等级</td>
               <td>天猫分数</td>
               <td>天猫经验值</td>
           </tr>
           <tr style="color:#00BB00">
               <td><?php echo ($personalInfo["aliPayRemainingAmount"]); ?></td>
               <td><?php echo ($personalInfo["aliPaymFund"]); ?></td>
               <td><?php echo ($personalInfo["aliPaymFundProfit"]); ?></td>
               <td><?php echo ($personalInfo["buyerCreditPoint"]); ?></td>
               <td><?php echo ($personalInfo["creditLevel"]); ?></td>
               <td><?php echo ($personalInfo["growthValue"]); ?></td>
               <td><?php echo ($personalInfo["huabeiCanUseMoney"]); ?></td>
               <td><?php echo ($personalInfo["huabeiTotalAmount"]); ?></td>
               <td><?php echo ($personalInfo["taobaoFastRefundMoney"]); ?></td>
               <td><?php echo ($personalInfo["taobaoLevel"]); ?></td>
               <td><?php echo ($personalInfo["tianMaoAccountName"]); ?></td>
               <td><?php echo ($personalInfo["tianMaoLevel"]); ?></td>
               <td><?php echo ($personalInfo["tianMaoPoints"]); ?></td>
               <td><?php echo ($personalInfo["tianmaoExperience"]); ?></td>
       
          </tr> 
       </table>
      <table id="mytable" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">      
      <tr>
            <td style="color:#0000E3">收货地址<?php echo ($user_data["u_name"]); ?></td>
          </tr>
      <?php if(is_array($addrs)): $i = 0; $__LIST__ = $addrs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
            <td 
              <?php $u_name=mb_substr($vo,0,mb_strlen($user_data['u_name'],'utf-8'),'utf-8'); if($u_name==$user_data['u_name']){ echo "style='color:red'"; } ?>
            >
              <?php echo ($vo); ?>
           </td>
          </tr><?php endforeach; endif; else: echo "" ;endif; ?>
       </table>
     <!--  <span style="color:red">2017年成功消费金额：<?php echo ($money); ?></span> -->


      <span style="color:black;margin-left:100px;"><?php echo ($mytime); ?>至今成功消费金额：<span style='color:red'><?php echo ($lost_money); ?></span></span>
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
</body>
</html>