<?php 
/*
*所有审核用户集合
*/
namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf8");
class AuditedController extends BaseController{
    //审核通过列表
	public function index(){
        $where['maudit'] = 2;
        $count = M('Loan')->where($where)->count();
        $Page = new \Think\Page($count,20);
        $data = M()->table('free_user user,free_loan loan')
        ->where('user.user_id=loan.user_id and user.audit=2 and loan.maudit=2')
        ->order('loan.loan_id desc')
        ->limit($Page->firstRow.','.$Page->listRows)
        ->select();
        $show = $Page->show();
        $this->assign('page',$show);
        $this->assign('data',$data);
        $this->assign('count',$count);
		$this->display();
	}
    //审核不通过列表
    public function npass(){
        $where['maudit'] = 1;
        $count = M('Loan')->where($where)->count();
        $Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
        $ndata = M()->table('free_user user,free_loan loan')
        ->where('user.user_id=loan.user_id and (user.audit=1 or loan.maudit=1)')
        ->order('loan.loan_id desc')->limit($Page->firstRow.','.$Page->listRows)
        ->select();
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('ndata',$ndata);
        $this->assign('count',$count);
        $this->display();
    }
}