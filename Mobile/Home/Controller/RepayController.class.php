<?php
/*
还款页面
*/
namespace Home\Controller;
use Think\Controller;
use Home\Model\FocusModel as FocusModel;
use Home\Model\MoneyModel as MoneyModel;
use Home\Model\RepayModel as RepayModel;
use Home\Model\SesameModel as SesameModel;
use Home\Model\MessageModel as MessageModel;
use Home\Model\LLModel as LLModel;
use lian\llpay_submit;
use lian\llpay_notify;
use lian\llpay_cls_json;
header("content-type:text/html;charset=utf8");
class RepayController extends Base { 
  protected $task_model;
  public function __construct(){
    parent::__construct();
    $this->task_model = M('Task');
  }
      public function index(){
      	 if (!empty($_REQUEST['code'])) {
      			$code = $_REQUEST['code'];
      			$data1 = ['appid' => 'wx77c3255a41d184ad', 'secret' => '474b6a0cd731d5bae343db9a0169b57e', 'code' => $code, 'grant_type' => 'authorization_code', ];
      			$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?' . http_build_query($data1);
      			$req = json_decode(file_get_contents($url), true);
      			$openid = $req['openid'];
      			if (!empty($openid)) {
        			$_SESSION['openid'] = $openid;
        			$user_model = M("User");
        			$map['open_id'] = session('openid');
        			$user_data = $user_model->where($map)->find();
        			$_SESSION['name'] = $user_data['user_name'];
        			if (!empty($user_data)) {
        				$this->redirect('borrow/index');
        			}else{
        				$this->redirect('user/login');
        			}
      			}
    	  }

    if (empty(session('name'))) {
		      $this->redirect('user/login');
		}
		  $user=M('user');
	    $loan = M('loan');
	    $where['user_name'] = $_SESSION['name'];
	    $user_data = $user->where($where)->find();
	    $loan_data = $loan->where($where)->find();

	    if($loan_data['loan_time']==1){
           $loan_time=7;
	    }else if($loan_data['loan_time']==2){
           $loan_time=14;
	    }
/*  逾期费用  */
	    $overdue_show=RepayModel::overdue_show($loan_data['is_pay'],$loan_time,$loan_data['renewal_days'],$loan_data['loan_amount']);
/*  手续费   */
	    $shouxufei=MoneyModel::shouxufei($loan_data['loan_amount'],$loan_data['interest'],$loan_data['cuts_interest']);

      $day_time=strtotime(date('Y-m-d',$overdue_show['time']))+86399;
      if($day_time==$overdue_show['time']){
        $this->dueday=1;
      }
	    $end_money=$loan_data['loan_amount']+$shouxufei+$overdue_show['overdue_money'];
	    $this->shouxufei=$shouxufei;
	    $this->loan_time=$loan_time;
	    $this->user_data=$user_data;
	    $this->loan_data=$loan_data;
	    $this->end_money=$end_money;
	    $this->overdue_show=$overdue_show;
      $this->display('repay/index');
      }
 /* 续期页面 */
      public function renewal(){
        if (empty($_SESSION['name'])) {
		      $this->redirect('user/login');
		    }
		    $user=M('user');
	      $loan = M('loan');
	      $where['user_name'] = $_SESSION['name'];
	      $user_data = $user->where($where)->find();
	      $loan_data = $loan->where($where)->find();
/*续期判断*/
      if($loan_data['renewal_num'] >= 3){
            die("<script>alert('三次续期已用完！');history.back();</script>");
      }
	    if($loan_data['loan_time']==1){
           $loan_time=7;
	    }else if($loan_data['loan_time']==2){
           $loan_time=14;
	    }

/*逾期判断*/

	    $overdue_show=RepayModel::overdue_show($loan_data['is_pay'],$loan_time,$loan_data['renewal_days'],$loan_data['loan_amount']);
        if($overdue_show['day']>4){
            die("<script>alert('您已逾期四天，不能进行续期操作。');history.back();</script>");
        }
	    if($overdue_show['day']>0){
        $sumday = $overdue_show['day'];
      }else{
        $sumday = 0;
      }

    	$get=I('get.');
    	if($get['num']==14){
          $loan_save['renewal_day'] = 14;
          $is_lpay=$overdue_show['time']+($sumday+14)*86400;
          $renewal_money=RepayModel::renewal_money($loan_save['renewal_day'],$loan_data['loan_amount']);
          $binmoney = $overdue_show['overdue_money'];//逾期费用
          $renewal_all = $renewal_money['renewal_all']+$overdue_show['overdue_money'];//续期总费用
          $this->sign=14;
    	}else{
          $loan_save['renewal_day'] = 7;
          $is_lpay=$overdue_show['time']+($sumday+7)*86400;
          $renewal_money=RepayModel::renewal_money($loan_save['renewal_day'],$loan_data['loan_amount']);
          $binmoney = $overdue_show['overdue_money'];//逾期费用
          $fwmoney = $loan_data['loan_amount']*0.06;//服务费用
          $sxmoney = $loan_data['loan_amount']*0.06;//手续费用
          $renewal_all = $renewal_money['renewal_all']+$overdue_show['overdue_money'];//续期总费用
    	}
      $this->renewal_money=$renewal_money;
      $this->binmoney=$binmoney;
      $this->fwmoney=$fwmoney;
      $this->sxmoney=$sxmoney;
    	$this->renewal_all=$renewal_all;
      $this->is_lpay=$is_lpay;
      $this->overdue_show=$overdue_show;
      $this->loan_data=$loan_data;
    	$this->display('repay/renewal');
      }
      public function ajax(){
        $post=I('post.');
        $loan = M('loan');
        $where['user_name'] = $_SESSION['name'];
        $loan_data = $loan->where($where)->find();
        if($loan_data['loan_time']==1){
             $loan_time=7;
        }else if($loan_data['loan_time']==2){
             $loan_time=14;
        }
        $overdue_show=RepayModel::overdue_show($loan_data['is_pay'],$loan_time,$loan_data['renewal_days'],$loan_data['loan_amount']);
        if($overdue_show['day']>0){
          $sumday = $overdue_show['day'];
        }else{
          $sumday = 0;
        }
        if($post['time']==14){
          $loan_save['renewal_day'] = 14;
          $is_lpay=$overdue_show['time']+($sumday+14)*86400;
          $renewal_money=RepayModel::renewal_money($loan_save['renewal_day'],$loan_data['loan_amount']);
          $renewal_money['binmoney'] = $overdue_show['overdue_money']+$renewal_money['renewal_fw'];//本期费用
          $renewal_money['all'] = $renewal_money['renewal_all']+$overdue_show['overdue_money'];//续期总费用
        }else{
            $loan_save['renewal_day'] = 7;
            $is_lpay=$overdue_show['time']+($sumday+7)*86400;
            $renewal_money=RepayModel::renewal_money($loan_save['renewal_day'],$loan_data['loan_amount']);
            $renewal_money['binmoney'] = $overdue_show['overdue_money']+$renewal_money['renewal_fw'];//本期费用
            $renewal_money['all'] = $renewal_money['renewal_all']+$overdue_show['overdue_money'];//续期总费用
        }
        $renewal_money['new_time']=date("Y-m-d",$is_lpay);
        $renewal_money['time']=date("Y-m-d",$overdue_show['time']);
        $this->ajaxReturn($renewal_money);
      }
/* 续期判断*/
      public function num() {
            return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
      }
      public function to_pay(){
        if (empty($_SESSION['name'])) {
             $this->redirect('user/login');
        }
        
            // $post=I('post.');
            $post['time'] = 7;
            $loan_model=M('loan');
            $where['user_name']=$_SESSION['name'];
            $loan_data=$loan_model->where($where)->find();
            $user_model=M('user');
            $user_data=$user_model->where($where)->find();
            /**********************************************************************/

            $id_len=strlen($user_data['user_id']);
            $order=$this->num();
            $loan_order=substr_replace($order,$user_data['user_id'],8,$id_len);
            $renewal_order=substr($loan_order, 0, -1);
            $loan_save['renewal_order'] = $renewal_order;
            /*************************************************************************/
/*计算续期费用*/ 
            $post['time']==14?$time=14:$time=7;
            $loan_data['loan_time']==2?$loan_time=14:$loan_time=7;
            $overdue_show=RepayModel::overdue_show($loan_data['is_pay'],$loan_time,$loan_data['renewal_days'],$loan_data['loan_amount']);
            if($overdue_show['day']>0){
              $sumday = $overdue_show['day'];
            }else{
              $sumday = 0;
            }
            $renewal_money=RepayModel::renewal_money($time,$loan_data['loan_amount']);
            $loan_save['renewal_day'] = $time+$sumday;
            $renewal_money['all'] = $renewal_money['renewal_all']+$overdue_show['overdue_money'];
            $change_renewal_order = $loan_model->where($where)->save($loan_save);
            if (!$change_renewal_order) {
                $this->redirect('repay/index');
            }

            $llpay_config=LLModel::llconfig();

            $version = '1.0';
            $oid_partner = '201706141001821167';
            $app_request = '3';
            $sign_type = strtoupper('RSA');
            $valid_order ="10080";
            $busi_partner="101001";
            $name_goods="蜻蜓白卡";
            $info_order="蜻蜓白卡";
            $id_type="0";
            $pay_type="D";
            $notify_url="https://ziyouqingting.com/free/mobile.php/home/repay/to_notifu";
            $return_url="https://ziyouqingting.com/free/mobile.php/home/repay/to_return";

            $user_id=$user_data['user_name'];               //商户用户唯一编号

            $no_order=$renewal_order;            //订单号
            $money_order=$renewal_money['all'];//订单金额
            $acct_name=$user_data['u_name'];        //用户名称
            $id_no=$user_data['identity'];          //身份证号
            $no_agree='';                           //协议号
            $time=date('YmdHis',$user_data['create_time']);
            $risk_item='{\"user_info_mercht_userno\":\"'.$user_id.'\",\"user_info_full_name\":\"'.$acct_name.'\",\"user_info_bind_phone\":\"'.$user_id.'\",\"user_info_dt_register\":\"'.$time.'\",\"user_info_id_no\":\"'.$id_no.'\",\"user_info_identify_state\":\"1\",\"user_info_identify_type\":\"4\",\"frms_ware_category\":\"2010\"}';                          //风控参数
            $parameter = array (
                "bg_color"=>"4AB5FB",
                "version" => $version,
                "oid_partner" => $oid_partner,
                "app_request" => $app_request,
                "sign_type" => $sign_type,
                "valid_order" => $valid_order,
                "user_id" => $user_id,
                "busi_partner" => $busi_partner,
                "no_order" => $no_order,
                "dt_order" => date('YmdHis', time()),
                "name_goods" => $name_goods,
                "info_order" => $info_order,
                "money_order" => $money_order,
                "notify_url" => $notify_url,
                "url_return" => $return_url,
                "acct_name" => $acct_name,
                "id_type" =>$id_type,
                "id_no" => $id_no,
                "no_agree" => $no_agree,
                "risk_item" => $risk_item,
                "pay_type" =>$pay_type
            );
            $llpaySubmit = new llpay_submit($llpay_config);
            $html_text = $llpaySubmit->buildRequestForm($parameter, "post", "确认");
            echo $html_text;


      }

