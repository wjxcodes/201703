<?php
/*
认证信息页面
*/
namespace Home\Controller;
use Think\Controller;
use Home\Model\SesameModel as SesameModel;
use Home\Model\ApixModel as ApixModel;
use Home\Model\MoneyModel as MoneyModel;
use zmxy\ZmopSdk;
use zmxy\zmop\ZmopClient;
use zmxy\zmop\request\ZhimaAuthInfoAuthorizeRequest;
header("content-type:text/html;charset=utf8");
class ApproveController extends Controller {
	  public function index(){
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
        $user=M('user');
        $taobao=M('taobao');
        $tel_record=M('tel_record');
        $jingdong=M('jingdong');
        $where['user_name']=$_SESSION['name'];
        $user_data=$user->where($where)->find();
        $tel_record_data=$tel_record->where($where)->find();
        $taobao_data=$taobao->where($where)->find();
        $jingdong_data=$jingdong->where($where)->find();
        $this->jingdong_data=$jingdong_data;
        $this->taobao_data=$taobao_data;
        $this->tel_record_data=$tel_record_data;
        $this->user_data=$user_data;
        $this->display('approve/index');
    }
/*  芝麻认证  */
    public function auth(){
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
	    $user=M('user');
	    $where['user_name']=$_SESSION['name'];
	    $user_data=$user->where($where)->find();
      	if($user_data['zhima_openid']!=''||$user_data['zhima_openid']!=null||$user_data['zhima_openid']!=0){
            preg_match('/\d{27}/',$user_data['zhima_openid'],$match); 
            if(!empty($match)){
                die("<script>alert('您已进行过芝麻授权');window.location.href='index';</script>");
            }
        }
      	if($user_data['u_name']==''||$user_data['u_name']==null||$user_data['identity']==''||$user_data['identity']==null){
            die("<script>alert('请先填写个人信息');window.location='/free/mobile.php/home/info/detail';</script>");
      	}
      	if($user_data['linkman_name']==''||$user_data['linkman_name']==null){
        	die("<script>alert('请先填写紧急联系人');window.location='/free/mobile.php/home/info/linkman';</script>");
      	}
      	if($user_data['bank_card']==''||$user_data['bank_card']==null){
        	die("<script>alert('请先填写银行卡');window.location='/free/mobile.php/home/info/bank_card';</script>");
      	}
      	$gatewayUrl = "https://zmopenapi.zmxy.com.cn/openapi.do";
      	$privateKeyFile = "/usr/keyi/rsa_private_key.pem";
      	$zmPublicKeyFile = "/usr/keyi/zmf_public_key.pem";
      	$charset = "UTF-8";
      	$appId = "1002359";
      	$client = new ZmopClient($gatewayUrl,$appId,$charset,$privateKeyFile,$zmPublicKeyFile);
       	$request = new ZhimaAuthInfoAuthorizeRequest();
       	$request->setChannel("apppc");
       	$request->setPlatform("zmop");
       	$request->setIdentityType("2");// 必要参数
       	$request->setIdentityParam("{\"name\":\"".$user_data['u_name']."\",\"certType\":\"IDENTITY_CARD\",\"certNo\":\"".$user_data['identity']."\"}");// 必要参数         
       	$request->setBizParams("{\"auth_code\":\"M_H5\",\"channelType\":\"app\"}");//    
       	$url = $client->generatePageRedirectInvokeUrl($request);
       	redirect($url);
    }

