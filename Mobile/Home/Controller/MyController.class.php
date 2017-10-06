<?php
/*
我的页面
*/
namespace Home\Controller;
use Think\Controller;
use Home\Model\MoneyModel as MoneyModel;
header("content-type:text/html;charset=utf8");
class MyController extends Base { 
	public function index(){
		if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
        $user=M('user');
        $loan=M('loan');     
        $where['user_name']=$_SESSION['name'];
        $user_data=$user->where($where)->find();
        $loan_data=$loan->where($where)->find();
        $judge="ok";
        if($user_data['message'] == 1){
          	$xiane=MoneyModel::xiane($user_data['lines'],$loan_data['loan_lines']);
         	$yue=$xiane;
            $judge="ok";
            if($loan_data['is_loan']==1){
            	$judge="no";
                $yue=$xiane - $loan_data['loan_amount'];
                $perc=($yue/$xiane)*100;
            }
        }
        if($yue<0){
            $yue=0;
        }
        $initial=MoneyModel::initial();
        $this->assign('initial',$initial);
        $this->assign('judge',$judge);
        $this->assign('user_data',$user_data);
        $this->assign('loan_data',$loan_data);
        $this->assign('xiane',$xiane);
        $this->assign('yue',$yue);
        $this->assign('perc',$perc);
        $this->display('my/index');
	}
    public function customer(){
        $this->display('my/customer');
    }
}