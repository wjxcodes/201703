<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\RepayModel as RepayModel;
use zmxy\ZmopSdk;
use zmxy\zmop\ZmopClient;
use zmxy\zmop\request\ZhimaDataBatchFeedbackRequest;
header("content-type:text/html;charset=utf8");
class FeedController extends BaseController {
       
      public function overdue(){
          $Model=M();
          $loan_data=$Model
                    ->table('free_user user,free_loan loan')
                    ->where('user.user_id = loan.user_id AND loan.is_loan = 1')
                    ->select();     

//如果在借款中  
    //在借款中maudit=2   is_pay=''  则为审批通过
    //maudit=2   is_pay！=''   则为已放款
//如果不在借款中
    //未反馈过的
        //如果不是在借款中就是审批否决
  $i=0;
    foreach ($loan_data as $key => $value) {
      if($value['user_name']!='18639773300'&&$value['user_name']!='18756173056'&&$value['user_name']!='17633713110'&&$value['user_name']!='15939190895'&&$value['user_name']!='15638200108'){
          if($value['is_loan']==1){
              if($value['maudit']==2){
              if($value['is_pay']!=''&&$value['is_pay']!=0){
                //不变项
              $records['records'][$i]['user_name']=$value['u_name'];               //用户名
              $records['records'][$i]['user_credentials_type']=0;                  //身份类型 0 身份证
              $records['records'][$i]['user_credentials_no']=$value['identity'];   //身份证号

/*preg_match('/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{2}$)/',$value['identity'],$match); 
          if(empty($match)){
              var_dump($value['identity']);
          }*/

              $records['records'][$i]['order_no']=$value['loan_order'];            //订单号
              $records['records'][$i]['biz_type']=1;                               //业务种类 1 贷款
              $records['records'][$i]['create_amt']=$value['loan_amount'];        //授信金额放款金额
              //变动
                   if($value['is_pay']==''||$value==0){
                       $records['records'][$i]['order_status']='01';                          //业务状态
                       $records['records'][$i]['pay_month']='';              //还款月份    未放款为空
                       $records['records'][$i]['gmt_ovd_date']=date("Y-m-d H:i:s",$value['loan_request']);    //各阶段时间日期 未放款填写请求时间
                       $records['records'][$i]['overdue_amt']='';            //当前逾期总额   未放款 为空
                       $records['records'][$i]['overdue_days']='';           //当前逾期天数   未放款 为空
                   }else{
                      $records['records'][$i]['order_status']='04';                          //业务状态
                        if($value['loan_time']==1){
                            $loan_time=7;
                        }else if($value['loan_time']==2){
                            $loan_time=14;
                        }
                      $return_arr=RepayModel::overdue_show($value['is_pay'],$loan_time,$value['renewal_days'],$value['loan_amount']);
                        if($return_arr['day']>0){
                           $records['records'][$i]['pay_month']="O";    //还款月份    已放款到还款日  为还款月份
                           $records['records'][$i]['gmt_ovd_date']=date("Y-m-d H:i:s",$return_arr['time']);    //各阶段时间日期 已放款已逾期填写应还款时间
                           $records['records'][$i]['overdue_amt']=$return_arr['overdue_money'];            //当前逾期总额   已放款 已逾期为 逾期费用
                           $records['records'][$i]['overdue_days']=$return_arr['day'];           //当前逾期天数   已放款 已逾期为 逾期天数
                        }else{
                           $records['records'][$i]['pay_month']=0;              //还款月份    已放款未到还款日   为0；
                           $records['records'][$i]['gmt_ovd_date']=date("Y-m-d H:i:s",$value['is_pay']);    //各阶段时间日期 已放款未逾期填应还款时间
                           $records['records'][$i]['overdue_amt']=0;            //当前逾期总额   已放款 未逾期为0
                           $records['records'][$i]['overdue_days']=0;           //当前逾期天数   未放款 为0
                        }
                   }//if($value['is_pay']==''||$value==0){
              $records['records'][$i]['gmt_pay']='';                                     //结清日期
              $records['records'][$i]['memo']='';
              $i+=1;
              }
              }//if($value['maudit']==2){
                  
          }//if($value['is_loan']==1){    
          }
    }//foreach ($loan_data as $key => $value) {
          
          $records=json_encode($records);
          $time=date("Ymd",time());                           
          file_put_contents("D:/feed/".$time."overdue.json",$records);
          

      } 
      public function data_deal(){         
        $Model=M();
         $record_data=$Model
                    ->table('free_user user,free_record record')
                    ->where('user.user_id = record.user_id AND feedback=0')
                    ->select();
             $i=0;
       foreach ($record_data as $key => $value) {
        if($value['user_name']!='18639773300'&&$value['user_name']!='18756173056'&&$value['user_name']!='17633713110'&&$value['user_name']!='15939190895'&&$value['user_name']!='15638200108'){
           $records['records'][$i]['user_name']=$value['u_name'];               //用户名
           $records['records'][$i]['user_credentials_type']=0;                  //身份类型 0 身份证
           $records['records'][$i]['user_credentials_no']=$value['identity'];   //身份证号
/*preg_match('/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{2}$)/',$value['identity'],$match); 
          if(empty($match)){
              var_dump($value['identity']);
          }*/
           $records['records'][$i]['order_no']=$value['loan_order'];            //订单号
           $records['records'][$i]['biz_type']=1;                               //业务种类 1 贷款
           $records['records'][$i]['order_status']='04';                          //业务状态  
           $records['records'][$i]['create_amt']=$value['pay_money'];           //授信金额放款金额 
           if($value['loan_time']==1){
                $loan_time=7;
           }else if($value['loan_time']==2){
                $loan_time=14;
           }
             
             $time=$value['pay_time']+$loan_time*86400+$value['xutime']*86400;      

             $records['records'][$i]['pay_month']="O";    //还款月份

             $is_overdue=$value['repayment_time']-$time;

             $day=ceil($is_overdue/86400);
             if($day>0){
              $records['records'][$i]['gmt_ovd_date']=date("Y-m-d H:i:s",$time);                //各阶段时间日期   
                                            
                if($day<=3){
                    $overdue_money=ceil($value['pay_money']*0.015*$day);
                }else{
                  $day_i=$day-3;
                  $overdue_money=ceil($value['pay_money']*0.02*$day_i+$value['pay_money']*0.015*3);
                }
                $records['records'][$i]['overdue_amt']=0;                                      //当前逾期总额
                $records['records'][$i]['overdue_days']=0;
             }else{

              $records['records'][$i]['gmt_ovd_date']=date("Y-m-d H:i:s",$time);                //各阶段时间日期  

              $records['records'][$i]['overdue_amt']=0;                                //当前逾期总额
              $records['records'][$i]['overdue_days']=0;
             }
             $records['records'][$i]['gmt_pay']=date("Y-m-d H:i:s",$value['repayment_time']);                                     //结清日期
           $records['records'][$i]['memo']='';
           $i+=1;
          }
         }
           
             $records=json_encode($records);       
             $time=date("Ymd",time());                           
             file_put_contents("D:/feed/".$time."records.json",$records); 
             
        
             $record=M('record');
             $where['feedback']=0;
             $save['feedback']=1;
             $record->where($where)->save($save);
      }

