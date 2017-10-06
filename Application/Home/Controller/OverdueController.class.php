<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\RepayModel as RepayModel;
use Home\Model\MoneyModel as MoneyModel;
use Home\Model\WeixinModel as WeixinModel;
header("Content-Type:text/html;charset=utf-8");
class OverdueController extends BaseController{
    public function index(){
        if ($_POST) {
            $post=I('post.');
            $start=strtotime($post['start']);
            $end = strtotime($post['end']);
            $end=$end+86400;
            $data = $this->search($start,$end);
            foreach ($data as $k => $v) {
                      if($v['loan_time']==1){
                           $loan_time=7;
                      }else if($v['loan_time']==2){
                           $loan_time=14;
                      }
                      
                      $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']     );
                      $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
        
                      if($start <=$return_arr['time'] && $return_arr['time']<= $end){
                          $arr[$k]['shouxufei']=$shouxufei;
                          $arr[$k]['day']=$return_arr['day'];
                          $arr[$k]['loan_time'] = $loan_time;
                          $arr[$k]['due_time']=$return_arr['time'];
                          $arr[$k]['overdue_money']=$return_arr['overdue_money'];
                          $arr[$k]['user_id'] = $v['user_id'];
                          $arr[$k]['user_name'] = $v['user_name'];
                          $arr[$k]['linkman_name']=$v['linkman_name'];
                          $arr[$k]['linkman_tel']=$v['linkman_tel'];
                          $arr[$k]['bank_name'] = $v['bank_name'];
                          $arr[$k]['bank_tel'] = $v['bank_tel'];
                          $arr[$k]['query_message'] = $v['query_message'];
                          $arr[$k]['open_id'] = $v['open_id'];
                          $arr[$k]['identity'] = $v['identity'];
                          $arr[$k]['bank_card'] = $v['bank_card'];
                          $arr[$k]['loan_amount'] = $v['loan_amount'];
                          $arr[$k]['is_pay'] = $v['is_pay'];
                          $arr[$k]['renewal_days'] = $v['renewal_days'];
                          $sum+=1;
        
                          if($return_arr['day'] >= (-1) && $return_arr['day'] < 1){
                            $pre_sum+=1;
                          }
                          if($return_arr['day']>=1){
                            $heji_money+=$shouxufei;
                            $money_sum+=$v['loan_amount'];
                            $overdue_sum+=1;
                            $overdue_sum_money+=$return_arr['overdue_money'];
                          }
                          if($return_arr['day'] > 0 && $return_arr['day']<4){
                            $let_sum+=1;
                          }
                      }
                }
                    $this->assign('heji_money',$heji_money);
                    $this->assign('overdue_sum_money',$overdue_sum_money);
                    $this->assign('money_sum',$money_sum);
                    $this->assign('let_sum',$let_sum);
                    $this->assign('sum',$sum);
                    $this->assign('pre_sum',$pre_sum);
                    $this->assign('overdue_sum',$overdue_sum);
                    $this->assign('arr',$arr);
        }else{
            $data = M()
            ->table('free_user user,free_loan loan')
            ->where("user.user_id=loan.user_id and loan.is_pay>0")
            ->select();
                foreach ($data as $k => $v) {
                      if($v['loan_time']==1){
                           $loan_time=7;
                      }else if($v['loan_time']==2){
                           $loan_time=14;
                      }
                      
                      $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']     );
                      $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
        
                      if($return_arr['day']>3){
                          $arr[$k]['shouxufei']=$shouxufei;
                          $arr[$k]['day']=$return_arr['day'];
                          $arr[$k]['loan_time'] = $loan_time;
                          $arr[$k]['due_time']=$return_arr['time'];
                          $arr[$k]['overdue_money']=$return_arr['overdue_money'];
                          $arr[$k]['user_id'] = $v['user_id'];
                          $arr[$k]['user_name'] = $v['user_name'];
                          $arr[$k]['bank_name'] = $v['bank_name'];
                          $arr[$k]['linkman_name']=$v['linkman_name'];
                          $arr[$k]['linkman_tel']=$v['linkman_tel'];
                          $arr[$k]['bank_tel'] = $v['bank_tel'];
                          $arr[$k]['query_message'] = $v['query_message'];
                          $arr[$k]['open_id'] = $v['open_id'];
                          $arr[$k]['identity'] = $v['identity'];
                          $arr[$k]['bank_card'] = $v['bank_card'];
                          $arr[$k]['loan_amount'] = $v['loan_amount'];
                          $arr[$k]['is_pay'] = $v['is_pay'];
                          $arr[$k]['renewal_days'] = $v['renewal_days'];
                          $sum+=1;
        
                          if($return_arr['day']>=(-1)&&$return_arr['day']<1){
                            $pre_sum+=1;
                          }
                          if($return_arr['day']>=1){
                            $heji_money+=$shouxufei;
                            $money_sum+=$v['loan_amount'];
                            $overdue_sum+=1;
                            $overdue_sum_money+=$return_arr['overdue_money'];
                          }
                          if($return_arr['day'] > 0 && $return_arr['day']<4){
                            $let_sum+=1;
                          }
                      }
                }
                    $page_length=15;
                    $num=count($arr);
                    $page_total=new \Think\Page($num,$page_length);
                    $cart_data=array_slice($arr,$page_total->firstRow,$page_total->listRows);
                    $show=$page_total->show();
                    $this->assign('heji_money',$heji_money);
                    $this->assign('overdue_sum_money',$overdue_sum_money);
                    $this->assign('money_sum',$money_sum);
                    $this->assign('let_sum',$let_sum);
                    $this->assign('sum',$sum);
                    $this->assign('pre_sum',$pre_sum);
                    $this->assign('overdue_sum',$overdue_sum);
                    $this->assign('page',$show);
                    $this->assign('arr',$cart_data);
                    $this->assign('count',$count);
        }
    
        $this->display();
    }

    public function search($start,$end){
        $data = M()
            ->table('free_user user,free_loan loan')
            ->where("user.user_id = loan.user_id AND loan.is_pay !=0")
            ->select();
            return $data;
    }

