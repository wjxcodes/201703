<?php 
/*
*已放款
*/
namespace Home\Controller;
use Think\Controller;
use Home\Model\MoneyModel as MoneyModel;
use Home\Model\RepayModel as RepayModel;
use Home\Model\WeixinModel as WeixinModel;
header("content-type:text/html;charset=utf8");
class AlreadyController extends BaseController{
    protected $task_model;
    protected $loan_model;
    public function __construct(){
        parent::__construct();
        $this->task_model = M('Task');
        $this->loan_model = M('Loan');
    }
/*放款主页面*/
    public function index(){
        $loan=M('loan');
        $where['is_pay']=array('gt',0);
        $loan_count=$loan->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->count();
        $Page = new \Think\Page($loan_count,15);
        $loan_data=$loan->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->limit($Page->firstRow.','.$Page->listRows)->order('free_loan.is_pays desc')->select();
        $loan_sum=$loan->field('loan_amount,interest')->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->select();
        foreach ($loan_sum as $key => $value) {
            $cuts_interest=!empty($value['cuts_interest']) ? $value['cuts_interest']:null;
            $sum['shouxufei']+=MoneyModel::poundage($value['loan_amount'],$value['interest'],$cuts_interest);
            $sum['sum_money']+=$value['loan_amount'];
            $sum['ren']+=1;
        }
/*还款时间插入数组*/
        foreach ($loan_data as $key => &$value) {
            if($value['loan_time']==1){
                   $loan_time=7;
            }else if($value['loan_time']==2){
                 $loan_time=14;
            }
            $return_arr=RepayModel::overdue_show($value['is_pay'],$loan_time,$value['renewal_days'],$value['loan_amount']);
            $value['overdue_money']=$return_arr['overdue_money'];
            $value['be_time']=$return_arr['time'];
            $value['loan_time']=$loan_time;
        }
        $show = $Page->show();
        $this->assign('page',$show);
        $this->assign('sum',$sum);
        $this->assign('loan_data',$loan_data);
        $this->display('Already/index');
    }



