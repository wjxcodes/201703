<?php
namespace Home\Model;
use Think\Model;
class RepayModel extends Model{
      	static public function overdue_show($is_pay,$loan_time,$renewal_days,$loan_amount){
             $time=$is_pay+$loan_time*86400+$renewal_days*86400;
             $is_overdue=time()-$time;
             $day=ceil($is_overdue/86400);
             if($day>=0){
      	       	if($day<=3){
      	           $overdue_money=ceil($loan_amount*0.015*$day);
      	        }else{
      	        	$day_i=$day-3;
      	        	$overdue_money=ceil($loan_amount*0.02*$day_i+$loan_amount*0.015*3);
      	        }
             }
             $arr=array('time'=>"".$time."",
             	          'day'=>"".$day."",
             	          'overdue_money'=>"".$overdue_money."");
             return $arr;
      	}
        
        static public function renewal_money($renewal_day,$loan_amount){
            if($renewal_day==7){
               $renewal_all=$loan_amount*0.12;
            }else if($renewal_day==14){
               $renewal_all=$loan_amount*0.18;
            }
            $renewal_fw=$loan_amount*0.06;
            $arr=array('renewal_fw'=>"".$renewal_fw."",
                       'renewal_all'=>"".$renewal_all."",
                       );
            return $arr;
        }



/*已还款逾期费用计算 */

    static public function be_overdue($is_pay,$loan_time,$renewal_days,$loan_amount,$repayment_time){
         $time=$is_pay+$loan_time*86400+$renewal_days*86400;
         $is_overdue=$repayment_time-$time;
         $day=ceil($is_overdue/86400);
         if($day>=0){
            if($day<=3){
               $overdue_money=ceil($loan_amount*0.015*$day);
            }else{
              $day_i=$day-3;
              $overdue_money=ceil($loan_amount*0.02*$day_i+$loan_amount*0.015*3);
            }
         }
         $arr=array('time'=>"".$time."",
               'day'=>"".$day."",
               'overdue_money'=>"".$overdue_money."");
         return $arr;
    }



}