    // 容时期催收短信群发
    public function collection(){
        $data = M()
        ->table('free_user user,free_loan loan')
        ->where("user.user_id=loan.user_id and loan.is_pay>0")
        ->select();
            foreach ($data as $k => $v) {
               if($v['loan_time']==1){
                       $loan_time=7;
                }else if($v['loan_time']==2){
                       $loan_time=14;
                }
                $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
                $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
                if($return_arr['day'] > 0 && $return_arr['day']<4){
                    if ($v['is_pays'] == null) {
                        $time = date("m月d日H时",$return_arr['time']);
                    }else{
                        $time = date("m月d日",$return_arr['time']);
                    }
                    $money=$v['loan_amount']+$shouxufei;
                    $end_money=$money+$return_arr['overdue_money'];
                    $rongs=ceil($v['loan_amount']*0.015);
                    $map['user_name'] = $v['user_name'];
                    $data['query_message'] = 1;
                    M('Loan')->where($map)->save($data);
                    $bool = WeixinModel::sex($v['identity']);
                    $sex = $bool?"先生":"女士";
                    $dataSms = $v['bank_name'].$sex."，您的".$v['loan_amount']."元借款，目前已逾期".$return_arr['day']."天，请您今天务必通过微信关注“蜻蜓白卡”公众号，进行还款，逾期会产生费用，如已还款请忽略！";
                    $mobile = $v['user_name'];
                    WeixinModel::sendSms($dataSms,$mobile);
                }
            }

        $this->redirect("Home/Rong/index");
    }
    public function collWx(){
        $data = M()
        ->table('free_user user,free_loan loan')
        ->where("user.user_id=loan.user_id and loan.is_pay>0")
        ->select();
        $access_token = WeixinModel::getToken();
            foreach ($data as $k => $v) {
               if($v['loan_time']==1){
                       $loan_time=7;
                }else if($v['loan_time']==2){
                       $loan_time=14;
                }
                  
                $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
                $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
                if($return_arr['day'] > 0 && $return_arr['day']<4){
                    if ($v['is_pays'] == null) {
                        $time = date("m月d日H时",$return_arr['time']);
                    }else{
                        $time = date("m月d日",$return_arr['time']);
                    }
                    $money=$v['loan_amount']+$shouxufei;
                    $end_money=$money+$return_arr['overdue_money'];
                    $bool = WeixinModel::sex($v['identity']);
                    $sex = $bool?"先生":"女士";
                    $name = $v['u_name'].$sex;
                    $returndata = $this->note_ajax_no($v['user_name'],$return_arr['day']);
                    $identity = $v['identity'];
                    WeixinModel::collectionWx($v['open_id'],$name,$return_arr['day'],$money,$end_money,$time,$access_token,$identity,$returndata);
                }elseif ($return_arr['day'] > 3 && $return_arr['day']<7) {
                    $money=$v['loan_amount']+$shouxufei;
                    $end_money=$money+$return_arr['overdue_money'];
                    $bool = WeixinModel::sex($v['identity']);
                    $sex = $bool?"先生":"女士";
                    $name = $v['u_name'].$sex;
                    $identity = substr($v['identity'], 0,6)."****".substr($v['identity'], -4);
                    WeixinModel::collectionWxs($v['open_id'],$name,$return_arr['day'],$money,$end_money,$time,$access_token,$identity);
                }
            }
        $this->redirect("Home/Rong/index");
    }
    public function collectionNo(){
        $data = M()
        ->table('free_user user,free_loan loan')
        ->where("user.user_id=loan.user_id and loan.is_pay>0")
        ->select();
            foreach ($data as $k => $v) {
               if($v['loan_time']==1){
                       $loan_time=7;
                }else if($v['loan_time']==2){
                       $loan_time=14;
                }
                  
                $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
                $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
                if($return_arr['day']>3){
                    $time = date("m月d日",$return_arr['time']);
                    $money=$v['loan_amount']+$shouxufei;
                    $end_money=$money+$return_arr['overdue_money'];
                    $rongs=ceil($v['loan_amount']*0.015);
                    $map['user_name'] = $v['user_name'];
                    $data['query_message'] = 1;
                    M('Loan')->where($map)->save($data);
                    $bool = WeixinModel::sex($v['identity']);
                    $sex = $bool?"先生":"女士";
                    $dataSms = "【蜻蜓卡】".$v['bank_name'].$sex."，您好，您在“蜻蜓白卡”的借款".$v['loan_amount']."元目前已逾期".$return_arr['day']."天，请您于今天下午六点之前务必通过微信关注“蜻蜓白卡”公众号手动操作还款，如已还款请忽略！";
                    $mobile = $v['user_name'];
                    WeixinModel::bomber($mobile,$dataSms);
                }
            }

        $this->redirect("Home/Rong/index");
    }
    //预催收短信群发
    public function precollection(){
         $data = M()
        ->table('free_user user,free_loan loan')
        ->where("user.user_id=loan.user_id and loan.is_pay>0")
        ->select();
            foreach ($data as $k => $v) {
               if($v['loan_time']==1 && $v['ll_code'] != 1){
                    $loan_time=7;
                    $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
                    $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
                    if($return_arr['day'] >= (-2) && $return_arr['day'] <= (-0)){
                        if ($v['is_pays'] == null) {
                          $time = date("m月d日H时",$return_arr['time']);
                        }else{
                          $time = date("m月d日",$return_arr['time']);
                        }
                        $money=$v['loan_amount']+$shouxufei;
                        $bool = WeixinModel::sex($v['identity']);
                        $sex = $bool?"先生":"女士";
                        $mobile[$k] = $v['user_name'];
                        $dataSms =  "尊敬的".$v['bank_name'].$sex."，您的".$money."元借款将于".$time."到期。请至微信公众号“蜻蜓白卡”中还款，以免逾期！如已还款请忽略。";
                        WeixinModel::sendSms($dataSms,$mobile[$k]);
                        //WeixinModel::bomber($mobile[$k],$dataSms);
                    }

                }else if($v['loan_time']==2 && $v['ll_code'] != 1){
                    $loan_time=14;
                    $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
                    $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
                    if($return_arr['day'] >= (-2) && $return_arr['day'] <= (-0)){
                        if ($v['is_pays'] == null) {
                          $time = date("m月d日H时",$return_arr['time']);
                        }else{
                          $time = date("m月d日",$return_arr['time']);
                        }
                        $money=$v['loan_amount']+$shouxufei;
                        $bool = WeixinModel::sex($v['identity']);
                        $sex = $bool?"先生":"女士";
                        $mobile[$k] = $v['user_name'];
                        $dataSms =  "尊敬的".$v['bank_name'].$sex."，您的".$money."元借款将于".$time."到期。请至微信公众号“蜻蜓白卡”中还款，以免逾期！如已还款请忽略。";
                        WeixinModel::sendSms($dataSms,$mobile[$k]);
                    }
                }
            }
       $this->redirect("Home/Pre/index");
    }