      /*续期主动回调*/
      public function to_return(){
        $llpay_config=LLModel::llconfig();
      $llpayNotify = new llpay_notify($llpay_config);
      $verify_result = $llpayNotify->verifyReturn();
      if($verify_result) {//验证成功

        $json = new llpay_cls_json;
        $res_data = $_POST["res_data"];

        //商户编号
        $oid_partner = $json->decode($res_data)-> {'oid_partner' };

        //商户订单号
        $no_order = $json->decode($res_data)-> {'no_order' };
        $oid_paybill = $json->decode($res_data)-> {'oid_paybill' };
        //支付结果
        $result_pay =  $json->decode($res_data)-> {'result_pay' };
        //支付金额
        $money_order = $json->decode($res_data)-> {'money_order' };
          if($result_pay == 'SUCCESS') {

/*开始*/
            $continued_model = M('continued');
            $debit_model = M('Debit');
            $continued_where['renewal_order']=$no_order;
            $continued_where['llorder']=$oid_paybill;
            $debit_where['no_order']=$no_order;
            $continued_data=$continued_model->where($continued_where)->find();
            $debit_save['debit_save'] = 3;
            $debit_data=$debit_model->where($debit_where)->save($debit_save);

/*判断记录*/
            if($continued_data){

            }else{

                $loan_model=M('loan');
                $loan_where['renewal_order']=$no_order;
                $loan_data=$loan_model->where($loan_where)->find();
     
                $user_model=M('user');
                $user_where['user_id']=$loan_data['user_id'];
                $user_data=$user_model->where($user_where)->find();
                $continued_model = M('Continued');
                $data['user_id'] = $loan_data['user_id'];
                $data['user_name'] = $loan_data['user_name'];
                $data['renewal_order'] = $no_order;
                $data['loan_time'] = $loan_data['loan_time'];
                $data['request_time'] = $loan_data['loan_request'];
                $data['pay_time'] = $loan_data['is_pay'];
                $data['repayment_time'] = time(); //还款时间
                $data['is_kq'] = 2; //还款标识
                $data['to_order']=$oid_paybill;
                $data['pay_money']=$money_order;
                $data['repayment_money'] = $loan_data['loan_amount'];
                $data['identity']=$user_data['identity'];
                $data['linkman_tel']=$user_data['linkman_tel'];
                $data['bank_card']=$user_data['bank_card'];

                /*****************************************************************/
                $data['loan_order'] = $loan_data['loan_order'];
                $data['continued_day']=$loan_data['renewal_day'];
                /*****************************************************************/
                $con_res=$continued_model->add($data);

                if($con_res){
                  $loan_save['renewal_days'] = $loan_data['renewal_day']+$loan_data['renewal_days'];
                  $loan_save['renewal_num'] = $loan_data['renewal_num'] + 1;
                  $loan_save['renewal_time'] = time();
                  /*********************************************/
                    $loan_save['ll_code'] = '';
                    $loan_save['auth_time'] = '';
                    $loan_save['ll_status'] = '';
                    /*********************************************/
                  $loan_model->where($loan_where)->save($loan_save);
                  $bool = MessageModel::sex($user_data['identity']);
                  $sex = $bool?"先生":"女士";
                  $xuday = 7;
                  $data="【蜻蜓卡】尊敬的".$user_data['u_name'].$sex."，您已成功申请续期".$xuday."天，到期请及时还款，请您注意更改后的到期时间！";
                  MessageModel::bomber($data,$user_data['user_name']);
                  $access_token = MessageModel::getToken();
                  $contnet = "尊敬的".$user_data['u_name'].$sex."，您已成功申请续期".$xuday."天，到期请及时还款，请您注意更改后的到期时间！";
                  MessageModel::sendWeixin($user_data['open_id'],$contnet,$loan_data['loan_amount'],date('Y-m-d H:i',$loan_data['loan_request']),$access_token);

                }

            }
                
/*结束*/   
          }
          die("<script>window.location.href='index';</script>");
      }else {
          die("<script>window.location.href='index';</script>");
      }


      }
/*续期被动回调*/
      public function to_notifu(){
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
/*开始*/
            $continued_model = M('continued');
            $debit_model = M('Debit');
            $continued_where['renewal_order']=$no_order;
            $debit_where['no_order']=$no_order;
            $continued_where['llorder']=$oid_paybill;
            $continued_data=$continued_model->where($continued_where)->find();
            $debit_save['debit_save'] = 3;
            $debit_data=$debit_model->where($debit_where)->save($debit_save);
            
/*判断记录*/
            if($continued_data){
            }else{
                $loan_model=M('loan');
                $loan_where['renewal_order']=$no_order;
                $loan_data=$loan_model->where($loan_where)->find();

                $user_model=M('user');
                $user_where['user_id']=$loan_data['user_id'];
                $user_data=$user_model->where($user_where)->find();
                $continued_model = M('Continued');
                $data['user_id'] = $loan_data['user_id'];
                $data['user_name'] = $loan_data['user_name'];
                $data['renewal_order'] = $no_order;
                $data['loan_time'] = $loan_data['loan_time'];
                $data['request_time'] = $loan_data['loan_request'];
                $data['pay_time'] = $loan_data['is_pay'];
                $data['repayment_time'] = time(); //还款时间
                $data['is_kq'] = 2; //还款标识
                $data['to_order']=$oid_paybill;
                $data['pay_money']=$money_order;
                $data['repayment_money'] = $loan_data['loan_amount'];
                $data['identity']=$user_data['identity'];
                $data['linkman_tel']=$user_data['linkman_tel'];
                $data['bank_card']=$user_data['bank_card'];

                 /*****************************************************************/
                $data['loan_order'] = $loan_data['loan_order'];
                $data['continued_day']=$loan_data['renewal_day'];
                /*****************************************************************/
                $con_res=$continued_model->add($data);

                if($con_res){
                  $loan_save['renewal_days'] = $loan_data['renewal_day']+$loan_data['renewal_days'];
                  $loan_save['renewal_num'] = $loan_data['renewal_num'] + 1;
                  $loan_save['renewal_time'] = time();
                    /*********************************************/
                    $loan_save['ll_code'] = '';
                    $loan_save['auth_time'] = '';
                    $loan_save['ll_status'] = '';
                    /*********************************************/
                  $loan_model->where($loan_where)->save($loan_save);
                  $bool = MessageModel::sex($user_data['identity']);
                  $sex = $bool?"先生":"女士";
                  $xuday = 7;
                  $data="尊敬的".$user_data['u_name'].$sex."，您已成功申请续期".$xuday."天，到期请及时还款，请您注意更改后的到期时间！";
                  MessageModel::sendSms($data,$user_data['user_name']);
                  $access_token = MessageModel::getToken();
                  $contnet = "尊敬的".$user_data['u_name'].$sex."，您已成功申请续期".$xuday."天，到期请及时还款，请您注意更改后的到期时间！";
                  MessageModel::sendWeixin($user_data['open_id'],$contnet,$loan_data['loan_amount'],date('Y-m-d H:i',$loan_data['loan_request']),$access_token);
                }

            }

/*结束*/   
            }

