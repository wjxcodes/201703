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
     <script src="/free/Public/js/jquery.min.js?v=2.1.4"></script>
    <script src="/free/Public/js/bootstrap.min.js?v=3.3.6"></script>
    <script src="/free/Public/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/free/Public/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="/free/Public/js/plugins/layer/layer.min.js"></script>
    <script src="/free/Public/js/hplus.min.js?v=4.1.0"></script>
    <script src="/free/Public/js/plugins/pace/pace.min.js"></script>


<script type="text/javascript" src="/free/Public/zhexiantu/jqplot.js"></script>
        <!--右侧部分开始-->
        <div id="page-wrapper" class="gray-bg dashbard-1" style="height:2500px">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                </nav>
            </div>

            

            <div class="row content-tabs">
<form action="<?php echo U('Home/statis/index');?>" method="post" style="float: left; margin-right: 60px;" >
    <input placeholder="开始时间" required type="text" id="layui-laydate-input" onclick="layui.laydate({elem: this})" class="layui-input" name="start">
    <input placeholder="结束时间" required type="text" id="layui-laydate-input" onclick="layui.laydate({elem: this})" class="layui-input" name="end">
    <input type="hidden" name="code" value="1">
    <input type="submit"  value="放款日期统计">
</form>
                <a href="<?php echo U('Home/index/logout');?>" class="roll-nav roll-right J_tabExit"><i class="fa fa fa-sign-out"></i> 退出</a>
            </div>
            <div class="col-sm-12" >

            

<div id="chart2"></div>

<script type="text/javascript">
    var time=<?php echo json_encode($time_arr);?>;
    
    //var a=time[0][s_time];
    var due=new Array();
    var to=new Array();
    var s_time=new Array();
    var loan_due=new Array();
    var loan_to=new Array();
    time.forEach(function(time){
        if(time['due']==null){
            due=due.concat(0); 
        }else{
            due=due.concat(time['due']);  
        }
        if(time['to']==null){
            to=to.concat(0); 
        }else{
            to=to.concat(time['to']);  
        }
        if(time['loan_due']==null){
            loan_due=loan_due.concat(0); 
        }else{
            loan_due=loan_due.concat(time['loan_due']);  
        }
        if(time['loan_to']==null){
            loan_to=loan_to.concat(0); 
        }else{
            loan_to=loan_to.concat(time['loan_to']);  
        }
        var time=new Date(time['s_time']*1000);

        s_time=s_time.concat(time.getDate()); 
    });
    
    var data = [due,to,loan_due,loan_to];
    var data_max = 100; //Y轴最大刻度
    var line_title = ["逾期还款","还款","逾期","待还款"]; //曲线名称
    var y_label = ""; //Y轴标题
    var x_label = ""; //X轴标题
    var x = s_time; //定义X轴刻度值
    var title = "这是标题"; //统计图标标题
    j.jqplot.diagram.base("chart2", data, line_title, "放款日期统计", x, x_label, y_label, data_max, 2);
</script>
                
<form action="<?php echo U('Home/statis/index');?>" method="post" style="float: left; margin-right: 60px;" >
    <input placeholder="开始时间" required type="text" id="layui-laydate-input" onclick="layui.laydate({elem: this})" class="layui-input" name="start">
    <input placeholder="结束时间" required type="text" id="layui-laydate-input" onclick="layui.laydate({elem: this})" class="layui-input" name="end">
    <input type="hidden" name="code" value="2">
    <input type="submit"  value="放款日期详细统计">