      public function nopass(){
          $feed=M('feed');
          $time=strtotime(date('Y-m-d',strtotime('-1 day')));
          $end_time=$time+86400;
          $where['create_time']=array(array('GT',$time),array('ELT',$end_time));
          $feed_data=$feed->where($where)->select();
          $i=0;
          foreach ($feed_data as $key => $value) {
            $a=$this->is_idcard($value['identity']);
            if($a){
              $records['records'][$i]['user_name']=$value['u_name'];             //  user_name        用户名
              $records['records'][$i]['user_credentials_type']=0;                  //身份类型 0 身份证
              $records['records'][$i]['user_credentials_no']=$value['identity'];   //身份证号
 
              $records['records'][$i]['order_no']=$value['loan_order'];            //订单号
              $records['records'][$i]['biz_type']=1;                               //业务种类 1 贷款
              $records['records'][$i]['order_status']='02';                          //业务状态
              $records['records'][$i]['create_amt']=$value['loan_amount'];     //  create_amt              授信金额放款金额 
              $records['records'][$i]['pay_month']='';                   //还款月份 
              $records['records'][$i]['gmt_ovd_date']=date("Y-m-d H:i:s",$value['create_time']); //  gmt_ovd_date 各阶段时间日期  
              $records['records'][$i]['overdue_amt']='';                                //当前逾期总额
              $records['records'][$i]['overdue_days']='';         //  overdue_days            当前逾期天数
              $records['records'][$i]['gmt_pay']='';       //  gmt_pay                 结清日期
              $records['records'][$i]['memo']='';              //  memo
              $i+=1;
            }
          }
          $records=json_encode($records);
          $time=date("Ymd",time());
          file_put_contents("D:/feed/".$time."nopass.json",$records); 

      }

