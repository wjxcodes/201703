<?php
/*
个人信息界面
*/
namespace Home\Controller;
use Think\Controller;
use Home\Model\LLModel as LLModel;
use Home\Model\ApixModel as ApixModel;
use Home\Model\MoneyModel as MoneyModel;
use llpay\llpay_submit;
use llpay\llpay_notify;
use llpay\JSON;

header("content-type:text/html;charset=utf8");
class InfoController extends Controller {

/*  个人信息界面 */
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

        $this->display('info/index');
    }

/*身份证信息  */ 
    public function detail(){
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
        $user=M('user');
        $loan=M('loan');
        $where['user_name']=$_SESSION['name'];
        $user_data=$user->where($where)->find();
        $loan_data=$loan->where($where)->find();
        $addres=explode("|",$user_data['addres']);
        $com_addres=explode("|",$user_data['com_addres']);
        $this->addres=$addres;
        $this->com_addres=$com_addres;
        $this->loan_data=$loan_data;
        $this->user_data=$user_data;
        if($_POST){
            $post=I('post.');
            if($post['yonghuming']==""||$post['yonghuming']==null){
              die("<script>alert('姓名不能为空');history.back();</script>");
            }
            preg_match("/([\x{4e00}-\x{9fa5}]){1}/u",$post['yonghuming'],$match);
            if(empty($match)){
                die("<script>alert('姓名格式错误');history.back();</script>");
            }
            if($post['shenfenzheng']==""||$post['shenfenzheng']==null){
                die("<script>alert('身份证不能为空');history.back();</script>");
            }
            preg_match('/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{2}$)/',$post['shenfenzheng'],$match); 
            if(empty($match)){
                die("<script>alert('身份证号格式错误');history.back();</script>");
            }
            $shenfenzheng_w['identity']=$post['shenfenzheng'];
            $shenfenzheng_w['user_id']=array('NEQ',$user_data['user_id']);
            $shenfenzheng=$user->where($shenfenzheng_w)->select();

            if($shenfenzheng){
                die("<script>alert('该身份证号已经被使用！');history.back();</script>");
            }

            $data['u_name']=$post['yonghuming'];
            $data['identity']=$post['shenfenzheng'];

            $data['education']=$post['xueli'];
            $data['bank_name']=$post['yonghuming'];

            /*地址 */
            $data['addres']=$post['user_province']."|".$post['user_city']."|".$post['user_area']."|".$post['addres'];
            $data['com_addres']=$post['company_province']."|".$post['company_city']."|".$post['company_area']."|".$post['com_addres'];
            /*地址*/
            $res=$user->where($where)->save($data);
            if($res){
                $alter_info['alter_info']=1;
                $user->where($where)->save($alter_info);
                die("<script>alert('身份信息保存成功');window.location.href='index';</script>");  
            }else{
                die("<script>window.location.href='detail';</script>");
            }
        }
        $this->display('info/detail');
    }


/*银行卡信息*/
    public function bank_card(){
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
        $user=M('user');
        $where['user_name']=$_SESSION['name'];
        $user_data=$user->field('user_name,u_name,identity,linkman_name,bank_tel,bank_name as chikaren,bank_card as yinhangka,bank_tel as kashoujihao,audit,zhima_openid,create_time')->where($where)->find();
        if($user_data['u_name']==''||$user_data['u_name']==null||$user_data['identity']==''||$user_data['identity']==null){
            die("<script>alert('请先填写个人信息');window.location.href='detail';</script>");
        }

        $loan=M('loan');
        $loan_data=$loan->where($where)->find();
        $this->loan_data=$loan_data;
        $this->user_data=$user_data;
        if($_POST){
            $post=I('post.');
            $save['bank_name']=$post['chikaren'];
            $yinhang=str_replace(' ','',$post['yinhangka']);
            if($user_data['yinhangka']==$yinhang && $user_data['kashoujihao']=='LL'){
                die("<script>alert('已绑卡成功！');window.location='/free/mobile.php/home/info/index'</script>");
            }
            $save['bank_card']=$yinhang;
            $save['bank_tel']='';
            $res=$user->where($where)->save($save);
            $this->wen_sign($user_data['user_name'],$user_data['identity'],$user_data['u_name'],$save['bank_card'],$user_data['create_time']);
        }
        $this->display('info/bank_card');
    }
