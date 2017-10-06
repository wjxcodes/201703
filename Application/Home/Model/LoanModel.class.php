<?php
namespace Home\Model;
use Think\Model;
class LoanModel extends Model{

	

//查询一条数据	
	public function find_username($user_name){
		$loan=M('loan');
		$where['user_name']=$user_name;
		$loan_data=$loan->where($where)->find();
		return $loan_data;
	}
//引用
//anrong/index	



	public function user_find($user_id){
		$loan=M('loan');
		$where['user_id']=$user_id;
		$loan_data=$loan->where($where)->find();
		return $loan_data;
	}

	public function is_loan(){
		$loan=M('loan');
		$where['is_pay']=array('gt',0);
		$loan_data=$loan->field('free_user.u_name,free_user.user_name,free_user.identity,zm_score,is_matched,is_pay,loan_num,loan_amount,loan_time,renewal_days')->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->select();
		return $loan_data;
	}

}