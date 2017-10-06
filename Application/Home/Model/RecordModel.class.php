<?php
namespace Home\Model;
use Think\Model;
class RecordModel extends Model{
	public function select_all(){
		$record=M('record');
		$record_data=$record->field('free_user.user_name,free_user.u_name,free_user.identity,zm_score,is_matched,loan_time,pay_time,repayment_time,pay_money,xutime')->join('free_user ON free_user.user_id=free_record.user_id')->select();
		return $record_data;
	}

	public function user_select($user_id){
		$record=M('record');
		$where['free_record.user_id']=$user_id;
		$record_data=$record->where($where)->order('record_id desc')->select();
		return $record_data;
	}
}