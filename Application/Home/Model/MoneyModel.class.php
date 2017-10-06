<?php
namespace Home\Model;
use Think\Model;
class MoneyModel extends Model{
/*    旧手续费   逐步替换*/  
  static public function shouxufei($time,$money,$user){
      if($user==0||$user==null){
		    	if ($time == 1) {
			      $shouxufei = $money * 0.1;
			    } else if ($time == 2) { 
			      $shouxufei = $money * 0.15;
			    }
	   }else if($user==1){
          if ($time == 1) {
			      $shouxufei = $money * 0.05;
			    } else if ($time == 2) { 
			      $shouxufei = $money * 0.1;
			    }
	   }
	    return $shouxufei;
    }
/*   新手续费计算  */

    static public function poundage($money,$interest,$cuts_interest){
        $poundage=$money*$interest-$cuts_interest;
        return $poundage;
    }

    static public function renewal_money($renewal_day,$loan_amount){
            if($renewal_day==7){
               $renewal_all=$loan_amount*0.15;
            }else if($renewal_day==14){
               $renewal_all=$loan_amount*0.2;
            }
            $renewal_fw=$loan_amount*0.05;
            $arr=array('renewal_fw'=>"".$renewal_fw."",
                       'renewal_all'=>"".$renewal_all."",
                       );
            return $arr;
        }
}