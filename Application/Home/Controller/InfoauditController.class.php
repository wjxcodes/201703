<?php 
namespace Home\Controller;
use Think\Controller;
use Home\Model\WandaModel as WandaModel;
use Home\Model\ApixModel as ApixModel;
use Home\Model\WeixinModel as WeixinModel;
header("content-type:text/html;charset=utf8");
class InfoauditController extends BaseController{
    protected $user_model;
    protected $loan_model;
    public function  __construct(){
        parent::__construct();//执行父类的构造函数，否则会被覆盖的。
        $this->user_model = M('user');
        $this->loan_model = M('Loan');
        $this->auser_model = M('Auser');
        $this->feed_model = M('Feed');
    }
    
	public function index(){
        $user_data=D('user')->au_request();
        
        $this->assign('user_data',$user_data);
        $this->display();
	}
    public function checkNotify() {
        $where['au_request'] = 1;
        $where['audit'] = 0;
        $where['lev'] = 0;
        $res = $this->user_model->where($where)
        ->field("user_id,user_name,create_time,linkman_name,linkman_tel,bank_name,bank_tel,bank_card,identity")
        ->select();
        if ($res) {
            foreach ($res as $key => $v) {
                $map['user_id'] = $v['user_id'];
                $data['lev'] = 1;
                $this->user_model->where($map)->save($data);
            }
                echo json_encode($res);
        }else{
            echo "error";
        }
    }


    //暂不通过  标记  
    public function tag(){
        if($_POST){
            $post=I('post.');
            $user_id=$post['id'];
            $user_res=D('user')->tag($user_id);
            if($user_res){
                $ajax['state']='ok';
            }else{
                $ajax='state';
            }
            $ajax['id']=$post['id'];
            $this->ajaxReturn($ajax);
        }
    }


    public function change(){
        $id = intval(I('get.id'));
        $status = I('get.status');
        $dataStatus = I('post.');
        $where['user_id'] = $id;
        $user_info = $this->user_model->find($id);
        $loan_info = $this->loan_model->where($where)->find();
        // 审核未通过
        switch ($status){
            case '1':
                $data['audit'] = 0;
                $data['au_request'] = 0;
                $data['alter_info'] = 0;
                $result = $this->user_model->where($where)->save($data);
                /*不通过记录开始*/
                
                
                /*不通过记录结束*/
                if ($result){
                    $arr['maudit'] = 0;
                    $arr['loan_amount'] = 0;
                    $arr['loan_request'] = 0;
                    $arr['is_loan'] = 0;
                    $arr['loan_time'] = 0;
                    $where['user_id'] = $id;
                    $ress = $this->loan_model->where($where)->save($arr);
                    if ($ress) {
                        $this->sendSms($user_info['open_id'],$user_info['u_name'],$dataStatus,$loan_info['loan_request'],$loan_info['loan_amount'],$user_info['user_name'],$user_info['identity']);
                    }


                    $feed=M('feed');
                $feed_save['user_id']=$user_info['user_id'];
                $feed_save['u_name']=$user_info['u_name'];
                $feed_save['identity']=$user_info['identity'];
                $feed_save['loan_order']=$loan_info['loan_order'];
                $feed_save['loan_amount']=$loan_info['loan_amount'];
                $feed_save['reject_state']=1;
                $feed_save['create_time']=time();
                $feed->add($feed_save);
                    $this->redirect('Home/Infoaudit/index');
                }else{
                    echo '<script>alert("网络缘故审核提交失败,请稍后再试！！！");history.back()</script>';exit;
                }
                break;
            // 审核通过
            case '2':
                $data['audit'] = 2;
                $data['au_request'] = 0;
                $result = $this->user_model->where($where)->save($data);
                if ($result) {
                    $this->redirect('Home/Infoaudit/index');
                }else{
                    echo '<script>alert("网络缘故审核提交失败,请稍后再试！！！");history.back()</script>';exit;
                }
                break;
            //拉黑
            case '3':
                $this->user_model->audit  = 0;
                $this->user_model->au_request = 0;
                $this->user_model->past = $user_info['past']+3;
                $this->user_model->pastime = time();
                $result = $this->user_model->where($where)->save();
                /*不通过记录开始*/
                $feed=M('feed');
                $feed_save['user_id']=$user_info['user_id'];
                $feed_save['u_name']=$user_info['u_name'];
                $feed_save['identity']=$user_info['identity'];
                $feed_save['loan_order']=$loan_info['loan_order'];
                $feed_save['loan_amount']=$loan_info['loan_amount'];
                $feed_save['reject_state']=1;
                $feed_save['create_time']=time();
                $feed->add($feed_save);
                /*不通过记录结束*/
                if ($result){
                    $arr['maudit'] = 0;
                    $arr['loan_amount'] = 0;
                    $arr['loan_request'] = 0;
                    $arr['is_loan'] = 0;
                    $arr['loan_time'] = 0;
                    $where['user_id'] = $id;
                    $ress = $this->loan_model->where($where)->save($arr);
                    if(!$ress)
                        echo '<script>alert("网络缘故审核提交失败,请稍后再试！！！");history.back()</script>';
                    $this->redirect('Home/Infoaudit/index');
                }else{
                    echo '<script>alert("网络缘故审核提交失败,请稍后再试！！！");history.back()</script>';exit;
                }
            default:
                $this->redirect('Home/Infoaudit/index');
                break;
        }
    }

