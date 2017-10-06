<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model{

//查询  请求借款用户
	public function au_request(){
		$user=M('user');
		$where['au_request']=1;
		$data=$user->where($where)->join('free_loan ON free_loan.user_id=free_user.user_id')->order('loan_request asc')->select();
		return $data;
	}
//infoaudit/index



// 初步审核  不通过
	public function no_pass($user_id){
		$user=M('user');
		$where['user_id']=$user_id;
		$save['audit'] = 0;
        $save['au_request'] = 0;
        $save['alter_info'] = 0;
		$data=$user->where($where)->save($save);
		return $data;
	}
//infoaudit/operation
//


	public function tag($user_id){
		$user=M('user');
		$where['user_id']=$user_id;
		$save['tag']=1;
		$data=$user->where($where)->save($save);
		return $data;
	}


/*统计总条数*/
	public function count($start_time,$end_time){
		$user=M('user');
		$where['create_time']=array(array('EGT',$start_time),array('LT',$end_time));
		$data=$user->where($where)->count();
		return $data;
	}

//register/index


/*用户所有信息分页*/
	public function page_all($page,$start_time,$end_time){
		$user=M('user');
		$where['create_time']=array(array('EGT',$start_time),array('LT',$end_time));
		$data=$user->where($where)->order('user_id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
		return $data;
	}

//register/index


/* 查询一条用户表数据*/
	public function find_username($user_name){
		$user=M('user');
		$where['user_name']=$user_name;
		$user_data=$user->where($where)->find();
		return $user_data;
	}
/* where条件查询一个用户*/
	public function where_select($where){
		$user=M('user');
		$user_data=$user->where($where)->select();
		return $user_data;
	}
}