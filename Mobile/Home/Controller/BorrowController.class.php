<?php
/*
借款页面
*/
namespace Home\Controller;
use Think\Controller;
use Home\Model\FocusModel as FocusModel;
use Home\Model\MoneyModel as MoneyModel;
use Home\Model\SesameModel as SesameModel;
use Home\Model\LocationModel as LocationModel;
use Home\Model\RepayModel as RepayModel;
header("content-type:text/html;charset=utf8");
class BorrowController extends Base {

/*借款首页面*/
    public function index(){
    $wxdata = LocationModel::getSignPackage();
    $this->wxdata = $wxdata;
    if (!empty($_REQUEST['code'])) {
          $code = $_REQUEST['code'];
          $data1 = ['appid' => 'wx77c3255a41d184ad', 'secret' => '474b6a0cd731d5bae343db9a0169b57e', 'code' => $code, 'grant_type' => 'authorization_code', ];
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?' . http_build_query($data1);
        $req = json_decode(file_get_contents($url),true);
        $openid = $req['openid'];
          if (!empty($openid)) {
            $user_model = M("user");
            $_SESSION['openid'] = $openid;
            $map['open_id'] = $_SESSION['openid'];
            $user_data = $user_model->where($map)->find();
            if ($user_data) {
              $this->user_data = $user_data;
              $this->redirect('borrow/index');
            }else{
             $this->redirect('user/login');
            }
          }else{
            $this->redirect('user/login');
          }
    }


	    $user = M('user');
	    $loan = M('loan');
      $taobao=M('taobao');
      $jingdong=M('jingdong');
      $tel_record = M('tel_record');

	    $where['user_name'] = session('name');

      $jingdong_data=$jingdong->where($where)->find();
	    $user_data = $user->where($where)->find();
	    $loan_data = $loan->where($where)->find();
      $tel_record_data = $tel_record->where($where)->find();
      $taobao_data=$taobao->where($where)->find();
      $in_mobile = $user_data['user_name'];
/*页面上的银行卡号*/
	    $data['card_1']=substr(session('name'),-11,3);
	    $data['card_2']=substr(session('name'),-4);
/*借款期限换算*/
		  if ($loan_data['loan_time'] == 1){
	      $data['deadline'] = 7;
	    } else if ($loan_data['loan_time'] == 2){
      	    $data['deadline'] = 14;
	    }
        /*  逾期费用  */
        $overdue_show=RepayModel::overdue_show($loan_data['is_pay'],$data['deadline'],$loan_data['renewal_days'],$loan_data['loan_amount']);
        $day_time=strtotime(date('Y-m-d',$overdue_show['time']))+86399;
/*手续费计算*/
	    $data['poundage']=MoneyModel::shouxufei($loan_data['loan_amount'],$loan_data['interest'],$loan_data['cuts_interest']);
	    $wenmoney = $data['poundage']+$overdue_show['overdue_money']+$loan_data['loan_amount'];
/*额度*/
	    if ($user_data['message'] == 1) {
          $data['limit_money']=MoneyModel::xiane($user_data['lines'],$loan_data['loan_lines']);
	    } else {
	        $data['limit_money']=MoneyModel::initial();
	    }
	    $this->wenmoney=$wenmoney;
	    $this->day_time=$day_time;
	    $this->loan_amount=$loan_data['loan_amount'];
	    $this->is_pay=$loan_data['is_pay'];
      $this->jingdong_data=$jingdong_data;
      $this->taobao_data=$taobao_data;
      $this->tel_record_data=$tel_record_data;
      $this->user_data = $user_data;
      $this->loan_data = $loan_data;

      $this->data=$data;
    	$this->display('borrow/index');
    }
/*确认信息页面*/
    public function confirm(){

    	if (empty($_SESSION['name'])) {
	      $this->redirect('user/login');
	    }
	    if(empty($_POST)){
	    	$this->redirect('borrow/index');
	    }
	    $user=M('user');
	    $loan=M('loan');
	    $where['user_name']=$_SESSION['name'];
	    $loan_data=$loan->where($where)->find();
	    $user_data=$user->where($where)->find();
      if($user_data['past']>=3){
           die("<script>alert('您暂未通过风控审核，请您持续关注!!');history.back();</script>");
      }


	    $post=I('post.');

      if ($post['money'] == "" || $post['money'] == null) {
          die("<script>alert('借款金额不能为空');history.back();</script>");
        }

	    if ($user_data['message'] == '0') {
          die("<script>alert('您的信息尚未完善!');history.back();</script>");
        }
        if ($user_data['au_request'] == '1') {
          die("<script>alert('您的信息尚未审核完成!');history.back();</script>");
        }
        if($loan_data['is_loan']==1){
           die("<script>alert('您有未完成审批的借款，请您耐心等待!');history.back();</script>");
        }
        $coupons=M('coupons');
        $coupons_where['user_name']=$_SESSION['name'];
        $coupons_where['is_use']=0;
        $coupons_where['overdue_time']=array('gt',time());
        $coupons_data=$coupons->where($coupons_where)->order('coupons_type desc')->select();
        
        foreach ($coupons_data as $key => $value) {
            if($value['coupons_type']==1){
                $coupons_interest[]=$value;
            }
        }
        foreach ($coupons_data as $key => $value) {
            if($value['coupons_type']==2){
                $coupons_lines[]=$value;
            }
        }
        if ($loan_data['user_id'] == '' || $loan_data['user_id'] == null) {
          $loan_save['user_id'] = $user_data['user_id'];
          $loan_save['user_name'] = $user_data['user_name'];
          $loan_save['linkman_tel'] = $user_data['linkman_tel'];
          $loan_save['identity'] = $user_data['identity'];
          $loan_save['bank_card'] = $user_data['bank_card'];
          $res = $loan->where($where)->add($loan_save);
          if ($res) {
          } else {
            die("<script>alert('提交错误');history.back();</script>");
          }
        }else{
          $loan_save['user_id'] = $user_data['user_id'];
          $loan_save['user_name'] = $user_data['user_name'];
          $loan_save['linkman_tel'] = $user_data['linkman_tel'];
          $loan_save['identity'] = $user_data['identity'];
          $loan_save['bank_card'] = $user_data['bank_card'];
          $res = $loan->where($where)->save($loan_save);
        }  

        $current_date = date('Y-m-d',time()); 
        if($post['time']==7){
           $data['weekLater'] = date('Y-m-d',strtotime("$current_date + 1 week"));
           $time=1;
        }else{
           $data['weekLater'] = date('Y-m-d',strtotime("$current_date + 2 week"));
           $time=2;
        }
/*   手续费*/
        $interest=MoneyModel::interest_type($loan_data['interest_type'],$time);
        $data['poundage']=MoneyModel::shouxufei($post['money'],$interest);
        $data['sum_cost']=MoneyModel::sum_cost($post['money'],$interest);

/*  利息前后置 */ 
        $initial_palace=MoneyModel::initial_palace();
        if($initial_palace==1){
          $data['repay_money']=$post['money'];
          $data['should_money']=$post['money']-$data['poundage'];
        }else if($initial_palace==2){
          $data['should_money']=$post['money'];
          $data['repay_money']=$post['money']+$data['poundage']-$coupons_interest['interest'];   
        }
        $data['user_bank_card'] = "****".substr($user_data['bank_card'],-4);
        $this->coupons_lines=$coupons_lines;
        $this->coupons_interest=$coupons_interest;
        $this->coupons_data=$coupons_data;
        $this->data=$data;
        $this->user_data=$user_data;
        $this->post_data=$post;
    	$this->display('borrow/confirm');
    }
/*处理借款信息*/
    public function refer(){
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
        if(empty($_POST)){
            $this->redirect('borrow/index');
        }
        $post=I('post.');
        $user = M('user');
        $loan = M('loan');
        $where['user_name'] = $_SESSION['name'];
        $user_data = $user->where($where)->find();
        $loan_data = $loan->where($where)->find();

            $feed=M('feed');
            $feed_where['user_id']=$user_data['user_id'];
            $feed_data=$feed->field('create_time')->where($feed_where)->order('id desc')->limit('3')->select();

            if($feed_data[0]['create_time']>'1505404800'){            
                  if($feed_data){
                      $feed_create_time=$feed_data[0]['create_time']-86400*2;
                      foreach ($feed_data as $key => $value) {
                          if($value['create_time']>=$feed_create_time){
                              $feed_num+=1;
                          }
                      }
                      if($feed_num>=2){
                           $feed_time=30-ceil((time()-$feed_data[0]['create_time'])/86400);
                           if($feed_time>0){
                              die("<script>alert('您的综合信用评分未达到审核标准，请您".$feed_time."天之后再来申请');history.back();</script>");
                           } 
                      }
                  }
            }

        if ($post['money'] == "" || $post['money'] == null) {
          die("<script>alert('借款金额不能为空');history.back();</script>");
        }
        if($loan_data['is_loan']==1){
           die("<script>alert('您有未完成审批的借款，请您耐心等待!');history.back();</script>");
        }

        if ($user_data['message'] == 1) {
          $xiane=MoneyModel::xiane($user_data['lines'],$loan_data['loan_lines']);
           $yue = $xiane - $loan_data['loan_amount'];
        }else{
          die("<script>alert('您的信息尚未审核完成!');history.back();</script>");
        }
        $rea = $xiane < (int)$post['money'];
        if ($rea == true) {
          die("<script>alert('您的额度为" . $xiane . "');history.back();</script>");
        }
        if($user_data['past']>=3){
           die("<script>alert('您暂未通过风控审核，请您持续关注!!');history.back();</script>");
       }


      $credit=M('credit');
      $credit_where['user_id']=$user_data['user_id'];
      $credit_data=$credit->field('create_time,is_matched,zm_score')->where($credit_where)->order('credit_id desc')->find();
      $credit_time=date('Y-m-d',$credit_data['create_time']);
      $now_time=date('Y-m-d',time());

if($loan_data['loan_num']<1){
    if($credit_data){
        if($credit_data['is_matched']==1 || $credit_data['zm_score']<630){
            die("<script>alert('【蜻蜓白卡】尊敬的".$user_data['u_name']."，您未通过我们的风控审核，请您仔细核查所填资料是否有误！');window.location.href='index';</script>");
        }
    }
}
      

          $id_len=strlen($user_data['user_id']);
          $order=$this->num();
          $loan_order=substr_replace($order,$user_data['user_id'],8,$id_len);
          $this_save['loan_order'] = $loan_order;
          $renewal_order=substr($loan_order, 0, -1);
          $this_save['renewal_order'] = $renewal_order;
          $loan_res=$loan->where($where)->save($this_save);
          if($loan_res){

          }else{
            die("<script>alert('网络出错了!');history.back();</script>");
          }


    $coupons=M('coupons');
    $coupons_where_interest['id']=$post['coupons_interest'];

    $coupons_interest=$coupons->where($coupons_where_interest)->find();

    /*改变  loan 表  开始 */
    if ($post['time'] == 14) {
        $save['loan_time'] = 2;
    }else{
        $save['loan_time'] = 1;
    }
    $save['interest']=MoneyModel::interest_type($loan_data['interest_type'],$save['loan_time']);
    $save['is_loan'] = 1;

    if($coupons_interest['interest']){
      $save['cuts_interest']=$coupons_interest['interest'];
    }else{
      $save['cuts_interest']=0;
    }


    $save['loan_request'] = time();
    $save['loan_amount'] = $post['money'];
    $res = $loan->where($where)->save($save);
          
    /*改变  loan 表  结束 */
    if($res){
        $request_lev['au_request']=1;
        $request_lev['lev']=0;
        $request_lev_res=$user->where($where)->save($request_lev);
        if($request_lev_res){
/*  优惠卷       */
            if($post['coupons_lines']!=''|| $post['coupons_interest']!=''){
                if($post['coupons_interest']!=''){
                    $coupons_id[]=$post['coupons_interest'];
                }
                if($post['coupons_lines']!=''){
                    $coupons_id[]=$post['coupons_lines'];
                }
                $coupons_where['id']=array('in',$coupons_id);
                $coupons_save['is_use']=1;
                $coupons_data=$coupons->where($coupons_where)->save($coupons_save);
            }
/*       */

            die("<script>alert('您的信息已进入风控审核！');window.location.href='index';</script>"); 
        }else{
            die("<script>alert('出错了！');window.location.href='index';</script>"); 
        }
    }else{
        die("<script>alert('出错了！');history.back();</script>"); 
    }


/*   改变状态    结束  */

    }
/* 借款协议页面*/
    public function agreement(){
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
            }
        /*$where['user_name']=$_SESSION['name'];
        $user=M('user');

        $user_data=$user->where($where)->find();

        $loan=M('loan');

        $loan_data=$loan->where($where)->find();

        $this->user_data=$user_data;
       
        $contract=$user_data['contract'];
        if($user_data['contract']==""){
            $con=date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
            $contract="QTBK-$con";
            $save['contract']=$contract;
            $res=$user->where($where)->save($save);
        }

        $this->contract=$contract;

        $time=time();
        $this->time=$time;

        $get=I('get.');
        $this->get_data=$get;

        $repay=time()+7*86400;
        if($get['time']==14){
           $repay=time()+14*86400;
        }
        if($get['time']==7){
        	$time=1;
        }else{
        	$time=2;
        }


        $interest=MoneyModel::interest_type($loan_data['interest_type'],$time);
        $shouxufei=MoneyModel::shouxufei($get['money'],$interest);

        $this->shouxufei=$shouxufei;
        $this->repay=$repay;*/

