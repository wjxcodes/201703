<?php
/*
登录界面
*/
namespace Home\Controller;
use Think\Controller;
use dayu\api_demo\SmsDemo;
header("content-type:text/html;charset=utf8");
use Home\Model\MessageModel as MessageModel;
use Home\Model\SesameModel as SesameModel;
class UserController extends Controller {
    public function weixin(){
          $redirecturl= urlencode("https://ziyouqingting.com/free/mobile.php/Home/borrow/index");
            $appid='wx77c3255a41d184ad';
            $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirecturl."&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
            header("Location:$url");
    }
    public function weixincode(){
          $redirecturl= urlencode("https://ziyouqingting.com/free/mobile.php/Home/repay/index");
            $appid='wx77c3255a41d184ad';
            $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirecturl."&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
            header("Location:$url");
    }
   
    public function login(){
        SESSION_START();
        if ($_POST) {
            $post_data = I('post.');
            $user_model = M('User');
            $post_name = trim($post_data['name']);
            $post_pwd = trim($post_data['pwd']);
            if (!empty($post_name) && !empty($post_pwd)) {
                $bool = MessageModel::isMobile($post_name);
                if (!$bool) {
                   echo '<script>alert("手机号格式错误");history.back();</script>';
                   exit;
                }
                $where['user_name'] = $post_name;
                $user_data = $user_model->where($where)->find();
                if ($user_data) {
                    $post_pwd = md5(md5($post_pwd)."...");
                    $info_pwd = $user_data['pass_word'];
                    if ($post_pwd == $info_pwd) {
                        $_SESSION['name'] = $user_data['user_name'];
                        $data['open_id'] = session('openid');
                        if ($data['open_id']) {
                            $user_model->where($where)->save($data);
                            $this->redirect('borrow/index');
                        }else{
                            $this->redirect('borrow/index');
                        }
                    }else{
                        echo '<script>alert("账号和密码不匹配");history.back();</script>';
                        exit;
                    }
                }else{
                    echo '<script>alert("请先注册");window.location.href="register";</script>';
                    exit;
                }
                
            }else{
                echo '<script>alert("请输入账号和密码");history.back();</script>';
                exit;
            }
        }else{
            $this->display('user/login');
        }

    }
    public function logout(){
        $user_model= M('User');
        $data['is_logout']=1;
        $map['user_name']  = $_SESSION['name'];
        $user_model->where($map)->save($data);
        $_SESSION['name']='';
        $_SESSION['sendCode']='';
        $this->redirect('user/login');
    }
    public function register(){
        $code_time=session('code_time');
        $time=90-(time()-$code_time);
        if($time>0){
            $now_time=$time;
        }
        $this->now_time=$now_time;
        $user_model = M('User');
        if ($_POST) {
            $post_data = I('post.');
            $sendCode = session('sendCode');
            $inputCode = $post_data['code'];
            $bon_pwd = trim($post_data['bonuscode']);
            $return_check = SesameModel::checkW($bon_pwd);
            if($bon_pwd){
                if($return_check){    
                    $map['bonuscode'] = $bon_pwd;
                    $bon_data = $user_model->where($map)->field('user_id,user_name,bonuscode,bonuscodes')->find();
                }
                if($bon_data){
                    $data['bonuscodes'] = $bon_pwd;
                }else{
                    echo '<script>alert("不存在的邀请码");history.back();</script>';exit;
                }
            }
            if (trim($post_data['name']) && $sendCode && trim($post_data['pwd'])) {
                $bool = MessageModel::isMobile(trim($post_data['name']));
                if (!$bool) {
                   echo '<script>alert("手机号格式错误");history.back();</script>';
                   exit;
                }
                if ($sendCode == $inputCode) {
                    $data['user_name'] = trim($post_data['name']);
                    $data['pass_word'] = md5(md5(trim($post_data['pwd']))."...");
                    $data['create_time']=time();
                    $result = $user_model->add($data);
                }else{
                    echo '<script>alert("验证码不正确");history.back();</script>';
                    exit;
                }
                if ($result) {
                    $success = $this->creatCode($result,$bon_data['user_id'],$data['bonuscodes'],$data['user_name']);
                    if($success){
                        echo '<script>alert("恭喜您注册成功");window.location.href="login";</script>';
                    }else{
                        $dmap['user_id'] = $result;
                        $user_model->where($dmap)->delete();
                        echo '<script>alert("请重新注册");history.back();</script>';
                    }
                }else{
                    echo '<script>alert("请重新注册");history.back();</script>';
                }
            }else{
                echo '<script>alert("请正确填写手机号、密码和验证码");history.back();</script>';
                exit;
            }
        }else{
        $this->display('user/register');
        }
    }
    public function sendCode(){
        $mobile = I('post.tel');
        $user_model=M('user');    
        $where['user_name']=$mobile;
        $user_data=$user_model->where($where)->find();
        if($user_data){
            $data = "该手机号已经被注册！";
            $this->ajaxReturn($data);
        }else{
            session('code_time',time());
            $code = rand(112674,982541);
            $data="您好，您的验证码是：".$code."。";
            MessageModel::sendSms($data,$mobile);

            $_SESSION['sendCode']=$code;
        }
    }
    public function sendCode_forget(){
        $mobile = I('post.tel');
        $user_model=M('user');    
        $where['user_name']=$mobile;
        $user_data=$user_model->where($where)->find();
        if($user_data){
            session('code_time',time());
            $code = rand(112674,982541);
            $data="您好，您的验证码是：".$code."。";
            MessageModel::sendSms($data,$mobile);
            $_SESSION['sendCode']=$code;
        }else{
            $data = "该手机号未注册！";
            $this->ajaxReturn($data);
        }
    }

