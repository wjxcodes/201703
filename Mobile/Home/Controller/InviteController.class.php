<?php
/*
邀请界面
*/
namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf8");
class InviteController extends Controller {
	public function index(){
		if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }

        $user_model=M('user');
        $user_where['user_name']=session('name');
        $user_data=$user_model->where($user_where)->find();



        $invitation_model=M('invitation');
        $where['invitation_ids']=$user_data['user_id'];
        $invitation_data=$invitation_model->field('loan_code,is_no,money,user_name,id')->where($where)
        ->join('free_user ON free_user.user_id=free_invitation.invitation_id')
        ->select();

        foreach ($invitation_data as $key => &$value) {
        	if($value['loan_code']==1){
        		if($value['is_no']==0){
        			$data['no_money']+=$value['money'];
        		}else{
        			$data['use_money']+=$value['money'];
        		}
        		$data['i']+=1;
        	}
        	$user_name=substr_replace($value['user_name'],'****',3,4);
        	$value['user_name']=$user_name;
        }

        $this->invitation_data=$invitation_data;
        $this->user_data=$user_data;
        $this->data=$data;
        $this->display('invite/index');
	}

	public function request(){
		if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
        

        $user_model=M('user');
        $user_where['user_name']=session('name');
        $user_data=$user_model->where($user_where)->find();


        $withdraw_model=M('withdraw');
        $withdraw_where['user_id']=$user_data['user_id'];
        $withdraw_where['request']=1;
        $withdraw_data=$withdraw_model->where($withdraw_where)->select();
        if($withdraw_data){
        	die("<script>alert('您已有在提现中的奖金');window.location.href='index'</script>"); 
        }


        $invitation_model=M('invitation');
        $invitation_where['loan_code']=1;
        $invitation_where['is_no']=0;
        $invitation_where['invitation_ids']=$user_data['user_id'];
        $invitation_data=$invitation_model->where($invitation_where)->select();
        if(!$invitation_data){
        	die("<script>alert('您当前没有可供提现奖金');window.location.href='index'</script>"); 
        }
        foreach ($invitation_data as $key => $value) {
        	$money+=$value['money'];
        	$by_user.=$value['invitation_id'].'-';
        }

        if($money<40){
        	die("<script>alert('奖金40元以上才可以提现');window.location.href='index'</script>"); 
        }

        
        $save['user_id']=$user_data['user_id'];
        $save['money']=$money;
        $save['create_time']=time();
        $save['by_user']=$by_user;
        $withdraw_res=$withdraw_model->add($save);
        if($withdraw_res){
        	$invitation_save['is_no']=1;
        	$invitation_data=$invitation_model->where($invitation_where)->save($invitation_save);
        	die("<script>alert('提现成功');window.location.href='index'</script>"); 
        }else{
        	die("<script>alert('出错了！');window.location.href='index'</script>"); 
        }
        //$this->display('invite/index');
	}

}