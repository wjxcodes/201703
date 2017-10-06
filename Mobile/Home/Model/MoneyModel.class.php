<?php
namespace Home\Model;
use Think\Model;
class MoneyModel extends Model{

 /* 传入时间 利息类型  返回利息*/   
    static public function interest_type($interest_type,$time){
      if($interest_type==0||$interest_type==''){
          if($time==2){
            $return_interest=0.15;
          }else if($time==1){
            $return_interest=0.10;
          }
      }else if($interest_type==1){
          if($time==2){
            $return_interest=0.1;
          }else if($time==1){
            $return_interest=0.05;
          }
      }else if($interest_type==2){
          if($time==2){
            $return_interest=0.12;
          }else if($time==1){
            $return_interest=0.06;
          }
      }
       return $return_interest;
    }
/*  降息卷    */
    /*static public function interest_type($interest_type,$time){
      if($interest_type==0||$interest_type==''){
        $coupons=M('coupons');
        $where['user_name']=session('name');
        $where['is_use']=0;
        $where['coupons_type']=1;
        $coupons_data=$coupons->where($where)->find();
        if($coupons_data['interest']==1){
            if($time==2){
                $return_interest=0.1;
            }else if($time==1){
                $return_interest=0.05;
            }
        }else{
            if($time==2){
                $return_interest=0.15;
            }else if($time==1){
                $return_interest=0.1;
            }
        }
      }else if($interest_type==1){
          if($time==2){
            $return_interest=0.1;
          }else if($time==1){
            $return_interest=0.05;
          }
      }
       return $return_interest;
    }*/

/* 手续费分离*/
    static public function sum_cost($money,$interest){
        $arr['shenji']=$money*$interest*0.475;
        $arr['lixi']=$money*$interest*0.025;
        $arr['guanli']=$money*$interest*0.50;
        return $arr;
    }

/* 手续费*/    
    static public function shouxufei($money,$interest,$cuts_interest){
        $shouxufei=$money*$interest-$cuts_interest;

        return $shouxufei;
    }


 /*当前额度*/
    /*static public function xiane($original,$loan_lines){
        if($original==''){
            $limit_money=1000+$loan_lines;
        }else{
            $limit_money=$original+$loan_lines;
        }
        return $limit_money;
    }*/



/*提额卷*/
    static public function xiane($original,$loan_lines){
        if($original==''){
            $limit_money=1000+$loan_lines;
        }else{
            $limit_money=$original+$loan_lines;
        }
        $coupons=M('coupons');
        $where['user_name']=session('name');
        $where['is_use']=0;
        $where['coupons_type']=2;
        $where['overdue_time']=array('gt',time());
        $coupons_data=$coupons->where($where)->find();
        $limit_money=$coupons_data['lines']+$limit_money;
        return $limit_money;
    }


   
/*未认证额度*/
    static public function initial($initial=5000){
          return $initial;
    }






/*$borrow_num  传入的借款次数  $gains 每次上涨额度   $interest   初始额度 */

    static public function lines($hit,$score){
       /*基础额度*/
       $interest=1100;
       /*基础芝麻分*/
       $in_score=670;
       /*芝麻分增长额度*/
       $score_growth=100;
       /*欺诈关注名单增长额度*/
       $hit_growth=0;
       /*初始额度最大值*/
       $max_interest=3000;
       if($hit=='no'){
          $hit_money=$hit_growth;
       }
       if($score>$in_score){
          $num=floor(($score-$in_score)/10);
          $score_money=$num*$score_growth;
       }
       $return=$interest+$score_money+$hit_money;
       if($return>=$max_interest){
            $return=$max_interest;
       }
       if ($return == 1500) {
          $return = 1400;
       }elseif($return == 2000){
          $return = 1900;
       }elseif($return == 2500){
          $return = 2400;
       }elseif ($return == 3000) {
          $return = 2900;
       }
       return $return;
    }

  
    /* $initial  未注册初始额度 */
    
    
/*  利息前置后置  2 后置 1前置*/
    static public function initial_palace(){
           return 2;
    }
}