          die("{'ret_code':'0000','ret_msg':'交易成功'}"); //请不要修改或删除
        } else {

         die("{'ret_code':'9999','ret_msg':'验签失败'}");
        }


      }

/*   支付宝还款页面 */
      public function zfb_pay(){
      	if (empty($_SESSION['name'])) {
	      $this->redirect('user/login');
	    }
	    $user=M('user');
	    $where['user_name'] = $_SESSION['name'];
	    $user_data=$user->where($where)->find();
	    $this->user_data=$user_data;
	    if($_POST){
	      $post=I('post.');
	      $save['zfbuser']=$post['zhifubao'];
	       $res=$user->where($where)->save($save);
	       if($res){
	         die("<script>alert('支付宝账户保存成功');window.location.href='zfb_hk';</script>");
	       }else{
	         die("<script>alert('不要重复提交信息');history.back();</script>");
	       }
	    }

      	$this->display('repay/zfb_pay');
      }
    public function zfb_img(){
    	
    	$this->display('repay/zfb_img');
    }
    


    public function llpay(){

    if (empty($_SESSION['name'])) {
        $this->redirect('user/login');
    }


      $llpay_config=LLModel::llconfig();


      $user_model=M('user');
      $where['user_name']=$_SESSION['name'];
      $user_data=$user_model->where($where)->find();

      $loan_model=M('loan');
      $loan_data=$loan_model->where($where)->find();
      
      $taobao_info=M('taobao_info');
      $taobao_info_data=$taobao_info->field('personalinfo')->where($where)->find();
      $personalInfo=json_decode($taobao_info_data['personalinfo'],1);

      $version = '1.0';
      $oid_partner = '201706141001821167';
      $app_request = '3';
      $sign_type = strtoupper('RSA');
      $valid_order ="10080";
      $busi_partner="101001";
      $name_goods="蜻蜓白卡";
      $info_order="蜻蜓白卡";
      $id_type="0";
      $pay_type="D";

      $notify_url="https://ziyouqingting.com/free/mobile.php/home/repay/ll_notifu";
      $return_url="https://ziyouqingting.com/free/mobile.php/home/repay/ll_return";
  if($loan_data['loan_time']==1){
           $loan_time=7;
      }else if($loan_data['loan_time']==2){
           $loan_time=14;
      }
  /*  逾期费用  */
      $overdue_show=RepayModel::overdue_show($loan_data['is_pay'],$loan_time,$loan_data['renewal_days'],$loan_data['loan_amount']);
/*  手续费   */
      $shouxufei=MoneyModel::shouxufei($loan_data['loan_amount'],$loan_data['interest'],$loan_data['cuts_interest']);
      $user_id=$user_data['user_name'];               //商户用户唯一编号
      $no_order=$loan_data['loan_order'];            //订单号
      $money_order=$loan_data['loan_amount']+$shouxufei+$overdue_show['overdue_money'];                //订单金额
      $acct_name=$user_data['u_name'];        //用户名称
      $id_no=$user_data['identity'];          //身份证号
      $no_agree='';                             //协议号
      $time=date('YmdHis',$user_data['create_time']);
      $risk_item='{\"user_info_mercht_userno\":\"'.$user_id.'\",\"user_info_full_name\":\"'.$acct_name.'\",\"user_info_bind_phone\":\"'.$user_id.'\",\"user_info_dt_register\":\"'.$time.'\",\"user_info_id_no\":\"'.$id_no.'\",\"user_info_identify_state\":\"1\",\"user_info_identify_type\":\"4\",\"frms_ware_category\":\"2010\"}';                          //风控参数
        $parameter = array (
            "bg_color"=>"4AB5FB",
            "version" => $version,
            "oid_partner" => $oid_partner,
            "app_request" => $app_request,
            "sign_type" => $sign_type,
            "valid_order" => $valid_order,
            "user_id" => $user_id,
            "busi_partner" => $busi_partner,
            "no_order" => $no_order,
            "dt_order" => date('YmdHis', time()),
            "name_goods" => $name_goods,
            "info_order" => $info_order,
            "money_order" => $money_order,
            "notify_url" => $notify_url,
            "url_return" => $return_url,
            "acct_name" => $acct_name,
            "id_type" =>$id_type,
            "id_no" => $id_no,
            "no_agree" => $no_agree,
            "risk_item" => $risk_item,
            "pay_type" =>$pay_type
        );

        $llpaySubmit = new llpay_submit($llpay_config);
        $html_text = $llpaySubmit->buildRequestForm($parameter, "post", "确认");
        echo $html_text;
    }