/*连连绑卡*/

    public function wen_sign($user_id,$id_no,$acct_name,$card_no,$register_time){

    $llpay_config=LLModel::llconfig();
    $version = '1.1';
    $app_request = '3';
    $sign_type  = 'RSA';
    $id_type = '0';
    $pay_type  = 'I';
    $risk_item  = '{\"frms_ware_category\":\"2010\",\"user_info_mercht_userno\":\"'.$user_id.'\",\"user_info_bind_phone\":\"'.$user_id.'\",\"user_info_dt_register\":\"'.$register_time.'\",\"user_info_full_name\":\"'.$acct_name.'\",\"user_info_id_no\":\"'.$id_no.'\",\"user_info_identify_type\":\"3\",\"user_info_identify_state\":\"1\"}';
    $url_return  = "https://ziyouqingting.com/free/mobile.php/home/info/to_return";
    $parameter = array (
          "oid_partner" => trim($llpay_config['oid_partner']),
          "version" => $version,
          "user_id" => $user_id,
          "app_request" => $app_request,
          "sign_type" => $sign_type,
          "id_type" => $id_type,
          "id_no" => $id_no,
          "acct_name" => $acct_name,
          "card_no" => $card_no,
          "pay_type" => $pay_type,
          "risk_item" => $risk_item,
          "url_return" => $url_return
    );
    $llpay_gateway_new = 'https://wap.lianlianpay.com/signApply.htm';

    $llpaySubmit = new LLpay_submit($llpay_config,$llpay_gateway_new);
    $html_text = $llpaySubmit->buildRequestForm($parameter, "post", "确认");
    echo $html_text;
  }

/*  连连银行卡绑卡回调*/
    public function to_return(){
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
        $get=I('get.');
        if($get['status']=="0000"){
            $user=M('user');
            $where['user_name']=$_SESSION['name'];
            $user_data=$user->field('zhima_openid')->where($where)->find();
            $result=str_replace('&quot;','"',$get['result']);
            $result=json_decode($result,1);
            $user=M('user');
            $where['user_name']=session('name');
            $save['bank_tel']="LL";
            $save['agreeno']=$result['agreeno'];
            $res=$user->where($where)->save($save);
            if($res){
                die("<script>alert('绑卡成功！');window.location='/free/mobile.php/home/info/index'</script>");
            }else{
                die("<script>alert('已绑卡成功！');window.location='/free/mobile.php/home/info/index'</script>");
            }
        }else{
            if($get['status']=="9910"){
                die("<script>alert('".$get['result']."');window.location='/free/mobile.php/home/info/bank_card'</script>");
            }
            if($get['result']==''){
                die("<script>alert('出错了！,请使用本人银行卡');window.location='/free/mobile.php/home/info/bank_card'</script>");
            }else{
                die("<script>alert('".$get['result']."');window.location='/free/mobile.php/home/info/bank_card'</script>");
            }
            
        }
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


        if($user_data['u_name']==''||$user_data['u_name']==null||$user_data['identity']==''||$user_data['identity']==null){
            die("<script>alert('请先填写个人信息');window.location.href='detail';</script>");
        }
        /*if($user_data['bank_tel']!='LL'){
            die("<script>alert('请先填写银行卡信息');window.location.href='bank_card';</script>");
        }*/

        if($tel_record_data['is_collect']==1){
            die("<script>alert('您已进行过认证');history.back();</script>");
        }
        if($tel_record_data['is_collect']==2){
            die("<script>alert('您正在进行认证');history.back();</script>");
        }

        $identity=substr($user_data['identity'],0,2);
        if($identity!='41'){
            if($loan_data['loan_num']<1){
                $shenhe=time()-86400;
                die("<script>alert('您正在进行初步审核');history.back();</script>");
            }
        }
        

        if($loan_data['loan_num']>0){
            $url=ApixModel::apix_url();
            die("<script>window.location.href='".$url."';</script>");
        }

        if($user_data['lines']==''){
            $user_save['lines']=1000;
            $user->where($where)->save($user_save);
        }
        
        if(($user_data['zm_score']>300 && $user_data['zm_score'] <630) || $user_data['is_matched']==1){
            die("<script>alert('很遗憾，您未通过我们的初步风控审核，请您20天后再次申请');window.location.href='index';</script>");
        }

        $url=ApixModel::apix_url();
        die("<script>window.location.href='".$url."';</script>");
    }


