<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\RepayModel as RepayModel;
use Home\Model\MoneyModel as MoneyModel;
header("Content-Type:text/html;charset=utf-8");
class RongController extends BaseController{
    public function index(){
            $data = M()
            ->table('free_user user,free_loan loan')
            ->where("user.user_id=loan.user_id and loan.is_pay>0")
            ->select();
                foreach ($data as $k => $v) {
                   // 手机号(用户名) 持卡人姓名   银行卡绑定手机号    身份证号    银行卡号    借款金额    打款金额         借款天数    打款时间    借款期限    到期时间 逾期天数
                      if($v['loan_time']==1){
                           $loan_time=7;
                      }else if($v['loan_time']==2){
                           $loan_time=14;
                      }
                      
                      $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']     );
                      $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
        
                      if($return_arr['day']>=1 && $return_arr['day']<=3){
                          $arr[$k]['shouxufei']=$shouxufei;
                          $arr[$k]['day']=$return_arr['day'];
                          $arr[$k]['loan_time'] = $loan_time;
                          $arr[$k]['due_time']=$return_arr['time'];
                          $arr[$k]['overdue_money']=$return_arr['overdue_money'];
                          $arr[$k]['user_id'] = $v['user_id'];
                          $arr[$k]['loan_order']=$v['loan_order'];
                          $arr[$k]['user_name'] = $v['user_name'];
                          $arr[$k]['bank_name'] = $v['bank_name'];
                          $arr[$k]['bank_tel'] = $v['bank_tel'];
                          $arr[$k]['query_message'] = $v['query_message'];
                          $arr[$k]['open_id'] = $v['open_id'];
                          $arr[$k]['identity'] = $v['identity'];
                          $arr[$k]['bank_card'] = $v['bank_card'];
                          $arr[$k]['loan_amount'] = $v['loan_amount'];
                          $arr[$k]['is_pay'] = $v['is_pay'];
                          $arr[$k]['renewal_days'] = $v['renewal_days'];
                          $sum+=1;
        
                          if($return_arr['day']>=(-1)&&$return_arr['day']<1){
                            $pre_sum+=1;
                          }
                          if($return_arr['day']>=1){
                            $heji_money+=$shouxufei;
                            $money_sum+=$v['loan_amount'];
                            $overdue_sum+=1;
                            $overdue_sum_money+=$return_arr['overdue_money'];
                          }
                          if($return_arr['day'] > 0 && $return_arr['day']<4){
                            $let_sum+=1;
                          }
                      }
                }
                    $page_length=20;
                    $num=count($arr);
                    $page_total=new \Think\Page($num,$page_length);
                    $cart_data=array_slice($arr,$page_total->firstRow,$page_total->listRows);
                    $show=$page_total->show();
                    $this->assign('heji_money',$heji_money);
                    $this->assign('overdue_sum_money',$overdue_sum_money);
                    $this->assign('money_sum',$money_sum);
                    $this->assign('let_sum',$let_sum);
                    $this->assign('sum',$sum);
                    $this->assign('pre_sum',$pre_sum);
                    $this->assign('overdue_sum',$overdue_sum);
                    $this->assign('page',$show);
                    $this->assign('arr',$cart_data);
                    $this->assign('count',$count);
                    $this->display();
    }


public function index2(){
            $data = M()
            ->table('free_user user,free_loan loan')
            ->where("user.user_id=loan.user_id and loan.is_pay>0")
            ->select();
                foreach ($data as $k => $v) {
                   // 手机号(用户名) 持卡人姓名   银行卡绑定手机号    身份证号    银行卡号    借款金额    打款金额         借款天数    打款时间    借款期限    到期时间 逾期天数
                      if($v['loan_time']==1){
                           $loan_time=7;
                      }else if($v['loan_time']==2){
                           $loan_time=14;
                      }
                      
                      $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']     );
                      $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
        
                      if($return_arr['day']>=1 && $return_arr['day']<=3){
                          $arr[$k]['shouxufei']=$shouxufei;
                          $arr[$k]['day']=$return_arr['day'];
                          $arr[$k]['loan_time'] = $loan_time;
                          $arr[$k]['due_time']=$return_arr['time'];
                          $arr[$k]['overdue_money']=$return_arr['overdue_money'];
                          $arr[$k]['user_id'] = $v['user_id'];
                          $arr[$k]['loan_order']=$v['loan_order'];
                          $arr[$k]['user_name'] = $v['user_name'];
                          $arr[$k]['bank_name'] = $v['bank_name'];
                          $arr[$k]['bank_tel'] = $v['bank_tel'];
                          $arr[$k]['query_message'] = $v['query_message'];
                          $arr[$k]['open_id'] = $v['open_id'];
                          $arr[$k]['identity'] = $v['identity'];
                          $arr[$k]['bank_card'] = $v['bank_card'];
                          $arr[$k]['loan_amount'] = $v['loan_amount'];
                          $arr[$k]['is_pay'] = $v['is_pay'];
                          $arr[$k]['renewal_days'] = $v['renewal_days'];
                          $sum+=1;
        
                          if($return_arr['day']>=(-1)&&$return_arr['day']<1){
                            $pre_sum+=1;
                          }
                          if($return_arr['day']>=1){
                            $heji_money+=$shouxufei;
                            $money_sum+=$v['loan_amount'];
                            $overdue_sum+=1;
                            $overdue_sum_money+=$return_arr['overdue_money'];
                          }
                          if($return_arr['day'] > 0 && $return_arr['day']<4){
                            $let_sum+=1;
                          }
                      }
                }
                    $page_length=15;
                    $num=count($arr);
                    $page_total=new \Think\Page($num,$page_length);
                    $cart_data=array_slice($arr,$page_total->firstRow,$page_total->listRows);
                    $show=$page_total->show();
                    $this->assign('heji_money',$heji_money);
                    $this->assign('overdue_sum_money',$overdue_sum_money);
                    $this->assign('money_sum',$money_sum);
                    $this->assign('let_sum',$let_sum);
                    $this->assign('sum',$sum);
                    $this->assign('pre_sum',$pre_sum);
                    $this->assign('overdue_sum',$overdue_sum);
                    $this->assign('page',$show);
                    $this->assign('arr',$cart_data);
                    $this->assign('count',$count);
                    $this->display();
    }

    