        $this->display('borrow/agreement');
    }
    public function num() {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
    public function num1() {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 7);
    }
    public function locat(){
      $position_model = M('Position');
      $data = $_POST;
      $where['user_name'] = $data['param3'];
      $save_user['jd']= $data['param1'];
      $save_user['wd']= $data['param2'];
      $save_user['create_time']= time();
      $save_user['user_name']= $data['param3'];
      $result = $position_model->where($where)->order('create_time asc')->select();
      $count_num = count($result);
      if ($count_num >= 5) {
        $map['id'] = $result[0]['id'];
        $bool = $position_model->where($map)->delete();
        if ($bool) {
          $info = $position_model->add($save_user);
          if ($info) {
            echo json_encode($info);
            return;
          }else{
            echo json_encode('error');
            return;
          }
        }else{
           echo json_encode('error');
           return false;
        }
      }else{
        $info = $position_model->add($save_user);
        if ($info) {
          echo json_encode($info);
        }else{
          echo json_encode('error');
        }
      }
    }

    public function zhengxin(){
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
        $user=M('user');
        $where['user_name']=session('name');
        $user_data=$user->where($where)->find();

        //var_dump($user_data);
        $data['identity']=substr_replace($user_data['identity'],'****',3,11);
        //var_dump($data['identity']);
        $data['time']=date('Y年m月d日',time());
        $this->data=$data;
        $this->user_data=$user_data;
        $this->display('borrow/zhengxin');
    }

    public function deduct(){
      $this->display('borrow/deduct');
    }
}