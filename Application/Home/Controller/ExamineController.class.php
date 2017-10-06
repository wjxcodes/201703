<?php 
/*
*打款金额审核
*/
namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf8");
use Home\Model\WeixinModel as WeixinModel;
class ExamineController extends BaseController{
    protected $user_model;
    protected $loan_model;
    public function  __construct($appid,$appsecret){
        parent::__construct();//执行父类的构造函数，否则会被覆盖的。
        $this->user_model = M('User');
        $this->loan_model = M('Loan');
    }
	public function index(){
        $where['aname'] = session('aname');
        $lstdata = M()->table('free_user user,free_loan loan')
        ->order('loan.loan_id asc')
        ->where('user.user_id=loan.user_id and loan.maudit=0 and loan.is_loan=1 and user.audit=2')
        ->field("user.user_id,user.user_name,user.open_id,user.user_name,user.bank_name,user.bank_tel,user.identity,user.bank_card,loan_order,loan_amount,loan_time,loan_num,loan_request,user.check,interest,cuts_interest")
        ->order('loan.loan_id asc')
        ->select();
        $this->assign('lstdata',$lstdata);
        $this->display();
	}
    public function change(){
        $id = intval(I('get.id'));
        $status = intval(I('get.status'));
        $where['user_id'] = $id;
        $access_token = WeixinModel::getToken();
        $data = M()->table('free_user user,free_loan loan')
                ->where("user.user_id=loan.user_id and loan.user_id='$id'")
                ->field("user.au_request,user.audit,user.u_name,user.user_id,user.user_name,user.identity,user.bank_card,user.open_id,loan.maudit,loan.loan_amount,loan.loan_request,loan.is_loan,loan.loan_order,loan.loan_time")
                ->find();
        $bool = WeixinModel::sex($data['identity']);
        $sex = $bool?"先生":"女士";
        // 金额审核不通过
        switch ($status) {
            case '1':
                $save['maudit'] = 0;
                $save['loan_amount'] = 0;
                $save['loan_request'] = 0;
                $save['is_loan'] = 0;
                $save['loan_time'] = 0;
                $user_save['au_request'] = 0;
                $user_save['audit'] = 0;
                $resultl = $this->user_model->where($where)->save($user_save);
                $result = $this->loan_model->where($where)->save($save);
                if ($result && $resultl) {
                    $feed=M('feed');
                    $feed_save['user_id']=$data['user_id'];
                    $feed_save['u_name']=$data['u_name'];
                    $feed_save['identity']=$data['identity'];
                    $feed_save['loan_order']=$data['loan_order'];
                    $feed_save['loan_amount']=$data['loan_amount'];
                    $feed_save['reject_state']=1;
                    $feed_save['create_time']=time();
                    $feed->add($feed_save);

                    $dataSms ="尊敬的".$data['u_name'].$sex."，您申请的借款未通过审核，请您仔细核查所填信息是否有误！";
                    $mobile = $data['user_name'];
                    WeixinModel::sendSms($dataSms,$mobile);

                    //WeixinModel::bomber($mobile,$dataSms);
                    $contnet = '尊敬的'.$data['u_name'].$sex."，您申请的借款未通过审核，请您仔细核查所填信息是否有误！";
                    WeixinModel::sendWeixin($data['open_id'],$contnet,$data['loan_amount'],date('Y-m-d H:i',$data['loan_request']),$access_token);
                    $this->redirect('Home/Examine/index');
                }else{
                    echo '<script>alert("网络缘故审核提交失败,请稍后再试！！！");history.back()</script>';exit;
                }
                break;
            // 金额审核通过
            case '2';
                $save['maudit'] = 2;
                $result = $this->loan_model->where($where)->save($save);
                if ($result) {
                    $money = $data['loan_amount'];
                    $cod = substr(trim($data['bank_card']),-4);
                    $dataSms ="尊敬的".$data['u_name'].$sex."，您申请的".$money."元借款已通过审核，系统将尽快进行打款，收款银行卡尾号".$cod."，请注意查收！";
                    $mobile = $data['user_name'];
                    WeixinModel::sendSms($dataSms,$mobile);

                    //WeixinModel::bomber($mobile,$dataSms);
                    $contnet = "尊敬的".$data['u_name'].$sex."，您申请的".$money."元借款已通过审核，系统将尽快进行打款，收款银行卡尾号".$cod."，请注意查收！";
                    WeixinModel::sendWeixin($data['open_id'],$contnet,$data['loan_amount'],date('Y-m-d H:i',$data['loan_request']),$access_token);
                    $this->redirect('Home/Examine/index');
                }else{
                    echo '<script>alert("网络缘故审核提交失败,请稍后再试！！！");history.back()</script>';exit;
                }
                break;
            default:
                $this->redirect('Home/Examine/index');
                break;
        }
    }