      public function is_idcard( $id ) 
{ 
  $id = strtoupper($id); 
  $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/"; 
  $arr_split = array(); 
  if(!preg_match($regx, $id)) 
  { 
    return FALSE; 
  } 
  if(15==strlen($id)) //检查15位 
  { 
    $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/"; 
  
    @preg_match($regx, $id, $arr_split); 
    //检查生日日期是否正确 
    $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4]; 
    if(!strtotime($dtm_birth)) 
    { 
      return FALSE; 
    } else { 
      return TRUE; 
    } 
  } 
  else      //检查18位 
  { 
    $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/"; 
    @preg_match($regx, $id, $arr_split); 
    $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4]; 
    if(!strtotime($dtm_birth)) //检查生日日期是否正确 
    { 
      return FALSE; 
    } 
    else
    { 
      //检验18位身份证的校验码是否正确。 
      //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。 
      $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); 
      $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); 
      $sign = 0; 
      for ( $i = 0; $i < 17; $i++ ) 
      { 
        $b = (int) $id{$i}; 
        $w = $arr_int[$i]; 
        $sign += $b * $w; 
      } 
      $n = $sign % 11; 
      $val_num = $arr_ch[$n]; 
      if ($val_num != substr($id,17, 1)) 
      { 
        return FALSE; 
      } //phpfensi.com 
      else
      { 
        return TRUE; 
      } 
    } 
  } 
}
      public function index(){
        $time=date("Ymd",time());                           
        
        //$url="D:/feed/".$time."overdue.json";
        //$url="D:/feed/".$time."records.json";
        $url="D:/feed/".$time."nopass.json";






        //$url="D:/new_feed/user_records.json";
        //$url="D:/new_feed/identity_records.json";
        //$url="D:/new_feed/new_records1.json";
        //$url="D:/new_feed/new_records2.json";
        //$url="D:/new_feed/new_records3.json";
        //$url="D:/new_feed/new_records4.json";

          $this->feedback($url);
       }
      public function feedback($url){
	      	 $gatewayUrl = "https://zmopenapi.zmxy.com.cn/openapi.do";
		    //商户私钥文件
		     $privateKeyFile = "D:/keyi/rsa_private_key.pem";
		    //芝麻公钥文件
		     $zmPublicKeyFile = "D:/keyi/sjfk_public_key.pem";
		    //数据编码格式
		     $charset = "UTF-8";
		    //芝麻分配给商户的 appId
		     $appId = "1002964";

	    
	          $client = new ZmopClient($gatewayUrl,$appId,$charset,$privateKeyFile,$zmPublicKeyFile);
	          $request = new ZhimaDataBatchFeedbackRequest();
	          $request->setChannel("apppc");
            $request->setPlatform("zmop");
            $request->setFileType("json_data");// 必要参数 
            $request->setFileCharset("UTF-8");// 必要参数 
            $request->setRecords("100");// 必要参数 
            $request->setColumns("user_name,user_credentials_type,user_credentials_no,order_no,biz_type,create_amt,order_status,pay_month,gmt_ovd_date,overdue_amt,overdue_days,gmt_pay,memo");// 必要参数 
            $request->setPrimaryKeyColumns("order_no,pay_month");// 必要参数 
            //$request->setFileDescription("文件描述信息");// 
            $request->setTypeId("1002203-default-order");// 必要参数 
            //$request->setBizExtParams("{\"extparam1\":\"value1\"}");// 
            $request->setFile($url);// 必要参数 
            $response = $client->execute($request);
            echo json_encode($response);
      }
      public function linux(){
           $gatewayUrl = "https://zmopenapi.zmxy.com.cn/openapi.do";
        //商户私钥文件
         $privateKeyFile = "/usr/keyi/rsa_private_key.pem";
        //芝麻公钥文件
         $zmPublicKeyFile = "/usr/keyi/sjfk_public_key.pem";
        //数据编码格式
         $charset = "UTF-8";
        //芝麻分配给商户的 appId
         $appId = "1002964";

      
            $client = new ZmopClient($gatewayUrl,$appId,$charset,$privateKeyFile,$zmPublicKeyFile);

            $request = new ZhimaDataBatchFeedbackRequest();
            $request->setChannel("apppc");
            $request->setPlatform("zmop");
            $request->setFileType("json_data");// 必要参数 
            $request->setFileCharset("UTF-8");// 必要参数 
            $request->setRecords("100");// 必要参数 
            $request->setColumns("user_name,user_credentials_type,user_credentials_no,order_no,biz_type,create_amt,order_status,pay_month,gmt_ovd_date,overdue_amt,overdue_days,gmt_pay,memo");// 必要参数 
            $request->setPrimaryKeyColumns("order_no,pay_month");// 必要参数 
            //$request->setFileDescription("文件描述信息");// 
            $request->setTypeId("1002203-default-test");// 必要参数 
            //$request->setBizExtParams("{\"extparam1\":\"value1\"}");// 
            $request->setFile("/usr/local/nginx/html/test111111.json");// 必要参数 
            $response = $client->execute($request);
            echo json_encode($response);
    
      }
      
      
      public function record(){
        $record=M('record');
        $where['feedback']=1;
        $save['feedback']=0;
       $record->where($where)->save($save);
      }

      



       public function test110(){
          $Model=M();
          $loan_data=$Model
                    ->table('free_user user,free_loan loan')
                    ->where('user.user_id = loan.user_id AND user.user_name=15738849971')
                    ->find();     


    
              $records['records'][0]['user_name']=$loan_data['u_name'];               //用户名
              $records['records'][0]['user_credentials_type']=0;                  //身份类型 0 身份证
              $records['records'][0]['user_credentials_no']=$loan_data['identity'];   //身份证号
              $records['records'][0]['order_no']=$loan_data['loan_order'];            //订单号
              $records['records'][0]['biz_type']=1;                               //业务种类 1 贷款
              $records['records'][0]['create_amt']=$loan_data['loan_amount'];        //授信金额放款金额
              

              $records['records'][0]['order_status']='04';                          //业务状态
         
              $return_arr=RepayModel::overdue_show($loan_data['is_pay'],$loan_time,$loan_data['renewal_days'],$loan_data['loan_amount']);
          
             $records['records'][0]['pay_month']=0;    //还款月份    已放款到还款日  为还款月份
             
             $records['records'][0]['gmt_ovd_date']=date("Y-m-d H:i:s",$loan_data['is_pay']);    //各阶段时间日期 已放款已逾期填写应还款时间
             $records['records'][0]['overdue_amt']=0;            //当前逾期总额   已放款 已逾期为 逾期费用
             $records['records'][0]['overdue_days']=0;           //当前逾期天数   已放款 已逾期为 逾期天数
         
                   
              $records['records'][0]['gmt_pay']='';                                     //结清日期
              $records['records'][0]['memo']='';
          $records=json_encode($records);
          file_put_contents("D:/test111111.json",$records);
      }
      
}