/*主动支付主动回调*/
    public function ll_return(){
      $llpay_config=LLModel::llconfig();

      $llpayNotify = new llpay_notify($llpay_config);
      $verify_result = $llpayNotify->verifyReturn();
      if($verify_result) {//验证成功

        $json = new llpay_cls_json;
        $res_data = $_POST["res_data"];

        //商户编号
        $oid_partner = $json->decode($res_data)-> {'oid_partner' };

        //商户订单号
        $no_order = $json->decode($res_data)-> {'no_order' };
        $oid_paybill = $json->decode($res_data)-> {'oid_paybill' };
        //支付结果
        $result_pay =  $json->decode($res_data)-> {'result_pay' };
        //支付金额
        $money_order = $json->decode($res_data)-> {'money_order' };
          if($result_pay == 'SUCCESS') {
/*开始*/
              $record_model = M('Record');
              $debit_model = M('Debit');
              $record_where['loan_order']=$no_order;
              $debit_where['no_order']=$no_order;
              $record_where['llorder']=$oid_paybill;
              $record_data=$record_model->where($record_where)->find();
              $debit_save['lev_sign'] = 2;//主动还款
              $debit_data=$debit_model->where($debit_where)->save($debit_save);
              if($record_data){

              }else{
                $loan_model = M('Loan');
                $loan_where['loan_order'] = $no_order;
                $loan_where['is_loan']=1;
                $loan_data=$loan_model->where($loan_where)->find();
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
                $record_save['interest']=$loan_data['interest'];
                $record_save['pay_money'] = $loan_data['loan_amount']; 
                $record_save['xutime'] = $loan_data['renewal_days']; 
                $record_save['repayment_money'] = $money_order;
                $record_save['llorder']=$oid_paybill;
                $record_save['identity']=$user_data['identity'];
                $record_save['linkman_tel']=$user_data['linkman_tel'];
                $record_save['bank_card']=$user_data['bank_card'];
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
                    $loan_save['ll_code'] = '';
                    $loan_save['auth_time'] = '';
                    $loan_save['ll_status'] = '';
                    $loan_save['automatic']=null;
                    $loan_save['loan_num'] = $loan_data['loan_num'] + 1;
                    $task_save['complete'] = date('Y-m-d H:i',time());
                    $loan_model->where($loan_where)->save($loan_save);
                    $this->task_model->where($debit_where)->save($task_save);
                    $bool = MessageModel::sex($user_data['identity']);
                    $sex = $bool?"先生":"女士";



                    $this->end_upgrade($user_data['user_name'],$loan_data['card_type'],$user_data['lines'],$loan_data['loan_lines'],$user_data['u_name'],$sex,$loan_data['loan_amount'],$user_data['open_id'],$loan_data['loan_num']);
                      //$this->upgrade($user_data['user_name'],$loan_data['card_type'],$user_data['lines'],$loan_data['loan_lines'],$user_data['u_name'],$sex,$loan_data['loan_amount'],$user_data['open_id']);
                        //$this->new_upgrade($user_data['user_name'],$loan_data['card_type'],$user_data['lines'],$loan_data['loan_lines'],$user_data['u_name'],$sex,$loan_data['loan_amount'],$user_data['open_id']);
                    



                }
              }   
  /*结束*/   
          }
          die("<script>window.location='/free/mobile.php/home/borrow/index'</script>");
      }else {
          die("<script>window.location.href='index';</script>");
      }
    }
