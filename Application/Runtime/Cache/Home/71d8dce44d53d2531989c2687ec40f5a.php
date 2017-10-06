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
                    <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                </nav>
            </div>
            <div class="row content-tabs">
                <a href="<?php echo U('Home/index/logout');?>" class="roll-nav roll-right J_tabExit"><i class="fa fa fa-sign-out"></i> 退出</a>
            </div>
               <div class="col-sm-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <form action="<?php echo U('Home/Already/statistical');?>" method="post" style="float: left; margin-right: 60px;" >
                                <input placeholder="开始时间" required type="text" id="layui-laydate-input" onclick="layui.laydate({elem: this})" class="layui-input" name="start">
                                <input placeholder="结束时间" required type="text" id="layui-laydate-input" onclick="layui.laydate({elem: this})" class="layui-input" name="end">
                                <input type="hidden" name="code" value="1">
                                <input type="submit"  value="生成记录">
                            </form>
                        </div>
                       
                        <div class="ibox-title">  
                           <table width="1000px" height="auto" align="center" style="text-align:center;font-size:15px;color:#000000;" border="1px">
                                <tr>
                                    <td rowspan="2" colspan="3">名称</td>
                                    <td colspan="3">今日合计</td>
                                    <td colspan="3">过往合计</td>
                                </tr>
                                <tr>
                                    <td>人次</td>
                                    <td>本金</td>
                                    <td>利息</td>
                                    <td>人次</td>
                                    <td>本金</td>
                                    <td>利息</td>
                                </tr>
                                <tr>
                                    <td rowspan="4">放款</td>
                                    <td rowspan="3">放款</td>
                                    <td>7天</td>
                                    <td><?php echo ($arr['lend_7_ren']); ?></td>
                                    <td><?php echo ($arr['lend_7_money']); ?></td>
                                    <td><?php echo ($arr['lend_7_cost']); ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>14天</td>
                                    <td><?php echo ($arr['lend_14_ren']); ?></td>
                                    <td><?php echo ($arr['lend_14_money']); ?></td>
                                    <td><?php echo ($arr['lend_14_cost']); ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>合计</td>
                                    <td rowspan="2"><?php echo ($arr['lend_14_ren']+$arr['lend_7_ren']); ?></td>
                                    <td><?php echo ($arr['lend_7_money']+$arr['lend_14_money']); ?></td>
                                    <td><?php echo ($arr['lend_7_cost']+$arr['lend_14_cost']); ?></td>
                                    <td rowspan="2"></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="2">合计</td>
                                    <td colspan="2"><?php echo ($arr['lend_7_money']+$arr['lend_14_money']+$arr['lend_7_cost']+$arr['lend_14_cost']); ?></td>
                                    <td colspan="2"></td>
                                </tr>
                            </table>
                            <br/>            
                            <table width="1000px" height="auto" align="center" style="text-align:center;font-size:15px;color:#000000;" border="1px">
                                <tr>
                                    <td rowspan="5">应收款</td>
                                    <td rowspan="4">到期应收款</td>
                                    <td>天数</td>
                                    <td>人次</td>
                                    <td>本金</td>
                                    <td>利息</td>
                                    <td>人次</td>
                                    <td>本金</td>
                                    <td>利息</td>
                                </tr>
                                <tr>
                                    
                                    <td>7天</td>
                                    <td><?php echo ($arr['rece_7_ren']+$arr['not_7_ren']); ?></td>
                                    <td><?php echo ($arr['rece_7_money']+$arr['not_7_money']); ?></td>
                                    <td><?php echo ($arr['rece_7_cost']+$arr['not_7_cost']); ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>14天</td>
                                    <td><?php echo ($arr['rece_14_ren']+$arr['not_14_ren']); ?></td>
                                    <td><?php echo ($arr['rece_14_money']+$arr['not_14_money']); ?></td>
                                    <td><?php echo ($arr['rece_14_cost']+$arr['not_14_cost']); ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>合计</td>
                                    <td rowspan="2"><?php echo ($arr['rece_14_ren']+$arr['rece_7_ren']+$arr['not_14_ren']+$arr['not_7_ren']); ?></td>
                                    <td><?php echo ($arr['rece_14_money']+$arr['rece_7_money']+$arr['not_14_money']+$arr['not_7_money']); ?></td>
                                    <td><?php echo ($arr['rece_14_cost']+$arr['rece_7_cost']+$arr['not_14_cost']+$arr['not_7_cost']); ?></td>
                                    <td rowspan="2"></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="2">合计</td>
                                    <td colspan="2"><?php echo ($arr['rece_14_money']+$arr['rece_7_money']+$arr['rece_14_cost']+$arr['rece_7_cost']+$arr['not_14_money']+$arr['not_7_money']+$arr['not_14_cost']+$arr['not_7_cost']); ?></td>
                                    <td colspan="2"></td>
                                </tr>
                            </table>
                            <br/>
                            <table width="1000px" height="auto" align="center" style="text-align:center;font-size:15px;color:#000000;" border="1px">
                                <tr>
                                    <td rowspan="20">实收款</td>
                                    <td rowspan="4">到期实收款</td>
                                    <td rowspan="2">连连</td>
                                    <td >7天</td>
                                    <td ><?php echo ($arr['ll_normal_7_ren']); ?></td>
                                    <td ><?php echo ($arr['ll_normal_7_money']); ?></td>
                                    <td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
                                    <td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
                                </tr>
                                <tr>
                                    <td >14天</td>
                                    <td ><?php echo ($arr['ll_normal_14_ren']); ?></td>
                                    <td ><?php echo ($arr['ll_normal_14_money']); ?></td>
                                    <td > </td>
                                    <td > </td>
                                </tr>
                                <tr>
                                    <td rowspan="2">支付宝</td>
                                    <td >7天</td>
                                    <td ><?php echo ($arr['normal_7_ren']); ?></td>
                                    <td ><?php echo ($arr['normal_7_money']); ?></td>
                                    <td > </td>
                                    <td > </td>
                                </tr>
                                <tr>
                                    <td >14天</td>
                                    <td ><?php echo ($arr['normal_14_ren']); ?></td>
                                    <td ><?php echo ($arr['normal_14_money']); ?></td>
                                    <td > </td>
                                    <td > </td>
                                </tr>
                                <tr>
                                    <td rowspan="2">续期费用</td>
                                    <td colspan="2">连连</td>
                                    <td ><?php echo ($arr['zfb_renwal_ren']); ?></td>
                                    <td ><?php echo ($arr['zfb_renwal_money']); ?></td>
                                    <td > </td>
                                    <td > </td>
                                </tr>
                                <tr>
                                    <td colspan="2">支付宝</td>
                                    <td ></td>
                                    <td ></td>
                                    <td > </td>
                                    <td > </td>
                                </tr>
                                <tr>
                                    <td rowspan="2">逾期费用</td>
                                    <td colspan="2">连连</td>
                                    <td ><?php echo ($arr['ll_overdue_ren']); ?></td>
                                    <td ><?php echo ($arr['ll_overdue_money']); ?></td>
                                    <td > </td>
                                    <td > </td>
                                </tr>
                                <tr>
                                    <td colspan="2">支付宝</td>
                                    <td ><?php echo ($arr['zfb_overdue_ren']); ?>(<?php echo ($arr['payment_ren']); ?>)</td>
                                    <td ><?php echo ($arr['zfb_overdue_money']); ?>(<?php echo ($arr['payment']); ?>)</td>
                                    <td > </td>
                                    <td > </td>
                                </tr>
                                <tr>
                                    <td colspan="3">到期实收款</td>
                                    <td ><?php echo ($arr['normal_sum_ren']); ?></td>
                                    <td ><?php echo ($arr['normal_sum_money']); ?></td>
                                    <td > </td>
                                    <td > </td>
                                </tr>
                                <tr>
                                    <td colspan="3">到期毛利润</td>
                                    <td >————</td>
                                    <td >————</td>
                                    <td > </td>
                                    <td > </td>
                                </tr>
                                <tr>
                                    <td rowspan="4">提前收款</td>
                                    <td rowspan="2">连连</td>
                                    <td >7天</td>
                                    <td ><?php echo ($arr['ll_ahead_7_ren']); ?></td>
                                    <td ><?php echo ($arr['ll_ahead_7_money']); ?></td>
                                    <td > </td>
                                    <td > </td>
                                </tr>
                                <tr>
                                    <td >14天</td>
                                    <td ><?php echo ($arr['ll_ahead_14_ren']); ?></td>
                                    <td ><?php echo ($arr['ll_ahead_14_money']); ?></td>
                                    <td > </td>
                                    <td > </td>
                                </tr>
                                <tr>
                                    <td rowspan="2">支付宝</td>
                                    <td >7天</td>
                                    <td ><?php echo ($arr['ahead_7_ren']); ?></td>
                                    <td ><?php echo ($arr['ahead_7_money']); ?></td>
                                    <td > </td>
                                    <td > </td>
                                </tr>
                                <tr>
                                    <td >14天</td>
                                    <td ><?php echo ($arr['ahead_14_ren']); ?></td>
                                    <td ><?php echo ($arr['ahead_14_money']); ?></td>
                                    <td > </td>
                                    <td > </td>
                                </tr>
                                <tr>
                                    <td colspan="3">提前收款合计</td>
                                    <td ><?php echo ($arr['ahead_sum_ren']); ?></td>
                                    <td ><?php echo ($arr['ahead_sum_money']); ?></td>
                                    <td > </td>
                                    <td > </td>
                                </tr>
                                <tr>
                                    <td colspan="3">实收款合计</td>
                                    <td ><?php echo ($arr['ahead_sum_ren']+$arr['normal_sum_ren']); ?></td>
                                    <td ><?php echo ($arr['ahead_sum_money']+$arr['normal_sum_money']); ?></td>
                                    <td > </td>
                                    <td > </td>
                                </tr>
                            </table>
                            <br/>
                            <table width="1000px" height="auto" align="center" style="text-align:center;font-size:15px;color:#000000;" border="1px">
                                <tr>
                                    <td rowspan="6">待收款</td>
                                    <td rowspan="4">待收款</td>
                                    <td>天数</td>
                                    <td>人次</td>
                                    <td>本金</td>
                                    <td>利息</td>
                                    <td>人次</td>
                                    <td>本金</td>
                                    <td>利息</td>
                                </tr>
                                <tr>
                                    <td>7天</td>
                                    <td><?php echo ($arr['not_7_ren']); ?></td>
                                    <td><?php echo ($arr['not_7_money']); ?></td>
                                    <td><?php echo ($arr['not_7_cost']); ?></td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
                                <tr>
                                    <td>14天</td>
                                    <td><?php echo ($arr['not_14_ren']); ?></td>
                                    <td><?php echo ($arr['not_14_money']); ?></td>
                                    <td><?php echo ($arr['not_14_cost']); ?></td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
                                <tr>
                                    <td>合计</td>
                                    <td><?php echo ($arr['not_7_ren']+$arr['not_14_ren']); ?></td>
                                    <td><?php echo ($arr['not_14_money']+$arr['not_7_money']); ?></td>
                                    <td><?php echo ($arr['not_7_cost']+$arr['not_14_cost']); ?></td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
                                <tr>
                                    <td colspan="2">合计</td>
                                    <td rowspan="2"><?php echo ($arr['not_7_ren']+$arr['not_14_ren']); ?></td>
                                    <td colspan="2"><?php echo ($arr['not_14_money']+$arr['not_7_money']+$arr['not_7_cost']+$arr['not_14_cost']); ?></td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>       
                                <tr>
                                    <td colspan="2">逾期费用</td>
                                    <td colspan="2"><?php echo ($arr['not_overdue']); ?></td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>                
                            </table>
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
    <script>
layui.use('laydate', function(){
  var laydate = layui.laydate
});
</script>
</body>

</html>