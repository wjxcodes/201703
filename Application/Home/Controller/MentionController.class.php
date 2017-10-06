<?php 
/*
*配置提额降息
*/
namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf8");
class MentionController extends BaseController{
	public function index(){
		$mention=M('mention');
		$mention_data=$mention->select();
		$this->mention_data=$mention_data;
		$this->display();
	}
	public function add(){
		if($_POST){
			$post=I('post.');
			if($post['money']==''){
				die("<script>alert('金额不能为空！');history.back();</script>");;
			}
			$mention=M('mention');
			$save['now_money']=$post['money'];
			$save['sum_money']=$post['money'];
			$save['create_time']=time();
			$save['create_per']=session('aname');
			$res=$mention->add($save);
			if($res){
				die("<script>alert('添加成功！');window.location.href='index';</script>");
			}else{
				die("<script>alert('添加失败！');window.location.href='index';</script>");;
			}
		}
		$this->display();
	}
	public function delete(){
		if($_GET){
			$get=I('get.');
			$mention=M('mention');
			$where['id']=$get['id'];
			$save['is_use']=1;
			$res=$mention->where($where)->save($save);
			if($res){
				die("<script>window.location.href='index';</script>");
			}else{
				die("<script>alert('移除失败！');window.location.href='index';</script>");;
			}
		}
	}

	public function coupons(){
		if($_GET){
			$get=I('get.');
			$coupons_model=M('coupons');
			$coupons_where['mention_id']=$get['id'];
			/*$coupons_count=$coupons_model->where($coupons_where)->count();
			$Page = new \Think\Page($coupons_count,15);*/
			$coupons_data=$coupons_model->where($coupons_where)->select();

			foreach ($coupons_data as $key => $value) {
				if($value['is_use']==1){
					$data['sum_lines']+=$value['lines'];
				}
			}
			$this->data=$data;
			//$show = $Page->show();
        	$this->page=$show; 
			$this->coupons_data=$coupons_data;
			$this->display();
		}
	}

	public function sum(){
		$coupons=M('coupons');
		$s_time=strtotime(date("Y-m-d",strtotime('-1 day')));
		$e_time=$s_time+86400;
		$where['create_time']=array(array('EGT',$s_time),array('LT',$e_time));
		$coupons_data=$coupons->where($where)->select();
		//var_dump($coupons_data);
		foreach ($coupons_data as $key => $value) {
			$sum_money+=$value['lines'];
			$i+=1;
		}
		var_dump($i);
		var_dump($sum_money);
	}

	public function test(){
		$user_name='18756173056';
		$a=$this->bulid($user_name);
		var_dump($a);
	}

	public function bulid($user_name){
	
			
			
		$money=200;
       
			$coupons=M('coupons');
			$save['user_name']=$user_name;
			$save['coupons_type']=2;
			$save['create_time']=time();
			$save['overdue_time']=strtotime(date('Y-m-d',strtotime('+1 day')))+86339;
			$save['lines']=$money;
			$save['mention_id']=$mention_data['id'];

			$coupons_res=$coupons->add($save);

					
			
		return $return_money;
	}


}