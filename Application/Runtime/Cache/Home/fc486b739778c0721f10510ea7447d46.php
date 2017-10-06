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
                    <h5 style="margin-left:1000px;color:red"><?php echo ($hint); ?></h5>
                </div>
                <div class="ibox-content">

    <table id="mytable" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
      <tr>

        <td>登记手机号</td>
        <td>入网时间</td>
        <td>认证实名</td>
        <td>认证身份证号</td>
        <td>手机号</td>
        <td>登记邮箱</td>
        <td>当前余额</td>
        <td>会员等级</td>
        <td>积分值</td>
        <td>网龄</td>
        <td>最早通话时间</td>
        <td>最近通话时间</td>
      </tr>
      <tr>

        <td style="color:red"><?php echo ($apix_data["basicInfo"]["phoneNo"]); ?></td>
        <td><?php echo ($apix_data["phoneInfo"]["inNetDate"]); ?></td>
        <td style="color:red"><?php echo ($apix_data["phoneInfo"]["realName"]); ?></td>
        <td style="color:red"><?php echo ($apix_data["phoneInfo"]["certNo"]); ?></td>
        <td style="color:red"><?php echo ($apix_data["phoneInfo"]["phoneNo"]); ?></td>
        <td><?php echo ($apix_data["phoneInfo"]["email"]); ?></td>
        <td><?php echo ($apix_data["phoneInfo"]["balance"]); ?></td>
        <td><?php echo ($apix_data["phoneInfo"]["vipLevel"]); ?></td>
        <td><?php echo ($apix_data["phoneInfo"]["pointValue"]); ?></td>
        <td style="color:red"><?php echo ($apix_data["phoneInfo"]["netAge"]); ?></td>
        <td><?php echo ($apix_data["phoneInfo"]["firstCallDate"]); ?></td>
        <td><?php echo ($apix_data["phoneInfo"]["lastCallDate"]); ?></td>
      </tr>
      <tr>
        
        <td>运营商是否实名</td>
        <td>运营商实名是否与登记人一致</td>
        <td>是否出现长时间关机（5天以上无短信记录，无通话记录）</td>
        <td>申请人信息是否命中网贷黑名单</td>
        <td>是否出现法院相关号码呼叫</td>
        <td>身份证号码有效性</td>
      </tr>
      <tr>
       
        <td style="color:red"><?php echo ($apix_data["deceitRisk"]["phoneIsAuth"]); ?></td>
        <td><?php echo ($apix_data["deceitRisk"]["samePeople"]); ?></td>
        <td><?php echo ($apix_data["deceitRisk"]["longTimePowerOff"]); ?></td>
        <td><?php echo ($apix_data["deceitRisk"]["inBlacklist"]); ?></td>
        <td><?php echo ($apix_data["deceitRisk"]["calledByCourtNo"]); ?></td>
         <td><?php echo ($apix_data["deceitRisk"]["certNoIsValid"]); ?></td>
      </tr>
    </table>
    <br/>
<span style="margin-left:100px;color:blue"> 总钱数<?php echo ($money); ?></span>
<span style="margin-left:100px;color:blue"> 6个月平均<?php echo (ceil($money/6)); ?></span>
<span style="margin-left:100px;color:blue"> 5个月平均<?php echo (ceil($money/5)); ?></span>
<span style="margin-left:100px;color:blue"> 4个月平均<?php echo (ceil($money/4)); ?></span>
<span style="margin-left:100px;color:blue"> 3个月平均<?php echo (ceil($money/3)); ?></span>
<span style="margin-left:100px;color:blue"> 2个月平均<?php echo (ceil($money/2)); ?></span>
 <br/>