/*  电商认证 */
    public function electricity(){

        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }

        $apix=M('apix');
        $where['user_name']=$_SESSION['name'];

        $apix_data=$apix->field('callrecords')->where($where)->order('id desc')->find();

        if(!$apix_data){
            die("<script>alert('请先进行服务商认证！');window.location.href='index';</script>");
        }
        $callRecordsInfo=json_decode($apix_data['callrecords'],1);
        if(!$callRecordsInfo){
            die("<script>alert('请先进行服务商认证！');window.location.href='index';</script>");
        }
        foreach ($callRecordsInfo as $key => $value) {
            preg_match('/^1[34578]\d{9}$/A',$value['phoneNo'],$match);
            if(!empty($match)){

                if($value['calledTimes']!=0 && $value['callTimes']!=0){
                    $interflow+=1;
                }

                if($key<50){
                    $valid+=1;
                }

                if($key<25){
                    if($value['calledTimes']!=0 && $value['callTimes']!=0){
                        $interflow25+=1;
                    }
                }
            }
        }

        if($interflow<10 || $valid<30 || $interflow25<5){
            die("<script>alert('很遗憾，您未通过我们的初步风控审核，请您20天后再次申请');window.location.href='index';</script>");
        }

        $taobao=M('taobao');
        $jingdong=M('jingdong');
        
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
            die("<script>alert('请先进行服务商认证！');window.location.href='index';</script>");
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
            die("<script>alert('请先进行服务商认证！');window.location.href='index';</script>");
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



/* 图片信息*/
    public function photo(){
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
        $user=M('user');
        $loan=M('loan');
        $where['user_name']=$_SESSION['name'];
        $user_data=$user->where($where)->find();
        $loan_data=$loan->field('is_loan')->where($where)->find();
        $this->loan_data=$loan_data;
        $this->user_data=$user_data;
        if($_POST){
            $post=I('post.');
            /*foreach ($post as $key => $value) {
                $conven.=$value;
            }*/
            $conven=$post['conven'];
            $char = "，。";
            $pattern=array("/[[:punct:]]/i","/['.$char.']/u");
            $conven=preg_replace($pattern,'',$conven);
            if($conven!="本人自愿通过蜻蜓白卡向其合作银行申请贷款遵守合约同意上报征信"){
                die("<script>alert('输入错误！');history.back();</script>");
            }
            $save['conven']=1;
            $res=$user->where($where)->save($save);
            if($res){
                die("<script>window.location.href='index';</script>");
            }else{
                die("<script>alert('信息提交失败！');history.back();</script>");
            }
        }
        $this->display('info/photo');
    }



    public function upload(){
        $where_user['user_name'] = session('name');
        $user_model = M('User');
        $user_data = $user_model->where($where_user)->find();
        if ($_FILES) {
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     20971520;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
            $upload->savePath  =     ''; // 设置附件上传（子）目录
            // 上传文件 
            $info   =   $upload->upload();
            $identity_reverse= $info['identity_reverse']['savepath'].$info['identity_reverse']['savename'];
            $self_portrait= $info['self_portrait']['savepath'].$info['self_portrait']['savename'];
            $identity_front= $info['identity_front']['savepath'].$info['identity_front']['savename'];
            if(!$info) {
                die('<script>alert("上传图片失败");history.back()</script>');
            }else{// 上传成功
                if($identity_reverse!=''){
                  unlink("./Uploads/".$user_data["identity_reverse"]."");
                  $path['identity_reverse']=$identity_reverse;
                }
                if($self_portrait!=''){
                  unlink("./Uploads/".$user_data["self_portrait"]."");
                  $path['self_portrait']=$self_portrait;
                }
                if($identity_front!=''){
                  unlink("./Uploads/".$user_data["identity_front"]."");
                  $path['identity_front']=$identity_front;
                }
                $res=$user_model->where($where_user)->save($path);
                if($res){
                    foreach ($info as $key => $value) {
                        $path="./Uploads/".$value['savepath'].$value['savename'];
                        $mini="./Uploads/".$value['savepath'].$value['savename'];
                        $image=new \Think\Image();
                        $image->open($path);
                        $image->thumb(1000,1000)->save($mini);
                    }
                    if($user_data['conven']==0){
                        $this->redirect('info/photo');
                    }else{
                        if($user_data['message']==1){
                            $this->redirect('info/index');
                        }else{
                            $this->redirect('info/index');
                        }
                    }
                }else{
                    die('<script>alert("上传图片失败");history.back()</script>');
                }    
            }
        } 
    } 