    public function stages(){
        $get=I('get.');
        $order=$get['order'];

        $stages_data=D('pinch')->zfb_stage_select($order);

        if($_POST){
            $post=I('post.');

            $stages_res=D('pinch')->zfb_stage_add($post,$get);
            if($stages_res){
                $this->redirect('Home/Already/index');
            }else{
                die('<script>alert("失败了！！！");history.back()</script>');
            }
        }


        $this->stages_data=$stages_data;
        $this->order=$order;
        $this->display();
    }







/* 放款时间查找*/
/*搜索手机号姓名*/
    public function search(){
        $loan=M('loan');
        $post=I('post.');
        switch ($post['code']) {
/* 放款时间查找*/
            case 'lending':
                $start_time=strtotime($post['start']);
                $end = strtotime($post['end']);
                $end_time=$end+86400;
                $where['is_pay']=array(array('egt',$start_time),array('elt',$end_time));
                $loan_data=$loan->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->order('free_loan.is_pays desc')->select();
                break;
/*搜索手机号姓名*/
            case 'search';
                if (is_numeric($post['name'])) {
                    $where['free_user.user_name']=$post['name'];
                }else{
                    $where['free_user.u_name']=$post['name'];
                }
                $where['is_pay']=array('gt',0);
                $loan_data=$loan->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->select();
                break;           
            default:

             break;
        }
/*还款时间插入数组*/
        $psun = 0;
        $pmoney = 0;
        foreach ($loan_data as $key => &$value) {
            if($value['loan_time']==1){
                   $loan_time=7;
            }else if($value['loan_time']==2){
                 $loan_time=14;
            }

            if($value['loan_num']<1){
                $sum['new_user_num']+=1;
                $sum['new_user']+=$value['loan_amount'];
            }else{
                $sum['old_user_num']+=1;
                $sum['old_user']+=$value['loan_amount'];
            }


            if(!$value['loan_num']){
                $psun = $psun+1;
                $pmoney = $pmoney+$value['loan_amount'];
            }

            $return_arr=RepayModel::overdue_show($value['is_pay'],$loan_time,$value['renewal_days'],$value['loan_amount']);
            $value['overdue_money']=$return_arr['overdue_money'];
            $value['be_time']=$return_arr['time'];
            $value['loan_time']=$loan_time;
            $sum['shouxufei']+=MoneyModel::poundage($value['loan_amount'],$value['interest'],$value['cuts_interest']);
            $sum['sum_money']+=$value['loan_amount'];
            $sum['ren']+=1;
        }
        $this->assign('psun',$psun);
        $this->assign('pmoney',$pmoney);
        $this->assign('sum',$sum);
        $this->assign('loan_data',$loan_data);
        $this->display('Already/index');
    }
/*查看提额页面*/
    public function look(){
        $arr=file_get_contents('end_upgrade.txt');
        $arr=explode('*',rtrim(trim($arr),"*"));
        if($_GET['time']){
            $get=I('get.');
            $inittime=$get['time'];
        }else{
            $inittime=time();
        }

        $s_time=date("Y-m-d",$inittime);

        $time=strtotime($s_time);

        for ($i=4; $i >0 ; $i--) { 
            $etime=$time-86400*$i;
            $date_etime=date("m月d",$etime);
            $time_data[$date_etime]=$etime;
        }

        for ($i=0; $i <4 ; $i++) { 
            $stime=$time+86400*$i;
            $date_stime=date("m月d",$stime);
            $time_data[$date_stime]=$stime;
        }

        foreach ($arr as $key => $value) {
            $data=json_decode($value,1);
            $money+=$data['提升额度'];
            if($data['提升时间']==$s_time){
                $lines_data[]=$data;
                $day_i+=1;
                $day_money+=$data['提升额度'];
            }
        }

        $this->time_data=$time_data;
        $this->day_i=$day_i;
        $this->day_money=$day_money;
        $this->money=$money;
        $this->lines_data=$lines_data;

        $this->display();
    }

/*到期时间查找*/
    public function expire(){
        $post=I('post.');
        $start_time=strtotime($post['start']);
        $end = strtotime($post['end']);
        $end_time=$end+86400;
        $loan=M('loan');
        $where['is_pay']=array('gt',0);
        $loan_data=$loan->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->select();

        foreach ($loan_data as $key => &$value) {
            if($value['loan_time']==1){
                $loan_time=7;
            }else if($value['loan_time']==2){
                $loan_time=14;
            }
            $return_arr=RepayModel::overdue_show($value['is_pay'],$loan_time,$value['renewal_days'],$value['loan_amount']);
            if($return_arr['time']>$start_time&&$return_arr['time']<$end_time){
                $i+=1;
                $value['num']=$i;
                $value['overdue_money']=$return_arr['overdue_money'];
                $value['be_time']=$return_arr['time'];
                $value['loan_time']=$loan_time;
                $value['type']=1;
                $sum['shouxufei']+=MoneyModel::poundage($value['loan_amount'],$value['interest'],$value['cuts_interest']);
                $sum['sum_money']+=$value['loan_amount'];
                $sum['ren']+=1;
            }
        }
        
        $this->start_time=$start_time;
        $this->end_time=$end_time;

        $this->assign('sum',$sum);
        $this->assign('loan_data',$loan_data);
        $this->display('Already/expire');
    }

