<?php
/*
*用户搜索
*/
namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf8");
class SearchController extends BaseController{
	public function index(){

		$this->display();
	}
	
	public function detail(){
		if(!$_POST){
			die('<script>alert("请输入内容");history.back()</script>');
		}
		$post=I('post.');

		$mobile=preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,1,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $post['search']);
		if($mobile){
//手机号搜索
			$where['user_name']=$post['search'];
		}else{
			$identity=preg_match('/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{2}$)/',$post['search']);
			if($identity){
//身份证搜索	
				$where['identity']=$post['search'];
			}else{
//姓名搜索				
				$where['u_name']=$post['search'];
			}
		}
		$user_data=D('user')->where_select($where);
		if(!$user_data){
			die('<script>alert("没有找到该用户，请检查输入");history.back()</script>');
		}


		$loan_data=D('loan')->user_find($user_data[0]['user_id']);
		$record_data=D('record')->user_select($user_data[0]['user_id']);
		$continued_data=D('continued')->user_select($user_data[0]['user_id']);
		$feed_data=D('feed')->user_select($user_data[0]['user_id']);
		
		if($loan_data['renewal_days']>0){
			$renewal_days=$loan_data['renewal_days'];
			foreach ($continued_data as $key => $value) {
				$renewal_days=$renewal_days-$value['continued_day'];
				if($renewal_days>=0){
					$renewal_key=$key+1;
				}
			}
			$loan_renewal=array_chunk($continued_data,$renewal_key);
			
			$loan_data['xudetail']=$loan_renewal[0];
			foreach ($loan_renewal as $key => $value) {
				if($key!=0){
					foreach ($value as $k => $v) {
						$new_continued_data[]=$v;
					}
				}
			}
		}

		$tie=0;
		foreach ($record_data as $key => &$value) {
			if($value['xutime']>0){
				$xutime=$value['xutime'];
				foreach ($new_continued_data as $k => $v) {
					if($k==$tie){
						$xutime=$xutime-$v['continued_day'];
						if($xutime>=0){
							$value['xudetail'][$k]=$v;
							$tie=$k+1;
							
						}
					}
				}
			}
		}

		$this->loan_data=$loan_data;
		$this->user_data=$user_data;
		$this->record_data=$record_data;
		$this->feed_data=$feed_data;
		$this->display();
	}
}