      //预催收微信群发
    public function precoll(){
         $data = M()
        ->table('free_user user,free_loan loan')
        ->where("user.user_id=loan.user_id and loan.is_pay>0")
        ->select();
        //
        $access_token = WeixinModel::getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
            foreach ($data as $k => $v) {
                if($v['loan_time']==1){
                    $loan_time=7;
                }else if($v['loan_time']==2){
                    $loan_time=14;
                }
                $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
                $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
                if($return_arr['day'] >= (-2) && $return_arr['day'] <= (-0)){
                    if ($v['is_pays'] == null) {
                      $time = date("m月d日H时",$return_arr['time']);
                    }else{
                      $time = date("m月d日",$return_arr['time']);
                    }
                    $bool = WeixinModel::sex($v['identity']);
                    $sex = $bool?"先生":"女士";
                    $name = $v['u_name'].$sex;
                    $qt_order = "QT".$v['loan_order'];
                    $money = $v['loan_amount']+$shouxufei;
                    $sum[]=$this->precollWx($v['open_id'],$name,$time,$v['loan_amount'],$money,$qt_order,$loan_time);
                    
                }
            }
            foreach ($sum as $key => $value) {
                $ch[$key]=curl_init();
                curl_setopt($ch[$key], CURLOPT_URL, $url); //抓取指定网页
                curl_setopt($ch[$key], CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch[$key], CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
                curl_setopt($ch[$key], CURLOPT_POST, 1); //post提交方式
                curl_setopt($ch[$key], CURLOPT_POSTFIELDS, $value);
            }
            $mh = curl_multi_init();

            foreach ($sum as $key => $value) {
                curl_multi_add_handle($mh,$ch[$key]);
            }
            $running=null;

            do {
                usleep(10000);
                curl_multi_exec($mh,$running);
            } while ($running > 0);

            foreach ($sum as $key => $value) {
              curl_multi_remove_handle($mh, $ch[$key]);
            }
            curl_multi_close($mh);

       $this->redirect("Home/Pre/index");
    }



   public function precollWx($open_id,$name,$time,$loan_amount,$money,$qt_order,$loan_time){
        $url = "https://ziyouqingting.com/free/mobile.php/home/user/weixincode";
        $template_id= "VAlf54BHV_uHgpgUCHjCxntJ1d1IEE97xQ7J0h0X6aI";
        $topcolor = '#0000FF';
        $sms=array(
                    'first'=>array('value'=>urlencode("尊敬的".$name."，您的".$money."元借款将于".$time."到期。请至微信公众号“蜻蜓白卡”中还款，以免逾期！如已还款请忽略。"
                     ),'color'=>"#0000FF"),
                    'keyword1'=>array('value'=>urlencode($qt_order),'color'=>'#0000FF'),
                    'keyword2'=>array('value'=>urlencode($loan_amount."元"),'color'=>'#0000FF'),
                    'keyword3'=>array('value'=>urlencode($money."元"),'color'=>'#0000FF'),
                    'keyword4'=>array('value'=>urlencode($loan_time."天"),'color'=>'#0000FF'),
                    'keyword5'=>array('value'=>urlencode($time),'color'=>'#0000FF')
                        
                    );
        $template = array(
                    'touser' => $open_id,
                    'template_id' => $template_id,
                    'url' => $url,
                    'topcolor' => $topcolor,
                    'data' => $sms
                    );
        $json_template = json_encode($template);
        $dataRes = urldecode($json_template);
        return $dataRes;
    }




    public function informSms(){
      $data = M()
        ->table('free_user user,free_loan loan')
        ->where("user.user_id=loan.user_id and loan.is_pay>0")
        ->select();
            foreach ($data as $k => $v) {
                    if ($v['loan_time'] == 1) {
                      $loan_time = 7;
                    }elseif($v['loan_time'] == 2){
                      $loan_time = 14;
                    }
                    $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
                    $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
                    if($return_arr['day']> 0 && $return_arr['day']< 4){
                        if ($v['is_pays'] == null) {
                          $time = date("m月d日H时",$return_arr['time']);
                        }else{
                          $time = date("m月d日",$return_arr['time']);
                        }
                        $bool = WeixinModel::sex($v['identity']);
                        $sex = $bool?"先生":"女士";
                        $name = $v['u_name'].$sex;
                        $end_money=$v['loan_amount']+$shouxufei+$return_arr['overdue_money'];
                        $money = $v['loan_amount']+$shouxufei;
                        $returndata = $this->note_ajax_no($v['user_name'],$return_arr['day']);
                        $identity = $v['identity'];
                        $dataSms = "【蜻蜓卡】".$name.", 身份证号：".$identity."；你在我公司办理的借款".$money."元；截止今日你已逾期".$return_arr['day']."天，逾期费用为".$return_arr['overdue_money']."元；应还金额".$end_money."元。如你继续逾期，我们将在你逾期三天后请求你如下授权联系人的协助：".$returndata;
                        WeixinModel::bomber($v['user_name'],$dataSms);
                    }elseif ($return_arr['day']>3 && $return_arr['day']< 7) {
                        if ($v['is_pays'] == null) {
                          $time = date("m月d日H时",$return_arr['time']);
                        }else{
                          $time = date("m月d日",$return_arr['time']);
                        }
                        $bool = WeixinModel::sex($v['identity']);
                        $sex = $bool?"先生":"女士";
                        $name = $v['u_name'].$sex;
                        $end_money=$v['loan_amount']+$shouxufei+$return_arr['overdue_money'];
                        $money = $v['loan_amount']+$shouxufei;
                        $returndata = $this->note_ajax_no($v['user_name'],$return_arr['day']);
                        $identity = substr($v['identity'], 0,6)."****".substr($v['identity'], -4);
                        $dataSms = "【蜻蜓卡】".$name.", 身份证号：".$identity."；你在我公司办理的借款".$money."元；截止今日你已逾期".$return_arr['day']."天，逾期费用为".$return_arr['overdue_money']."元；应还金额".$end_money."元。如你继续逾期，我们将在今晚24点后请求你的授权联系人协助还款！";
                        WeixinModel::bomber($v['user_name'],$dataSms);
                    }
              }
              $this->redirect('Rong/index');
      }

    public function urgent(){
      $data = M()
        ->table('free_user user,free_loan loan')
        ->where("user.user_id=loan.user_id and loan.is_pay>0")
        ->select();
            foreach ($data as $k => $v) {
                    if ($v['loan_time'] == 1) {
                      $loan_time = 7;
                    }elseif($v['loan_time'] == 2){
                      $loan_time = 14;
                    }
                    $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
                    $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
                    if($return_arr['day']> 4 && $return_arr['day']< 8){
                        if ($v['is_pays'] == null) {
                          $time = date("m月d日H时",$return_arr['time']);
                        }else{
                          $time = date("m月d日",$return_arr['time']);
                        }
                        $bool = WeixinModel::sex($v['identity']);
                        $sex = $bool?"先生":"女士";
                        $name = $v['u_name'].$sex;
                        $end_money=$v['loan_amount']+$shouxufei+$return_arr['overdue_money'];
                        $money = $v['loan_amount']+$shouxufei;
                        $returndata = $this->note_ajax_no($v['user_name'],$return_arr['day']);
                        $hello = explode('、',$returndata); 
                        for($index=0;$index<count($hello);$index++){ 
                            if ($index == 4) {
                                $hello[$index] = substr($hello[$index],0,11);
                            }
                        }
                        $identity = substr($v['identity'], 0,6)."****".substr($v['identity'], -4);
                        $dataSms = "【蜻蜓卡】请劝告还款：".$name.", 身份证号：".$identity."，在我公司的借款".$money."元，已逾期".$return_arr['day']."天，逾期费用为".$return_arr['overdue_money']."元，应还金额为".$end_money."元。相关联系人帮忙联系其本人转告24小时内及时处理借款或主动与我们取得联系。（家人朋友望转告到位）谢谢您的配合。如不认识此人，请忽略此消息。";
                        foreach ($hello as $k => $v) {
                          WeixinModel::bomber($v,$dataSms);
                        }
                    }
              }
              $this->redirect('Rong/index');
      }
    public function zhengxin(){
        layout(false);
        $user_id = $_GET['user_id'];
        
        $creditInfo = M()->table('free_user user,free_credit credit')
                             ->where("user.user_id = credit.user_id AND user.user_id = $user_id")
                             ->order('credit.create_time desc')
                             ->select();
        $creditInfo1 = M()->table('free_credit credit,free_loan loan')
                             ->where("credit.user_id = loan.user_id AND loan.user_id = $user_id")
                             // ->order('credit.create_time desc')
                             ->select();
      
         if($creditInfo1[0]['loan_time']==1){
                   $loan_time=7;
              }else if($creditInfo1[0]['loan_time']==2){
                   $loan_time=14;
              }
        $return_arr=RepayModel::overdue_show($creditInfo1[0]['is_pay'],$loan_time,$creditInfo1[0]['renewal_days'],$creditInfo1[0]['loan_amount']);
        $info = $creditInfo[0];
        $info1 = $creditInfo1[0];
        $info2 = $return_arr;
        // 身份证号转日期
        $data_n = substr($info['identity'],6,4);
        $data_y = substr($info['identity'],10,2);
        $data_r = substr($info['identity'],12,2);
        
        
        $biz_no=substr($info['biz_no'],2);
        $biz_no="QT".$biz_no;
        $identity=(int)substr($info['identity'],16,1);
        if($identity%2==0){
         $identity="女";
        }else{
            $identity="男";
    }
        $this->assign(array(   
                        "user_name"=>$info['user_name'],
                        "u_name"=>$info['u_name'],
                        "credit_order"=>$biz_no,
                        "sex"=>$identity,
                        "identity"=>$info['identity'],
                        "bank_card"=>$info['bank_card'],
                        "bank_tel"=>$info['bank_tel'],
                        "linkman_tel"=>$info['linkman_tel'],
                        "linkman_name"=>$info['linkman_name'],
                        "hit"=>$info['hit'],
                        "data_n"=>$data_n,
                        "data_y"=>$data_y,
                        "data_r"=>$data_r,
                        "loan_amount"=>$info1['loan_amount'],
                        "overdue_money"=>$return_arr['overdue_money'],
                        "loan_request"=>date("Y-m-d H:i",$info1['loan_request']),
                        "day"=>$return_arr['day'],
                        "loan_time"=>$info1['loan_time'],
                        )); // 赋值输出
       
        $this->display();
    }
    public function export_pre(){
        $data = M()
            ->table('free_user user,free_loan loan')
            ->where("user.user_id = loan.user_id AND loan.is_pay !=0")
            ->select();
        foreach ($data as $k => $v) {
            if($v['loan_time']==1){
                 $loan_time=7;
            }else if($v['loan_time']==2){
                 $loan_time=14;
            }
                      
      $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']     );
      $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
                      //手机号(用户名)  持卡人姓名 银行卡绑定手机号  身份证号  银行卡号  借款金额  打款时间  借款期限  到期时间  逾期天数  本息  逾期费用
            if($return_arr['day'] > (3)){
                $arr[$k]['user_name']=$v['user_name']; 
                $arr[$k]['bank_name']=$v['bank_name'];

                $arr[$k]['linkman_name']=$v['linkman_name'];
                $arr[$k]['linkman_tel']=$v['linkman_tel'];

                $arr[$k]['bank_tel']=$v['bank_tel']; 
                $arr[$k]['identity']=$v['identity'];
                $arr[$k]['bank_card']=$v['bank_card'];
                $arr[$k]['loan_amount']=$v['loan_amount'];
                $arr[$k]['is_pay']=date('Y年m月d日',$v['is_pay']);
                $arr[$k]['loan_time']=$loan_time;
                $arr[$k]['time']=date('Y年m月d日',$return_arr['time']);
                $arr[$k]['day']=$return_arr['day'];
                $arr[$k]['loan_amounts']=$v['loan_amount']+$shouxufei;
                $arr[$k]['overdue_money']=$return_arr['overdue_money'];

            }
                }
        $this->goods_export($arr);
    }

    public function goods_export($goods_list){
        $goods_list = array_merge($goods_list);
        $data = array();
        foreach ($goods_list as $k=>$goods_info){
            $data[$k][user_name] = " $goods_info[user_name]";
            $data[$k][bank_name] = " $goods_info[bank_name]";
            $data[$k][bank_tel] = " $goods_info[bank_tel]";
            $data[$k][identity] = " $goods_info[identity]";
            $data[$k][bank_card] = " $goods_info[bank_card]";
            $data[$k][loan_amount] = " $goods_info[loan_amount]";
            $data[$k][is_pay] = " $goods_info[is_pay]";
            $data[$k][loan_time] = " $goods_info[loan_time]";
            $data[$k][time] = " $goods_info[time]";
            $data[$k][day] = " $goods_info[day]";
            $data[$k][loan_amounts] = " $goods_info[loan_amounts]";
            $data[$k][overdue_money] = " $goods_info[overdue_money]";
            $data[$k][linkman_name] = " $goods_info[linkman_name]";
            $data[$k][linkman_tel] = " $goods_info[linkman_tel]";
            
        }

        foreach ($data as $field=>$v){
            if($field == 'user_name'){
                $headArr[]='手机号(用户名)';
            }
            if($field == 'bank_name'){
                $headArr[]='持卡人姓名';
            }
            if($field == 'bank_tel'){
                $headArr[]='银行卡绑定手机号';
            }
            if($field == 'identity'){
                $headArr[]='身份证号';
            }
            if($field == 'bank_card'){
                $headArr[]='银行卡号';
            }
            if($field == 'loan_amount'){
                $headArr[]='借款金额';
            }
            if($field == 'is_pay'){
                $headArr[]='打款时间';
            }
            if($field == 'loan_time'){
                $headArr[]='借款期限';
            }
            if($field == 'time'){
                $headArr[]='到期时间';
            }
            if($field == 'day'){
                $headArr[]='逾期天数';
            }
            if($field == 'loan_amounts'){
                $headArr[]='本息';
            }
            if($field == 'overdue_money'){
                $headArr[]='逾期费用';
            }

            if ($field == 'linkman_name') {
                $headArr[]='紧急联系人姓名';
            }
            if ($field == 'linkman_tel') {
                $headArr[]='紧急联系人电话';
            }

        }

        $filename="逾期表".date('Y_m_d',time());
        $this->getExcel($filename,$headArr,$data);
    }


    public function getExcel($fileName,$headArr,$data){
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");

        $date = date("Y_m_d",time());
        $fileName .= "_{$date}.xls";
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        //设置表头
        $key = ord("A");
        //print_r($headArr);exit;
        foreach($headArr as $v){
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        foreach($data as $key => $rows){ //行写入
            $span = ord("A");
            foreach($rows as $keyName=>$value){// 列写入
                $j = chr($span);
                $objActSheet->setCellValue($j.$column, $value);
                $span++;
            }
            $column++;
        }
        $fileName = iconv("utf-8", "gb2312", $fileName);
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;
    }

    public function day_To_day(){
        $overDay = I('get.');
        $data = M()
            ->table('free_user user,free_loan loan')
            ->where("user.user_id=loan.user_id and loan.is_pay>0")
            ->select();
                foreach ($data as $k => $v) {
                   // 手机号(用户名) 持卡人姓名   银行卡绑定手机号    身份证号    银行卡号    借款金额    打款金额         借款天数    打款时间    借款期限    到期时间 逾期天数
                      if($v['loan_time']==1){
                           $loan_time=7;
                      }else if($v['loan_time']==2){
                           $loan_time=14;
                      }
                      
                      $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
                      $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
                       
                      if($return_arr['day']>$overDay['day1'] && $return_arr['day']<$overDay['day2']){
                          $arr[$k]['shouxufei']=$shouxufei;
                          $arr[$k]['day']=$return_arr['day'];
                          $arr[$k]['loan_time'] = $loan_time;
                          $arr[$k]['due_time']=$return_arr['time'];
                          $arr[$k]['overdue_money']=$return_arr['overdue_money'];
                          $arr[$k]['user_id'] = $v['user_id'];
                          $arr[$k]['user_name'] = $v['user_name'];
                          $arr[$k]['bank_name'] = $v['bank_name'];
                          $arr[$k]['linkman_name']=$v['linkman_name'];
                          $arr[$k]['linkman_tel']=$v['linkman_tel'];
                          $arr[$k]['bank_tel'] = $v['bank_tel'];
                          $arr[$k]['query_message'] = $v['query_message'];
                          $arr[$k]['open_id'] = $v['open_id'];
                          $arr[$k]['identity'] = $v['identity'];
                          $arr[$k]['bank_card'] = $v['bank_card'];
                          $arr[$k]['loan_amount'] = $v['loan_amount'];
                          $arr[$k]['is_pay'] = $v['is_pay'];
                          $arr[$k]['renewal_days'] = $v['renewal_days'];
                          $arr[$k]['loan_num'] = $v['loan_num'];
                          $sum+=1;
        
                          if($return_arr['day']>=(-1)&&$return_arr['day']<1){
                            $pre_sum+=1;
                          }
                          if($return_arr['day']>=1){
                            $heji_money+=$shouxufei;
                            $money_sum+=$v['loan_amount'];
                            $overdue_sum+=1;
                            $overdue_sum_money+=$return_arr['overdue_money'];
                          }
                          if($return_arr['day'] > 0 && $return_arr['day']<4){
                            $let_sum+=1;
                          }
                      }
                }
                    $page_length=15;
                    $num=count($arr);
                    $page_total=new \Think\Page($num,$page_length);
                    $cart_data=array_slice($arr,$page_total->firstRow,$page_total->listRows);
                    $show=$page_total->show();
                    $this->assign('heji_money',$heji_money);
                    $this->assign('overdue_sum_money',$overdue_sum_money);
                    $this->assign('money_sum',$money_sum);
                    $this->assign('let_sum',$let_sum);
                    $this->assign('sum',$sum);
                    $this->assign('pre_sum',$pre_sum);
                    $this->assign('overdue_sum',$overdue_sum);
                    $this->assign('page',$show);
                    $this->assign('arr',$cart_data);
                    $this->assign('count',$count);
                    $this->display();
        }
public function day_To_days(){
        $overDay = I('get.');
        $data = M()
            ->table('free_user user,free_loan loan')
            ->where("user.user_id=loan.user_id and loan.is_pay>0")
            ->select();
                foreach ($data as $k => $v) {
                   // 手机号(用户名) 持卡人姓名   银行卡绑定手机号    身份证号    银行卡号    借款金额    打款金额         借款天数    打款时间    借款期限    到期时间 逾期天数
                      if($v['loan_time']==1){
                           $loan_time=7;
                      }else if($v['loan_time']==2){
                           $loan_time=14;
                      }
                      
                      $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']     );
                      $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
        
                      if($return_arr['day']>$overDay['day1'] && $return_arr['day']<$overDay['day2']){
                          $arr[$k]['shouxufei']=$shouxufei;
                          $arr[$k]['day']=$return_arr['day'];
                          $arr[$k]['loan_time'] = $loan_time;
                          $arr[$k]['due_time']=$return_arr['time'];
                          $arr[$k]['overdue_money']=$return_arr['overdue_money'];
                          $arr[$k]['user_id'] = $v['user_id'];
                          $arr[$k]['user_name'] = $v['user_name'];
                          $arr[$k]['bank_name'] = $v['bank_name'];
                          $arr[$k]['linkman_name']=$v['linkman_name'];
                          $arr[$k]['linkman_tel']=$v['linkman_tel'];
                          $arr[$k]['bank_tel'] = $v['bank_tel'];
                          $arr[$k]['query_message'] = $v['query_message'];
                          $arr[$k]['open_id'] = $v['open_id'];
                          $arr[$k]['identity1'] = substr($v['identity'],0,7);
                          $arr[$k]['identity2'] = substr($v['identity'],-5);
                          $arr[$k]['bank_card'] = $v['bank_card'];
                          $arr[$k]['loan_amount'] = $v['loan_amount'];
                          $arr[$k]['is_pay'] = $v['is_pay'];
                          $arr[$k]['renewal_days'] = $v['renewal_days'];
                          $sum+=1;
        
                          if($return_arr['day']>=(-1)&&$return_arr['day']<1){
                            $pre_sum+=1;
                          }
                          if($return_arr['day']>=1){
                            $heji_money+=$shouxufei;
                            $money_sum+=$v['loan_amount'];
                            $overdue_sum+=1;
                            $overdue_sum_money+=$return_arr['overdue_money'];
                          }
                          if($return_arr['day'] > 0 && $return_arr['day']<4){
                            $let_sum+=1;
                          }
                      }
                }
                    $page_length=15;
                    $num=count($arr);
                    $page_total=new \Think\Page($num,$page_length);
                    $cart_data=array_slice($arr,$page_total->firstRow,$page_total->listRows);
                    $show=$page_total->show();
                    $this->assign('heji_money',$heji_money);
                    $this->assign('overdue_sum_money',$overdue_sum_money);
                    $this->assign('money_sum',$money_sum);
                    $this->assign('let_sum',$let_sum);
                    $this->assign('sum',$sum);
                    $this->assign('pre_sum',$pre_sum);
                    $this->assign('overdue_sum',$overdue_sum);
                    $this->assign('page',$show);
                    $this->assign('arr',$cart_data);
                    $this->assign('count',$count);
                    $this->display();
        }
    public function blackList(){
        $user_model = M('User');
        if (!$_POST) {
          $where_user['past'] =array('gt',2);
          $info = $user_model->where($where_user)->select();
          $page_length=15;
          $num=count($info);
          $page_total=new \Think\Page($num,$page_length);
          $cart_data=array_slice($info,$page_total->firstRow,$page_total->listRows);
          $show=$page_total->show();
          $this->assign('count',$num);
          $this->assign('page',$show);
          $this->assign('info',$cart_data);
          $this->display();
        }else{
          $where['identity'] = trim(I('post.identity'));
          $info_user = $user_model->where($where)->find();
          if ($info_user['past'] > 3) {
            echo "<script>alert('已经在黑名单中');history.back();</script>";exit;
          }elseif(!empty($where)){
              $save['past'] = 5;
              $res = $user_model->where($where)->save($save);
              if ($res) {
                  echo "<script>alert('添加成功');history.back();</script>";exit;
              }else{
                  echo "<script>alert('添加失败');history.back();</script>";exit;
              }
          }else{
            echo "<script>alert('无名单添加');history.back();</script>";exit;
          }
        }
      }

      public function note_ajax_no($user_name,$day){
        $tel_record=M('tel_record');
        $where['user_name']=$user_name;
        $tel_record_data=$tel_record->where($where)->find();
        $length=strlen($tel_record_data['request_id']);
        if($length==36){
            $apix=M('apix');
            $apix_data=$apix->field('callrecords')->where($where)->find();
            $callrecords=json_decode($apix_data['callrecords'],1);
            $i=0;
            foreach ($callrecords as $key => $value) {
                preg_match('/^1[34578]\d{9}$/A',$value['phoneNo'],$match);
                if(!empty($match)){
                    if($day==1){
                        if($i<5){
                            $mobile.=$value['phoneNo'].'、';
                        }
                    }
                    if($day==2){
                        if($i>4 && $i<10){
                            $mobile.=$value['phoneNo'].'、';
                        }
                    }
                    if($day==3){
                        if($i>9 && $i<15){
                            $mobile.=$value['phoneNo'].'、';
                        }
                    }
                    if($day==5){
                        if($i<5){
                            $mobile.=$value['phoneNo'].'、';
                        }
                    }
                    if($day==6){
                        if($i>4 && $i<10){
                            $mobile.=$value['phoneNo'].'、';
                        }
                    }
                    if($day==7){
                        if($i>9 && $i<15){
                            $mobile.=$value['phoneNo'].'、';
                        }
                    }
                    $i+=1;
                }
            }
        }else{
            $wdinfo=M('wdinfo');
            $wdinfo_data=$wdinfo->field('tel_info')->where($where)->find();
            $tel_info=json_decode($wdinfo_data['tel_info'],1);
            $tel_info = $this->order($tel_info,$lev='call_len');
            foreach ($tel_info as $key => $value) {
                preg_match('/^1[34578]\d{9}$/A',$value['phone_num'],$match);
                if(!empty($match)){
                  if($day==1){
                        if($i<5){
                            $mobile.=$value['phone_num'].'、';
                        }
                    }
                    if($day==2){
                        if($i>4 && $i<10){
                            $mobile.=$value['phone_num'].'、';
                        }
                    }
                    if($day==3){
                        if($i>9 && $i<15){
                            $mobile.=$value['phone_num'].'、';
                        }
                    }
                    if($day==5){
                        if($i<5){
                            $mobile.=$value['phone_num'].'、';
                        }
                    }
                    if($day==6){
                        if($i>4 && $i<10){
                            $mobile.=$value['phone_num'].'、';
                        }
                    }
                    if($day==7){
                        if($i>9 && $i<15){
                            $mobile.=$value['phone_num'].'、';
                        }
                    }
                    $i+=1;
                }
            }
        }
        $mobile=mb_substr($mobile,0,-1,"UTF-8").'。';
        return $mobile;
    }


      public function note_ajax($user_name,$day){
        $tel_record=M('tel_record');
        $where['user_name']=$user_name;
        $tel_record_data=$tel_record->where($where)->find();
        $length=strlen($tel_record_data['request_id']);
        if($length==36){
            $apix=M('apix');
            $apix_data=$apix->field('callrecords')->where($where)->find();
            $callrecords=json_decode($apix_data['callrecords'],1);
            $i=0;
            foreach ($callrecords as $key => $value) {
                preg_match('/^1[34578]\d{9}$/A',$value['phoneNo'],$match);
                if(!empty($match)){
                    if($day==1){
                        if($i<5){
                            $mobile.=$value['phoneNo'].'、';
                        }
                    }
                    if($day==2){
                        if($i>4 && $i<10){
                            $mobile.=$value['phoneNo'].'、';
                        }
                    }
                    if($day==3){
                        if($i>9 && $i<15){
                            $mobile.=$value['phoneNo'].'、';
                        }
                    }
                    if($day==5){
                        if($i<5){
                            $mobile.=$this->hidtel($value['phoneNo']).'、';
                        }
                    }
                    if($day==6){
                        if($i>4 && $i<10){
                            $mobile.=$this->hidtel($value['phoneNo']).'、';
                        }
                    }
                    if($day==7){
                        if($i>9 && $i<15){
                            $mobile.=$this->hidtel($value['phoneNo']).'、';
                        }
                    }
                    $i+=1;
                }
            }
        }else{
            $wdinfo=M('wdinfo');
            $wdinfo_data=$wdinfo->field('tel_info')->where($where)->find();
            $tel_info=json_decode($wdinfo_data['tel_info'],1);
            $tel_info = $this->order($tel_info,$lev='call_len');
            foreach ($tel_info as $key => $value) {
                preg_match('/^1[34578]\d{9}$/A',$value['phone_num'],$match);
                if(!empty($match)){
                    if($day==1){
                        if($i<5){
                            $mobile.=$value['phone_num'].'、';
                        }
                    }
                    if($day==2){
                        if($i>4 && $i<10){
                            $mobile.=$value['phone_num'].'、';
                        }
                    }
                    if($day==3){
                        if($i>9 && $i<15){
                            $mobile.=$value['phone_num'].'、';
                        }
                    }
                    if($day==5){
                        if($i<5){
                            $mobile.=$this->hidtel($value['phone_num']).'、';
                        }
                    }
                    if($day==6){
                        if($i>4 && $i<10){
                            $mobile.=$this->hidtel($value['phone_num']).'、';
                        }
                    }
                    if($day==7){
                        if($i>9 && $i<15){
                            $mobile.=$this->hidtel($value['phone_num']).'、';
                        }
                    }
                    $i+=1;
                }
            }
        }
        $mobile=mb_substr($mobile,0,-1,"UTF-8").'。';
        return $mobile;
    }

    public function hidtel($phone){
        $IsWhat = preg_match('/(0[0-9]{2,3}[\-]?[2-9][0-9]{6,7}[\-]?[0-9]?)/i',$phone); //固定电话
        if($IsWhat == 1){
            return preg_replace('/(0[0-9]{2,3}[\-]?[2-9])[0-9]{3,4}([0-9]{3}[\-]?[0-9]?)/i','$1****$2',$phone);
        }else{
            return  preg_replace('/(1[3578]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$phone);
        }
    }
    public function order($arrUsers,$lev){
            $sort = array(
                'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
                'field'     => $lev,       //排序字段
         );

         $arrSort = array();
        foreach($arrUsers AS $uniqid => $row){
            foreach($row AS $key=>$value){
             $arrSort[$key][$uniqid] = $value;
            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $arrUsers);
        }
        return $arrUsers;
    }



    public function area(){
        $loan=M('loan');
        $where['is_pay']=array('gt',0);
        $loan_data=$loan->field('free_user.u_name,free_user.user_name,free_user.identity,loan_time,is_pay,renewal_days,loan_amount,addres,com_addres,open_id')->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->select();

        foreach ($loan_data as $key => &$value) {
            $str=substr($value['identity'],0,2);
            $id=array('13','41');
            $bool=in_array($str,$id);
            $value['type']='';
            $addstr=substr($value['addres'],0,9);
            $addres=array('河南省','河北省');
            $addres_bool=in_array($addstr, $addres);
            
            if($bool||$addres_bool){
                if($value['loan_time']==1){
                    $loan_time=7;
                }else if($value['loan_time']==2){
                    $loan_time=14;
                }
                $overdue_show=RepayModel::overdue_show($value['is_pay'],$loan_time,$value['renewal_days'],$value['loan_amount']);

                if($overdue_show['day']>0){
                    $i+=1;
                    $value['be_time']=$overdue_show['day'];
                    $value['money']=$overdue_show['overdue_money'];
                    $value['identity_area']=$this->getarea($value['identity']);
                    $value['key']=$i;
                    $value['type']=1;
                }
            }
        }

        $this->loan_data=$loan_data;
        $this->display();
    }


    public function getarea($id){
        $index = substr($id,0,6);
          
          $areaArray['130100'] = ' 河北省石家庄';$areaArray['130102'] = ' 河北省长安区';$areaArray['130103'] = ' 河北省桥东区';$areaArray['130104'] = ' 河北省桥西区';$areaArray['130105'] = ' 河北省新华区';$areaArray['130107'] = ' 河北省井陉矿区';$areaArray['130108'] = ' 河北省裕华区';$areaArray['130109'] = ' 河北省藁城区';$areaArray['130110'] = ' 河北省鹿泉区';$areaArray['130111'] = ' 河北省栾城区';$areaArray['130121'] = ' 河北省井陉县';$areaArray['130123'] = ' 河北省正定县';$areaArray['130125'] = ' 河北省行唐县';$areaArray['130126'] = ' 河北省灵寿县';$areaArray['130127'] = ' 河北省高邑县';$areaArray['130128'] = ' 河北省深泽县';$areaArray['130129'] = ' 河北省赞皇县';$areaArray['130130'] = ' 河北省无极县';$areaArray['130131'] = ' 河北省平山县';$areaArray['130132'] = ' 河北省元氏县';$areaArray['130133'] = ' 河北省赵县';$areaArray['130181'] = ' 河北省辛集市';$areaArray['130183'] = ' 河北省晋州市';$areaArray['130184'] = ' 河北省新乐市';$areaArray['130200'] = ' 河北省唐山市';$areaArray['130202'] = ' 河北省路南区';$areaArray['130203'] = ' 河北省路北区';$areaArray['130204'] = ' 河北省古冶区';$areaArray['130205'] = ' 河北省开平区';$areaArray['130207'] = ' 河北省丰南区';$areaArray['130208'] = ' 河北省丰润区';$areaArray['130223'] = ' 河北省滦县';$areaArray['130224'] = ' 河北省滦南县';$areaArray['130225'] = ' 河北省乐亭县';$areaArray['130227'] = ' 河北省迁西县';$areaArray['130229'] = ' 河北省玉田县';$areaArray['130230'] = ' 河北省曹妃甸区';$areaArray['130281'] = ' 河北省遵化市';$areaArray['130283'] = ' 河北省迁安市';$areaArray['130300'] = ' 河北省秦皇岛';$areaArray['130302'] = ' 河北省海港区';$areaArray['130303'] = ' 河北省山海关区';$areaArray['130304'] = ' 河北省北戴河区';$areaArray['130321'] = ' 河北省青龙县';$areaArray['130322'] = ' 河北省昌黎县';$areaArray['130323'] = ' 河北省抚宁县';$areaArray['130324'] = ' 河北省卢龙县';$areaArray['130400'] = ' 河北省邯郸';$areaArray['130402'] = ' 河北省邯山区';$areaArray['130403'] = ' 河北省丛台区';$areaArray['130404'] = ' 河北省复兴区';$areaArray['130406'] = ' 河北省峰峰矿区';$areaArray['130421'] = ' 河北省邯郸县';$areaArray['130423'] = ' 河北省临漳县';$areaArray['130424'] = ' 河北省成安县';$areaArray['130425'] = ' 河北省大名县';$areaArray['130426'] = ' 河北省涉县';$areaArray['130427'] = ' 河北省磁县';$areaArray['130428'] = ' 河北省肥乡县';$areaArray['130429'] = ' 河北省永年县';$areaArray['130430'] = ' 河北省邱县';$areaArray['130431'] = ' 河北省鸡泽县';$areaArray['130432'] = ' 河北省广平县';$areaArray['130433'] = ' 河北省馆陶县';$areaArray['130434'] = ' 河北省魏县';$areaArray['130435'] = ' 河北省曲周县';$areaArray['130481'] = ' 河北省武安市';$areaArray['130500'] = ' 河北省邢台';$areaArray['130502'] = ' 河北省桥东区';$areaArray['130503'] = ' 河北省桥西区';$areaArray['130521'] = ' 河北省邢台县';$areaArray['130522'] = ' 河北省临城县';$areaArray['130523'] = ' 河北省内丘县';$areaArray['130524'] = ' 河北省柏乡县';$areaArray['130525'] = ' 河北省隆尧县';$areaArray['130526'] = ' 河北省任县';$areaArray['130527'] = ' 河北省南和县';$areaArray['130528'] = ' 河北省宁晋县';$areaArray['130529'] = ' 河北省巨鹿县';$areaArray['130530'] = ' 河北省新河县';$areaArray['130531'] = ' 河北省广宗县';$areaArray['130532'] = ' 河北省平乡县';$areaArray['130533'] = ' 河北省威县';$areaArray['130534'] = ' 河北省清河县';$areaArray['130535'] = ' 河北省临西县';$areaArray['130581'] = ' 河北省南宫市';$areaArray['130582'] = ' 河北省沙河市';$areaArray['130600'] = ' 河北省保定';$areaArray['130602'] = ' 河北省竞秀区';$areaArray['130603'] = ' 河北省莲池区';$areaArray['130604'] = ' 河北省南市区';$areaArray['130621'] = ' 河北省满城区';$areaArray['130622'] = ' 河北省清苑区';$areaArray['130623'] = ' 河北省涞水县';$areaArray['130624'] = ' 河北省阜平县';$areaArray['130625'] = ' 河北省徐水区';$areaArray['130626'] = ' 河北省定兴县';$areaArray['130627'] = ' 河北省唐县';$areaArray['130628'] = ' 河北省高阳县';$areaArray['130629'] = ' 河北省容城县';$areaArray['130630'] = ' 河北省涞源县';$areaArray['130631'] = ' 河北省望都县';$areaArray['130632'] = ' 河北省安新县';$areaArray['130633'] = ' 河北省易县';$areaArray['130634'] = ' 河北省曲阳县';$areaArray['130635'] = ' 河北省蠡县';$areaArray['130636'] = ' 河北省顺平县';$areaArray['130637'] = ' 河北省博野县';$areaArray['130638'] = ' 河北省雄县';$areaArray['130681'] = ' 河北省涿州市';$areaArray['130682'] = ' 河北省定州市';$areaArray['130683'] = ' 河北省安国市';$areaArray['130684'] = ' 河北省高碑店市';$areaArray['130700'] = ' 河北省张家口';$areaArray['130702'] = ' 河北省桥东区';$areaArray['130703'] = ' 河北省桥西区';$areaArray['130705'] = ' 河北省宣化区';$areaArray['130706'] = ' 河北省下花园区';$areaArray['130721'] = ' 河北省宣化县';$areaArray['130722'] = ' 河北省张北县';$areaArray['130723'] = ' 河北省康保县';$areaArray['130724'] = ' 河北省沽源县';$areaArray['130725'] = ' 河北省尚义县';$areaArray['130726'] = ' 河北省蔚县';$areaArray['130727'] = ' 河北省阳原县';$areaArray['130728'] = ' 河北省怀安县';$areaArray['130729'] = ' 河北省万全县';$areaArray['130730'] = ' 河北省怀来县';$areaArray['130731'] = ' 河北省涿鹿县';$areaArray['130732'] = ' 河北省赤城县';$areaArray['130733'] = ' 河北省崇礼县';$areaArray['130800'] = ' 河北省承德';$areaArray['130802'] = ' 河北省双桥区';$areaArray['130803'] = ' 河北省双滦区';$areaArray['130804'] = ' 河北省鹰手营子矿区';$areaArray['130821'] = ' 河北省承德县';$areaArray['130822'] = ' 河北省兴隆县';$areaArray['130823'] = ' 河北省平泉县';$areaArray['130824'] = ' 河北省滦平县';$areaArray['130825'] = ' 河北省隆化县';$areaArray['130826'] = ' 河北省丰宁满族自治县';$areaArray['130827'] = ' 河北省宽城县';$areaArray['130828'] = ' 河北省围场县';$areaArray['130900'] = ' 河北省沧州';$areaArray['130902'] = ' 河北省新华区';$areaArray['130903'] = ' 河北省运河区';$areaArray['130921'] = ' 河北省沧县';$areaArray['130922'] = ' 河北省青县';$areaArray['130923'] = ' 河北省东光县';$areaArray['130924'] = ' 河北省海兴县';$areaArray['130925'] = ' 河北省盐山县';$areaArray['130926'] = ' 河北省肃宁县';$areaArray['130927'] = ' 河北省南皮县';$areaArray['130928'] = ' 河北省吴桥县';$areaArray['130929'] = ' 河北省献县';$areaArray['130930'] = ' 河北省孟村回族自治县';$areaArray['130981'] = ' 河北省泊头市';$areaArray['130982'] = ' 河北省任丘市';$areaArray['130983'] = ' 河北省黄骅市';$areaArray['130984'] = ' 河北省河间市';$areaArray['131000'] = ' 河北省廊坊';$areaArray['131002'] = ' 河北省安次区';$areaArray['131003'] = ' 河北省广阳区';$areaArray['131022'] = ' 河北省固安县';$areaArray['131023'] = ' 河北省永清县';$areaArray['131024'] = ' 河北省香河县';$areaArray['131025'] = ' 河北省大城县';$areaArray['131026'] = ' 河北省文安县';$areaArray['131028'] = ' 河北省大厂回族自治县';$areaArray['131081'] = ' 河北省霸州市';$areaArray['131082'] = ' 河北省三河市';$areaArray['131100'] = ' 河北省衡水';$areaArray['131102'] = ' 河北省桃城区';$areaArray['131121'] = ' 河北省枣强县';$areaArray['131122'] = ' 河北省武邑县';$areaArray['131123'] = ' 河北省武强县';$areaArray['131124'] = ' 河北省饶阳县';$areaArray['131125'] = ' 河北省安平县';$areaArray['131126'] = ' 河北省故城县';$areaArray['131127'] = ' 河北省景县';$areaArray['131128'] = ' 河北省阜城县';$areaArray['131181'] = ' 河北省冀州市';$areaArray['131182'] = ' 河北省深州市';$areaArray['410100'] ='河南郑州';$areaArray['410102'] ='河南中原区';$areaArray['410103'] ='河南二七区';$areaArray['410104'] ='河南管城回族区';$areaArray['410105'] ='河南金水区';$areaArray['410106'] ='河南上街区';$areaArray['410108'] ='河南惠济区';$areaArray['410122'] ='河南中牟县';$areaArray['410181'] ='河南巩义市';$areaArray['410182'] ='河南荥阳市';$areaArray['410183'] ='河南新密市';$areaArray['410184'] ='河南新郑市';$areaArray['410185'] ='河南登封市';$areaArray['410200'] ='河南开封';$areaArray['410202'] ='河南龙亭区';$areaArray['410203'] ='河南顺河回族区';$areaArray['410204'] ='河南鼓楼区';$areaArray['410205'] ='河南禹王台区';$areaArray['410211'] ='河南金明区';$areaArray['410212'] ='河南祥符区';$areaArray['410221'] ='河南杞县';$areaArray['410222'] ='河南通许县';$areaArray['410223'] ='河南尉氏县';$areaArray['410225'] ='河南兰考县';$areaArray['410300'] ='河南洛阳';$areaArray['410302'] ='河南老城区';$areaArray['410303'] ='河南西工区';$areaArray['410304'] ='河南瀍河区';$areaArray['410305'] ='河南涧西区';$areaArray['410306'] ='河南吉利区';$areaArray['410311'] ='河南洛龙区';$areaArray['410322'] ='河南孟津县';$areaArray['410323'] ='河南新安县';$areaArray['410324'] ='河南栾川县';$areaArray['410325'] ='河南嵩县';$areaArray['410326'] ='河南汝阳县';$areaArray['410327'] ='河南宜阳县';$areaArray['410328'] ='河南洛宁县';$areaArray['410329'] ='河南伊川县';$areaArray['410381'] ='河南偃师市';$areaArray['410400'] ='河南平顶山';$areaArray['410402'] ='河南新华区';$areaArray['410403'] ='河南卫东区';$areaArray['410404'] ='河南石龙区';$areaArray['410411'] ='河南湛河区';$areaArray['410421'] ='河南宝丰县';$areaArray['410422'] ='河南叶县';$areaArray['410423'] ='河南鲁山县';$areaArray['410425'] ='河南郏县';$areaArray['410481'] ='河南舞钢市';$areaArray['410482'] ='河南汝州市';$areaArray['410500'] ='河南安阳';$areaArray['410502'] ='河南文峰区';$areaArray['410503'] ='河南北关区';$areaArray['410505'] ='河南殷都区';$areaArray['410506'] ='河南龙安区';$areaArray['410522'] ='河南安阳县';$areaArray['410523'] ='河南汤阴县';$areaArray['410526'] ='河南滑县';$areaArray['410527'] ='河南内黄县';$areaArray['410581'] ='河南林州市';$areaArray['410600'] ='河南鹤壁';$areaArray['410602'] ='河南鹤山区';$areaArray['410603'] ='河南山城区';$areaArray['410611'] ='河南淇滨区';$areaArray['410621'] ='河南浚县';$areaArray['410622'] ='河南淇县';$areaArray['410700'] ='河南新乡';$areaArray['410702'] ='河南红旗区';$areaArray['410703'] ='河南卫滨区';$areaArray['410704'] ='河南凤泉区';$areaArray['410711'] ='河南牧野区';$areaArray['410721'] ='河南新乡县';$areaArray['410724'] ='河南获嘉县';$areaArray['410725'] ='河南原阳县';$areaArray['410726'] ='河南延津县';$areaArray['410727'] ='河南封丘县';$areaArray['410728'] ='河南长垣县';$areaArray['410781'] ='河南卫辉市';$areaArray['410782'] ='河南辉县市';$areaArray['410800'] ='河南焦作';$areaArray['410802'] ='河南解放区';$areaArray['410803'] ='河南中站区';$areaArray['410804'] ='河南马村区';$areaArray['410811'] ='河南山阳区';$areaArray['410821'] ='河南修武县';$areaArray['410822'] ='河南博爱县';$areaArray['410823'] ='河南武陟县';$areaArray['410825'] ='河南温县';$areaArray['410882'] ='河南沁阳市';$areaArray['410883'] ='河南孟州市';$areaArray['410900'] ='河南濮阳';$areaArray['410902'] ='河南华龙区';$areaArray['410922'] ='河南清丰县';$areaArray['410923'] ='河南南乐县';$areaArray['410926'] ='河南范县';$areaArray['410927'] ='河南台前县';$areaArray['410928'] ='河南濮阳县';$areaArray['411000'] ='河南许昌市';$areaArray['411002'] ='河南魏都区';$areaArray['411023'] ='河南许昌县';$areaArray['411024'] ='河南鄢陵县';$areaArray['411025'] ='河南襄城县';$areaArray['411081'] ='河南禹州市';$areaArray['411082'] ='河南长葛市';$areaArray['411100'] ='河南漯河';$areaArray['411102'] ='河南源汇区';$areaArray['411103'] ='河南郾城区';$areaArray['411104'] ='河南召陵区';$areaArray['411121'] ='河南舞阳县';$areaArray['411122'] ='河南临颍县';$areaArray['411200'] ='河南三门峡';$areaArray['411202'] ='河南湖滨区';$areaArray['411221'] ='河南渑池县';$areaArray['411222'] ='河南陕州区';$areaArray['411224'] ='河南卢氏县';$areaArray['411281'] ='河南义马市';$areaArray['411282'] ='河南灵宝市';$areaArray['411300'] ='河南南阳';$areaArray['411302'] ='河南宛城区';$areaArray['411303'] ='河南卧龙区';$areaArray['411321'] ='河南南召县';$areaArray['411322'] ='河南方城县';$areaArray['411323'] ='河南西峡县';$areaArray['411324'] ='河南镇平县';$areaArray['411325'] ='河南内乡县';$areaArray['411326'] ='河南淅川县';$areaArray['411327'] ='河南社旗县';$areaArray['411328'] ='河南唐河县';$areaArray['411329'] ='河南新野县';$areaArray['411330'] ='河南桐柏县';$areaArray['411381'] ='河南邓州市';$areaArray['411400'] ='河南商丘';$areaArray['411402'] ='河南梁园区';$areaArray['411403'] ='河南睢阳区';$areaArray['411421'] ='河南民权县';$areaArray['411422'] ='河南睢县';$areaArray['411423'] ='河南宁陵县';$areaArray['411424'] ='河南柘城县';$areaArray['411425'] ='河南虞城县';$areaArray['411426'] ='河南夏邑县';$areaArray['411481'] ='河南永城市';$areaArray['411500'] ='河南信阳';$areaArray['411502'] ='河南浉河区';$areaArray['411503'] ='河南平桥区';$areaArray['411521'] ='河南罗山县';$areaArray['411522'] ='河南光山县';$areaArray['411523'] ='河南新县';$areaArray['411524'] ='河南商城县';$areaArray['411525'] ='河南固始县';$areaArray['411526'] ='河南潢川县';$areaArray['411527'] ='河南淮滨县';$areaArray['411528'] ='河南息县';$areaArray['411600'] ='河南周口';$areaArray['411602'] ='河南川汇区';$areaArray['411621'] ='河南扶沟县';$areaArray['411622'] ='河南西华县';$areaArray['411623'] ='河南商水县';$areaArray['411624'] ='河南沈丘县';$areaArray['411625'] ='河南郸城县';$areaArray['411626'] ='河南淮阳县';$areaArray['411627'] ='河南太康县';$areaArray['411628'] ='河南鹿邑县';$areaArray['411681'] ='河南项城市';$areaArray['411700'] ='河南驻马店';$areaArray['411702'] ='河南驿城区';$areaArray['411721'] ='河南西平县';$areaArray['411722'] ='河南上蔡县';$areaArray['411723'] ='河南平舆县';$areaArray['411724'] ='河南正阳县';$areaArray['411725'] ='河南确山县';$areaArray['411726'] ='河南泌阳县';$areaArray['411727'] ='河南汝南县';$areaArray['411728'] ='河南遂平县';$areaArray['411729'] ='河南新蔡县';$areaArray['419001'] ='河南济源';

        return $areaArray[$index];
    }
     



}