    public function num() {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
/*APIX 手机号*/
    public function apix(){
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
        $user=M('user');
        $tel_record=M('tel_record');
        $loan=M('loan');
        $where['user_name']=$_SESSION['name'];
        $user_data=$user->where($where)->find();
        $tel_record_data=$tel_record->where($where)->find();
        $loan_data=$loan->where($where)->find();
        if($tel_record_data['is_collect']==1){
            die("<script>alert('您已进行过认证');history.back();</script>");
        }
        if($tel_record_data['is_collect']==2){
            die("<script>alert('您正在进行认证');history.back();</script>");
        }
        if($user_data['zhima_openid']==''||$user_data['zhima_openid']==null||$user_data['zhima_openid']==0){
           	preg_match('/\d{27}/',$user_data['zhima_openid'],$match); 
            if(empty($match)){
            	die("<script>alert('请先进行芝麻授权');window.location.href='auth';</script>");
            }
        }
        if($user_data['zm_score']==''||$user_data['is_matched']==null){
            /*  芝麻分查询  */
            $response_score=SesameModel::anti_score($user_data);
            $response_score =SesameModel::object_array($response_score);
            /* 行业内名单查询*/
            $response_focus=SesameModel::anti_focus($user_data);
            $response_focus=SesameModel::object_array($response_focus);
            /* 欺诈关注清单*/
            /*$response_fraud=SesameModel::anti_fraud($user_data);
            $response_fraud=SesameModel::object_array($response_fraud);*/
            /*  数据插入 */
            //$credit_save['hit']=$response_fraud['hit'];
            $credit_save['zm_score']=$response_score['zm_score'];
            if($response_focus['is_matched']){
                $is_matched=1;
            }else{
                $is_matched=0;
            }
            $credit_save['is_matched']=$is_matched;
            $credit_save['lines']=800;
            $user->where($where)->save($credit_save);
            if($is_matched==1||$response_score['zm_score'] < 630){

                /*不通过记录*/
                $id_len=strlen($user_data['user_id']);
                $order=$this->num();
                $loan_order=substr_replace($order,$user_data['user_id'],8,$id_len);
                $feed=M('feed');
                $feed_save['user_id']=$user_data['user_id'];
                $feed_save['u_name']=$user_data['u_name'];
                $feed_save['identity']=$user_data['identity'];
                $feed_save['loan_order']=$loan_order;
                $feed_save['loan_amount']=800;
                $feed_save['create_time']=time();
                $feed_save['reject_state']=1;
                $feed->add($feed_save);
                /*不通过记录*/
                die("<script>alert('很遗憾，您未通过我们的初步风控审核，请您20天后再次申请');window.location.href='index';</script>");
            }else{
                $url=ApixModel::apix_url();
                die("<script>window.location.href='".$url."';</script>");
            }
        }else{
            if($loan_data['loan_num']>0){
                $url=ApixModel::apix_url();
                die("<script>window.location.href='".$url."';</script>");
            }
            if($user_data['is_matched']==1||$user_data['zm_score'] < 630){
                die("<script>alert('很遗憾，您未通过我们的初步风控审核，请您20天后再次申请');window.location.href='index';</script>");
            }else{
                $url=ApixModel::apix_url();
                die("<script>window.location.href='".$url."';</script>");
            }
        }
    }
/*  电商认证 */
    public function electricity(){
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
        $taobao=M('taobao');
        $jingdong=M('jingdong');
        $where['user_name']=$_SESSION['name'];
        $taobao_data=$taobao->where($where)->find();
        $jingdong_data=$jingdong->where($where)->find();
        $this->jingdong_data=$jingdong_data;
        $this->taobao_data=$taobao_data;
        $this->display('approve/electricity');
    }
/*京东认证*/
    public function jingdong(){
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
        $jingdong=M('jingdong');
        $where['user_name']=$_SESSION['name'];
        $tel_record=M('tel_record');
        $tel_record_data=$tel_record->where($where)->find();
        $jingdong_data=$jingdong->where($where)->find();
        if($jingdong_data['is_collect']==1){
          	die("<script>alert('您已进行京东认证！');history.back();</script>");
        }
        if($tel_record_data['is_collect']==0){
          	die("<script>alert('请先进行服务商认证！');history.back();</script>");
        }
        $md5=md5('ajax'.$_SESSION['name']);

        $curl = curl_init();
        curl_setopt_array($curl, array(
          	CURLOPT_URL => "http://e.apix.cn/apixanalysis/jd/grant/ele_business/jingdong/jd/page?callback_url=https://ziyouqingting.com/free/mobile.php/home/zhima/jingdong?id=".$_SESSION['name']."&success_url=https://ziyouqingting.com/free/mobile.php/home/zhima/jd_ok?id=".$md5."&failed_url=https://ziyouqingting.com/free/mobile.php/home/approve/index",
          	CURLOPT_RETURNTRANSFER => true,
          	CURLOPT_ENCODING => "",
          	CURLOPT_MAXREDIRS => 10,
          	CURLOPT_TIMEOUT => 30,
          	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          	CURLOPT_CUSTOMREQUEST => "GET",
          	CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "apix-key: f5da3756ebdd4a295dd6849b9778f9d3",
            "content-type: application/json"
          	),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          	die("<script>alert('出错了！');history.back();</script>");
        } else {
          	$response=json_decode($response,1);
         	die("<script>window.location.href='".$response['url']."';</script>");
        }
    }
/*淘宝信息*/
    public function taobao(){
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
        $taobao=M('taobao');
        $tel_record=M('tel_record');
        $where['user_name']=$_SESSION['name'];
        $tel_record_data=$tel_record->where($where)->find();
        $taobao_data=$taobao->where($where)->find();
        if($taobao_data['is_collect']==1){
            die("<script>alert('您已进行淘宝认证');history.back();</script>");
        }
        if($tel_record_data['is_collect']==0){
            die("<script>alert('请先进行服务商认证！');history.back();</script>");
        }
        $md5=md5('ajax'.$_SESSION['name']);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://e.apix.cn/apixanalysis/tb/grant/ele_business/taobao/pages?callback_url=https://ziyouqingting.com/free/mobile.php/home/zhima/taobao?id=".$_SESSION['name']."&success_url=https://ziyouqingting.com/free/mobile.php/home/zhima/ok?id=".$md5."&failed_url=https://ziyouqingting.com/free/mobile.php/home/approve/index",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
            "accept:application/json",
            "apix-key:64672249571d47376d435abbe8c3c602",
            "content-type:application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          die("<script>alert('出错了！');history.back();</script>");
        } else {
           $response=json_decode($response,1);
           die("<script>window.location.href='".$response['url']."';</script>");
        }
    }
}