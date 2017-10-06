<?php
//续期还款
namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf8");
class RenewalController extends BaseController{
	public function index(){
		header("Content-Type:text/html;charset=utf-8");
		$count = M()->table('free_user user,free_loan loan,free_continued continued')
		->where("user.user_id=loan.user_id and loan.user_id=continued.user_id and continued.is_kq=0")->count();
		$Page = new \Think\Page($count,15);
		$info = M()->table('free_user user,free_loan loan,free_continued continued')
		->where("user.user_id=loan.user_id and loan.user_id=continued.user_id and continued.is_kq=0")
		->order('overday_time asc')
		->limit($Page->firstRow.','.$Page->listRows)
		->select();
		$show = $Page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
		$this->assign('info',$info);
		$this->display();
	}
	public function renewal(){
		header("Content-Type:text/html;charset=utf-8");
		$count = M()->table('free_user user,free_loan loan,free_continued continued')
		->where("user.user_id=loan.user_id and loan.user_id=continued.user_id and continued.is_kq=1")->count();
		$Page = new \Think\Page($count,15);
		$info = M()->table('free_user user,free_loan loan,free_continued continued')
		->where("user.user_id=loan.user_id and loan.user_id=continued.user_id and continued.is_kq=1")
		->order('overday_time asc')
		->limit($Page->firstRow.','.$Page->listRows)
		->select();
		$show = $Page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
		$this->assign('info',$info);
		$this->display();
	}


	public function ll_pay(){
		header("Content-Type:text/html;charset=utf-8");

		$continued=M('continued');
		$where['is_kq']=2;
		$count=$continued->where($where)
		->join('free_user ON free_user.user_id=free_continued.user_id')
		->join('free_loan ON free_loan.user_id=free_continued.user_id')
		->count();

		$Page = new \Think\Page($count,20);
		$continued_data=$continued->field('free_user.user_name,free_user.u_name,free_continued.pay_time,free_continued.repayment_time,free_loan.loan_amount,free_loan.loan_time,renewal_days,renewal_num,pay_money,free_continued.renewal_order')->where($where)
		->join('free_user ON free_user.user_id=free_continued.user_id')
		->join('free_loan ON free_loan.user_id=free_continued.user_id')
		->order('free_continued.repayment_time desc')
		->limit($Page->firstRow.','.$Page->listRows)
		->select();

		$show = $Page->show();

		$time=strtotime(date("Y-m-d",time()));
		$this->assign('time',$time);
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('continued_data',$continued_data);
		$this->display();

		
	}

}