/*主动支付被动回调*/
    public function ll_notifu(){
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
/*开始*/
              $record_model = M('Record');
              $debit_model = M('Debit');
              $record_where['loan_order']=$no_order;
              $debit_where['no_order']=$no_order;
              $record_where['llorder']=$oid_paybill;
              $record_data=$record_model->where($record_where)->find();
              $debit_save['lev_sign'] = 2;//主动还款
              $debit_data=$debit_model->where($debit_where)->save($debit_save);
              if($record_data){

              }else{
                $loan_model = M('Loan');
                $loan_where['loan_order'] = $no_order;
                $loan_where['is_loan']=1;
                $loan_data=$loan_model->where($loan_where)->find();

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
                $record_save['interest']=$loan_data['interest'];
                $record_save['pay_money'] = $loan_data['loan_amount'];
                $record_save['xutime'] = $loan_data['renewal_days'];
                $record_save['repayment_money'] = $money_order;
                $record_save['llorder']=$oid_paybill;
                $record_save['identity']=$user_data['identity'];
                $record_save['linkman_tel']=$user_data['linkman_tel'];
                $record_save['bank_card']=$user_data['bank_card'];
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
                    $loan_save['ll_code'] = '';
                    $loan_save['auth_time'] = '';
                    $loan_save['ll_status'] = '';
                    $loan_save['automatic']=null;
                    $loan_save['loan_num'] = $loan_data['loan_num'] + 1;
                    $task_save['complete'] = date('Y-m-d H:i',time());
                    $loan_model->where($loan_where)->save($loan_save);
                    $this->task_model->where($debit_where)->save($task_save);
                    $bool = MessageModel::sex($user_data['identity']);
                    $sex = $bool?"先生":"女士";

                    $this->end_upgrade($user_data['user_name'],$loan_data['card_type'],$user_data['lines'],$loan_data['loan_lines'],$user_data['u_name'],$sex,$loan_data['loan_amount'],$user_data['open_id'],$loan_data['loan_num']);
                        //$this->upgrade($user_data['user_name'],$loan_data['card_type'],$user_data['lines'],$loan_data['loan_lines'],$user_data['u_name'],$sex,$loan_data['loan_amount'],$user_data['open_id']);
                        //$this->new_upgrade($user_data['user_name'],$loan_data['card_type'],$user_data['lines'],$loan_data['loan_lines'],$user_data['u_name'],$sex,$loan_data['loan_amount'],$user_data['open_id']);
                    
                    
                }
              }   
  /*结束*/   
            }

          die("{'ret_code':'0000','ret_msg':'交易成功'}"); //请不要修改或删除
        } else {

         die("{'ret_code':'9999','ret_msg':'验签失败'}");
        }
    }

    public function yuwenhan(){
        $user=M('user');
        $where['free_user.user_name']=15738849971;
        $user_data=$user->where($where)->join('free_loan ON free_loan.user_id=free_user.user_id')->find();
        $bool = MessageModel::sex($user_data['identity']);
        $sex = $bool?"先生":"女士";
        $this->end_upgrade($user_data['user_name'],$user_data['card_type'],$user_data['lines'],$user_data['loan_lines'],$user_data['u_name'],$sex,$user_data['loan_amount'],$user_data['open_id'],$user_data['loan_num']);
    }


 public function end_upgrade($user_name,$card_type,$user_lines,$loan_lines,$u_name,$sex,$loan_amount,$open_id,$loan_num){
        $access_token = MessageModel::getToken();
        $where['user_name']=$user_name;
        if($user_lines==''){
            $user_lines=1000;
        }
        $lines=$user_lines+$loan_lines;
        $now_lines=$loan_lines+100;

        
        $record=M('record');
        $record_data=$record->where($where)->order('record_id desc')->find();
        if($record_data['loan_time']==1){
            $loan_time=7;
        }else if($record_data['loan_time']==2){
            $loan_time=14;
        }
        $be_overdue=RepayModel::be_overdue($record_data['pay_time'],$loan_time,$record_data['xutime'],$record_data['pay_money'],$record_data['repayment_time']);
        if($be_overdue['day']<1){

            if($lines<1400){
                if(($loan_num%2)==0){
                    $loan=M('loan');
                    $save['loan_lines']=$now_lines;
                    $loan_res=$loan->where($where)->save($save);
                    if($loan_res){
                        $card_arr=array("提升时间"=>date('Y-m-d',time()),"手机号"=>$user_name,"提升额度"=>"100","提升类型"=>"永久");
                        $card_json=json_encode($card_arr);
                        file_put_contents("end_upgrade.txt", $card_json."*".PHP_EOL,FILE_APPEND);
                        $data ="尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款，同时获得100元额度提升！";
                        $contnet = "尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款，同时获得100元额度提升！";
                        MessageModel::sendSms($data,$user_name);
                        $a=MessageModel::sendrepay($open_id,$contnet,'蜻蜓白卡',$loan_amount,date('Y-m-d H:i',time()),'银行卡还款',$access_token);
                        return ;
                    }
            
                }
            }else{
                $bulid_money=$this->bulid($user_name);
                if($bulid_money){
                    $data ="尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。恭喜您获得一张提额卷，赶快打开微信公众号\"蜻蜓白卡\"查看吧！";
                    $contnet = "尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。恭喜您获得一张提额劵，赶快打开微信公众号“蜻蜓白卡”查看吧！";
                    MessageModel::sendSms($data,$user_name);
                    $a=MessageModel::sendrepay($open_id,$contnet,'蜻蜓白卡',$loan_amount,date('Y-m-d H:i',time()),'银行卡还款',$access_token);
                    return ;
                }
                
            }

        }

        $data ="尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款！";
        $contnet = "尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款！";
        MessageModel::sendSms($data,$user_name);
        $a=MessageModel::sendrepay($open_id,$contnet,'蜻蜓白卡',$loan_amount,date('Y-m-d H:i',time()),'银行卡还款',$access_token);
        return ;
    }




