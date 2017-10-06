<?php
namespace Home\Controller;
use Think\Controller;
class InvitationController extends BaseController{
	public function index(){
		$withdraw_model=M('withdraw');
		$where['request']=1;

		$withdraw_data=$withdraw_model->field('user_name,free_user.user_id,bank_card,money,free_withdraw.create_time,id,u_name')
		->where($where)->join('free_user ON free_user.user_id=free_withdraw.user_id')
		->select();

		$this->withdraw_data=$withdraw_data;
		$this->display();

	}

	public function pay(){
		$get=I('get.');
		$withdraw_model=M('withdraw');
		$where['id']=$get['id'];
		$save['request']=2;
		$save['save_time']=time();
		$withdraw_res=$withdraw_model->where($where)->save($save);
		if($withdraw_res){
			$this->redirect('Home/Invitation/index');
		}else{
			die('<script>alert("失败了！！！");history.back()</script>');
		}
	}
}