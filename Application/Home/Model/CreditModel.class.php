<?php
namespace Home\Model;
use Think\Model;
class CreditModel extends Model{


// 查询指定时间的  记录
	public function time_all($start_time,$end_time){
		$credit=M('credit');
		$where['create_time']=array(array('EGT',$start_time),array('ELT',$end_time));
		$data=$credit->where($where)->group('user_id')->order('credit_id asc')->select();
		return $data;
	}

// 与user  表连接 条件为 is_matched
	public function select_all($is_matched){
		$credit=M('credit');
		$where['free_credit.is_matched']=$is_matched;
		$credit_data=$credit->field('free_user.user_name,free_user.u_name,free_user.identity,free_credit.zm_score,free_credit.is_matched')->where($where)->group('free_credit.user_id')->limit(5000)->join('free_user ON free_user.user_id=free_credit.user_id')->select();
		return $credit_data;
	}



	public function no_matched(){
		$credit=M('credit');
		$where['free_credit.is_matched']=array('neq',1);
		$credit_data=$credit->field('free_user.user_name,free_user.u_name,free_user.identity,free_credit.zm_score,free_credit.is_matched')->where($where)->group('free_credit.user_id')->order('free_credit.zm_score desc')->limit(5000)->join('free_user ON free_user.user_id=free_credit.user_id')->select();
		return $credit_data;
	}
	

}