<?php
namespace Home\Model;
use Think\Model;
class ContinuedModel extends Model{

	public function user_select($user_id){
		$continued=M('continued');
		$where['user_id']=$user_id;
		$data=$continued->where($where)->order('repayment_time desc')->select();
		return $data;
	}
}