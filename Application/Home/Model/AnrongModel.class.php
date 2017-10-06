<?php
namespace Home\Model;
use Think\Model;
class AnrongModel extends Model{
	

	public function find_username($user_name){
		$anrong=M('anrong');
		$where['user_name']=$user_name;
		$anrong_data=$anrong->where($where)->find();
		if($anrong_data){
			$data['title']=json_decode($anrong_data['title'],1);
			$data['applyDetail']=json_decode($anrong_data['applydetail'],1);
			$data['normalCreditDetails']=json_decode($anrong_data['normalcreditdetails'],1);
			$data['abnormalCreditDetails']=json_decode($anrong_data['abnormalcreditdetails'],1);
			$data['queryDetails']=json_decode($anrong_data['querydetails'],1);
			$data['blackDatas']=json_decode($anrong_data['blackdatas'],1);
		}
		return $data;
	}

	public function add_all($user_name,$add_data){
		$anrong=M('anrong');
		$save['title']=json_encode($add_data['title']);
		$save['applydetail']=json_encode($add_data['applyDetail']);
		$save['normalcreditdetails']=json_encode($add_data['normalCreditDetails']);
		$save['abnormalcreditdetails']=json_encode($add_data['abnormalCreditDetails']);
		$save['querydetails']=json_encode($add_data['queryDetails']);
		$save['blackdatas']=json_encode($add_data['blackDatas']);
		$save['user_name']=$user_name;
		$save['create_time']=time();
		$data=$anrong->add($save);
		return $data;
	}
}