     public function forget(){
        $code_time=session('code_time');
        $time=90-(time()-$code_time);
        if($time>0){
            $now_time=$time;
        }
        $this->now_time=$now_time;

        
       $user_model = M('User');
        if ($_POST) {
            $post_data = I('post.');
            $where['user_name'] = $post_data['name'];
            $sendCode = session('sendCode');
            $inputCode = $post_data['code'];
            if ($where['user_name'] && $sendCode && $post_data['pwd']) {

                if ($sendCode == $inputCode) {
                    $data['pass_word'] = md5(md5($post_data['pwd'])."...");
                    $result = $user_model->where($where)->save($data);
                }else{
                    echo '<script>alert("验证码不正确");history.back();</script>';
                    exit;
                }
                
                if ($result) {
                    echo '<script>alert("密码修改成功");window.location.href="login";</script>';
                    exit;
                }else{
                    echo '<script>alert("密码修改失败");history.back();</script>';
                    exit;
                }
            }else{
                echo '<script>alert("请正确填写手机号、密码和验证码");history.back();</script>';
                exit;
            }
        }else{
            $this->display('user/forget');
        }
    }

    public function ajax(){
        $user = I('post.phone');
        $model= M("user");
        $map['user_name']= $user;
        $us=$model->where($map)->select();
        if(empty($us)){
            $data  = "true";
            $this->ajaxReturn($data);
        }else{
            $data = "false";
            $this->ajaxReturn($data);
        }
    }       

    public function agreement(){
        $this->display('user/agreement');
    }
    public function message(){
        $this->display('user/message');
    }
    public function creatCode($user_id,$user_ids=NULL,$codes=NULL,$user_name){
        $user_model = M('User');
        $inv_model = M('Invitation');
        $coupons_model = M('Coupons');
        $code = SesameModel::getRandomString($user_id);
        $map['user_id'] = $user_id;
        $data['bonuscode'] = $code;
        $return = $user_model->where($map)->save($data);
        if($return && $user_ids && $codes){
            $inv_add['invitation_coed'] = $code;
            $inv_add['invitation_id'] = $user_id;
            $inv_add['invitation_coeds'] = $codes;
            $inv_add['invitation_ids'] = $user_ids;
            $inv_add['set_time'] = time();
            $returns = $inv_model->add($inv_add);
            $cou_map['user_name'] = $user_name;
            $cou_data = $coupons_model->where($cou_map)->find();
            if(!$cou_data){
                $save['user_name']=$user_name;
                $save['coupons_type']=1;
                $save['create_time']=time();
                $save['overdue_time']=strtotime(date('Y-m-d',strtotime('+1 day')))+86339;
                $save['interest']=10;
                $coupons_data=$coupons_model->add($save);
            }
            return $returns;
        }else{
            return $return;
        }
    }
}