public function new_upgrade($user_name,$card_type,$user_lines,$loan_lines,$u_name,$sex,$loan_amount,$open_id){
        $access_token = MessageModel::getToken();
        if($user_lines==''){
            $user_lines=1000;
        }
        $lines=$user_lines+$loan_lines+200;
        $now_lines=$loan_lines+200;
        $record=M('record');
        $loan=M('loan');
        $where['user_name']=$user_name;
        $record_data=$record->where($where)->order('record_id desc')->find();

        if($record_data['loan_time']==1){
            $loan_time=7;
        }else if($record_data['loan_time']==2){
            $loan_time=14;
        }

        $be_overdue=RepayModel::be_overdue($record_data['pay_time'],$loan_time,$record_data['xutime'],$record_data['pay_money'],$record_data['repayment_time']);
        
        if($lines<=2000){
//  小于2000  给永久提额  

            if($be_overdue['day']>0){
    //逾期  给提额卷
                $bulid_money=$this->bulid($user_name);
                if($bulid_money==''){
                    $data ="尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款！";
                    $contnet = "尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款！";
                }else{
                    $data ="尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。恭喜您获得一张提额卷，赶快打开微信公众号\"蜻蜓白卡\"查看吧！";
                    $contnet = "尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。恭喜您获得一张提额卷，赶快打开微信公众号“蜻蜓白卡”查看吧！";
                }
                 MessageModel::sendSms($data,$user_name);
                //MessageModel::bomber($user_name,$data);
                $a=MessageModel::sendrepay($open_id,$contnet,'蜻蜓白卡',$loan_amount,date('Y-m-d H:i',time()),'银行卡还款',$access_token);
            }else{
    //未逾期  给永久卷
                
                if(($lines<2000 && $lines>1500) && $card_type==0){
                    $save['card_type']=2;

                    $data ="尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。恭喜您成为蜻蜓白卡银卡会员，您的借款额度已提升，请您继续保持良好的信用！";
                    MessageModel::sendSms($data,$user_name);
                    //MessageModel::bomber($user_name,$data);
                    $contnet = "尊敬的".$u_name."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。您的会员级别有所变更，具体信息如下：";
                    $text="蜻蜓白卡为感谢您的支持，您的借款额度已提升，请您继续保持良好的信用。";
                    $card="银卡会员";
                    $a=MessageModel::interest($open_id,$contnet,$text,$access_token,$card);
                }else if($lines==2000 && $card_type!=1){
                    $save['card_type']=1;
                    $save['interest_type']=1;
                    $data ="尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。恭喜您成为蜻蜓白卡金卡会员，额度提升同时降低了您借款所需的服务费用，请您继续保持良好的信用！";
                    MessageModel::sendSms($data,$user_name);
                    //MessageModel::bomber($user_name,$data);
                    $contnet = "尊敬的".$u_name."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。您的会员级别有所变更，具体信息如下：";
                    $text="恭喜您成为蜻蜓白卡金卡会员，额度提升同时降低了您借款所需的服务费用，请您继续保持良好的信用。";
                    $card="金卡会员";
                    $a=MessageModel::interest($open_id,$contnet,$text,$access_token,$card);
                }else{
                    $data ="尊敬的 ".$u_name.$sex."，您于".date("m",time())."月".date("d",time())."日成功结清".$loan_amount."元借款，同时获得200元额度提升！";
                    $contnet = "尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款！恭喜您获得额度提升，赶快打开微信公众号“蜻蜓白卡“查看吧！";
                    MessageModel::sendSms($data,$user_name);
                    //MessageModel::bomber($user_name,$data);
                    $a=MessageModel::sendrepay($open_id,$contnet,'蜻蜓白卡',$loan_amount,date('Y-m-d H:i',time()),'银行卡还款',$access_token);
                }
                $save['loan_lines']=$now_lines;
                $save['card_time']=time();
                $res=$loan->where($where)->save($save);
                if($res){
                    $card_arr=array("提升时间"=>date('Y-m-d',time()),"手机号"=>$user_name,"提升额度"=>"200","提升类型"=>$save['card_type']);
                    $card_json=json_encode($card_arr);
                    file_put_contents("new_card.txt", $card_json."*".PHP_EOL,FILE_APPEND);  

                }else{ 
                    file_put_contents("new_card.txt", $user_name." ".date('Y-m-d',time())." 出现错误"."*".PHP_EOL,FILE_APPEND);  
                }
            }
        }else{
//大于  2000  直接还款   
            $data ="尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款！";
            $contnet = "尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款！";
             MessageModel::sendSms($data,$user_name);
            //MessageModel::bomber($user_name,$data);
            $a=MessageModel::sendrepay($open_id,$contnet,'蜻蜓白卡',$loan_amount,date('Y-m-d H:i',time()),'银行卡还款',$access_token);
        }

        return;

    }