     public function credit(){
        $tel_record_model = M('Tel_record');
        $get=I('get.');
        $where_name['user_name'] = $get['id'];  
        $open_id= $get['open_id'];
        if($open_id==''){

        }else{
            $access_token = WeixinModel::getToken($appid,$appsecret);
            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$open_id&lang=zh_CN";
            $data = WeixinModel::request_get($url);
            $token = json_decode(stripslashes($data));
            $array = json_decode(json_encode($token), true);
            $map['open_id'] = $open_id;
            $arr = $this->user_model->where($where_name)->find();
            $array['user_id'] = $arr['user_id'];
            $array['user_name'] = $arr['user_name'];
            $array['u_name'] = $arr['u_name'];
            $this->array=$array;
        }

        $res = $tel_record_model->where($where_name)->find();
        $request_id=strlen($res['request_id']);
        $this->assign('id',$map['user_name']);
        if($request_id==36){
            /*APIX 通讯记录详情开始*/
            $apix=M('apix');

                $apix_data=$apix->where($where_name)->find();
                if($apix_data){
                    $apix_data['basicInfo']=json_decode($apix_data['basic'],1);
                    $apix_data['phoneInfo']=json_decode($apix_data['phone'],1);
                    $apix_data['deceitRisk']=json_decode($apix_data['decei'],1);
                    $apix_data['consumeInfo']=json_decode($apix_data['consume'],1);
                    $apix_data['callRecordsInfo']=json_decode($apix_data['callrecords'],1);
                    $apix_data['contactAreaInfo']=json_decode($apix_data['contactarea'],1);
                    $apix_data['specialCallInfo']=json_decode($apix_data['specialcall'],1);
                    $apix_data['phoneOffInfos']=json_decode($apix_data['phoneoff'],1);

                    $this->assign("hint","数据库读出数据");
                    $this->assign("apix_data",$apix_data);
                }else{
                    
                }
                foreach ($apix_data['consumeInfo'] as $key => $value) {
                        $money+=$value['payMoney'];
                }
                $this->assign('money',$money);
                    
                $this->display('apix/index');
            /*APIX 通讯记录详情结束*/
        }else if($request_id==40){
            /* 万达通讯详情开始*/
           $wdinfo_model = M('wdinfo');
            $result = $wdinfo_model->where($where_name)->find();
            $accounts = json_decode($result['accounts'],true);
            $casic = json_decode($result['casic'],true);
            $result = json_decode($result['tel_info'],true);
            $result = $this->order($result,$lev='call_len');
            $this->assign('result',$result);
            $this->assign('accounts',$accounts[0]['behavior']);
            $this->assign('casic',$casic);
            $this->display();
            /* 万达通讯详情结束*/
        }else{
            $hint="出现未知错误";
            $this->assign("hint",$hint);
            $this->display();
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
    public function photo(){
        $get = I('get.');
        $where['user_name'] = $get['id'];
        $user_data = $this->user_model->where($where)->find();
        $this->assign('user_data',$user_data);
        $this->display();
    }

    public function sendSms($open_id,$u_name,$data,$loan_request,$loan_amount,$mobile,$identity){
        $access_token = WeixinModel::getToken();
        $bool = WeixinModel::sex($identity);
        $sex = $bool?"先生":"女士";
        $idnumber = $data['idnumber'];
        $photo = $data['photo'];
        $sesame = $data['sesame'];
        $bankcar = $data['bankcar'];
        if ($idnumber==2 && $photo==3 && $sesame==4 && $bankcar==5) {
             $sms = "尊敬的".$u_name.$sex."，很抱歉因您填写的证件号与银行卡不匹配、上传的照片不够清晰且取消了芝麻授权，未能通过我们的风控审核！";
                    $mobile = $mobile;
                    // WeixinModel::sendSms($sms,$mobile);
                    $contnet="尊敬的".$u_name.$sex."，很抱歉因您填写的证件号与银行卡不匹配、上传的照片不够清晰且取消了芝麻授权，未能通过我们的风控审核！";
                    WeixinModel::sendWeixin($open_id,$contnet,$loan_amount,date('Y-m-d H:i',$loan_request),$access_token);
        }elseif($idnumber==2 && $photo==3 && $bankcar==5){
            $sms = "尊敬的".$u_name.$sex."，很抱歉因您填写的证件号与银行卡不匹配且上传的照片不够清晰，未能通过我们的风控审核！";
                    $mobile = $mobile;
                    // WeixinModel::sendSms($sms,$mobile);
                   $contnet="尊敬的".$u_name.$sex."，很抱歉因您填写的证件号与银行卡不匹配且上传的照片不够清晰，未能通过我们的风控审核！";
                    WeixinModel::sendWeixin($open_id,$contnet,$loan_amount,date('Y-m-d H:i',$loan_request),$access_token);
        }elseif($photo==3 && $sesame==4 && $bankcar==5){
            $sms = "尊敬的".$u_name.$sex."，很抱歉因您填写的银行卡不存在、上传的照片不够清晰且取消了芝麻授权，未能通过我们的风控审核！";
                    $mobile = $mobile;
                    // WeixinModel::sendSms($sms,$mobile);
                   $contnet="尊敬的".$u_name.$sex."，很抱歉因您填写的银行卡不存在、上传的照片不够清晰且取消了芝麻授权，未能通过我们的风控审核！";
                    WeixinModel::sendWeixin($open_id,$contnet,$loan_amount,date('Y-m-d H:i',$loan_request),$access_token);
        }elseif($idnumber==2 && $sesame==4 && $bankcar==5){
            $sms = "尊敬的".$u_name.$sex."，很抱歉因您填写的证件号与银行卡不匹配且取消了芝麻授权，未能通过我们的风控审核！";
                    $mobile = $mobile;
                    // WeixinModel::sendSms($sms,$mobile);
                   $contnet="尊敬的".$u_name.$sex."，很抱歉因您填写的证件号与银行卡不匹配且取消了芝麻授权，未能通过我们的风控审核！";
                    WeixinModel::sendWeixin($open_id,$contnet,$loan_amount,date('Y-m-d H:i',$loan_request),$access_token);
        }elseif($idnumber==2 && $photo==3 && $sesame==4){
            $sms = "尊敬的".$u_name.$sex."，很抱歉因您填写的证件信息无效、上传的照片不够清晰且取消了芝麻授权，未能通过我们的风控审核！";
                    $mobile = $mobile;
                    // WeixinModel::sendSms($sms,$mobile);
                   $contnet="尊敬的".$u_name.$sex."，很抱歉因您填写的证件信息无效、上传的照片不够清晰且取消了芝麻授权，未能通过我们的风控审核！";
                    WeixinModel::sendWeixin($open_id,$contnet,$loan_amount,date('Y-m-d H:i',$loan_request),$access_token);
        }elseif ($idnumber==2 && $photo==3) {
            $sms = "尊敬的".$u_name.$sex."，很抱歉因您填写的证件信息无效且上传的照片不够清晰，未能通过我们的风控审核！";
                    $mobile = $mobile;
                    // WeixinModel::sendSms($sms,$mobile);
                   $contnet="尊敬的".$u_name.$sex."，很抱歉因您填写的证件信息无效且上传的照片不够清晰，未能通过我们的风控审核！";
                    WeixinModel::sendWeixin($open_id,$contnet,$loan_amount,date('Y-m-d H:i',$loan_request),$access_token);
        }elseif ($idnumber==2 && $sesame==4) {
            $sms = "尊敬的".$u_name.$sex."，很抱歉因您填写的证件信息无效且取消了芝麻授权，未能通过我们的风控审核！";
                    $mobile = $mobile;
                    // WeixinModel::sendSms($sms,$mobile);
                   $contnet="尊敬的".$u_name.$sex."，很抱歉因您填写的证件信息无效且取消了芝麻授权，未能通过我们的风控审核！";
                    WeixinModel::sendWeixin($open_id,$contnet,$loan_amount,date('Y-m-d H:i',$loan_request),$access_token);
        }elseif ($idnumber==2 && $bankcar==5) {
            $sms = "尊敬的".$u_name.$sex."，很抱歉因您填写的证件号与银行卡不匹配，未能通过我们的风控审核！";
                    $mobile = $mobile;
                    // WeixinModel::sendSms($sms,$mobile);
                   $contnet="很抱歉因您填写的证件号与银行卡不匹配，未能通过我们的风控审核！";
                    WeixinModel::sendWeixin($open_id,$contnet,$loan_amount,date('Y-m-d H:i',$loan_request),$access_token);
        }elseif ($photo==3 && $sesame==4) {
            $sms = "尊敬的".$u_name.$sex."，很抱歉因您上传的照片不够清晰且取消了芝麻授权，未能通过我们的风控审核！";
                    $mobile = $mobile;
                    // WeixinModel::sendSms($sms,$mobile);
                   $contnet="尊敬的".$u_name.$sex."，很抱歉因您上传的照片不够清晰且取消了芝麻授权，未能通过我们的风控审核！";
                    WeixinModel::sendWeixin($open_id,$contnet,$loan_amount,date('Y-m-d H:i',$loan_request),$access_token);
        }elseif ($photo==3 && $bankcar==5) {
            $sms = "尊敬的".$u_name.$sex."，很抱歉因您填写的银行卡不存在且上传的照片不够清晰，未能通过我们的风控审核！";
                    $mobile = $mobile;
                    // WeixinModel::sendSms($sms,$mobile);
                   $contnet="尊敬的".$u_name.$sex."，很抱歉因您填写的银行卡不存在且上传的照片不够清晰，未能通过我们的风控审核！";
                    WeixinModel::sendWeixin($open_id,$contnet,$loan_amount,date('Y-m-d H:i',$loan_request),$access_token);
        }elseif($sesame==4 && $bankcar==5){
            $sms = "尊敬的".$u_name.$sex."，很抱歉因您填写的银行卡不存在且取消了芝麻授权，未能通过我们的风控审核！";
                    $mobile = $mobile;
                    // WeixinModel::sendSms($sms,$mobile);
                   $contnet="很抱歉因您填写的银行卡不存在且取消了芝麻授权，未能通过我们的风控审核！";
                    WeixinModel::sendWeixin($open_id,$contnet,$loan_amount,date('Y-m-d H:i',$loan_request),$access_token);
        }elseif($idnumber==2){
            $sms = "尊敬的".$u_name.$sex."，很抱歉因您填写的证件信息无效，未能通过我们的风控审核！";
                    $mobile = $mobile;
                    // WeixinModel::sendSms($sms,$mobile);
                   $contnet="尊敬的".$u_name.$sex."，很抱歉因您填写的证件信息无效，未能通过我们的风控审核！";
                    WeixinModel::sendWeixin($open_id,$contnet,$loan_amount,date('Y-m-d H:i',$loan_request),$access_token);
        }elseif ($photo==3) {
            $sms = "尊敬的".$u_name.$sex."，很抱歉因您上传的照片不够清晰，未能通过我们的风控审核！";
                    $mobile = $mobile;
                    // WeixinModel::sendSms($sms,$mobile); 
                   $contnet="尊敬的".$u_name.$sex."，很抱歉因您上传的照片不够清晰，未能通过我们的风控审核！";
                    WeixinModel::sendWeixin($open_id,$contnet,$loan_amount,date('Y-m-d H:i',$loan_request),$access_token);
        }elseif ($sesame==4) {
            $sms = "尊敬的".$u_name.$sex."，很抱歉因您取消芝麻授权，未能通过我们的风控审核！";
                    $mobile = $mobile;
                    // WeixinModel::sendSms($sms,$mobile);
                   $contnet="尊敬的".$u_name.$sex."，很抱歉因您取消芝麻授权，未能通过我们的风控审核！";
                    WeixinModel::sendWeixin($open_id,$contnet,$loan_amount,date('Y-m-d H:i',$loan_request),$access_token); 
        }elseif($bankcar==5){
            $sms = "尊敬的".$u_name.$sex."，很抱歉因您填写的银行卡不存在，未能通过我们的风控审核！";
                    $mobile = $mobile;
                    // WeixinModel::sendSms($sms,$mobile);
                   $contnet="尊敬的".$u_name.$sex."，很抱歉因您填写的银行卡不存在，未能通过我们的风控审核！";
                    WeixinModel::sendWeixin($open_id,$contnet,$loan_amount,date('Y-m-d H:i',$loan_request),$access_token);
        }elseif($idnumber==NULL && $photo==NULL && $sesame==NULL && $bankcar==NULL){
            $sms = "尊敬的".$u_name.$sex."，很抱歉您提交的资料未能通过我们的风控审核！";
                    $mobile = $mobile;
                    // WeixinModel::sendSms($sms,$mobile);
                   $contnet="尊敬的".$u_name.$sex."，很抱歉您提交的资料未能通过我们的风控审核！";
                    WeixinModel::sendWeixin($open_id,$contnet,$loan_amount,date('Y-m-d H:i',$loan_request),$access_token);
        }
    }
    //定时执行的方法
    public function crons(){
        //在文件中写入内容
        file_put_contents("testwen.txt", date("Y-m-d H:i:s") . "执行定时任务！" . "\r\n<br>", FILE_APPEND);
    }
}