    public function excel_bepay(){
        $get=I('get.');
        $loan=M('loan');
        $where['is_pay']=array('gt',0);
        $start_time=$get['start_time'];
        $end_time =$get['end_time'];

        $loan_data=$loan->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->select();
         foreach ($loan_data as $key => $value) {
            if($value['loan_time']==1){
                $loan_time=7;
            }else if($value['loan_time']==2){
                $loan_time=14;
            }
            $return_arr=RepayModel::overdue_show($value['is_pay'],$loan_time,$value['renewal_days'],$value['loan_amount']);
            $shouxufei=MoneyModel::poundage($value['loan_amount'],$value['interest'],$value['cuts_interest']);
            if($return_arr['time']>$start_time&&$return_arr['time']<$end_time){
                $money=$shouxufei+$return_arr['overdue_money']+$value['loan_amount'];
               $excel_data[$key]=$value;

               $excel_data[$key]['be_time']=$return_arr['time'];
               $excel_data[$key]['shouxufei']=$shouxufei;
               $excel_data[$key]['money']=$money;
            }
        }

        $this->goods_export($excel_data);
    }

/*支付宝还款输入页面*/
    public function zfb_pay(){
        $get=I('get.');
        $this->num=$get['id'];
        $this->display();
    }
/*还款处理页面*/
    public function repayment(){
            $loan_model = M('Loan');
            $post=I('post.');
            $loan_id = $post['id'];
            if($post['id']=='' && $post['order']==''){
                die("<script>alert('未知错误！');history.back();</script>");
            }
            $order_lenght=strlen($post['order']);
            if($order_lenght!=32){
                die("<script>alert('支付宝订单号错误！');history.back();</script>");
            }

            $info = M()->table('free_user user,free_loan loan')->where("user.user_id=loan.user_id and loan.loan_id=$loan_id")->find();

            $record_model = M('Record');
            $data['user_id'] = $info['user_id'];
            $data['user_name'] = $info['user_name'];
            $data['loan_order'] = $info['loan_order'];
            $data['linkman_tel'] = $info['linkman_tel'];
            $data['identity'] = $info['identity'];
            $data['bank_card'] = $info['bank_card'];
            $data['loan_time'] = $info['loan_time'];
            $data['request_time'] = $info['loan_request'];
            $data['pay_time'] = $info['is_pay'];
            $data['repayment_time'] = time(); //还款时间
            $data['pay_money'] = $info['loan_amount'];
            $data['llorder']=$post['order'];
            $data['xutime']=$info['renewal_days'];
            $shouxufei=MoneyModel::poundage($info['loan_amount'],$info['interest'],$info['cuts_interest']);
            $data['repayment_money'] = $info['loan_amount']+$shouxufei;
            $data['interest']=$info['interest'];
            $data['aname'] =  session('aname');
            $data['is_overdue'] = $info['is_overdue'];
            $data['cuts_interest']=$info['cuts_interest'];
            $record_add = $record_model->add($data);
            if ($record_add){
                $user_model = M('User');
                $u['audit'] = 0;
                $u['lev'] = 1;
                $w['user_id'] = $info['user_id'];
                $user_model->where($w)->save($u);

                $where['loan_id'] = $loan_id;
                $arr['loan_amount'] =null;
                $arr['is_pay'] = '';
                $arr['is_repayment'] = '';
                $arr['loan_time'] = '';
                $arr['loan_request'] = 0;
                $arr['is_loan'] = 0;
                $arr['is_overdue'] = 0;
                $arr['renewal_num'] = 0;
                $arr['maudit'] = 0;
                $arr['renewal_days'] = 0;
                $arr['renewal_day'] = 0;
                $arr['renewal_time'] = 0;
                $arr['ll_code'] = '';
                $arr['auth_time'] = '';
                $arr['ll_status'] = '';
                $arr['field'] = 0;
                $arr['automatic']=null;
                $arr['loan_num'] = $info['loan_num']+1;
                $arr['loan_order'] = $this->num();
                $task_map['no_order'] = $info['loan_order'];
                $task_save['complete'] = date('Y-m-d H:i',time());
                $bool = $loan_model->where($where)->save($arr);
                $this->task_model->where($task_map)->save($task_save);
                if ($bool){
                    $bool = WeixinModel::sex($info['identity']);
                    $sex = $bool?"先生":"女士";

                    $this->end_upgrade($info['user_name'],$info['card_type'],$info['lines'],$info['loan_lines'],$info['u_name'],$sex,$info['loan_amount'],$info['open_id'],$info['loan_num']);
                    //$this->upgrade($info['user_name'],$info['card_type'],$info['lines'],$info['loan_lines'],$info['u_name'],$sex,$info['loan_amount'],$info['open_id']);
                    //$this->new_upgrade($info['user_name'],$info['card_type'],$info['lines'],$info['loan_lines'],$info['u_name'],$sex,$info['loan_amount'],$info['open_id']);
                


                $this->redirect('Home/Already/index');
                }else{
                    echo '<script>alert("网络缘故审核提交失败,请稍后再试！！！");history.back()</script>';
                }
            }else{
                echo '<script>alert("数据迁移失败,请稍后再试！！！");history.back()</script>';
            }
    }


public function yuwenhan(){
        $user=M('user');
        $where['free_user.user_name']=15738849971;
        $user_data=$user->where($where)->join('free_loan ON free_loan.user_id=free_user.user_id')->find();
        $bool = WeixinModel::sex($user_data['identity']);
        $sex = $bool?"先生":"女士";
        $a=$this->end_upgrade($user_data['user_name'],$user_data['card_type'],$user_data['lines'],$user_data['loan_lines'],$user_data['u_name'],$sex,$user_data['loan_amount'],$user_data['open_id'],$user_data['loan_num']);
        var_dump($a);
    }
    public function end_upgrade($user_name,$card_type,$user_lines,$loan_lines,$u_name,$sex,$loan_amount,$open_id,$loan_num){
        $access_token = WeixinModel::getToken();
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
                        WeixinModel::sendSms($data,$user_name);
                        $a=WeixinModel::sendrepay($open_id,$contnet,'蜻蜓白卡',$loan_amount,date('Y-m-d H:i',time()),'银行卡还款',$access_token);
                        return ;
                    }
            
                }
            }else{
                $bulid_money=$this->bulid($user_name);
                if($bulid_money){
                    $data ="尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。恭喜您获得一张提额卷，赶快打开微信公众号\"蜻蜓白卡\"查看吧！";
                    $contnet = "尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。恭喜您获得一张提额劵，赶快打开微信公众号“蜻蜓白卡”查看吧！";
                    WeixinModel::sendSms($data,$user_name);
                    $a=WeixinModel::sendrepay($open_id,$contnet,'蜻蜓白卡',$loan_amount,date('Y-m-d H:i',time()),'银行卡还款',$access_token);
                    return ;
                }
                
            }

        }
          

        $data ="尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款！";
        $contnet = "尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款！";
        WeixinModel::sendSms($data,$user_name);
        $a=WeixinModel::sendrepay($open_id,$contnet,'蜻蜓白卡',$loan_amount,date('Y-m-d H:i',time()),'银行卡还款',$access_token);
    }
    public function new_upgrade($user_name,$card_type,$user_lines,$loan_lines,$u_name,$sex,$loan_amount,$open_id){
        $access_token = WeixinModel::getToken();
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
                 WeixinModel::sendSms($data,$user_name);
                //WeixinModel::bomber($user_name,$data);
                $a=WeixinModel::sendrepay($open_id,$contnet,'蜻蜓白卡',$loan_amount,date('Y-m-d H:i',time()),'银行卡还款',$access_token);
            }else{
    //未逾期  给永久卷
                
                if(($lines<2000 && $lines>1500) && $card_type==0){
                    $save['card_type']=2;

                    $data ="尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。恭喜您成为蜻蜓白卡银卡会员，您的借款额度已提升，请您继续保持良好的信用！";
                    WeixinModel::sendSms($data,$user_name);
                    //WeixinModel::bomber($user_name,$data);
                    $contnet = "尊敬的".$u_name."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。您的会员级别有所变更，具体信息如下：";
                    $text="蜻蜓白卡为感谢您的支持，您的借款额度已提升，请您继续保持良好的信用。";
                    $card="银卡会员";
                    $a=WeixinModel::interest($open_id,$contnet,$text,$access_token,$card);
                }else if($lines==2000 && $card_type!=1){
                    $save['card_type']=1;
                    $save['interest_type']=1;
                    $data ="尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。恭喜您成为蜻蜓白卡金卡会员，额度提升同时降低了您借款所需的服务费用，请您继续保持良好的信用！";
                    WeixinModel::sendSms($data,$user_name);
                    //WeixinModel::bomber($user_name,$data);
                    $contnet = "尊敬的".$u_name."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。您的会员级别有所变更，具体信息如下：";
                    $text="恭喜您成为蜻蜓白卡金卡会员，额度提升同时降低了您借款所需的服务费用，请您继续保持良好的信用。";
                    $card="金卡会员";
                    $a=WeixinModel::interest($open_id,$contnet,$text,$access_token,$card);
                }else{
                    $data ="尊敬的 ".$u_name.$sex."，您于".date("m",time())."月".date("d",time())."日成功结清".$loan_amount."元借款，同时获得200元额度提升！";
                    $contnet = "尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款！恭喜您获得额度提升，赶快打开微信公众号“蜻蜓白卡“查看吧！";
                    WeixinModel::sendSms($data,$user_name);
                    //WeixinModel::bomber($user_name,$data);
                    $a=WeixinModel::sendrepay($open_id,$contnet,'蜻蜓白卡',$loan_amount,date('Y-m-d H:i',time()),'银行卡还款',$access_token);
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
             WeixinModel::sendSms($data,$user_name);
            //WeixinModel::bomber($user_name,$data);
            $a=WeixinModel::sendrepay($open_id,$contnet,'蜻蜓白卡',$loan_amount,date('Y-m-d H:i',time()),'银行卡还款',$access_token);
        }

        $this->redirect('Home/Already/index');

    }