</form>
                <table border="1px" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
                    <tr>
                        <td>芝麻分</td>
                        <td >逾期</td>
                        <td >逾期还款</td>
                        <td >未逾期还款</td>
                    </tr>
                    <tr>
                        <td>人数</td>
                        <td ><?php echo ($arr['zm_dueren']); ?></td>
                        <td ><?php echo ($record_arr['zm_dueren']); ?></td>
                        <td ><?php echo ($record_arr['zm_toren']); ?></td>
                    </tr>
                    <tr>
                        <td>芝麻分600以下</td>
                        <td ><?php echo ($arr['zm_due600']); ?></td>
                        <td ><?php echo ($record_arr['zm_due600']); ?></td>
                        <td ><?php echo ($record_arr['zm_to600']); ?></td>
                    </tr>
                    <tr>
                        <td>芝麻分600-650</td>
                        <td ><?php echo ($arr['zm_due650']); ?></td>
                        <td ><?php echo ($record_arr['zm_due650']); ?></td>
                        <td ><?php echo ($record_arr['zm_to650']); ?></td>
                    </tr>


                    <!-- <tr>
                        <td></td>
                        <td>花呗1000以下</td>
                        <td>花呗1000-2000</td>
                        <td>花呗2000-3000</td>
                        <td>花呗3000-4000</td>
                        <td>花呗4000-5000</td>
                        <td>花呗5000以上</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php echo ($arr['zm_650hb_due1000']); ?></td>
                        <td><?php echo ($arr['zm_650hb_due2000']); ?></td>
                        <td><?php echo ($arr['zm_650hb_due3000']); ?></td>
                        <td><?php echo ($arr['zm_650hb_due4000']); ?></td>
                        <td><?php echo ($arr['zm_650hb_due5000']); ?></td>
                        <td><?php echo ($arr['zm_650hb_due6000']); ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>淘宝消费1000以下</td>
                        <td>淘宝消费1000-2000</td>
                        <td>淘宝消费2000-3000</td>
                        <td>淘宝消费3000-5000</td>
                        <td>淘宝消费5000-10000</td>
                        <td>淘宝消费10000以上</td>
                    
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php echo ($arr['zm_650xf_due1000']); ?></td>
                        <td><?php echo ($arr['zm_650xf_due2000']); ?></td>
                        <td><?php echo ($arr['zm_650xf_due3000']); ?></td>
                        <td><?php echo ($arr['zm_650xf_due5000']); ?></td>
                        <td><?php echo ($arr['zm_650xf_due10000']); ?></td>
                        <td><?php echo ($arr['zm_650xf_due11000']); ?></td>
                    </tr> -->
                    <tr>
                        <td>芝麻分650-700</td>
                        <td ><?php echo ($arr['zm_due700']); ?></td>
                        <td ><?php echo ($record_arr['zm_due700']); ?></td>
                        <td ><?php echo ($record_arr['zm_to700']); ?></td>
                    </tr>
                    <tr>
                        <td>芝麻分700-750</td>
                        <td ><?php echo ($arr['zm_due750']); ?></td>
                        <td ><?php echo ($record_arr['zm_due750']); ?></td>
                        <td ><?php echo ($record_arr['zm_to750']); ?></td>
                    </tr>
                    <tr>
                        <td>芝麻分750以上</td>
                        <td ><?php echo ($arr['zm_due800']); ?></td>
                        <td ><?php echo ($record_arr['zm_due800']); ?></td>
                        <td ><?php echo ($record_arr['zm_to800']); ?></td>

                    </tr>
                </table>


                <table border="1px" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
                    <tr>
                        <td>花呗</td>
                        <td>逾期</td>
                        <td>逾期还款</td>
                        <td>未逾期还款</td>
                    </tr>
                    <tr>
                        <td>花呗1000以下</td>
                        <td><?php echo ($arr['hb_due1000']); ?></td>
                        <td><?php echo ($record_arr['hb_due1000']); ?></td>
                        <td><?php echo ($record_arr['hb_to1000']); ?></td>
                    </tr>
                    <tr>
                        <td>花呗1000-2000</td>
                        <td><?php echo ($arr['hb_due2000']); ?></td>
                        <td><?php echo ($record_arr['hb_due2000']); ?></td>
                        <td><?php echo ($record_arr['hb_to2000']); ?></td>
                    </tr>
                    <tr>
                        <td>花呗2000-3000</td>
                        <td><?php echo ($arr['hb_due3000']); ?></td>
                        <td><?php echo ($record_arr['hb_due3000']); ?></td>
                        <td><?php echo ($record_arr['hb_to3000']); ?></td>
                    </tr>
                    <tr>
                        <td>花呗3000-4000</td>
                        <td><?php echo ($arr['hb_due4000']); ?></td>
                        <td><?php echo ($record_arr['hb_due4000']); ?></td>
                        <td><?php echo ($record_arr['hb_to4000']); ?></td>
                    </tr>
                    <tr>
                        <td>花呗4000-5000</td>
                        <td><?php echo ($arr['hb_due5000']); ?></td>
                        <td><?php echo ($record_arr['hb_due5000']); ?></td>
                        <td><?php echo ($record_arr['hb_to5000']); ?></td>
                    </tr>
                    <tr>
                        <td>花呗5000以上</td>
                        <td><?php echo ($arr['hb_due6000']); ?></td>
                        <td><?php echo ($record_arr['hb_due6000']); ?></td>
                        <td><?php echo ($record_arr['hb_to6000']); ?></td>
                    </tr>
                    <tr>
                        <td>无花呗信息</td>
                        <td><?php echo ($arr['hb_due_empty']); ?></td>
                        <td><?php echo ($record_arr['hb_due_empty']); ?></td>
                        <td><?php echo ($record_arr['hb_to_empty']); ?></td>
                    </tr>
                </table>




                <table border="1px" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
                    <tr>
                        <td>有效通话</td>
                        <td>逾期</td>
                        <td>逾期还款</td>
                        <td>未逾期还款</td>
                    </tr>
                    <tr>
                        <td>有效10以下</td>
                        <td><?php echo ($arr['yx_due10']); ?></td>
                        <td><?php echo ($record_arr['yx_due10']); ?></td>
                        <td><?php echo ($record_arr['yx_to10']); ?></td>
                    </tr>
                    <tr>
                        <td>有效10-20</td>
                        <td><?php echo ($arr['yx_due20']); ?></td>
                        <td><?php echo ($record_arr['yx_due20']); ?></td>
                        <td><?php echo ($record_arr['yx_to20']); ?></td>
                    </tr>
                    <tr>
                        <td>有效20-30</td>
                        <td><?php echo ($arr['yx_due30']); ?></td>
                        <td><?php echo ($record_arr['yx_due30']); ?></td>
                        <td><?php echo ($record_arr['yx_to30']); ?></td>
                    </tr>
                    <tr>
                        <td>有效30-40</td>
                        <td><?php echo ($arr['yx_due40']); ?></td>
                        <td><?php echo ($record_arr['yx_due40']); ?></td>
                        <td><?php echo ($record_arr['yx_to40']); ?></td>
                    </tr>
                    <tr>
                        <td>有效40-50</td>
                        <td><?php echo ($arr['yx_due50']); ?></td>
                        <td><?php echo ($record_arr['yx_due50']); ?></td>
                        <td><?php echo ($record_arr['yx_to50']); ?></td>
                    </tr>
                </table>

                
                <table border="1px" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
                    <tr>
                        <td>年消费</td>
                        <td>逾期</td>
                        <td>逾期还款</td>
                        <td>未逾期还款</td>
                    </tr>
                    <tr>
                        <td>消费1000以下</td>
                        <td><?php echo ($arr['xf_due1000']); ?></td>
                        <td><?php echo ($record_arr['xf_due1000']); ?></td>
                        <td><?php echo ($record_arr['xf_to1000']); ?></td>
                    </tr>
                    <tr>
                        <td>消费1000-2000</td>
                        <td><?php echo ($arr['xf_due2000']); ?></td>
                        <td><?php echo ($record_arr['xf_due2000']); ?></td>
                        <td><?php echo ($record_arr['xf_to2000']); ?></td>
                    </tr>
                    <tr>
                        <td>消费2000-3000</td>
                        <td><?php echo ($arr['xf_due3000']); ?></td>
                        <td><?php echo ($record_arr['xf_due3000']); ?></td>
                        <td><?php echo ($record_arr['xf_to3000']); ?></td>
                    </tr>
                    <tr>
                        <td>消费3000-5000</td>
                        <td><?php echo ($arr['xf_due5000']); ?></td>
                        <td><?php echo ($record_arr['xf_due5000']); ?></td>
                        <td><?php echo ($record_arr['xf_to5000']); ?></td>
                    </tr>
                    <tr>
                        <td>消费5000-10000</td>
                        <td><?php echo ($arr['xf_due10000']); ?></td>
                        <td><?php echo ($record_arr['xf_due10000']); ?></td>
                        <td><?php echo ($record_arr['xf_to10000']); ?></td>
                    </tr>
                    <tr>
                        <td>消费10000以上</td>
                        <td><?php echo ($arr['xf_due11000']); ?></td>
                        <td><?php echo ($record_arr['xf_due11000']); ?></td>
                        <td><?php echo ($record_arr['xf_to11000']); ?></td>
                    </tr>
                </table>

   
                <table border="1px" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
                    <tr>
                        <td>紧急联系人通话</td>
                        <td>逾期</td>
                        <td>逾期还款</td>
                        <td>未逾期还款</td>
                    </tr>
                    <tr>
                        <td>通话20次以下</td>
                        <td><?php echo ($arr['ut_due20']); ?></td>
                        <td><?php echo ($record_arr['ut_due20']); ?></td>
                        <td><?php echo ($record_arr['ut_to20']); ?></td>
                    </tr>
                    <tr>
                        <td>通话20-50</td>
                        <td><?php echo ($arr['ut_due50']); ?></td>
                        <td><?php echo ($record_arr['ut_due50']); ?></td>
                        <td><?php echo ($record_arr['ut_to50']); ?></td>
                    </tr>
                    <tr>
                        <td>通话50-100</td>
                        <td><?php echo ($arr['ut_due100']); ?></td>
                        <td><?php echo ($record_arr['ut_due100']); ?></td>
                        <td><?php echo ($record_arr['ut_to100']); ?></td>
                    </tr>
                    <tr>
                        <td>通话100-150</td>
                        <td><?php echo ($arr['ut_due150']); ?></td>
                        <td><?php echo ($record_arr['ut_due150']); ?></td>
                        <td><?php echo ($record_arr['ut_to150']); ?></td>
                    </tr>
                    <tr>
                        <td>通话150以上</td>
                        <td><?php echo ($arr['ut_due200']); ?></td>
                        <td><?php echo ($record_arr['ut_due200']); ?></td>
                        <td><?php echo ($record_arr['ut_to200']); ?></td>
                    </tr>
                </table>


                <table border="1px" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
                    <tr>
                        <td>前十平均通话</td>
                        <td>逾期</td>
                        <td>逾期还款</td>
                        <td>未逾期还款</td>
                    </tr>
                    <tr>
                        <td>通话50分钟以下</td>
                        <td><?php echo ($arr['pj_due50']); ?></td>
                        <td><?php echo ($record_arr['pj_due50']); ?></td>
                        <td><?php echo ($record_arr['pj_to50']); ?></td>
                    </tr>
                    <tr>
                        <td>通话50-100</td>
                        <td><?php echo ($arr['pj_due100']); ?></td>
                        <td><?php echo ($record_arr['pj_due100']); ?></td>
                        <td><?php echo ($record_arr['pj_to100']); ?></td>
                    </tr>
                    <tr>
                        <td>通话100-150</td>
                        <td><?php echo ($arr['pj_due150']); ?></td>
                        <td><?php echo ($record_arr['pj_due150']); ?></td>
                        <td><?php echo ($record_arr['pj_to150']); ?></td>
                    </tr>
                    <tr>
                        <td>通话150-200</td>
                        <td><?php echo ($arr['pj_due200']); ?></td>
                        <td><?php echo ($record_arr['pj_due200']); ?></td>
                        <td><?php echo ($record_arr['pj_to200']); ?></td>
                    </tr>
                    <tr>
                        <td>通话200以上</td>
                        <td><?php echo ($arr['pj_due250']); ?></td>
                        <td><?php echo ($record_arr['pj_due250']); ?></td>
                        <td><?php echo ($record_arr['pj_to250']); ?></td>
                    </tr>
                </table>


                <table border="1px" class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
                    <tr>
                        <td>互通電話量</td>
                        <td>逾期</td>
                        <td>逾期还款</td>
                        <td>未逾期还款</td>
                    </tr>
                    <tr>
                        <td>互通20次以下</td>
                        <td><?php echo ($arr['ht_due20']); ?></td>
                        <td><?php echo ($record_arr['ht_due20']); ?></td>
                        <td><?php echo ($record_arr['ht_to20']); ?></td>
                    </tr>
                    <tr>
                        <td>互通20-40</td>
                        <td><?php echo ($arr['ht_due40']); ?></td>
                        <td><?php echo ($record_arr['ht_due40']); ?></td>
                        <td><?php echo ($record_arr['ht_to40']); ?></td>
                    </tr>
                    <tr>
                        <td>互通40-60</td>
                        <td><?php echo ($arr['ht_due60']); ?></td>
                        <td><?php echo ($record_arr['ht_due60']); ?></td>
                        <td><?php echo ($record_arr['ht_to60']); ?></td>
                    </tr>
                    <tr>
                        <td>互通60-80</td>
                        <td><?php echo ($arr['ht_due80']); ?></td>
                        <td><?php echo ($record_arr['ht_due80']); ?></td>
                        <td><?php echo ($record_arr['ht_to80']); ?></td>
                    </tr>
                    <tr>
                        <td>互通80以上</td>
                        <td><?php echo ($arr['ht_due100']); ?></td>
                        <td><?php echo ($record_arr['ht_due100']); ?></td>
                        <td><?php echo ($record_arr['ht_to100']); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <!--右侧部分结束-->
    </div>

</body>
    <script>
layui.use('laydate', function(){
  var laydate = layui.laydate
});
</script>
</html>