    // public function export(){
    //     $data = M()
    //         ->table('free_user user,free_loan loan')
    //         ->where("user.user_id = loan.user_id AND loan.is_pay !=0")
    //         ->select();
    //     foreach ($data as $k => $v) {
    //      // 手机号(用户名) 持卡人姓名   银行卡绑定手机号    身份证号    银行卡号    借款金额    打款金额         借款天数    打款时间    借款期限    到期时间 逾期天数
    //         if($v['loan_time']==1){
    //              $loan_time=7;
    //         }else if($v['loan_time']==2){
    //              $loan_time=14;
    //         }
                      
    //   $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']     );
    //   $shouxufei=MoneyModel::shouxufei($v['loan_time'],$v['loan_amount'],$v['field']);
    //                   //手机号(用户名)  持卡人姓名 银行卡绑定手机号  身份证号  银行卡号  借款金额  打款时间  借款期限  到期时间  逾期天数  本息  逾期费用
    //         if($return_arr['time']<time()){
    //             $arr[$k]['user_name']=$v['user_name']; 
    //             $arr[$k]['bank_name']=$v['bank_name'];

    //             $arr[$k]['linkman_name']=$v['linkman_name'];
    //             $arr[$k]['linkman_tel']=$v['linkman_tel'];

    //             $arr[$k]['bank_tel']=$v['bank_tel']; 
    //             $arr[$k]['identity']=$v['identity'];
    //             $arr[$k]['bank_card']=$v['bank_card'];
    //             $arr[$k]['loan_amount']=$v['loan_amount'];
    //             $arr[$k]['is_pay']=date('Y年m月d日',$v['is_pay']);
    //             $arr[$k]['loan_time']=$loan_time;
    //             $arr[$k]['time']=date('Y年m月d日',$return_arr['time']);
    //             $arr[$k]['day']=$return_arr['day'];
    //             $arr[$k]['loan_amounts']=$v['loan_amount']+$shouxufei;
    //             $arr[$k]['overdue_money']=$return_arr['overdue_money'];

    //         }
    //             }
    //     $this->goods_export($arr);

    // }