/*提升用户等级*/
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

        if($time<600 && $time>=500){
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
                    WeixinModel::sendSms($data,$user_name);
                    //WeixinModel::bomber($user_name,$data);
                    $access_token = WeixinModel::getToken();
                    $contnet = "尊敬的".$u_name."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。您的会员级别有所变更，具体信息如下：";
                    $text="蜻蜓白卡为感谢您的支持，您的借款额度已提升，请您继续保持良好的信用。";
                    $card="银卡会员";
                    $a=WeixinModel::interest($open_id,$contnet,$text,$access_token,$card);
                    $this->redirect('Home/Already/index');
                }
            }
        }else if($time<2000 && $time>=1000){
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
                    WeixinModel::sendSms($data,$user_name);
                    //WeixinModel::bomber($user_name,$data);
                    $access_token = WeixinModel::getToken();
                    $contnet = "尊敬的".$u_name."，您的会员级别有所变更，具体信息如下：";
                    $text="您已成为蜻蜓白卡金卡会员。额度提升同时降低了您借款所需的服务费用，请您继续保持良好的信用。";
                    $card="金卡会员";
                    $a=WeixinModel::interest($open_id,$contnet,$text,$access_token,$card);
                    $this->redirect('Home/Already/index');
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
            $contnet = "尊敬的".$u_name.$sex."，您于".date("m月d日",time())."成功结清".$loan_amount."元借款。恭喜您获得一张提额劵，赶快打开微信公众号“蜻蜓白卡”查看吧！";
        }

            WeixinModel::sendSms($data,$user_name);
            //WeixinModel::bomber($user_name,$data);
            $access_token = WeixinModel::getToken();
            
            $a=WeixinModel::sendrepay($open_id,$contnet,'蜻蜓白卡',$loan_amount,date('Y-m-d H:i',time()),'银行卡还款',$access_token);
            $this->redirect('Home/Already/index');
        
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


