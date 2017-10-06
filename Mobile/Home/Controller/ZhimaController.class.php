<?php
/*
芝麻返回和apix返回
*/
namespace Home\Controller;
use Think\Controller;
use zmxy\ZmopSdk;
use zmxy\zmop\ZmopClient;
use Home\Model\MoneyModel as MoneyModel;
use zmxy\zmop\request\ZhimaCreditIvsDetailGetRequest;
use zmxy\zmop\request\ZhimaCreditAntifraudRiskListRequest;
use zmxy\zmop\request\ZhimaCustomerCertificationInitializeRequest;
use zmxy\zmop\request\ZhimaCustomerCertificationCertifyRequest;
use zmxy\zmop\request\ZhimaCustomerCertificationQueryRequest;
header("content-type:text/html;charset=utf8");
class ZhimaController extends Controller {
    public function index(){
            

    $params=$_REQUEST['params'];
    $sign=$_REQUEST['sign'];  
            function getResult($params,$sign) {
               
                // 判断串中是否有%，有则需要decode
                $params = strstr ( $params, '%' ) ? urldecode ( $params ) : $params;
                $sign = strstr ( $sign, '%' ) ? urldecode ( $sign ) : $sign;

                $gatewayUrl = "https://zmopenapi.zmxy.com.cn/openapi.do";
                $privateKeyFile = "/usr/keyi/rsa_private_key.pem";
                $zmPublicKeyFile = "/usr/keyi/zmf_public_key.pem";

                $charset = "UTF-8";
                $appId = "1002359";

                $client = new ZmopClient ( $gatewayUrl, $appId, $charset, $privateKeyFile, $zmPublicKeyFile );
                $result = $client->decryptAndVerifySign ( $params, $sign );
                return $result;
            }
    $pass=getResult($params,$sign);
    $expass=explode('&',$pass);
    preg_match('/\d+/',$expass['0'],$openid);
    $expass2=explode('=',$expass['0']);
        if($expass2[0]!="open_id"){
            die("<script>window.location='/free/mobile.php/home/info/index'</script>");
        }
        $user=M('user');
        $where['user_name']=$_SESSION['name'];
        $save['zhima_openid']=$openid[0];
        $res=$user->where($where)->save($save);
        if($res){
            die("<script>window.location='/free/mobile.php/home/info/index'</script>"); 
        }else{
            die("<script>window.location='/free/mobile.php/home/info/index'</script>");
        }

        $this->display('zhima/index');
    }
    public function taobao(){
       $get=I('get.');
       $taobao=M('taobao');
       $where['user_name']=$get['id'];
       $save['token']=$get['token'];
       $taobao->where($where)->save($save);
    }

    public function ok(){
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
       $get=I('get.');
       $md5=md5('ajax'.$_SESSION['name']);
       if($get['id']==$md5){
            $taobao=M('taobao');
            $user=M('user');
            $where['user_name']=$_SESSION['name'];
            $user_data=$user->where($where)->find();
            $save['user_name']=$_SESSION['name'];
            $save['is_collect']=1;
            $res=$taobao->add($save);
            if($res){
              if($user_data['message']==1){
                 die("<script>window.location='/free/mobile.php/home/info/index'</script>");
              }else{
                $user_save['message']=1;
                $user->where($where)->save($user_save);
                die("<script>alert('恭喜您！通过我们的初次评估，您的初始额度为".$user_data['lines']."元整！');window.location='/free/mobile.php/home/info/index'</script>"); 
              }
            }else{
              die("<script>alert('未知网络原因导致失败！');window.location='/free/mobile.php/home/info/index'</script>"); 
            }
       }
    }

    public function apix(){
       $get=I('get.');
       $tel_record=M('tel_record');
       $where['user_name']=$get['id'];
       $save['request_id']=$get['token'];
       $tel_record->where($where)->save($save);
    }
    public function apix_ok(){
      if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
       $get=I('get.');
       $md5=md5('ajax'.$_SESSION['name']);
       if(1){
          $tel_record=M('tel_record');
          $where['user_name']=$_SESSION['name'];
          $user=M('user');
          $user_data=$user->where($where)->find();
          $tel_record_data=$tel_record->where($where)->find();
          if($tel_record_data){
             $save['is_collect']=1;
             $res=$tel_record->where($where)->save($save);
          }else{
             $save['user_name']=$_SESSION['name'];
             $save['is_collect']=1;
             $save['create_time']=time();
             $res=$tel_record->where($where)->add($save);
          }
          if($res){
/* 初次额度判断*/            
              if($user_data['message']==1){
                 die("<script>window.location='/free/mobile.php/home/info/index'</script>");
              }else{
                $user_save['message']=1;
                $user->where($where)->save($user_save);
                die("<script>alert('恭喜您！通过我们的初次评估，您的初始额度为".$user_data['lines']."元整！');window.location='/free/mobile.php/home/info/index'</script>"); 
              }
          }else{
              die("<script>window.location='/free/mobile.php/home/info/index'</script>");
          }
       }
    }



    public function jingdong(){
      $get=I('get.');
       $jingdong=M('jingdong');
       $where['user_name']=$get['id'];
       $save['token']=$get['token'];
       $jingdong->where($where)->save($save);
    }

    public function jd_ok(){
      if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
       $get=I('get.');
       $md5=md5('ajax'.$_SESSION['name']);
       if($get['id']==$md5){
          $jingdong=M('jingdong');
          $where['user_name']=$_SESSION['name'];
           $user=M('user');
          $user_data=$user->where($where)->find();
          $jingdong_data=$jingdong->where($where)->find();
          if($jingdong_data){
             $save['is_collect']=1;
             $res=$jingdong->where($where)->save($save);
          }else{
             $save['user_name']=$_SESSION['name'];
             $save['is_collect']=1;
             $save['create_time']=time();
             $res=$jingdong->where($where)->add($save);
          }
          if($res){
              if($user_data['message']==1){
                 die("<script>window.location='/free/mobile.php/home/info/index'</script>");
              }else{
                $user_save['message']=1;
                $user->where($where)->save($user_save);
                die("<script>alert('恭喜您！通过我们的初次评估，您的初始额度为".$user_data['lines']."元整！');window.location='/free/mobile.php/home/info/index'</script>"); 
              }
          }else{
              die("<script>window.location='/free/mobile.php/home/info/index'</script>");
          }
       }
    }


}
