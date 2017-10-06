<?php
namespace Home\Controller;
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
header("content-type:text/html;charset=utf8");
class IndexController extends Controller{
    public function index(){
        layout(false); // 临时关闭当前模板的布局功能
        
        if($_POST){
            $post=I('post.');
            $code=$this->check_verify($post['pd']);
            if(!$code){
                die("<script>alert('验证码错误');window.location.href='index';</script>");
            }

            $auth = M('auth');
            $where['username']=$post['name'];
            $auth_data=$auth->where($where)->find();
            if(!$auth_data){
                die("<script>alert('不存在的用户名');window.location.href='index';</script>");
            }
            $password=md5($post['pwd'].$auth_data['salf'])==$auth_data['password'];
            if($password){
                session('aname', $post['name']);
                $url=explode('-', $auth_data['auth']);
                if($url[0]=='Credit'){
                    $this->redirect('Home/'.$url[0].'/credit');
                }else{
                    $this->redirect('Home/'.$url[0].'/index');
                }
                
            }else{
                die("<script>alert('密码错误');window.location.href='index';</script>");
            }

        }
        $this->display();
    }

    public function verify(){
        $coofig = array(
            'fontSize' => 30,
            'length' => 3,
            'useCurve' => false
        );
        $verify = new \Think\Verify($coofig);
        $verify->entry();
    }

    // 检测输入的验证码是否正确，$code为用户输入的验证码字符串
    function check_verify($code, $id = ''){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }

    //检测登录
    public function checklogin(){
        if (session('aname')) {
            return true;
        } else {
            return false;
        }
    }

    public function logout(){
        session('aname', null);
        $this->redirect('Home/Index/index');
    }