/*续期 */
    public function renewal(){
        if($_POST){
            $post=I('post.');
            $get=I('get.');

            if($get['id']==''){
                die("<script>alert('未知错误！');history.back();</script>");
            }
            if($post['renewal_money']==''){
                die("<script>alert('续期费用不能为空');history.back();</script>");
            }
            if($post['renewal_order']==''){
                die("<script>alert('订单号不能为空');history.back();</script>");
            }
            
            // $order_lenght=strlen($post['order']);
            // if($order_lenght!=32){
            //     die("<script>alert('支付宝订单号错误！');history.back();</script>");
            // }

            $renewal_time=strtotime(preg_replace('# #','',$post['renewal_time']));
            if($renewal_time==''){
                die("<script>alert('续期时间格式错误');history.back();</script>");
            }
            if($post['renewal_time']==''){
                die("<script>alert('续期时间不能为空');history.back();</script>");
            }
            if($post['renewal_day']==''){
                die("<script>alert('续期天数不能为空');history.back();</script>");
            }
            $loan_model=M('loan');
            $loan_where['user_name'] = $get['id'];
            $loan_data=$loan_model->where($loan_where)->find();
            $loan_save['renewal_days'] = $post['renewal_day'];
            $loan_save['renewal_time'] = strtotime($post['renewal_time']);
            $loan_save['renewal_num']=$loan_data['renewal_num']+1;
            $loan_res=$loan_model->where($loan_where)->save($loan_save);
            if($loan_res){
                $info = M()->table('free_user user,free_loan loan')
                ->where("user.user_id=loan.user_id and user.user_name=".$get['id']."")
                ->find();
                $continued_model = M('Continued');
                $data['user_id'] = $info['user_id'];
                $data['user_name'] = $info['user_name'];
                $data['renewal_order'] = $post['renewal_order'];
                $data['linkman_tel'] = $info['linkman_tel'];
                $data['identity'] = $info['identity'];
                $data['bank_card'] = $info['bank_card'];
                $data['loan_time'] = $info['loan_time'];
                $data['request_time'] = $info['loan_request'];
                $data['pay_time'] = $info['is_pay'];
                $data['repayment_time'] = time(); //续期还款时间
                $data['is_kq'] = 0; //还款标识    
                $data['pay_money']=$post['renewal_money'];
                $data['repayment_money'] = $info['loan_amount'];
                $continued_res=$continued_model->add($data);
                if($continued_res){
                    $this->redirect('Already/index');   
                }else{
                    die("<script>alert('续期失败了');history.back();</script>");
                }
            }
            else{
                die("<script>alert('失败了');history.back();</script>");
            }   
        }
        $this->display();

    }
