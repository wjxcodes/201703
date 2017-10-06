<?php
namespace Home\Controller;
use phpDocumentor\Reflection\Types\Null_;
use Think\Controller;
use Home\Model\RepayModel as RepayModel;
use Home\Model\MoneyModel as MoneyModel;
use Home\Model\WeixinModel as WeixinModel;
use Home\Model\LLModel as LLModel;
use Home\Model\LdfModel as LdfModel;
use llpay\llpay_submit;
use llpay\llpay_notify;
use llpay\llpay_cls_json;
use Dfll\llpay_apipost_submit;
use Dfll\llpay_security;
class WithholdController extends Controller{
	protected $user_model;
    protected $loan_model;
    protected $record_model;
	protected $auth_model;
	public function __construct(){
		parent::__construct();
		$this->user_model = M('User');
        $this->loan_model = M('Loan');
        $this->record_model = M('Record');
		$this->record_model = M('Record');
        $this->debit_model = M('Debit');
        $this->auth_model = M('Auth');
        $base_where['username']=session('aname');
        $base_data=$this->auth_model->field('auth')->where($base_where)->find();
        $base_auth=explode('-', $base_data['auth']);
        $this->base_auth=$base_auth;
	}
    public function index(){
        $map['is_loan'] = 1;
        $loan_info = $this->loan_model
                     ->where($map)
                     ->field('user_name,is_pay,is_pays,loan_amount,repayment_no,loan_time,field,bank_card,is_loan,loan_num,renewal_days,overday_time,auth_time,ll_code,interest,cuts_interest')
                     ->select();
        $i = 0;
        foreach ($loan_info as $k => $v) {
            if($v['loan_time']==1){
                $loan_time=7;
            }else if($v['loan_time']==2){
                $loan_time=14;
            }
            $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
            $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
            $money=$v['loan_amount']+$shouxufei;
            $be_time=$v['is_pay']+$loan_time*86400+$v['renewal_days']*86400;
            $start_time = strtotime(date('Y-m-d',strtotime('+1 day')));
            $end_time = strtotime(date('Y-m-d',strtotime('+2 day')));
            if ($be_time>$start_time && $be_time<$end_time) {
                $info[$i]['user_name'] = $v['user_name'];
                $info[$i]['bank_card'] = $v['bank_card'];
                $info[$i]['is_pay'] = $v['is_pay'];
                $info[$i]['is_pays'] = $v['is_pays'];
                $info[$i]['loan_amount'] = $v['loan_amount'];
                $info[$i]['loan_time'] = $v['loan_time'];
                $info[$i]['renewal_days'] = $v['renewal_days'];
                $info[$i]['repayment_no'] = $v['repayment_no'];
                $info[$i]['loan_num'] = $v['loan_num'];
                $info[$i]['overday_time'] = $v['overday_time'];
                $info[$i]['auth_time'] = $v['auth_time'];
                $info[$i]['ll_code'] = $v['ll_code'];
                $i = $i+1;
            }
        }
        $this->assign('loan_info',$info);
        $this->display();
    }