<h1 style="color:red">运营商消费分析</h1>
    <table id="mytable" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
      <tr>
        <td>月份</td>
        <td>主叫时间</td>
        <td>被叫时间</td>
        <td>短信数</td>
        <td>话费充值额</td>
      </tr>
    <?php if(is_array($apix_data["consumeInfo"])): $i = 0; $__LIST__ = $apix_data["consumeInfo"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
        <td><?php echo ($vo["month"]); ?></td>
        <td><?php echo ($vo["callTime"]); ?></td>
        <td><?php echo ($vo["calledTime"]); ?></td>
        <td><?php echo ($vo["totalSmsNumber"]); ?></td>
        <td 
        <?php if($vo["payMoney"] == 0): ?>style="color:red"
        <?php else: ?>
            style="color:blue"<?php endif; ?>
        ><?php echo ($vo["payMoney"]); ?></td>
      </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>

    <h1 style="color:red">通话短信需求分析</h1>
    <table id="mytable" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
      <tr>
        <td>通话次数</td>
        <td>对方标识</td>
        <td>月份</td>
        <td>对方号码</td>
        <td>短信条数</td>
      </tr>
    <?php if(is_array($apix_data["specialCallInfo"])): $i = 0; $__LIST__ = $apix_data["specialCallInfo"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
        <td><?php echo ($vo["connTimes"]); ?></td>
        <td><?php echo ($vo["identityInfo"]); ?></td>
        <td><?php echo ($vo["month"]); ?></td>
        <td><?php echo ($vo["phoneNo"]); ?></td>
        <td><?php echo ($vo["smsTimes"]); ?></td>
      </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>



<h1 style="color:red">通话记录分析</h1>
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
    <?php if(is_array($apix_data["callRecordsInfo"])): $i = 0; $__LIST__ = array_slice($apix_data["callRecordsInfo"],0,100,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
        <td 
        <?php preg_match('/^1[34578]\d{9}$/A',$vo['phoneNo'],$match); if(empty($match)){ echo "style='color:red'"; } ?>
            ><?php echo ($vo["phoneNo"]); ?></td>
        <td><?php echo ($vo["connTime"]); ?></td>
        <td><?php echo ($vo["connTimes"]); ?></td>
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
<h1 style="color:red">联系人位置分析</h1>
    <table border="1px">
      <tr>
        <td>地区</td>
        <td>号码数量</td>
        <td>主叫次数</td>
        <td>主叫时间</td>
        <td>被叫次数</td>
        <td>被叫时间</td>
        <td>占比</td>
      </tr>
    <?php if(is_array($apix_data["contactAreaInfo"])): $i = 0; $__LIST__ = array_slice($apix_data["contactAreaInfo"],0,5,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
        <td><?php echo ($vo["area"]); ?></td>
        <td><?php echo ($vo["totalNumber"]); ?></td>
        <td><?php echo ($vo["callTimes"]); ?></td>
        <td><?php echo ($vo["callTime"]); ?></td>
        <td><?php echo ($vo["calledTimes"]); ?></td>
        <td><?php echo ($vo["calledTime"]); ?></td>
        <td><?php echo ($vo["percent"]); ?></td>
      </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>
<!-- <h1 style="color:red">短信记录分析</h1>
    <table id="mytable" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
      <tr>
        <td>号码</td>
        <td>号码归属地</td>
        <td>条数</td>
        <td>号码标识</td>
      </tr>
    <?php if(is_array($apix_data["messageRecordsInfo"])): $i = 0; $__LIST__ = $apix_data["messageRecordsInfo"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
        <td><?php echo ($vo["phoneNo"]); ?></td>
        <td><?php echo ($vo["belongArea"]); ?></td>
        <td><?php echo ($vo["totalSmsNumber"]); ?></td>
        <td><?php echo ($vo["identifyInfo"]); ?></td>
      </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table> -->

<!-- <h1 style="color:red">（关机详情）</h1>
    <table id="mytable" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
      <tr>
        <td>关机开始日期</td>
        <td>关机结束日期</td>
        <td>关机天数</td>
      </tr>
    <?php if(is_array($apix_data["phoneOffInfos"])): $i = 0; $__LIST__ = $apix_data["phoneOffInfos"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
        <td><?php echo ($vo["beginDate"]); ?></td>
        <td><?php echo ($vo["endDate"]); ?></td>
        <td><?php echo ($vo["days"]); ?></td>
      </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>
         -->



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