/*升级判断*/
    public function upgrade($user_name,$card_type,$user_lines,$loan_lines,$u_name,$sex,$loan_amount,$open_id){
        if($user_lines==''){
            $user_lines=1000;
        }
        $lines=$user_lines+$loan_lines;
        $record=M('record');
        $loan=M('loan');
        $where['user_name']=$user_name;
        $record_data=$record->where($where)->order('record_id desc')->select();
        foreach ($record_data as $key => $value) {
            if($value['loan_time']==1){
                $loan_time=7;
            }else if($value['loan_time']==2){
                $loan_time=14;
            }
            $be_overdue=RepayModel::be_overdue($value['pay_time'],$loan_time,$value['xutime'],$value['pay_money'],$value['repayment_time']);
            if($key==0){
                if($be_overdue['day']<1){
                    $overdue_type=1;
                }
            }
            if($be_overdue['day']>0){
                break;
            }else{
                $time+=$value['loan_time'];
            }
        }
        $time=$time*7;
        if($time<800 && $time>=500){
            if($card_type==0 && $lines<1500){
                $jia_lines=1500-$lines;
                $now_lines=$jia_lines+$loan_lines;
                $save['card_type']=2;
                $save['loan_lines']=$now_lines;
                $save['card_time']=time();
                $res=$loan->where($where)->save($save);
                if($res){
                    $card_arr=array("提升时间"=>date('Y-m-d',time()),"手机号"=>$user_name,"提升额度"=>$jia_lines,"提升类型"=>"银卡");
                    $card_json=json_encode($card_arr);
                    file_put_contents("card.txt", $card_json."*".PHP_EOL,FILE_APPEND);  

                    $data ="尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。恭喜您成为蜻蜓白卡银卡会员，您的借款额度已提升，请您继续保持良好的信用！";
                    MessageModel::sendSms($data,$user_name);
                    //MessageModel::bomber($data,$user_name);
                    $access_token = MessageModel::getToken();
                    $contnet = "尊敬的".$u_name."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。您的会员级别有所变更，具体信息如下：";
                    $text="蜻蜓白卡为感谢您的支持，您的借款额度已提升，请您继续保持良好的信用。";
                    $card="银卡会员";
                    $a=MessageModel::interest($open_id,$contnet,$text,$access_token,$card);
                    return;
                }
            }
        }else if($time<2000 && $time>=800){
            if(($card_type==0||$card_type==2) && $lines<2000){
                $jia_lines=2000-$lines;
                $now_lines=$jia_lines+$loan_lines;
                $save['card_type']=1;
                $save['interest_type']=1;
                $save['loan_lines']=$now_lines;
                $save['card_time']=time();
                $res=$loan->where($where)->save($save);
                if($res){
                    $card_arr=array("提升时间"=>date('Y-m-d',time()),"手机号"=>$user_name,"提升额度"=>$jia_lines,"提升类型"=>"金卡");
                    $card_json=json_encode($card_arr);
                    file_put_contents("card.txt", $card_json."*".PHP_EOL,FILE_APPEND);

                    $data ="尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。恭喜您成为蜻蜓白卡金卡会员，额度提升同时降低了您借款所需的服务费用，请您继续保持良好的信用！";
                    MessageModel::sendSms($data,$user_name);
                    //MessageModel::bomber($data,$user_name);
                    $access_token = MessageModel::getToken();
                    $contnet = "尊敬的".$u_name."，您的会员级别有所变更，具体信息如下：";
                    $text="您已成为蜻蜓白卡金卡会员。额度提升同时降低了您借款所需的服务费用，请您继续保持良好的信用。";
                    $card="金卡会员";
                    $a=MessageModel::interest($open_id,$contnet,$text,$access_token,$card);
                    return;
                }
            }
        }

        if($overdue_type==1){
            $bulid_money=$this->bulid($user_name);
        }
        if($bulid_money==''){
            $data ="尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款！";
            $contnet = "尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款！";
        }else{
            $data ="尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。恭喜您获得一张提额卷，赶快打开微信公众号\"蜻蜓白卡\"查看吧！";
            $contnet = "尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。恭喜您获得一张提额券，赶快打开微信公众号“蜻蜓白卡”查看吧！";
        }
        MessageModel::sendSms($data,$user_name);
        //MessageModel::bomber($data,$user_name);
        $access_token = MessageModel::getToken();
        $a=MessageModel::sendrepay($open_id,$contnet,'蜻蜓白卡',$loan_amount,date('Y-m-d H:i',time()),'银行卡还款',$access_token);
        return;
    }