    //定时执行的方法
    public function timedtask(){
       $codeTime = strtotime(date('Y-m-d',time()));
        $action = $codeTime+32400;
        $end = $codeTime+64800;
        $code = trim(I('get.code'));
        if ($code == 17633713110) {
            if (time() <= $action || time() >= $end) {
                $this->wenindex('namewen');//自动审核放款
            }elseif(time() > $action && time() < $end){
               $this->wenautomatic('namewen');//自动审核不放款
            }
        }
    }
    public function wenindex($lcode){
        $credit_model = M('Credit');
        $record_model = M('Record');
        $loan_model = M('Loan');
        $user_model = M('User');
        $code = $lcode;
        if($code == 'namewen'){
            $lstdata = M()->table('free_user user,free_loan loan')
            ->where("user.user_id=loan.user_id and user.au_request=1 and (loan.is_pay='' or loan.is_pay=0 or loan.is_pay='0') and loan.automatic is null and loan.loan_num>=1")
            ->field('user.user_id,user.user_name,user.u_name,user.bank_card,user.identity,user.open_id,loan.is_pay,loan.loan_num,loan.automatic,loan.loan_amount,loan.loan_request')
            ->select();
        foreach ($lstdata as $k => $v) {
            $map['user_id'] = $v['user_id'];
            $info_credit = $credit_model->where($map)->field('is_matched,user_id')->order('credit_id desc')->find();
            $info_record = $record_model->where($map)->field('user_id,user_name,pay_time,repayment_time,loan_time,xutime,pay_money')->order('record_id desc')->find();
            if($info_record['loan_time'] == 1){
                $loan_time = 7;
            }else{
                $loan_time = 14;
            }
            $returnday = RepayModel::be_overdue($info_record['pay_time'],$loan_time,$info_record['xutime'],$info_record['pay_money'],$info_record['repayment_time']);
            $difference = time()-$info_record['repayment_time'];
            if($returnday['day'] <= 0 && $info_credit['is_matched'] == 0 && $difference <= 2592000){
                $id_len = strlen($v['user_id']);
                $dt_order = date('Ymdhis',time());
                $card_no = $v['bank_card'];
                $money_order = $v['loan_amount']."."."00";
                $acct_name = $v['u_name'];
                $order=$this->num();
                $noorder=substr_replace($order,$v['user_id'],8,$id_len);
                $data['no_order']=substr($noorder, 0, -1);
                $data_user['au_request'] = 0;
                $return_bool = $user_model->where($map)->save($data_user);
                $return_bools = $loan_model->where($map)->save($data);
                if($return_bool && $return_bools){
                    $content = "逾期".$returnday['day']."天"."ID".$map['user_id']."Tel".$info_record['user_name']."姓名".$acct_name."金额".$money_order."卡号".$card_no."执行时间".date('Y-m-d H:i');
                    file_put_contents('dfsuccess.txt',$content . "\r\n", FILE_APPEND);
                    $returndata = $this->replacePay($data['no_order'],$dt_order,$money_order,$card_no,$acct_name);
                    if($returndata['ret_code'] == 0000 && $returndata['ret_msg'] == '交易成功'){
                        $this->sendSmswen($v['identity'],$v['loan_amount'],$v['bank_card'],$v['user_name'],$v['u_name'],$v['open_id'],$v['loan_request']);
                        $loan_save['automatic'] = 1;
                        $loan_save['maudit'] = 2;
                        $loan_model->where($map)->save($loan_save);
                        unset($data['no_order']);
                        unset($dt_order);
                        unset($money_order);
                        unset($card_no);
                        unset($acct_name);
                        unset($info_credit['is_matched']);
                        unset($returnday['day']);
                        unset($info_record['user_name']);
                        unset($info_credit);
                        unset($info_record);
                        unset($content);
                        unset($map['user_id']);
                        unset($loan_save['automatic']);
                        unset($loan_save['maudit']);
                        unset($difference);
                    }else{
                        $content = "逾期".$returnday['day']."天"."ID".$map['user_id']."Tel".$info_record['user_name']."姓名".$acct_name."金额".$money_order."卡号".$card_no."执行时间".date('Y-m-d H:i');
                       file_put_contents('ordererror.txt',$content. "\r\n", FILE_APPEND);
                       $loan_save['automatic'] = 3;
                       $loan_model->where($map)->save($loan_save);
                       unset($data['no_order']);
                       unset($dt_order);
                       unset($money_order);
                       unset($card_no);
                       unset($acct_name);
                       unset($info_credit['is_matched']);
                       unset($returnday['day']);
                       unset($info_record['user_name']);
                       unset($info_credit);
                       unset($info_record);
                       unset($content);
                       unset($map['user_id']);
                       unset($loan_save['automatic']);
                       unset($loan_save['maudit']);
                       unset($difference);
                    }
                }
            }elseif($v['automatic'] !=2){
                $content = "逾期".$returnday['day']."天"."ID".$map['user_id']."Tel".$info_record['user_name']."执行时间".date('Y-m-d H:i');
                file_put_contents('dferror.txt',$content . "\r\n", FILE_APPEND);
                unset($info_credit['is_matched']);
                unset($returnday['day']);
                unset($info_record['user_name']);
                unset($info_credit);
                unset($info_record);
                unset($content);
                $loan_save['automatic'] = 2;
                $loan_model->where($map)->save($loan_save);
                unset($map['user_id']);
                unset($loan_save['automatic']);
            }
        }
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
    public function num() {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    public function sendSmswen($identity,$money,$bank_card,$user_name,$u_name,$open_id,$loan_request){
        $access_token = WeixinModel::getToken();
        $bool = WeixinModel::sex($identity);
        $sex = $bool?"先生":"女士";
        $cod = substr(trim($bank_card),-4);
        $dataSms1 ="【蜻蜓卡】尊敬的".$u_name.$sex."，您申请的".$money."元借款已通过审核，系统将尽快进行打款，收款银行卡尾号".$cod."，请注意查收！";
        $mobile = $user_name;
        WeixinModel::bomber($mobile,$dataSms1);

        $contnet2 = "尊敬的".$u_name.$sex."，您申请的".$money."元借款已通过审核，系统将尽快进行打款，收款银行卡尾号".$cod."，请注意查收！";
        WeixinModel::sendWeixin($open_id,$contnet2,$money,date('Y-m-d H:i',$loan_request),$access_token);
    }

    public function wenautomatic($lcode){
        $credit_model = M('Credit');
        $record_model = M('Record');
        $loan_model = M('Loan');
        $user_model = M('User');
        $lstdata = M()->table('free_user user,free_loan loan')
            ->where("user.user_id=loan.user_id and user.au_request=1 and (loan.is_pay='' or loan.is_pay=0 or loan.is_pay='0') and loan.automatic is null and loan.loan_num>=1")
            ->field('user.user_id,user.user_name,user.u_name,user.bank_card,user.identity,user.open_id,loan.is_pay,loan.loan_num,loan.automatic,loan.loan_amount,loan.loan_request')
            ->select();
        foreach ($lstdata as $k => $v) {
            $map['user_id'] = $v['user_id'];
            $info_credit = $credit_model->where($map)->field('is_matched,user_id')->order('credit_id desc')->find();
            $info_record = $record_model->where($map)->field('user_id,user_name,pay_time,repayment_time,loan_time,xutime,pay_money')->order('record_id desc')->find();
            if($info_record['loan_time'] == 1){
                $loan_time = 7;
            }else{
                $loan_time = 14;
            }
            $returnday = RepayModel::be_overdue($info_record['pay_time'],$loan_time,$info_record['xutime'],$info_record['pay_money'],$info_record['repayment_time']);
            $difference = time()-$info_record['repayment_time'];
            if($returnday['day'] <= 0 && $info_credit['is_matched'] == 0  && $difference <= 2592000){
                $data_user['au_request'] = 0;
                $return_bool = $user_model->where($map)->save($data_user);
                $loan_save['automatic'] = 3;
                $loan_save['maudit'] = 2;
                $return_bools = $loan_model->where($map)->save($loan_save);
                if($return_bool && $return_bools){
                    $this->sendSmswen($v['identity'],$v['loan_amount'],$v['bank_card'],$v['user_name'],$v['u_name'],$v['open_id'],$v['loan_request']);
                    $content = "逾期".$returnday['day']."天"."ID".$v['user_id']."Tel".$v['user_name']."姓名".$v['u_name']."金额".$v['loan_amount']."卡号".$v['bank_card']."执行时间".date('Y-m-d H:i');
                    file_put_contents('shsuccess.txt',$content . "\r\n", FILE_APPEND);
                    unset($difference);
                    unset($data_user);
                    unset($loan_save);
                    unset($data_user['au_request']);
                    unset($content);
                    unset($returnday);
                }
            }elseif($v['automatic'] !=2){
                $content = "逾期".$returnday['day']."天"."ID".$v['user_id']."Tel".$v['user_name']."执行时间".date('Y-m-d H:i');
                file_put_contents('sherror.txt',$content . "\r\n", FILE_APPEND);
                unset($content);
                $loan_save['automatic'] = 2;
                $loan_model->where($map)->save($loan_save);
                unset($difference);
                unset($map['user_id']);
                unset($loan_save['automatic']);
                unset($returnday);
            }
        }
    }
}