    public function implement(){
        $map['is_loan'] = array('eq',1);
        $map['ll_code'] = array('eq',1);
        $loan_info = $this->loan_model
                     ->where($map)
                     ->field('user_name,ll_status,is_pays,is_pay,renewal_days,loan_amount,repayment_no,loan_time,field,bank_card,is_loan,auth_time,auth_order,identity,loan_num,ll_code,overday_time')
                     ->select();
        $i = 0;
        foreach ($loan_info as $k => $v) {
            if($v['loan_time']==1){
                $loan_time=7;
            }else if($v['loan_time']==2){
                $loan_time=14;
            }
            $be_time=$v['is_pay']+$loan_time*86400+$v['renewal_days']*86400;
            $start_time = strtotime(date('Y-m-d',time()));
            $end_time = strtotime(date('Y-m-d',strtotime('+1 day')));
            if ($be_time>$start_time && $be_time<$end_time) {
                $data[$i]['user_name'] = $v['user_name'];
                $data[$i]['bank_card'] = $v['bank_card'];
                $data[$i]['is_pay'] = $v['is_pay'];
                $data[$i]['is_pays'] = $v['is_pays'];
                $data[$i]['loan_amount'] = $v['loan_amount'];
                $data[$i]['loan_time'] = $v['loan_time'];
                $data[$i]['overday_time'] = $v['overday_time'];
                $data[$i]['renewal_days'] = $v['renewal_days'];
                $data[$i]['repayment_no'] = $v['repayment_no'];
                $data[$i]['loan_num'] = $v['loan_num'];
                $data[$i]['ll_code'] = $v['ll_code'];
                $data[$i]['auth_time'] = $v['auth_time'];
                $data[$i]['ll_status'] = $v['ll_status'];
                $i=$i+1;
            }
        }
        $this->assign('loan_info',$data);
        $this->display();
    }
    /*public function implem(){
        $map['is_loan'] = array('eq',1);
        $map['ll_code'] = array('eq',1);
        $loan_info = $this->loan_model
            ->where($map)
            ->field('user_name,ll_status,is_pays,is_pay,renewal_days,loan_amount,repayment_no,loan_time,field,bank_card,is_loan,auth_time,auth_order,identity,loan_num,ll_code,overday_time')
            ->select();
        $i = 0;
        foreach ($loan_info as $k => $v) {
            if($v['loan_time']==1){
                $loan_time=7;
            }else if($v['loan_time']==2){
                $loan_time=14;
            }
            $be_time=$v['is_pay']+$loan_time*86400+$v['renewal_days']*86400;
            $start_time = strtotime(date('Y-m-d',strtotime('-1 day')));
            $end_time = strtotime(date('Y-m-d',time()));
            if ($be_time>$start_time && $be_time<$end_time) {
                $data[$i]['user_name'] = $v['user_name'];
                $data[$i]['bank_card'] = $v['bank_card'];
                $data[$i]['is_pay'] = $v['is_pay'];
                $data[$i]['is_pays'] = $v['is_pays'];
                $data[$i]['loan_amount'] = $v['loan_amount'];
                $data[$i]['loan_time'] = $v['loan_time'];
                $data[$i]['overday_time'] = $v['overday_time'];
                $data[$i]['renewal_days'] = $v['renewal_days'];
                $data[$i]['repayment_no'] = $v['repayment_no'];
                $data[$i]['loan_num'] = $v['loan_num'];
                $data[$i]['ll_code'] = $v['ll_code'];
                $data[$i]['auth_time'] = $v['auth_time'];
                $data[$i]['ll_status'] = $v['ll_status'];
                $i = $i+1;
            }
        }
        $this->assign('loan_info',$data);
        $this->display();
    }*/
    public function implem(){
        $count = $this->debit_model->where('1=1')->count();
        $Page = new \Think\Page($count,15);
        $show = $Page->show();
        $return_debit = $this->debit_model
                        ->where($map)
                        ->field('u_name,user_name,bank_card,loan_time,repayment_no,query_money,sign_time,lev_sign,result,update_time')
                        ->order('id desc')
                        ->limit($Page->firstRow.','.$Page->listRows)
                        ->select();
        $this->assign('loan_info',$return_debit);
        $this->assign('page',$show);
        $this->display();
    }
    public function  surrenderview(){
        if ($_POST){
            $user_name = trim(I('post.user_name'));
            $agree = trim(I('post.agree'));
            $boolMobile = $this->isMobile($user_name);
            $boolAgree = strlen($agree);
            if ($boolAgree == 16 && $boolMobile){
                $return_info = $this->surrender($user_name,$agree);
                dump($return_info);die;
                if ($return_info){

                }
            }else{
                echo '<script>alert("填写数据有错误");history.back();</script>';
            }
        }else{
            $this->display();
        }
    }
    public function isMobile($mobile) {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,1,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }
	public function ll_sign(){
        $aname = session('aname');
        if ($aname == 'caomiaoFcb02' || $aname == 'companyauth' || $aname == 'ww258a1GH') {
            $map['is_loan'] = 1;
            $loan_info = $this->loan_model
                ->where($map)
                ->field('user_id,user_name,is_pay,renewal_days,loan_amount,loan_time,field,bank_card,is_loan,interest,cuts_interest')
                ->select();
            foreach ($loan_info as $k => $v) {
                if($v['loan_time']==1){
                    $loan_time=7;
                }else if($v['loan_time']==2){
                    $loan_time=14;
                }
                $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
                $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
                $money=$v['loan_amount']+$shouxufei;
                $be_time=$v['is_pay']+$loan_time*86400+$v['renewal_days']*86400;
                $start_time = strtotime(date('Y-m-d',strtotime('+1 day')));
                $end_time = strtotime(date('Y-m-d',strtotime('+2 day')));
                if ($be_time>$start_time && $be_time<$end_time) {
                    $return_sign = $this->information($v['user_name'],$v['bank_card']);
                    $where['user_name'] = $v['user_name'];
                    if ($return_sign) {
                        $data = date('Y-m-d',strtotime('+1 day'));
                        $money = $money.'.00';

                        $idlen=strlen($v['user_id']);
                        $orderN=$this->num();
                        $loanOrder=substr_replace($orderN,$v['user_id'],8,$idlen);
                        $orderNum=substr($loanOrder, 0, -1);

                        $return_autho = $this->authorization($v['user_name'],$data,$money,$orderNum,$v['bank_card']);
                        $loan_save['ll_code'] = $return_autho['ret_code '];
                        if ($return_autho['ret_code'] == 0000 && $return_autho['ret_msg'] == '交易成功') {

                            $id_len=strlen($v['user_id']);
                            $order=$this->num();
                            $loan_order=substr_replace($order,$v['user_id'],8,$id_len);
                            $this_order=substr($loan_order, 0, -1);
                            $loan_save['auth_order'] = $this_order;
                            $loan_save['repayment_no'] = $orderNum;
                            $loan_save['ll_code'] = 1;
                            $loan_save['auth_time'] = $data;
                            $boll = $this->loan_model->where($where)->save($loan_save);
                            if ($boll){
                                unset($loan_save['auth_order']);
                                unset($loan_save['repayment_no']);
                                unset($loan_save['ll_code']);
                                unset($loan_save['auth_time']);
                            }
                        }else{
                            $loan_save['ll_code'] = $return_autho['ret_code '];
                            $this->loan_model->where($where)->save($loan_save);
                        }
                    }else{
                        $loan_save['ll_code'] = 2;
                        $this->loan_model->where($where)->save($loan_save);
                    }
                }
            }
            $this->redirect('Withhold/index');
        }else{
            echo "<script>alert('没有权限，联系管理员开通。');history.back();</script>";die;
        }
	}
//	代扣当天到期的
	public function debit(){
        $aname = session('aname');
        if ($aname == 'caomiaoFcb02' || $aname == 'companyauth' || $aname == 'ww258a1GH') {
            $map['is_loan'] = array('eq',1);
            $map['ll_code'] = array('eq',1);
            $loan_info = $this->loan_model
                ->where($map)
                ->join('free_user ON free_user.user_id=free_loan.user_id')
                ->field('free_loan.user_id,free_loan.user_name,is_pays,is_pay,renewal_days,loan_amount,repayment_no,loan_time,field,free_loan.bank_card,is_loan,auth_time,auth_order,free_loan.identity,interest,create_time,u_name,cuts_interest')
                ->select();
            $i = 1;
            foreach ($loan_info as $k => $v) {
                if($v['loan_time']==1){
                    $loan_time=7;
                }else if($v['loan_time']==2){
                    $loan_time=14;
                }
                $be_time=$v['is_pay']+$loan_time*86400+$v['renewal_days']*86400;
                $start_time = strtotime(date('Y-m-d',time()));
                $end_time = strtotime(date('Y-m-d',strtotime('+1 day')));
                if ($be_time>$start_time && $be_time<$end_time) {
                    $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
                    $money=$v['loan_amount']+$shouxufei;
                    $money = $money.'.00';

                    $return_agree = $this->information($v['user_name'],$v['bank_card']);
                    $register = date('Y-m-d',$v['create_time']);
                    $actionDk[$i]['user_id'] = $v['user_id'];
                    $actionDk[$i]['user_name'] = $v['user_name'];
                    $actionDk[$i]['auth_order'] = $v['auth_order'];
                    $actionDk[$i]['auth_time'] = $v['auth_time'];
                    $actionDk[$i]['is_pays'] = date('YmdHis',$v['is_pays']);
                    $actionDk[$i]['money'] = $money;
                    $actionDk[$i]['register'] = $register;
                    $actionDk[$i]['u_name'] = $v['u_name'];
                    $actionDk[$i]['identity'] = $v['identity'];
                    $actionDk[$i]['repayment_no'] = $v['repayment_no'];
                    $actionDk[$i]['loan_amount'] = $v['loan_amount'];
                    $actionDk[$i]['no_agree'] = $return_agree['no_agree'];
                    $i = $i+1;
                }
            }
            foreach($actionDk as $k=>$v){
                $where_user['user_name'] = $v['user_name'];
                $return_debit = $this->ll_debit($v['user_name'],$v['auth_order'],$v['auth_time'],$v['is_pays'],$v['money'],$v['register'],$v['u_name'],$v['identity'],$v['repayment_no'],$v['no_agree']);
                if ($return_debit['ret_code'] == 0000 && $return_debit['ret_msg'] == '交易成功') {
                }else{
                    $ll_data['ll_status'] = $return_debit['ret_msg'];
                    $code_time = strtotime(date('Y-m-d',time()))+43200;
                    if ($code_time > time()){
                        $id_len=strlen($v['user_id']);
                        $order=$this->num();
                        $loan_order=substr_replace($order,$v['user_id'],8,$id_len);
                        $this_order=substr($loan_order, 0, -1);
                        $ll_data['auth_order'] = $this_order;
                        $this->loan_model->where($where_user)->save($ll_data);
                        unset($ll_data['auth_order']);
                        $dataSms = "【蜻蜓卡】尊敬的".$v['u_name']."，您好，由于您的收款银行卡余额不足，本次代还款失败，请您于今日18点前预存足够金额".$v['money']."元，以便我们再次扣款。";
                        WeixinModel::bomber($v['user_name'],$dataSms);
                    }else{

                        $id_len=strlen($v['user_id']);
                        $order=$this->num();
                        $loan_order=substr_replace($order,$v['user_id'],8,$id_len);
                        $this_order=substr($loan_order, 0, -1);
                        $ll_data['auth_order'] = $this_order;
                        $this->loan_model->where($where_user)->save($ll_data);
                        unset($ll_data['auth_order']);
                        $money =$v['money']+ceil($v['loan_amount']*0.015);
                        $dataSms = "【蜻蜓卡】尊敬的".$v['u_name']."，您好，由于您的收款银行卡余额不足，本次代还款失败，请您于明日9点前预存足够金额".$money."元，以便我们再次扣款。";
                    }
                    
                }
            }
            $this->redirect('Withhold/implement');
        }else{
            echo "<script>alert('没有权限，联系管理员开通。');history.back();</script>";die;
        }
	}
    public function querySignHtml(){
        if ($_POST) {
            $user_name = trim(I('post.user_name'));
            $bool  =$this->isMobile($user_name);
            if (!$bool){
               echo '<script>alert("手机号有误");history.back()</script>';
            }
            $result = $this->querySign($user_name);
            foreach ($result['agreement_list'] as $k => $v) {
                $data[$k]['no_agree'] = $v['no_agree'];
                $data[$k]['card_no'] = $v['card_no'];
                $data[$k]['bank_name'] = $v['bank_name'];
            }
            $this->assign('data',$data);
            $this->display();
        }else{
            $this->display();
        }
    }
	/********************************************************用户签约信息询查询API接口**************/
	public function information($user_name,$bank_card){

		$llpay_config=LLModel::llconfig();
		$pay_type  = 'D';
		$sign_type  = 'RSA';
		$offset   = '0';
		$parameter = array (
			"oid_partner" => trim($llpay_config['oid_partner']),
			"user_id" => $user_name,
			"pay_type" => $pay_type,
			"sign_type" => $sign_type,
			"offset" => $offset,
		);
		$llpay_gateway_new = 'https://queryapi.lianlianpay.com/bankcardbindlist.htm';
		$llpaySubmit = new LLpay_submit($llpay_config,$llpay_gateway_new);
		$html_text = $llpaySubmit->buildRequestJSON($parameter,$llpay_gateway_new);
		$data_info = json_decode($html_text,true);
		if ($data_info['ret_code'] == '0000') {
			$bank_card = substr($bank_card,-4); 
			foreach ($data_info['agreement_list'] as $k => $v) {
				if ($bank_card == $v['card_no']) {
					$no_agree['bank_name'] = $v['bank_name'];
					$no_agree['card_no'] = $v['card_no'];
					$no_agree['no_agree'] = $v['no_agree'];
				}
			}
			return $no_agree;
		}
		return $data_info['ret_code'];
	}