/*生成订单号*/
    public function num() {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    public function export(){
        $loan=M('loan');
        $get=I('get.');
        $where['is_pay']=array('gt',0);
        $loan_data=$loan->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->select();
        
        foreach ($loan_data as $key => &$value) {
           if($value['loan_time']==1){
                $loan_time=7;
            }else if($value['loan_time']==2){
                $loan_time=14;
            }
            $return_arr=RepayModel::overdue_show($value['is_pay'],$loan_time,$value['renewal_days'],$value['loan_amount']);
            $value['shouxufei']=MoneyModel::poundage($value['loan_amount'],$value['interest'],$value['cuts_interest']);
            $value['money']=$value['shouxufei']+$value['loan_amount'];
            $value['be_time']=$return_arr['time'];
            $value['loan_time']=$loan_time;
        }
        $this->goods_export($loan_data);
    }
    public function goods_export($goods_list){
        $goods_list = array_merge($goods_list);
        $data = array();
        foreach ($goods_list as $k=>$goods_info){
            $data[$k][u_name] = " $goods_info[u_name]";
            $data[$k][user_name] = " $goods_info[user_name]";
            $data[$k][loan_order] = " $goods_info[loan_order]";
            $data[$k][is_pay] = "$goods_info[is_pay]";
            $data[$k][loan_time] = " $goods_info[loan_time]";
            $data[$k][loan_amount] = " $goods_info[loan_amount]";
            $data[$k][shouxufei] = " $goods_info[shouxufei]";
            $data[$k][end_money] = " $goods_info[money]";
            $data[$k][renewal_days] = " $goods_info[renewal_days]";
            $data[$k][be_time] = " $goods_info[be_time]";
        }
        foreach ($data as $field=>$v){
            if($field == 'u_name'){
                $headArr[]='姓名';
            }
            if($field == 'user_name'){
                $headArr[]='手机号';
            }
            if($field == 'loan_order'){
                $headArr[]='订单号';
            }
            if($field == 'is_pay'){
                $headArr[]='打款时间';
            }
            if($field == 'loan_time'){
                $headArr[]='借款期限';
            }
            if($field == 'loan_amount'){
                $headArr[]='打款金额';
            }
            if($field == 'shouxufei'){
                $headArr[]='综合费用';
            }
            if($field == 'end_money'){
                $headArr[]='应还金额';
            }
            if($field == 'renewal_days'){
                $headArr[]='续期';
            }
            if($field == 'overday_time'){
                $headArr[]='应还款时间';
            }
//手机号(用户名)  持卡人姓名 银行卡绑定手机号  身份证号  银行卡号  借款金额  打款时间  借款期限  到期时间  逾期天数  本息  逾期费用
        }

        $filename="用户借款表".date('Y_m_d',time());
        $this->getExcel($filename,$headArr,$data);
    }


Static public function getExcel($fileName,$headArr,$data){
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");
        $date = date("Y_m_d",time());
        $fileName .= "_{$date}.xls";
        //创建PHPExcel对象，注意，不能少了\
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
                if($keyName=='is_pay' || $keyName=='be_time'){
                    $objActSheet->setCellValue($j.$column, \PHPExcel_Shared_Date::PHPToExcel($value));
                    $objActSheet->getStyle($j.$column)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                }else if($keyName=='shouxufei' || $keyName=='loan_amount' ||$keyName=='end_money' ||$keyName=='renewal_days' ||$keyName=='loan_time'){
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit($j.$column,$value,\PHPExcel_Cell_DataType::TYPE_NUMERIC);
                }else{
                    $objActSheet->setCellValue($j.$column, $value);
                }
                $span++;
            }
            $column++;
        }
        $fileName = iconv("utf-8", "gb2312", $fileName);
        //重命名表
        //$objPHPExcel->getActiveSheet()->setTitle('test');
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;
    }


