<?php 
/*
*简化版信息审核
*/
namespace Home\Controller;
use Think\Controller;
use Home\Model\WandaModel as WandaModel;
use Home\Model\MoneyModel as MoneyModel;
use Home\Model\ApixModel as ApixModel;
use Home\Model\RepayModel as RepayModel;
use Home\Model\SurveyModel as SurveyModel;
use Home\Model\JdModel as JdModel;
use Home\Model\WeixinModel as WeixinModel;
header("content-type:text/html;charset=utf8");
class SurveyController extends BaseController{   
    public function curlGet($wd,$jd){
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, "https://way.jd.com/jisuapi/geoconvert?lat=$wd&lng=$jd&type=baidu&appkey=80ad75c777ec75502fae571565dbb773");
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        $return_data = json_decode($data,true);
        if($return_data['msg'] =="查询成功" && $return_data['code'] == "10000"){
            $location = $return_data['result']['result']['country']."，".$return_data['result']['result']['address']."，".$return_data['result']['result']['description'];
        }else{
            $location = "未授权地理位置";
        }
        return $location;
    }
    public function linksend(){
        $get=I('get.');
        $where['user_name']=$get['id'];
        $loan=M('loan');
        $user=M('user');
        $user_data=$user->where($where)->find();
        $loan_data=$loan->where($where)->find();
        $access_token = WeixinModel::getToken();
        $contnet="您好，很抱歉因您紧急联系人填写错误，未能通过我们的风控审核！";
        $a=WeixinModel::sendWeixin($user_data['open_id'],$contnet,$loan_data['loan_amount'],date('Y-m-d H:i',$loan_data['loan_request']),$access_token);
        die("<script>history.back();</script>");
    }   

 	public function index(){

        $user_model=M('user');
        $position_model=M('Position');
        $loan=M('loan');
        $get=I('get.');
        $where['user_name']=$get['id'];
        $user_data = $user_model->where($where)->find();
        $loan_data = $loan->where($where)->find();
        $position_data = $position_model->where($where)->order('id desc')->find();
        $location_data = $this->curlGet($position_data['wd'],$position_data['jd']);
        $this->assign("location_data",$location_data);
        $this->assign("loan_data",$loan_data);
        $this->assign("user_name",$get['id']);
        $this->assign('user_data',$user_data);
        /*紧急联系人查询*/
        $people1 = $this->inspectLinkman($user_data['linkman_tel']);
        $people2 = $this->inspectLinkman($user_data['clan_tel']);
        $this->assign('people1',$people1);
        $this->assign('people2',$people2);
/*微信 昵称*/

        $open_id=$get['open_id'];
        $access_token=WeixinModel::getToken();
        $weixin=WeixinModel::getinfo($access_token,$open_id);
        if($weixin['subscribe']!=1){
            $this->assign('subscribe',$weixin['subscribe']);
        }
        
        $this->assign("weixin",$weixin);
/*借款记录*/

        $record_arr=$this->record($where);
        $this->borrow=$record_arr['borrow'];
        $this->new_record_data=$record_arr['new_record_data'];
        $this->frist_overdue=$record_arr['frist_overdue'];

/*芝麻信用*/

        $credit_arr=$this->credit($get,$where);
        $this->assign("hit_detail",$credit_arr['hit_detail']);
        $this->assign("three_ele",$credit_arr['three_ele']);
        $this->assign("ele",$credit_arr['ele']);
        $this->assign("clan",$credit_arr['clan']);
        $this->assign("match",$credit_arr['match']);
        $this->assign("credit_data",$credit_arr['credit_data']);

/*京东消费*/

        $jingdong=M('jingdong');
        $jingdong_data=$jingdong->where($where)->find();
        if($jingdong_data['is_collect']==1){
            if($jingdong_data['token']){
                $response=JdModel::jd_data($jingdong_data['token']);
                $this->all_money=$all_money;
                $this->response=$response;
            }else{
                
            }
        }else{
            $this->nit="该用户未认证京东";
        }
/* 淘宝数据开始 */
        $taobao_arr=$this->taobao($where,$user_data,$get);
        $this->assign("addrs",$taobao_arr['addrs']);
        $this->assign("lost_money",$taobao_arr['lost_money']);
        $this->assign("arr",$taobao_arr['arr']);
        $this->assign("personalInfo",$taobao_arr['personalInfo']);
        $this->assign("accountSafeInfo",$taobao_arr['accountSafeInfo']);
        $this->assign("bindAccountInfo",$taobao_arr['bindAccountInfo']);
        $this->assign("t_hint",$taobao_arr['t_hint']);
/* 淘宝数据结束 */

/* 通讯行为 */
        $tel_record_model = M('Tel_record');
        $tel_record_data = $tel_record_model->where($where)->find();
        $request_id=strlen($tel_record_data['request_id']);
        if($request_id==36 || $tel_record_data['request_id']==''){

/*APIX 通讯记录详情开始*/
            $apix=M('apix');
            
                $apix_data=$apix->where($where)->order('id desc')->find();
                $apix_data['phoneInfo']=json_decode($apix_data['phone'],1);
                $apix_data['callRecordsInfo']=json_decode($apix_data['callrecords'],1);
                $i=0;
                foreach ($apix_data['callRecordsInfo'] as $key => $value){
                    preg_match('/^1[34578]\d{9}$/A',$value['phoneNo'],$match);
                    if(!empty($match)){
                        $i+=1;
                        if($i<=10){
                            $connTime+=$value['connTime'];
                        }
                        if($key<50){

                            $apix_return['valid']+=1;
                        }
                        
                        if($value['connTimes']>=30){
                            $apix_return['tel_num']+=1;
                        }

                        if($value['calledTimes']!=0 && $value['callTimes']!=0){
                            $apix_return['interflow']+=1;
                        }
                    }
                    if($value['identifyInfo']!=''){
                        $apix_return['special'][]=$value;
                    }
                    if($user_data['linkman_tel']==$value['phoneNo']){
                        $apix_return['linkman_tel']=$value;
                    }
                    if($user_data['clan_tel']==$value['phoneNo']){
                        $apix_return['clan_tel']=$value;
                    }
                }

                $this->assign("apix_return",$apix_return);
            
            $tel_valid_time=$connTime/600;
            $urgency_tel=$apix_return['linkman_tel']['connTimes'];
            $tel_time=$apix_data['phoneInfo']['netAge'];
            $valid_tel=$apix_return['valid'];
            $interflow_tel=$apix_return['interflow'];
            $this->assign("apix_data",$apix_data);
            /*if($get['xxx']!=1){
                if($credit_arr['credit_data']['zm_score']>750){
                    $this->display('Survey/apix751');
                }else if($credit_arr['credit_data']['zm_score']>700&&$credit_arr['credit_data']['zm_score']<751){
                    $this->display('Survey/apix700');
                }else if($credit_arr['credit_data']['zm_score']>599&&$credit_arr['credit_data']['zm_score']<651){
                    $this->display('Survey/apix600');
                }else if($credit_arr['credit_data']['zm_score']>650&&$credit_arr['credit_data']['zm_score']<701){
                    $this->display('Survey/apix650');
                }else{
                    $this->display('Survey/index');
                }
            }else{*/
                
                
            /*}*/
 /*APIX 通讯记录详情结束*/
        }else if($request_id==40){
            $wdinfo_model = M('wdinfo');
            $result = $wdinfo_model->where($where)->find();

            $casic = json_decode($result['casic'],true);

            $result = json_decode($result['tel_info'],true);
            if($result==''){
                $hint="通讯详情异常";
            }
            $result = $this->order($result,$lev='call_len');
            $i=0;
            foreach ($result as $key => $value) {
                if($key<100){
                   preg_match('/^1[34578]\d{9}$/A',$value['phone_num'],$match);
                    if(!empty($match)){
                        $i+=1;
                        if($i<=10){
                            $call_len+=$value['call_len'];
                        }
                        if($key<50){
                            $wada_return['valid']+=1;
                        }
                    }
                    if($user_data['linkman_tel']==$value['phone_num']){
                        $wada_return['linkman_tel']=$value;
                    }
                    if($user_data['clan_tel']==$value['phone_num']){
                        $wada_return['clan_tel']=$value;
                    }
                }
            }
            foreach ($casic as $key => $value) {
                if($value['check_point_cn']=="号码使用时间"){
                    $evidence=strstr($value['evidence'],"使用");
                    $tel_time=mb_substr($evidence,2,7,"UTF-8");
                }
                if($value['check_point_cn']=="互通过电话的号码数量"){
                    $interflow_tel=strstr($value['evidence'],"有");
                }
            }
            $tel_valid_time=$call_len/10;
            $urgency_tel=$wada_return['linkman_tel']['call_in_cnt']+$wada_return['linkman_tel']['call_out_cnt'];
            $valid_tel=$wada_return['valid'];
            $this->assign('wada_return',$wada_return);
            $this->assign('result',$result);
            $this->assign('casic',$casic);
            $this->assign('hint',$hint);
            /*if($get['xxx']!=1){
                if($credit_arr['credit_data']['zm_score']>750){
                    $this->display('Survey/wanda751');
                }else if($credit_arr['credit_data']['zm_score']>700&&$credit_arr['credit_data']['zm_score']<751){
                    $this->display('Survey/wanda700');
                }else if($credit_arr['credit_data']['zm_score']>599&&$credit_arr['credit_data']['zm_score']<651){
                    $this->display('Survey/wanda600');
                }else if($credit_arr['credit_data']['zm_score']>650&&$credit_arr['credit_data']['zm_score']<701){
                    $this->display('Survey/wanda650');
                }else{
                    $this->display('Survey/wanda');
                }
            }else{*/
                
            /*}*/
        }else{
            die("<script>alert('通讯信息出现异常！');history.back();</script>");
            $this->display('Survey/index');
        }

        $credit_statistics=M('credit_statistics');
        $credit_statistics_data=$credit_statistics->where($where)->find();

        if($credit_statistics_data){
            $credit_statistics_save['huabei_lines']=$taobao_arr['personalInfo']['huabeiTotalAmount'];
            $credit_statistics_save['zm_score']=$credit_arr['credit_data']['zm_score'];

            $credit_statistics_save['tel_time']=$tel_time;
            $credit_statistics_save['valid_tel']=$valid_tel;
            $credit_statistics_save['interflow_tel']=$interflow_tel;
            $credit_statistics_save['year_expense']=$taobao_arr['lost_money'];
            $credit_statistics_save['urgency_tel']=$urgency_tel;
            $credit_statistics_save['tel_valid_time']=$tel_valid_time;
            $credit_statistics->where($where)->save($credit_statistics_save);

        }else{
            $credit_statistics_save['user_id']=$user_data['user_id'];
            $credit_statistics_save['user_name']=$user_data['user_name'];

            $credit_statistics_save['huabei_lines']=$taobao_arr['personalInfo']['huabeiTotalAmount'];
            $credit_statistics_save['zm_score']=$credit_arr['credit_data']['zm_score'];

            $credit_statistics_save['tel_time']=$tel_time;
            $credit_statistics_save['valid_tel']=$valid_tel;
            $credit_statistics_save['interflow_tel']=$interflow_tel;
            $credit_statistics_save['year_expense']=$taobao_arr['lost_money'];
            $credit_statistics_save['urgency_tel']=$urgency_tel;
            $credit_statistics_save['tel_valid_time']=$tel_valid_time;
            $credit_statistics_save['create_time']=time();
            $credit_statistics->add($credit_statistics_save);
        }

        $this->tel_valid_time=$tel_valid_time;
        if($request_id==36 || $tel_record_data['request_id']==''){
            $this->display('Survey/index');
        }else if($request_id==40){
            $this->display('Survey/wanda');
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



/*   借款记录   */
    public function record($where){
        $record=M('record');
        $record_data=$record->where($where)->order('record_id desc')->select();
        foreach ($record_data as $key => $value) {
            $borrow+=1;
            if($value['loan_time']==1){
                   $loan_time=7;
            }else if($value['loan_time']==2){
                 $loan_time=14;
            }
            $be_overdue=RepayModel::be_overdue($value['pay_time'],$loan_time,$value['xutime'],$value['pay_money'],$value['repayment_time']);
            if($key==0){
                if($be_overdue['day']>0){
                    $arr['frist_overdue']="最后一次逾期";
                }else{
                    $arr['frist_overdue']="最后一次未逾期";
                }
            }
            if($be_overdue['overdue_money']>0){
            $new_record_data[$key]['repayment_time']=$value['repayment_time'];
            $new_record_data[$key]['should_time']=$be_overdue['time'];
            $new_record_data[$key]['day']=$be_overdue['day'];
            $new_record_data[$key]['overdue_money']=$be_overdue['overdue_money'];
            }
        }
        $arr['borrow']=$borrow;
        $arr['new_record_data']=$new_record_data;
        return $arr;
    }
/*  征信信息*/
    public function credit($get,$where){
        $credit=M('credit');
        $credit_where['cuser_name']=$get['id'];
        $credit_data=$credit->where($credit_where)->order('create_time desc')->find();
        $match=SurveyModel::four_ele($credit_data);
        $ele=SurveyModel::two_ele($credit_data);


        $clan=SurveyModel::clan($credit_data);

        if($credit_data['clan_name_a']==''){
            $clan=1;    
        }

        

        if($where['user_name']!=$credit_data['cbank_tel']){
            $three_ele=SurveyModel::three_ele($credit_data);
        }

        if($credit_data['hit_detail']!=''){
            $hit_detail=SurveyModel::hit_detail($credit_data['hit_detail']);
        }

        $arr['clan']=$clan;
        $arr['hit_detail']=$hit_detail;
        $arr['three_ele']=$three_ele;
        $arr['ele']=$ele;
        $arr['match']=$match;
        $arr['credit_data']=$credit_data;
        return $arr;
    }

/*  淘宝信息  */
    public function taobao($where,$user_data,$get){
        $taobao=M('taobao');
        $taobao_data=$taobao->where($where)->find();

        if($taobao_data['token']){

            $taobao_info=M('taobao_info');
            $taobao_info_data=$taobao_info->where($where)->find();
            if($taobao_info_data){
                $accountSafeInfo=json_decode($taobao_info_data['accountsafeinfo'],1);
                $personalInfo=json_decode($taobao_info_data['personalinfo'],1);
                $addrs=json_decode($taobao_info_data['addrs'],1);
                foreach ($addrs as $key => $value) {
                    $u_name=mb_substr($value,0,mb_strlen($user_data['u_name'],'utf-8'),'utf-8');
                    $addrs_tel=substr($value,-11,2).substr($value,-2);
                    $u_tel=substr($get['id'],0,2).substr($get['id'],-2);
                    if($addrs_tel==$u_tel||$user_data['u_name']==$u_name){
                        $addrs_new[$key]=$value;
                    }
                }
                $bindAccountInfo=json_decode($taobao_info_data['bindaccountinfo'],1);
                $orderlist=json_decode($taobao_info_data['orderlist'],1);
                $arr['bindAccountInfo']=$bindAccountInfo;
                $arr['arr']=$orderlist;
                $arr['addrs']=$addrs_new;
                $arr['lost_money']=$taobao_info_data['money'];
                $arr['accountSafeInfo']=$accountSafeInfo;
                $arr['personalInfo']=$personalInfo;
            }else{
                $apix_taobao=ApixModel::apix_taobao($taobao_data['token']);
                if($apix_taobao==''){
                     die("<script>alert('该用户淘宝数据未抓取到！');history.back();</script>");
                }


                $accountSafeInfo=$apix_taobao['accountSafeInfo'];
                $addrs=$apix_taobao['addrs'];
                $bindAccountInfo=$apix_taobao['bindAccountInfo'];
                $orderList=$apix_taobao['orderList'];
                $personalInfo=$apix_taobao['personalInfo'];
                $mytime=(int)date("Ymd", strtotime("-1 year"));
                foreach ($orderList as $key => $value) {
                    if($value['orderStatus']=='交易成功'){
                        $date=(int)str_replace('-','',$value['businessDate']);
                        if($date>=$mytime){
                            $lost_money+=$value['orderTotalPrice'];
                            $i+=1;
                                if($i<=30){
                                    $orderl[$key]['businessDate']=$value['businessDate'];
                                    $orderl[$key]['orderProducts']=$value['orderProducts'];
                                    $orderl[$key]['orderStatus']=$value['orderStatus'];
                                    $orderl[$key]['orderTotalPrice']=$value['orderTotalPrice'];
                                    $orderl[$key]['orderid']=$value['orderid'];
                                }
                        }

                    }
                }
                $save['accountSafeInfo']=json_encode($accountSafeInfo);
                $save['bindAccountInfo']=json_encode($bindAccountInfo);
                $save['personalInfo']=json_encode($personalInfo);
                $save['addrs']=json_encode($addrs);
                $save['orderList']=json_encode($orderl);
                $save['user_name']=$where['user_name'];
                $save['create_time']=json_encode($mytime);
                $save['money']=$lost_money;

                $res=$taobao_info->add($save);
                if($res){
                }else{
                    $t_hint="淘宝数据储存失败";
                }
                foreach ($addrs as $key => $value) {
                    $u_name=mb_substr($value,0,mb_strlen($user_data['u_name'],'utf-8'),'utf-8');
                    $addrs_tel=substr($value,-11,2).substr($value,-2);
                    $u_tel=substr($get['id'],0,2).substr($get['id'],-2);
                    if($addrs_tel==$u_tel||$user_data['u_name']==$u_name){
                        $addrs_new[$key]=$value;
                    }
                }
                $arr['bindAccountInfo']=$bindAccountInfo;
                $arr['arr']=$orderl;
                $arr['addrs']=$addrs_new;
                $arr['lost_money']=$lost_money;
                $arr['accountSafeInfo']=$accountSafeInfo;
                $arr['personalInfo']=$personalInfo;
            }
            $arr['t_hint']=$t_hint;
        }else{
            // die("<script>alert('未获取到淘宝信息！');history.back();</script>");
            return $arr;
        }
        return $arr;
    }

    public function to_excel(){ 
        $get=I('get.');
        $where['user_name']=$get['id'];
        $user_model=M('user');
        $loan=M('loan');
        $user_data = $user_model->where($where)->find();
        //var_dump($user_data);exit;
        $loan_data = $loan->where($where)->find();
        $credit=M('credit');
        $credit_where['user_id']=$user_data['user_id'];
        $credit_data=$credit->field('zm_score')->where($credit_where)->order('create_time desc')->find();
        $credit_statistics=M('credit_statistics');
        $credit_statistics_data=$credit_statistics->where($where)->find();
        $record_arr=$this->record($where);
        if($loan_data['loan_time']==1){
            $loan_time=7;
        }else if($loan_data['loan_time']==2){
            $loan_time=14;
        }
        $time=$loan_data['loan_request']+$loan_time*86400;
        $shouxufei=MoneyModel::shouxufei($loan_data['loan_time'],$loan_data['loan_amount']);
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();



        //set width  
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(16);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(24);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(24);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(24);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(24);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(24);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(24);  
         

        //设置行高度  
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);  
        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(24);  
        $objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(24);
        $objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(30);
        $objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(24);
        $objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(30);
        $objPHPExcel->getActiveSheet()->getRowDimension('7')->setRowHeight(30);
        $objPHPExcel->getActiveSheet()->getRowDimension('8')->setRowHeight(30);
        $objPHPExcel->getActiveSheet()->getRowDimension('9')->setRowHeight(30);
        $objPHPExcel->getActiveSheet()->getRowDimension('10')->setRowHeight(24);
        $objPHPExcel->getActiveSheet()->getRowDimension('11')->setRowHeight(24);

        //set font size bold  
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(11);  
        $objPHPExcel->getActiveSheet()->getStyle('A1:A10')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A10:G10')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B6:B10')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('D6:D10')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('F6:F10')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B4')->getFont()->setBold(true); 
        $objPHPExcel->getActiveSheet()->getStyle('E4')->getFont()->setBold(true); 
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
         
  
        //设置水平居中  
        $objPHPExcel->getActiveSheet()->getStyle('A1:G11')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        
        //设置垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A1:G11')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        
        
        //合并cell  
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');  
        $objPHPExcel->getActiveSheet()->mergeCells('A2:A3');
        $objPHPExcel->getActiveSheet()->mergeCells('B4:C4');
        $objPHPExcel->getActiveSheet()->mergeCells('E4:F4');
        $objPHPExcel->getActiveSheet()->mergeCells('A10:A11');
        $objPHPExcel->getActiveSheet()->mergeCells('A6:A7');
        $objPHPExcel->getActiveSheet()->mergeCells('A8:A9');
        $objPHPExcel->getActiveSheet()->mergeCells('D2:E2');
        $objPHPExcel->getActiveSheet()->mergeCells('F2:G2');
        $objPHPExcel->getActiveSheet()->mergeCells('D3:E3');
        $objPHPExcel->getActiveSheet()->mergeCells('F3:G3');
        $objPHPExcel->getActiveSheet()->mergeCells('B9:E9');
        $objPHPExcel->getActiveSheet()->mergeCells('F9:G9');

        // set table header content  
        $objPHPExcel->setActiveSheetIndex(0)  
            ->setCellValue('A1', '借款审批表    编号：'.$loan_data['loan_order'])  
            ->setCellValue('A2', '基本信息')  
            ->setCellValue('A6', '万达/Apix') 
            ->setCellValue('A8', '淘宝/京东') 
            ->setCellValue('B2', '手机号') 
            ->setCellValue('B3', $user_data['user_name']) 
            ->setCellValue('B4', '身份证正面、反面、手持正面照片') 
            ->setCellValue('B5', $credit_data['zm_score']) 
            ->setCellValue('B6', '有微信昵称') 
            ->setCellValue('B7', '紧急联系人通话次数') 
            ->setCellValue('B8', '近两个月有消费记录') 
            ->setCellValue('B9', '家庭、工作至少有一条在淘宝收货地址中（具体到街道）') 
            ->setCellValue('B11', $loan_data['loan_amount'])
            ->setCellValue('C11', $loan_time)
            ->setCellValue('D11', date("Y-m-d",$time))
            ->setCellValue('E11', $shouxufei)
            ->setCellValue('F11', $loan_data['loan_amount']+$shouxufei)
            ->setCellValue('G11', $record_arr['borrow'])
            ->setCellValue('D6', '通话记录有效号码个数') 
            ->setCellValue('D7', '贷款被叫次数')
            ->setCellValue('D8', '近一年消费金额')
            ->setCellValue('F6', '在网时长') 
            ->setCellValue('F7', '异常通话记录')
            ->setCellValue('F8', '三电话一致') 
            ->setCellValue('E4', '身份证信息一致') 
            ->setCellValue('E6', $credit_statistics_data['valid_tel']) 
            ->setCellValue('E8', $credit_statistics_data['year_expense'])
            ->setCellValue('C2', '姓名') 
            ->setCellValue('C7', $credit_statistics_data['urgency_tel']) 
            ->setCellValue('C3', $user_data['u_name'])
            ->setCellValue('D2', '身份证号') 
            ->setCellValue('D3', " ".$user_data['identity'])
            ->setCellValue('F2', '银行卡号') 
            ->setCellValue('F3', " ".$user_data['bank_card'])
            ->setCellValue('A10', '要素审核') 
            ->setCellValue('B10', '借款金额（元）')  
            ->setCellValue('C10', '借款期限（天）')  
            ->setCellValue('D10', '应还时间')  
            ->setCellValue('E10', '综合费用（元）')  
            ->setCellValue('F10', '应还金额（元）')  
            ->setCellValue('G10', '借款次数（次）')
            ->setCellValue('G6', $credit_statistics_data['tel_time']) 
            ->setCellValue('A5', '芝麻分')
            ->setCellValue('A4', '身份证照片');
      

        //设置名字
        $fileName="".$user_data['u_name']."_".$credit_data['zm_score']."_".$loan_data['loan_num']." .xls";
        $fileName = iconv("utf-8", "gb2312", $fileName);
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;
    }

    public function inspectLinkman($user_name){
        $user_model = M('User');
        $loan_model = M('Loan');
        $map['user_name'] = $user_name;
        $loan_info = $loan_model->where($map)->field('is_pay,loan_time,renewal_days,loan_amount')->find();
        // dump($loan_info);
        if($loan_info){
            if($loan_info['loan_time'] == 1){
                $loan_time = 7;
            }elseif($loan_info['loan_time'] == 2){
                $loan_time = 14;
            }

            if($loan_info['is_pay']<1){
                return "没有借款";
            }else{
                $return_day = RepayModel::overdue_show($loan_info['is_pay'],$loan_time,$loan_info['renewal_days'],$loan_info['loan_amount']); 
                if($return_day['day'] >0){
                    return "已逾期".$return_day['day']."天";
                }else{
                    return "未逾期";
                }
            }
            
        }else{
            return "未找到此人";
        }
        

    }
}