    public function credit(){
        $user_model=M('user');
        $where['user_name'] = I('get.id');
        $open_id=I("get.open_id");
        if($open_id==''){

        }else{
            $access_token = WeixinModel::getToken();
            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$open_id&lang=zh_CN";
            $data = WeixinModel::request_get($url);
            $token = json_decode(stripslashes($data));
            $array = json_decode(json_encode($token), true);
            $arr = $user_model->where($where)->find();
            $array['user_id'] = $arr['user_id'];
            $array['user_name'] = $arr['user_name'];
            $array['u_name'] = $arr['u_name'];
            $this->array=$array;
        }
        
   /*微信 昵称*/ 

   /*  淘宝信息 */
        $taobao_info=M('taobao_info');
        $taobao_info_data=$taobao_info->where($where)->find();
        if($taobao_info_data){
            $accountSafeInfo=json_decode($taobao_info_data['accountsafeinfo'],1);
            $addrs=json_decode($taobao_info_data['addrs'],1);
            $bindAccountInfo=json_decode($taobao_info_data['bindaccountinfo'],1);
            $personalInfo=json_decode($taobao_info_data['personalinfo'],1);
            $orderlist=json_decode($taobao_info_data['orderlist'],1);
            $this->mytime=$taobao_info_data['create_time'];
            $this->taobao_arr=$orderlist;
            $this->lost_money=$taobao_info_data['money'];
            $this->personalInfo=$personalInfo;
            $this->accountSafeInfo=$accountSafeInfo;
            $this->addrs=$addrs;
            $this->bindAccountInfo=$bindAccountInfo;
            $hint="淘宝的信息在万达下面";
        }else{
            $taobao=M('taobao');
            $taobao_data=$taobao->where($where)->find();
            if($taobao_data){
                $hint="淘宝的信息未采集到";
            }else{
                $hint="该用户没有淘宝信息，请查看时间是否在5月19日之前";
            }
        }
        $this->hint=$hint;
        /*  淘宝信息 */


        $tel_record=M('tel_record');
        $tel_record_data=$tel_record->where($where)->find();
        $request_id=strlen($tel_record_data['request_id']);
        if($request_id==36){
            $apix=M('apix');
            $apix_data=$apix->where($where)->find();
            $apix_data['basicInfo']=json_decode($apix_data['basic'],1);
            $apix_data['phoneInfo']=json_decode($apix_data['phone'],1);
            $apix_data['deceitRisk']=json_decode($apix_data['decei'],1);
            $apix_data['consumeInfo']=json_decode($apix_data['consume'],1);
            $apix_data['callRecordsInfo']=json_decode($apix_data['callrecords'],1);
            $apix_data['contactAreaInfo']=json_decode($apix_data['contactarea'],1);
            $apix_data['specialCallInfo']=json_decode($apix_data['specialcall'],1);
            $apix_data['phoneOffInfos']=json_decode($apix_data['phoneoff'],1);
            $this->hinti="Apix数据库读出数据";
            $this->apix_data=$apix_data;
            $this->display('Examine/apix');
        }else if($request_id==40){//万达信息
            $wdinfo_model = M('wdinfo');
            $result = $wdinfo_model->where($where)->find();
            $accounts = json_decode($result['accounts'],true);
            $casic = json_decode($result['casic'],true);
            $result = json_decode($result['tel_info'],true);
            $len = count($accounts[0]['behavior']);
            foreach ($accounts[0]['behavior'] as $k => $v) {
                $money +=$v['total_amount'];
            }
            $result = $this->order($result,$lev='call_len');
            $avg = $money/$len;
            $this->assign('avg',$avg);
            $this->assign('result',$result);
            $this->assign('accounts',$accounts[0]['behavior']);
            $this->assign('casic',$casic);
            $this->display();
        }else{   //request  未储存成功！
            $this->hit="未获取到通讯记录信息，请查看时间是否在5月3日之前";
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
        //微信延时通知
    public function sendWx(){
        $get_data = I('get.');
        $where['user_name'] = $get_data['id'];
        $user_info = $this->user_model->where($where)->find();
        $loan_info = $this->loan_model->where($where)->find();
        $bool = WeixinModel::sex($user_info['identity']);
        $sex = $bool?"先生":"女士";
        $access_token = WeixinModel::getToken();
        $contnet = "尊敬的".$user_info['u_name'].$sex."，由于今日申请用户过多，系统温馨提示，您的申请将要等到明日进行审核，给您带来不便，敬请谅解。";
        $result = WeixinModel::sendWeixin($get_data['open_id'],$contnet,$loan_info['loan_amount'],date('Y-m-d H:i',$loan_info['loan_request']),$access_token);
       if ($result) {
           $this->success('发送成功！','',2);
       }else{
        die("<script>alert('发送失败');history.back();</script>");
       }
    }

    public function checkUser(){
        $where['user_id'] = I('get.id');
        $save['check'] = 1;
        $result = $this->user_model->where($where)->save($save);
        if ($result) {
            $this->redirect('Examine/index');
        }else{
            die("<script>alert('标记失败');history.back();</script>");
        }
    }
}