/*生成记录*/
    public function statistical(){
        $post=I('post.');
        $start_time=strtotime($post['start']);
        $end = strtotime($post['end']);
        $end_time=$end+86400;

        $loan_model=M('loan');
        $record_model=M('record');
        $loan_where['is_pay']=array('NEQ',0);
        $loan_data=$loan_model->where($loan_where)->select();
        $record_data=$record_model->select();

        $continued=M('continued');
        $continued_where['repayment_time']=array(array('GT',$start_time),array('ELT',$end_time));
        $continued_data=$continued->field('pay_money')->where($continued_where)->select();
        /*续期  */

        foreach ($continued_data as $key => $value) {
            $loan_arr['zfb_renwal_money']+=$value['pay_money'];
            $loan_arr['zfb_renwal_ren']+=1;
        }
/*loan 表*/
        foreach ($loan_data as $key => $value) {
            if($value['loan_time']==1){
                   $loan_time=7;
            }else if($value['loan_time']==2){
                 $loan_time=14;
            }
            $shouxufei=MoneyModel::poundage($value['loan_amount'],$value['interest'],$value['cuts_interest']);
    /*未还 放款 统计*/
            if($value['is_pay']>=$start_time && $value['is_pay']<$end_time){
                if($value['loan_time']==1){
                    $loan_arr['lend_7_money']+=$value['loan_amount'];
                    $loan_arr['lend_7_cost']+=$shouxufei;
                    $loan_arr['lend_7_ren']+=1;
                }else if($value['loan_time']==2){
                    $loan_arr['lend_14_money']+=$value['loan_amount'];
                    $loan_arr['lend_14_cost']+=$shouxufei;
                    $loan_arr['lend_14_ren']+=1;
                }
            }
            /*未还放款统计*/
            $return_arr=RepayModel::overdue_show($value['is_pay'],$loan_time,$value['renewal_days'],$value['loan_amount']);
            /*到期应收未还*/
            if($return_arr['time']>=$start_time && $return_arr['time']<$end_time){
                if($value['loan_time']==1){
                    $loan_arr['not_7_money']+=$value['loan_amount'];
                    $loan_arr['not_7_cost']+=$shouxufei;
                    $loan_arr['not_7_ren']+=1;
                }else if($value['loan_time']==2){
                    $loan_arr['not_14_money']+=$value['loan_amount'];
                    $loan_arr['not_14_cost']+=$shouxufei;
                    $loan_arr['not_14_ren']+=1;
                }
                $loan_arr['not_overdue']+=$return_arr['overdue_money'];
            }
            /*到期应收未还*/

        }

/*记录  表*/
        foreach ($record_data as $key => $value) {
            if($value['loan_time']==1){
                   $loan_time=7;
            }else if($value['loan_time']==2){
                 $loan_time=14;
            }

            $be_overdue=RepayModel::be_overdue($value['pay_time'],$loan_time,$value['xutime'],$value['pay_money'],$value['repayment_time']);
            $interest=($value['repayment_money']-$value['pay_money'])/$value['pay_money'];
            $shouxufei=MoneyModel::poundage($value['pay_money'],$value['interest'],$value['cuts_interest']);
            

            /*已还放款统计*/
            if($value['pay_time']>=$start_time && $value['pay_time']<$end_time){
                if($value['loan_time']==1){
                    $loan_arr['lend_7_money']+=$value['pay_money'];
                    $loan_arr['lend_7_cost']+=$shouxufei;
                    $loan_arr['lend_7_ren']+=1;
                }else if($value['loan_time']==2){
                    $loan_arr['lend_14_money']+=$value['pay_money'];
                    $loan_arr['lend_14_cost']+=$shouxufei;
                    $loan_arr['lend_14_ren']+=1;
                }
            }
            /*已还放款统计*/

            /*到期应收已还*/
            if($be_overdue['time']>=$start_time && $be_overdue['time']<$end_time){
                $money+=$value['pay_money'];
                $money_shou+=$shouxufei;

                if($value['loan_time']==1){
                    $loan_arr['rece_7_money']+=$value['pay_money'];
                    $loan_arr['rece_7_cost']+=$shouxufei;
                    $loan_arr['rece_7_ren']+=1;
                }else if($value['loan_time']==2){
                    $loan_arr['rece_14_money']+=$value['pay_money'];
                    $loan_arr['rece_14_cost']+=$shouxufei;
                    $loan_arr['rece_14_ren']+=1;
                }
            }
            /*到期应收已还*/
            /* 实收款  */
            if($value['repayment_time']>=$start_time && $value['repayment_time']<$end_time){
                if($value['is_kq']==1){//快钱
                    /*$kq_money+=$value['repayment_money'];
                    $kq_ren+=1;*/
                }else if($value['is_kq']==0){//支付宝
                    if($be_overdue['time']>$end_time){//提前还款
                        if($value['loan_time']==1){
                            $loan_arr['ahead_7_money']+=$value['repayment_money'];
                            $loan_arr['ahead_7_ren']+=1;
                        }else if($value['loan_time']==2){
                            $loan_arr['ahead_14_money']+=$value['repayment_money'];
                            $loan_arr['ahead_14_ren']+=1;
                        }
                    }else{//正常还款
                        if($value['loan_time']==1){
                            $loan_arr['normal_7_money']+=$value['repayment_money'];
                            $loan_arr['normal_7_ren']+=1;
                        }else if($value['loan_time']==2){
                            $loan_arr['normal_14_money']+=$value['repayment_money'];
                            $loan_arr['normal_14_ren']+=1;
                        }
                    }
                    if($be_overdue['overdue_money']>0){
                        $overdue_money+=$be_overdue['overdue_money'];
                        $loan_arr['zfb_overdue_money']+=$be_overdue['overdue_money'];
                        $loan_arr['zfb_overdue_ren']+=1;
                        if($value['payment']>0){
                            $loan_arr['payment_ren']+=1;
                            $loan_arr['payment']+=$value['payment'];  
                        }
                    }
                }else if($value['is_kq']==2){//连连
                    if($be_overdue['time']>$end_time){//提前还款
                        if($value['loan_time']==1){
                            $loan_arr['ll_ahead_7_money']+=$value['repayment_money'];
                            $loan_arr['ll_ahead_7_ren']+=1;
                        }else if($value['loan_time']==2){
                            $loan_arr['ll_ahead_14_money']+=$value['repayment_money'];
                            $loan_arr['ll_ahead_14_ren']+=1;
                        }
                    }else{//正常还款
                        if($value['loan_time']==1){
                            $loan_arr['ll_normal_7_money']+=$value['pay_money']+$shouxufei;
                            $loan_arr['ll_normal_7_ren']+=1;
                        }else if($value['loan_time']==2){
                            $loan_arr['ll_normal_14_money']+=$value['pay_money']+$shouxufei;
                            $loan_arr['ll_normal_14_ren']+=1;
                        }
                    }
                    if($be_overdue['overdue_money']>0){//连连逾期
                        $overdue_money+=$be_overdue['overdue_money'];
                        $loan_arr['ll_overdue_money']+=$be_overdue['overdue_money'];
                        $loan_arr['ll_overdue_ren']+=1;
                    }
                }
            }
        }
        /*到期实收款人*/
        $loan_arr['normal_sum_ren']=$loan_arr['normal_7_ren']+$loan_arr['normal_14_ren']+$loan_arr['ll_normal_14_ren']+$loan_arr['ll_normal_7_ren'];
        /*到期实收款钱*/    /*续期错误*/
        $loan_arr['normal_sum_money']=$loan_arr['normal_7_money']+$loan_arr['normal_14_money']+$loan_arr['zfb_renwal_money']+$loan_arr['zfb_overdue_money']+$loan_arr['ll_overdue_money']+$loan_arr['ll_normal_14_money']+$loan_arr['ll_normal_7_money'];
        /*提前收款人*/
        $loan_arr['ahead_sum_ren']=$loan_arr['ahead_7_ren']+$loan_arr['ahead_14_ren']+$loan_arr['ll_ahead_7_ren']+$loan_arr['ll_ahead_14_ren'];
        /*提前收款钱*/
        $loan_arr['ahead_sum_money']=$loan_arr['ahead_7_money']+$loan_arr['ahead_14_money']+$loan_arr['ll_ahead_14_money']+$loan_arr['ll_ahead_7_money'];
      
        $this->arr=$loan_arr;
        $this->display();
    }
    public function otain(){
        $map['create'] = date('Y-m-d',time());
        $info = $this->task_model->where($map)->select();
        if(!$info){
          echo '<script>alert("未找到今日逾期名单");history.back()</script>';  
        }
        foreach ($info as $k => $v) {
            $where['user_name'] = $v['user_name'];
            $res = $this->loan_model->where($where)->find();
            if($res['loan_time']==1){
                $loan_time=7;
            }elseif($res['loan_time']==2){
                $loan_time=14;
            }
            $return_arr=RepayModel::overdue_show($res['is_pay'],$loan_time,$res['renewal_days'],$res['loan_amount']);
            $shouxufei=MoneyModel::poundage($res['loan_amount'],$res['interest'],$res['cuts_interest']);
            $results[$k]['u_name'] = $v['u_name'];
            $results[$k]['identity'] = $res['identity'];
            $results[$k]['loan_amount'] = $v['loan_amount'];
            $results[$k]['bentime'] = $return_arr['time'];
            $results[$k]['money'] = $v['money'];
            $results[$k]['group'] = $v['group'];
        }
        $this->assign('info',$results);
        $this->display();
    }
}