    public function export_pre(){
        $data = M()
            ->table('free_user user,free_loan loan')
            ->where("user.user_id = loan.user_id AND loan.is_pay !=0")
            ->select();
        foreach ($data as $k => $v) {
         // 手机号(用户名) 持卡人姓名   银行卡绑定手机号    身份证号    银行卡号    借款金额    打款金额         借款天数    打款时间    借款期限    到期时间 逾期天数
            if($v['loan_time']==1){
                 $loan_time=7;
            }else if($v['loan_time']==2){
                 $loan_time=14;
            }
                      
      $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']     );
      $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
                      //手机号(用户名)  持卡人姓名 银行卡绑定手机号  身份证号  银行卡号  借款金额  打款时间  借款期限  到期时间  逾期天数  本息  逾期费用
            if($return_arr['day'] > (0) && $return_arr['day'] <= (3)){
                $arr[$k]['user_name']=$v['user_name']; 
                $arr[$k]['bank_name']=$v['bank_name'];

                $arr[$k]['linkman_name']=$v['linkman_name'];
                $arr[$k]['linkman_tel']=$v['linkman_tel'];

                $arr[$k]['bank_tel']=$v['bank_tel']; 
                $arr[$k]['identity']=$v['identity'];
                $arr[$k]['bank_card']=$v['bank_card'];
                $arr[$k]['loan_amount']=$v['loan_amount'];
                $arr[$k]['is_pay']=date('Y年m月d日',$v['is_pay']);
                $arr[$k]['loan_time']=$loan_time;
                $arr[$k]['time']=date('Y年m月d日',$return_arr['time']);
                $arr[$k]['day']=$return_arr['day'];
                $arr[$k]['loan_amounts']=$v['loan_amount']+$shouxufei;
                $arr[$k]['overdue_money']=$return_arr['overdue_money'];

            }
                }
        $this->goods_export($arr);

    }

    public function goods_export($goods_list){
        $goods_list = array_merge($goods_list);
        $data = array();
        foreach ($goods_list as $k=>$goods_info){
            $data[$k][user_name] = " $goods_info[user_name]";
            $data[$k][bank_name] = " $goods_info[bank_name]";
            $data[$k][bank_tel] = " $goods_info[bank_tel]";
            $data[$k][identity] = " $goods_info[identity]";
            $data[$k][bank_card] = " $goods_info[bank_card]";

            $data[$k][loan_amount] = " $goods_info[loan_amount]";
            $data[$k][is_pay] = " $goods_info[is_pay]";
            $data[$k][loan_time] = " $goods_info[loan_time]";
            $data[$k][time] = " $goods_info[time]";
            $data[$k][day] = " $goods_info[day]";

            $data[$k][loan_amounts] = " $goods_info[loan_amounts]";
            $data[$k][overdue_money] = " $goods_info[overdue_money]";

            $data[$k][linkman_name] = " $goods_info[linkman_name]";
            $data[$k][linkman_tel] = " $goods_info[linkman_tel]";
            
        }

        foreach ($data as $field=>$v){
            if($field == 'user_name'){
                $headArr[]='手机号(用户名)';
            }
            if($field == 'bank_name'){
                $headArr[]='持卡人姓名';
            }
            if($field == 'bank_tel'){
                $headArr[]='银行卡绑定手机号';
            }
            if($field == 'identity'){
                $headArr[]='身份证号';
            }
            if($field == 'bank_card'){
                $headArr[]='银行卡号';
            }
            if($field == 'loan_amount'){
                $headArr[]='借款金额';
            }

//手机号(用户名)  持卡人姓名 银行卡绑定手机号  身份证号  银行卡号  借款金额  打款时间  借款期限  到期时间  逾期天数  本息  逾期费用
            if($field == 'is_pay'){
                $headArr[]='打款时间';
            }
            if($field == 'loan_time'){
                $headArr[]='借款期限';
            }


            if($field == 'time'){
                $headArr[]='到期时间';
            }
            if($field == 'day'){
                $headArr[]='逾期天数';
            }
            if($field == 'loan_amounts'){
                $headArr[]='本息';
            }
            if($field == 'overdue_money'){
                $headArr[]='逾期费用';
            }

            if ($field == 'linkman_name') {
                $headArr[]='紧急联系人姓名';
            }
            if ($field == 'linkman_tel') {
                $headArr[]='紧急联系人电话';
            }

        }

        $filename="逾期表".date('Y_m_d',time());

        $this->getExcel($filename,$headArr,$data);
    }


