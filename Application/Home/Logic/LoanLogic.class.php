<?php 
/*
*
*/
namespace Home\Logic;
use Think\Controller;
header("content-type:text/html;charset=utf8");
class LoanLogic extends Controller{
	/*当前逾期*/
	public function overdue_show($is_pay,$loan_time,$renewal_days,$loan_amount){
       $time=$is_pay+$loan_time*86400+$renewal_days*86400;
       $is_overdue=time()-$time;
       $day=ceil($is_overdue/86400);
       if($day>=0){
	        if($day<=3){
	           $money=ceil($loan_amount*0.015*$day);
	        }else{
	        	$day_i=$day-3;
	        	$money=ceil($loan_amount*0.02*$day_i+$loan_amount*0.015*3);
	        }
       }
       $overdue_money=!empty($money) ? $money:null;
       $arr=array('time'=>"".$time."",
	           'day'=>"".$day."",
	           'overdue_money'=>"".$overdue_money."");
       return $arr;
	}
	
}