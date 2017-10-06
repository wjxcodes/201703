<?php
namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf8");
class ApplyController extends Controller {
    public function __initialize(){
    }
    public function queren(){
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        } 
        $where['user_name']=$_SESSION['name'];
        $user=M('user');
        $message=$user->field('user_id,user_name,linkman_tel,identity,bank_card,message,audit')->where($where)->select();
        $loan=M('loan');
        $is_loan=$loan->field('user_id,is_loan,loan_num,loan_amount')->where($where)->select();
        if($_POST){
if($message[0]['message']=='0'){
                    $this->redirect('Info/index');
                }
                if($message[0]['au_request']=='1'){
                    die("<script>alert('您的信息尚未审核完成!');history.back();</script>");
                }
            $jiekuan['jiekuanjine']=I('post.jiekuanjine');
            $jiekuan['jiekuanshijian']=I('post.jiekuanshijian');
            $jiekuan['djine']=I('post.djine');
            $jiekuan['sxf']=I('post.sxf');
            
        }else{
            die("<script>alert('请选择借款金额和时间');history.back();</script>");
        }
        $this->jiekuan=$jiekuan;
        $this->display('apply/queren');
    }
    public function index(){
    	if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }      
    	$where['user_name']=$_SESSION['name'];
        $user=M('user');
        $message=$user->field('user_id,user_name,linkman_tel,identity,bank_card,message,audit')->where($where)->select();

        $loan=M('loan');
		$is_loan=$loan->field('user_id,is_loan,loan_num,loan_amount')->where($where)->select();
        $this->is_loan=$is_loan[0];
        if($message[0]['message']==1){
                $xiane=$is_loan[0]['loan_num']*200+800;
                $yue=$xiane-$is_loan[0]['loan_amount'];
                $this->yue=$yue;
                $this->xiane=$xiane;
                
                $yingdaolixi=$xiane*0.098;
                $this->yingdaolixi=$yingdaolixi;
                
                $yingdaojine=$xiane*0.902;
                $this->yingdaojine=$yingdaojine;
            }else{
                $this->yue=$yue;
                $this->xiane=$xiane;
            }



    	if($_POST){
             $post=I('post.');

		        if($message[0]['message']=='0'){
		            $this->redirect('Info/index');
		        }
		        if($message[0]['au_request']=='1'){
		        	die("<script>alert('您的信息尚未审核完成!');history.back();</script>");
		        }
			        if($is_loan[0]['user_id']==''||$is_loan[0]['user_id']==null){
			        	$data['user_id']=$message[0]['user_id'];
			        	$data['user_name']=$message[0]['user_name'];
			        	$data['linkman_tel']=$message[0]['linkman_tel'];
			        	$data['identity']=$message[0]['identity'];
			        	$data['bank_card']=$message[0]['bank_card'];
			        	  $res=$loan->where($where)->add($data);
			        	  if($res){
			        	  }else{
			        	  	die("<script>alert('提交错误');history.back();</script>");
			        	  }
			        }

		        if($is_loan[0]['is_loan']=='1'){
		               die("<script> alert('已经有申请中的贷款');history.back();</script>");
		        }

			    if($post['jiekuanjine']==""||$post['jiekuanjine']==null){
                     die("<script>alert('借款金额不能为空');history.back();</script>");
			    }
                  
                
                $rea=$xiane<(int)$post['jiekuanjine'];
                if($rea==true){
                   die("<script>alert('您的额度为".$xiane."');history.back();</script>");
                }

			    $save['loan_amount']=$post['jiekuanjine'];
                if($post['jiekuanshijian']==7){
                    $jiekuanshijian=1;
                }else if($post['jiekuanshijian']==14){
                    $jiekuanshijian=2;
                }
			    $save['loan_time']=$jiekuanshijian;    
                $save['is_loan']=1;
                $save['loan_request']=time();
                $save['loan_order']=date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
                $res=$loan->where($where)->save($save);
                if($res){
                    if($message[0]['au_request']==0){
                        $baocun['au_request']=1;
                        $baocun['lev']=0;
                        $au_request=$user->where($where)->save($baocun);
                          if($au_request)
                                    {
                    die("<script>alert('申请成功，您的的信息已进入风控审核！');window.location.href='index';</script>");
                      }else{
                        die("<script>alert('提交失败');history.back();</script>");
                      }
                  }
                }else{
                	die("<script>alert('申请失败');history.back();</script>");
                }
    	}
        $this->display('apply/index');
    }
    public function repay(){
        header("Content-type: text/html; charset=utf-8"); 
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
        $loan=M('loan');
        $where['user_name']=$_SESSION['name'];
        $data=$loan->where($where)->select();
        $this->data=$data[0];
        if($data[0]['loan_time']==1){
          $riqi=7;
        }else if($data[0]['loan_time']==2){
             $riqi=14;
        }
        $huankuanriqi=$data[0]['loan_request']+86400*$riqi;
        $this->huankuanriqi=$huankuanriqi;
        $this->assign('data',$this->kqPay());
    	$this->display('apply/repay');
    }
    public function hk(){
        $this->display('apply/hk');
    }
    public function hkxq(){
        $this->display('apply/hkxq');
    }


    public function xuqi(){
        if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
        $loan=M('loan');
        $where['user_name']=$_SESSION['name'];

        $data=$loan->where($where)->select();

        $this->data=$data[0];
        if($data[0]['loan_time']==1){
          $riqi=7;
        }else if($data[0]['loan_time']==2){
             $riqi=14;
        }

        $huankuanriqi=$data[0]['loan_request']+86400*$riqi;
        $this->huankuanriqi=$huankuanriqi; 
        
        $huankuanriqi7=$huankuanriqi+86400*7;
        $this->huankuanriqi7=$huankuanriqi7;
      
        $huankuanriqi14=$huankuanriqi+86400*14;
        $this->huankuanriqi14=$huankuanriqi14;

        $benqixifei7=$data[0]['loan_amount']*0.098;
        $this->benqixifei7=$benqixifei7;
       
        $benqixifei14=$data[0]['loan_amount']*0.15;
        $this->benqixifei14=$benqixifei14;
        $this->display('apply/xuqi');
    }



    public function zfb_hk(){
        $loan=M('loan');
        $where['user_name']=$_SESSION['name'];

       $data=$loan->where($where)->select();
       
       $this->data=$data[0];

        $this->display('apply/zfb_hk');
    }

    public function kqPay(){
        header("content-type:text/html;charset=utf8");
         $name = session('name');
        $info = M()->table('free_user user,free_loan loan')->where("user.user_name=loan.user_name and user.user_name=$name")->find();
        // ======================= 传送参数设置  开始  =====================================
        //* 表示 必填写项目.  ( )里的表示字符长度
        
        $kq_target="https://www.99bill.com/mobilegateway/recvMerchantInfoAction.htm";
        
        $kq_merchantAcctId = "1019150115901";   //*  商家用户编号     (30)
        
        $kq_inputCharset    = "1";  //   1 ->  UTF-8        2 -> GBK        3 -> GB2312   default: 1    (2)
        $kq_pageUrl     = "http://home-index.xyz/free/mobile.php/home/apply/repay_pay"; //   直接跳转页面 (256)
        $kq_bgUrl       = "http://home-index.xyz/free/mobile.php/home/apply/repay_pay"; //   后台通知页面 (256)
        $kq_version     = "mobile1.0";  //*  版本  固定值 v2.0   (10)
        $kq_language        = "1";  //*  默认 1 ， 显示 汉语   (2)
        $kq_signType        = "4";   //*  固定值 1 表示 MD5 加密方式 , 4 表示 PKI 证书签名方式   (2)
        
        $kq_payerName       = $data['u_name'];   //   英文或者中文字符   (32)
        $kq_payerContactType = "1";  //  支付人联系类型  固定值： 1  代表电子邮件方式 (2)
        $kq_payerContact   = "";    //   支付人联系方式    (50)
        $kq_orderId     = $info['loan_order']; //*  字母数字或者, _ , - ,  并且字母数字开头 并且在自身交易中式唯一  (50)
        $kq_orderAmount = $info['loan_amount']*100; //*   字符金额 以 分为单位 比如 10 元， 应写成 1000 (10)
        @$kq_orderTime      = date(YmdHis);  //*  交易时间  格式: 20110805110533
        $kq_productName = $data['user_name'];   //    商品名称英文或者中文字符串(256)
        $kq_productNum      = "";   //    商品数量  (8)
        $kq_productId       = "";   //    商品代码，可以是 字母,数字,-,_   (20) 
        $kq_productDesc = "居家必备";   //    商品描述， 英文或者中文字符串  (400)
        $kq_ext1            = "";   //    扩展字段， 英文或者中文字符串，支付完成后，按照原样返回给商户。 (128)
        $kq_ext2            = "";
        $kq_payType     = "21"; //*  固定选择值：00、15、21、21-1、21-2
        //00代表显示快钱各支付方式列表；
        //15信用卡无卡支付
        //21 快捷支付
        //21-1 代表储蓄卡快捷；21-2 代表信用卡快捷
        //*其中”-”只允许在半角状态下输入。
        
        $kq_bankId          = "";   // 银行代码 银行代码 要在开通银行时 使用， 默认不开通 (8)
        $kq_redoFlag        = "0";   // 同一订单禁止重复提交标志  固定值 1 、 0      
                                // 1 表示同一订单只允许提交一次 ； 0 表示在订单没有支付成功状态下 可以重复提交； 默认 0 
        $kq_pid         = "";   //  合作伙伴在快钱的用户编号 (30)
        
        $kq_payerIdType=""; //指定付款人
        
        $kq_payerId=$info['user_name']; //付款人标识

        $signMsg = "inputCharset=$kq_inputCharset&pageUrl=$kq_pageUrl&bgUrl=$kq_bgUrl&version=$kq_version&language=$kq_language&signType=$kq_signType&merchantAcctId=$kq_merchantAcctId&payerName=$kq_payerName&payerContactType=$kq_payerContactType&payerContact=$kq_payerContact&payerIdType=$kq_payerIdType&payerId=$kq_payerId&payerIP=&orderId=$kq_orderId&orderAmount=$kq_orderAmount&orderTime=$kq_orderTime&orderTimestamp=&productName=&productNum=&productId=&productDesc=&ext1=&ext2=&payType=$kq_payType&bankId=&cardIssuer=&cardNum=&remitType=&remitCode=&redoFlag=&pid=&submitType=&orderTimeOut=&mobileGateway=&extDataType=&extDataContent=";
        
        // ======================= 传送参数设置  结束  =====================================
        
        // ======================= 快钱 封装代码 ! ! 勿随便更改 开始  =====================================
        
        
        
            $kq_all_para =$this->kq_ck_null($kq_inputCharset,'inputCharset');
            $kq_all_para.=$this->kq_ck_null($kq_pageUrl,"pageUrl");
            $kq_all_para.=$this->kq_ck_null($kq_bgUrl,'bgUrl');
            $kq_all_para.=$this->kq_ck_null($kq_version,'version');
            $kq_all_para.=$this->kq_ck_null($kq_language,'language');
            $kq_all_para.=$this->kq_ck_null($kq_signType,'signType');
            $kq_all_para.=$this->kq_ck_null($kq_merchantAcctId,'merchantAcctId');
            $kq_all_para.=$this->kq_ck_null($kq_payerName,'payerName');
            $kq_all_para.=$this->kq_ck_null($kq_payerContactType,'payerContactType');
            $kq_all_para.=$this->kq_ck_null($kq_payerContact,'payerContact');
            $kq_all_para.=$this->kq_ck_null($kq_payerIdType,'payerIdType');
            $kq_all_para.=$this->kq_ck_null($kq_payerId,'payerId');
            $kq_all_para.=$this->kq_ck_null($kq_orderId,'orderId');
            $kq_all_para.=$this->kq_ck_null($kq_orderAmount,'orderAmount');
            $kq_all_para.=$this->kq_ck_null($kq_orderTime,'orderTime');
            $kq_all_para.=$this->kq_ck_null($kq_productName,'productName');
            $kq_all_para.=$this->kq_ck_null($kq_productNum,'productNum');
            $kq_all_para.=$this->kq_ck_null($kq_productId,'productId');
            $kq_all_para.=$this->kq_ck_null($kq_productDesc,'productDesc');
            $kq_all_para.=$this->kq_ck_null($kq_ext1,'ext1');
            $kq_all_para.=$this->kq_ck_null($kq_ext2,'ext2');
            $kq_all_para.=$this->kq_ck_null($kq_payType,'payType');
            $kq_all_para.=$this->kq_ck_null($kq_bankId,'bankId');
            $kq_all_para.=$this->kq_ck_null($kq_redoFlag,'redoFlag');
            $kq_all_para.=$this->kq_ck_null($kq_pid,'pid');
        
        
        //$kq_all_para=substr($kq_all_para,0,strlen($kq_all_para)-1);
        //$kq_all_para=substr($kq_all_para,0,-1);
        $kq_all_para=rtrim($kq_all_para,'&');
        // echo $kq_all_para;
        
        //////////////////////////////               lib  start  
        ////////  私钥加密 生成 MAC
        $priv_key = file_get_contents("http://home-index.xyz/free/Public/mobile/zf/pcarduser.pem");
    
        $pkeyid = openssl_get_privatekey($priv_key);
    
        // echo '$pkeyid='.$pkeyid.'<br>';
    
        // compute signature
        openssl_sign($kq_all_para, $signMsg, $pkeyid);
    
        // free the key from memory
        openssl_free_key($pkeyid);
    
        $kq_sign_msg = base64_encode($signMsg);
        
        $kq_get_url=$kq_target.'?'.$kq_all_para.'&signMsg='.$kq_sign_msg;
        
        // ======================= 快钱 封装代码 ! ! 勿随便更改 结束  =====================================
        $data = array(
            'kq_target'          => $kq_target,
            'kq_merchantAcctId'  => $kq_merchantAcctId,
            'kq_inputCharset'    => $kq_inputCharset,
            'kq_pageUrl'         => $kq_pageUrl,
            'kq_bgUrl'           => $kq_bgUrl,
            'kq_version'         => $kq_version,
            'kq_language'        => $kq_language,
            'kq_signType'        => $kq_signType,
            'kq_payerName'       => $kq_payerName,
            'kq_payerContactType'=> $kq_payerContactType,
            'kq_payerContact'    => $kq_payerContact,
            'kq_orderId'         => $kq_orderId,
            'kq_orderAmount'     => $kq_orderAmount,
            'kq_orderTime'       => $kq_orderTime,
            'kq_productName'     => $kq_productName,
            'kq_productNum'      => $kq_productNum,
            'kq_productId'       => $kq_productId,
            'kq_productDesc'     => $kq_productDesc,
            'kq_ext1'            => $kq_ext1,
            'kq_ext2'            => $kq_ext2,
            'kq_payType'         => $kq_payType,
            'kq_bankId'          => $kq_bankId,
            'kq_redoFlag'        => $kq_redoFlag,
            'kq_pid'             => $kq_pid,
            'kq_payerIdType'     => $kq_payerIdType,
            'kq_payerId'         => $kq_payerId,
            'kq_sign_msg'         =>$kq_sign_msg
        );
        return $data;       
    }

    public function kq_ck_null($kq_va,$kq_na){if($kq_va == ""){$kq_va="";}else{return $kq_va=$kq_na.'='.$kq_va.'&';}}
        

    public function repay_pay(){
        function kq_ck_null($kq_va,$kq_na){if($kq_va == ""){return $kq_va="";}else{return $kq_va=$kq_na.'='.$kq_va.'&';}}
        $kq_check_all_para=kq_ck_null($_GET[merchantAcctId],'merchantAcctId').kq_ck_null($_GET[version],'version').kq_ck_null($_GET[language],'language').kq_ck_null($_GET[signType],'signType').kq_ck_null($_GET[payType],'payType').kq_ck_null($_GET[bankId],'bankId').kq_ck_null($_GET[orderId],'orderId').kq_ck_null($_GET[orderTime],'orderTime').kq_ck_null($_GET[orderAmount],'orderAmount').kq_ck_null($_GET[bindCard],'bindCard').kq_ck_null($_GET[bindMobile],'bindMobile').kq_ck_null($_GET[dealId],'dealId').kq_ck_null($_GET[bankDealId],'bankDealId').kq_ck_null($_GET[dealTime],'dealTime').kq_ck_null($_GET[payAmount],'payAmount').kq_ck_null($_GET[fee],'fee').kq_ck_null($_GET[ext1],'ext1').kq_ck_null($_GET[ext2],'ext2').kq_ck_null($_GET[payResult],'payResult').kq_ck_null($_GET[errCode],'errCode');

        $trans_body=substr($kq_check_all_para,0,strlen($kq_check_all_para)-1);
        $MAC=base64_decode($_GET[signMsg]);

        $cert = file_get_contents("./99bill[1].cert.rsa.20140803.cer");
        $pubkeyid = openssl_get_publickey($cert); 
        $ok = openssl_verify($trans_body, $MAC, $pubkeyid); 

        if ($ok == 1) { 
             echo '<result>1</result><redirecturl>http://success.html</redirecturl>';
            }else{
             echo '<result>1</result><redirecturl>http://false.html</redirecturl>';
        }

    }
}