/* 生成提额卷 */
    public function bulid($user_name){
        $mention=M('mention');
        $where['is_use']=0;
        $mention_data=$mention->where($where)->find();

        if($mention_data){
            $coupons=M('coupons');
            $coupons_where['user_name']=$_SESSION['name'];
            $coupons_where['coupons_type']=2;
            $coupons_where['is_use']=0;
            $coupons_where['overdue_time']=array('gt',time());
            $coupons_data=$coupons->where($coupons_where)->order('create_time desc')->find();
            if(!$coupons_data){
                $money=200;
                /*当前可放额度判断*/
                $now_money=$mention_data['now_money']-$money;
                if($now_money>0){
                    $coupons=M('coupons');
                    $save['user_name']=$user_name;
                    $save['coupons_type']=2;
                    $save['create_time']=time();
                    $save['overdue_time']=strtotime(date('Y-m-d',strtotime('+1 day')))+86339;
                    $save['lines']=$money;
                    $save['mention_id']=$mention_data['id'];

                    $coupons_res=$coupons->add($save);

                    if($coupons_res){
                        $return_money=$money;
                        $mention_where['id']=$mention_data['id'];
                        $mention_save['now_money']=$now_money;
                        $mention_res=$mention->where($mention_where)->save($mention_save);

                    }else{
                        $return_money='';
                    }
                }else{
                    $mention_where1['id']=$mention_data['id'];
                    $mention_save1['is_use']=1;
                    $mention_res=$mention->where($mention_where1)->save($mention_save1);
                    $return_money='';
                }
            }else{
                $return_money='';
            }
        }else{
            $return_money='';
        }

        return $return_money;
    }

}