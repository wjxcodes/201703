<?php
namespace Home\Model;
use Think\Model;
class RepayModel extends Model{
	/*当前逾期*/
	static public function overdue_show($is_pay,$loan_time,$renewal_days,$loan_amount){
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
	/*已还款逾期费用计算 */
	static public function be_overdue($is_pay,$loan_time,$renewal_days,$loan_amount,$repayment_time){
       $time=$is_pay+$loan_time*86400+$renewal_days*86400;
       $is_overdue=$repayment_time-$time;
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

      /*通过时间戳、用户自增id、随机数组成28位单号*/
  Static public function getOrderCode($user_id){
      $timlen = strlen(date('Ymdhis'));
      $idlen = strlen($user_id);
      $len = 28-$timlen-$idlen;
      return date('Ymdhis') . $user_id.substr(time().time(),0,$len);
  }
}