<?php
/*
借款记录页面
*/
namespace Home\Controller;
use Think\Controller;
use Home\Model\MoneyModel as MoneyModel;
use Home\Model\RepayModel as RepayModel;
class RecordController extends Controller {

/*  借款记录  */
    public function index(){
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
        $where['user_name']=$_SESSION['name'];
        $loan =M('loan');
        $record=M('record');
        $loan_data=$loan->where($where)->find();
        $record_data=$record->field('user_id,user_name,repayment_money,request_time,is_overdue')->where($where)->select(); 
      
        if($loan_data['loan_time']==1){
            $loan_time=7;
        }else if($loan_data['loan_time']==2){
            $loan_time=14;
        }
/*  逾期费用  */
            
        $overdue_show=RepayModel::overdue_show($loan_data['is_pay'],$loan_time,$loan_data['renewal_days'],$loan_data['loan_amount']);
        $day_time=strtotime(date('Y-m-d',$overdue_show['time']))+86399;
        if($day_time==$overdue_show['time']){
            $this->dueday=1;
        }

        $this->overdue_show=$overdue_show;
        $this->loan_data=$loan_data;
        $this->record_data=$record_data;
        $this->display('record/index');
    }



    public function detail(){
    	if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
        $user=M('user');
        $loan=M('loan');
        $where['user_name']=$_SESSION['name'];
        $user_data=$user->where($where)->find();
        $loan_data=$loan->where($where)->find();

        if($loan_data['loan_time']==1){
           $loan_time=7;
        }else if($loan_data['loan_time']==2){
           $loan_time=14;
        }
/*  逾期费用  */
        if($loan_data['is_pay']==0||$loan_data['is_pay']==null||$loan_data['is_pay']==''){
            
        }else{
            $overdue_show=RepayModel::overdue_show($loan_data['is_pay'],$loan_time,$loan_data['renewal_days'],$loan_data['loan_amount']);
        }

        $day_time=strtotime(date('Y-m-d',$loan_data['is_pay']))+86399;
        if($day_time==$loan_data['is_pay']){
          $this->dueday=1;
        }
/*  手续费   */
        $shouxufei=MoneyModel::shouxufei($loan_data['loan_amount'],$loan_data['interest'],$loan_data['cuts_interest']);
        $this->loan_time=$loan_time;
        $this->overdue_show=$overdue_show;
        $this->shouxufei=$shouxufei;
        $this->user_data=$user_data;
        $this->loan_data=$loan_data;
        $this->display('record/detail');
    }
}