/*联系人  信息*/
    public function linkman(){
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
        $user=M('user');
        $loan=M('loan');
        $where['user_name']=$_SESSION['name'];
        $user_data=$user->field('user_name,u_name,identity,relation,bank_tel,linkman_name,bank_card,linkman_tel,audit,clan_relation,clan_tel,clan_name')->where($where)->find();
        $loan=M('loan');
        $loan_data=$loan->where($where)->find();
        $this->loan_data=$loan_data;
        $this->user_data=$user_data;
        if($_POST){
            $post=I('post.');
            if($post['lianxirenxingming']=="" || $post['lianxirenxingming']==null){
               die("<script>alert('第二紧急联系人不能为空');history.back();</script>");
            }
            preg_match('/([\x{4e00}-\x{9fa5}]){1}/u',$post['lianxirenxingming'],$match); 
            if(empty($match)){
                die("<script>alert('第二紧急联系人姓名格式错误');history.back();</script>");
            }
            if($user_data['u_name']==$post['lianxirenxingming']){
                  die("<script>alert('第二紧急联系人不能为本人');history.back();</script>"); 
            }
            if($post['lianxifangshi']==""||$post['lianxifangshi']==null){
                die("<script>alert('第二紧急联系人手机号不能为空');history.back();</script>");
            }
            preg_match('/^1[34578]\d{9}$/A',$post['lianxifangshi'],$match); 
            if(empty($match)){
                die("<script>alert('第二紧急联系人手机号格式错误');history.back();</script>");
            }
            if($user_data['user_name']==$post['lianxifangshi']){
                die("<script>alert('第二紧急联系人手机号不能为本人手机号');history.back();</script>"); 
            }


            if($post['clan_name']==$post['lianxirenxingming']){
               die("<script>alert('两个紧急联系人姓名不能相同');history.back();</script>");
            }
            if($post['clan_tel']==$post['lianxifangshi']){
               die("<script>alert('两个紧急联系人手机号不能相同');history.back();</script>");
            }


            if($post['clan_name']=="" || $post['clan_name']==null){
               die("<script>alert('第一紧急联系人不能为空');history.back();</script>");
            }
            preg_match('/([\x{4e00}-\x{9fa5}]){1}/u',$post['clan_name'],$match); 
            if(empty($match)){
                die("<script>alert('第一紧急联系人姓名格式错误');history.back();</script>");
            }
            if($user_data['u_name']==$post['clan_name']){
                  die("<script>alert('第一紧急联系人不能为本人');history.back();</script>"); 
            }
            if($post['clan_tel']==""||$post['clan_tel']==null){
                die("<script>alert('第一紧急联系人手机号不能为空');history.back();</script>");
            }
            preg_match('/^1[34578]\d{9}$/A',$post['clan_tel'],$match); 
            if(empty($match)){
                die("<script>alert('第一紧急联系人手机号格式错误');history.back();</script>");
            }
            if($user_data['user_name']==$post['clan_tel']){
                die("<script>alert('第一紧急联系人手机号不能为本人手机号');history.back();</script>"); 
            }

 
            
                $data['relation']=$post['guanxi'];
                $data['linkman_name']=$post['lianxirenxingming'];
                $data['linkman_tel']=$post['lianxifangshi'];
                $data['clan_tel']=$post['clan_tel'];
                $data['clan_name']=$post['clan_name'];
                $data['clan_relation']=$post['clan_relation'];
                $res=$user->where($where)->save($data);
                if($res){
                    $alter_info['alter_info']=1;
                    $user->where($where)->save($alter_info);
                        die("<script>alert('联系人信息保存成功');window.location.href='index';</script>");
                }else{
                    die("<script>window.location.href='linkman';</script>");
                }
            
        }
        $this->display('info/linkman');
    }


}