    public function getExcel($fileName,$headArr,$data){
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
                $objActSheet->setCellValue($j.$column, $value);
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


/*    public function search($start,$end){
        $data = M()
            ->table('free_user user,free_loan loan')
            ->where("user.user_id = loan.user_id AND loan.is_pay !=0 AND loan.overday_time >= ".$start." AND loan.overday_time <= ".$end."")
            ->select();
            return $data;
    }
    public function collection(){
        $data = M()
        ->table('free_user user,free_loan loan')
        ->where("user.user_id=loan.user_id and loan.is_pay>0")
        ->select();
            foreach ($data as $k => $v) {
               if($v['loan_time']==1){
                       $loan_time=7;
                }else if($v['loan_time']==2){
                       $loan_time=14;
                }
                  
                $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
                $shouxufei=MoneyModel::shouxufei($v['loan_time'],$v['loan_amount'],$v['field']);
                if($return_arr['day'] > 0 && $return_arr['day']<4){
                    $time = date("m月d日",$return_arr['time']);
                    $money=$v['loan_amount']+$shouxufei;
                    $end_money=$money+$return_arr['overdue_money'];
                    $rongs=ceil($v['loan_amount']*0.015);
                    $map['user_name'] = $v['user_name'];
                    $data['query_message'] = 1;
                    M('Loan')->where($map)->save($data);
                    $this->codes($v['bank_name'],$v['user_name'],$v['loan_amount'],$return_arr['day'],$rongs,$end_money);
                }
            }

        $this->redirect("Home/Overdue/index");
    }
    public function precollection(){
         $data = M()
        ->table('free_user user,free_loan loan')
        ->where("user.user_id=loan.user_id and loan.is_pay>0")
        ->select();
            foreach ($data as $k => $v) {
               if($v['loan_time']==1){
                       $loan_time=7;
                }else if($v['loan_time']==2){
                       $loan_time=14;
                }
                  
                $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
                $shouxufei=MoneyModel::shouxufei($v['loan_time'],$v['loan_amount'],$v['field']);
                if($return_arr['day']>=(-1)&&$return_arr['day']<1){
                    $time = date("m月d日",$return_arr['time']);
                    $money=$v['loan_amount']+$shouxufei;
                    $map['user_name'] = $v['user_name'];
                    $data['query_message'] = 1;
                    M('Loan')->where($map)->save($data);
                    $this->code($v['bank_name'],$v['user_name'],$money,$time);
                }
            }
       $this->redirect("Home/Overdue/index");
    }

public function send_note(){
        $data = M()
        ->table('free_user user,free_loan loan')
        ->where("user.user_id=loan.user_id and loan.is_pay>0")
        ->select();
            foreach ($data as $k => $v) {
                   if($v['loan_time']==1){
                           $loan_time=7;
                    }else if($v['loan_time']==2){
                           $loan_time=14;
                    }
                      
                    $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
                    $shouxufei=MoneyModel::shouxufei($v['loan_time'],$v['loan_amount'],$v['field']);
                    $money=$shouxufei+$v['loan_amount'];
                    $end_money=$money+$return_arr['overdue_money'];
                    $time = date("Y年m月d日",$return_arr['time']);
                    if($return_arr['day'] > 6){
                    echo  "【蜻蜓白卡】友情提示：".$v['u_name']."，身份证号：".$v['identity']." ；您于2017年04月15日的借款".$v['money']."（".$v['loan_amount']."+".$shouxufei."）元，于".$time."到期；截止今日您已逾期".$return_arr['day']."天，逾期费用为".$return_arr['overdue_money']."元；应还金额为".$end_money."元。请尽快还款，逾期不还后果十分严重望诚信自重。";
                    echo "<br/>";
                    echo "<br/>";

                    }
            }
         $this->display();
    }
    public function code($name,$mobile,$money,$time){       
        $data = "尊敬的".$name."先生（女士)，".$time."是您的还款日，本期应还".$money."元。请在微信公众号“蜻蜓白卡”中按时还款，以免逾期，如果您还全款困难，您可以在借款详情界面进行续期哦！如已还款请忽略此消息。";
        $post_data = array();
        $post_data['un'] ="N8058379";//账号
        $post_data['pw'] = "ugn0bO9GMf5b0e";//密码
        $post_data['msg']=$data; 
        $post_data['phone'] ="$mobile";//手机
        $post_data['rd']=1;
        $url='http://sms.253.com/msg/send'; 
        $res=$this->http_request($url,http_build_query($post_data));
    }
    public function codes($name,$mobile,$loan_amount,$day,$rongs,$money){       
        $data = $name."您好，蜻蜓善意提醒，您".$loan_amount."元借款已经逾期".$day."天，3天容时期内每天滞纳金".$rongs."元，今天您应还款金额为".$money."元，请尽快在微信公众号“蜻蜓白卡”中还款，以免影响您的人民银行个人征信记录，给您未来的生活带来困扰！";
        $post_data = array();
        $post_data['un'] ="N8058379";//账号
        $post_data['pw'] = "ugn0bO9GMf5b0e";//密码
        $post_data['msg']=$data; 
        $post_data['phone'] ="$mobile";//手机
        $post_data['rd']=1;
        $url='http://sms.253.com/msg/send'; 
        $res=$this->http_request($url,http_build_query($post_data));
    }

    public function http_request($url,$data = null){
                            if(function_exists('curl_init')){
                                $curl = curl_init();
                                curl_setopt($curl, CURLOPT_URL, $url);
                               
                                if (!empty($data)){
                                    curl_setopt($curl, CURLOPT_POST, 1);
                                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                                }
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                $output = curl_exec($curl);
                                curl_close($curl);
                                
                            
                                $result=preg_split("/[,\r\n]/",$output);

                                if($result[1]==0){
                                      return "curl success";
                                }else{
                                      return "curl error".$result[1];
                                }
                            }elseif(function_exists('file_get_contents')){
                                
                                $output=file_get_contents($url.$data);
                                $result=preg_split("/[,\r\n]/",$output);
                            
                                if($result[1]==0){
                                      return "success";
                                }else{
                                      return "error".$result[1];
                                }
                                
                                
                            }else{
                                return false;
                            } 
                            
                        }


public function zhengxin(){
        layout(false);
        $user_id = $_GET['user_id'];
        
        $creditInfo = M()->table('free_user user,free_credit credit')
                             ->where("user.user_id = credit.user_id AND user.user_id = $user_id")
                             ->order('credit.create_time desc')
                             ->select();
        $creditInfo1 = M()->table('free_credit credit,free_loan loan')
                             ->where("credit.user_id = loan.user_id AND loan.user_id = $user_id")
                             // ->order('credit.create_time desc')
                             ->select();
      
         if($creditInfo1[0]['loan_time']==1){
                   $loan_time=7;
              }else if($creditInfo1[0]['loan_time']==2){
                   $loan_time=14;
              }
        $return_arr=RepayModel::overdue_show($creditInfo1[0]['is_pay'],$loan_time,$creditInfo1[0]['renewal_days'],$creditInfo1[0]['loan_amount']);
        $info = $creditInfo[0];
        $info1 = $creditInfo1[0];
        $info2 = $return_arr;
        // 身份证号转日期
        $data_n = substr($info['identity'],6,4);
        $data_y = substr($info['identity'],10,2);
        $data_r = substr($info['identity'],12,2);
        
        
        $biz_no=substr($info['biz_no'],2);
        $biz_no="QT".$biz_no;
        $identity=(int)substr($info['identity'],16,1);
        if($identity%2==0){
         $identity="女";
        }else{
            $identity="男";
    }
        $this->assign(array(   
                        "user_name"=>$info['user_name'],
                        "u_name"=>$info['u_name'],
                        "credit_order"=>$biz_no,
                        "sex"=>$identity,
                        "identity"=>$info['identity'],
                        "bank_card"=>$info['bank_card'],
                        "bank_tel"=>$info['bank_tel'],
                        "linkman_tel"=>$info['linkman_tel'],
                        "linkman_name"=>$info['linkman_name'],
                        "hit"=>$info['hit'],
                        "data_n"=>$data_n,
                        "data_y"=>$data_y,
                        "data_r"=>$data_r,
                        "loan_amount"=>$info1['loan_amount'],
                        "overdue_money"=>$return_arr['overdue_money'],
                        "loan_request"=>date("Y-m-d H:i",$info1['loan_request']),
                        "day"=>$return_arr['day'],
                        "loan_time"=>$info1['loan_time'],
                        )); // 赋值输出
       
        $this->display();
    }


*/
}