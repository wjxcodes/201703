<?php
namespace Home\Controller;
use Home\Model\RepayModel as RepayModel;
use Home\Model\MoneyModel as MoneyModel;
use Home\Model\WeixinModel as WeixinModel;
use Home\Model\LLModel as LLModel;
use llpay\llpay_submit;
use llpay\llpay_notify;
use llpay\llpay_cls_json;
header("content-type:text/html;charset=utf8");
class PendingController extends BaseController{
    protected $user_model;
    protected $loan_model;
    protected $inv_model;
    protected $feed_model;
    public function __construct(){
        parent::__construct();
        $this->user_model = M('User');
        $this->loan_model = M('Loan');
        $this->inv_model = M('Invitation');
        $this->feed_model = M('Feed');
    }
	public function index(){
         $PendingInfo =M()->table('free_user user, free_loan loan')
         ->where('user.user_id = loan.user_id AND loan.maudit=2 AND loan.loan_request !=0 AND loan.is_pay=0')
         ->field('user.open_id,user.user_name,user.bank_name,user.bank_card,user.identity,loan.loan_request,loan.loan_time,loan.loan_amount,loan.loan_id,loan.automatic')
         ->select();
         $this->assign('PendingInfo',$PendingInfo);
		 $this->display();
	}
    public function change(){
            $loan_id = I('get.id');
            $status = I('get.status');
            $where['loan_id'] = $loan_id;
            //借款表和用户表
            $access_token = WeixinModel::getToken();
            $res = $this->loan_model->where($where)->find();
            $map['user_id'] = $res['user_id'];
            $info = $this->user_model->where($map)->find();
            if ($status == 1) {
                $timewen = date('Y-m-d',time());
                $data['is_pays'] = time();
                $data['is_pay'] = strtotime($timewen)+86399;
                if ($res['loan_time'] == 1) {
                    $data['overday_time'] = $data['is_pay']+7*86400;
                }elseif ($res['loan_time'] == 2) {
                    $data['overday_time'] = $data['is_pay']+14*86400;
                }
                $result = $this->loan_model->where($where)->save($data);
                $inv_map['invitation_id'] = $res['user_id'];
                $inv_data['loan_code'] = 1;
                $inv_data['money'] = 20;
                $inv_data['loan_time'] = time();
                $inv_result = $this->inv_model->where($inv_map)->save($inv_data);
                if ($result) {
                    $data = M()->table('free_user user,free_loan loan')
                    ->where("user.user_id=loan.user_id and loan.user_id='$map[user_id]'")
                    ->find();
                    $bool = WeixinModel::sex( $data['identity']);
                    $sex = $bool?"先生":"女士";
                    $time = date('Y年m月d日',$data['loan_request']);
                    $money = $data['loan_amount'];
                    // 短信提醒
                    $dataSms ="尊敬的".$data['u_name'].$sex."，您".$time."在蜻蜓白卡借款".$money."元已成功汇到您的银行卡！";
                    $mobile = $data['user_name'];
                    WeixinModel::sendSms($dataSms,$mobile);
                    $contnet = "尊敬的".$data['u_name'].$sex."，您".$time."在蜻蜓白卡借款".$money."元已成功汇到您的银行卡！";
                    WeixinModel::sendWeixin($data['open_id'],$contnet,$data['loan_amount'],date('Y-m-d H:i',$data['loan_request']),$access_token);
                    $this->redirect('Home/Pending/index');
                }else{
                echo '<script>alert("网络缘故审核提交失败,请稍后再试！！！");history.back()</script>';
                exit;
                }
            }else{
                echo '<script>alert("非法提交！！！");</script>';
                exit;
            }
        }

    public function changes(){
        $loan_id = I('get.id');
        $where['loan_id'] = $loan_id;
        $res = $this->loan_model->where($where)->find();
        $user_info=$this->user_model->where($where)->find();
        $map['user_id'] = $res['user_id'];
        $data_loan['maudit'] = 0;
        $data_loan['loan_amount'] = 0;
        $data_loan['loan_request'] = 0;
        $data_loan['is_loan'] = 0;
        $data_loan['loan_time'] = 0;
        $info_loan = $this->loan_model->where($where)->save($data_loan);
        $data_user['audit'] = 0;
        $data_user['au_request'] = 0;
        $data_user['alter_info'] = 0;
        $info_user = $this->user_model->where($map)->save($data_user);

        /*不通过记录开始*/
                $feed_save['user_id']=$user_info['user_id'];
                $feed_save['u_name']=$user_info['u_name'];
                $feed_save['identity']=$user_info['identity'];
                $feed_save['loan_order']=$res['loan_order'];
                $feed_save['loan_amount']=$res['loan_amount'];
                $feed_save['reject_state']=1;
                $feed_save['create_time']=time();
                $this->feed_model->add($feed_save);
        /*不通过记录结束*/

        if ($info_loan && $info_user) {
            echo "<script>alert('取消打款成功！');</script>";
            $this->redirect('Home/Pending/index');
            exit;
        }else{
            echo '<script>alert("取消打款失败，请联系17633713110");history.back()</script>';
        }
    }
}