    public function formation($user_name,$bank_card){

        $llpay_config=LLModel::llconfig();
        $pay_type  = 'D';
        $sign_type  = 'RSA';
        $offset   = '0';
        $parameter = array (
            "oid_partner" => trim($llpay_config['oid_partner']),
            "user_id" => $user_name,
            "pay_type" => $pay_type,
            "sign_type" => $sign_type,
            "offset" => $offset,
        );
        $llpay_gateway_new = 'https://queryapi.lianlianpay.com/bankcardbindlist.htm';
        $llpaySubmit = new LLpay_submit($llpay_config,$llpay_gateway_new);
        $html_text = $llpaySubmit->buildRequestJSON($parameter,$llpay_gateway_new);
        $data_info = json_decode($html_text,true);
        if ($data_info['ret_code'] == '0000') {
            $bank_card = substr($bank_card,-4); 
            foreach ($data_info['agreement_list'] as $k => $v) {
                if ($bank_card == $v['card_no']) {
                    return $v['no_agree'];
                }
            }
        }
        return 9999;
    }
	/********************************************************************************用户签约信息询查询API接口**************/
	/********************************************************************************授权请申请API **************/
	public function authorization($user_name,$data,$moeny,$no_order,$bank_card){
        $llpay_config=LLModel::llconfig();
        $sign_type  = 'RSA';
        $api_version  = '1.0';
        $repayment_plan = '{\"repaymentPlan\":[{\"date\":\"'.$data.'\",\"amount\":\"'.$moeny.'\"}]}';
        $repayment_no = $no_order;//还款计划编号
        $sms_param  = '{\"contract_type\":\"蜻蜓白卡\",\"contact_way\":\"0378-58576913\"}';
        $pay_type  = 'D';
        $return_no_agree = $this->information($user_name,$bank_card);
        $no_agree = $return_no_agree['no_agree'];//签约协议号 
        $parameter = array (
            "user_id" => $user_name,
            "oid_partner" => trim($llpay_config['oid_partner']),
            "sign_type" => $sign_type,
            "api_version" => $api_version,
            "repayment_plan" => $repayment_plan,
            "sms_param" => $sms_param,
            "repayment_no" =>$repayment_no,
            "pay_type" => $pay_type,
            "no_agree" => $no_agree,
        );
        $llpay_gateway_new = 'https://repaymentapi.lianlianpay.com/agreenoauthapply.htm';
        $llpaySubmit = new LLpay_submit($llpay_config,$llpay_gateway_new);
        $html_text = $llpaySubmit->buildRequestJSON($parameter,$llpay_gateway_new);
        $data_info = json_decode($html_text,true);
        return $data_info;
    }
/********************************************************************************授权请申请API **************/
/********************************************************************************银行卡还款扣款接口**************/
public function ll_debit($user_name,$no_order,$schedule_repayment_date,$dt_order,$money_order,$register,$acct_name,$id_no,$repayment_no,$no_agree){
	$llpay_config=LLModel::llconfig();
	$busi_partner = '101001';
	$api_version  = '1.0';
	$name_goods = "蜻蜓白卡";
	$notify_url  = "https://ziyouqingting.com/free/index.php/Home/Withhold/return_no";
	$risk_item ='{\"frms_ware_category\":\"2010\",\"user_info_mercht_userno\":\"'.$user_name.'\",\"user_info_bind_phone\":\"'.$user_name.'\",\"user_info_dt_register\":\"'.$register.'\",\"user_info_full_name\":\"'.$acct_name.'\",\"user_info_id_no\":\"'.$id_no.'\",\"user_info_identify_type\":\"1\",\"user_info_identify_state\":\"1\"}';
	$pay_type  = 'D';
	$parameter = array (
    	"user_id" => $user_name,
    	"oid_partner" => trim($llpay_config['oid_partner']),
    	"sign_type" => strtoupper('RSA'),
    	"busi_partner" => $busi_partner,
    	"api_version" => $api_version,
    	"no_order" => $no_order,
    	"dt_order" =>$dt_order,
    	"name_goods" =>$name_goods,
    	"money_order" =>$money_order,
    	"notify_url" =>$notify_url,
    	"risk_item" => $risk_item,
    	"schedule_repayment_date" =>$schedule_repayment_date,
    	"repayment_no" =>$repayment_no,
    	"pay_type" => $pay_type,
    	"no_agree" => $no_agree
	);
	$llpay_gateway_new = 'https://repaymentapi.lianlianpay.com/bankcardrepayment.htm';
	$llpaySubmit = new LLpay_submit($llpay_config,$llpay_gateway_new);
	$html_text = $llpaySubmit->buildRequestJSON($parameter,$llpay_gateway_new);
	$data_info = json_decode($html_text,true);
    return $data_info;
}

/********************************************************************************银行卡还款扣款接口**************/
/********************************************************************************用户签约信息询查询API接口**************/
public function querySign($user_name){
    $llpay_config=LLModel::llconfig();
    $pay_type  = 'D';
    $sign_type  = 'RSA';
    $offset   = '0';
    //构造要请求的参数数组
    $parameter = array (
        "oid_partner" => trim($llpay_config['oid_partner']),
        "user_id" => $user_name,
        "pay_type" => $pay_type,
        "sign_type" => $sign_type,
        "offset" => $offset,
    );
    $llpay_gateway_new = 'https://queryapi.lianlianpay.com/bankcardbindlist.htm';
    $llpaySubmit = new LLpay_submit($llpay_config,$llpay_gateway_new);
    $html_text = $llpaySubmit->buildRequestJSON($parameter,$llpay_gateway_new);
    $data_info = json_decode($html_text,true);
    return $data_info;
}
/********************************************************************************用户签约信息询查询API接口**************/
/********************************************************************************解约**************/
public function surrender($user_name,$no_agree){
    $llpay_config=LLModel::llconfig();
    $sign_type  = 'RSA';
    $pay_type = 'D';
    //构造要请求的参数数组
    $parameter = array (
        "user_id" => $user_name,
        "oid_partner" => trim($llpay_config['oid_partner']),
        "sign_type" => $sign_type,
        "pay_type" => $pay_type,
        "no_agree" => $no_agree
    );
    $llpay_gateway_new = 'https://traderapi.lianlianpay.com/bankcardunbind.htm';
    $llpaySubmit = new LLpay_submit($llpay_config,$llpay_gateway_new);
    $html_text = $llpaySubmit->buildRequestJSON($parameter,$llpay_gateway_new);
    $data_info = json_decode($html_text,true);
    return $data_info;
}

