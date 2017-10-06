<?php
/*

*/
namespace Home\Model;
use Think\Model;
class PinchModel extends Model{

	public function zfb_stage_add($post,$get){

		$zfb_stages=M('zfb_stages');
		$save['loan_order']=$get['order'];
		$save['money']=$post['money'];
		$save['create_time']=time();
		$save['create_per']=session('aname');
		$save['zfb_order']=$post['zfb_order'];

		$data=$zfb_stages->add($save);
		return $data;
	}


	public function zfb_stage_select($order){
		$zfb_stages=M('zfb_stages');
		$where['loan_order']=$order;
		$data=$zfb_stages->where($where)->select();
		return $data;
	}
}