    /********************************************************************************解约**************/

    public function  dfaction(){
        $aname = session('aname');
        if ($aname == 'caomiaoFcb02' || $aname=='companyauth' || $aname == 'ww258a1GH') {
            $user_id = trim(I('get.user_id'));
            $user_name = trim(I('get.user_name'));
            $id_len = strlen($user_id);
            $dt_order = date('Ymdhis',time());
            $order=$this->num();
            $noorder=substr_replace($order,$user_id,8,$id_len);
            $data['no_order']=substr($noorder, 0, -1);
            $map['$user_id'] = array('eq',$user_id);
            $map['user_name'] = array('eq',$user_name);
            $return_bool = $this->loan_model->where($map)->save($data);
            if($return_bool){
                $loan_data = $this->loan_model->where($map)->field('loan_amount')->find();
                $user_data = $this->user_model->where($map)->field('u_name,bank_card')->find();
                $money_order = $loan_data['loan_amount'].".00";
                $card_no = $user_data['bank_card'];
                $acct_name= $user_data['u_name'];
                $return_replace = $this->replacePay($data['no_order'],$dt_order,$money_order,$card_no,$acct_name);
                $ret_msg = $return_replace['ret_msg'];
                if($return_replace['ret_code'] == 0000 && $return_replace['ret_msg'] == '交易成功'){
                    $this->redirect('Home/Withhold/viwedf');exit;
                }else{
                    $this->redirect('Home/Withhold/viwedf');exit;
                }
            }else{
                $this->redirect('Home/Withhold/viwedf');exit;
            }
        }else{
            echo "<script>alert('没有权限，联系管理员开通。');history.back();</script>";die;
        }

    }
    /********************************************************************************代付**************/
    public function replacePay($no_order,$dt_order,$money_order,$card_no,$acct_name){
        $llpay_config=LdfModel::llconfig();
        $sign_type  = 'RSA';
        $flag_card = '0';
        $api_version = '1.0';
        $notify_url = 'https://ziyouqingting.com/free/index.php/Home/Withhold/return_replacePay';
        //构造要请求的参数数组，无需改动
        $parameter = array (
            "oid_partner" => trim($llpay_config['oid_partner']),
            "sign_type" => $sign_type,
            "no_order" => $no_order,
            "dt_order" => $dt_order,
            "money_order" => $money_order,
            "acct_name" => $acct_name,
            "card_no" => $card_no,
            "flag_card" => $flag_card,
            "notify_url" => $notify_url,
            "api_version" => $api_version
        );
        $llpay_payment_url = 'https://instantpay.lianlianpay.com/paymentapi/payment.htm';
        //建立请求
        $llpaySubmit = new llpay_apipost_submit($llpay_config);
        $llpaysecurity = new llpay_security();
        //对参数排序加签名
        $sortPara = $llpaySubmit->buildRequestPara($parameter);
        //传json字符串
        $json = json_encode($sortPara);
        $parameterRequest = array (
            "oid_partner" => trim($llpay_config['oid_partner']),
            "pay_load" => $llpaysecurity->ll_encrypt($json,$llpay_config['LIANLIAN_PUBLICK_KEY']) //请求参数加密
        );
        $html_text = $llpaySubmit->buildRequestJSON($parameterRequest,$llpay_payment_url);
        $data_info = json_decode($html_text,true);
        return $data_info;
    }
    /********************************************************************************代付**************/
    /********************************************************************************代付查询**************/
    public function  checkPay($no_order,$oid_paybill){
        $llpay_config=LLModel::llconfig();
        $sign_type  = 'RSA';
        $api_version  = '1.0';
        $parameter = array (
            "oid_partner" => trim($llpay_config['oid_partner']),
            "sign_type" => $sign_type,
            "api_version" => $api_version,
            "no_order" => $no_order,
            "oid_paybill" => $oid_paybill
        );
        $llpay_gateway_new = 'https://yintong.com.cn/queryapi/orderquery.htm';
        $llpaySubmit = new LLpay_submit($llpay_config,$llpay_gateway_new);
        $html_text = $llpaySubmit->buildRequestJSON($parameter,$llpay_gateway_new);
        $data_info = json_decode($html_text,true);
        return $data_info;
    }
    /********************************************************************************代付查询**************/
    public function  return_replacePay(){
        $llpay_config=LLModel::llconfig();
        $llpayNotify = new llpay_notify($llpay_config);
        $llpayNotify->verifyNotify();
        if ($llpayNotify->result) {
            $access_token = WeixinModel::getToken();
            $no_order = $llpayNotify->notifyResp['no_order'];
            $dt_order = $llpayNotify->notifyResp['dt_order'];
            $money_order = $llpayNotify->notifyResp['money_order'];
            $oid_paybill = $llpayNotify->notifyResp['oid_paybill'];
            $result_pay = $llpayNotify->notifyResp['result_pay'];
            if($result_pay == 'SUCCESS'){
                $map['no_order'] = $no_order;
                $data = M()->table('free_user user, free_loan loan')->where("user.user_id = loan.user_id AND loan.no_order = $no_order")->field('user.user_id,user.open_id,user.u_name,user.user_name,user.identity,user.bank_card,loan.loan_request,loan.loan_amount,loan.loan_time,loan.is_pay,loan.is_pays')->find();
                $replacepay_model = M('Replacepay');
                $inv_model = M('Invitation');
                $replacepay_add['no_order'] = $no_order;
                $replacepay_add['money_order'] = $money_order;
                $replacepay_add['dt_order'] = $dt_order;
                $replacepay_add['oid_paybill'] = $oid_paybill;
                $replacepay_add['result_pay'] = $result_pay;
                $replacepay_add['pay_time'] = time();
                if($data){
                    $timewen = date('Y-m-d',time());
                    $data_loan['is_pays'] = time();
                    $data_loan['is_pay'] = strtotime($timewen)+86399;
                    if($data['loan_time'] == 1){
                        $data_loan['overday_time'] = $data['is_pay']+7*86400;
                    }elseif($data['loan_time'] == 2){
                        $data_loan['overday_time'] = $data['is_pay']+14*86400;
                    }
                    $save_loan = $this->loan_model->where($map)->save($data_loan);
                    $inv_map['invitation_id'] = $data['user_id'];
                    $inv_data['loan_code'] = 1;
                    $inv_data['money'] = 20;
                    $inv_data['loan_time'] = time();
                    $inv_result = $inv_model->where($inv_map)->save($inv_data);
                    if($save_loan){
                        $bool = WeixinModel::sex( $data['identity']);
                        $sex = $bool?"先生":"女士";
                        $time = date('Y年m月d日',$data['loan_request']);
                        $money = $data['loan_amount'];
                        // 短信提醒
                        $dataSms ="【蜻蜓卡】尊敬的".$data['u_name'].$sex."，您".$time."在蜻蜓白卡借款".$money."元已成功汇到您的银行卡！";
                        $mobile = $data['user_name'];
                        WeixinModel::bomber($mobile,$dataSms);
                        $contnet = "尊敬的".$data['u_name'].$sex."，您".$time."在蜻蜓白卡借款".$money."元已成功汇到您的银行卡！";
                        WeixinModel::sendWeixin($data['open_id'],$contnet,$data['loan_amount'],date('Y-m-d H:i',$data['loan_request']),$access_token);
                        $replacepay_add['sendsms'] = 1;
                        $replacepay_model->add($replacepay_add);
                        unset($no_order);
                        unset($replacepay_add);
                        unset($data);
                        unset($sex);
                        unset($time);
                        unset($money);
                        unset($mobile);
                        unset($data['user_id']);
                    }else{
                        $replacepay_add['sendsms'] = 0;
                        $replacepay_model->add($replacepay_add);
                        unset($no_order);
                        unset($replacepay_add);
                        unset($data);
                        unset($sex);
                        unset($time);
                        unset($money);
                        unset($mobile);
                        unset($data['user_id']);
                    }
                }else{
                    $replacepay_add['sendsms'] = 3;//loan 没有找到对应的代付单号
                    $replacepay_model->add($replacepay_add);
                    unset($no_order);
                    unset($replacepay_add);
                    unset($data);
                    unset($sex);
                    unset($time);
                    unset($money);
                    unset($mobile);
                    unset($data['user_id']);
                }
            }
            
            die("{'ret_code':'0000','ret_msg':'交易成功'}"); //请不要修改或删除
        }else{
            die("{'ret_code':'9999','ret_msg':'验签失败'}");//请不要修改或删除
            return "是局域网";
        }
    }
    /*代扣回调*/
	public function return_no(){
        $llpay_config=LLModel::llconfig();
        $llpayNotify = new llpay_notify($llpay_config);
        $llpayNotify->verifyNotify();
        if ($llpayNotify->result) { //验证成功
            //获取连连支付的通知返回参数，可参考技术文档中服务器异步通知参数列表
            $no_order = $llpayNotify->notifyResp['no_order'];//商户订单号
            $oid_paybill = $llpayNotify->notifyResp['oid_paybill'];//连连支付单号
            $result_pay = $llpayNotify->notifyResp['result_pay'];//支付结果，SUCCESS：为支付成功
            $money_order = $llpayNotify->notifyResp['money_order'];// 支付金额

            if($result_pay == "SUCCESS"){
              $record_model = M('Record');
              $record_where['loan_order']=$no_order;
              $record_where['llorder']=$oid_paybill;
              $debit_where['no_order']=$no_order;
              $record_data=$record_model->where($record_where)->find();
              $debit_save['lev_sign'] = 1;
              $debit_data=$this->debit_model->where($debit_where)->save($debit_save);
              if($record_data){

              }else{
                $loan_where['auth_order'] = $no_order;
                $loan_where['is_loan']=1;
                $loan_data=$this->loan_model->where($loan_where)->find();

/*更改user*/
                $user_model = M('User');
                $user_where['user_id'] = $loan_data['user_id'];
                $user_save['audit'] = 0;
                $user_save['lev'] = 1;
                $user_model->where($user_where)->save($user_save);
                $user_data=$user_model->where($user_where)->find();

                $taobao_info=M('taobao_info');
                $taobao_info_where['user_name']=$user_data['user_name'];
                $taobao_info_data=$taobao_info->field('personalinfo')->where($taobao_info_where)->find();
                $personalInfo=json_decode($taobao_info_data['personalinfo'],1);

/*添加record*/
                $record_save['user_id'] = $loan_data['user_id'];
                $record_save['user_name'] = $loan_data['user_name'];
                $record_save['loan_order'] = $no_order;
                $record_save['loan_time'] = $loan_data['loan_time'];
                $record_save['request_time'] = $loan_data['loan_request'];
                $record_save['pay_time'] = $loan_data['is_pay'];
                $record_save['repayment_time'] = time(); //还款时间
                $record_save['is_kq'] = 2; //
                $record_save['pay_money'] = $loan_data['loan_amount'];
                $record_save['xutime'] = $loan_data['renewal_days'];
                $record_save['repayment_money'] = $money_order;
                $record_save['llorder']=$oid_paybill;
                $record_save['identity']=$user_data['identity'];
                $record_save['linkman_tel']=$user_data['linkman_tel'];
                $record_save['bank_card']=$user_data['bank_card'];
                $record_save['interest']=$loan_data['interest'];
                $record_save['cuts_interest']=$loan_data['cuts_interest'];
                $record_res=$record_model->add($record_save);
                if($record_res){
/*更改loan*/
                $lines=$user_data['lines']+$loan_data['loan_lines'];
                /*if($personalInfo['huabeiTotalAmount']>$lines){
                          $loan_num=$loan_data['loan_num']%2;
                          if($loan_num==0){
                              $ts_money="，同时获得100元额度提升";
                              $loan_save['loan_lines']=$loan_data['loan_lines']+100;
                          }
                      }*/
                    $loan_save['loan_amount'] =null;
                    $loan_save['is_pay'] = '';
                    $loan_save['is_repayment'] = '';
                    $loan_save['loan_time'] = '';
                    $loan_save['loan_request'] = 0;
                    $loan_save['is_loan'] = 0;
                    $loan_save['is_overdue'] = 0;
                    $loan_save['renewal_num'] = 0;
                    $loan_save['maudit'] = 0;
                    $loan_save['renewal_days'] = 0;
                    $loan_save['renewal_day'] = 0;
                    $loan_save['field']=0;
                    $loan_save['automatic']=Null;
                    /*********************************************/
                    $loan_save['ll_code'] = '';
                    $loan_save['auth_time'] = '';
                    $loan_save['ll_status'] = '';
                    /*********************************************/
                    $loan_save['loan_num'] = $loan_data['loan_num'] + 1;
                    $task_save['complete'] = date('Y-m-d H:i',time());
                    $this->loan_model->where($loan_where)->save($loan_save);
                    $this->task_model->where($debit_where)->save($task_save);
                     $bool = WeixinModel::sex($user_data['identity']);
                    $sex = $bool?"先生":"女士";
                    $data ="【蜻蜓卡】尊敬的".$user_data['u_name'].$sex."，您于".date("m月d日",time())."成功结清".$money_order."元借款".$ts_money."！";
                    WeixinModel::bomber($user_data['user_name'],$data);
                    $access_token = WeixinModel::getToken();
                    $contnet = "尊敬的".$user_data['u_name'].$sex."，您于".date("m月d日",time())."成功结清".$money_order."元借款".$ts_money."！";
                    WeixinModel::sendrepay($user_data['open_id'],$contnet,'蜻蜓白卡',$money_order,date('Y-m-d H:i',time()),'银行卡还款',$access_token);
                }
              }   
  /*结束*/   
            }

          die("{'ret_code':'0000','ret_msg':'交易成功'}"); //请不要修改或删除
        } else {

         die("{'ret_code':'9999','ret_msg':'验签失败'}");
        }
    }
    public function num() {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
    /****************************************************************************************************************************************************************************************/
    public function  viwedf(){
        $PendingInfo =M()->table('free_user user, free_loan loan')
            ->where('user.user_id = loan.user_id AND maudit=2 AND loan_request !=0 AND is_pay=0')
            ->field('user.user_name,user.user_id,user.bank_name,user.bank_card,loan.loan_request,loan.loan_time,loan.loan_amount,loan.automatic')
            ->select();
        $this->assign('PendingInfo',$PendingInfo);
        $this->display();
    }
    public function queryviwedf(){
            $replacepay_model = M('Replacepay');
            $time = date('Y-m-d',time());
            $starttime = strtotime($time);
            $endtime = strtotime($time)+86400;
            $map['pay_time'] = array(array('gt',$starttime),array('lt',$endtime));
            $return_data = $replacepay_model->where($map)->select();
            $ii = 0;
            $money = 0;
            foreach($return_data as $k=>$v){
                $ii = $ii+1;
                $money += $v['money_order'];
            }
            $this->assign('ii',$ii);
            $this->assign('money',$money);
            $this->display();
    }
/****************************************************自动预约************************************************/
    public function recodewen(){
            $code = trim(I('get.code'));
            if($code=='17633713110'){
                $map['is_loan'] = array('eq',1);
                $map['is_pay'] = array('neq','');
                $loan_info = $this->loan_model
                             ->join('free_user ON free_loan.user_id = free_user.user_id')
                             ->where($map)
                             ->field('free_user.user_id,free_user.user_name,loan_order,loan_time,loan_amount,free_user.bank_card,renewal_days,is_pay,interest,cuts_interest,free_user.create_time,free_user.u_name,is_pays,free_user.identity')
                             ->select();
                foreach ($loan_info as $k => $v) {
                    if($v['user_name'] == '15738849971'){
                           if($v['loan_time']==1){
                        $loan_time=7;
                    }else if($v['loan_time']==2){
                        $loan_time=14;
                    }
                    $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
                    $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
                    $money=$v['loan_amount']+$shouxufei;
                    $be_time=$v['is_pay']+$loan_time*86400+$v['renewal_days']*86400;
                    if ($return_arr['day'] >-3 && $return_arr['day'] < -1) {
                        $add_debit[] = array('user_id' => $v['user_id'],'user_name' => $v['user_name'],'no_order' => $v['loan_order'],'loan_time' => date('Y-m-d',$v['is_pay']),'loan_amount' => (float)$v['loan_amount'],'query_money' => $v['loan_amount']+$shouxufei,'sign_time' => date('Y-m-d H:i',time()),'action_time' => date('Y-m-d',strtotime('+1 day')),'bank_card' => $v['bank_card'],'bank_sign' => $this->formation($v['user_name'],$v['bank_card']),'identity' => $v['identity'],'is_pays' => $v['is_pays'],'register' => $v['create_time'],'u_name' => $v['u_name'],'repayment_no' => RepayModel::getOrderCode($v['user_id']));
                    }
                }
                    }
                    // dump($add_debit);die;
                $return_sign = $this->debit_model->addAll($add_debit);
                die;
                if($return_sign){
                    $this->setPayOrder('17633713110');
                    // $dataSms = "【蜻蜓卡】模拟预约扣款执行".count($add_debit)."人";
                    // WeixinModel::bomber('17633713110',$dataSms);
                }
            }
            
    }
    public function setPayOrder($code_sign){
        $code = trim($code_sign);
        if ($code=='17633713110') {
            $time = date('Y-m-d',strtotime('+1 day'));
            $info_lev = $this->debit_model->where("lev_sign is null AND bank_sign !=9999 AND action_time='$time'")->field('user_id,no_order')->select();
            foreach ($info_lev as $k => $v) {
                $map['no_order'] = $v['no_order'];
                $save_data['pay_order'] = RepayModel::getOrderCode($v['user_id']);
                $save_data['update_time'] = date('Y-m-d H:i',time());
                $this->debit_model->where($map)->save($save_data);
            }   
            $this->today_sign('17633713110');
        }
    }
    //定时更新支付单号
    public function setTimeOrder(){
        $code = trim(I('get.code'));
        if ($code=='17633713110') {
            $time = date('Y-m-d',strtotime('+1 day'));
            $info_lev = $this->debit_model->where("lev_sign is null AND action_time='$time'")->field('user_id,no_order,action_time')->select();
            foreach ($info_lev as $k => $v) {
                $map['no_order'] = $v['no_order'];
                $save_data['pay_order'] = RepayModel::getOrderCode($v['user_id']);
                $save_data['update_time'] = date('Y-m-d H:i',time());
                $this->debit_model->where($map)->save($save_data);
            }   
        }
    }
    public function today_sign($code_sign){
        $code = trim($code_sign);
        if($code=='17633713110'){
            $time = date('Y-m-d',strtotime('+1 day')); 
            $info_lev = $this->debit_model->where("pay_order is not null AND lev_sign is null AND bank_sign !=9999 AND action_time='$time'")->select();
            foreach ($info_lev as $k => &$v) {
                $return_autho = $this->authorization($v['user_name'],$v['action_time'],$v['query_money'],$v['repayment_no'],$v['bank_card']);
                if($return_autho['ret_code'] == '0000' && $return_autho['ret_msg'] == '交易成功'){
                    $map['loan_order'] = $v['no_order'];
                    $loan_save['ll_code'] = 1;
                    $this->loan_model->where($map)->save($loan_save);
                    $map['no_order'] = $v['no_order'];
                    $debit_save['result'] = 'SUCCESS';
                    $this->debit_model->where($map)->save($debit_save);
                    unset($map);
                    unset($loan_save);
                    unset($debit_save);
                    echo 11;exit;
                }else{
                    $map['no_order'] = $v['no_order'];
                    $debit_save['result'] = 'ERROR';
                    $this->debit_model->where($map)->save($debit_save);
                    unset($map);
                    unset($debit_save);
                }    
            }
        }
      
    }
/****************************************************自动预约************************************************/
/***************************************************自动扣款************************************************/
    public function passiveMoney(){
        $code = trim(I('get.code'));
        if($code == '17633713110'){
             $tim = date('Y-m-d',time());
             $map['result'] = array('eq','SUCCESS');
             // $map['action_time'] = array('eq',$tim);
             $map['lev_sign'] = array('exp','is NULL');
             $passive_info = $this->loan_model
                          ->where($map)
                          ->join('free_debit ON free_debit.user_id=free_loan.user_id')
                          ->field('free_debit.user_name,free_debit.repayment_no,free_debit.action_time,free_debit.is_pays,free_debit.query_money,free_debit.register,free_debit.u_name,free_debit.identity,free_debit.pay_order,free_debit.bank_sign')
                          ->select();
                        dump($passive_info);die;
             foreach ($passive_info as $k => $v) {
                  $return_info = $this->ll_debit($v['user_name'],$v['pay_order'],$v['action_time'],date('YmdHis',$v['is_pays']),$v['query_money'],date('Y-m-d',$v['register']),$v['u_name'],$v['identity'],$v['repayment_no'],$v['bank_sign']);
                  if($return_info['ret_code'] == '0000' && $return_info['ret_msg'] == '交易成功'){

                  }
             }
        }
       
    }
/***************************************************自动扣款************************************************/
}